 
    <div class="row">
        @if(auth()->user()->role == 'manager'||auth()->user()->role == 'system')
            {{-- <div class="col-lg-12 mb-3">
                <form method="post" action="jobcards/add" id="addplant">
                    @csrf
                    <section class="card">
                        <header class="card-header">
                            <h2 class="card-title">Add New Jobcard or Recipe</h2>
                        </header>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12 col-md-2 pb-sm-3 pb-md-0">                               
                                    <x-form.input wire=0 name="code" label="Jobcard Code" :value="old('code')" />
                                </div>
                                <div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">                                
                                    <x-form.input wire=0 name="description" label="Jobcard Description" :value="old('description')" />
                                </div>
                                <div class="col-sm-12 col-md-2 pb-sm-3 pb-md-0">
                                    <x-form.number wire=0 step="0.1" name="opening_balance" label="Opening Balance" :value="old('opening_balance')" />
                                </div>
                                <div class="col-sm-12 col-md-2 pb-sm-3 pb-md-0">
                                    <x-form.select wire=0 name="unit_measure" label="Unit Measure" :value="old('unit_measure')" :list="$unit_measure_list" />
                                </div>
                                <div class="col-sm-12 col-md-3 pb-sm-3 pb-md-0">
                                   
                                    <x-form.checkbox wire=0 name="has_recipe" label="Has a recipe?" :toggle="old('has_recipe')" :value="1" />
                                </div>
                            </div>
                        </div>
                        <footer class="card-footer text-end">
                            <button class="btn btn-primary">Add Jobcards</button>
                            <button type="reset" class="btn btn-default">Reset</button>
                        </footer>
                    </section>
                </form>
            </div> --}}
        @endif  
        
        <div class="col-lg-12 mb-3">
            <section class="card">
                <header class="card-header">
                    <h2 class="card-title">Jobcards</h2>
                </header>
                <div class="card-body">
                   

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" id="JobTabs" role="tablist">
                      <li class="nav-item {{($tab=='unfilled'?'active':'')}}" role="presentation">
                        <button class="nav-link {{($tab=='unfilled'?'active':'')}}" id="unfilled-tab" data-bs-toggle="tab" data-bs-target="#unfilled" type="button" role="tab" aria-controls="unfilled" wire:click="toggleTab('unfilled')" aria-selected="{{($tab=='unfilled'?'true':'false')}}">Unfilled Jobcards</button>
                      </li>
                      <li class="nav-item {{($tab=='filled'?'active':'')}}" role="presentation">
                        <button class="nav-link {{($tab=='filled'?'active':'')}}" id="filled-tab" data-bs-toggle="tab" data-bs-target="#filled" type="button" role="tab" aria-controls="filled" wire:click="toggleTab('filled')" aria-selected="{{($tab=='filled'?'true':'false')}}">Filled Jobcards</button>
                      </li>
                      <li class="nav-item {{($tab=='archive'?'active':'')}}" role="presentation">
                        <button class="nav-link {{($tab=='archive'?'active':'')}}" id="archive-tab" data-bs-toggle="tab" data-bs-target="#archive" type="button" role="tab" aria-controls="archive" wire:click="toggleTab('archive')" aria-selected="{{($tab=='archive'?'true':'false')}}">Archive</button>
                      </li>
                    </ul>
                    
                    <!-- Tab panes -->
                    <div class="tab-content">
                      <div class="tab-pane {{($tab=='unfilled'?'active':'')}}" id="unfilled" role="tabpanel" aria-labelledby="unfilled-tab">
                        <div class="header-right mb-5">
                            <h4>Unfilled</h4>
                            <form action="#" class="search nav-form">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" wire:model="search" placeholder="Search Jobcard...">
                                    <button class="btn btn-default" type="submit"><i class="bx bx-search"></i></button>
                                </div>
                            </form>
                        </div>
                        <table width="100%" class="table table-hover table-responsive-md mb-0">
                            <thead>
                                <tr>
                                    <th width="15%">Date</th>
                                    <th width="10%">Job Number</th>
                                    <th width="10%">Site Number</th>
                                    <th width="45%">Customer/Contractor</th>
                                    <th width="15%">Contact Number</th>
                                    <th width="5%">Action</th>
                                </tr>
                            </thead>
                                @if($jobcards_list->count()>0)
                                    @foreach($jobcards_list as $jobcard)
                                        <x-manufacture.jobs.item :jobcard="$jobcard" />
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">No jobcards..</td>
                                    </tr>
                                @endif
                        </table>          
                        {{$jobcards_list->links()}}         

                      </div>
                      <div class="tab-pane {{($tab=='filled'?'active':'')}}" id="filled" role="tabpanel" aria-labelledby="filled-tab">
                        <div class="header-right mb-5">
                            <h4>Filled</h4>
                            <form action="#" class="search nav-form">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" wire:model="search_filled" placeholder="Search Jobcard...">
                                    <button class="btn btn-default" type="submit"><i class="bx bx-search"></i></button>
                                </div>
                            </form>
                        </div>
                        <table width="100%" class="table table-hover table-responsive-md mb-0">
                            <thead>
                                <tr>
                                    <th width="15%">Date</th>
                                    <th width="10%">Job Number</th>
                                    <th width="10%">Site Number</th>
                                    <th width="45%">Customer/Contractor</th>
                                    <th width="15%">Contact Number</th>
                                    <th width="5%">Action</th>
                                </tr>
                            </thead>
                                @if($jobcards_list_filled->count()>0)
                                    @foreach($jobcards_list_filled as $jobcard)
                                        <x-manufacture.jobs.item :jobcard="$jobcard" />
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">No jobcards..</td>
                                    </tr>
                                @endif
                        </table>          
                        {{$jobcards_list_filled->links()}}         

                      </div>
                      <div class="tab-pane {{($tab=='archive'?'active':'')}}" id="archive" role="tabpanel" aria-labelledby="archive-tab">
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
                                    <th width="15%">Date</th>
                                    <th width="10%">Job Number</th>
                                    <th width="10%">Site Number</th>
                                    <th width="45%">Customer/Contractor</th>
                                    <th width="15%">Contact Number</th>
                                    <th width="5%">Action</th>
                                </tr>
                            </thead>
                                @if($archive_list->count()>0)
                                    @foreach($archive_list as $jobcard)
                                        <x-manufacture.jobs.item :jobcard="$jobcard" />
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">No jobcards..</td>
                                    </tr>
                                @endif
                        </table>          
                        {{$archive_list->links()}}         
                        
                      </div>
                    </div>
                </div> 
            </section>
        </div>
    </div>
