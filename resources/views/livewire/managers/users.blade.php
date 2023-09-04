
    <div class="row">
        <div class="col-sm-12 col-md-12 pb-sm-12 pb-md-0">
            <button href="#modalAddUser" class="mb-1 mt-1 mr-1 modal-sizes btn btn-primary">Create User</button>
            <div class="header-right">
                <div class="input-group">
                    <input type="text" class="form-control" wire:model.600ms="search" name="search"  placeholder="Search User...">
                    <button class="btn btn-default" id='searchBtn' type="button"><i class="bx bx-search"></i></button>                    
                </div>
            </div>
        </div>
        <!-- Modal Create User -->
        <div id="modalAddUser" class="modal-block modal-block-lg mfp-hide">
            <form method="post" action="users/add" enctype="multipart/form-data">
                @csrf
                <section class="card">
                    <header class="card-header">
                        <h2 class="card-title">Add New User</h2>
                        <p class="card-subtitle">Add new users. Photo should be in .jpg or .png format and not larger than 2MB.</p>
                    </header>
                    <div class="card-body">
                        <x-managers.users.view :user="null" />
                    </div>
                    <footer class="card-footer text-end">
                        <button type="submit" name="add_user" class="btn btn-primary">Add User</button>
                        <button class="btn btn-default modal-dismiss">Cancel</button>
                    </footer>
                </section>
            </form>
        </div>
        <!-- Modal Create User End -->
        <br>
        <br>
        <div class="col-lg-12 mb-12">
            <section class="card">
                <header class="card-header">
                    <h2 class="card-title">Manage Users</h2>
                </header>
                <div class="card-body">
                    <table width="1047" class="table table-responsive-md mb-0">
                        <thead>
                            <tr>
                                <th width="200">First Name</th>
                                <th width="200">Last Name</th>
                                <th width="110">Email</th>
                                <th width="110">Contact Number</th>
                                <th width="150">ID Number</th>
                                <th width="150">Employee Number</th>
                                <th width="100">Roll</th>
                                <th>Out of Office</th>
                                <th width="80">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="users_list">
                            @if($users_list->count() > 0)
                                @foreach($users_list as $user)
                                    <x-managers.users.item :item="$user" />
                                @endforeach
                                <script>
                                    setTimeout(function() {
                                        $.getScript('js/examples/examples.modals.js');
                                    }, 300);
                                </script>
                            @else
                                <tr>
                                    <td colspan='9'>No Users to list...</td>
                                </tr>
                            @endif
                            
                        </tbody>
                    </table>
                    {{ $users_list->links() }}
                    
                </div>
            </section>
        </div>
    </div>