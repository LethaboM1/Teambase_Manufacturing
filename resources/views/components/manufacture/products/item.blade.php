
<tr>
    <td>{{$item->created_at}}</td>
    <td>{{$item->code}}</td>
    <td>{{$item->description}}</td>
    <td>{{$item->qty}}</td>
    <td class="actions">
        <!-- Modal Edit Product -->
        <a class="mb-1 mt-1 mr-1 modal-basic" href="#modalviewProduct" data-bs-toggle="tooltip" data-bs-animation="false" data-bs-placement="top" title="" data-bs-original-title="Edit Product"><i class="fas fa-pencil-alt"></i></a>
 
        <!-- Modal Edit Product -->
        <div id="modalviewProduct" class="modal-block modal-block-lg mfp-hide">
            <section class="card">
                <header class="card-header">
                    <h2 class="card-title">Edit Product</h2>
                </header>
                <form action="products/save" method="post">
                    @csrf
                    <div class="card-body">
                        <div class="modal-wrapper">
                            <div class="modal-text">
                                <div class="row">
                                    <x-form.hidden name="id" :value="$item->id" />
                                    <div class="col-sm-12 col-md-3 pb-sm-3 pb-md-0">                                    
                                        <x-form.input wire=0 name="code" label="Product Code" :value="$item->code" />
                                    </div>
                                    <div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
                                        <x-form.input wire=0 name="description" label="Product Description" :value="$item->description" />
                                    </div>
                                    <div class="col-sm-12 col-md-3 pb-sm-3 pb-md-0">
                                        <label class="col-form-label" for="formGroupExampleInput">Qty</label>
                                        <input type="text" name="openvalue" class="form-control" :value="$item->qty" disabled>
                                    </div>
                                    <div class="col-sm-12 col-md-2 pb-sm-3 pb-md-0">
                                        <x-form.select wire=0 name="unit_measure" label="Unit Measure" :value="$item->unit_measure" :list="$unit_measure_list" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <footer class="card-footer">
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button class="btn btn-primary">Save Product</button>
                                <button class="btn btn-default modal-dismiss">Cancel</button>
                            </div>
                        </div>
                    </footer>
                </form>
            </section>
        </div>
        <!-- Modal Edit Product End -->

        <!-- Modal adjust stock -->
        <a class="mb-1 mt-1 mr-1 modal-basic" href="#modaladjust" data-bs-toggle="tooltip" data-bs-animation="false" data-bs-placement="top" title="" data-bs-original-title="Adjust Stock"><i class="fas fa-chart-simple"></i></a>
        <!-- Modal adjust stock End -->
        <!-- Modal Edit Recipe -->
        <a class="mb-1 mt-1 mr-1 modal-basic" href="#modaladdrecipe" data-bs-toggle="tooltip" data-bs-animation="false" data-bs-placement="top" title="" data-bs-original-title="Add Recipe"><i class="fas fa-list-alt"></i></a>
        <!-- Modal Edit recipe End -->
        <!-- Modal Delete -->
        <a class="mb-1 mt-1 mr-1 modal-basic" href="#modalHeaderColorDanger" data-bs-toggle="tooltip" data-bs-animation="false" data-bs-placement="top" title="" data-bs-original-title="Delete Product"><i class="fas fa-trash-alt"></i></a>
        <!-- Modal Delete End -->
    </td>
</tr>