<div id='addLabTest' class='modal-block modal-block-lg mfp-hide'>
    <form action="{{url("labs/add")}}" method='post' enctype='multipart/form-data'>
        @csrf
        <section class='card'>
            <header class="card-header">
                <div class="row">
                    <div class="col">
                        <h2 class="card-title">Marshall Stability & Flow</h2>
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
                                    <x-form.hidden name="sample[type]" value="m-s-f" />
                                    <x-form.hidden name="sample[batch_id]" value="{{$batch->id}}" />
                                    <x-form.hidden name="sample[sample]" value="{{$sample}}" />
                                    <x-form.hidden name="sample[batch_number]" value="{{$batch->batch_number}}" />
                                    <h4>{{$batch->batch_number}}</h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-6 pb-sm-3 pb-md-0">
                                    <label class="col-form-label" for="formGroupExampleInput">Position:</label>
                                    <input type="text" name="position" placeholder="" class="form-control">
                                </div>
                                <div class="col-sm-12 col-md-6 pb-sm-3 pb-md-0">
                                    <label class="col-form-label" for="formGroupExampleInput">Sample No:</label>
                                    <input type="text" name="sample" placeholder="" class="form-control">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-6 pb-sm-3 pb-md-0">
                                    <label class="col-form-label" for="formGroupExampleInput">Time:</label>
                                    <input type="datetime-local" name="sample[datetime]" value="{{date("Y-m-d\TH:i")}}" placeholder="" class="form-control">db
                                </div>
                                <div class="col-sm-12 col-md-6 pb-sm-3 pb-md-0">
                                    <label class="col-form-label" for="formGroupExampleInput">location:</label>
                                    <input type="text" name="location" placeholder="" class="form-control">
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">Briquette 1</div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">Briquette 2</div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">Briquette 3</div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">Height 1</div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="mm" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="mm" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="mm" class="form-control"></div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">Height 2</div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="mm" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="mm" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="mm" class="form-control"></div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">Height 3</div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="mm" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="mm" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="mm" class="form-control"></div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">Avarage</div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="mm" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="mm" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="mm" class="form-control"></div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">x Corredction Factor (x)</div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="" class="form-control"></div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">a Stability (kN)</div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="kN" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="kN" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="kN" class="form-control"></div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">x Corrected Stability (ax)</div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="kN" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="kN" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="kN" class="form-control"></div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">Avarage</div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="kN" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"></div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">y Corrected Stability (ax)</div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="mm" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="mm" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="mm" class="form-control"></div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">Avarage</div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="mm" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"></div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">% Voids</div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="%" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"></div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">Applied Load (p)</div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="kN" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"></div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">I.T.S</div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="kPa" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"></div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">Compacted Temperature</div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="C" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="C" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="C" class="form-control"></div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">Avarage</div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="C" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"></div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">Mass in Air</div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="g" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="g" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="g" class="form-control"></div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">Mass in Water</div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="g" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="g" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="g" class="form-control"></div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">Mass with dry surface</div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="g" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="g" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="g" class="form-control"></div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">Volume</div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="cm" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="cm" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="cm" class="form-control"></div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">C.D.M (Core Dry Mass)</div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="g/cm" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="g/cm" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="g/cm" class="form-control"></div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">Avarage</div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="location" placeholder="g/cm" class="form-control"></div>
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