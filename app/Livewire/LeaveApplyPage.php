<?php

namespace App\Livewire;

use App\Models\EmployeeDetails;
use App\Models\HolidayCalendar;
use App\Models\LeaveRequest;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class LeaveApplyPage extends Component
{
    public $leave_type;
    public $emp_id;
    public $from_date;
    public $from_session = 'Session 1';
    public $to_session = 'Session 2';
    public $to_date;
    public $applying_to;
    public $contact_details;
    public $reason;
    public $selectedPeople = [];
    public  $showinfoMessage = true;
    public  $showinfoButton = false;
    public $errorMessage = '';
    public $showNumberOfDays = false;
    public $differenceInMonths;
    public $show_reporting = false;
    public $showApplyingTo = true;
    public $leaveBalances = [];
    public $selectedYear;
    public $createdLeaveRequest;
    public $calculatedNumberOfDays = 0;
    public $employeeDetails = [];
    public $showPopupMessage = false;
    public $numberOfDays;
    public $showApplyingToContainer = false;
    public $loginEmpManagerProfile;
    public $loginEmpManager;
    public $cc_to;
    public $showCcRecipents = false;
    public $loginEmpManagerId;
    public $employee;
    public $managerFullName = [];
    public $ccRecipients = [];
    public $selectedEmployee = [];
    public $searchTerm = '';
    public $filter = '';
    public $fromDate;
    public $fromSession;
    public $toSession;
    public $toDate;
    public $selectedCcTo = [];
    public $selectedCCEmployees = [];
    public $showCasualLeaveProbation;
    protected $rules = [
        'leave_type' => 'required',
        'from_date' => 'required|date',
        'from_session' => 'required',
        'to_date' => 'required|date',
        'to_session' => 'required',
        'contact_details' => 'required',
        'reason' => 'required',
    ];

    protected $messages = [
        'leave_type.required' => 'Leave type is required',
        'from_date.required' => 'From date is required',
        'from_session.required' => 'Session is required',
        'to_date.required' => 'To date is required',
        'to_session.required' => 'Session is required',
        'contact_details.required' => 'Contact details are required',
        'reason.required' => 'Reason is required',
    ];

    public function mount()
    {
        $this->searchTerm = '';
        $this->filter = '';
        $this->selectedYear = Carbon::now()->format('Y');
        $employeeId = auth()->guard('emp')->user()->emp_id;
        $this->employee = EmployeeDetails::where('emp_id', $employeeId)->first();
        // Determine if the dropdown option should be displayed
        $this->showCasualLeaveProbation = $this->employee && !$this->employee->probation_period && !$this->employee->confirmation_date;
        $this->searchManager();
    }

    public function validateField($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function toggleInfo()
    {
        $this->showinfoMessage = !$this->showinfoMessage;
        $this->showinfoButton = !$this->showinfoButton;
    }


    //this method used to filter cc recipients from employee details
    public function searchCCRecipients()
    {
        try {
            // Fetch employees based on the search term for CC To
            $employeeId = auth()->guard('emp')->user()->emp_id;
            $applying_to = EmployeeDetails::where('emp_id', $employeeId)->first();
            $this->ccRecipients = EmployeeDetails::where('company_id', $applying_to->company_id)
                ->where('emp_id', '!=', $employeeId) // Exclude the current user
                ->where(function ($query) {
                    $query
                        ->orWhere('first_name', 'like', '%' . $this->searchTerm . '%')
                        ->orWhere('last_name', 'like', '%' . $this->searchTerm . '%');
                })
                ->groupBy('emp_id', 'image', 'gender')
                ->select(
                    'emp_id',
                    'gender',
                    'image',
                    DB::raw('MIN(CONCAT(first_name, " ", last_name)) as full_name')
                )
                ->orderBy('full_name')
                ->get();
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error in searchCCRecipients method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while searching for CC recipients. Please try again later.');
        }
    }

    public function openCcRecipientsContainer()
    {
        try {
            $this->showCcRecipents = true;
            $this->searchCCRecipients();
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error in openCcRecipientsContainer method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while opening CC recipients container. Please try again later.');
        }
    }


    //this method will help to close the cc to container
    public function closeCcRecipientsContainer()
    {
        try {
            $this->showCcRecipents = !$this->showCcRecipents;
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error in closeCcRecipientsContainer method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while closing CC recipients container. Please try again later.');
        }
    }

    public $selectedEmployeeId;
    //selected applying to manager details
    public $empManagerDetails;
    public $applyingToDetails = [];
    public function selectEmployee($employeeId) {}



    private function isWeekend($date)
    {
        // Convert date string to a Carbon instance
        $carbonDate = Carbon::parse($date);
        // Check if the day of the week is Saturday or Sunday
        return $carbonDate->isWeekend();
    }
    public function toggleSelection($empId)
    {
        if (isset($this->selectedPeople[$empId])) {
            unset($this->selectedPeople[$empId]);
        } else {
            $this->selectedPeople[$empId] = true;
        }
        $this->searchCCRecipients();
        $this->fetchEmployeeDetails();
    }
    public function fetchEmployeeDetails()
    {
        // Reset the list of selected employees
        $this->selectedCCEmployees = [];

        // Fetch details for selected employees
        foreach ($this->selectedPeople as $empId => $selected) {
            $employee = EmployeeDetails::where('emp_id', $empId)->first();

            if ($employee) {
                // Calculate initials
                $firstNameInitial = strtoupper(substr($employee->first_name, 0, 1));
                $lastNameInitial = strtoupper(substr($employee->last_name, 0, 1));
                $initials = $firstNameInitial . $lastNameInitial;

                // Add to selectedEmployees array
                $this->selectedCCEmployees[] = [
                    'emp_id' => $empId,
                    'first_name' => $employee->first_name,
                    'last_name' => $employee->last_name,
                    'initials' => $initials,
                ];
            }
        }
    }
    public function handleCheckboxChange($empId)
    {
        if (isset($this->selectedPeople[$empId])) {
            // If the checkbox is unchecked, remove from CC
            $this->removeFromCcTo($empId);
        } else {
            // If the checkbox is checked, add to CC
            $this->selectedPeople[$empId] = true;
        }
    }
    public function removeFromCcTo($empId)
    {
        // Remove the employee from selectedCcTo array
        $this->selectedCcTo = array_values(array_filter($this->selectedCcTo, function ($recipient) use ($empId) {
            return $recipient['emp_id'] != $empId;
        }));

        // Update cc_to field with selectedCcTo (comma-separated string of emp_ids)
        $this->cc_to = implode(',', array_column($this->selectedCcTo, 'emp_id'));

        // Toggle selection state in selectedPeople
        unset($this->selectedPeople[$empId]);
        $this->showCcRecipents = true;
        // Fetch updated employee details
        $this->fetchEmployeeDetails();
        $this->searchCCRecipients();
    }


    //this method will handle the search functionality
    public function handleSearch($type)
    {
        try {
            if ($type === 'employees') {
                $this->searchEmployees();
            } elseif ($type === 'ccRecipients') {
                $this->searchCCRecipients();
            }
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error in handleSearch method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while handling the search. Please try again later.');
        }
    }

    public function leaveApply()
    {
        $this->validate();

        try {
            $this->selectLeave();

            // Check for weekend
            if ($this->isWeekend($this->from_date) || $this->isWeekend($this->to_date)) {
                $this->errorMessage = 'Looks like it\'s already your non-working day. Please pick different date(s) to apply.';
                return redirect()->back()->withInput();
            }

            // Check for overlapping leave
            $overlappingLeave = LeaveRequest::where('emp_id', auth()->guard('emp')->user()->emp_id)
                ->where(function ($query) {
                    $query->where(function ($q) {
                        $q->where('from_date', '<=', $this->from_date)
                            ->where('to_date', '>=', $this->from_date);
                    })->orWhere(function ($q) {
                        $q->where('from_date', '<=', $this->to_date)
                            ->where('to_date', '>=', $this->to_date);
                    })->orWhere(function ($q) {
                        $q->where('from_date', '>=', $this->from_date)
                            ->where('to_date', '<=', $this->to_date);
                    });
                })
                ->whereIn('status', ['approved', 'Pending'])
                ->exists();

            if ($overlappingLeave) {
                $this->errorMessage = 'The selected leave dates overlap with an existing leave application.';
                return;
            }

            // Validate from_date to to_date
            if ($this->to_date < $this->from_date) {
                $this->errorMessage = 'To date must be greater than or equal to from date.';
                return redirect()->back()->withInput();
            }

            // Check for holidays in the selected date range
            $holidays = HolidayCalendar::where(function ($query) {
                $query->whereBetween('date', [$this->from_date, $this->to_date])
                    ->orWhere(function ($q) {
                        $q->where('date', '>=', $this->from_date)
                            ->where('date', '<=', $this->to_date);
                    });
            })->get();

            if ($holidays->isNotEmpty()) {
                $this->errorMessage = 'The selected leave dates overlap with existing holidays. Please pick different dates.';
                return redirect()->back()->withInput();
            }

            $employeeId = auth()->guard('emp')->user()->emp_id;
            $this->employeeDetails = EmployeeDetails::where('emp_id', $employeeId)->first();
            $ccToDetails = [];

            foreach ($this->selectedCCEmployees as $selectedEmployeeId) {
                // Check if the employee ID already exists in ccToDetails
                $existingIds = array_column($ccToDetails, 'emp_id');

                if (!in_array($selectedEmployeeId, $existingIds)) {
                    // Fetch additional details from EmployeeDetails table
                    $employeeDetails = EmployeeDetails::where('emp_id', $selectedEmployeeId)->first();

                    // Concatenate first_name and last_name to get the full name
                    $fullName = $employeeDetails->first_name . ' ' . $employeeDetails->last_name;

                    $ccToDetails[] = [
                        'emp_id' => $selectedEmployeeId,
                        'full_name' => $fullName,
                    ];
                }
            }

            $applyingToDetails = [];
            if ($this->selectedEmployeeId) {
                $employeeDetails = EmployeeDetails::where('emp_id', $this->selectedEmployeeId)->first();
                if ($employeeDetails) {
                    $applyingToDetails[] = [
                        'manager_id' => $this->selectedEmployeeId,
                        'report_to' => $employeeDetails->first_name . ' ' . $employeeDetails->last_name,
                        'image' => $employeeDetails->image,
                    ];
                }
            } else {
                $employeeDetails = EmployeeDetails::where('emp_id', $employeeId)->first();
                $defualtManager = $employeeDetails->manager_id;
                // Handle default values if no employee is selected
                $applyingToDetails[] = [
                    'manager_id' => $defualtManager,
                    'report_to' => $this->loginEmpManager,
                    'image' => $this->loginEmpManagerProfile,
                ];
            }


            // Create the leave request
            $this->createdLeaveRequest = LeaveRequest::create([
                'emp_id' => $employeeId,
                'leave_type' => $this->leave_type,
                'category_type' => 'Leave',
                'from_date' => $this->from_date,
                'from_session' => $this->from_session,
                'to_session' => $this->to_session,
                'to_date' => $this->to_date,
                'applying_to' => json_encode($applyingToDetails),
                'cc_to' => json_encode($ccToDetails),
                'contact_details' => $this->contact_details,
                'reason' => $this->reason,
            ]);

            Notification::create([
                'emp_id' => $employeeId,
                'notification_type' => 'leave',
                'leave_type' => $this->leave_type,
                'leave_reason' => $this->reason,
                'applying_to' => json_encode($applyingToDetails),
                'cc_to' => json_encode($ccToDetails),
            ]);

            logger('LeaveRequest created successfully', ['leave_request' => $this->createdLeaveRequest]);

            if ($this->createdLeaveRequest && $this->createdLeaveRequest->emp_id) {
                session()->flash('message', 'Leave application submitted successfully!');
                return redirect()->to('/leave-form-page');
            } else {
                logger('Error creating LeaveRequest', ['emp_id' => $employeeId]);
                session()->flash('error', 'Error submitting leave application. Please try again.');
                return redirect()->to('/leave-form-page');
            }
        } catch (\Exception $e) {
            if ($e instanceof \Illuminate\Database\QueryException) {
                Log::error("Database error: " . $e->getMessage());
                session()->flash('error', 'Database error occurred. Please try again later.');
            } elseif (strpos($e->getMessage(), 'Call to a member function store() on null') !== false) {
                session()->flash('error', 'Please upload an image.');
            } elseif ($e instanceof \Illuminate\Http\Client\RequestException) {
                Log::error("Network error: " . $e->getMessage());
                session()->flash('error', 'Network error occurred. Please try again later.');
            } elseif ($e instanceof \PDOException) {
                Log::error("Database connection error: " . $e->getMessage());
                session()->flash('error', 'Database connection error. Please try again later.');
            } else {
                Log::error("General error: " . $e->getMessage());
                session()->flash('error', 'Failed to submit leave application. Please try again later.');
            }
            return redirect()->to('/leave-form-page');
        }
    }
    public function handleFieldUpdate($field)
    {
        try {
            $this->showNumberOfDays = true;
            $this->showPopupMessage = false; // Ensure popup is not shown by default

            if ($field == 'from_date' || $field == 'to_date' || $field == 'from_session' || $field == 'to_session') {
                list($result, $errorMessage) = $this->calculateNumberOfDays($this->fromDate, $this->fromSession, $this->toDate, $this->toSession);

                if ($errorMessage) {
                    // If there's an error, set the popup message
                    session()->flash('popupMessage', $errorMessage);
                    $this->showPopupMessage = true;
                } else {
                    $this->numberOfDays = $result;

                    // Check if number of days is 0 and set the popup flag
                    if ($this->numberOfDays === 0) {
                        session()->flash('popupMessage', 'Selected dates are valid, but no working days are calculated.');
                        $this->showPopupMessage = true;
                    }
                }
            }
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error in handleFieldUpdate method: ' . $e->getMessage());
            session()->flash('popupMessage', 'An error occurred while handling field update. Please try again later.');
            $this->showPopupMessage = true;
        }
    }
    public function applyingTo()
    {
        try {
            $this->showApplyingToContainer = !$this->showApplyingToContainer;
            $this->show_reporting = true;
            $this->showApplyingTo = false;
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error in openCcRecipientsContainer method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while opening CC recipients container. Please try again later.');
        }
    }

    public function selectLeave()
    {
        try {
            $this->show_reporting = $this->leave_type !== 'default';
            $this->showApplyingTo = false;
            $this->selectedYear = Carbon::now()->format('Y');
            $employeeId = auth()->guard('emp')->user()->emp_id;
            // Retrieve all leave balances
            $allLeaveBalances = LeaveBalances::getLeaveBalances($employeeId, $this->selectedYear);
            // Filter leave balances based on the selected leave type
            switch ($this->leave_type) {
                case 'Casual Leave Probation':
                    $this->leaveBalances = [
                        'casualProbationLeaveBalance' => $allLeaveBalances['casualProbationLeaveBalance']
                    ];
                    break;
                case 'Casual Leave':
                    $this->leaveBalances = [
                        'casualLeaveBalance' => $allLeaveBalances['casualLeaveBalance']
                    ];
                    break;
                case 'Loss of Pay':
                    $this->leaveBalances = [
                        'lossOfPayBalance' => $allLeaveBalances['lossOfPayBalance']
                    ];

                    break;
                case 'Sick Leave':
                    $this->leaveBalances = [
                        'sickLeaveBalance' => $allLeaveBalances['sickLeaveBalance']
                    ];
                    break;
                case 'Maternity Leave':
                    $this->leaveBalances = [
                        'maternityLeaveBalance' => $allLeaveBalances['maternityLeaveBalance']
                    ];
                    break;
                case 'Paternity Leave':
                    $this->leaveBalances = [
                        'paternityLeaveBalance' => $allLeaveBalances['paternityLeaveBalance']
                    ];
                    break;
                case 'Marriage Leave':
                    $this->leaveBalances = [
                        'marriageLeaveBalance' => $allLeaveBalances['marriageLeaveBalance']
                    ];
                    break;
                default:
                    $this->leaveBalances = [];
                    break;
            }
        } catch (\Exception $e) {
            // Log the error
            Log::error("Error selecting leave: " . $e->getMessage());
            // Flash an error message to the user
            session()->flash('error', 'An error occurred while selecting leave and leave balance. Please try again later.');
            // Redirect the user back
            return redirect()->back();
        }
    }
    //it will calculate number of days for leave application
    public function calculateNumberOfDays($fromDate, $fromSession, $toDate, $toSession)
    {
        try {
            $startDate = Carbon::parse($fromDate);
            $endDate = Carbon::parse($toDate);

            // Check if the start or end date is a weekend
            if ($startDate->isWeekend() || $endDate->isWeekend()) {
                return 'Error: Selected dates fall on a weekend. Please choose weekdays.';
            }

            // Check if the start and end sessions are different on the same day
            if (
                $startDate->isSameDay($endDate) &&
                $this->getSessionNumber($fromSession) === $this->getSessionNumber($toSession)
            ) {
                // Inner condition to check if both start and end dates are weekdays
                if (!$startDate->isWeekend() && !$endDate->isWeekend()) {
                    return 0.5;
                } else {
                    // If either start or end date is a weekend, return 0
                    return 0;
                }
            }

            if (
                $startDate->isSameDay($endDate) &&
                $this->getSessionNumber($fromSession) !== $this->getSessionNumber($toSession)
            ) {
                // Inner condition to check if both start and end dates are weekdays
                if (!$startDate->isWeekend() && !$endDate->isWeekend()) {
                    return 1;
                } else {
                    // If either start or end date is a weekend, return 0
                    return 0;
                }
            }

            $totalDays = 0;

            while ($startDate->lte($endDate)) {
                // Check if it's a weekday (Monday to Friday)
                if ($startDate->isWeekday()) {
                    $totalDays += 1;
                }
                // Move to the next day
                $startDate->addDay();
            }

            // Deduct weekends based on the session numbers
            if ($this->getSessionNumber($fromSession) > 1) {
                $totalDays -= $this->getSessionNumber($fromSession) - 1; // Deduct days for the starting session
            }
            if ($this->getSessionNumber($toSession) < 2) {
                $totalDays -= 2 - $this->getSessionNumber($toSession); // Deduct days for the ending session
            }
            // Adjust for half days
            if ($this->getSessionNumber($fromSession) === $this->getSessionNumber($toSession)) {
                // If start and end sessions are the same, check if the session is not 1
                if ($this->getSessionNumber($fromSession) !== 1) {
                    $totalDays += 0.5; // Add half a day
                } else {
                    $totalDays += 0.5;
                }
            } elseif ($this->getSessionNumber($fromSession) !== $this->getSessionNumber($toSession)) {
                if ($this->getSessionNumber($fromSession) !== 1) {
                    $totalDays += 1; // Add half a day
                }
            } else {
                $totalDays += ($this->getSessionNumber($toSession) - $this->getSessionNumber($fromSession) + 1) * 0.5;
            }

            return $totalDays;
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    private function getSessionNumber($session)
    {
        return (int) str_replace('Session ', '', $session);
    }
    public function searchManager()
    {
        $employeeId = auth()->guard('emp')->user()->emp_id;
        $managerId = null;
        $managers = collect();

        try {
            // Fetch details for the current employee
            $applying_to = EmployeeDetails::where('emp_id', $employeeId)->first();
            if ($applying_to) {
                $managerId = $applying_to->manager_id;

                // Fetch the logged-in employee's manager details
                $managerDetails = EmployeeDetails::where('emp_id', $managerId)->first();
                if ($managerDetails) {
                    $this->empManagerDetails = $managerDetails;
                    $fullName = $this->empManagerDetails->first_name . ' ' . $this->empManagerDetails->last_name;
                    // Add the logged-in manager to the collection
                    $managers->push([
                        'full_name' => $fullName,
                        'emp_id' => $managerDetails->emp_id,
                        'gender' => $managerDetails->gender,
                        'image' => $managerDetails->image,
                    ]);
                }
            }



            // Fetch employees with job roles CTO and Chairman
            $jobRoles = ['CTO', 'Chairman'];
            $filteredManagers = EmployeeDetails::whereIn('job_role', $jobRoles)
                ->get(['first_name', 'last_name', 'emp_id', 'gender', 'image']);

            // Add the filtered managers to the collection
            $this->managers = $managers->merge(
                $filteredManagers->map(function ($manager) {
                    $fullName = ucfirst(strtolower($manager->first_name)) . ' ' . ucfirst(strtolower($manager->last_name));
                    return [
                        'full_name' => $fullName,
                        'emp_id' => $manager->emp_id,
                        'gender' => $manager->gender,
                        'image' => $manager->image,
                    ];
                })
            );

        } catch (\Exception $e) {
            // Log the error and handle the exception
            Log::error('Error fetching employee or manager details: ' . $e->getMessage());
        }
    }

    public $managerDetails, $fullName;
    public $managers=[];

    public function render()
    {
        $this->searchManager();
        $employeeGender = null;
        $employeeId = auth()->guard('emp')->user()->emp_id;
        $this->selectedYear = Carbon::now()->format('Y');
        // Fetch the gender of the logged-in employee
        $employeeGender = EmployeeDetails::where('emp_id', $employeeId)->select('gender')->first();

        return view('livewire.leave-apply-page', [
            'employeeGender' => $employeeGender,
            'calculatedNumberOfDays' => $this->calculatedNumberOfDays,
            'empManagerDetails' => $this->empManagerDetails,
            'loginEmpManager' => $this->loginEmpManager,
            'managers' => $this->managers,
            'ccRecipients' => $this->ccRecipients,
            'showCasualLeaveProbation' => $this->showCasualLeaveProbation
        ]);
    }
}