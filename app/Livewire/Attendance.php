<?php
/*Created by:Pranita Priyadarshi*/
/*This submodule will be showing users swipe time and also ur attendance status*/
// File Name                       : Attendance.php
// Description                     : This file contains the implementation of a EmployeesAttendance by this we can know attendance of particular employees in a month.
// Creator                         : Pranita Priyadarshi
// Email                           : priyadarshipranita72@gmail.com
// Organization                    : PayG.
// Date                            : 2023-03-07
// Framework                       : Laravel (10.10 Version)
// Programming Language            : PHP (8.1 Version)
// Database                        : MySQL
// Models                          : LeaveRequest,EmployeeDetails,HolidayCalendar,SwipeRecord
namespace App\Livewire;

use App\Models\EmployeeDetails;
use App\Models\SwipeRecord;
use App\Models\HolidayCalendar;
use App\Models\LeaveRequest;
use App\Models\RegularisationNew1;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Attendance extends Component
{
    public $currentDate2;
    public $hours;
    public $minutesFormatted;
    public $last_out_time;
    public $currentDate;
    public $date1;
    public $clickedDate;
    public $currentWeekday;
    public $calendar;
    public $selectedDate;
    public $shortFallHrs;
    public $work_hrs_in_shift_time;
    public $swipe_record;
    public $holiday;
    public $leaveApplies;
    public $first_in_time;
    public $year;
    public $currentDate2record;
    public $month;
    public $actualHours = [];
    public $firstSwipeTime;
    public $secondSwipeTime;
    public $swiperecords;
    public $currentDate1;
    public $swiperecord;
    public $showCalendar = true;
    public $date2;
    public $modalTitle = '';
    public $view_student_swipe_time;
    public $view_student_in_or_out;
    public $swipeRecordId;
    public $from_date;
    public $to_date;
    public $status;
    public $dynamicDate;
    public $view_student_emp_id;
    public $view_employee_swipe_time;
    public $currentDate2recordexists;
    public $dateclicked;
    public $view_table_in;
    public $view_table_out;
    public $employeeDetails;
    public $changeDate = 0;
    public $student;
    public $selectedRecordId = null;
    public $distinctDates;
    public $isPresent;
    public $table;
     public $previousMonth;
    public $session1 = 0;
    public $session2 = 0;
    public $session1ArrowDirection = 'right';
    public $session2ArrowDirection = 'right';
    public $avgSwipeInTime=null;
    public $avgSwipeOutTime=null;
    public $totalDays;
    public $avgWorkHours = 0;
    public $avgLateIn = 0;
    public $avgEarlyOut = 0;

    public $k, $k1;
    public $showMessage = false;
    //This function will help us to toggle the arrow present in session fields
    public function toggleSession1Fields()
{
    try {
        $this->session1 = !$this->session1;
        $this->session1ArrowDirection = ($this->session1) ? 'left' : 'right';
    } catch (\Exception $e) {
        // Log the exception
        Log::error('Error in toggleSession1Fields method: ' . $e->getMessage());

        // Optionally, you can set some default values or handle the error in a user-friendly way
        $this->session1 = false;
        $this->session1ArrowDirection = 'right';

        // You can also set a session message or an error message to inform the user
        session()->flash('error', 'An error occurred while toggling session fields. Please try again later.');
    }
}
//This function will help us to toggle the arrow present in session fields
public function toggleSession2Fields()
{
    try {
        $this->session2 = !$this->session2;
        $this->session2ArrowDirection = ($this->session2) ? 'left' : 'right';
        // dd($this->session1);
    } catch (\Exception $e) {
        // Log the exception
        Log::error('Error in toggleSession2Fields method: ' . $e->getMessage());

        // Optionally, you can set some default values or handle the error in a user-friendly way
        $this->session2 = false;
        $this->session2ArrowDirection = 'right';

        // You can also set a session message or an error message to inform the user
        session()->flash('error', 'An error occurred while toggling session fields. Please try again later.');
    }
}

public function mount()
{
    try {
        //insights
        $this->from_date = now()->startOfMonth()->toDateString();
        $this->to_date = now()->toDateString();
        $this->updateModalTitle();
        $this->calculateTotalDays();

        $this->previousMonth = Carbon::now()->subMonth()->format('F');

        $swipeRecords = SwipeRecord::where('emp_id', auth()->guard('emp')->user()->emp_id)->get();

        // Group the swipe records by the date part only
        $groupedDates = $swipeRecords->groupBy(function ($record) {
            return Carbon::parse($record->created_at)->format('Y-m-d');
        });

        $this->distinctDates = $groupedDates->mapWithKeys(function ($records, $key) {
            $inRecord = $records->where('in_or_out', 'IN')->first();
            $outRecord = $records->where('in_or_out', 'OUT')->last();

            return [
                $key => [
                    'in' => "IN",
                    'first_in_time' => optional($inRecord)->swipe_time,
                    'last_out_time' => optional($outRecord)->swipe_time,
                    'out' => "OUT",
                ]
            ];
        });

        // Get the current date and store it in the $currentDate property
        $this->currentDate = date('d');
        $this->currentWeekday = date('D');
        $this->currentDate1 = date('d M Y');
        $this->swiperecords = SwipeRecord::all();
        $this->year = now()->year;
        $this->month = now()->month;
        $this->generateCalendar();
    } catch (\Exception $e) {
        // Log the exception
        Log::error('Error in mount method: ' . $e->getMessage());

        // Handle the error in a user-friendly way, e.g., setting default values
        $this->from_date = now()->startOfMonth()->toDateString();
        $this->to_date = now()->toDateString();
        $this->distinctDates = collect();
        $this->currentDate = date('d');
        $this->currentWeekday = date('D');
        $this->currentDate1 = date('d M Y');
        $this->swiperecords = collect();
        $this->year = now()->year;
        $this->month = now()->month;

        // Optionally, you can set a session message or an error message to inform the user
        session()->flash('error', 'An error occurred while initializing the component. Please try again later.');
    }
}


public function showBars()
{
    try {
        $this->showMessage = false;
    } catch (\Exception $e) {
        Log::error('Error in showBars method: ' . $e->getMessage());
        session()->flash('error', 'An error occurred while showing the bars. Please try again later.');
    }
}
//This function will help us to calculate the number of public holidays in a particular month
protected function getPublicHolidaysForMonth($year, $month)
{
    try {
        return HolidayCalendar::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get();
    } catch (\Exception $e) {
        Log::error('Error in getPublicHolidaysForMonth method: ' . $e->getMessage());
        session()->flash('error', 'An error occurred while fetching public holidays. Please try again later.');
        return collect(); // Return an empty collection to handle the error gracefully
    }
}

public function showlargebox($k)
{
    try {
        $this->k1 = $k;
        $this->dispatchBrowserEvent('refreshModal', ['k1' => $this->k1]);
    } catch (\Exception $e) {
        Log::error('Error in showlargebox method: ' . $e->getMessage());
        session()->flash('error', 'An error occurred while showing the large box. Please try again later.');
    }
}
//This function will help us to check if the employee is present on this particular date or not
private function isEmployeePresentOnDate($date)
{
    try {
        $employeeId = auth()->guard('emp')->user()->emp_id;
        return SwipeRecord::where('emp_id', $employeeId)->whereDate('created_at', $date)->exists();
    } catch (\Exception $e) {
        Log::error('Error in isEmployeePresentOnDate method: ' . $e->getMessage());
        session()->flash('error', 'An error occurred while checking employee presence. Please try again later.');
        return false; // Return false to handle the error gracefully
    }
}
//This function will help us to check if the employee is on leave for this particular date or not
private function isEmployeeLeaveOnDate($date, $employeeId)
{
    try {
        $employeeId = auth()->guard('emp')->user()->emp_id;
        return LeaveRequest::where('emp_id', $employeeId)
            ->where('status', 'approved')
            ->where(function ($query) use ($date) {
                $query->whereDate('from_date', '<=', $date)
                    ->whereDate('to_date', '>=', $date);
            })
            ->exists();
    } catch (\Exception $e) {
        Log::error('Error in isEmployeeLeaveOnDate method: ' . $e->getMessage());
        session()->flash('error', 'An error occurred while checking employee leave. Please try again later.');
        return false; // Return false to handle the error gracefully
    }
}
//This function will help us to check the leave type of employee
private function getLeaveType($date, $employeeId)
{
    try {
        return LeaveRequest::where('emp_id', $employeeId)
            ->whereDate('from_date', '<=', $date)
            ->whereDate('to_date', '>=', $date)
            ->value('leave_type');
    } catch (\Exception $e) {
        Log::error('Error in getLeaveType method: ' . $e->getMessage());
        session()->flash('error', 'An error occurred while fetching leave type. Please try again later.');
        return null; // Return null to handle the error gracefully
    }
}
private function isDateRegularized($date, $employeeId)
{
    $records = RegularisationNew1::where('emp_id', $employeeId)->get();

    foreach ($records as $record) {
        $regularisationEntries = json_decode($record->regularisation_entries, true);
        foreach ($regularisationEntries as $entry) {
            if ($entry['date'] === $date) {
                return true;
            }
        }
    }

    return false;
}

//This function will help us to create the calendar
public function generateCalendar()
{
    try {
        $employeeId = auth()->guard('emp')->user()->emp_id;
        $firstDay = Carbon::create($this->year, $this->month, 1);
        $daysInMonth = $firstDay->daysInMonth;
        $today = now();
        $calendar = [];
        $dayCount = 1;
        $publicHolidays = $this->getPublicHolidaysForMonth($this->year, $this->month);

        // Calculate the first day of the week for the current month
        $firstDayOfWeek = $firstDay->dayOfWeek;

        // Calculate the starting date of the previous month
        $startOfPreviousMonth = $firstDay->copy()->subMonth();

        // Fetch holidays for the previous month
        $publicHolidaysPreviousMonth = $this->getPublicHolidaysForMonth(
            $startOfPreviousMonth->year,
            $startOfPreviousMonth->month
        );

        // Calculate the last day of the previous month
        $lastDayOfPreviousMonth = $firstDay->copy()->subDay();

        for ($i = 0; $i < ceil(($firstDayOfWeek + $daysInMonth) / 7); $i++) {
            $week = [];
            for ($j = 0; $j < 7; $j++) {
                if ($i === 0 && $j < $firstDay->dayOfWeek) {
                    // Add the days of the previous month
                    $previousMonthDays = $lastDayOfPreviousMonth->copy()->subDays($firstDay->dayOfWeek - $j - 1);
                    $week[] = [
                        'day' => $previousMonthDays->day,
                        'isToday' => false,
                        'isPublicHoliday' => in_array($previousMonthDays->toDateString(), $publicHolidaysPreviousMonth->pluck('date')->toArray()),
                        'isCurrentMonth' => false,
                        'isPreviousMonth' => true,
                        'backgroundColor' => '',
                        'status' => '',
                        'onleave' => ''
                    ];
                } elseif ($dayCount <= $daysInMonth) {
                    $isToday = $dayCount === $today->day && $this->month === $today->month && $this->year === $today->year;
                    $isPublicHoliday = in_array(
                        Carbon::create($this->year, $this->month, $dayCount)->toDateString(),
                        $publicHolidays->pluck('date')->toArray()
                    );

                    $backgroundColor = $isPublicHoliday ? 'background-color: IRIS;' : '';

                    $date = Carbon::create($this->year, $this->month, $dayCount)->toDateString();

                    // Check if the employee is absent
                    $isAbsent = !$this->isEmployeePresentOnDate($date);
                    $isonLeave = $this->isEmployeeLeaveOnDate($date, $employeeId);
                    $leaveType = $this->getLeaveType($date, $employeeId);
                    if ($isonLeave) {
                        $leaveType = $this->getLeaveType($date, $employeeId);

                        switch ($leaveType) {
                            case 'Causal Leave Probation':
                                $status = 'CLP'; // Casual Leave Probation
                                break;
                            case 'Sick Leave':
                                $status = 'SL'; // Sick Leave
                                break;
                            case 'Loss Of Pay':
                                $status = 'LOP'; // Loss of Pay
                                break;
                            default:
                                $status = 'L'; // Default to 'L' if the leave type is not recognized
                                break;
                        }
                    } else {
                        // Employee is not on leave, check for absence or presence
                        $isAbsent = !$this->isEmployeePresentOnDate($date);

                        // Set the status based on presence
                        $status = $isAbsent ? 'A' : 'P';
                    }
                    // Set the status based on presence
                    $week[] = [
                        'day' => $dayCount,
                        'isToday' => $isToday,
                        'isPublicHoliday' => $isPublicHoliday,
                        'isCurrentMonth' => true,
                        'isPreviousMonth' => false,
                        'backgroundColor' => $backgroundColor,
                        'status' => $status,
                    ];

                    $dayCount++;
                } else {
                    $week[] = [
                        'day' => $dayCount - $daysInMonth,
                        'isToday' => false,
                        'isPublicHoliday' => in_array($lastDayOfPreviousMonth->copy()->addDays($dayCount - $daysInMonth)->toDateString(), $this->getPublicHolidaysForMonth($startOfPreviousMonth->year, $startOfPreviousMonth->month)->pluck('date')->toArray()),
                        'isCurrentMonth' => false,
                        'isNextMonth' => true,
                        'backgroundColor' => '', 
                        'status' => '',
                    ];
                    $dayCount++;
                }
            }
            $calendar[] = $week;
        }

        $this->calendar = $calendar;
    } catch (\Exception $e) {
        Log::error('Error in generateCalendar method: ' . $e->getMessage());
        session()->flash('error', 'An error occurred while generating the calendar. Please try again later.');
        $this->calendar = []; // Set calendar to empty array in case of error
    }
}
//This function will help us to check the details related to the particular date in the calendar
public function updateDate($date1)
{
    try {
        $parsedDate = Carbon::parse($date1);

        if ($parsedDate->format('Y-m-d') < Carbon::now()->format('Y-m-d')) {
            $this->changeDate = 1;
        }
    } catch (\Exception $e) {
        Log::error('Error in updateDate method: ' . $e->getMessage());
        session()->flash('error', 'An error occurred while updating the date. Please try again later.');
    }
}
//This function will help us to check whether the employee is absent 'A' or present 'P'
public function dateClicked($date1)
{
    try {
        $date1 = trim($date1);
        $this->selectedDate = $this->year . '-' . $this->month . '-' . str_pad($date1, 2, '0', STR_PAD_LEFT);
        $isSwipedIn = SwipeRecord::whereDate('created_at', $date1)->where('in_or_out', 'In')->exists();
        $isSwipedOut = SwipeRecord::whereDate('created_at', $date1)->where('in_or_out', 'Out')->exists();

        if (!$isSwipedIn) {
            // Employee did not swipe in
            $this->selectedDate = $date1;
            $this->status = 'A';
        } elseif (!$isSwipedOut) {
            // Employee swiped in but not out
            $this->selectedDate = $date1;
            $this->status = 'P';
        }
        $this->updateDate($date1);
        $this->dateclicked = $date1;
    } catch (\Exception $e) {
        Log::error('Error in dateClicked method: ' . $e->getMessage());
        session()->flash('error', 'An error occurred while processing the date click. Please try again later.');
    }
}

public function updatedFromDate($value)
{
    try {
        // Additional logic if needed when from_date is updated
        $this->from_date = $value;
        $this->updateModalTitle();
    } catch (\Exception $e) {
        Log::error('Error in updatedFromDate method: ' . $e->getMessage());
        session()->flash('error', 'An error occurred while updating the from date. Please try again later.');
    }
}

public function updatedToDate($value)
{
    try {
        // Additional logic if needed when to_date is updated
        $this->to_date = $value;
        $this->updateModalTitle();
    } catch (\Exception $e) {
        Log::error('Error in updatedToDate method: ' . $e->getMessage());
        session()->flash('error', 'An error occurred while updating the to date. Please try again later.');
    }
}


private function updateModalTitle()
{
    try {
        // Format the dates and update the modal title
        $formattedFromDate = Carbon::parse($this->from_date)->format('d M');
        $formattedToDate = Carbon::parse($this->to_date)->format('d M');
        $this->modalTitle = "Insights for Attendance Period $formattedFromDate - $formattedToDate";
    } catch (\Exception $e) {
        Log::error('Error in updateModalTitle method: ' . $e->getMessage());
        session()->flash('error', 'An error occurred while updating the modal title. Please try again later.');
    }
}    
public function calculateTotalDays()
{
    try {
        $employeeId = auth()->guard('emp')->user()->emp_id;
        $startDate = Carbon::parse($this->from_date);
        $endDate = Carbon::parse($this->to_date);
        $originalEndDate = $endDate->copy();

        if ($endDate->isToday()) {
            // Exclude today's date
            $endDate->subDay();
        }

        $totalDays = $this->calculateWorkingDays($startDate, $endDate, $employeeId);

        $swipeRecords = SwipeRecord::where('emp_id', $employeeId)
            ->whereBetween('created_at', [$startDate, $originalEndDate])
            ->get();

        // Group the swipe records by the date part only
        $groupedDates = $swipeRecords->groupBy(function ($record) {
            return Carbon::parse($record->created_at)->format('Y-m-d');
        });

        $totalLateIn = 0;
        $totalEarlyOut = 0;
        $totalValidDays = 0;
        $signInTotalTime = 0;
        $signOutTotalTime = 0;
        $averageSwipeInTime = [];
        $averageSwipeOutTime = [];

        foreach ($swipeRecords as $records) {
            if ($records->in_or_out === 'IN') {
                // Calculate sign-in time
                $signInTime = Carbon::parse($records->swipe_time);
                $averageSwipeInTime[] = $signInTime;

                // Calculate total late-In
                $inTime = Carbon::parse($records->swipe_time);
                $expectedInTime = Carbon::parse('10:00:00');
                if ($inTime->gt($expectedInTime)) {
                    $totalLateIn++;
                }
            } elseif ($records->in_or_out === 'OUT') {
                // Calculate sign-out time
                $signOutTime = Carbon::parse($records->swipe_time);
                $averageSwipeOutTime[] = $signOutTime;

                $outTime = Carbon::parse($records->swipe_time);
                $expectedOutTime = Carbon::parse('19:00:00');
                if ($outTime->lt($expectedOutTime)) {
                    $totalEarlyOut++;
                }
            }
            $totalValidDays++;
        }

        // Calculate the total time for swipe-in
        foreach ($averageSwipeInTime as $time) {
            $signInTotalTime += $time->timestamp;
        }

        // Calculate the total time for swipe-out
        foreach ($averageSwipeOutTime as $time) {
            $signOutTotalTime += $time->timestamp;
        }

        // Calculate average swipe-in time if there are valid days
        if (count($averageSwipeInTime) > 0) {
            $avgSwipeInTimestamp = $signInTotalTime / count($averageSwipeInTime);
            // Create Carbon instance representing the average swipe-in time
            $averageSwipeInTime = Carbon::createFromTimestamp($avgSwipeInTimestamp);
            // Format the resulting time
            $this->avgSwipeInTime = $averageSwipeInTime->format('H:i');
        }

        // Calculate average swipe-out time if there are valid days
        if (count($averageSwipeOutTime) > 0) {
            $avgSwipeOutTimestamp = $signOutTotalTime / count($averageSwipeOutTime);
            // Create Carbon instance representing the average swipe-out time
            $averageSwipeOutTime = Carbon::createFromTimestamp($avgSwipeOutTimestamp);
            // Format the resulting time
            $this->avgSwipeOutTime = $averageSwipeOutTime->format('H:i');
        }

        // Calculate total(LateIn,EarlyOut)
        $this->avgLateIn = $totalLateIn;
        $this->avgEarlyOut = $totalEarlyOut;
        $this->totalDays = $totalDays;
    } catch (\Exception $e) {
        Log::error('Error in calculateTotalDays method: ' . $e->getMessage());
        session()->flash('error', 'An error occurred while calculating total days. Please try again later.');
    }
}
private function calculateWorkingDays($startDate, $endDate, $employeeId)
{
    try {
        $workingDays = 0;
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            if ($currentDate->isWeekday() && !$this->isEmployeeLeaveOnDate($currentDate, $employeeId) && $this->isEmployeePresentOnDate($currentDate)) {
                $workingDays++;
            }
            $currentDate->addDay();
        }

        return $workingDays;
    } catch (\Exception $e) {
        Log::error('Error in calculateWorkingDays method: ' . $e->getMessage());
        session()->flash('error', 'An error occurred while calculating working days. Please try again later.');
        return 0;
    }
}

