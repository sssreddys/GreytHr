<?php

namespace App\Livewire;

use App\Models\EmployeeDetails;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ShiftRoasterSubmodule extends Component
{
    public $showShiftRoaster = false;
    public function render()
    {
        $loggedInEmpId = Auth::guard('emp')->user()->emp_id;
        // Check if the logged-in user is a manager by comparing emp_id with manager_id in employeedetails
        $isManager = EmployeeDetails::where('manager_id', $loggedInEmpId)->exists();

        // Show "Team on Leave" if the logged-in user is a manager
        $this->showShiftRoaster = $isManager;
        return view('livewire.shift-roaster-submodule',['showShiftRoaster' => $this->showShiftRoaster]);
    }
}
