<?php

namespace App\Http\Controllers\Manufacture;

use App\Models\User;
use App\Models\Approvals;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Functions;
use App\Models\ManufactureProducts;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\ManufactureProductRecipe;
use App\Http\Controllers\DefaultsController;
use App\Models\ManufactureJobcards;
use App\Models\ManufactureProductTransactions;

class ProductsController extends Controller
{
    public function adjust_product(Request $request)
    {
        // dd($request);
        if($request->request_btn!=null && (Auth::user()->getSec()->product_adjustment_request_value || Auth::user()->getSec()->global_admin_value)){
            //A New Request for Stock Adjustment
            if($request->approval_request=='false'){
                //This is actioned by user with products_adjustment_approve = false and products_adjustment_request = true
                $request->validate([
                    'id' => 'required|exists:manufacture_products',
                    'new_value' => 'required|numeric',
                    'comment' => 'required'
                ]);                

                $product=ManufactureProducts::whereId($request->id)->first();
                $product_description=$product->code.' | '.$product->description;
                    
                //Build Approval Request.
                $approval_request = ['request_type'=>'Stock Adjustment',
                    'request_model'=>'manufacture_products',
                    'request_model_id'=>$request->id,
                    'requesting_user_id'=>Auth::user()->user_id,
                    'approving_user_id'=>'',	
                    'request_detail_array'=> base64_encode(json_encode(['adjust_qty'=>$request->new_value,
                        'adjust_reason'=>$request->comment,]))  
                ]; 
        
                // dd($approval_request);           

                //Insert Approval Request
                Approvals::insert($approval_request);            

                //Get Approval Users
                $approval_users = User::whereIn('user_id', array(DB::raw('select user_id from user_sec_tbl where products_adjustment_approve=1')))->whereActive('1')->get()->toArray();
                
                foreach($approval_users as $user){
                    //SMS Transfer request Notification.
                    if($user['contact_number'] != '') Functions::sms_($user['contact_number'], '['.date("Y-m-d\TH:i").'] Product Adjustment Requested on Product '.$product_description.' by '.Auth::user()->name.' '.Auth::user()->last_name.'. Please review at your earliest convenience.'/* temp removed at '.env('APP_URL','').'/products' */, '', '');
                    
                    if($user['email'] != '')Functions::intmail_($user['email'], 
                    date("Y-m-d\TH:i").': Product Adjustment Requested on Product '.$product_description.' by '.Auth::user()->name.' '.Auth::user()->last_name.'. The Reason for the Request is noted as: "'.$request->comment.'". Please review at your earliest convenience by clicking the link below.',
                    env('MAIL_FROM_ADDRESS', Auth::user()->email), 
                    'Product Adjustment Request - Product '.$product_description,
                    /* temp removed ['link'=>['url'=>env('APP_URL','').'/products',
                    'description'=>'Review Product Adjustment Request on '.$product_description] ]*/);
                }   

                return back()->with('alertMessage', 'Product Adjustment has been requested for Approval.');
            }

        } elseif($request->cancel_request_btn!=null && (Auth::user()->getSec()->product_adjustment_request_value || Auth::user()->getSec()->global_admin_value)){
            //Cancel an Existing Request for Stock Adjustment
            if($request->approval_request=='true'){
                //This is actioned by user with products_adjustment_approve = false and products_adjustment_request = true
                $request->validate([
                    'id' => 'required|exists:manufacture_products',                    
                ]);                                                
                
                //Decode Approval Request Post
                $approval_post = base64_decode($request->approval_post);
                $approval_post = json_decode($approval_post, true); 

                //Cancel (Delete) Approval Request
                Approvals::whereId($approval_post['id'])->delete();                              

                return back()->with('alertMessage', 'Product Adjustment Request has been Cancelled.');
            }

        } elseif($request->approve_request_btn!=null && (Auth::user()->getSec()->product_adjustment_approve_value || Auth::user()->getSec()->global_admin_value)){
            //Approve an Existing Request for Stock Adjustment
            if($request->approval_request=='true'){
                //This is actioned by user with products_adjustment_approve = true and products_adjustment_request = false
                $form_fields=$request->validate([
                    'id' => 'required|exists:manufacture_products',
                    'new_value' => 'required|numeric',
                    'comment' => 'required'                    
                ]);                
                
                //Decode Approval Request Post
                $approval_post = base64_decode($request->approval_post);
                $approval_post = json_decode($approval_post, true);
                $approval_post_detail = base64_decode($approval_post['request_detail_array']);
                $approval_post_detail = json_decode($approval_post_detail, true);
                
                $qty = ManufactureProducts::where('id', $form_fields['id'])->first();
    
                $diff_approved = $form_fields['new_value'] - $qty->qty;
                $diff_requested = $approval_post_detail['adjust_qty'] - $qty->qty;
                $approved_comment = 'Adj Req for Qty: '.$diff_requested.', Apprvd for Qty: '.$diff_approved.'. Note: "'.$request->comment.'".';
        
                ManufactureProductTransactions::insert([
                    'product_id' => $form_fields['id'],
                    'type' => 'ADJ',
                    'weight_out_user' => auth()->user()->user_id,
                    'weight_out_datetime' => date("Y-m-d\TH:i:s"),
                    'status' => 'Completed',
                    'qty' => $diff_approved,
                    'comment' => $approved_comment,
                    'user_id' => auth()->user()->user_id
        
                ]);

                //Approve Approval Request
                Approvals::whereId($approval_post['id'])->update(['declined'=>'0', 'approved'=>'1']);                              

                return back()->with('alertMessage', 'Product Adjustment Request has been Approved.');
            }

        } elseif($request->decline_request_btn!=null && (Auth::user()->getSec()->product_adjustment_approve_value || Auth::user()->getSec()->global_admin_value)){
            //Decline an Existing Request for Stock Adjustment
            if($request->approval_request=='true'){
                //This is actioned by user with products_adjustment_approve = true and products_adjustment_request = false
                $request->validate([
                    'id' => 'required|exists:manufacture_products',                    
                ]);               
                
                //Decode Approval Request Post
                $approval_post = base64_decode($request->approval_post);
                $approval_post = json_decode($approval_post, true); 

                //Decline Approval Request
                Approvals::whereId($approval_post['id'])->update(['declined'=>'1', 'approved'=>'0']);                              

                return back()->with('alertMessage', 'Product Adjustment Request has been Declined.');
            }
            
        } elseif($request->adjust_btn!=null && Auth::user()->getSec()->global_admin_value){
            //Adjust a Product Level without Request. Should onl;y be available to Global Admin            
            $form_fields = $request->validate([
                'id' => 'required|exists:manufacture_products',
                'new_value' => 'required|numeric',
                'comment' => 'required'
            ]);
    
            $qty = ManufactureProducts::where('id', $form_fields['id'])->first();
    
            $diff = $form_fields['new_value'] - $qty->qty;
    
    
            ManufactureProductTransactions::insert([
                'product_id' => $form_fields['id'],
                'type' => 'ADJ',
                'weight_out_user' => auth()->user()->user_id,
                'weight_out_datetime' => date("Y-m-d\TH:i:s"),
                'status' => 'Completed',
                'qty' => $diff,
                'comment' => $form_fields['comment'],
                'user_id' => auth()->user()->user_id
    
            ]);
    
            return back()->with('alertMessage', 'Product Qty has been adjusted.');
        }

    }    