private function calculateActualHours($swipe_records)
{
    try {
        $this->actualHours = [];

        for ($i = 0; $i < count($swipe_records) - 1; $i += 2) {
            $firstSwipeTime = strtotime($swipe_records[$i]->swipe_time);
            $secondSwipeTime = strtotime($swipe_records[$i + 1]->swipe_time);

            $timeDifference = $secondSwipeTime - $firstSwipeTime;

            $hours = floor($timeDifference / 3600);
            $minutes = floor(($timeDifference % 3600) / 60);

            $this->actualHours[] = sprintf("%02dhrs %02dmins", $hours, $minutes);
        }
    } catch (\Exception $e) {
        Log::error('Error in calculateActualHours method: ' . $e->getMessage());
        session()->flash('error', 'An error occurred while calculating actual hours. Please try again later.');
    }
}
public function viewDetails($id)
{
    try {
        $student = SwipeRecord::find($id);
        $this->view_student_emp_id = $student->emp_id;
        $this->view_student_swipe_time = $student->swipe_time;
        $this->view_student_in_or_out = $student->in_or_out;
        $this->showSR = true;
    } catch (\Exception $e) {
        Log::error('Error in viewDetails method: ' . $e->getMessage());
        session()->flash('error', 'An error occurred while viewing details. Please try again later.');
    }
}
public function closeViewStudentModal()
{
    try {
        $this->view_student_emp_id = '';
        $this->view_student_swipe_time = '';
        $this->view_student_in_or_out = '';
    } catch (\Exception $e) {
        Log::error('Error in closeViewStudentModal method: ' . $e->getMessage());
        session()->flash('error', 'An error occurred while closing view student modal. Please try again later.');
    }
}
    public $show = false;
    public $show1 = false;
    public function showViewStudentModal()
    {
        try {
            $this->show = true;
        } catch (\Exception $e) {
            Log::error('Error in showViewStudentModal method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while showing view student modal. Please try again later.');
        }
    }
    
    public function showViewTableModal()
    {
        try {
            $this->show1 = true;
        } catch (\Exception $e) {
            Log::error('Error in showViewTableModal method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while showing view table modal. Please try again later.');
        }
    }

    public $showSR = false;
    public function openSwipes()
    {
            try {
                $this->showSR = true;
            } catch (\Exception $e) {
                Log::error('Error in openSwipes method: ' . $e->getMessage());
                session()->flash('error', 'An error occurred while opening swipes. Please try again later.');
            }
    }
    public function closeSWIPESR()
    {
            try {
                $this->showSR = false;
            } catch (\Exception $e) {
                Log::error('Error in closeSWIPESR method: ' . $e->getMessage());
                session()->flash('error', 'An error occurred while closing SWIPESR. Please try again later.');
            }
    }
    public function close1()
    {
        try {
            $this->show1 = false;
        } catch (\Exception $e) {
            Log::error('Error in close1 method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while closing 1. Please try again later.');
        }
    }
    public function beforeMonth()
    {
            try {
                $date = Carbon::create($this->year, $this->month, 1)->subMonth();
                $this->year = $date->year;
                $this->month = $date->month;
                $this->generateCalendar();
            } catch (\Exception $e) {
                Log::error('Error in beforeMonth method: ' . $e->getMessage());
                session()->flash('error', 'An error occurred while navigating to the previous month. Please try again later.');
            }
    }

    public function nextMonth()
    {
        try {
            $date = Carbon::create($this->year, $this->month, 1)->addMonth();
            $this->year = $date->year;
            $this->month = $date->month;
            $this->generateCalendar();
        } catch (\Exception $e) {
            Log::error('Error in nextMonth method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while navigating to the next month. Please try again later.');
        }
    }
    public function render()
    {
        try {
            $this->dynamicDate = now()->format('Y-m-d');
            $currentDate = Carbon::now()->format('Y-m-d');
            $holiday = HolidayCalendar::all();
    
            $today = Carbon::today();
            $data = SwipeRecord::join('employee_details', 'swipe_records.emp_id', '=', 'employee_details.emp_id')
                ->where('swipe_records.emp_id', auth()->guard('emp')->user()->emp_id)
                ->whereDate('swipe_records.created_at', $today)
                ->select('swipe_records.*', 'employee_details.first_name', 'employee_details.last_name')
                ->get();
            $this->holiday = HolidayCalendar::all();
            $this->leaveApplies = LeaveRequest::where('emp_id', auth()->guard('emp')->user()->emp_id)->get();
    
            if ($this->changeDate == 1) {
                $currentDate2 = $this->dateclicked;
    
                $this->currentDate2record = SwipeRecord::where('emp_id', auth()->guard('emp')->user()->emp_id)->whereDate('created_at', $currentDate2)->get();
    
                if (!empty($this->currentDate2record) && isset($this->currentDate2record[0]) && isset($this->currentDate2record[1])) {
                    $this->first_in_time = substr($this->currentDate2record[0]['swipe_time'], 0, 5);
                    $this->last_out_time = substr($this->currentDate2record[1]['swipe_time'], 0, 5);
                    $firstInTime = Carbon::createFromFormat('H:i', $this->first_in_time);
                    $lastOutTime = Carbon::createFromFormat('H:i', $this->last_out_time);
    
                    if ($lastOutTime < $firstInTime) {
                        $lastOutTime->addDay();
                    }
    
                    if ($lastOutTime < $firstInTime) {
                        $lastOutTime->addDay();
                    }
    
                    $timeDifferenceInMinutes = $lastOutTime->diffInMinutes($firstInTime);
                    $this->hours = floor($timeDifferenceInMinutes / 60);
                    $minutes = $timeDifferenceInMinutes % 60;
                    $this->minutesFormatted = str_pad($minutes, 2, '0', STR_PAD_LEFT);
                } elseif (!isset($this->currentDate2record[1]) && isset($this->currentDate2record[0])) {
                    $this->first_in_time = substr($this->currentDate2record[0]['swipe_time'], 0, 5);
                    $this->last_out_time = substr($this->currentDate2record[0]['swipe_time'], 0, 5);
                } else {
                    $this->first_in_time = '-';
                    $this->last_out_time = '-';
                }
                if ($this->first_in_time == $this->last_out_time) {
                    $this->shortFallHrs = '08:59';
                    $this->work_hrs_in_shift_time = '-';
                } else {
                    $this->shortFallHrs = '-';
                    $this->work_hrs_in_shift_time = '09:00';
                }
                $this->currentDate2recordexists = SwipeRecord::where('emp_id', auth()->guard('emp')->user()->emp_id)->whereDate('created_at', $currentDate2)->exists();
            } else {
                $currentDate2 = Carbon::now()->format('Y-m-d');
            }
    
            $swipe_records = SwipeRecord::where('emp_id', auth()->guard('emp')->user()->emp_id)->whereDate('created_at', $currentDate)->get();
            $swipe_records1 = SwipeRecord::where('emp_id', auth()->guard('emp')->user()->emp_id)->orderBy('created_at', 'desc')->get();
    
            $this->calculateActualHours($swipe_records);
            return view('livewire.attendance', ['Holiday' => $this->holiday, 'Swiperecords' => $swipe_records, 'Swiperecords1' => $swipe_records1, 'data' => $data, 'CurrentDate' => $currentDate2, 'CurrentDateTwoRecord' => $this->currentDate2record, 'ChangeDate' => $this->changeDate, 'CurrentDate2recordexists' => $this->currentDate2recordexists,
                'avgLateIn' => $this->avgLateIn,'avgEarlyOut' => $this->avgEarlyOut,
                'avgSignInTime'=>$this->avgSwipeInTime,'avgSignOutTime'=>$this->avgSwipeOutTime,
                'modalTitle'=>$this->modalTitle,'totalDays'=>$this->totalDays
            ]);
        } catch (\Exception $e) {
            Log::error('Error in render method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while rendering the page. Please try again later.');
            return view('livewire.attendance'); // Return an empty view or handle it as appropriate
        }
    }
    
}
