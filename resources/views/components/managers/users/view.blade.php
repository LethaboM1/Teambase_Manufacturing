<div>
    <!-- Nav tabs --> 
    <ul class="nav nav-tabs" id="UserTab" role="tablist">
        <li class="nav-item active" role="presentation">
            <button class="nav-link active"                
                id="user-details_{{ isset($user['user_id']) ? $user['user_id']:'new' }}-tab" data-bs-toggle="tab"
                data-bs-target="#user-details_{{ isset($user['user_id']) ? $user['user_id']:'new' }}" type="button" role="tab"
                aria-controls="user-details_{{ isset($user['user_id']) ? $user['user_id']:'new' }}" aria-selected="true">User Details</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="user_access_{{ isset($user['user_id']) ? $user['user_id']:'new' }}-tab" data-bs-toggle="tab"                
                data-bs-target="#user_access_{{ isset($user['user_id']) ? $user['user_id']:'new' }}" type="button" role="tab"
                aria-controls="user_access_{{ isset($user['user_id']) ? $user['user_id']:'new' }}" aria-selected="false">Access Levels</button>
        </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane active" id="user-details_{{ isset($user['user_id']) ? $user['user_id']:'new' }}"
            role="tabpanel" aria-labelledby="user-details_{{ isset($user['user_id']) ? $user['user_id']:'new' }}-tab">
            <div class="row">
                {{-- CRUD --}}
                @if (Auth::user()->getSec()->getCRUD('user_profile_crud')['read'] || Auth::user()->getSec()->global_admin_value)

                    @if(isset($user['user_id']))
                        <x-form.hidden name="user_id" :value="$user['user_id']" />
                    @endif
                    <div class="row">
                        <div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
                            <label class="col-form-label" for="formGroupExampleInput">First Name</label>
                            <input type="text" name="name" value="{{$user['name']}}"  class="form-control"">
                        </div>
                        <div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
                            <label class="col-form-label" for="formGroupExampleInput">Last Name</label>
                            <input type="text" name="last_name" value="{{$user['last_name']}}"  class="form-control">
                        </div>
                        <div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
                            <label class="col-form-label" for="formGroupExampleInput">ID Number</label>
                            <input name="id_number" value="{{$user['id_number']}}" id="fc_inputmask_1" data-plugin-masked-input data-input-mask="999999-9999-999" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
                            <label class="col-form-label" for="formGroupExampleInput">Company Number</label>
                            <input name="company_number" value="{{$user['company_number']}}" id="company_number" class="form-control" >
                        </div>
                        <div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
                            <label class="col-form-label" for="formGroupExampleInput">Contact Number</label>
                            <input name="contact_number" value="{{$user['contact_number']}}" id="fc_inputmask_2" data-plugin-masked-input data-input-mask="999-999-9999" class="form-control">
                        </div>
                        <div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
                            <label class="col-form-label" for="formGroupExampleInput">Employee Number</label>
                            <input type="text" name="employee_number" value="{{$user['employee_number']}}"  class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
                            <label class="col-form-label" for="formGroupExampleInput">Username</label>
                            <input type="username" name="username" value="{{$user['username']}}" class="form-control">
                        </div>
                        <div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
                            <label class="col-form-label" for="formGroupExampleInput">Password</label>
                            <input type="password" name="password"  class="form-control">
                        </div>
                        <div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
                            <label class="col-form-label" for="formGroupExampleInput">Confirm Password</label>
                            <input type="password" name="password_confirmation"  class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
                            <label class="col-form-label" for="formGroupExampleInput">Email Address</label>
                            <input type="email" name="email" value="{{$user['email']}}" class="form-control">
                        </div>
                        <div class="col-sm-12 col-md-4 pb-sm-3 pb-md-0">
                            
                            <x-form.select wire=0 label="Role" name="role" value="{{$user['role']}}" :list=" $roles_list" />
                        </div>
                        <div class="col-sm-12 col-md-4 pb-sm-12 pb-md-0">
                            <label class="col-form-label" for="photo">Photo</label>
                            <div class="input-group mb-3">
                                <input name="photo" id="photo" type="file" style="display:none">
                                <input id="photo-box" type='text' class="form-control">
                                <button id="photo-btn" type='button' class="input-group-text" id="basic-addon2"><i class="fa fa-image"></i></button>
                            </div>
                            @push('scripts')
                                <script>
                                    $('#photo-btn').click(function (){ 
                                    $('#photo').click();

                                });
                                
                                $('#photo-box').click(function (){ 
                                    $('#photo').click();

                                });
                                </script>
                            @endpush
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2 mb-3"><strong>Status : </strong>{{$user['active'] == 1 ? 'Active':'Disabled'}}</div>
                        <div class="col-md-4 form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" name="active" id="activeSwitchCheckChecked" {{$user['active'] == 1 ? 'checked':''}}>                            
                        </div>
                    </div>

                @else
                    <div class="col-md-12 m-2 row">Your Access Level does not allow this view.</div>
                @endif
            </div>
        </div>

        <div class="tab-pane" id="user_access_{{ isset($user['user_id']) ? $user['user_id']:'new' }}"
            role="tabpanel" aria-labelledby="user_access_{{ isset($user['user_id']) ? $user['user_id']:'new' }}-tab">
            <div class="row">
                @if (Auth::user()->getSec()->getCRUD('user_man_crud')['read'] || Auth::user()->getSec()->global_admin_value)
                    <div class="col-md-12 row">
                        <input type="hidden" name="sec_array" value="{{$sec_array}}">

                        @foreach ($crud_items as $crud_item => $value)
                            <div class="col-md-4">
                                <div class="card m-2 border-left-primary">
                                    <div class="card-body">
                                        <b>{{ucwords(str_replace('crud', '', str_replace('_', ' ', $crud_item)))}}</b>
                                        <hr>
                                            <x-form.new-toggle class="col-md-4 m-2" name='{{$crud_item}}_create' label="Add" value="{{$sec_levels[$crud_item]['create']}}" wire=0 checked="{{$sec_levels[$crud_item]['create'] == 'true' ? 'true':'false'}}" />
                                            <x-form.new-toggle class="col-md-4 m-2" name='{{$crud_item}}_read' label="View" value="{{$sec_levels[$crud_item]['read']}}" wire=0 checked="{{$sec_levels[$crud_item]['read'] == 'true' ? 'true':'false'}}" />
                                            <x-form.new-toggle class="col-md-4 m-2" name='{{$crud_item}}_update' label="Edit" value="{{$sec_levels[$crud_item]['update']}}" wire=0 checked="{{$sec_levels[$crud_item]['update'] == 'true' ? 'true':'false'}}" />
                                            <x-form.new-toggle class="col-md-4 m-2" name='{{$crud_item}}_delete' label="Delete" value="{{$sec_levels[$crud_item]['delete']}}" wire=0 checked="{{$sec_levels[$crud_item]['delete'] == 'true' ? 'true':'false'}}" />                                                        
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @if (Auth::user()->getSec()->global_admin_value)
                            <x-form.new-toggle class="col-md-12 mx-4 my-3" name="global_admin" label="Global Admin" wire=0 value="{{$sec_levels['global_admin'] == '1' ? '1':'0'}}" checked="{{$sec_levels['global_admin'] == '1' ? 'true':'false'}}" />                        
                        @endif
    
                        <x-form.new-toggle class="col-md-12 mx-4 my-3" name="settings_admin" label="Settings Admin" wire=0 value="{{$sec_levels['settings_admin'] == '1' ? '1':'0'}}" checked="{{$sec_levels['settings_admin'] == '1' ? 'true':'false'}}" />

                        <x-form.new-toggle class="col-md-12 mx-4 my-3" name="products_adjustment_request" label="Can Request Product Adjustment" wire=0 value="{{$sec_levels['products_adjustment_request'] == '1' ? '1':'0'}}" checked="{{$sec_levels['products_adjustment_request'] == '1' ? 'true':'false'}}" />

                        <x-form.new-toggle class="col-md-12 mx-4 my-3" name="products_adjustment_approve" label="Can Approve Product Adjustment Requests" wire=0 value="{{$sec_levels['products_adjustment_approve'] == '1' ? '1':'0'}}" checked="{{$sec_levels['products_adjustment_approve'] == '1' ? 'true':'false'}}" />

                        <x-form.new-toggle class="col-md-12 mx-4 my-3" name="dispatch_transfer_request" label="Can Request a Transfer on a Dispatch" wire=0 value="{{$sec_levels['dispatch_transfer_request'] == '1' ? '1':'0'}}" checked="{{$sec_levels['dispatch_transfer_request'] == '1' ? 'true':'false'}}" />

                        <x-form.new-toggle class="col-md-12 mx-4 my-3" name="dispatch_transfer_approve" label="Can Approve a Dispatch Transfer Requests" wire=0 value="{{$sec_levels['dispatch_transfer_approve'] == '1' ? '1':'0'}}" checked="{{$sec_levels['dispatch_transfer_approve'] == '1' ? 'true':'false'}}" />

                        <x-form.new-toggle class="col-md-12 mx-4 my-3" name="dispatch_admin" label="Can Alter Products and Reference No on Historically Dispatched Loads." wire=0 value="{{$sec_levels['dispatch_admin'] == '1' ? '1':'0'}}" checked="{{$sec_levels['dispatch_admin'] == '1' ? 'true':'false'}}" />

                        <x-form.new-toggle class="col-md-12 mx-4 my-3" name="dispatch_returns" label="Can Return Items on a Dispatch" wire=0 value="{{$sec_levels['dispatch_returns'] == '1' ? '1':'0'}}" checked="{{$sec_levels['dispatch_returns'] == '1' ? 'true':'false'}}" />

                        <x-form.new-toggle class="col-md-12 mx-4 my-3" name="receive_stock" label="Can Receive Stock from Suppliers" wire=0 value="{{$sec_levels['receive_stock'] == '1' ? '1':'0'}}" checked="{{$sec_levels['receive_stock'] == '1' ? 'true':'false'}}" />

                        <x-form.new-toggle class="col-md-12 mx-4 my-3" name="return_stock" label="Can Return Stock to Suppliers" wire=0 value="{{$sec_levels['return_stock'] == '1' ? '1':'0'}}" checked="{{$sec_levels['return_stock'] == '1' ? 'true':'false'}}" />

                        <x-form.new-toggle class="col-md-12 mx-4 my-3" name="reports_dispatch" label="Can View the Dispatch Report" wire=0 value="{{$sec_levels['reports_dispatch'] == '1' ? '1':'0'}}" checked="{{$sec_levels['reports_dispatch'] == '1' ? 'true':'false'}}" />

                        {{-- <x-form.new-toggle class="col-md-12 mx-4 my-3" name="reports_labs" label="Can View the Laboratory Report" wire=0 value="{{$sec_levels['reports_labs'] == '1' ? '1':'0'}}" checked="{{$sec_levels['reports_labs'] == '1' ? 'true':'false'}}" /> --}}

                        <x-form.new-toggle class="col-md-12 mx-4 my-3" name="reports_stock" label="Can View the Stock Report" wire=0 value="{{$sec_levels['reports_stock'] == '1' ? '1':'0'}}" checked="{{$sec_levels['reports_stock'] == '1' ? 'true':'false'}}" />
                        
    
                    </div>
                    
                @else
                    <div class="col-md-12 m-2 row">Your Access Level does not allow this view.</div>
                @endif
            </div>
        </div>





    
</div>