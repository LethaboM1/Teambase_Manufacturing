    <div>
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
            
            <x-form.select label="Role" name="role" value="{{$user['role']}}" :list=" $roles_list" />
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
</div>