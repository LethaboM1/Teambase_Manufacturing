<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserSec extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'user_sec_tbl', $primaryKey = 'id', $guard = [], $dates = ['updated_at', 'created_at', 'deleted_at'];
    protected $casts = [
        'created_at'  => 'datetime:Y-m-d H:i:s',
        'updated_at'  => 'datetime:Y-m-d H:i:s',
        'deleted_at'  => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'global_admin',
        'settings_admin',
        'user_profile_crud',
        'user_man_crud',
        'customer_crud',
        'supplier_crud',
        'products_crud',
        'products_adjustment_request',
        'products_adjustment_approve',
        'recipes_crud',
        'jobcards_crud',
        'lab_tests_crud',
        'dispatch_crud',
        'dispatch_transfer_request',
        'dispatch_transfer_approve',
        'dispatch_returns',
        'receive_stock',
        'reports_dispatch',
        'reports_labs',
        'reports_stock',
    ];

    //Single Sec Options - True / False
    function getGlobalAdminValueAttribute()
    {        
        return UserSec::select('global_admin')->where('user_id', Auth::user()->user_id)->where('global_admin','1')->first();
    }

    function getSettingsAdminValueAttribute()
    {        
        return UserSec::select('settings_admin')->where('user_id', Auth::user()->user_id)->where('settings_admin','1')->first();
    }

    function getProductAdjustmentRequestValueAttribute()
    {
        return UserSec::select('products_adjustment_request')->where('user_id', Auth::user()->user_id)->where('products_adjustment_request','1')->first();
    }

    function getProductAdjustmentApproveValueAttribute()
    {        
        return UserSec::select('products_adjustment_approve')->where('user_id', Auth::user()->user_id)->where('products_adjustment_approve','1')->first();
    }

    function getDispatchTransferRequestValueAttribute()
    {
        return UserSec::select('dispatch_transfer_request')->where('user_id', Auth::user()->user_id)->where('dispatch_transfer_request','1')->first();
    }

    function getDispatchTransferApproveValueAttribute()
    {
        return UserSec::select('dispatch_transfer_approve')->where('user_id', Auth::user()->user_id)->where('dispatch_transfer_approve','1')->first();
    }

    function getDispatchReturnsValueAttribute()
    {
        return UserSec::select('dispatch_returns')->where('user_id', Auth::user()->user_id)->where('dispatch_returns','1')->first();
    }

    function getReceiveStockValueAttribute()
    {
        return UserSec::select('receive_stock')->where('user_id', Auth::user()->user_id)->where('receive_stock','1')->first();
    }

    function getReturnStockValueAttribute()
    {
        return UserSec::select('return_stock')->where('user_id', Auth::user()->user_id)->where('return_stock','1')->first();
    }

    function getReportsDispatchValueAttribute()
    {
        return UserSec::select('reports_dispatch')->where('user_id', Auth::user()->user_id)->where('reports_dispatch','1')->first();
    }

    function getReportsLabsValueAttribute()
    {
        return UserSec::select('reports_labs')->where('user_id', Auth::user()->user_id)->where('reports_labs','1')->first();
    }

    function getReportsStockValueAttribute()
    {
        return UserSec::select('reports_labs')->where('reports_stock', Auth::user()->user_id)->where('reports_labs','1')->first();
    }

    //Multi Sec Options C.R.U.D True/False
    function getCRUD($field_name){
        $array = $this->toArray();        
        foreach ($array as $sec_level => $value){
            
            if(str_contains($sec_level, $field_name)){
                //CRUD Sec Item                                
                $return_array = [
                'create' => substr($value,0,1) == '1' ? true:false,
                'read' => substr($value,1,1) == '1' ? true:false,
                'update' => substr($value,2,1) == '1' ? true:false,
                'delete' => substr($value,3,1) == '1' ? true:false];                
            }
        }        

        return  $return_array;
        //Available Fields 2024-07-11
        //user_profile_crud
        //user_man_crud
        //customer_crud
        //supplier_crud
        //products_crud        
        //recipes_crud
        //jobcards_crud
        //lab_tests_crud
        //dispatch_crud        
    }   
            

}
