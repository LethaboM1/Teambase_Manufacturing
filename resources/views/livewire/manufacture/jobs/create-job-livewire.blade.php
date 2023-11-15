<div class="row">
    <div class="col-lg-12 mb-3">
		<form method="post" action="{{url('jobs/create')}}">
			@csrf
			<section class="card">
				<header class="card-header">
					<h2 class="card-title">Add New Job Card</h2>
				</header>
				<div class="card-body">
					{{-- <form class="form-horizontal form-bordered" method="get">
						<div class="form-group row pb-4">
							<div class="col-lg-6">
								<div class="radio">
									<label><input type="radio" name="internal_jobcard" value=1 checked="" wire:model.debounce.500="internal_jobcard">&nbspInternal Jobcard</label>&nbsp
									<label><input type="radio" name="internal_jobcard" value=0 wire:model.debounce.500="internal_jobcard">&nbspExternal Jobcard</label>
								</div>
							</div>
						</div>
					</form> Only Internal Jobs 2023-10-19 --}}

					{{-- If Internal Job, allow manual entry of Details --}}
					
						{{-- <div class="row">
							<div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
								
							</div>
							<div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
								<x-form.input name="site_number" label="Site Number" />
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
								
								<x-form.input name="contact_person" label="Contact Person" value={{$customer_contact}}/>
							</div>
							<div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
								<x-form.textarea name="delivery_address" label="Delivery Address" value={{$customer_address}}/>
							</div>
						</div> --}}
					<div class="row">
						<div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
							{{-- Rather used the one field in the if statement --}}
							{{-- @if($internal_jobcard == 0)
								<x-form.select name="customer_id" label="Customer" :list="$customer_list" />
							@else
								<x-form.input name="contractor" label="Contractor" />
							@endif Only Internal Jobs 2023-10-19 --}}
							<x-form.input name="contractor" label="Contractor" />
						</div>
						<div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
							<x-form.input name="site_number" label="Site Number" />
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
							<x-form.input name="contact_person" label="Contact Person" />
						</div>
						<div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
							<x-form.textarea name="delivery_address" label="Delivery Address" />
						</div>
					</div>

					<div class="row">
						<div class="col-sm-12 col-md-12 pb-sm-3 pb-md-0">
							<x-form.textarea name="notes" label="Notes" />
						</div>
					</div>
					<br>
					<div class="row">
					{{-- <form class="form-horizontal form-bordered" method="get"> --}}
						<div class="form-group row pb-4">
							<div class="col-lg-6">
								<div class="radio">
									<label><input type="radio" name="delivery" value=1 checked="" wire:model.debounce.500="delivery">&nbspDelivery</label>&nbsp
									<label><input type="radio" name="delivery" value=0 wire:model.debounce.500="delivery">&nbspCollection</label>
								</div>
							</div>
						</div>
					{{-- </form> --}}
					</div>	
				</div>
				<footer class="card-footer text-end">
					<button type='submit' class="btn btn-primary">Create Job Card</button>
					<button type="reset" class="btn btn-default">Reset</button>
				</footer>
			</section>
		</form>
	</div>
</div>
