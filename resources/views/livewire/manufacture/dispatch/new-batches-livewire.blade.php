 
    <div class="row">
        <div class="col-lg-12 mb-3">
            <section class="card">
                <header class="card-header">
                    <h2 class="card-title">New Dispatches</h2>
                </header>
                <div class="card-body">                   

                        <div class="header-right mb-5">
                            <h4>New Dispatches</h4>
                            <form action="#" class="search nav-form">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" wire:model="search" placeholder="Search batch...">
                                    <button class="btn btn-default" type="submit"><i class="bx bx-search"></i></button>
                                </div>
                            </form>
                        </div>

                        <div class="dropdown open">
                            <a class="btn btn-secondary dropdown-toggle" type="button" id="btnActions" data-bs-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                        Actions
                                    </a>
                            <div class="dropdown-menu" aria-labelledby="btnActions">
                                <a class="dropdown-item modal-basic" href="#addDispatch" >Job Card Collection / Dispatch</a>
                                <a class="dropdown-item modal-basic" href="#addReturn" >Job Card Return</a>
                                <a class="dropdown-item modal-basic" href="#receiveGoods" >Good Received</a>
                            </div>
                        </div>

                        {{-- Job Card Collection / Dispatch --}}
                        <div id='addDispatch' class='modal-block modal-block-lg mfp-hide'>
                            <form method='post' enctype='multipart/form-data'>
                                @csrf
                                <section class='card'>
                                    <header id='addDispatchheader' class='card-header'><h2 class='card-title'></h2></header>
                                        <div class='card-body'>
                                            <div class='modal-wrapper'>
                                                <div class='modal-text'>
                                                    <livewire:manufacture.dispatch.add-dispatch-modal />
                                                </div>
                                            </div>
                                        </div>
                                        <footer class='card-footer'>
                                            <div class='row'>
                                                <div class='col-md-12 text-right'>
                                                    <button type='submit' class='btn btn-primary'>Confirm</button>
                                                    <button class='btn btn-default modal-dismiss'>Cancel</button>
                                                </div>
                                            </div>
                                        </footer>
                                </section>
                            </form>
                        </div>
                        {{-- Job Card Return --}}
                        <div id='addReturn' class='modal-block modal-block-lg mfp-hide'>
                            <form method='post' action="{{url("dispatches/return")}}" enctype='multipart/form-data'>
                                @csrf
                                <section class='card'>
                                    <header id='addReturnheader' class='card-header'><h2 class='card-title'></h2></header>
                                        <div class='card-body'>
                                            <div class='modal-wrapper'>
                                                <div class='modal-text'>
                                                    <livewire:manufacture.dispatch.return-dispatch-modal />
                                                </div>
                                            </div>
                                        </div>
                                        <footer class='card-footer'>
                                            <div class='row'>
                                                <div class='col-md-12 text-right'>
                                                    <button type='submit' class='btn btn-primary'>Confirm</button>
                                                    <button class='btn btn-default modal-dismiss'>Cancel</button>
                                                </div>
                                            </div>
                                        </footer>
                                </section>
                            </form>
                        </div>
                        

                        <table width="100%" class="table table-hover table-responsive-md mb-0">
                            <thead>
                                <tr>
                                    <th width="10%">Date</th>
                                    <th width="10%">Dispatch Number</th>
                                    <th width="10%">Job Card</th>
                                    <th width="15%">Contractor</th>
                                    <th witdh="15%">Vehicle</th>
                                    <th width="15%">Qty</th>
                                    <th width="15%">Status</th>
                                    <th width="10%">Action</th>
                                </tr>
                            </thead>
                                @if($dispatches->count()>0)
                                    @foreach($dispatches as $dispatch)
                                        <livewire:manufacture.dispatch.new-batch-line-livewire key="{{$dispatch->id}}_{{now()}}" :dispatch="$dispatch">
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8">No Dispatches Loading..</td>
                                    </tr>
                                @endif
                        </table>          
                        {{$dispatches->links()}}         

                      
                </div> 
            </section>
        </div>
    </div>
