<?php

namespace App\Livewire;

use App\Models\EmployeeDetails;
use App\Models\HolidayCalendar;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Spatie\SimpleExcel\SimpleExcelWriter;

class ShiftRoaster extends Component
{
    public $searching=1;
    public $search;

    public $holiday;
    public $notFound;

    public $selectedMonth='June';
    public $attendanceMonth;

    public function updateselectedMonth()
    {
        $this->attendanceMonth = $this->selectedMonth; 
    }
    public function searchfilter()
    {
        $searching = 1;
        $currentDate = now()->toDateString();
        $loggedInEmpId = Auth::guard('emp')->user()->emp_id;
        $employees = EmployeeDetails::where('manager_id', $loggedInEmpId)->get();
        $nameFilter = $this->search;
        $filteredEmployees = $employees->filter(function ($employee) use ($nameFilter) {
            return stripos($employee->first_name, $nameFilter) !== false ||
                stripos($employee->last_name, $nameFilter) !== false ||
                stripos($employee->emp_id, $nameFilter) !== false ||
                stripos($employee->job_title, $nameFilter) !== false ||
                stripos($employee->city, $nameFilter) !== false ||
                stripos($employee->state, $nameFilter) !== false;
        });

        if ($filteredEmployees->isEmpty()) {
            $this->notFound = true; 
        } else {
            $this->notFound = false;
        } 
        
    }
    public function downloadExcel()
    {
        $loggedInEmpId = Auth::guard('emp')->user()->emp_id;
        $employees = EmployeeDetails::where('manager_id', $loggedInEmpId)->select('emp_id', 'first_name', 'last_name')->get();
        $currentMonth = $this->selectedMonth;
        $currentMonth1 = DateTime::createFromFormat('F', $currentMonth)->format('n');
        $currentYear = date('Y');
        $holiday = HolidayCalendar::where('month', $currentMonth)
                ->where('year', $currentYear)
                ->pluck('date')
                ->toArray();
        if(empty($holiday))
        {
           $count_of_holiday=0;  
        }
        else
        {
            $count_of_holiday=count($holiday);
            
        }
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth1, $currentYear);
        $data = [['List of Employees for ' . $currentMonth. ' ' . $currentYear],
                  ['Employee ID', 'Name', 'No. of Regular Days'],
                ];
       for ($i = 1; $i <= $daysInMonth; $i++) {
                $date = new DateTime("$currentYear-$currentMonth1-$i");
                // Get the day name
                $dayName = $date->format('D');
                $data[1][] = $i . $dayName; 

       }
       foreach ($employees as $employee) {
        $rowData = [$employee['emp_id'], $employee['first_name'] . ' ' . $employee['last_name']];
        
        
        $dateCount=0;
        for ($i = 1; $i <= $daysInMonth; $i++) 
        {
            $currentDate = $currentYear . '-' . str_pad($currentMonth1, 2, '0', STR_PAD_LEFT) . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
            if (date('N', strtotime($currentDate)) == 6 || date('N', strtotime($currentDate)) == 7) {
                $dateCount+=1; 
            }
           
        }
        $rowData[]=$daysInMonth-$dateCount-$count_of_holiday;
        
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $currentDate = $currentYear . '-' . str_pad($currentMonth1, 2, '0', STR_PAD_LEFT) . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
            if (date('N', strtotime($currentDate)) == 6 || date('N', strtotime($currentDate)) == 7) {
                $rowData[] = 'O'; 
            }
            elseif(in_array($currentDate, $holiday)) {
                $rowData[] = 'H'; 
            }
            else{
                $rowData[] = 'GS'; 
            } 
           
        }
        $data[] = $rowData;
    } 
      
       $filePath = storage_path('app/ShiftRoasterData.xlsx');
       SimpleExcelWriter::create($filePath)->addRows($data);
       return response()->download($filePath, 'Shift_Roaster_Data.xlsx');
    }
    public function render()
    {
        $loggedInEmpId = Auth::guard('emp')->user()->emp_id;
        $currentMonth=$this->selectedMonth;
        $currentYear = date('Y');  
        $year = $currentYear;
        $employees=EmployeeDetails::where('manager_id',$loggedInEmpId)->select('emp_id', 'first_name', 'last_name','job_title','city','state')->get();
        $this->holiday = HolidayCalendar::where('month',$currentMonth)
        ->where('year', $year)
        ->get('date');
        $count_of_holiday=count($this->holiday);
        if($this->searching==1)
        {
                $nameFilter = $this->search; // Assuming $this->search contains the name filter
                $filteredEmployees = $employees->filter(function ($employee) use ($nameFilter) {
                    return stripos($employee->first_name, $nameFilter) !== false ||
                        stripos($employee->last_name, $nameFilter) !== false ||
                        stripos($employee->emp_id, $nameFilter) !== false||
                        stripos($employee->job_title, $nameFilter) !== false||
                        stripos($employee->city, $nameFilter) !== false||
                        stripos($employee->state, $nameFilter) !== false;
                });

                if ($filteredEmployees->isEmpty()) {
                    $this->notFound = true; // Set a flag indicating that the name was not found
                } else {
                    $this->notFound = false;
                }
        }
        else
        {
            $filteredEmployees=$employees;
        }
        return view('livewire.shift-roaster',['Employees'=>$filteredEmployees,'Holiday'=> $this->holiday,'CountOfHoliday'=>$count_of_holiday,'Month'=>$currentMonth]);
    }
}
