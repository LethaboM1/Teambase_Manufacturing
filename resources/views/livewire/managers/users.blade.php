
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
                                <th width="35">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="users_list">
                            @if($users_list->count() > 0)
                                @foreach($users_list as $user)
                                    <x-managers.users.item :item="$user" />
                                @endforeach
                            @else
                                <tr>
                                    <td colspan='9'>No Users to list...</td>
                                </tr>
                            @endif
                            <?php
                            // if ($_SESSION['user']['role'] == 'system') {
                            //     $get_users = dbq("select * from users_tbl where role!='system' order by name");
                            // } else {
                            //     $get_users = dbq("select * from users_tbl where role!='system' and active=1 order by name");
                            // }
    
                            // if ($get_users) {
                            //     if (dbr($get_users) > 0) {
                            //         while ($row = dbf($get_users)) {
                            //             include "includes/pages/manager/users/list_users.php";
                            //         }
                            //     } else {
                            //         echo "<tr><td colspan='8'>No Users</td></tr>";
                            //     }
                            // } else {
                            //     echo "<tr><td colspan='8'>Error reteiving users</td></tr>";
                            // }
    
                            // $modal_form = "<div id='edit_user_modal'></div>";
                            // modal('modalEditUser', 'Edit User', $modal_form, 'Save', 'save_user');
    
                            // $jscript_function .=	"
                            //             function edit_user(user_id) {
                            //                 $.ajax({
                            //                     method:'post',
                            //                     url: 'includes/ajax.php',
                            //                     data: {
                            //                         cmd:'get_edit_user',
                            //                         user_id: user_id
                            //                     },
                            //                     success: function (result) {
                            //                         $('#edit_user_modal').html(result);
                            //                         $('#openModalEditUser').click();
                            //                     }
                            //                 });
                            //             }
                            //             ";
    
                            // $modal_form = "<div id='del_user_modal'></div>";
                            // modal('modalDeleteUser', 'Delete User', $modal_form, 'Confirm', 'del_user');
    
                            // $jscript_function .=	"
                            //                         function delete_user(user_id) {
                            //                             $.ajax({
                            //                                 method:'post',
                            //                                 url: 'includes/ajax.php',
                            //                                 data: {
                            //                                     cmd:'get_del_user',
                            //                                     user_id: user_id
                            //                                 },
                            //                                 success: function (result) {
                            //                                     $('#del_user_modal').html(result);
                            //                                     $('#openModalDeleteUser').click();
                            //                                 }
                            //                             });
                            //                         }
                            //                         ";
                            // $modal_form = "<div id='view_user_modal'></div>";
                            // modal('modalViewUser', 'View User Profile', $modal_form);
    
                            // $jscript_function .=	"
                            //                         function view_user(user_id) {
                            //                             $.ajax({
                            //                                 method:'post',
                            //                                 url: 'includes/ajax.php',
                            //                                 data: {
                            //                                     cmd:'get_view_user',
                            //                                     user_id: user_id
                            //                                 },
                            //                                 success: function (result) {
                            //                                     $('#view_user_modal').html(result);
                            //                                     $('#openModalViewUser').click();
                            //                                 }
                            //                             });
                            //                         }
                            //                         ";
                            ?>
                        </tbody>
                    </table>
                    {{ $users_list->links() }}
                    <a id='openModalEditUser' href='#modalEditUser' class='mb-1 mt-1 mr-1 modal-sizes'></a>
                    <a id="openModalDeleteUser" class="mb-1 mt-1 mr-1 modal-basic" href="#modalDeleteUser"></a>
                    <a id="openModalViewUser" class="mb-1 mt-1 mr-1 modal-basic" href="#modalViewUser"></a>
                </div>
            </section>
        </div>
    </div>