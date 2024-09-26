<?php

namespace App\Livewire;

use App\Models\EmployeeDetails;
use App\Models\Hr;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use App\Models\Notification;

class LeaveCancelPage extends Component
{
    public $cancelLeaveRequests;
    public $selectedLeaveRequestId;
    public  $showinfoMessage = true;
    public  $showinfoButton = false;
    public $showCcRecipents = false;
    public $showApplyingTo = true;
    public $ccRecipients;
    public $selectedCCEmployees = [];
    public $searchTerm = '';
    public $filter = '';
    public $applying_to;
    public $selectedCcTo = [];
    public $cc_to, $reason;
    public $selectedYear;
    public $loginEmpManagerId;
    public $loginEmpManager;
    public $loginEmpManagerProfile;
    public $leave_cancel_reason;
    public $managerFullName;
    public $employeeDetails = [];
    public $selectedPeople = [];
    public $leaveRequestDetails;
    public $applyingToDetails = [];
    public $managerDetails;
    public $selectedManager = [];
    public $employee;
    public $showApplyingToContainer = false;
    public $show_reporting = false;
    public  $fullName;
    public $searchQuery = '';
    public $showCasualLeaveProbation;
    public $empManagerDetails, $selectedManagerDetails;
    public $showAlert = false;
    public $selectedLeaveType = null;
    protected $rules = [
        'leave_cancel_reason' => 'required',
    ];

    protected $messages = [
        'leave_cancel_reason.required' => 'Reason is required',
    ];


