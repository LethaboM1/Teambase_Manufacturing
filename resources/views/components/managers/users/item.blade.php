<tr>
        <td>{{$item['name']}}</td>
        <td>{{$item['last_name']}}</td>
        <td>{{$item['email']}}</td>
        <td>{{$item['contact_number']}}</td>
        <td>{{$item['id_number']}}</td>
        <td>{{$item['employee_number']}}</td>
        <td>{{strtoupper($item['role'])}}</td>
        <td>
            @if($item['out_of_office'])
                <span class='badge badge-danger'>Yes</span>
            @else
                No
            @endif
        </td>
        <td class='actions'>
            @if (Auth::user()->getSec()->getCRUD('user_man_crud')['update'] || Auth::user()->getSec()->global_admin_value)
                <a class="modal-sizes" href="#edit_user{{$item['user_id']}}"><i class='fas fa-pencil-alt pointer'></i></a>
            
                <a class="modal-sizes" href="#view_user{{$item['user_id']}}"><i class='fa-solid fa-magnifying-glass pointer'></i></a>
            @endif
            @if (Auth::user()->getSec()->getCRUD('user_man_crud')['delete'] || Auth::user()->getSec()->global_admin_value)
                <a class="modal-sizes" href="#delete_user{{$item['user_id']}}"><i class='far fa-trash-alt pointer'></i></a>            
            @endif
            
            
            <div id='delete_user{{$item['user_id']}}' class='modal-block modal-block-lg mfp-hide'>
                <form action="users/delete" method='post' enctype='multipart/form-data'>
                    @csrf
                    <section class='card'>
                        <header id='delete_user{{$item['user_id']}}header' class='card-header'><h2 class='card-title'>Delete User?</h2></header>
                            <div class='card-body'>
                                <div class='modal-wrapper'>
                                    <div class='modal-text'>
                                        <x-form.hidden wire=0 name="user_id" :value="$item->user_id" />
                                        <p>Are you sure you want to delete user "{{$item->name}} {{$item->last_name}}"</p>
                                    </div>
                                </div>
                            </div>
                            <footer class='card-footer'>
                                <div class='row'>
                                    <div class='col-md-12 text-right'>
                                        <button type='submit' name='save' value='save' class='btn btn-danger'>Confirm</button>
                                        <button class='btn btn-default modal-dismiss'>Cancel</button>
                                    </div>
                                </div>
                            </footer>
                    </section>
                </form>
            </div>

            <div id='edit_user{{$item['user_id']}}' class='modal-block modal-block-lg mfp-hide'>
                <form action="users/save" method='post' enctype='multipart/form-data'>
                    @csrf
                    <section class='card'>
                        <header id='edit_user{{$item['user_id']}}header' class='card-header'><h2 class='card-title'>Edit User {{$item['name'] . ' ' . $item['last_name']}}'s Profile</h2></header>
                            <div class='card-body'>
                                <div class='modal-wrapper'>
                                    <div class='modal-text'>                                        
                                        <x-managers.users.view :user="$item" :cruditems="$crud_items" :seclevels="$sec_levels" />
                                    </div>
                                </div>
                            </div>
                            <footer class='card-footer'>
                                <div class='row'>
                                    <div class='col-md-12 text-right'>
                                        <button type='submit' name='save' value='save' class='btn btn-primary'>Save</button>
                                        <button class='btn btn-default modal-dismiss'>Cancel</button>
                                    </div>
                                </div>
                            </footer>
                    </section>
                </form>
            </div>

            <div id='view_user{{$item['user_id']}}' class='modal-block modal-block-lg mfp-hide'>
                <form action="users/outofoffice" method='post' enctype='multipart/form-data'>
                    @csrf
                    <section class='card'>
                        <header id='view_user{{$item['user_id']}}header' class='card-header'><h2 class='card-title'>Out of office</h2></header>
                            <div class='card-body'>
                                <div class='modal-wrapper'>
                                    <div class='modal-text'>
                                        <h2>{{$item->name}} {{$item->last_name}}</h2>
                                        <x-form.hidden name="user_id" :value="$item->user_id" />
                                        <x-form.checkbox wire=0 name="out_of_office" label="Out of office?" value=1 :toggle="$item->out_of_office" />
                                    </div>
                                </div>
                            </div>
                            <footer class='card-footer'>
                                <div class='row'>
                                    <div class='col-md-12 text-right'>
                                        <button type='submit' name='save' value='save' class='btn btn-primary'>Save</button>
                                        <button class='btn btn-default modal-dismiss'>Cancel</button>
                                    </div>
                                </div>
                            </footer>
                    </section>
                </form>
            </div>
        </td>
    </tr>
