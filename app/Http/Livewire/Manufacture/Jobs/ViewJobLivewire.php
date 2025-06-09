<?php

namespace App\Http\Livewire\Manufacture\Jobs;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Functions;
use App\Models\ManufactureJobcards;
use App\Models\ManufactureProducts;
use App\Models\ManufactureCustomers;
use App\Models\ManufactureJobcardProducts;

class ViewJobLivewire extends Component
{
    use WithPagination;

    public $jobcard, $edit = 0, $unit_measure, $product_list, $product_id, $qty, $original_site_number, $site_number, $site_number_new, $percentage_filled = 0.00,
    $confirmopen, $confirmclose;

    protected $listeners = ['remove_product' => 'rem_product'];

    function mount($job)
    {
        $this->jobcard = ManufactureJobcards::where('id', $job)->first()->toArray();
        $this->site_number = $this->jobcard['site_number'];
        $this->original_site_number = $this->jobcard['site_number'];
        unset($this->jobcard['updated_at']);
        unset($this->jobcard['created_at']);

        $this->product_list = ManufactureProducts::select(DB::raw("concat(code,' - ',description) as name, id as value"))->where('active', 1)->orderBy('code', 'asc')->get()->toArray();
        array_unshift($this->product_list, ['name' => '...', 'value' => 0]);

        $this->unit_measure = '';
        $this->qty = 1;

        $this->confirmclose = false;
        $this->confirmopen = false;
    }

    function rem_product($value)
    {
        ManufactureJobcardProducts::where('id', $value)->delete();
    }

    function updatedJobcard()
    {
        $this->edit = 1;
    }

    function updatedProductId()
    {
        $product = ManufactureProducts::where('id', $this->product_id)->first();
        $this->unit_measure = $product->unit_measure;
    }

    function save_jobcard()
    {
        // dd($this->jobcard);
        ManufactureJobcards::where('id', $this->jobcard['id'])->update($this->jobcard);
        $this->edit = 0;
    }

    function close_jobcard()
    {
        $this->confirmclose = true;
        $this->edit = 0;        
    }

    function confirmed_close_jobcard()
    {
        // dd($this->jobcard);
        $this->jobcard['status'] = 'Completed';
        ManufactureJobcards::where('id', $this->jobcard['id'])->update($this->jobcard);
        $this->confirmclose = false;
        $this->confirmopen = false;
        $this->edit = 0;
    }

    function decline_jobcard_change()
    {
        $this->confirmclose = false;
        $this->confirmopen = false;
        $this->edit = 0;
    }

    function reopen_jobcard()
    {
        $this->confirmopen = true;
        $this->edit = 0;
    }

    function confirmed_reopen_jobcard()
    {
        // dd($this->jobcard);
        $this->jobcard['status'] = 'Open';
        ManufactureJobcards::where('id', $this->jobcard['id'])->update($this->jobcard);
        $this->confirmclose = false;
        $this->confirmopen = false;
        $this->edit = 0;
    }

    function updatedSiteNumber()
    {
        // if($this->site_number !== $this->original_site_number){        
        //     //Validation on Site Number Formatting     
        //     // $current_sites = ManufactureJobcards::select('site_number')->distinct()->get();
        //     $current_sites = ManufactureJobcards::select('site_number', \DB::raw('substr(site_number, 1, 4) as siteno, concat(".",substr(site_number, 6)) as subno'))->distinct()->get();        
            
        //     if($current_sites->where('siteno', substr($this->site_number, 0, 4))->count() > 0){
        //         // dd('we have a double');                        
        //         $siteno=$current_sites->where('siteno', substr($this->site_number, 0, 4))->first()->siteno;
        //         $subno=$current_sites->where('siteno', substr($this->site_number, 0, 4))->sortByDesc('subno')->first()->subno;
                
        //         $subnonext=Functions::incrementSiteSubNo($subno);
        //         $siteno=floatval($siteno+$subnonext);
                
        //         $this->site_number = str_replace('.','/',$siteno);           

        //         // dd('current highest sub:'.$subno.', next no:'.$subnonext);            

        //     } else{
        //         // dd('we do not have a double');
        //         $siteno=substr($this->site_number, 0, 4);
        //         $subno='.'.substr($this->site_number, 5);
                
        //         if($subno == '.00'){
        //             $subnonext=$this->incrementSubNo($subno);
        //             $siteno=floatval($siteno+$subnonext);
        //             $this->site_number = str_replace('.','/',$siteno);
        //         } else {
        //             $subnonext = $subno;
        //             $siteno=floatval($siteno+$subnonext);
        //             $this->site_number = str_replace('.','/',$siteno);
        //         }
                
        //         // dd('current highest sub:'.$subno.', next no:'.$subnonext);
        //     }

        //     //Enable Save                        
        //     $this->edit = 1;
        
        // } Site Number checks removed 2024-03-18

        $siteno=substr($this->site_number, 0, 7);
        $this->site_number = str_replace('.','/',$siteno);

        //Enable Save                        
        $this->edit = 1;
        
        $this->site_number_new=$this->site_number;        
        $this->jobcard['site_number'] = $this->site_number_new;

    }

