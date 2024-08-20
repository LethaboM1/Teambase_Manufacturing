<?php

namespace App\Http\Livewire\User;

use App\Models\User;
use App\Models\UserSec;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserProfileLivewire extends Component
{
    public $userprofile=[''];    

    function mount($userprofile=[''])
    {        
        
        if($userprofile==['']){$userprofile=User::where('user_id', Auth::user()->user_id)->first()->toArray();}        
        $this->userprofile = $userprofile;

        $this->userprofile['old_password'] = '';
        unset($this->userprofile['password']);
        $this->userprofile['new_password'] = '';        
        $this->userprofile['new_password_confirmation'] = '';        

        
        // $this->name = $this->userprofile['name'];
        // $this->last_name = $this->userprofile['lastname'];
        // $this->email = $this->userprofile['email'];        
        // $this->user_active = $this->userprofile['active'];
        
    } 

    public function messages()
    {
        return [
            'userprofile.old_password.required' => 'The Old Password is required.',            
            'userprofile.new_password.required' => 'The New Password is required.',
            'userprofile.new_password.confirmed' => 'Please Confirm the New Password or make sure the Passwords match.',
            'userprofile.new_password.min' => 'The New Password must be at least 8 Characters in length.',
            'userprofile.name.required' => 'The Name :input is required.',
            'userprofile.last_name.required' => 'The Last Name :input is required.',
            'userprofile.email.required' => 'The Email :input is required.',
            'userprofile.email.email' => 'The Email :input must be a valid Email.',
            'userprofile.email.unique' => 'The Email :input is already in use.',
        ];
    }

    public function updatePassword(){
        if(Auth::user()->getSec()->getCRUD('user_profile_crud')['update'] || Auth::user()->getSec()->global_admin_value){ 
            
            $this->validate(['userprofile.old_password' => 'required']);

            //Match The Old Password
        
            if(!Hash::check($this->userprofile['old_password'], auth()->user()->password)){                
                return redirect("/user/view/{$this->userprofile['user_id']}")->with(['alertError'=>'Please check Old Password']);                                             
            } else {
                $this->updateNewPassword();
            }

        }
    }
    
    protected function updateNewPassword(){
        
        if(Auth::user()->getSec()->getCRUD('user_profile_crud')['update'] || Auth::user()->getSec()->global_admin_value){
            
            //New Password + Confirmed Password
            $this->validate(['userprofile.new_password' => 'required|confirmed|min:8']);

            //Update the new Password
            User::whereUserId(auth()->user()->user_id)->update([
                'password' => Hash::make($this->userprofile['new_password'])
            ]);            

            return redirect("/user/view/{$this->userprofile['user_id']}")->with(['alertMessage'=>'New Password Saved!']);
            
        }        
    }

    public function updateProfile(){        
        if(Auth::user()->getSec()->getCRUD('user_profile_crud')['update'] || Auth::user()->getSec()->global_admin_value){
            //Validation           
            $this->validate(['userprofile.name' => 'required',
                'userprofile.last_name' => 'required',
                'userprofile.email' => 'required|email|unique:users_tbl,email,'.$this->userprofile['user_id'].',user_id',
            ]);           

            unset($this->userprofile['old_password']);
            unset($this->userprofile['new_password']);
            unset($this->userprofile['new_password_confirmation']);
            unset($this->userprofile['updated_at']);
            unset($this->userprofile['created_at']);
            
            //Update User Details            
            User::whereUserId(auth()->user()->user_id)->update($this->userprofile);
            
            return redirect("/user/view/{$this->userprofile['user_id']}")->with(['alertMessage'=>'Posted!']);            
            
        }

        
    }

    public function deleteProfile(){
        if(Auth::user()->getSec()->getCRUD('user_profile_crud')['delete'] || Auth::user()->getSec()->global_admin_value){
            //Delete User Profile
            UserSec::whereUserId(auth()->user()->user_id)->delete();
            User::whereUserId(auth()->user()->user_id)->delete();           

            auth()->logout();
            session()->invalidate();
            session()->regenerateToken();           
            
            return redirect("/")->with(['alertNotice'=>'User Profile Deleted!']);
            
            
        }        
    }    
    
    public function render()
    {
        return view('livewire.user.user-profile-livewire'/* , ['userprofile' => User::where('user_id', Auth::user()->user_id)->first()->toArray()] */);
    }
}

