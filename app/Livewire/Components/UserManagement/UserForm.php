<?php

namespace App\Livewire\Components\UserManagement;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\Attributes\On;
use Jantinnerezo\LivewireAlert\LivewireAlert;



class UserForm extends Component
{
    use LivewireAlert;
    public $show_password; //var true for show password false for hindi
    public $isCreate; //var true for create false for edit

    public $user_id;
    public $firstname;
    public $middlename;
    public $lastname;
    public $contact_number;
    public $role;
    public $status;
    public $username;
    public $password;
    public $retype_password;



    public function render()
    {
        // *tignan if yung id na pinasa from table is walang laman, pag may laman means mag populate then punta sa edit form punta
        if ($this->user_id) {
            $this->populateForm();
        }
        return view('livewire.components.UserManagement.user-form');
    }

    //* assign all the listners in one array
    protected $listeners = [
        'edit-user-from-table' => 'edit',  //@params parameter galing sa UserTable class
        'change-method' => 'changeMethod', //@params parameter galing sa UserTable class,  laman false
        'updateconfirmed',
        'createconfirmed'
    ];

    public function create()
    {

        $validated = $this->validateForm();


        $this->confirm('Do you want to update this user??', [
            'onConfirmed' => 'createconfirmed', //* call the createconfirmed method
            'inputAttributes' =>  $validated, //* pass the user to the confirmed method, as a form of array

        ]);

    }


    public function createconfirmed($data)
    {

        $validated = $data['inputAttributes'];

        if ($this->isCreate) {
            $user = User::create([
                'firstname' => $validated['firstname'],
                'middlename' => $validated['middlename'],
                'lastname' => $validated['lastname'],
                'contact_number' => $validated['contact_number'],
                'user_role_id' => $validated['role'],
                'status' => $validated['status'],
                'username' => $validated['username'],
                'password' => Hash::make($validated['password'])
            ]);
        }

        $this->resetForm();
        $this->alert('success', 'User is created successfully');
    }


    public function update()
    {
        $validated = $this->validateForm();

        $user = User::find($this->user_id); //? kunin lahat ng data ng may ari ng user_id

        //*pag hindi palitan ang password
        $user->firstname = $validated['firstname'];
        $user->middlename = $validated['middlename'];
        $user->lastname = $validated['lastname'];
        $user->contact_number = $validated['contact_number'];
        $user->user_role_id = $validated['role'];
        $user->status = $validated['status'];
        $user->username = $validated['username'];

        //*pag  palitan ang password
        if ($this->show_password) {
            $user->password = Hash::make($validated['password']);  //* gawing hash ang pass
        }


        $this->confirm('Do you want to update this user??', [
            'onConfirmed' => 'updateconfirmed', //* call the confmired method
            'inputAttributes' =>  $user, //* pass the user to the confirmed method, as a form of array

        ]);
    }

    public function updateconfirmed($data)
    {
        //var sa loob ng $data array, may array pa ulit (inputAttributes), extract the inputAttributes then assign the array to a variable
        $attributes = $data['inputAttributes'];

        //* hanapin id na attribute sa $attributes array
        $user = User::find($attributes['id']);

        $user->fill($attributes); //var ipasa ang laman ng $attributes sa $user variable
        $user->save(); //* Save the user model to the database

        $this->resetForm();
        $this->alert('success', 'User is updated successfully');
    }
    private function resetForm() //*tanggalin ang laman ng input pati $user_id value
    {
        $this->reset(['firstname', 'middlename', 'lastname', 'contact_number', 'role', 'status', 'username', 'password', 'retype_password', 'user_id']);
    }


    private function populateForm() //*lagyan ng laman ang mga input
    {

        $user_details = User::find($this->user_id); //? kunin lahat ng data ng may ari ng user_id
        $this->fill([
            'firstname' => $user_details->firstname,
            'middlename' => $user_details->middlename,
            'lastname' => $user_details->lastname,
            'contact_number' => $user_details->contact_number,
            'role' => $user_details->user_role_id,
            'status' => $user_details->status,
            'username' => $user_details->username,
        ]);
    }

    protected function validateForm()
    {

        $rules = [
            'firstname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'lastname' => 'required|string|max:255',
            'contact_number' => ['required', 'numeric', 'digits:11', Rule::unique('users', 'contact_number')->ignore($this->user_id)],
            'role' => 'required',
            'status' => 'required',

            //? validation sa username paro iignore ang user_id para maupdate ang username kahit unique
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($this->user_id)],
        ];

        //*para sa create na validation or //*para sa edit na may passowrd na validation
        if ($this->isCreate || $this->show_password) {
            $rules['password'] = 'required|string|min:8|same:retype_password';
            $rules['retype_password'] = 'required|string|min:8';
        }

        return $this->validate($rules);
    }


    public function edit($userID)
    {
        $this->user_id = $userID; //var assign ang parameter value sa global variable
    }

    public function changeMethod($isCreate)
    {

        $this->isCreate = $isCreate; //var assign ang parameter value sa global variable

        //* kapag true ang laman ng $isCreate mag reset ang form then  go to create form and ishow ang password else hindi ishow
        if ($this->isCreate) {

            $this->resetForm();
            $this->show_password = true;
        } else {
            $this->show_password = false;
        }
    }
}
