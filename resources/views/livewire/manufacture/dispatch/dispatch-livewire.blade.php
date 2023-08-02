
<div class="row">
	<div class="col-lg-12 mb-3">
			<section class="card">
				<header class="card-header">
					<h2 class="card-title">Batch to dispatch</h2>
				</header>
				<div class="card-body">
				<div class="row">
					<div class="col-sm-12 col-md-12 pb-sm-3 pb-md-0">
						<h3>Product</h3>
					</div>
					<div class="col-sm-12 col-md-3 pb-sm-3 pb-md-0">
						{{-- <x-form.select label="Product Description" name="product_id" :list="$products_list" /> --}}
						<label>Product Description</label>
						<h3>{{$batch->product()->code}} {{$batch->product()->description}}</h3>
					</div>
					<div class="col-sm-12 col-md-3 pb-sm-3 pb-md-0">
						<label>Qty</label>
						{{-- <h3>{{$qty_selected}}</h3> --}}
						<h3>{{$batch->qty_left}}</h3>
					</div>
					<div class="col-sm-12 col-md-3 pb-sm-3 pb-md-0">
						<label class="col-form-label" for="formGroupExampleInput">Qty Left</label>
						<h3>{{$qty_left}}</h3>
					</div>
					<div class="col-sm-12 col-md-3 pb-sm-3 pb-md-0">
						<label>Unit</label>
						<h3>{{strtoupper($batch->product()->unit_measure)}}</h3>
						{{-- <x-form.select name="unit" label="Unit" :list="$unit_measure_list" disabled="disabled" /> --}}
					</div>
					
					<hr>
					<div class="row">
						<table width="100%" class="table table-responsive-md mb-0">
							<thead>
								<tr>
									<th width="5%"><i class="fa fa-check"></i></th>
									<th>Allocate</th>
									<th width="20%">Qty Due</th>
									<th width="15%">Job Number</th>
									<th width="20%">Customer</th>
									<th width="30%">Product</th>
								</tr>
							</thead>
							<tbody>										
								@if(!empty($jobcard_list))
									@foreach($jobcard_list as $jobcard_product)
										<livewire:manufacture.dispatch.batch-allocate.job-item :key="$jobcard_product->id" :jobcardproduct="$jobcard_product" :batch="$batch" />
										{{-- <livewire:manufacture.dispatch.create.job-item key="{{$jobcard['id']}}" :jobcards="$jobcards" :jobcard="$jobcard->jobcard()->first()" :product="$jobcard" /> --}}
									@endforeach
								@else
									@if($batch->qty_left==0)
										<tr>
											<td colspan="5"><h3>Batch has nothing left to dispatch</h3></td>
										</tr>
									@else
										<tr>
											<td colspan="5">No jobs for this product</td>
										</tr>										
									@endif

								@endif
							</tbody>
						</table>
						{{(!is_array($jobcard_list)?$jobcard_list->links():"")}}
						{{-- <pre>{{print_r($jobcards,2)}}</pre> --}}
					</div>
					<div class="row">
						<table class="table">
							<thead>
								<tr>
									<td>Dispatch</td>
									<td>Job</td>
									<td>Qty</td>
									<td></td>
								</tr>
							</thead>
							@if($dispatched->count()>0)
								@foreach($dispatched as $dispatch)
									<tr>
										<td style="width:200px">{{$dispatch->dispatch_number}}</td>
										<td style="width:200px">{{$dispatch->jobcard()->jobcard_number}}</td>
										<td>{{$dispatch->qty}}</td>
										<td></td>
									</tr>
								@endforeach
							@else
								<tr>
									<td colspan="4">Nothing has been dispatched</td>
								</tr>
							@endif
						</table>
					</div>
				</div>
				<footer class="card-footer text-end">
					<button wire:click="dispatch" class="btn btn-primary">Create Batch</button>
					<button type="reset" class="btn btn-default">Reset</button>
				</footer>
			</section>
	</div>
</div>