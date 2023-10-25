 
    <div class="row">
        <div class="col-lg-12 mb-3">
            <section class="card">
                <header class="card-header">
                    <h2 class="card-title">Dispatches</h2>
                </header>
                <div class="card-body">                   
                    <div class="dropdown open mb-2">
                        <a class="btn btn-secondary dropdown-toggle" type="button" id="btnActions" data-bs-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                    Actions
                                </a>
                        <div class="dropdown-menu" aria-labelledby="btnActions">
                            <a class="dropdown-item modal-basic" href="#addDispatch" >Job Card Collection / Dispatch</a>
                        </div>
                    </div>

                    {{-- Job Card Collection / Dispatch --}}
                    <div id='addDispatch' class='modal-block modal-block-lg mfp-hide'>
                        <form method='post' enctype='multipart/form-data'>
                            @csrf
                            <section class='card'>
                                <header id='addDispatchheader' class='card-header'><h2 class='card-title'>Begin New Dispatch.</h2></header>                                
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
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" id="DispatchTabs" role="tablist">
                        <li class="nav-item {{($tab=='loading'?'active':'')}}" role="presentation">
                            <button class="nav-link {{($tab=='loading'?'active':'')}}" id="loading-tab" data-bs-toggle="tab" data-bs-target="#loading" type="button" role="tab" aria-controls="loading" aria-selected="{{($tab=='loading'?'true':'false')}}">Loading Dispatches</button>
                        </li>
                        <li class="nav-item {{($tab=='archive'?'active':'')}}" role="presentation">
                            <button class="nav-link {{($tab=='archive'?'active':'')}}" id="archive-tab" data-bs-toggle="tab" data-bs-target="#archive" type="button" role="tab" aria-controls="archive" aria-selected="{{($tab=='archive'?'true':'false')}}">Archive</button>
                        </li>
                    </ul>
                    
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane {{($tab=='loading'?'active':'')}}" id="loading" role="tabpanel" aria-labelledby="loading-tab">
                            <div class="header-right mb-5">
                                <h4>Loading Dispatches</h4>
                                <form action="#" class="search nav-form">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="search" wire:model="search" placeholder="Search Dispatches...">
                                        <button class="btn btn-default" type="submit"><i class="bx bx-search"></i></button>
                                    </div>
                                </form>
                            </div>
                            <br><br><br>

                            <table width="100%" class="table table-hover table-responsive-md mb-0">
                                <thead>
                                    <tr>
                                        <th width="10%">Date</th>
                                        <th width="8%">Dispatch Number</th>
                                        <th width="12%">Job Card</th>
                                        <th width="15%">Contractor</th>
                                        <th width="15%">Vehicle</th>
                                        <th width="15%">Product</th>
                                        <th width="5%">Qty</th>
                                        <th width="6%">Status</th>
                                        <th width="13%">Action</th>
                                    </tr>
                                </thead>
                                    @if($dispatches->count()>0)
                                        @foreach($dispatches as $dispatch)
                                            <livewire:manufacture.dispatch.new-batch-line-livewire key="{{$dispatch->id}}_{{now()}}" :dispatch="$dispatch" {{-- jobcard_list="{{$jobcard_list}}" delivery_zone_list="{{$delivery_zone_list}}" --}} dispatchaction="new" >
                                        @endforeach
                                        {{-- Refresh listeners on Modals --}}
                                        <script>
                                            setTimeout(function() {
                                                $.getScript('{{url("js/examples/examples.modals.js")}}');
                                            }, 500);
                                        </script>
                                    @else
                                        <tr>
                                            <td colspan="8">{{(strlen($search)==0?"No Dispatches Loading..":"Could not find '{$search}'")}}</td>
                                        </tr>
                                    @endif
                            </table>          
                            {{$dispatches->links()}}                                
                        </div>

                        <div class="tab-pane {{($tab=='archive'?'active':'')}}" id="archive" role="tabpanel" aria-labelledby="archive-tab">
                            <div class="header-right mb-5">
                                <h4>Archive</h4>
                                <form action="#" class="search nav-form">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="search_arc" wire:model="search_arc" placeholder="Search Archive...">
                                        <button class="btn btn-default" type="submit"><i class="bx bx-search"></i></button>
                                    </div>
                                </form>
                            </div><br><br><br>
                            <table width="100%" class="table table-hover table-responsive-md mb-0">
                                <thead>
                                    <tr>
                                        <th width="10%">Date</th>
                                        <th width="8%">Dispatch Number</th>
                                        <th width="8%">Job Card</th>
                                        <th width="15%">Contractor</th>
                                        <th width="15%">Vehicle</th>
                                        <th width="15%">Product</th>
                                        <th width="5%">Qty</th>
                                        <th width="10%">Status</th>
                                        <th width="12%">Action</th>
                                    </tr>
                                </thead>
                                    @if($dispatches_archived->count()>0)
                                        @foreach($dispatches_archived as $dispatch)
                                            <livewire:manufacture.dispatch.new-batch-line-livewire key="{{$dispatch->id}}_{{now()}}"  :dispatch="$dispatch" {{--jobcard_list="{{$jobcard_list}}" delivery_zone_list="{{$delivery_zone_list}}" --}} dispatchaction="view">
                                        @endforeach
                                        {{-- Refresh listeners on Modals --}}
                                        <script>
                                            setTimeout(function() {
                                                $.getScript('{{url("js/examples/examples.modals.js")}}');
                                            }, 500);
                                        </script>
                                    @else
                                        <tr>
                                            <td colspan="8">{{(strlen($search_arc)==0?"No Dispatches Archived..":"Could not find '{$search_arc}'")}}</td>
                                        </tr>
                                    @endif
                            </table>          
                            {{$dispatches_archived->links()}}         
                        
                        </div>
                    </div>


                        {{-- <div class="header-right mb-5">
                            <h4>New Dispatches</h4>
                            <form action="#" class="search nav-form">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" wire:model="search" placeholder="Search batch...">
                                    <button class="btn btn-default" type="submit"><i class="bx bx-search"></i></button>
                                </div>
                            </form>
                        </div> --}}

                        

                        {{-- <div class="dropdown open">
                            <a class="btn btn-secondary dropdown-toggle" type="button" id="btnActions" data-bs-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                        Actions
                                    </a>
                            <div class="dropdown-menu" aria-labelledby="btnActions">
                                <a class="dropdown-item modal-basic" href="#addDispatch" >Job Card Collection / Dispatch</a>
                                <a class="dropdown-item modal-basic" href="#addReturn" >Job Card Return</a>
                                <a class="dropdown-item modal-basic" href="#receiveGoods" >Good Received</a>
                            </div>
                        </div> --}}

                        {{-- Job Card Collection / Dispatch --}}
                        {{-- <div id='addDispatch' class='modal-block modal-block-lg mfp-hide'>
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
                        </div> --}}
                        {{-- Job Card Return --}}
                        {{-- <div id='addReturn' class='modal-block modal-block-lg mfp-hide'>
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
                        </div> --}}

                        {{-- Good Received --}}
                        {{-- <div id='addReturn' class='modal-block modal-block-lg mfp-hide'>
                            <form method='post' action="{{url("dispatches/receiveGoods")}}" enctype='multipart/form-data'>
                                @csrf
                                <section class='card'>
                                    <header id='addReturnheader' class='card-header'><h2 class='card-title'></h2></header>
                                        <div class='card-body'>
                                            <div class='modal-wrapper'>
                                                <div class='modal-text'>
                                                    <h4>Receive Goods</h4>
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
                        </div> --}}

                        

                        {{-- <table width="100%" class="table table-hover table-responsive-md mb-0">
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
                        {{$dispatches->links()}} --}}                         
                        

                        {{-- Print of Dispatch Note --}}
                        @if(Session::get('print_dispatch'))
                        <script>
                           window.open('{{url("dispatches/print/".Session::get('print_dispatch'))}}','_blank');
                        </script>

                        @endif
                </div> 
            </section>
        </div>
    </div>
