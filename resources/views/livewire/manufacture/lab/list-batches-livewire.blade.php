 
    <div class="row">
        <div class="col-lg-12 mb-3">
            <section class="card">
                <header class="card-header">
                    <h2 class="card-title">Lab Batches</h2>
                </header>
                <div class="card-body">
                   

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" id="JobTabs" role="tablist">
                      <li class="nav-item {{($tab=='open'?'active':'')}}" role="presentation">
                        <button class="nav-link {{($tab=='open'?'active':'')}}" id="open-tab" data-bs-toggle="tab" data-bs-target="#open" type="button" role="tab" aria-controls="open" aria-selected="{{($tab=='open'?'true':'false')}}">Batches</button>
                      </li>
                      {{--  <li class="nav-item {{($tab=='archive'?'active':'')}}" role="presentation">
                        <button class="nav-link {{($tab=='archive'?'active':'')}}" id="archive-tab" data-bs-toggle="tab" data-bs-target="#archive" type="button" role="tab" aria-controls="archive" aria-selected="{{($tab=='archive'?'true':'false')}}">Archive</button>
                      </li> Merged into one List 2023-10-19 --}}
                    </ul>
                    
                    <!-- Tab panes -->
                    <div class="tab-content">
                      <div class="tab-pane {{($tab=='open'?'active':'')}}" id="open" role="tabpanel" aria-labelledby="open-tab">
                        <div class="header-right mb-5">
                            <h4>Batches</h4>
                            <form action="#" class="search nav-form">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" wire:model="search" placeholder="Search Batches...">
                                    <button class="btn btn-default" type="submit"><i class="bx bx-search"></i></button>
                                </div>
                            </form>
                        </div>
                        <table width="100%" class="table table-hover table-responsive-md mb-0">
                            <thead>
                                <tr>
                                    <th width="10%">Date</th>
                                    <th width="15%">Batch Number</th>
                                    <th width="35%">Product</th>
                                    <th width="15%">Qty</th>
                                    <th width="10%">Status</th>
                                    <th width="15%">Action</th>
                                </tr>
                            </thead>
                                @if($batches_list->count()>0)
                                    @foreach($batches_list as $batch)
                                        <x-manufacture.lab.batches-item :batch="$batch" />
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">No batches..</td>
                                    </tr>
                                @endif
                        </table>          
                        {{$batches_list->links()}}         

                      </div>
                      {{-- <div class="tab-pane {{($tab=='archive'?'active':'')}}" id="archive" role="tabpanel" aria-labelledby="archive-tab">
                        <div class="header-right mb-5">
                            <h4>Archive</h4>
                            <form action="#" class="search nav-form">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" wire:model="search_arc" placeholder="Search Jobcard...">
                                    <button class="btn btn-default" type="submit"><i class="bx bx-search"></i></button>
                                </div>
                            </form>
                        </div>
                        <table width="100%" class="table table-hover table-responsive-md mb-0">
                            <thead>
                                <tr>
                                    <th width="10%">Date</th>
                                    <th width="15%">Batch Number</th>
                                    <th width="15%">Product</th>
                                    <th width="45%">Qty</th>
                                    <th width="15%">Action</th>
                                </tr>
                            </thead>
                                @if($archive_list->count()>0)
                                    @foreach($archive_list as $batch)
                                    <x-manufacture.lab.batches-item :batch="$batch" />
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">No batches..</td>
                                    </tr>
                                @endif
                        </table>          
                        {{$archive_list->links()}}         
                        
                      </div> Merged into one List 2023-10-19 --}}
                    </div>
                </div> 
            </section>
        </div>
    </div>