    public function products()
    {
        return view('manufacture.products.list');
    }

    public function add_product(Request $request)
    {
        $form_fields = $request->validate([
            'code' => 'required|unique:manufacture_products',
            'description' => 'required|unique:manufacture_products',
            'opening_balance' => 'nullable',
            'unit_measure' => 'required',
            'has_recipe' => 'nullable',
            // 'weighed_product' => 'nullable',
        ]);

        $form_fields['has_recipe'] = (isset($form_fields['has_recipe']) ? 1 : 0);

        $form_fields['weighed_product'] = DefaultsController::unit_measure_weighed[$form_fields['unit_measure']]; //(isset($form_fields['weighed_product']) ? 1 : 0);

        $opening_balance = (is_numeric($form_fields['opening_balance']) && $form_fields['opening_balance'] > 0) ?  $form_fields['opening_balance'] : 0;

        unset($form_fields['opening_balance']);

        $product_id = ManufactureProducts::insertGetId($form_fields);

        if ($opening_balance) {
            ManufactureProductTransactions::insert([
                'product_id' => $product_id,
                'type' => 'OB',
                'weight_out_user' => auth()->user()->user_id,
                'weight_out_datetime' => date("Y-m-d\TH:i:s"),
                'status' => 'Completed',
                'qty' => $opening_balance,
                'comment' => 'Opening balance',
                'user_id' => auth()->user()->user_id
            ]);
        }

        return back()->with('alertMessage', 'Product has been added');
    }


    public function save_product(Request $request)
    {
        $form_fields = $request->validate([
            'id' => 'exists:manufacture_products',
            'code' => "required|unique:manufacture_products,code,{$request->id}",
            'description' => "required|unique:manufacture_products,description,{$request->id}",
            'unit_measure' => 'required',
            'lab_test' => 'required',
            'has_recipe' => 'nullable',
            'weighed_product' => 'nullable',
        ]);

        $form_fields['has_recipe'] = (isset($form_fields['has_recipe']) ? 1 : 0);

        $form_fields['weighed_product'] = (isset($form_fields['weighed_product']) ? 1 : 0);

        ManufactureProducts::where('id', $form_fields['id'])->update($form_fields);

        return back()->with('alertMessage', 'Product has been saved');
    }

    public function delete_product(Request $request)
    {
        $form_fields = $request->validate([
            'id' => 'required|exists:manufacture_products'
        ]);

        ManufactureProductRecipe::where('product_add_id', $form_fields['id'])->delete();
        ManufactureProductTransactions::where('product_id', $form_fields['id'])->delete();
        ManufactureProducts::where('id', $form_fields['id'])->delete();



        return back()->with('alertMessage', 'Product has been removed.');
    }
}
