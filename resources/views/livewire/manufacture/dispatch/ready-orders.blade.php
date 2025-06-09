{{--  No Longer in Use 2024-07-18
    <div class="row">
        <div class="col-lg-12 mb-3">
            <section class="card">
                <header class="card-header">
                    <h2 class="card-title">Orders Ready to Dispatch</h2>
                </header>
                <div class="card-body">                   
                        <div class="header-right mb-5">
                            <h4>Ready Orders</h4>
                            <form action="#" class="search nav-form">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" wire:model="search" placeholder="Search batch...">
                                    <button class="btn btn-default" type="submit"><i class="bx bx-search"></i></button>
                                </div>
                            </form>
                        </div>
                        <table width="100%" class="table table-hover table-responsive-md mb-0">
                            <thead>
                                <tr>
                                    <th width="10%">Date</th>
                                    <th width="10%">Dispatch Number</th>
                                    <th width="10%">Job Card</th>
                                    <th width="15%">Contractor</th>
                                    <th witdh="15%">Driver</th>
                                    <th width="15%">Qty</th>
                                    <th width="15%">Status</th>
                                    <th width="10%">Action</th>
                                </tr>
                            </thead>
                                @if($dispatches->count()>0)
                                    @foreach($dispatches as $dispatch)
                                        <tr class="pointer">
                                            <td onclick="$('#edit_btn_{{$dispatch->id}}').click()">{{$dispatch->created_at}}</td>
                                            <td onclick="$('#edit_btn_{{$dispatch->id}}').click()">{{$dispatch->dispatch_number}}</td>
                                            <td onclick="$('#edit_btn_{{$dispatch->id}}').click()">{{$dispatch->jobcard()->jobcard_number}}</td>
                                            <td onclick="$('#edit_btn_{{$dispatch->id}}').click()">{{$dispatch->jobcard()->contractor}}</td>
                                            <td onclick="$('#edit_btn_{{$dispatch->id}}').click()">{{($dispatch->driver()!==null?$dispatch->driver()->name:"None")}}</td>
                                            <td onclick="$('#edit_btn_{{$dispatch->id}}').click()">{{$dispatch->qty}}</td>
                                            <td onclick="$('#edit_btn_{{$dispatch->id}}').click()">{{$dispatch->status}}</td>
                                            <td>
                                                <a id="edit_btn_{{$dispatch->id}}" href="#editDispatch_{{$dispatch->id}}" class="btn btn-primary btn-sm modal-basic"><i class="fas fa-edit"></i></a>                                                
                                                <div id='editDispatch_{{$dispatch->id}}' class='modal-block modal-block-lg mfp-hide'>
                                                    <form method='post' enctype='multipart/form-data'>
                                                        <section class='card'>
                                                            <header id='editDispatch_{{$dispatch->id}}header' class='card-header'><h2 class='card-title'></h2></header>
                                                                <div class='card-body'>
                                                                    <div class='modal-wrapper'>
                                                                        <div class='modal-text'>
                                                                            Dispatch to Driver
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <footer class='card-footer'>
                                                                    <div class='row'>
                                                                        <div class='col-md-12 text-right'>
                                                                            <button type='submit' name='save' value='save' class='btn btn-primary'>Save</button>
                                                                            <button class='btn btn-default modal-dismiss'>Cancel</button></div>
                                                                        </div>
                                                                    </div>
                                                                </footer>
                                                        </section>
                                                    </form>
                                                </div>
                                            
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6">No Orders ready for dispatch..</td>
                                    </tr>
                                @endif
                        </table>          
                        {{$dispatches->links()}}         

                      
                </div> 
            </section>
        </div>
    </div>
 --}}