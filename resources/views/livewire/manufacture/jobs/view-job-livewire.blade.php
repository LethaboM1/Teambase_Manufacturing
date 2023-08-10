<div class="row">
    <div class="col-lg-12 mb-3">
			<section class="card">
				<header class="card-header">
					<h2 class="card-title">Job Card : {{$jobcard['jobcard_number']}} : {{$jobcard['status']}}</h2>
				</header>
				<div class="card-body">					
				{{-- <form wire:submit.prevent="save_jobcard" method="post">			
					@csrf --}}
					<x-form.hidden name="id" :value="$jobcard['id']" />
					<div class="row">
						<div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">					
								<label>Job.</label>
								<h5>{{$jobcard['jobcard_number']}}</h5>
						</div>
						<div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
							@if($jobcard['status']!='Completed' && $jobcard['status']!='Canceled')
								<x-form.input name="jobcard.contractor" label="Contractor" />
							@else
								<label>Contractor</label>
								<h5>{{$jobcard['contractor']}}</h5>
							@endif
						</div>
						<div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
							@if($jobcard['status']!='Completed' && $jobcard['status']!='Canceled')
								<x-form.input name="jobcard.site_number" label="Site Number" />
							@else
								<label>Site Number</label>
								<h5>{{$jobcard['site_number']}}</h5>
							@endif
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
							@if($jobcard['status']!='Completed' && $jobcard['status']!='Canceled')
								<x-form.input name="jobcard.contact_person" label="Contact Person" />
							@else
								<label>Contact Person</label>
								<h5>{{$jobcard['contact_person']}}</h5>
							@endif
						</div>
						<div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
							@if($jobcard['status']!='Completed' && $jobcard['status']!='Canceled')
								<x-form.textarea name="jobcard.delivery_address" label="Delivery Address" />
							@else
								<label>Delivery Address</label>
								<h5>{{$jobcard['delivery_address']}}</h5>
							@endif
						</div>
						<div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
							@if($jobcard['status']!='Completed' && $jobcard['status']!='Canceled')
								<x-form.textarea name="jobcard.notes" label="Notes" />
							@else
								<label>Notes</label>
								<p>{{$jobcard['notes']}}</p>
							@endif
						</div>
					</div>
					<br>
					<div class="row">
						<div class="form-group row pb-4">
							<div class="col-lg-6">
								@if($jobcard['status']!='Completed' && $jobcard['status']!='Canceled')
									<div class="radio">
										<label><input type="radio" wire:model="jobcard.delivery" name="delivery" value=1 checked="">Delivery</label>
										<label><input type="radio" wire:model="jobcard.delivery" name="delivery" value=0>Collection</label>
									</div>
								@else
									<label>Type</label>
									{!!($jobcard['delivery']?"<h5>Delivery</h5>":"<h5>Collection</h5>")!!}
								@endif
							</div>
						</div>
						<div  class="row pb-4">
							<div class="col-lg-6">
								@if($jobcard['status']!='Completed' && $jobcard['status']!='Canceled')
									@if($edit)									
										<button class="btn btn-primary" wire:click="save_jobcard">Save Job Card</button>
									@else																		
										<button class="btn btn-secondary" disabled>Save Job Card</button>
									@endif									
								@endif
								
							</div>
						</div>						
					</div>						
					{{-- </form>			 --}}
					<hr>
					@if($jobcard['status']!='Completed' && $jobcard['status']!='Canceled')
						<div class="row">
							<div class="col-sm-12 col-md-12 pb-sm-3 pb-md-0">
								<h3>Product</h3>
							</div>
							<div class="col-sm-12 col-md-3 pb-sm-3 pb-md-0">
								<x-form.select name="product_id" :list="$product_list" label="Product"/>
							</div>
							<div class="col-sm-12 col-md-3 pb-sm-3 pb-md-0">
								<x-form.number name="qty" label="Qty {{strtoupper($unit_measure)}}" step="0.001" />
							</div>
							<div class="col-sm-12 col-md-3 pb-sm-3 pb-md-0">
								<br>
								<button wire:click="add_product" class="btn  btn-primary"><i class="fa fa-plus"></i></button>
							</div>
						</div>						
					@endif
					<div class="row">
						@if(Session::get('error'))
							<small class="text-danger">{{Session::get('error')}}</small>
						@endif
					</div>
					<table class="table table-hover table-responsive">
						<thead>
							<tr>
								<th>Product</th>
								<th>Qty</th>
								<th>Qty Left</th>
								<th>Filled</th>
								<th style="width:80px">Actions</th>
							</tr>
						</thead>
						@if($products->count()>0)
							@foreach($products as $product)
								<livewire:manufacture.jobs.item key="{{$product['id']}}" :item="$product" />
							@endforeach
						@else
							<tr>
								<td colspan="3">Nothing to list...</td>
							</tr>
						@endif
					</table>
					{{$products->links()}}
				</div>
			</section>
	</div>
</div>
