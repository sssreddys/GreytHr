<?php

namespace App\Livewire;

use App\Models\EmployeeDetails;
use App\Models\RegularisationDates;
use Carbon\Carbon;
use Google\Service\SecretManager\EnableSecretVersionRequest;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Spatie\SimpleExcel\SimpleExcelWriter;

class AttendanceRegularisationReport extends Component
{
    public $showAttendanceRegularisationReportDialog = true;

    public $selectedStatus = '';

    public $employees;
    public $fromDate;

    public $toDate;

   
   
    public $regularisationDetails;

    public $EmployeeId=[];
    public function updateEmployeeId()
    {
        $this->EmployeeId=$this->EmployeeId;
        
    }
    public function updateselectedStatus()
    {
        $this->selectedStatus = $this->selectedStatus;
    }
    public function updatefromDate()
    {
        $this->fromDate = $this->fromDate;
    }
    public function updatetoDate()
    {
        $this->toDate = $this->toDate;
    }
    public function resetFields()
    {
        $this->EmployeeId=[];
        $this->fromDate='';
        $this->toDate='';
        $this->selectedStatus='';
    }
    public function  downloadAttendanceRegularisationReportInExcel()
    {
        $loggedInEmpId = Auth::guard('emp')->user()->emp_id;
        $employees = EmployeeDetails::where('manager_id', $loggedInEmpId)->select('emp_id', 'first_name', 'last_name')->get();
     
        $employeeIds = $this->employees->pluck('emp_id')->toArray();
          
        if(empty($this->EmployeeId))
        {
            return redirect()->back()->with('error', 'Please Select at least one employee detail');
        }
        elseif(empty($this->fromDate)&&empty($this->fromDate))
        {
            return redirect()->back()->with('error', 'Please Select fromDate and ToDate');
        }
        elseif(empty($this->fromDate))
        {
            return redirect()->back()->with('error', 'Please Select fromDate');
        }
        elseif(empty($this->toDate))
        {
            return redirect()->back()->with('error', 'Please Select toDate');
        }
        elseif(empty($this->selectedStatus))
        {
            return redirect()->back()->with('error', 'Please Select Status');
        }
        else
        {
            if ($this->selectedStatus == 'applied') {
                if ($this->fromDate && $this->toDate) {
                    $this->regularisationDetails = RegularisationDates::whereIn('regularisation_dates.emp_id', $this->selectedEmployees)
                        ->where('regularisation_dates.status', 'pending')
                        ->where('regularisation_dates.is_withdraw', 0)
                        ->whereDate('regularisation_dates.created_at', '>=', $this->fromDate)
                        ->whereDate('regularisation_dates.created_at', '<=', $this->toDate)
                        ->join('employee_details', 'regularisation_dates.emp_id', '=', 'employee_details.emp_id')
                        ->select('regularisation_dates.*', 'employee_details.first_name', 'employee_details.last_name')
                        ->get();
                } else {
                    $this->regularisationDetails = RegularisationDates::whereIn('regularisation_dates.emp_id', $this->selectedEmployees)
                        ->where('regularisation_dates.status', 'pending')
                        ->where('regularisation_dates.is_withdraw', 0)
                        ->join('employee_details', 'regularisation_dates.emp_id', '=', 'employee_details.emp_id')
                        ->select('regularisation_dates.*', 'employee_details.first_name', 'employee_details.last_name')
                        ->get();
                }
            } elseif ($this->selectedStatus == 'withdrawn') {
                if ($this->fromDate && $this->toDate) {
                    $this->regularisationDetails = RegularisationDates::whereIn('regularisation_dates.emp_id', $this->selectedEmployees)
                        ->where('regularisation_dates.status', 'pending')
                        ->where('regularisation_dates.is_withdraw', 1)
                        ->whereDate('regularisation_dates.withdrawn_date', '>=', $this->fromDate)
                        ->whereDate('regularisation_dates.withdrawn_date', '<=', $this->toDate)
                        ->join('employee_details', 'regularisation_dates.emp_id', '=', 'employee_details.emp_id')
                        ->select('regularisation_dates.*', 'employee_details.first_name', 'employee_details.last_name')
                        ->get();
                } else {
                    $this->regularisationDetails = RegularisationDates::whereIn('regularisation_dates.emp_id', $this->selectedEmployees)
                        ->where('regularisation_dates.status', 'pending')
                        ->where('regularisation_dates.is_withdraw', 1)
     
                        ->join('employee_details', 'regularisation_dates.emp_id', '=', 'employee_details.emp_id')
                        ->select('regularisation_dates.*', 'employee_details.first_name', 'employee_details.last_name')
                        ->get();
                }
            } elseif ($this->selectedStatus == 'approved') {
                if ($this->fromDate && $this->toDate) {
                    $this->regularisationDetails = RegularisationDates::whereIn('regularisation_dates.emp_id', $this->EmployeeId)
                        ->where('regularisation_dates.status', $this->selectedStatus)
                        ->whereDate('regularisation_dates.approved_date', '>=', $this->fromDate)
                        ->whereDate('regularisation_dates.approved_date', '<=', $this->toDate)
                        ->join('employee_details', 'regularisation_dates.emp_id', '=', 'employee_details.emp_id')
                        ->select('regularisation_dates.*', 'employee_details.first_name', 'employee_details.last_name')
                        ->get();
                } else {
                    $this->regularisationDetails = RegularisationDates::whereIn('regularisation_dates.emp_id', $this->EmployeeId)
                        ->where('regularisation_dates.status', $this->selectedStatus)
                        ->join('employee_details', 'regularisation_dates.emp_id', '=', 'employee_details.emp_id')
                        ->select('regularisation_dates.*', 'employee_details.first_name', 'employee_details.last_name')
                        ->get();
                }
            } elseif ($this->selectedStatus == 'rejected') {
                if ($this->fromDate && $this->toDate) {
                    $this->regularisationDetails = RegularisationDates::whereIn('regularisation_dates.emp_id', $this->EmployeeId)
                        ->where('regularisation_dates.status', $this->selectedStatus)
                        ->whereDate('regularisation_dates.rejected_date', '>=', $this->fromDate)
                        ->whereDate('regularisation_dates.rejected_date', '<=', $this->toDate)
                        ->join('employee_details', 'regularisation_dates.emp_id', '=', 'employee_details.emp_id')
                        ->select('regularisation_dates.*', 'employee_details.first_name', 'employee_details.last_name')
                        ->get();
                } else {
                    $this->regularisationDetails = RegularisationDates::whereIn('regularisation_dates.emp_id', $this->EmployeeId)
                        ->where('regularisation_dates.status', $this->selectedStatus)
                        ->join('employee_details', 'regularisation_dates.emp_id', '=', 'employee_details.emp_id')
                        ->select('regularisation_dates.*', 'employee_details.first_name', 'employee_details.last_name')
                        ->get();
                }
            }
            // $this->regularisationDetails = RegularisationDates::whereIn('regularisation_dates.emp_id', $this->EmployeeId)
            // ->where('regularisation_dates.status', $this->selectedStatus)
            // ->join('employee_details', 'regularisation_dates.emp_id', '=', 'employee_details.emp_id')
            // ->select('regularisation_dates.*', 'employee_details.first_name', 'employee_details.last_name')
            // ->get();
            $data = [
                ['List of Attendance Regularisation Employees from ' . Carbon::parse($this->fromDate)->format('jS F, Y') . 'to' . Carbon::parse($this->toDate)->format('jS F, Y')],
                ['Employee ID', 'Name', 'Status', 'Date'],
    
            ];
            foreach ($this->regularisationDetails as $employee) {
                if ($employee['status'] == 'pending' && $employee['is_withdraw'] == 0) {
                    $createdAtDate = Carbon::parse($employee['created_at'])->format('Y-m-d');
                    $data[] = [$employee['emp_id'], $employee['first_name'] . ' ' . $employee['last_name'], 'Applied', $createdAtDate];
                } elseif ($employee['status'] == 'pending' && $employee['is_withdraw'] == 1) {
                    $data[] = [$employee['emp_id'], $employee['first_name'] . ' ' . $employee['last_name'], 'Withdrawn', $employee['withdrawn_date']];
                } elseif ($employee['status'] == 'approved') {
                    $data[] = [$employee['emp_id'], $employee['first_name'] . ' ' . $employee['last_name'], $employee['status'], $employee['approved_date']];
                } elseif ($employee['status'] == 'rejected') {
                    $data[] = [$employee['emp_id'], $employee['first_name'] . ' ' . $employee['last_name'], $employee['status'], $employee['rejected_date']];
                }
            }
        
        $filePath = storage_path('app/attendance_regularisation_report.xlsx');
        SimpleExcelWriter::create($filePath)->addRows($data);
        return response()->download($filePath, 'attendance_regularisation_report.xlsx');
        }
    }
    public function closeAttendanceRegularisationReport()
    {
        $this->showAttendanceRegularisationReportDialog = false;
    }
    public function render()
    {
        $loggedInEmpId = Auth::guard('emp')->user()->emp_id;
        $this->employees = EmployeeDetails::where('manager_id', $loggedInEmpId)->select('emp_id', 'first_name', 'last_name','employee_status')->get();
        $employeeIds = $this->employees->pluck('emp_id')->toArray();
       
            return view('livewire.attendance-regularisation-report');
    }
}
