<div class="row">
    <div class="header-right col-lg-4 col-md-4">
        <h3>Prints list of Dispatches over date range</h3>
        <form method="post" action="{{url('report/dispatch-reports/print')}}">
            @csrf
            <div class="row">
                <div class="col-md-12 pb-sm-3 pb-md-0">					
                    <x-form.select label="List Dispatches" name="dispatch_report_category" :list="$dispatch_report_category_list" />                        
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 pb-sm-3 pb-md-0">
                    <x-form.date name="from_date" value="" label="From" />
                </div>
                <div class="col-md-6 pb-sm-3 pb-md-0">
                    <x-form.date name="to_date" value="" label="To" />
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 pb-sm-3 pb-md-0">
                    <br>    
                </div>
            </div>


            <button class="btn btn-primary mt-2" type="submit">Print</button>           
            
        </form>
    </div>
</div>