    public function mount()
    {
        try {
            $this->searchTerm = '';
            $this->selectedYear = Carbon::now()->format('Y');
            $employeeId = auth()->guard('emp')->user()->emp_id;
            $this->employee = EmployeeDetails::where('emp_id', $employeeId)->first();
            if ($this->employee) {
                $managerId = $this->employee->manager_id;
                // Fetch the logged-in employee's manager details
                $managerDetails = EmployeeDetails::where('emp_id', $managerId)->first();
                if ($managerDetails) {
                    $fullName = ucfirst(strtolower($managerDetails->first_name)) . ' ' . ucfirst(strtolower($managerDetails->last_name));
                    $this->loginEmpManager = $fullName;
                    $this->selectedManagerDetails = $managerDetails;
                } else {
                    // If no manager is found, check if the managerId is null
                    if (is_null($managerId)) {
                        // Get the company_id from the logged-in employee's details
                        $companyId = $this->employee->company_id;
                        // Fetch emp_ids from the HR table
                        $hrEmpIds = Hr::pluck('emp_id');
                        // Now, fetch employee details for these HR emp_ids
                        $hrManagers = EmployeeDetails::whereIn('emp_id', $hrEmpIds)
                            ->whereJsonContains('company_id', $companyId) // Ensure company_id matches
                            ->get();

                        if ($hrManagers->isNotEmpty()) {
                            // Assuming you want the first manager or you can apply your own logic to select a specific manager
                            $firstManager = $hrManagers->first();
                            $fullName = ucfirst(strtolower($firstManager->first_name)) . ' ' . ucfirst(strtolower($firstManager->last_name));
                            $this->loginEmpManager = $fullName;
                            $this->selectedManagerDetails = $firstManager;
                        } else {
                            // Handle case where no HR managers are found
                            $this->loginEmpManager = 'No manager found';
                            $this->selectedManagerDetails = null;
                        }
                    }
                }
                // Determine if the dropdown option should be displayed
                $this->showCasualLeaveProbation = $this->employee && !$this->employee->probation_period && !$this->employee->confirmation_date;
            }
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error in mount method: ' . $e->getMessage());
            // Display a friendly error message to the user
            session()->flash('error', 'An error occurred while loading leave apply page. Please try again later.');
            // Redirect the user to a safe location
            return redirect()->back();
        }
    }
    public function validateField($propertyName)
    {
        $this->validateOnly($propertyName);
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
    public function searchCCRecipients()
    {
        try {
            // Fetch employees based on the search term for CC To
            $employeeId = auth()->guard('emp')->user()->emp_id;

            // Fetch the company_ids for the logged-in employee
            $companyIds = EmployeeDetails::where('emp_id', $employeeId)->value('company_id');

            // Check if companyIds is an array; decode if it's a JSON string
            $companyIdsArray = is_array($companyIds) ? $companyIds : json_decode($companyIds, true);

            // Initialize an empty collection for recipients
            $this->ccRecipients = collect(); // Ensure it's initialized as a collection

            // Loop through each company ID and find employees
            foreach ($companyIdsArray as $companyId) {
                $employees = EmployeeDetails::whereJsonContains('company_id', $companyId) // Check against JSON company_id
                    ->where('emp_id', '!=', $employeeId)
                    ->whereIn('employee_status', ['active', 'on-probation']) e // Exclude the logged-in employee
                    ->where(function ($query) {
                        // Apply search filtering if a search term is provided
                        if ($this->searchTerm) {
                            $query->where('first_name', 'like', '%' . $this->searchTerm . '%')
                                ->orWhere('last_name', 'like', '%' . $this->searchTerm . '%');
                        }
                    })
                    ->groupBy('emp_id', 'image', 'gender') // Group by the required fields
                    ->select(
                        'emp_id',
                        'gender',
                        'image',
                        DB::raw('MIN(CONCAT(first_name, " ", last_name)) as full_name') // Create a full name field
                    )
                    ->orderBy('full_name') // Order by full name
                    ->get();

                // Merge the results into the ccRecipients collection
                $this->ccRecipients = $this->ccRecipients->merge($employees);
            }

            // Optionally, you can remove duplicates if necessary
            $this->ccRecipients = $this->ccRecipients->unique('emp_id');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error in searchCCRecipients method: ' . $e->getMessage());
            session()->flash('error', 'An error occurred while searching for CC recipients. Please try again later.');
        }
    }

    public function toggleManager($empId)
    {
        if ($empId) {
            $this->fetchManagerDetails($empId);
        } else {
            $employeeId = auth()->guard('emp')->user()->emp_id;
            $applying_to = EmployeeDetails::where('emp_id', $employeeId)->first();
            if ($applying_to) {
                $managerId = $applying_to->manager_id;

                // Fetch the logged-in employee's manager details
                $managerDetails = EmployeeDetails::where('emp_id', $managerId)->first();
                if ($managerDetails) {
                    $fullName = ucfirst(strtolower($managerDetails->first_name)) . ' ' . ucfirst(strtolower($managerDetails->last_name));
                    $this->loginEmpManager = $fullName;
                    $this->empManagerDetails = $managerDetails;
                }
            }
            $this->selectedManager = [$empId];
            $this->showApplyingToContainer = false;
        }
    }
    // Method to fetch manager details
    private function fetchManagerDetails($empId)
    {
        $employeeDetails = EmployeeDetails::where('emp_id', $empId)->first();
        if ($employeeDetails) {
            $fullName = ucfirst(strtolower($employeeDetails->first_name)) . ' ' . ucfirst(strtolower($employeeDetails->last_name));
            $this->loginEmpManager = $fullName;
            $this->selectedManagerDetails = $employeeDetails;
        } else {
            $this->resetManagerDetails();
        }
    }

    private function resetManagerDetails()
    {
        $this->empManagerDetails = null;
    }

    public function toggleSelection($empId)
    {
        if (isset($this->selectedPeople[$empId])) {
            // If already selected, unselect it
            unset($this->selectedPeople[$empId]);
        } else {
            // Check if limit is reached
            if (count($this->selectedPeople) < 5) {
                // Add employee if under limit
                $this->selectedPeople[$empId] = true;
            } else {
                // Show error if limit exceeded
                session()->flash('error', 'You can only select up to 5 CC recipients.');
                $this->showAlert = true; // Assuming you're using this to control alert visibility
            }
        }

        $this->searchCCRecipients(); // Assuming you want to update the recipients list
        $this->fetchEmployeeDetails(); // Fetch details if necessary
    }

    public function handleCheckboxChange($empId)
    {
        try {
            // Check if the employee is selected (checkbox is checked)
            if (array_key_exists($empId, $this->selectedPeople) && $this->selectedPeople[$empId]) {
                // Checkbox is currently checked, so it’s being unchecked
                $this->removeFromCcTo($empId);
            } else {
                // Checkbox is currently unchecked, so it’s being checked
                $this->selectedPeople[$empId] = true;

                // Optionally add to selectedCcTo or perform other actions
                $this->selectedCcTo[] = ['emp_id' => $empId];
            }

            // Update cc_to field
            $this->cc_to = implode(',', array_column($this->selectedCcTo, 'emp_id'));

            // Fetch updated employee details and search CC recipients
            $this->fetchEmployeeDetails();
            $this->searchCCRecipients();
        } catch (\Exception $e) {
            // Handle the exception (log it, show a message, etc.)
            Log::error('Error handling checkbox change: ' . $e->getMessage());
            // You might also want to notify the user in some way
            session()->flash('error', 'An error occurred while updating CC recipients.');
        }
    }

    public function removeFromCcTo($empId)
    {
        try {
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
        } catch (\Exception $e) {
            // Handle the exception (log it, show a message, etc.)
            Log::error('Error removing from CC: ' . $e->getMessage());
            // Notify the user
            session()->flash('error', 'An error occurred while removing CC recipients.');
        }
    }


    public function hideAlert()
    {
        $this->showAlert = false;
        $this->searchCCRecipients();
    }

    public function markAsLeaveCancel()
    {
        $this->validate();
        try {
            // Check if a leave request ID is set
            if (empty($this->selectedLeaveRequestId)) {
                throw new \Exception('No leave request selected.');
            }

            // Find the leave request by ID
            $leaveRequest = LeaveRequest::findOrFail($this->selectedLeaveRequestId);

            // Get the employee ID and details
            $this->employeeDetails = EmployeeDetails::where('emp_id', $leaveRequest->emp_id)->first();
            // Prepare ccToDetails
            $ccToDetails = [];
            foreach ($this->selectedCCEmployees as $selectedEmployeeId) {
                // Check if the employee ID already exists in ccToDetails
                $existingIds = array_column($ccToDetails, 'emp_id');
                if (!in_array($selectedEmployeeId, $existingIds)) {
                    // Fetch additional details from EmployeeDetails table
                    $employeeDetails = EmployeeDetails::where('emp_id', $selectedEmployeeId)->first();
                    if ($employeeDetails) {
                        // Concatenate first_name and last_name to get the full name
                        $fullName = $employeeDetails->first_name . ' ' . $employeeDetails->last_name;
                        $ccToDetails[] = [
                            'emp_id' => $selectedEmployeeId,
                            'full_name' => $fullName,
                        ];
                    }
                }
            }

            // Prepare applyingToDetails
            $applyingToDetails = [];
            if ($this->selectedManagerDetails) {
                $employeeDetails = EmployeeDetails::where('emp_id', $this->selectedManagerDetails->emp_id)->first();
                if ($employeeDetails) {
                    $applyingToDetails[] = [
                        'manager_id' => $employeeDetails->emp_id,
                        'report_to' => $employeeDetails->first_name . ' ' . $employeeDetails->last_name,
                    ];
                }
            } else {
                $employeeDetails = EmployeeDetails::where('emp_id', $leaveRequest->emp_id)->first();
                $defaultManager = $employeeDetails->manager_id;
                // Handle default values if no employee is selected
                $applyingToDetails[] = [
                    'manager_id' => $defaultManager,
                    'report_to' => $this->loginEmpManager,
                    'image' => $this->loginEmpManagerProfile,
                ];
            }

            // Update the leave request
            $leaveRequest->update([
                'category_type' => 'Leave Cancel',
                'status' => 'approved',
                'cancel_status' => 'Pending Leave Cancel',
                'leave_cancel_reason' => $this->leave_cancel_reason,
                'applying_to' => json_encode($applyingToDetails), // Assuming applyingto is a JSON field
                'cc_to' => json_encode($ccToDetails), // Assuming ccto is a JSON field
            ]);

            $employeeId = auth()->guard('emp')->user()->emp_id;
            Notification::create([
                'emp_id' => $employeeId,
                'notification_type' => 'leaveCancel',
                'leave_type' => $leaveRequest->leave_type,
                'leave_reason' =>  $this->leave_cancel_reason,
                'applying_to' => json_encode($applyingToDetails),
                'cc_to' => json_encode($ccToDetails),
            ]);
            $this->cancel();
            session()->flash('message', 'Applied request for leave cancel successfully.');
            $this->showAlert = true;
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to submit the leave cancel request. Please try again.');
            $this->showAlert = true;
            Log::error('Error marking leave request as cancel: ' . $e->getMessage());
        }
    }

    public function toggleInfo()
    {
        $this->showinfoMessage = !$this->showinfoMessage;
        $this->showinfoButton = !$this->showinfoButton;
    }

    public  $LeaveShowinfoMessage = true;
    public  $LeaveShowinfoButton = false;
    public function toggleInfoLeave()
    {
        $this->LeaveShowinfoMessage = !$this->LeaveShowinfoMessage;
        $this->LeaveShowinfoButton = !$this->LeaveShowinfoButton;
    }

    //it will calculate number of days for leave application
    public function calculateNumberOfDays($fromDate, $fromSession, $toDate, $toSession, $leaveType)
    {
        try {
            $startDate = Carbon::parse($fromDate);
            $endDate = Carbon::parse($toDate);

            // Check if the start or end date is a weekend
            if ($startDate->isWeekend() || $endDate->isWeekend()) {
                return 0;
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
                if ($leaveType == 'Sick Leave') {
                    $totalDays += 1;
                } else {
                    if ($startDate->isWeekday()) {
                        $totalDays += 1;
                    }
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

    public function applyingTo($leaveRequestId)
    {
        $leaveRequest = LeaveRequest::find($leaveRequestId);
        if ($leaveRequest) {
            $this->selectedLeaveRequestId = $leaveRequestId;
            $this->leaveRequestDetails = $leaveRequest;
            $this->showApplyingToContainer = false;
            $this->show_reporting = true;
            $this->showApplyingTo = false;
        } else {
            // Handle the case where the leave request is not found
            $this->leaveRequestDetails = null;
            $this->applyingToDetails = [];
            $this->managerDetails = null;
        }
    }
    public function toggleApplyingto()
    {
        $this->showApplyingToContainer = !$this->showApplyingToContainer;
    }
    public $showCCEmployees = false;
    public function openModal()
    {
        $this->showCCEmployees = !$this->showCCEmployees;
    }
    public function cancel()
    {
        // Reset the state
        $this->selectedLeaveRequestId = null;
        $this->leaveRequestDetails = null;
        $this->showApplyingToContainer = false;
        $this->show_reporting = false;
        $this->showApplyingTo = true;
        $this->selectedCCEmployees = [];
        $this->leave_cancel_reason = null;
        $this->applying_to = null;
        $this->selectedLeaveType = null;
        $this->selectedPeople = [];
    }
    public function getFilteredManagers()
    {
        $this->render(); // Re-render to apply the search filter
    }
    public function handleEnterKey()
    {
        $this->searchCCRecipients();
    }
    public function render()
    {
        $this->selectedYear = Carbon::now()->format('Y');
        $employeeId = auth()->guard('emp')->user()->emp_id;
        $this->loginEmpManager = null;
        $this->selectedManager = $this->selectedManager ?? [];
        $managers = collect();
        $employeeGender = null;

        try {
            // Fetch details for the current employee
            $applying_to = EmployeeDetails::where('emp_id', $employeeId)->first();
            if ($applying_to) {
                $managerId = $applying_to->manager_id;
                // Fetch the logged-in employee's manager details
                $managerDetails = EmployeeDetails::where('emp_id', $managerId)->first();
                if ($managerDetails) {
                    $fullName = ucfirst(strtolower($managerDetails->first_name)) . ' ' . ucfirst(strtolower($managerDetails->last_name));
                    $this->loginEmpManager = $fullName;
                    $this->empManagerDetails = $managerDetails;

                    // Add the logged-in manager to the collection
                    $managers->push([
                        'full_name' => $fullName,
                        'emp_id' => $managerDetails->emp_id,
                        'gender' => $managerDetails->gender,
                        'image' => $managerDetails->image,
                    ]);
                }
            }

            // Fetch the gender of the logged-in employee
            $employeeGender = EmployeeDetails::where('emp_id', $employeeId)->select('gender')->first();

            // Fetch employees with job roles CTO, Chairman, and HR
            $jobRoles = ['CTO', 'Chairman'];
            $filteredManagers = EmployeeDetails::whereIn('job_role', $jobRoles)
                ->where(function ($query) {
                    // Apply search functionality
                    if ($this->searchQuery) {
                        $query->whereRaw('CONCAT(first_name, " ", last_name) LIKE ?', ["%{$this->searchQuery}%"]);
                    }
                })
                ->get(['first_name', 'last_name', 'emp_id', 'gender', 'image']);

            // Add the filtered managers to the collection
            $managers = $managers->merge(
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

            // Get the company_id from the logged-in employee's details
            $companyIds = $applying_to->company_id;

            // Convert the company IDs to an array if it's in JSON format
            $companyIdsArray = is_array($companyIds) ? $companyIds : json_decode($companyIds, true);

            // Fetch emp_ids from the HR table
            $hrEmpIds = Hr::pluck('emp_id');

            // Now, fetch employee details for these HR emp_ids
            $hrManagers = EmployeeDetails::whereIn('emp_id', $hrEmpIds)
                ->where(function ($query) use ($companyIdsArray) {
                    // Check if any of the company IDs match
                    foreach ($companyIdsArray as $companyId) {
                        $query->orWhere('company_id', 'like', "%\"$companyId\"%"); // Assuming company_id is stored as JSON
                    }
                })
                ->get(['first_name', 'last_name', 'emp_id', 'gender', 'image']);

            // Add HR managers to the collection
            $hrManagers->each(function ($hrManager) use ($managers) {
                $fullName = ucfirst(strtolower($hrManager->first_name)) . ' ' . ucfirst(strtolower($hrManager->last_name));
                $managers->push([
                    'full_name' => $fullName,
                    'emp_id' => $hrManager->emp_id,
                    'gender' => $hrManager->gender,
                    'image' => $hrManager->image,
                ]);
            });
            // Keep only unique emp_ids
            $managers = $managers->unique('emp_id')->values(); // Ensure we reset the keys

        } catch (\Exception $e) {
            Log::error('Error fetching employee or manager details: ' . $e->getMessage());
        }

        $this->cancelLeaveRequests = LeaveRequest::where('emp_id', $employeeId)
            ->where('status', 'approved')
            ->where('from_date', '>=', now()->subMonths(2))
            ->where('category_type', 'Leave')
            ->with('employee')
            ->get();
        return view('livewire.leave-cancel-page', [
            'cancelLeaveRequests' => $this->cancelLeaveRequests,
            'empManagerDetails' => $this->empManagerDetails,
            'selectedManagerDetails' => $this->selectedManagerDetails,
            'loginEmpManager' => $this->loginEmpManager,
            'managers' => $managers,
            'ccRecipients' => $this->ccRecipients,
            'showCasualLeaveProbation' => $this->showCasualLeaveProbation
        ]);
    }
}
