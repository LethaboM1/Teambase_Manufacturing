<div class="row">
    <div class="col-lg-12 mb-3">
		<form action="" id="addplant">
			<section class="card">
				<header class="card-header">
					<h2 class="card-title">Add New Job Card</h2>
				</header>
				<div class="card-body">
					<div class="row">
						<div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
							{{-- <label class="col-form-label" for="formGroupExampleInput">Job Number</label>
							<input type="text" name="jobnumber" placeholder="DB01" class="form-control"> --}}
							<x-form.input name="jobcard_number" label="Job Number" />
						</div>
						<div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
							<x-form.input name="contractor" label="Contractor" />
						</div>
						<div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
							<x-form.input name="site_number" label="Site Number" />
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
							<x-form.input name="contact_person" label="Site Number" />
						</div>
						<div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
							<x-form.input name="contact_person" label="Site Number" />
						</div>
						<div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
							<label class="col-form-label" for="formGroupExampleInput">Delivery Address</label>
							<textarea rows="3" type="text" name="hrreading" placeholder="" class="form-control"></textarea>
						</div>
					</div>
					<div class="row">
					<div class="col-sm-12 col-md-12 pb-sm-3 pb-md-0">
							<label class="col-form-label" for="formGroupExampleInput">Notes</label>
							<textarea rows="3" type="text" name="hrreading" placeholder="" class="form-control"></textarea>
						</div>
					</div>
					<br>
					<div class="row">
					<form class="form-horizontal form-bordered" method="get">
						<div class="form-group row pb-4">
							<div class="col-lg-6">
								<div class="radio">
									<label><input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked="">Delivery</label>
									<label><input type="radio" name="optionsRadios" id="optionsRadios2" value="option2">Collection</label>
								</div>
							</div>
						</div>
					</form>
					</div>				
					<hr>
					<div class="row">
						<div class="col-sm-12 col-md-12 pb-sm-3 pb-md-0">
							<h3>Product</h3>
						</div>
						<div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
							<label class="col-form-label" for="formGroupExampleInput">Product Description</label>
							<select class="form-control mb-3">
								<option>Product 1</option>
								<option>Product 2</option>
								<option>Product 3</option>
								<option>Product 4</option>
							</select>	
						</div>
						<div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
							<label class="col-form-label" for="formGroupExampleInput">Quantity</label>
							<input type="text" name="plantNumber" placeholder=""
								class="form-control">
						</div>
						<div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
							<label class="col-form-label" for="formGroupExampleInput">Unit</label>
							<select class="form-control mb-3">
								<option>Kg</option>
								<option>Tons</option>
								<option>Bag</option>
								<option>Liters</option>
							</select>	
						</div>
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
