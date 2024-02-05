<div class="row">
    <div class="col-lg-12 mb-3">
		<form method="post" action="{{url('settings/save')}}">
			@csrf
			<section class="card">
				<header class="card-header">
					<h2 class="card-title">Company Settings</h2>
				</header>
				<div class="card-body">
					<x-form.hidden name="settings_rows" value="{{$settings_rows}}" />
                    <x-form.hidden name="company_logo" value="{{$company_logo}}" />
					<div class="row">
						<div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">					
							<x-form.input wire=0 name="trade_name" value="{{!is_null($company_details) ? $company_details['trade_name']:''}}" label="Trade Name" />
						</div>
						<div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
							<x-form.input wire=0 name="reg_no" value="{{!is_null($company_details) ? $company_details['reg_no']:''}}" label="Registration No" />
						</div>
                        <div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
							<x-form.input wire=0 name="vat_no" value="{{!is_null($company_details) ? $company_details['vat_no']:''}}" label="VAT No" />
						</div>
					</div>
					<div class="row">						
						<div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
							<x-form.input wire=0 name="tel_no" value="{{!is_null($company_details) ? $company_details['tel_no']:''}}" label="Telephone No" />
						</div>
                        <div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
							<x-form.input wire=0 name="fax_no" value="{{!is_null($company_details) ? $company_details['fax_no']:''}}" label="Fax No" />
						</div>
						<div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
							<x-form.input wire=0 name="mobile" value="{{!is_null($company_details) ? $company_details['mobile']:''}}" label="Mobile No" />
						</div>
					</div>                    

                    <div class="row">
						<div class="col-sm-12 col-md-6 pb-sm-3 pb-md-0">
							<x-form.input wire=0 name="email" value="{{!is_null($company_details) ? $company_details['email']:''}}" label="Email Address" />
						</div>
                        <div class="col-sm-12 col-md-6 pb-sm-3 pb-md-0">
							<x-form.input wire=0 name="url" value="{{!is_null($company_details) ? $company_details['url']:''}}" label="Web Address" />
						</div>
                    </div>
                    <div class="row">
						
					</div>
                    <div class="row">
						<div class="col-sm-12 col-md-3 pb-sm-3 pb-md-0">
                            <x-form.image label="Company Logo" name="logo" :path="!is_null($company_details)&&$company_details['logo'] !='' ? asset('img/logos/logo/' . $company_details['logo']):''" />                            
						</div>
					</div>

					<div class="row">
						<div class="col-sm-12 col-md-6 pb-sm-3 pb-md-0">
							<x-form.textarea wire=0 name="physical_add" label="Physical Address" value="{{!is_null($company_details) ? $company_details['physical_add']:''}}"/>
						</div>
                        <div class="col-sm-12 col-md-6 pb-sm-3 pb-md-0">
							<x-form.textarea wire=0 name="postal_add" label="Postal Address" value="{{!is_null($company_details) ? $company_details['postal_add']:''}}"/>
						</div>
					</div>
					<br>
						
				</div>
				<footer class="card-footer text-end">
					<button type='submit' class="btn btn-primary">Save Changes</button>
					<button type="reset" class="btn btn-default">Cancel</button>
				</footer>
			</section>
		</form>
	</div>
</div>