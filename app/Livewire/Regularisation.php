<?php
// Created by : Pranita Priyadarshi
// About this component: It shows allowing employees to adjust or provide reasons for discrepancies in their recorded work hours
namespace App\Livewire;
use App\Models\EmployeeDetails;
use App\Models\RegularisationNew;
use App\Models\RegularisationNew1;
use App\Models\Regularisations;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Regularisation extends Component
{
    public $c=false;
    
    public $isApply=0;

    public $isPending=0;
    public $isHistory=0;

    public $date;
    
    public $callcontainer=0;
    public $data;

    public $pendingRegularisations;

    public $historyRegularisations;

    public $regularisationEntriesArray;
    public $selectdate;
    public $data1;
    public $data7;
    public $data8;
    public $manager3;
    public $selectedDates = [];
    public $employee;

    public $regularisationdescription;
    public $calendar=[];
    public $data4;
    public $from;
    public $to;
    public $reason;

    public $remarks;

    public $withdraw_session=false;
    public $isdatesApplied=false;
    public $count_for_regularisation=0;

    public $updatedregularisationEntries;
    public $regularisationEntries=[];
    public $manager1;
   
    public $storedArray;

    public $storedArray1;
    public $numberOfItems;
    public $year;
    public $month;
    public $currentMonth;

    public $isdeletedArray=0;
    public $currentYear;
    public $data10;
    public $currentDate;
    public $defaultApply=1;
    public $currentDateTime;
   
    public $count=0;
    public function mount()
    {
        try {
            $this->year = now()->year;
            $this->month = now()->month;
            $this->getDaysInMonth($this->year, $this->month);
            // $this->updateCurrentMonthYear();
            $this->currentDate = now();
            $this->generateCalendar();
        } catch (\Exception $e) {
            Log::error('Error in mount method: ' . $e->getMessage());
            // Handle the error as needed, such as displaying a message to the user
        }
    }
    //This function is used to create calendar
    public function generateCalendar()
    {
        try {
            $firstDay = Carbon::create($this->year, $this->month, 1);
            $daysInMonth = $firstDay->daysInMonth;
            $today = now();
            $calendar = [];
            $dayCount = 1;
            $firstDayOfWeek = $firstDay->dayOfWeek;
            $lastDayOfPreviousMonth = $firstDay->copy()->subDay();
    
            for ($i = 0; $i < ceil(($firstDayOfWeek + $daysInMonth) / 7); $i++) {
                $week = [];
                for ($j = 0; $j < 7; $j++) {
                    if ($i === 0 && $j < $firstDay->dayOfWeek) {
                        $previousMonthDays = $lastDayOfPreviousMonth->copy()->subDays($firstDay->dayOfWeek - $j - 1);
                        $week[] = [
                            'day' => $previousMonthDays->day,
                            'date' => $previousMonthDays->toDateString(),
                            'isToday' => false,
                            'isCurrentDate' => false,
                            'isCurrentMonth' => false,
                            'isPreviousMonth' => true,
                            'isAfterToday' => $previousMonthDays->isAfter($today),
                        ];
                    } elseif ($dayCount <= $daysInMonth) {
                        $date = Carbon::create($this->year, $this->month, $dayCount);
                        $isToday = $date->isSameDay($today);
                        $week[] = [
                            'day' => $dayCount,
                            'date' => $date->toDateString(),
                            'isToday' => $isToday,
                            'isCurrentDate' => $isToday,
                            'isCurrentMonth' => true,
                            'isPreviousMonth' => false,
                            'isAfterToday' => $date->isAfter($today),
                        ];
                        $dayCount++;
                    } else {
                        $nextMonthDays = Carbon::create($this->year, $this->month, $dayCount - $daysInMonth);
                        $week[] = [
                            'day' => $nextMonthDays->day,
                            'date' => $nextMonthDays->toDateString(),
                            'isToday' => false,
                            'isCurrentDate' => false,
                            'isCurrentMonth' => false,
                            'isNextMonth' => true,
                            'isAfterToday' => $nextMonthDays->isAfter($today),
                        ];
                        $dayCount++;
                    }
                }
                $calendar[] = $week;
            }
    
            $this->calendar = $calendar;
        } catch (\Exception $e) {
            Log::error('Error in generateCalendar method: ' . $e->getMessage());
            // Handle the error as needed, such as displaying a message to the user
        }
    }
    
    
    //This function will navigate to the previous month in the calendar
    public function previousMonth()
    {
            try {
            
                $this->date = Carbon::create($this->year, $this->month, 1)->subMonth();
            
                $this->year = $this->date->year;
                
                $this->month = $this->date->month;
                $daysInMonth1 = $this->getDaysInMonth($this->year, $this->month);
            } catch (\Exception $e) {
                Log::error('Error in previousMonth method: ' . $e->getMessage());
                // Handle the error as needed, such as displaying a message to the user
            }
    }


//This function will navigate to the next month in the calendar
public function nextMonth()
{
    try {
        
      $this->date = Carbon::create($this->year, $this->month, 1)->addMonth();
        
        $this->year = $this->date->year;
        $this->month = $this->date->month;
        $daysInMonth2 = $this->getDaysInMonth($this->year, $this->month);
    } catch (\Exception $e) {
        Log::error('Error in nextMonth method: ' . $e->getMessage());
        // Handle the error as needed, such as displaying a message to the user
    }
}
 
    //This function will calculate the no of days in a month
    public function getDaysInMonth($year, $month)
    {
        try {
            $date = Carbon::create($year, $month, 1);
            $daysInMonth = $date->daysInMonth;
    
            return collect(range(1, $daysInMonth))->map(function ($day) use ($date) {
                return [
                    'day' => $day,
                    'date' => $date->copy()->addDays($day - 1),
                    'isCurrentDate' => $day === now()->day && $date->isCurrentMonth(),
                ];
            }); 
        } catch (\Exception $e) {
            Log::error('Error in getDaysInMonth method: ' . $e->getMessage());
            // Handle the error as needed, such as returning a default value or displaying a message to the user
            return collect(); // Return an empty collection as a default value
        }
    }
    
   
    
    //This function is used to select the particular date in the calendar and these dates will be stored in the array
    public function selectDate($date)
    {
        try {
            $currentDate = date('Y-m-d');
            if (strtotime($date) < strtotime($currentDate)) {
                if (!in_array($date, $this->selectedDates)) {
                    // Add the date to the selectedDates array only if it's not already selected
                    $this->selectedDates[] = $date;
                    $this->regularisationEntries[] = [
                        'date' => $date,
                        'from' => '',
                        'to' => '',
                        'reason' => '',
                    ];
                }
            }
            $this->storedArray = array($this->selectedDates);
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            Log::error('Error in selectDate method: ' . $e->getMessage());
            // You might want to inform the user about the error or take other appropriate actions
        }
    }
    
    
    //This function will remove the date from the array if we dont want to regularise the attendance on the particular date
    public function deleteStoredArray($index)
    {
        try {
            unset($this->regularisationEntries[$index]);
            $this->isdeletedArray += 1;
            $this->updatedregularisationEntries = array_values($this->regularisationEntries);
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            Log::error('Error in deleteStoredArray method: ' . $e->getMessage());
            // You might want to inform the user about the error or take other appropriate actions
        }
    }
    //This function will store regularisation details in the database
    public function storearraydates()
{
    try {
        $this->isdatesApplied = true;
        $employeeDetails = EmployeeDetails::where('emp_id', auth()->guard('emp')->user()->emp_id)->first();
        $emp_id = $employeeDetails->emp_id;
        $regularisationEntriesJson = json_encode($this->regularisationEntries);
        if ($this->isdeletedArray > 0) {
            $regularisationEntriesArray = $this->updatedregularisationEntries;
        } else {
            $regularisationEntriesArray = json_decode($regularisationEntriesJson, true);
        }

        // Count the number of items
        $this->numberOfItems = count($regularisationEntriesArray);

        RegularisationNew1::create([
            'emp_id' => $emp_id,
            'employee_remarks' => $this->remarks,
            'regularisation_entries' => $regularisationEntriesJson,
            'is_withdraw' => 0,
            'regularisation_date' => '2024-03-26',
        ]);
        session()->flash('message', 'CV created successfully.');
        $regularisationEntriesJson = [];
        $this->regularisationEntries = [];
    } catch (\Exception $e) {
        // Log the error or handle it as needed
        Log::error('Error in storearraydates method: ' . $e->getMessage());
        // You might want to inform the user about the error or take other appropriate actions
        session()->flash('error', 'An error occurred while creating CV.');
    }
}

//This function will show the page where we can apply for regularisation    
public function applyButton()
{
    try {
        $this->isApply = 1;
        $this->isPending = 0;
        $this->isHistory = 0;
        $this->defaultApply = 1;
    } catch (\Exception $e) {
        // Handle any exceptions that might occur
        // For example, log the error or show a message to the user
        Log::error('Error occurred while applying: ' . $e->getMessage());
        session()->flash('error', 'An error occurred while applying.');
    }
}
//This function will show the page where we can see the pending regularisation details
public function pendingButton()
{
    try {
        $this->isApply = 0;
        $this->isPending = 1;
        $this->isHistory = 0;
        $this->defaultApply = 0;
    } catch (\Exception $e) {
        // Handle any exceptions that might occur
        Log::error('Error occurred while changing to pending state: ' . $e->getMessage());
        session()->flash('error', 'An error occurred while changing to pending state.');
    }
}
//This function will show the page where we can see the approved,rejected and withdrawn regularisation details
public function historyButton()
{
    try {
        $this->isApply = 0;
        $this->isPending = 0;
        $this->isHistory = 1;
        $this->defaultApply = 0;
    } catch (\Exception $e) {
        // Handle any exceptions that might occur
        Log::error('Error occurred while changing to history state: ' . $e->getMessage());
        session()->flash('error', 'An error occurred while changing to history state.');
    }
}


    public function storePost()
    {
        $employeeDetails = EmployeeDetails::where('emp_id', auth()->guard('emp')->user()->emp_id)->first();
        $emp_id = $employeeDetails->emp_id;
        
        try {
            Regularisations::create([
                'emp_id' => $emp_id,
                'from' => $this->from,
                'to' => $this->to,
                'reason' => $this->reason,
                'is_withdraw' => 0,
                'regularisation_date' => $this->selectedDate,
            ]);
            session()->flash('success', 'Hurry Up! Action completed successfully');
        } catch (\Exception $ex) {
            session()->flash('error', 'Something goes wrong!!');
        }
    }
 //This function will withdraw the regularisation page 
    public function withdraw($id)
    {
        try {
            $currentDateTime = Carbon::now();
            $this->data = RegularisationNew1::where('id', $id)->update([
                'is_withdraw' => 1,
                'withdraw_date' => $currentDateTime,
            ]);
            $this->withdraw_session = true;
            session()->flash('success', 'Hurry Up! Regularisation withdrawn  successfully');
        } catch (\Exception $ex) {
            session()->flash('error', 'Something went wrong while withdrawing regularisation.');
        }
    }
    //This function will update the count of regularisation
    public function updateCount()
    {
        try {
            $this->c = true;
        } catch (\Exception $e) {
            Log::error('Error occurred while updating count: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while updating count.');
        }
    }
    
       
    public function render()
    {
        $loggedInEmpId = Auth::guard('emp')->user()->emp_id;
        $s4 = EmployeeDetails::where('emp_id', auth()->guard('emp')->user()->emp_id)->pluck('report_to')->first();
        $employeeDetails = EmployeeDetails::select('manager_id')
        ->where('emp_id', $loggedInEmpId)
        ->first();  
        
        $empid=$employeeDetails->manager_id;
       
        $employeeDetails1 = EmployeeDetails::
         where('emp_id', $empid)
        ->first();
       
        $isManager = EmployeeDetails::where('manager_id', $loggedInEmpId)->exists();
        $subordinateEmployeeIds = EmployeeDetails::where('manager_id',auth()->guard('emp')->user()->emp_id)
       ->pluck('first_name','last_name')
       ->toArray();
        $pendingRegularisations = RegularisationNew1::where('emp_id', $loggedInEmpId)
        ->where('status', 'pending')
        ->where('is_withdraw', 0)
        ->orderByDesc('id')
        ->get();
       $this->pendingRegularisations = $pendingRegularisations->filter(function ($regularisation) {
          return $regularisation->regularisation_entries !== "[]";
        });

      $historyRegularisations = RegularisationNew1::where('emp_id', $loggedInEmpId)
                    ->whereIn('status', ['pending', 'approved', 'rejected'])
                    ->orderByDesc('id')
                    ->get();

      $this->historyRegularisations = $historyRegularisations->filter(function ($regularisation) {
        return $regularisation->regularisation_entries !== "[]";
       });
      $manager = EmployeeDetails::select('manager_id', 'report_to')->distinct()->get();   
      $this->data10= Regularisations::where('status', 'pending')->get();
      $this->manager1 = EmployeeDetails::where('emp_id', auth()->guard('emp')->user()->emp_id)->first();
      $this->data = Regularisations::where('is_withdraw', '0')->count();
      $this->data8 = Regularisations::where('is_withdraw', '0')->get();
      $this->data1 = Regularisations::where('status', 'pending')->first();
      $this->data4 = Regularisations::where('is_withdraw', '1')->count();
      $this->data7= Regularisations::all();
      $employee = EmployeeDetails::where('emp_id',auth()->guard('emp')->user()->emp_id)->first();
      if ($employee) {
            $this->manager3 = EmployeeDetails::find($employee->manager_id);
            
      }
      return view('livewire.regularisation',['CallContainer'=>$this->callcontainer,'manager_report'=>$s4,'isManager1'=>$isManager,'daysInMonth' => $this->getDaysInMonth($this->year,$this->month),'subordinate'=>$subordinateEmployeeIds,'show'=>$this->c,'manager11'=>$manager,'count'=>$this->c,'count1'=> $this->data,'manager2'=>$this->manager3,'data2'=>$this->data1 ,'data5'=>$this->data4,'data81'=>$this->data7,'withdraw'=>$this->data8,'data11'=>$this->data10,'manager2'=>$this->manager1,'EmployeeDetails'=>$employeeDetails1]);
    }
}