    // function save_jobcard()
    // {
    //     ManufactureJobcards::where('id', $this->jobcard['id'])->update($this->jobcard);
    //     $this->edit = 0;
    // }

    function add_product()
    {
        if ($this->product_id <= 0) return back()->with('error', 'Choose a product');
        if ($this->qty <= 0)  return back()->with('error', 'Choose a qty');

        $chk = ManufactureJobcardProducts::where('job_id', $this->jobcard['id'])->where('product_id', $this->product_id)->first();

        if (isset($chk) && $chk->count() > 0) {
            $qty = $this->qty +  $chk->qty;            
            //Update Existing Line Qty
            ManufactureJobcardProducts::where('id', $chk->id)->update([
                'qty' => $qty
            ]);

        } else {
            //Insert New Line
            ManufactureJobcardProducts::insert([
                'job_id' => $this->jobcard['id'],
                'product_id' => $this->product_id,
                'qty' => $this->qty
            ]);
           
        }

        //Get Inserted/Updated Record for Qty Calcs
        $chk = ManufactureJobcardProducts::where('job_id', $this->jobcard['id'])->where('product_id', $this->product_id)->first();

        //Calc Filled from Transactions Update Line
        if (($chk->qty_due <= 0.5 && $chk->product()->weighed_product > 0)||($chk->qty_due == 0 && $chk->product()->weighed_product == 0)) {
            ManufactureJobcardProducts::where('id', $chk->id)->update(['filled' => 1]);
        } else {
            ManufactureJobcardProducts::where('id', $chk->id)->update(['filled' => 0]);
        }
        
        //Set job card as Filled/Open based on filled in product lines
        if (ManufactureJobcardProducts::where('job_id', $chk->jobcard()->id)->where('filled', '0')->count() == 0) {
            ManufactureJobcards::where('id', $chk->jobcard()->id)->update(['status' => 'Filled']);
        } else {
            ManufactureJobcards::where('id', $chk->jobcard()->id)->update(['status' => 'Open']);
        }

        $this->product_id = 0;
        $this->qty = 1;
    }

    public function render()
    {
        $customer_list = [];
        $customer_list = ManufactureCustomers::select('id as value', DB::raw("name"))
            ->get()
            ->toArray();


        if (count($customer_list) > 0) {
            array_unshift($customer_list, ['value' => 0, 'name' => 'Select Customer']);
        } else {
            $customer_list = [];
            array_unshift($customer_list, ['value' => 0, 'name' => 'No Customers found...']);
        }

        $products = ManufactureJobcardProducts::where('job_id', $this->jobcard['id'])->paginate(15);
        $products_all = ManufactureJobcardProducts::where('job_id', $this->jobcard['id'])->get();

        //Populate Percentage Filled
        $counter = 0;
        $item_average = 0;        
        foreach ($products_all as $item){
            $counter = $counter+1;
            // dd($counter);
            $item_average = round($item_average + $item->filled_item_percentage, 2);
        }       

        if($counter>0){$this->percentage_filled = round($item_average / $counter, 2);
            // dd($this->percentage_filled);
        }        

        return view('livewire.manufacture.jobs.view-job-livewire', [
            'products' => $products,
            'customer_list' => $customer_list
        ]);
    }
}
