<div id='addLabTest' class='modal-block modal-block-lg mfp-hide'>
    <form action="{{url("labs/add")}}" method='post' enctype='multipart/form-data'>
        @csrf
        <section class='card'>
            <header class="card-header">
                <div class="row">
                    <div class="col">
                        <h2 class="card-title">Max-viodless Density</h2>
                    </div>
                    <div class="col">
                        <button class="btn btn-danger modal-dismiss float-end">Cancel</button>
                    </div>

                </div>
            </header>
            <div class='card-body'>
                <div class='modal-wrapper'>
                    <div class='modal-text'>
                        <div class="row">
                            
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-12 pb-sm-3 pb-md-0">
                                <h3>Sample {{$sample}}</h3>
                            </div>
                            <div class="col-sm-12 col-md-6 pb-sm-3 pb-md-0">
                                <label class="col-form-label" for="formGroupExampleInput">Contact:</label>
                                <input type="text" name="sample[contact]" placeholder="" class="form-control">
                            </div>
                            <div class="col-sm-12 col-md-6 pb-sm-3 pb-md-0">
                                <label class="col-form-label" for="formGroupExampleInput">Batch No:</label>
                                <x-form.hidden name="sample[type]" value="max-viodless-desity" />
                                <x-form.hidden name="sample[batch_id]" value="{{$batch->id}}" />
                                <x-form.hidden name="sample[sample]" value="{{$sample}}" />
                                <x-form.hidden name="sample[batch_number]" value="{{$batch->batch_number}}" />

                                <h4>{{$batch->batch_number}}</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-6 pb-sm-3 pb-md-0">
                                <label class="col-form-label" for="formGroupExampleInput">Position:</label>
                                <input type="text" name="sample[position]" placeholder="" class="form-control">
                            </div>
                            <div class="col-sm-12 col-md-6 pb-sm-3 pb-md-0">
                                <label class="col-form-label" for="formGroupExampleInput">Sample No:</label>
                                <input type="text" placeholder="" class="form-control" value="{{$sample}}" disabled>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-6 pb-sm-3 pb-md-0">
                                <label class="col-form-label" for="formGroupExampleInput">Time:</label>
                                <input type="datetime-local" name="sample[datetime]" value="{{date("Y-m-d\TH:i")}}" placeholder="" class="form-control">
                            </div>
                            <div class="col-sm-12 col-md-6 pb-sm-3 pb-md-0">
                                <label class="col-form-label" for="formGroupExampleInput">location:</label>
                                <input type="text" name="sample[location]" placeholder="" class="form-control">
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"></div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"></div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"></div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">M1</div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">Mass of Flask Assembly</div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[mask_flask_assembly]" placeholder="" class="form-control"></div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">g</div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">M2</div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">Mass of Flask Assembly + Sample</div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[mass_flask_assembly_sample]" placeholder="" class="form-control"></div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">g</div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"></div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">Mass of Sample</div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[mass_sample]" placeholder="" class="form-control"></div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">g</div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">M3</div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">Mass of Flask Assembly + Sample, Filled with Water</div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[mass_flass_assembly_sample_water]" placeholder="" class="form-control"></div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">g</div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">M4</div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">Mass of Flask Assembly, Filled With Water</div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[mass_flask_assembly_water]" placeholder="" class="form-control"></div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">g</div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">T</div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">Water Temperature</div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[water_temp]" placeholder="" class="form-control"></div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">&deg;C</div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">&rho;w</div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">Density of Water at Test Temperature</div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[density_water_at_test_temp]" placeholder="" class="form-control"></div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">kg/m&#13221;</div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">MVD</div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">Maximum Voidless Density</div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[maximum_voidless_density]" placeholder="" class="form-control"></div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">kg/m&#13221;</div>
                        </div>
                        <br>
                    </div>
                </div>
            </div>
            <footer class="card-footer">
                <div class="row">
                    <div class="col-md-12 text-right">
                        <button class="btn btn-primary">Add Lab</button>
                        <button class="btn btn-default modal-dismiss">Cancel</button>
                    </div>
                </div>
            </footer>
        </section>
    </form>
</div>