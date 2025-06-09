<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //update missing weight_out_datetime, status & type fields fields on records
        $backup_transactions = DB::statement('CREATE TABLE manufacture_product_transactions_backup like manufacture_product_transactions');
        $backup_transactions = DB::statement('INSERT INTO manufacture_product_transactions_backup SELECT * FROM manufacture_product_transactions');
        
        if($backup_transactions){
            echo 'Backup Success :'. ($backup_transactions=='1' ? 'true':'false') . ', ';
            $update_dates = DB::unprepared( DB::raw("update manufacture_product_transactions set weight_out_datetime=created_at where type is not NULL and (weight_out_datetime is NULL or weight_out_datetime = '') and status <> 'Loading'  ") );
            echo 'Updated Dates :'. ($update_dates=='1' ? 'true':'false') . ', ';
            $update_dates_extra = DB::unprepared( DB::raw("update manufacture_product_transactions set weight_out_datetime=weight_in_datetime where type is NULL and (weight_out_datetime is NULL or weight_out_datetime = '') and status <> 'Loading'  ") );
            echo 'Updated Dates Extra :'. ($update_dates_extra=='1' ? 'true':'false') . ', ';
            $update_status = DB::unprepared( DB::raw("update manufacture_product_transactions set status='Completed' where type is not NULL and (status is NULL or status = '') ") );
            echo 'Updated Status :'. ($update_status=='1' ? 'true':'false') . ', ';
            $update_types_jobcards = DB::unprepared( DB::raw("update manufacture_product_transactions set type='JDISP' where (type is NULL or type ='') and manufacture_jobcard_product_id <> '0' and status = 'Dispatched'") );
            echo 'Updated Jobcards :'. ($update_types_jobcards=='1' ? 'true':'false') . ', ';
            $update_types_customers = DB::unprepared( DB::raw("update manufacture_product_transactions set type='CDISP' where (type is NULL or type ='') and manufacture_jobcard_product_id = '0' and status = 'Dispatched'") );
            echo 'Updated Customers :'. ($update_types_customers=='1' ? 'true':'false') . ', ';
            $update_dates = DB::unprepared( DB::raw("update manufacture_product_transactions set weight_out_datetime=created_at where type is not NULL and (weight_out_datetime is NULL or weight_out_datetime = '') and status <> 'Loading'  ") );
            echo 'Updated Dates :'. ($update_dates=='1' ? 'true':'false') . ', ';
        } else {
            echo 'Backup Success :' . ($backup_transactions=='1' ? 'true':'false');
        }        

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {        
        //Rollback from Backup
        $rollback = DB::unprepared( DB::raw(" UPDATE `manufacture_product_transactions`
            JOIN `manufacture_product_transactions_backup` ON `manufacture_product_transactions`.`id` = `manufacture_product_transactions_backup`.`id`
            SET `manufacture_product_transactions`.dispatch_id = `manufacture_product_transactions_backup`.dispatch_id,
            `manufacture_product_transactions`.product_id = `manufacture_product_transactions_backup`.product_id,
            `manufacture_product_transactions`.manufacture_jobcard_product_id = `manufacture_product_transactions_backup`.manufacture_jobcard_product_id,
            `manufacture_product_transactions`.type = `manufacture_product_transactions_backup`.type,
            `manufacture_product_transactions`.type_id = `manufacture_product_transactions_backup`.type_id,
            `manufacture_product_transactions`.reference_number = `manufacture_product_transactions_backup`.reference_number,
            `manufacture_product_transactions`.registration_number = `manufacture_product_transactions_backup`.registration_number,
            `manufacture_product_transactions`.weight_out = `manufacture_product_transactions_backup`.weight_out,
            `manufacture_product_transactions`.weight_out_datetime = `manufacture_product_transactions_backup`.weight_out_datetime,
            `manufacture_product_transactions`.weight_out_user = `manufacture_product_transactions_backup`.weight_out_user,
            `manufacture_product_transactions`.weight_in = `manufacture_product_transactions_backup`.weight_in,
            `manufacture_product_transactions`.weight_in_datetime = `manufacture_product_transactions_backup`.weight_in_datetime,
            `manufacture_product_transactions`.weight_in_user = `manufacture_product_transactions_backup`.weight_in_user,
            `manufacture_product_transactions`.qty = `manufacture_product_transactions_backup`.qty,
            `manufacture_product_transactions`.dispatch_temp = `manufacture_product_transactions_backup`.dispatch_temp,
            `manufacture_product_transactions`.comment = `manufacture_product_transactions_backup`.comment,
            `manufacture_product_transactions`.user_id = `manufacture_product_transactions_backup`.user_id,
            `manufacture_product_transactions`.status = `manufacture_product_transactions_backup`.status,
            `manufacture_product_transactions`.updated_at = `manufacture_product_transactions_backup`.updated_at,
            `manufacture_product_transactions`.created_at = `manufacture_product_transactions_backup`.created_at"
        ) );
        echo 'Rollback Success :' . ($rollback=='1' ? 'true':'false');

    }
};
