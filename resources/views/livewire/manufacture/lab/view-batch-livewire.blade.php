<div>
    <section class="card">
        <header class="card-header">
            <h2 class="card-title">Batch Labs</h2>
        </header>
        <div class="card-body">
        <div class="modal-wrapper">
            <div class="modal-text">
                <div class="row">                    
                    <h3>Batch Information</h3>
                    <div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
                        <label class="col-form-label" for="formGroupExampleInput">Batch Number</label>
                        <h4>{{$batch->batch_number}}</h4>
                    </div>
                    
                    <div class="row">
                        
                        <div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
                            <label class="col-form-label" for="formGroupExampleInput">Product Description</label>
                            <h4>{{$product->code}} - {{$product->description}}</h4>
                        </div>
                        <div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
                            <label class="col-form-label" for="formGroupExampleInput">Quantity</label>
                            <h4>{{$batch->qty}}</h4>
                        </div>
                        <div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
                            <label class="col-form-label" for="formGroupExampleInput">Unit</label>
                            <h4>{{strtoupper($product->unit_measure)}}</h4>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <h3>Lab Test Data</h3>
                    
                    <div class="dropdown open m-3">
                        <a class="btn btn-secondary dropdown-toggle" type="button" id="labsDropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                    Actions
                                </a>
                        <div class="dropdown-menu" aria-labelledby="labsDropdown">
                            <a class="dropdown-item modal-basic" href="#addLabTest">Add Lab Test</a>                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-3 pb-sm-3 pb-md-0">
                            <label class="col-form-label" for="formGroupExampleInput">Number of Lab Tests Done</label>
                           <h4>{{$labs->count()}}</h4>
                        </div>    
                    </div>
                    <hr>
                    <table width="100%" class="table table-responsive-md mb-0">
                        <thead>
                            <tr>
                                <th width="20%">Date</th>
                                <th width="15%">Lab Test #</th>
                                <th width="30%"></th>
                                <th width="15%"></th>
                                <th width="20%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($labs->count()>0)
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>
                                    <!-- Modal view batch -->
                                    <a class="mb-1 mt-1 mr-1 modal-basic" href="#modalview" data-bs-toggle="tooltip" data-bs-animation="false" data-bs-placement="top" title="" data-bs-original-title="View Lab"><i
                                    class="fas fa-magnifying-glass"></i></a>
                                    <!-- Modal view batch End -->
                                </td>
                            </tr>

                            @else
                                <tr>
                                    <td colspan="5">No labs done yet...</td>
                                </tr>
                            @endif
                        </tbody>
                        </table>
                </div>
                <livewire:manufacture.lab.add-lab-m-s-f :sammple="$labs->count()+1" :batch="$batch" />
            </div>
        </div>
        </div>
        <footer class="card-footer">
            <div class="row">
            <div class="col-md-12 text-right">
                <button class="btn btn-primary">Close Lab</button>
                <button class="btn btn-default modal-dismiss">Cancel</button>
            </div>
            </div>
        </footer>	
    </section>
</div>	