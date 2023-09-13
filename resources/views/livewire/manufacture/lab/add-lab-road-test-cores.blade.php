<div id='addLabTestRCT' class='modal-block modal-block-lg mfp-hide'>
    <form action="{{url("labs/add")}}" method='post' enctype='multipart/form-data'>
        @csrf
        <section class='card'>
            <header class="card-header">
                <div class="row">
                    <div class="col">
                        <h2 class="card-title">Road Test Core</h2>
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
                            <div class="col-sm-12 col-md-12 pb-sm-3 pb-md-0">
                                <h3>Sample {{$sample}}</h3>
                            </div>
                            <div class="col-sm-12 col-md-6 pb-sm-3 pb-md-0">
                                <label class="col-form-label" for="formGroupExampleInput">Contact:</label>
                                <input type="text" name="contact" placeholder="" class="form-control">
                            </div>
                            <div class="col-sm-12 col-md-6 pb-sm-3 pb-md-0">
                                <label class="col-form-label" for="formGroupExampleInput">Batch No:</label>
                                <x-form.hidden name="sample[type]" value="road-test-cores" />
                                <x-form.hidden name="sample[batch_id]" value="{{$batch->id}}" />
                                <x-form.hidden name="sample[sample]" value="{{$sample}}" />
                                <x-form.hidden name="sample[batch_number]" value="{{$batch->batch_number}}" />
                                <h4>{{$batch->batch_number}}</h4>
                            </div>
                            <div class="col-sm-12 col-md-6 pb-sm-3 pb-md-0">
                                <label class="col-form-label" for="formGroupExampleInput">Type of Ashphalt:</label>
                                <input type="text" name="batch" placeholder="" class="form-control">
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
                        <div class="row">
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">Sample Number</div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">Lane</div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">Chainage</div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">Position</div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">Thickness / Height (mm) 1</div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">Thickness / Height (mm) 2</div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">Thickness / Height (mm) 3</div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">Thickness / Height (mm) Avarage</div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">Mass in Air (g)</div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">Mass in Water (g)</div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">Mass (Dry) (g)</div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">Volume (ml)</div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">C.R.D</div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">Density</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">1</div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="" class="form-control"></div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="" class="form-control"></div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="" class="form-control"></div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="" class="form-control"></div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="" class="form-control"></div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="" class="form-control"></div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"></div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="" class="form-control"></div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="" class="form-control"></div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="" class="form-control"></div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"></div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"></div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"></div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">2</div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="" class="form-control"></div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="" class="form-control"></div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="" class="form-control"></div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="" class="form-control"></div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="" class="form-control"></div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="" class="form-control"></div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"></div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="" class="form-control"></div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="" class="form-control"></div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="" class="form-control"></div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"></div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"></div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"></div>
                        </div>


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