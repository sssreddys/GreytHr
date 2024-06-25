<?php

namespace App\Livewire;

use App\Models\EmployeeDetails;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Spatie\SimpleExcel\SimpleExcelWriter;

class EmployeeDirectory extends Component
{
    public $companyId;

    public $records;

    public $records_excel;

    public $showHelp=false;

    public $selectedCategory = 'all';

    public $selectedEmploymentStatus='all';

    public $selectedEmploymentFilter='all';

    public $employeeFilter;

    public $employeeStatus;

   

   
    
    public function updateSelectedEmploymentStatus()
    {
        try {
            $this->employeeStatus = $this->selectedEmploymentStatus;
        } catch (\Exception $e) {
            Log::error('Error in updateSelectedEmploymentStatus method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while updating the employment status. Please try again later.');
        }
    }
    public function updateselectedEmploymentFilter()
    {
        try {
            $this->employeeFilter = $this->selectedEmploymentFilter;
        } catch (\Exception $e) {
            Log::error('Error in updateselectedEmploymentFilter method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while updating the employment filter. Please try again later.');
        }
    }
    public function hideHelp()
    {
   
        try {
            $this->employeeFilter = $this->selectedEmploymentFilter;
        } catch (\Exception $e) {
            Log::error('Error in updateselectedEmploymentFilter method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while updating the employment filter. Please try again later.');
        }
    }
    public function showhelp()
    {
        try {
            $this->showHelp = false;
        } catch (\Exception $e) {
            Log::error('Error in showHelp method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while trying to show the help section. Please try again later.');
        }

    }
    public function exportToExcel()
    {
        try {
            $this->records_excel = EmployeeDetails::select('employee_details.*')
                ->join('hr', 'employee_details.company_id', '=', 'hr.company_id')
                ->where('hr.company_id', '=', $this->companyId)
                ->get();
    
            $data[] = ['emp_id', 'emp_name', 'join_date', 'status', 'Phone No.', 'Email', 'Extension No.'];
    
            foreach ($this->records_excel as $er) {
                $data[] = [
                    $er['emp_id'],
                    $er['first_name'] . ' ' . $er['last_name'],
                    $er['hire_date'],
                    $er['job_title'],
                    $er['mobile_number'],
                    $er['email']
                ];
            }
    
            $filePath = storage_path('app/Total_no_of_employees.xlsx');
    
            SimpleExcelWriter::create($filePath)->addRows($data);
    
            return response()->download($filePath, 'Total_no_of_employees.xlsx');
        } catch (\Exception $e) {
            Log::error('Error in exportToExcel method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while exporting to Excel. Please try again later.');
            return redirect()->back();
        }
    }
    public function render()
    {
                try {
                    $this->companyId = Auth::user()->company_id;

                    if ($this->employeeFilter == 'past_employees') {
                        $this->records = EmployeeDetails::select('employee_details.*')
                            ->join('hr', 'employee_details.company_id', '=', 'hr.company_id')
                            ->where('employee_details.employee_status', 'resigned')
                            ->orWhere('employee_details.employee_status', 'terminated')
                            ->get();
                    } elseif ($this->employeeFilter == 'current_employees') {
                        $this->records = EmployeeDetails::select('employee_details.*')
                            ->join('hr', 'employee_details.company_id', '=', 'hr.company_id')
                            ->where('hr.company_id', '=', $this->companyId)
                            ->whereYear('employee_details.hire_date', '>=', 2022)
                            ->get();
                    } else {
                        $this->records = EmployeeDetails::select('employee_details.*')
                            ->join('hr', 'employee_details.company_id', '=', 'hr.company_id')
                            ->where('hr.company_id', '=', $this->companyId)
                            ->get();
                    }

                    if ($this->employeeStatus == 'consultant') {
                        $this->records = EmployeeDetails::select('employee_details.*')
                            ->join('hr', 'employee_details.company_id', '=', 'hr.company_id')
                            ->where(function ($query) {
                                $query->where('employee_details.job_title', 'LIKE', '%consultant%')
                                    ->orWhere('employee_details.job_title', 'LIKE', '%Consultant%');
                            })
                            ->get();
                    } elseif ($this->employeeStatus == 'resigned') {
                        $this->records = EmployeeDetails::select('employee_details.*')
                            ->join('hr', 'employee_details.company_id', '=', 'hr.company_id')
                            ->where('employee_status', 'resigned')
                            ->get();
                    } elseif ($this->employeeStatus == 'active') {
                        $this->records = EmployeeDetails::select('employee_details.*')
                            ->join('hr', 'employee_details.company_id', '=', 'hr.company_id')
                            ->where('employee_status', 'active')
                            ->get();
                    } elseif ($this->employeeStatus == 'new_joinee') {
                        $this->records = EmployeeDetails::select('employee_details.*')
                            ->join('hr', 'employee_details.company_id', '=', 'hr.company_id')
                            ->whereYear('employee_details.hire_date', 2024)
                            ->get();
                    } elseif ($this->employeeStatus == 'interns') {
                        $this->records = EmployeeDetails::select('employee_details.*')
                            ->join('hr', 'employee_details.company_id', '=', 'hr.company_id')
                            ->where('employee_details.job_title', 'intern')
                            ->get();
                    }

                    return view('livewire.employee-directory');
                } catch (\Exception $e) {
                    Log::error('Error in render method: ' . $e->getMessage());
                    session()->flash('error', 'An error occurred while loading employee records. Please try again later.');
                    $this->records = collect(); // Set records to an empty collection in case of error
                    return view('livewire.employee-directory');
                }
    }

}
