<div id='addLabTest' class='modal-block modal-block-lg mfp-hide'>
    <form action="{{url("labs/add")}}" method='post' enctype='multipart/form-data'>
        @csrf
        <section class='card'>
            <header class="card-header">
                <div class="row">
                    <div class="col">
                        <h2 class="card-title">Grading</h2>
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
                                <input type="text" name="sample[contact]" placeholder="" class="form-control">
                            </div>
                            <div class="col-sm-12 col-md-6 pb-sm-3 pb-md-0">
                                <label class="col-form-label" for="formGroupExampleInput">Batch No:</label>
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
                                <input type="text" placeholder="" class="form-control" value="{{sample}}" disabled>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-6 pb-sm-3 pb-md-0">
                                <label class="col-form-label" for="formGroupExampleInput">Time:</label>
                                <input type="text" name="sample[time]" placeholder="" class="form-control" value="{{date('H:i')}}">
                            </div>
                            <div class="col-sm-12 col-md-6 pb-sm-3 pb-md-0">
                                <label class="col-form-label" for="formGroupExampleInput">location:</label>
                                <input type="text" name="sample[location]" placeholder="" class="form-control">
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"></div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">Mass of Sample (Dry + Clean)(x):</div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[mass_of_sample]" placeholder="" class="form-control"></div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"></div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">NTO Binder %</div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[nto_binder]" placeholder="" class="form-control"></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"></div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">Method Used</div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[method]" placeholder="mm" class="form-control"></div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"></div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">Centrifuge Binder</div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[centrifuge_binder]" placeholder="mm" class="form-control"></div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"></div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">a</div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[a]" placeholder="mm" class="form-control"></div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"></div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">b</div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[b]" placeholder="mm" class="form-control"></div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"></div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">c</div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[c]" placeholder="mm" class="form-control"></div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"></div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">d</div>
                            <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[d]" placeholder="mm" class="form-control"></div>
                        </div>
                        <br>
                        <tr>
                            <div class="row">
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">Sieve Size</div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">Mass on Sieve</div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">% of Total</div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">% Passing</div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">28,000</div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[28_1]" placeholder="" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[28_2]" placeholder="" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[28_3]" placeholder="" class="form-control"></div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">20,000</div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[20_1]" placeholder="" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[20_2]" placeholder="" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[20_3]" placeholder="" class="form-control"></div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">14,000</div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[14_1]" placeholder="" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[14_2]" placeholder="" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[14_3]" placeholder="" class="form-control"></div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">10,000</div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[10_1]" placeholder="8,8" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[10_2]" placeholder="" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[10_3]" placeholder="" class="form-control"></div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">7,100</div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[7_1_1]" placeholder="227,9" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[7_1_2]" placeholder="" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[7_1_3]" placeholder="" class="form-control"></div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">5,000</div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[5_1]" placeholder="365,0" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[5_2]" placeholder="" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[5_3]" placeholder="" class="form-control"></div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">2,000</div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[2_1]" placeholder="338,0" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[2_2]" placeholder="" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[2_3]" placeholder="" class="form-control"></div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">1,000</div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[1_1]" placeholder="188,8" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[1_2]" placeholder="" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[1_3]" placeholder="" class="form-control"></div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">0,600</div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[0_6_1]" placeholder="104,1" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[0_6_2]" placeholder="" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[0_6_3]" placeholder="" class="form-control"></div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">0,300</div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[0_3_1]" placeholder="78,1" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[0_3_2]" placeholder="" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[0_3_3]" placeholder="" class="form-control"></div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">0,150</div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[0_15_1]" placeholder="66,7" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[0_15_2]" placeholder="" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[0_15_3]" placeholder="" class="form-control"></div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">0,075</div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[0_075_1]" placeholder="36,4" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[0_075_2]" placeholder="" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[0_075_3]" placeholder="" class="form-control"></div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">Received</div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[receieved_1]" placeholder="" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[receieved_2]" placeholder="" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[receieved_3]" placeholder="" class="form-control"></div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0">Total</div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[total_1]" placeholder="g" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[total_2]" placeholder="g" class="form-control"></div>
                                <div class="col-sm-3 col-md-3 pb-sm-3 pb-md-0"><input type="text" name="sample[total_3]" placeholder="g" class="form-control"></div>
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