 
    <div class="row">
        <div class="col-lg-12 mb-3">
            <section class="card">
                <header class="card-header">
                    <h2 class="card-title">Batches</h2>
                </header>
                <div class="card-body">
                   
                        <div class="header-right mb-5">
                            <h4>Ready for Dispatch</h4>
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
                                    <th width="15%">Batch Number</th>
                                    <th width="35%">Product</th>
                                    <th width="15%">Qty</th>
                                    <th width="10%">Status</th>
                                    <th width="15%">Action</th>
                                </tr>
                            </thead>
                                @if($batches_list->count()>0)
                                    @foreach($batches_list as $batch)
                                        <x-manufacture.dispatch.batches.item :batch="$batch" />
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6">No batches ready for dispatch..</td>
                                    </tr>
                                @endif
                        </table>          
                        {{$batches_list->links()}}         

                      
                </div> 
            </section>
        </div>
    </div>
