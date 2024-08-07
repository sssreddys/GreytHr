<?php

namespace App\Livewire;

use App\Models\EmployeeDetails;
use App\Models\RegularisationDates;
use App\Models\SwipeRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class ViewRegularisationPendingNew extends Component
{
    public $regularisations;
    public $employeeId;

    public $searchQuery='';
    public $searching=0;
    public $regularised_date;
    public $user;

    public $auto_approve=false;
    public $remarks;

    public $openRejectPopupModal=false;

    public $openApprovePopupModal=false;
    public $countofregularisations;
    public function mount()
    {
        $this->employeeId = auth()->guard('emp')->user()->emp_id;
        $this->user = EmployeeDetails::where('emp_id', $this->employeeId)->first();
        $employees=EmployeeDetails::where('manager_id',$this->employeeId)->select('emp_id', 'first_name', 'last_name')->get();
        $empIds = $employees->pluck('emp_id')->toArray();
        $this->regularisations = RegularisationDates::whereIn('emp_id', $empIds)
        ->where('is_withdraw', 0) // Assuming you want records with is_withdraw set to 0
        ->where('status','pending')
        ->selectRaw('*, JSON_LENGTH(regularisation_entries) AS regularisation_entries_count')
        ->whereRaw('JSON_LENGTH(regularisation_entries) > 0')
        ->with('employee')
        ->get();
        foreach ($this->regularisations as $regularisation) {
            $this->regularised_date = Carbon::parse($regularisation->created_at)->toDateString();

            $daysDifference = Carbon::parse($this->regularised_date)->diffInDays(Carbon::now());

            if ($daysDifference >= 3) {
                $this->auto_approve=true;
                $this->approve($regularisation->id);
            }


        }
    }
    public function openRejectModal()
    {
       $this->openRejectPopupModal=true;
    }
    public function closeRejectModal()
    {
        $this->openRejectPopupModal=false;
    }
    public function openApproveModal()
    {
        $this->openApprovePopupModal=true;
    }
    public function closeApproveModal()
    {
        $this->openApprovePopupModal=false;
    }
    public function approve($id)
    {
        $currentDateTime = Carbon::now();
        $item = RegularisationDates::find($id);
       
        $employeeId=$item->emp_id;
       
        $item->status='approved';
        if(empty($this->remarks))
        {
            if($this->auto_approve==true)
            {
                $item->approver_remarks='auto_approved';
            }

        }
        else
        {
            
                $item->approver_remarks=$this->remarks;
            
            
        }
        $item->approved_date = $currentDateTime;
        if($this->auto_approve==true)
        {
            $item->approved_by='auto_approved'; 
        }
        else
        {
            $item->approved_by=$this->user->first_name . ' ' . $this->user->last_name;
        }
        
        $item->save();
        $regularisationEntries = json_decode($item['regularisation_entries'], true);
        $count_of_regularisations=count($regularisationEntries);
        
        if (!empty($regularisationEntries) && is_array($regularisationEntries)) {
            
            for($i=0;$i<$count_of_regularisations;$i++) {
               
                $swiperecord=new SwipeRecord();
                $swiperecord->emp_id=$employeeId;
                $date = $regularisationEntries[$i]['date'];
                $from=$regularisationEntries[$i]['from'];
                $to=$regularisationEntries[$i]['to'];
                $reason=$regularisationEntries[$i]['reason'];
               
               
                
                if (empty($from)) {
                    
                    
                    $swiperecord->in_or_out='IN';
                    $swiperecord->swipe_time= '10:00';
                    $swiperecord->created_at=$date;
                    $swiperecord->is_regularised=true;
                    
                } else {
                    $swiperecord->in_or_out='IN';
                    $swiperecord->swipe_time= $from;
                    $swiperecord->created_at=$date;
                    $swiperecord->is_regularised=true;
                }
                $swiperecord->save();
                $swiperecord1=new SwipeRecord();
                $swiperecord1->emp_id=$employeeId;
                
                if (empty($to) ){
                    
                    
                    $swiperecord1->in_or_out='OUT';
                    $swiperecord1->swipe_time= '19:00';
                    $swiperecord1->created_at=$date;
                    $swiperecord1->is_regularised=true;
                    
                } else {
                    $swiperecord1->in_or_out='OUT';
                    $swiperecord1->swipe_time= $to;
                    $swiperecord1->created_at=$date;
                    $swiperecord1->is_regularised=true;
                }
                $swiperecord1->save();
                // Exit the loop after the first entry since the example has one entry
               
            }
        }
        $this->countofregularisations--;
        Session::flash('success', 'Regularisation Request approved successfully');
        $this->remarks='';
        $this->closeApproveModal();
    }
    
    public function searchRegularisation()
    {
        $this->searching=1;
       
    }
    public function reject($id)
    {
        $currentDateTime = Carbon::now();
        $item = RegularisationDates::find($id);
        if(empty($this->remarks))
        {

        }
        else
        {
            $item->approver_remarks=$this->remarks;
        }
        $item->status='rejected';
        $item->rejected_date = $currentDateTime; 
        $item->rejected_by=$this->user->first_name . ' ' . $this->user->last_name;
        $item->save();

        $this->countofregularisations--;
        Session::flash('success', 'Regularisation Request rejected successfully');
        $this->remarks='';
        $this->closeRejectModal();
    }
    
 
    public function render()
    {
        $employeeId = auth()->guard('emp')->user()->emp_id;
        $employees=EmployeeDetails::where('manager_id',$employeeId)->select('emp_id', 'first_name', 'last_name')->get();
        $empIds = $employees->pluck('emp_id')->toArray();
        if($this->searching==1)
        {
// Retrieve records from AttendanceRegularisationNew for the extracted emp_ids
                $this->regularisations = RegularisationDates::join('employee_details', 'regularisation_dates.emp_id', '=', 'employee_details.emp_id')
                ->whereIn('regularisation_dates.emp_id', $empIds)
                ->where('is_withdraw', 0) // Assuming you want records with is_withdraw set to 0
                ->where('regularisation_dates.status','pending')
                ->selectRaw('*, JSON_LENGTH(regularisation_entries) AS regularisation_entries_count')
                ->whereRaw('JSON_LENGTH(regularisation_entries) > 0') 
                ->with('employee') 
                ->where(function ($query) {
                    $query->where('regularisation_dates.emp_id', 'LIKE', '%' . $this->searchQuery . '%')
                        ->orWhere('employee_details.first_name', 'LIKE', '%' . $this->searchQuery . '%')
                        ->orWhere('employee_details.last_name', 'LIKE', '%' . $this->searchQuery . '%');
                        
                })
                ->get();
        }
        else
        {
            $this->regularisations = RegularisationDates::whereIn('emp_id', $empIds)
            ->where('is_withdraw', 0) // Assuming you want records with is_withdraw set to 0
            ->where('status','pending')
            ->selectRaw('*, JSON_LENGTH(regularisation_entries) AS regularisation_entries_count')
            ->whereRaw('JSON_LENGTH(regularisation_entries) > 0') 
            ->with('employee') 
            ->get();
        }
        
              
        $this->countofregularisations=$this->regularisations->count();  
        return view('livewire.view-regularisation-pending-new');
    }
}
