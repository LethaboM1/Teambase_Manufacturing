 
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
                            <a class="dropdown-item modal-basic" href="#receiveGoods" >Goods Received</a>
                        </div>
                    </div>

                    {{-- Good Received --}}
                    <div id='receiveGoods' class='modal-block modal-block-lg mfp-hide'>
                        <form method='post' action="{{url("dispatches/receiving-goods")}}" enctype='multipart/form-data'>
                            @csrf
                            <section class='card'>
                                <header id='receiveGoodsheader' class='card-header'><h2 class='card-title'>Good Receive</h2></header>
                                    <div class='card-body'>
                                        <div class='modal-wrapper'>
                                            <div class='modal-text'>                                                            
                                                <livewire:manufacture.dispatch.receive-goods-livewire />
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
                            <li class="nav-item {{($tab=='receiving'?'active':'')}}" role="presentation">
                                <button class="nav-link {{($tab=='receiving'?'active':'')}}" id="receiving-tab" data-bs-toggle="tab" data-bs-target="#receiving" type="button" role="tab" aria-controls="receiving" aria-selected="{{($tab=='receiving'?'true':'false')}}">Receiving Goods</button>
                            </li>
                            <li class="nav-item {{($tab=='archive'?'active':'')}}" role="presentation">
                                <button class="nav-link {{($tab=='archive'?'active':'')}}" id="archive-tab" data-bs-toggle="tab" data-bs-target="#archive" type="button" role="tab" aria-controls="archive" aria-selected="{{($tab=='archive'?'true':'false')}}">Archive</button>
                            </li>
                        </ul>
                        
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane {{($tab=='receiving'?'active':'')}}" id="receiving" role="tabpanel" aria-labelledby="receiving-tab">
                                <div class="header-right mb-2">
                                    <h4>Good Receiving</h4>
                                    <form action="#" class="search nav-form">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="search_receive_goods" wire:model="search_receive_goods" placeholder="Search Archive...">
                                            <button class="btn btn-default" type="submit"><i class="bx bx-search"></i></button>
                                        </div>
                                    </form>
                                </div><br><br><br>
                                <table width="100%" class="table table-hover table-responsive-md mb-0">
                                    <thead>
                                        <tr>
                                            <th width="10%">Date</th>
                                            <th width="10%">Reference Number</th>
                                            <th width="10%">Supplier</th>
                                            <th width="15%">Reg Number</th>
                                            <th width="15%">Product</th>
                                            <th width="5%">Weigh In</th>
                                            <th width="10%">Status</th>
                                            <th width="10%">Action</th>
                                        </tr>
                                    </thead>
                                        @if($receiving->count()>0)
                                            @foreach($receiving as $transaction)
                                                <livewire:manufacture.dispatch.new-receive-transactions-livewire key="{{$transaction->id}}" :transaction="$transaction" />
                                            @endforeach
                                            {{-- Refresh listeners on Modals --}}
                                            <script>
                                                setTimeout(function() {
                                                    $.getScript('{{url("js/examples/examples.modals.js")}}');
                                                }, 500);
                                            </script>
                                        @else
                                            <tr>
                                                <td colspan="8">{{(strlen($search_arc)==0?"No pending goods received Archived..":"Could not find '{$search_arc}'")}}</td>
                                            </tr>
                                        @endif
                                </table>          
                                {{$receiving->links()}}   
                            </div>

                            <div class="tab-pane {{($tab=='archive'?'active':'')}}" id="archive" role="tabpanel" aria-labelledby="archive-tab">
                                <div class="header-right mb-2">
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
                                            <th width="10%">Reference Number</th>
                                            <th width="10%">Supplier</th>
                                            <th width="15%">Reg Number</th>
                                            <th width="15%">Product</th>
                                            <th width="5%">Weigh In</th>
                                            <th width="10%">Status</th>
                                            <th width="10%">Action</th>
                                        </tr>
                                    </thead>
                                        @if($archive->count()>0)
                                            @foreach($archive as $transaction)
                                                <livewire:manufacture.dispatch.new-receive-transactions-livewire :archive="true" key="{{$transaction->id}}_{{now()}}" :transaction="$transaction" />
                                                {{-- <livewire:manufacture.dispatch.new-batch-line-livewire key="{{$dispatch->id}}_{{now()}}" :dispatch="$dispatch" :new="false"> --}}
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
                                {{$archive->links()}}         
                            
                            </div>
                        </div>                      
                        @if(Session::get('print_receipt'))
                            <script>
                                window.open('{{url("dispatches/received-goods/".Session::get('print_receipt')."/print")}}','_blank');
                            </script>
                        @endif
                </div> 
            </section>
        </div>
    </div>
