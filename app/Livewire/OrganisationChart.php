<?php

namespace App\Livewire;

use App\Models\EmployeeDetails;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class OrganisationChart extends Component
{
    public $lower_authorities;

    public $selected_higher_authorities;
    public $unassigned_manager;

    public $selected_higher_authority_emp_ids;
    public $selected_higher_authorities1;
    public $selected_higher_authorities_ID;
    public $searching=1;
    public $notFound;
    public $search='';

    public $selected_lower_authorities;
    public $managers;

    public $selectedManagers=[];
    

    public $primary_lower_authorities;

    public $manager_id;
    public function updateselectedManagers($managerId)
    {
       $this->manager_id=$managerId;
    }
    public function searchFilters()
    {
        $unassigned_manager1 = EmployeeDetails::where(function ($query) {
            $query->where('first_name', 'like', '%' . $this->search . '%')
            ->orWhere('last_name', 'like', '%' . $this->search . '%')
            ->orWhere('emp_id', 'like', '%' . $this->search . '%');
    
        })->whereNull('manager_id')->get();
        $nameFilter = $this->search; 
        $filteredEmployees = $unassigned_manager1->filter(function ($unassigned_manager1) use ($nameFilter) {
            return stripos($unassigned_manager1->first_name, $nameFilter) !== false ||
                stripos($unassigned_manager1->last_name, $nameFilter) !== false ||
                stripos($unassigned_manager1->emp_id, $nameFilter) !== false;
        });

        if ($filteredEmployees->isEmpty()) {
            $this->notFound = true; 
        } else {
            $this->notFound = false;
        }
       
     
    }
    public function render()
    {
        if($this->searching==1)
        {
            $this->unassigned_manager = EmployeeDetails::where(function ($query) {
                $query->where('first_name', 'like', '%' . $this->search . '%')
                ->orWhere('last_name', 'like', '%' . $this->search . '%')
                ->orWhere('emp_id', 'like', '%' . $this->search . '%')
                ->orWhere('job_title', 'like', '%' . $this->search . '%');
        
            })->whereNull('manager_id')->get();
            $nameFilter = $this->search; 
            $filteredEmployees = $this->unassigned_manager->filter(function ($unassigned_manager) use ($nameFilter) {
                return stripos($unassigned_manager->first_name, $nameFilter) !== false ||
                    stripos($unassigned_manager->last_name, $nameFilter) !== false ||
                    stripos($unassigned_manager->emp_id, $nameFilter) !== false||
                    stripos($unassigned_manager->job_title, $nameFilter) !== false;
            });
    
            if ($filteredEmployees->isEmpty()) {
                $this->notFound = true; 
            } else {
                $this->notFound = false;
            }
           
            
        }
        else
        {
            $this->unassigned_manager=EmployeeDetails::where('manager_id',null)->get();
            
        }
        $unassigned_manager_count=EmployeeDetails::where('manager_id',null)->count();
        if($this->manager_id)
        {
          $this->selected_higher_authorities=EmployeeDetails::where('emp_id',$this->manager_id)->get();
          $employee=EmployeeDetails::find($this->manager_id);
          $employeeId = $employee->emp_id;
          $this->selected_lower_authorities=EmployeeDetails::where('manager_id',$employeeId)->get();
          
          
        }
        $higher_authorities=EmployeeDetails::where('job_title','Chairman')->orWhere('job_title','Founder')->get();
        $higher_authorities=EmployeeDetails::where('job_title','Chairman')->orWhere('job_title','Founder')->get();
        $higher_authorities_ID=EmployeeDetails::where('job_title','Chairman')->select('emp_id');
        $this->lower_authorities=EmployeeDetails::where('manager_id',$higher_authorities_ID)->get();
        $this->managers = DB::table('employee_details as e1')
        ->join('employee_details as e2', 'e1.manager_id', '=', 'e2.emp_id')
        ->select('e2.emp_id as manager_id', 'e2.first_name', 'e2.last_name')
        ->distinct()
        ->get();
        $lower_authorities_ID=EmployeeDetails::where('manager_id',$higher_authorities_ID)->select('emp_id');
        // $this->primary_lower_authorities=EmployeeDetails::where('manager_id',$lower_authorities_ID)->get();
        return view('livewire.organisation-chart',['HigherAuthorities'=>$higher_authorities,'UnAssignedManagerCount'=>$unassigned_manager_count]);
    }
}
