<?php
/*
 * * File Name                       : EmployeeSwipesData.php
 * * Description                     : This file contains the implementation of all the employees who swiped in today and we can get the swipe record of the employees before todays date 
 * * Creator                         : Pranita Priyadarshi
 * * Email                           : priyadarshipranita72@gmail.com
 * * Organization                    : PayG.
 * * Date                            : 2023-12-07
 * * Framework                       : Laravel (10.10 Version)
 * * Programming Language            : PHP (8.1 Version)
 * * Database                        : MySQL
 * * Models                          : SwipeRecord,EmployeeDetails
*/

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use App\Models\SwipeRecord;
use App\Models\EmployeeDetails;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;
use Spatie\SimpleExcel\SimpleExcelWriter;

class EmployeeSwipesData extends Component
{
    public $employees;

    public $startDate;
    public $endDate;

    public $search;
    public $sw_ipes;
    public $notFound;
    public $selectedEmployee;
    public $deviceId;
    public $status;
    public $swipes;
    public $mobileId;

    public $flag = false;
    public $swipeTime = '';
    public $searchtest = 0;
    public $loggedInEmpId1;
    public $empid;
    public function mount()
    {
            try {
                $today = now()->startOfDay();

                $userSwipesToday = SwipeRecord::where('emp_id', Auth::guard('emp')->user()->emp_id)
                    ->where('created_at', '>=', $today)
                    ->where('created_at', '<', $today->copy()->endOfDay())
                    ->exists();

                $userSwipesToday1 = SwipeRecord::where('emp_id', Auth::guard('emp')->user()->emp_id)
                    ->where('created_at', '>=', $today)
                    ->where('created_at', '<', $today->copy()->endOfDay())
                    ->first();

                if ($userSwipesToday1) {
                    $agent = new Agent();

                    if ($agent->isMobile()) {
                        $this->status = 'Mobile';
                    } elseif ($agent->isDesktop()) {
                        $this->status = 'Desktop';
                    } else {
                        $this->status = '-';
                    }
                } else {
                    $this->status = '-';
                }
            } catch (\Exception $e) {
                    Log::error('Error in mount method: ' . $e->getMessage());
                    $this->status = 'Error';
            }
    }
    public function testMethod()
    {
        try
        {
         $currentDate = now()->toDateString();
         $loggedInEmpId = Auth::guard('emp')->user()->emp_id;
         $employees = EmployeeDetails::where('manager_id', $loggedInEmpId)->select('emp_id', 'first_name', 'last_name')->get();
         $this->swipes = SwipeRecord::whereIn('id', function ($query) use ($employees, $currentDate) {
             $query->selectRaw('MIN(id)')
                 ->from('swipe_records')
                 ->whereIn('emp_id', $employees->pluck('emp_id'))
                 ->whereDate('created_at', $currentDate)
                 ->groupBy('emp_id');
         })
             ->join('employee_details', 'swipe_records.emp_id', '=', 'employee_details.emp_id')
             ->when($this->search, function ($query) {
                 $query->where(function ($subQuery) {
                     $subQuery->where('first_name', 'like', '%' . $this->search . '%')
                         ->orWhere('last_name', 'like', '%' . $this->search . '%');
                 });
             })
             ->select('swipe_records.*', 'employee_details.first_name', 'employee_details.last_name')
             ->get();
         }catch (\Exception $e) {
             // Handle the exception and provide a user-friendly message
             Log::error('Error in test method: ' . $e->getMessage());
             // Optionally, you could also set an error state or return an error response
             session()->flash('error', 'An error occurred while performing the test method. Please try again.');
         }
    }
    //This method will show the list of all employees who swiped in and swiped out today 
    public function downloadFileforSwipes()
    {
        try
        {        
                $currentDate = now()->toDateString();
                $loggedInEmpId = Auth::guard('emp')->user()->emp_id;
                $this->loggedInEmpId1 = EmployeeDetails::where('emp_id', Auth::guard('emp')->user()->emp_id)->get();
                $this->loggedInEmpId1 = EmployeeDetails::where('emp_id', Auth::guard('emp')->user()->emp_id)->get();
                $employees = EmployeeDetails::where('manager_id', $loggedInEmpId)->select('emp_id', 'first_name', 'last_name')->get();
                $approvedLeaveRequests=LeaveRequest::join('employee_details', 'leave_applications.emp_id', '=', 'employee_details.emp_id')
                ->where('leave_applications.status', 'approved')
                ->whereIn('leave_applications.emp_id', $employees->pluck('emp_id'))
                ->whereDate('from_date', '<=', $currentDate)
                ->whereDate('to_date', '>=', $currentDate)
                ->get(['leave_applications.*', 'employee_details.first_name', 'employee_details.last_name'])
                ->map(function ($leaveRequest) {
                    // Calculate the number of days between from_date and to_date
                    $fromDate = \Carbon\Carbon::parse($leaveRequest->from_date);
                    $toDate = \Carbon\Carbon::parse($leaveRequest->to_date);
                    $leaveRequest->number_of_days = $fromDate->diffInDays($toDate) + 1; // Add 1 to include both start and end dates
                    return $leaveRequest;
                });
                if ($this->startDate && $this->endDate) {
                    $prev_date = $this->startDate;
                    $next_date = $this->endDate;
                    $this->swipes = SwipeRecord::whereIn('id', function ($query) use ($employees, $prev_date,  $next_date) {
                        $query->selectRaw('MIN(id)')
                            ->from('swipe_records')
                            ->whereIn('emp_id', $employees->pluck('emp_id'))
                            ->whereBetween('created_at', [$prev_date, $next_date])
                            ->groupBy('emp_id');
                    })
                        ->join('employee_details', 'swipe_records.emp_id', '=', 'employee_details.emp_id')
                        ->select('swipe_records.*', 'employee_details.first_name', 'employee_details.last_name')
                        ->get();
                } else {
                    $this->swipes = SwipeRecord::select('swipe_records.*', 'employee_details.first_name', 'employee_details.last_name')
                    ->join('employee_details', 'swipe_records.emp_id', '=', 'employee_details.emp_id')
                    ->whereNotIn('swipe_records.emp_id', $approvedLeaveRequests->pluck('emp_id')) // Specify swipe_records.emp_id
                    ->whereIn('swipe_records.emp_id', $employees->pluck('emp_id')) // Specify swipe_records.emp_id
                    ->whereDate('swipe_records.created_at', $currentDate)
                    ->orderBy('employee_details.first_name')
                    ->get();
                }
                $data = [
                    ['LIST OF PRESENT EMPLOYEES'],
                    ['Employee ID', 'Employee Name', 'Swipe Date', 'Swipe Time', 'Shift', 'In/Out', 'Door/Address', 'Status'],

                ];
                $employees1 = $this->swipes;
                foreach ($employees1 as $employee) {
                    $swipeTime1 = Carbon::parse($employee['created_at'])->format('d-m-Y'); // Format the date
                    $data[] = [$employee['emp_id'], $employee['first_name'] . ' ' . $employee['last_name'],  $employee['created_at']->format('d M, Y') , $employee['swipe_time'], '10:00 am to 07:00pm', $employee['in_or_out'], '-', '-'];
                }
                $filePath = storage_path('app/todays_present_employees.xlsx');
                SimpleExcelWriter::create($filePath)->addRows($data);  
                return response()->download($filePath, 'todays_present_employees.xlsx');
        }catch (\Exception $e) {
            // Handle the exception and provide a user-friendly message
            Log::error('Error downloading file for swipes: ' . $e->getMessage());
            // Optionally, you could also set an error state or return an error response
            session()->flash('error', 'An error occurred while downloading the file for swipes. Please try again.');
            return redirect()->back(); // Redirect back to the previous page
        }
    }
    public function render()
{
    try {
        $currentDate = now()->toDateString();
        $loggedInEmpId = Auth::guard('emp')->user()->emp_id;
        $employees = EmployeeDetails::where('manager_id', $loggedInEmpId)->select('emp_id', 'first_name', 'last_name')->get();
        
        $approvedLeaveRequests = LeaveRequest::join('employee_details', 'leave_applications.emp_id', '=', 'employee_details.emp_id')
            ->where('leave_applications.status', 'approved')
            ->whereIn('leave_applications.emp_id', $employees->pluck('emp_id'))
            ->whereDate('from_date', '<=', $currentDate)
            ->whereDate('to_date', '>=', $currentDate)
            ->get(['leave_applications.*', 'employee_details.first_name', 'employee_details.last_name'])
            ->map(function ($leaveRequest) {
                // Calculate the number of days between from_date and to_date
                $fromDate = Carbon::parse($leaveRequest->from_date);
                $toDate = Carbon::parse($leaveRequest->to_date);
        
                $leaveRequest->number_of_days = $fromDate->diffInDays($toDate) + 1; // Add 1 to include both start and end dates
        
                return $leaveRequest;
            });

        if ($this->startDate && $this->endDate) {
            $prev_date = $this->startDate;
            $next_date = $this->endDate;
            $this->swipes = SwipeRecord::whereIn('id', function ($query) use ($employees, $prev_date, $next_date) {
                $query->selectRaw('MIN(id)')
                    ->from('swipe_records')
                    ->whereIn('emp_id', $employees->pluck('emp_id'))
                    ->whereBetween('created_at', [$prev_date, $next_date])
                    ->groupBy('emp_id');
            })
            ->join('employee_details', 'swipe_records.emp_id', '=', 'employee_details.emp_id')
            ->select('swipe_records.*', 'employee_details.first_name', 'employee_details.last_name')
            ->orderBy('created_at')
            ->get();
        } else {
            $this->swipes = SwipeRecord::select('swipe_records.*', 'employee_details.first_name', 'employee_details.last_name')
                ->join('employee_details', 'swipe_records.emp_id', '=', 'employee_details.emp_id')
                ->whereNotIn('swipe_records.emp_id', $approvedLeaveRequests->pluck('emp_id')) // Specify swipe_records.emp_id
                ->whereIn('swipe_records.emp_id', $employees->pluck('emp_id')) // Specify swipe_records.emp_id
                ->whereDate('swipe_records.created_at', $currentDate)
                ->orderBy('employee_details.first_name')
                ->get();
        }

        $nameFilter = $this->search; // Assuming $this->search contains the name filter
        $this->swipes = $this->swipes->filter(function ($swipe) use ($nameFilter) {
            return stripos($swipe->first_name, $nameFilter) !== false || stripos($swipe->last_name, $nameFilter) !== false || stripos($swipe->emp_id, $nameFilter) !== false || stripos($swipe->swipe_time, $nameFilter) !== false;
        });

        if ($this->swipes->isEmpty()) {
            $this->notFound = true; // Set a flag indicating that the name was not found
        } else {
            $this->notFound = false;
        }

        $todaySwipeIN = SwipeRecord::where('emp_id', auth()->guard('emp')->user()->emp_id)->whereDate('created_at', $currentDate)->first();

        if ($todaySwipeIN) {
            // Swipe IN time for today
            $this->swipeTime = $todaySwipeIN->swipe_time;
        }

        return view('livewire.employee-swipes-data', [
            'LoggedInEmpId1' => $this->loggedInEmpId1,
            'SignedInEmployees' => $this->swipes,
            'SwipeTime' => $this->swipeTime,
            'SWIPES' => $this->swipes
        ]);
    } catch (\Exception $e) {
        // Handle the exception (e.g., log it, set a default value, or show an error message)
        Log::error('Error in render method: ' . $e->getMessage());
        $this->swipes = collect();
        $this->notFound = true;
        $this->swipeTime = null;

        return view('livewire.employee-swipes-data', [
            'LoggedInEmpId1' => $this->loggedInEmpId1,
            'SignedInEmployees' => $this->swipes,
            'SwipeTime' => $this->swipeTime,
            'SWIPES' => $this->swipes
        ]);
    }
}

}
