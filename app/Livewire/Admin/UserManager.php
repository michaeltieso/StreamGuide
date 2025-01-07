<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Validation\Rules;

class UserManager extends AdminComponent
{
    public $users;
    public $editingUser = false;
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $is_admin = false;
    public $currentUser;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255',
        'is_admin' => 'boolean',
    ];

    protected function adminMount(...$args): void
    {
        $this->loadUsers();
    }

    public function loadUsers()
    {
        $this->users = User::orderBy('name')->get();
    }

    public function createUser()
    {
        $this->resetForm();
        $this->editingUser = true;
    }

    public function editUser(User $user)
    {
        $this->currentUser = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->is_admin = $user->is_admin;
        $this->editingUser = true;
    }

    public function saveUser()
    {
        if ($this->currentUser) {
            // Editing existing user
            $rules = $this->rules;
            $rules['email'] .= ',email,' . $this->currentUser->id;
            
            if ($this->password) {
                $rules['password'] = ['required', 'confirmed', Rules\Password::defaults()];
            }
            
            $this->validate($rules);

            $data = [
                'name' => $this->name,
                'email' => $this->email,
                'is_admin' => $this->is_admin,
            ];

            if ($this->password) {
                $data['password'] = Hash::make($this->password);
            }

            $this->currentUser->update($data);
        } else {
            // Creating new user
            $rules = $this->rules;
            $rules['email'] .= '|unique:users';
            $rules['password'] = ['required', 'confirmed', Rules\Password::defaults()];
            
            $this->validate($rules);

            User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'is_admin' => $this->is_admin,
            ]);
        }

        $this->resetForm();
        $this->loadUsers();
        session()->flash('message', 'User saved successfully.');
    }

    public function deleteUser(User $user)
    {
        if ($user->id === auth()->id()) {
            session()->flash('error', 'You cannot delete your own account.');
            return;
        }

        $user->delete();
        $this->loadUsers();
        session()->flash('message', 'User deleted successfully.');
    }

    public function resetForm()
    {
        $this->currentUser = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->is_admin = false;
        $this->editingUser = false;
    }

    public function render(): View
    {
        return view('livewire.admin.user-manager');
    }
} 