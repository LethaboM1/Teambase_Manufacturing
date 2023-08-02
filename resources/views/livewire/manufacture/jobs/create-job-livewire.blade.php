<div class="row">
    <div class="col-lg-12 mb-3">
		<form method="post" action="{{url('jobs/create')}}">
			@csrf
			<section class="card">
				<header class="card-header">
					<h2 class="card-title">Add New Job Card</h2>
				</header>
				<div class="card-body">
					<div class="row">
						<div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
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
					<form class="form-horizontal form-bordered" method="get">
						<div class="form-group row pb-4">
							<div class="col-lg-6">
								<div class="radio">
									<label><input type="radio" name="delivery" value=1 checked="">Delivery</label>
									<label><input type="radio" name="delivery" value=0>Collection</label>
								</div>
							</div>
						</div>
					</form>
					</div>	
				</div>
				<footer class="card-footer text-end">
					<button class="btn btn-primary">Create Job Card</button>
					<button type="reset" class="btn btn-default">Reset</button>
				</footer>
			</section>
		</form>
	</div>
</div>
