<section class='card'>                
    {{-- <form wire:submit="updateProfile"> --}}
        <header id='userProfile_{{ $userprofile['user_id'] }}header' class='card-header'>
            <h4 class='card-title'>{{ $userprofile['name'] . ' ' . $userprofile['last_name'] . __('\'s Profile') }}</h4>            
        </header>
        
        <div class='card-body'>
            
            <div class="row col-md-12">
                {{-- CRUD --}}
                @if (Auth::user()->getSec()->getCRUD('user_profile_crud')['read'] || Auth::user()->getSec()->global_admin_value)            
                    
                    <div class="col-md-6 mb-3">
                        <x-form.input wire=1 name="userprofile.name" label="Name" value="{{$userprofile['name']}}"/>
                        @error('userprofile.name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror    
                    </div>

                    <div class="col-md-6 mb-3">
                        <x-form.input wire=1 name="userprofile.last_name" label="Last Name" value="{{$userprofile['last_name']}}"/>
                        @error('userprofile.last_name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror    
                    </div>

                    <div class="col-md-12 mb-3">
                        <x-form.input wire=1 name="userprofile.email" label="Email Address" value="{{$userprofile['email']}}"/>
                        @error('userprofile.email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                                        
                    <div class="col-md-12 mb-3">                                              
                        <x-form.password wire=1 name="userprofile.old_password" label="Old Password"/>
                        @error('userprofile.old_password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <x-form.password wire=1 name="userprofile.new_password" label="New Password"/>
                        @error('userprofile.new_password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="row col-md-12">
                        <div class="col-md-8 mb-3">
                            <x-form.password wire=1 name="userprofile.new_password_confirmation" label="Confirm New Password"/>
                        </div>

                        <div class="col-md-4 mb-2">
                            <div class="mb-6"><br></div>                            
                            <button wire:click="updatePassword" type="button" class='btn btn-secondary mt-2' >Change Password</button>                            
                        </div>
                    </div>
                      
                    <div class="col-md-2 mb-3"><strong>Status : </strong>{{$userprofile['active'] == 1 ? 'Active':'Disabled'}}</div>
                    {{-- <div class="col-md-4 form-check form-switch">
                        <input class="form-check-input" wire:model="userprofile.active" type="checkbox" role="switch" id="activeSwitchCheckChecked" {{$userprofile['active'] == 1 ? 'checked':''}}>
                        <label class="form-check-label" for="activeSwitchCheckChecked">{{$userprofile['active'] == 1 ? 'Active':'Disabled'}}</label>
                    </div> --}}
                    <hr>
                @else
                    <div class="col-md-12 m-2 row">Your Access Level does not allow this view.</div>
                @endif
            </div>
        </div>
        <footer class='card-footer'>
            <div class='row col-md-12'>
                <div class='d-grid gap-2 col-3 mx-auto'>                    
                    <button wire:click="updateProfile" class='btn btn-primary m-2 btn-block' type="button">Save</button>
                </div>
                <div class='d-grid gap-2 col-3 mx-auto'>                        
                    @if (Auth::user()->getSec()->GetCRUD('user_profile_crud')['delete'] || Auth::user()->getSec()->global_admin_value)
                        <button wire:click="deleteProfile" class='btn btn-danger m-2 btn-block' type="button">Delete</button>
                    @endif
                </div>
                
                
            </div>
        </footer>
    {{-- </form> --}}
</section>