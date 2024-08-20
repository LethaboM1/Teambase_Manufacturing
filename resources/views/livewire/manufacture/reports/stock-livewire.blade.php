<div class="row">
    <div class="header-right col-lg-12 col-md-4">
        <div class="row">
            <div class="col-md-6">
                <h3>Print Stock Transactions over a Date Range</h3>
            </div>
            <div class="col-md-3">
                <button wire:click='resetForm' class="btn btn-danger btn-sm mt-3 pull-right">Reset</button>
            </div>
        </div>
        <form method="post" action="{{url('report/stock-reports/print')}}">
            @csrf            
            <x-form.hidden wire=0 name="supplier_name_filter" value="{{$supplier_name_filter}}"/> 
            <x-form.hidden wire=0 name="ref_number_filter" value="{{$ref_number_filter}}"/>           
            <x-form.hidden wire=0 name="product_description_filter" value="{{$product_description_filter}}"/>
            <div class="row">
                <div class="col-md-3 pb-sm-3 pb-md-0">					
                    <x-form.select label="Stock List Category" name="stock_report_category" :list="$stock_report_category_list" />                        
                </div>
                <div class="col-md-3 pb-sm-3 pb-md-0">
                    <x-form.date name="from_date" value="" label="From" />
                </div>
                <div class="col-md-3 pb-sm-3 pb-md-0">
                    <x-form.date name="to_date" value="" label="To" />
                </div>
            </div>
            {{-- <div class="row">                
            </div> --}}
            <div class="row">
                <div class="col-md-12 pb-sm-3 pb-md-0">
                    <hr>    
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 pb-sm-3 pb-md-0">
                    <x-form.checkbox name="extra_criteria" value="" label="Additional Filter Criteria" disabled="{{!$extra_criteria_enabled}}"/>
                </div>                
            </div>      
            
            <div class="row">                                             
                <div class="col-md-3 pb-sm-3 pb-md-0">
                    <x-form.select name="supplier_name_filter" value="" label="Supplier Name" disabled="{{!$extra_criteria}}" :list="$stock_report_supplier_list"/>
                </div>
                <div class="col-md-3 pb-sm-3 pb-md-0">
                    <x-form.select name="ref_number_filter" value="" label="Reference" disabled="{{!$extra_criteria}}" :list="$stock_report_reference_list"/>
                </div> 
                <div class="col-md-3 pb-sm-3 pb-md-0">
                    <x-form.select name="product_description_filter" value="" label="Product Description" disabled="{{!$extra_criteria}}" :list="$stock_report_product_list"/>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 pb-sm-3 pb-md-0">
                    <br>    
                </div>
            </div>

            <div class="row">
                <div class="form-group pb-4">
                    <div class="col-lg-6">
                        <label>Report Format</label><br>
                        <div class="radio">                        
                            <label><input type="radio" name="stock_report_format" value='flattened'
                                    wire:model="stock_report_format">&nbsp;&nbsp;Flattened (Summarised)</label>&nbsp;&nbsp;
                            <label><input type="radio" name="stock_report_format" value='transactional' checked=""
                                    wire:model="stock_report_format">&nbsp;&nbsp;Transactional (Detailed)</label>&nbsp;&nbsp;
                        </div>
                    </div>
                </div>
            </div>            
            
            <div class="row">
                @if($stock_report_format !='flattened')
                    <div class="form-group row pb-4" style="margin-bottom: 15px;">
                        <div class="col-lg-6">
                            <label>Group Results By</label><br>
                            <div class="radio">                        
                                <label><input type="radio" name="stock_report_group_by" value='supplier'
                                        wire:model="stock_report_group_by">&nbsp;&nbsp;Supplier</label>&nbsp;&nbsp;
                                <label><input type="radio" name="stock_report_group_by" value='reference' checked=""
                                        wire:model="stock_report_group_by">&nbsp;&nbsp;Reference</label>&nbsp;&nbsp;                        
                                <label><input type="radio" name="stock_report_group_by" value='product'
                                        wire:model="stock_report_group_by">&nbsp;&nbsp;Product</label>&nbsp;&nbsp;
                                <label><input type="radio" name="stock_report_group_by" value='none'
                                        wire:model="stock_report_group_by">&nbsp;&nbsp;None</label>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            

            <div class="row">
                <div class="col-md-6 pb-sm-3 pb-md-0">
                    <br>    
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 pb-sm-3 pb-md-0">
                    <strong>Summary:</strong>
                    <br>
                    <em>
                        @if ($stock_report_category != '0'&& isset($from_date) && isset($to_date))
                            Select {{$stock_report_category}} from Stock Transactions between {{$from_date}} and {{$to_date}}
                            @if($primary_filter != '')
                                 where {{$primary_filter_column}} is like `{{$primary_filter_text}}`
                                @if($secondary_filter != '')
                                    and {{$secondary_filter_column}} is like `{{$secondary_filter_text}}`
                                    @if($tertiary_filter != '')
                                        and {{$tertiary_filter_column}} is like `{{$tertiary_filter_text}}`                                        
                                    @endif
                                @endif
                            @endif
                            @if($stock_report_format != 'flattened')
                                - Report Format : Transactional, Grouped by {{$stock_report_group_by}}
                            @else
                                - Report Format : Summarised.
                            @endif
                        @else
                            ...no criteria selected                            
                        @endif
                    </em>    
                </div>
            </div>

            <button class="btn btn-primary mt-2" type="submit">Print</button>                     
            
        </form>
         
    </div>
</div>