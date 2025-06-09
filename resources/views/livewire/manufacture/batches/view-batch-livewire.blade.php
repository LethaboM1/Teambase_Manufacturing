
<div class="row">
	<div class="col-lg-12 mb-3">
		<form method="post" >
			<x-alert-messages />
			@csrf
			<section class="card">
				<header class="card-header">
					<h2 class="card-title">Edit Batch</h2>
				</header>								
				<div class="card-body">					
					<h3>Batch No. {{$batch->batch_number}}</h3>	
					<div class="row">
						<div class="col-sm-12 col-md-12 pb-sm-3 pb-md-0">
							<h3>Product</h3>
						</div>
						<div class="col-sm-12 col-md-3 pb-sm-3 pb-md-0">
							<label class="col-form-label" for="formGroupExampleInput">Description</label>
							<h4>{{$batch_product->name}}</h4>
						</div>
						<div class="col-sm-12 col-md-3 pb-sm-3 pb-md-0">
							<label class="col-form-label" for="formGroupExampleInput">Quantity</label>
							{{-- <h4>{{$batch->qty}}</h4> new QTY changes on View 2023-10-19 --}}
							<input wire:model.lazy="qty" type="number" name="qty" placeholder=""
								class="form-control" value="{{$qty}}">
						</div>
						<div class="col-sm-12 col-md-3 pb-sm-3 pb-md-0">
							<label class="col-form-label" for="formGroupExampleInput">Unit</label>
							<h4>{{strtoupper($batch_product->unit_measure)}}</h4>
						</div>
					</div>
					<div class="row">						
						<div class="col-sm-12 col-md-3 pb-sm-3 pb-md-0">						
							<x-form.select wire:model="status" name="status" label="Status" :list="$status_list" />
						</div>						
						<div class="col-sm-12 col-md-12 pb-sm-3 pb-md-0">						
							<x-form.textarea wire:model.debounce="notes" name="notes" label="Notes" />
						</div>
					</div>
					<div class="row">						
						<div class="col-sm-12 col-md-3 pb-sm-3 pb-md-0">							
							@if (Auth::user()->getSec()->getCRUD('production_crud')['update'] || Auth::user()->getSec()->global_admin_value)
								<button type="button"
									class="btn btn-primary m-2"
									wire:click="save_batch"
									@if (($changed == 0) || $batch['status'] == 'Completed' || $batch['status'] == 'Ready for dispatch') disabled="disabled" @endif>Save Batch
								</button>
							@endif                                
						</div>										
					</div>
					<hr>
					<div class="row">
						<h3>Recipe</h4>
						<table width="100%" class="table table-responsive-md mb-0">
							<thead>
								<tr>
									<th style="width:150px">Status</th>
									<th style="width:100px">Quantity</th>
									<th style="width:100px">In Stock</th>
									<th>Code</th>
									<th>Product.</th>
								</tr>
							</thead>
							<tbody>
								@if(!empty($recipe))
									@foreach($recipe as $product)
										{{-- <livewire:manufacture.batches.recipe.item key="{{$product['id']}}" :product="$product" :qtyselected="$batch->qty" > new QTY changes on View 2023-10-19 --}}
										<livewire:manufacture.batches.recipe.item key="{{$product['id']}}" :product="$product" :qtyselected="$qty" >
									@endforeach
								@else
										<tr>
											<td colspan="3">Nothing selected</td>
										</tr>
								@endif
							</tbody>
						</table>
					</div>					
				</div>
				<footer class="card-footer text-end">
					&nbsp;
				</footer>
			</section>
		</form>
	</div>
</div>