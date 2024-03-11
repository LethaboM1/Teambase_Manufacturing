<div class="row">
    <div class="header-right col-lg-12 col-md-4">
        <div class="row">
            <div class="col-md-6">
                <h3>Prints list of Dispatches over date range</h3>
            </div>
            <div class="col-md-3">
                <button wire:click='resetForm' class="btn btn-danger btn-sm mt-3 pull-right">Reset</button>
            </div>
        </div>
        <form method="post" action="{{url('report/dispatch-reports/print')}}">
            @csrf
            <x-form.hidden wire=0 name="job_number_filter" value="{{$job_number_filter}}"/>
            <x-form.hidden wire=0 name="site_number_filter" value="{{$site_number_filter}}"/>
            <x-form.hidden wire=0 name="ref_number_filter" value="{{$ref_number_filter}}"/>
            <x-form.hidden wire=0 name="customer_name_filter" value="{{$customer_name_filter}}"/>
            <x-form.hidden wire=0 name="account_number_filter" value="{{$account_number_filter}}"/>
            <x-form.hidden wire=0 name="product_description_filter" value="{{$product_description_filter}}"/>
            <div class="row">
                <div class="col-md-3 pb-sm-3 pb-md-0">					
                    <x-form.select label="List Dispatches" name="dispatch_report_category" :list="$dispatch_report_category_list" />                        
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
                    <x-form.select name="job_number_filter" value="" label="Job Number" disabled="{{!$extra_criteria}}" :list="$dispatch_report_jobcard_list"/>
                </div>
                <div class="col-md-3 pb-sm-3 pb-md-0">
                    <x-form.select name="site_number_filter" value="" label="Site Number" disabled="{{!$extra_criteria}}" :list="$dispatch_report_site_list"/>
                </div>
                <div class="col-md-3 pb-sm-3 pb-md-0">
                    <x-form.select name="ref_number_filter" value="" label="Reference" disabled="{{!$extra_criteria}}" :list="$dispatch_report_reference_list"/>
                </div> 
            </div>            
            
            <div class="row">                                             
                <div class="col-md-3 pb-sm-3 pb-md-0">
                    <x-form.select name="customer_name_filter" value="" label="Customer Name" disabled="{{!$extra_criteria}}" :list="$dispatch_report_customer_list"/>
                </div>
                <div class="col-md-3 pb-sm-3 pb-md-0">
                    <x-form.select name="account_number_filter" value="" label="Account Number" disabled="{{!$extra_criteria}}" :list="$dispatch_report_account_list"/>
                </div>
                <div class="col-md-3 pb-sm-3 pb-md-0">
                    <x-form.select name="product_description_filter" value="" label="Product Description" disabled="{{!$extra_criteria}}" :list="$dispatch_report_product_list"/>
                </div>
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
                        @if ($dispatch_report_category != '0'&& isset($from_date) && isset($to_date))
                            Select {{$dispatch_report_category}} from Dispatches between {{$from_date}} and {{$to_date}}
                            @if($primary_filter != '')
                                 where {{$primary_filter_column}} is like `{{$primary_filter_text}}`
                                @if($secondary_filter != '')
                                    and {{$secondary_filter_column}} is like `{{$secondary_filter_text}}`
                                    @if($tertiary_filter != '')
                                        and {{$tertiary_filter_column}} is like `{{$tertiary_filter_text}}`
                                        @if($quaternary_filter != '')
                                            and {{$quaternary_filter_column}} is like `{{$quaternary_filter_text}}`
                                            @if($quinary_filter != '')
                                                and {{$quinary_filter_column}} is like `{{$quinary_filter_text}}`
                                                @if($senary_filter != '')
                                                    and {{$senary_filter_column}} is like `{{$senary_filter_text}}`
                                                @endif
                                            @endif
                                        @endif
                                    @endif
                                @endif
                            @endif
                        @else
                            ...no criteria selected                            
                        @endif
                    </em>    
                </div>
            </div>
            
            {{-- <div class="row">
                <div class="col-md-3 pb-sm-3 pb-md-0">
                    Primary= {{$primary_filter}}    
                </div>
                <div class="col-md-3 pb-sm-3 pb-md-0">
                    PField= {{$primary_filter_column}}    
                </div>
                <div class="col-md-3 pb-sm-3 pb-md-0">
                    PValue= {{$primary_filter_text}}    
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 pb-sm-3 pb-md-0">
                    Secondary= {{$secondary_filter}}    
                </div>
                <div class="col-md-3 pb-sm-3 pb-md-0">
                    SField= {{$secondary_filter_column}}    
                </div>
                <div class="col-md-3 pb-sm-3 pb-md-0">
                    SValue= {{$secondary_filter_text}}    
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 pb-sm-3 pb-md-0">
                    Tertiary= {{$tertiary_filter}}    
                </div>
                <div class="col-md-3 pb-sm-3 pb-md-0">
                    TField= {{$tertiary_filter_column}}    
                </div>
                <div class="col-md-3 pb-sm-3 pb-md-0">
                    TValue= {{$tertiary_filter_text}}    
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 pb-sm-3 pb-md-0">
                    Quaternary= {{$quaternary_filter}}    
                </div>
                <div class="col-md-3 pb-sm-3 pb-md-0">
                    QField= {{$quaternary_filter_column}}    
                </div>
                <div class="col-md-3 pb-sm-3 pb-md-0">
                    QValue= {{$quaternary_filter_text}}    
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 pb-sm-3 pb-md-0">
                    Quinary = {{$quinary_filter}}    
                </div>
                <div class="col-md-3 pb-sm-3 pb-md-0">
                    QnField= {{$quinary_filter_column}}    
                </div>
                <div class="col-md-3 pb-sm-3 pb-md-0">
                    QnValue= {{$quinary_filter_text}}    
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 pb-sm-3 pb-md-0">
                    Seniary= {{$senary_filter}}    
                </div>
                <div class="col-md-3 pb-sm-3 pb-md-0">
                    SeField= {{$senary_filter_column}}    
                </div>
                <div class="col-md-3 pb-sm-3 pb-md-0">
                    SeValue= {{$senary_filter_text}}    
                </div>
            </div> --}}


            <button class="btn btn-primary mt-2" type="submit">Print</button>                     
            
        </form>
         
    </div>
</div>