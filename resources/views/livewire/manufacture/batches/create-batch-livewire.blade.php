
<div class="row">
	<div class="col-lg-12 mb-3">
		<form method="post" >
			@csrf
			<section class="card">
				<header class="card-header">
					<h2 class="card-title">Create New Batch</h2>
				</header>
				<div class="card-body">
				<div class="row">
						<div class="col-sm-12 col-md-12 pb-sm-3 pb-md-0">
							<h3>Product</h3>
						</div>
						<div class="col-sm-12 col-md-3 pb-sm-3 pb-md-0">
							<x-form.select label="Product Description" name="product_id" :list="$products_list" />
						</div>
						<div class="col-sm-12 col-md-3 pb-sm-3 pb-md-0">
							<label>Qty Selected</label>
							<h3>{{$qty_selected}}</h3>
						</div>
						<div class="col-sm-12 col-md-3 pb-sm-3 pb-md-0">
							<label class="col-form-label" for="formGroupExampleInput">Quantity</label>
							<input type="text" name="qty" placeholder=""
								class="form-control">
						</div>
						<div class="col-sm-12 col-md-3 pb-sm-3 pb-md-0">
							
							<x-form.select name="unit" label="Unit" :list="$unit_measure_list" disabled="disabled" />
						</div>
					</div>
					<div class="row">
					<table width="100%" class="table table-responsive-md mb-0">
									<thead>
										<tr>
											<th style="width:150px"></th>
											<th style="width:100px">Quantity</th>
											<th style="width:100px">In Stock</th>
											<th>Code</th>
											<th>Product.</th>
										</tr>
									</thead>
									<tbody>
										@if(!empty($recipe))
											@foreach($recipe as $product)
												<livewire:manufacture.batches.recipe.item key="{{$product['id']}}" :product="$product" :qtyselected="$qty_selected" >
											@endforeach
										@else
												<tr>
													<td colspan="3">Nothing selected</td>
												</tr>
										@endif
									</tbody>
								</table>
					</div>
					<hr>
					<div class="row">
					<table width="100%" class="table table-responsive-md mb-0">
						<thead>
							<tr>
								<th width="5%"><i class="fa fa-check"></i></th>
								<th width="15%">Job Number</th>
								<th width="20%">Customer</th>
								<th width="40%">Product</th>
								<th width="15%">Quantity</th>
							</tr>
						</thead>
						<tbody>										
							@if(!empty($jobcard_list))
								@foreach($jobcard_list as $jobcardproduct)
									<livewire:manufacture.batches.create.job-item key="{{$jobcardproduct['id']}}" :jobcards="$jobcards"  :jobcardproduct="$jobcardproduct" />
								@endforeach
							@else
							<tr>
								<td colspan="5">No jobs for this product</td>
							</tr>

							@endif
						</tbody>
					</table>
					</div>
				</div>
				<footer class="card-footer text-end">
					<button class="btn btn-primary">Create Batch</button>
					<button type="reset" class="btn btn-default">Reset</button>
				</footer>
			</section>
		</form>
	</div>
</div>