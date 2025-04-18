<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\EmployeeDetails;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Spatie\SimpleExcel\SimpleExcelWriter;
use App\Models\LeaveRequest;
use App\Models\EmployeeLeaveBalances;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use App\Helpers\LeaveHelper;
use App\Helpers\FlashMessageHelper;

class ReportManagement extends Component
{
    public $currentSection = " ";

    public $searching = 0;

    public $sno;
    public $employeeType = 'active';
    public $leaveType = 'all';
    public $search;
    public $fromDate;
    public $sortBy;
    public $toDate;
    public $hireDate;
    public $sickLeavePerYear;
    public $casualLeavePerYear;
    public $casualProbationLeavePerYear;
    public $lossOfPayPerYear;

    public $notFound;

    public $peoples;
    public $transactionType = 'all';

    public $employees;
    public $dateErrorMessage;
    public $leaveBalance = [];
    public function showContent($section)
    {
           
            // Step 1: Assign section value
            $this->currentSection = $section;
           
           
            $this->resetFields();
           
    }
    public function updateLeaveType()
    {
        $this->leaveType = $this->leaveType;
    }

    public function  updateSortBy()
    {
        $this->sortBy = $this->sortBy;
    }

    public function  updateEmployeeType($event)
    {

        $this->employeeType = $event;
    }

    public function closeShiftSummaryReport()
    {
        $this->currentSection = '';
    }
    public function closeAbsentReport()
    {
        $this->currentSection = '';
    }
    public function showContent1($section)
    {

        $this->currentSection = $section;
    }
    public function searchfilter()
    {
        $this->searching = 1;
    }
    public function close()
    {
        $this->currentSection = '';
        $this->resetErrorBag();
        $this->resetFields();
    }

    public function updateFromDate()
    {
        $this->fromDate = $this->fromDate;
    }
    public function updateToDate()
    {
        $this->toDate = $this->toDate;
    }



    public $myTeam;
    protected $rules = [
        'fromDate' => 'required|date',
        'toDate' => 'required|date|after_or_equal:fromDate',
        'leaveType' => 'required',
    ];
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
        $this->resetErrorBag($propertyName); // Clear errors for the updated property
    }

    public function resetFields()
    {
        $this->fromDate = null;
        $this->toDate = null;
        $this->leaveType = 'all';
        $this->transactionType = 'all';
        $this->leaveBalance = [];
        $this->employeeType = 'active';
        $this->sortBy = 'newest_first';
    }

    public function downloadLeaveAvailedReportInExcel()
    {
        $this->validate([
            'fromDate' => 'required|date',
            'toDate' => 'required|date|after_or_equal:fromDate',
        ], [
            'fromDate.required' => 'From date is required.',
            'toDate.required' => 'To date is required.',
            'toDate.after_or_equal' => 'To date must be a date after or equal to the from date.',
        ]);
        try {

            $loggedInEmpId = Auth::guard('emp')->user()->emp_id;

            $query = LeaveRequest::select(
                DB::raw('DATE(from_date) as date_only'),
                DB::raw('count(*) as total_requests'),
                'leave_applications.emp_id',
                'employee_details.first_name',
                'employee_details.last_name',
                'leave_applications.leave_type',
                'leave_applications.from_date as leave_from_date',
                'leave_applications.to_date as leave_to_date',
                'leave_applications.reason',
                'leave_applications.created_at',
                'leave_applications.from_session',
                'leave_applications.to_session',
                'leave_applications.leave_status',
                'leave_applications.updated_at',
            )
                ->join('employee_details', 'leave_applications.emp_id', '=', 'employee_details.emp_id')
                ->where('leave_applications.leave_status', 2)
                ->when($this->leaveType && $this->leaveType != 'all', function ($query) {

                    $leaveTypes = [
                        'lop' => 'Loss of Pay',
                        'casual_leave' => 'Casual Leave',
                        'sick' => 'Sick Leave',
                        'petarnity' => 'Petarnity Leave',
                        'casual_leave_probation' => 'Casual Leave Probation',
                        'maternity' => 'Maternity Leave',
                        'marriage_leave' => 'Marriage Leave',
                        'earned_leave' => 'Earned Leave',
                    ];

                    if (array_key_exists($this->leaveType, $leaveTypes)) {
                        $query->where('leave_applications.leave_type', $leaveTypes[$this->leaveType]);
                    }
                })
                ->where(function ($query) use ($loggedInEmpId) {
                    $query->whereJsonContains('applying_to', [['manager_id' => $loggedInEmpId]])
                        ->orWhereJsonContains('cc_to', [['emp_id' => $loggedInEmpId]]);
                })
                ->where(function ($query) {
                    $query->whereBetween('from_date', [$this->fromDate, $this->toDate])
                        ->orWhereBetween('to_date', [$this->fromDate, $this->toDate])
                        ->orWhere(function ($query) {
                            $query->where('from_date', '<=', $this->toDate)
                                ->where('to_date', '>=', $this->fromDate);
                        });
                });

            if ($this->employeeType == 'active') {
                $query->where(function ($query) {
                    $query->where('employee_details.employee_status', 'active')
                        ->orWhere('employee_details.employee_status', 'on-probation');
                });
            } else {
                $query->where(function ($query) {
                    $query->where('employee_details.employee_status', 'resigned')
                        ->orWhere('employee_details.employee_status', 'terminated');
                });
            }

            $query = $query->groupBy(
                'date_only',
                'leave_applications.emp_id',
                'employee_details.first_name',
                'employee_details.last_name',
                'leave_applications.leave_type',
                'leave_applications.from_date',
                'leave_applications.to_date',
                'leave_applications.reason',
                'leave_applications.created_at',
                'leave_applications.from_session',
                'leave_applications.to_session',
                'leave_applications.leave_status',
                'leave_applications.updated_at',
            )
                ->orderBy('date_only', 'asc')
                ->get();



            // Aggregate data by date
            $aggregatedData = $query->groupBy('date_only')->map(function ($group) {
                return [
                    'date' => Carbon::parse($group->first()->date_only)->format('d M Y'),
                    'details' => $group->map(function ($item) {
                        $leaveRequest = new LeaveRequest();
                        $leaveDays = $leaveRequest->calculateLeaveDays(
                            $item->leave_from_date,
                            $item->from_session,
                            $item->leave_to_date,
                            $item->to_session,
                            $item->leave_type
                        );
                        return [
                            'emp_id' => $item->emp_id,
                            'first_name' => $item->first_name,
                            'last_name' => $item->last_name,
                            'leave_type' => $item->leave_type,
                            'leave_from_date' => $item->leave_from_date,
                            'leave_to_date' => $item->leave_to_date,
                            'reason' => $item->reason,
                            'created_at' => $item->created_at,
                            'updated_at' => $item->updated_at,
                            'from_session' => $item->from_session,
                            'to_session' => $item->to_session,
                            'leave_days' => $leaveDays,
                            'leave_status' => $item->status,
                        ];
                    })
                ];
            });


            $employeeDetails = EmployeeDetails::where('emp_id', $loggedInEmpId)->first();
            $pdf = Pdf::loadView('leaveAvailedReportPdf', [
                'employeeDetails' => $employeeDetails,
                'leaveTransactions' => $aggregatedData,
                'fromDate' => $this->fromDate,
                'toDate' => $this->toDate,
            ]);
            $this->currentSection = '';
            FlashMessageHelper::flashSuccess('Leave Availed Report Downloaded Successfully!');

            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->stream();
            }, 'leave_availed_report.pdf');
        } catch (\Exception $e) {

            // Log::error('Error generating Leave Availed Report', [
            //     'exception' => $e, // Logs the exception details
            // ]);
            // Optionally, return a user-friendly error message
            FlashMessageHelper::flashError('An error occurred while generating the report. Please try again.');
        }
    }

    public $filteredEmployees;

    public function searchfilterleave()
    {
        $this->searching = 1;
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

    public  function updateTransactionType($event)
    {
        $this->transactionType = $event;
    }


    public function dayWiseLeaveTransactionReport()
    {
        $this->validate(
            [
                'fromDate' => 'required|date',
                'toDate' => 'required|date|after_or_equal:fromDate',
                'transactionType' => 'required',
                'employeeType' => 'required',
            ],
            [
                'fromDate.required' => 'From date is required.',
                'toDate.required' => 'To date is required.',
                'toDate.after_or_equal' => 'To date must be a date after or equal to the from date.',
                'transactionType.required' => 'Leave type is required.',
                'employeeType.required' => 'Employee type is required.',
            ]
        );
        try {
            $loggedInEmpId = Auth::guard('emp')->user()->emp_id;


            if ($this->transactionType === 'granted') {

                // When the transaction type is granted, fetch data from employee_leave_balances


                // Query to fetch leave data
                $query = EmployeeLeaveBalances::select(
                    'employee_leave_balances.emp_id',
                    'employee_details.first_name',
                    'employee_details.last_name',
                    DB::raw("JSON_EXTRACT(employee_leave_balances.leave_policy_id, '$') AS leave_policy_id"),  // Extract the entire leave policy array
                    'employee_leave_balances.status',
                    'employee_leave_balances.created_at',
                    'employee_leave_balances.period'
                )
                    ->join('employee_details', 'employee_leave_balances.emp_id', '=', 'employee_details.emp_id')
                    ->where(function ($query) use ($loggedInEmpId) {
                        // Fetch team members whose manager is the logged-in employee
                        $query->where('employee_details.manager_id', $loggedInEmpId)
                            ->whereNotIn('employee_details.employee_status', ['terminated', 'resigned']);
                    })
                    ->when($this->leaveType && $this->leaveType != 'all', function ($query) {
                        $leaveTypes = [
                            'casual_probation' => 'Casual Leave',
                            'maternity' => 'Maternity Leave',
                            'paternity' => 'Paternity Leave',
                            'sick' => 'Sick Leave',
                            'lop' => 'Loss of Pay',
                            'earned_leave' => 'Earned Leave',
                        ];

                        if (array_key_exists($this->leaveType, $leaveTypes)) {
                            $query->whereJsonContains('leave_policy_id', [['leave_name' => $leaveTypes[$this->leaveType]]]);
                        }
                    })
                    ->when($this->employeeType, function ($query) {
                        if ($this->employeeType == 'active') {
                            $query->where(function ($query) {
                                $query->where('employee_details.employee_status', 'active')
                                    ->orWhere('employee_details.employee_status', 'on-probation');
                            });
                        } else {
                            $query->where(function ($query) {
                                $query->where('employee_details.employee_status', 'resigned')
                                    ->orWhere('employee_details.employee_status', 'terminated');
                            });
                        }
                    })

                    ->get();

                // Group the data by emp_id
                $grantedData = $query->groupBy('emp_id')->map(function ($group) {
                    return [
                        'emp_id' => $group->first()->emp_id,
                        'first_name' => $group->first()->first_name,
                        'last_name' => $group->first()->last_name,
                        'leave_details' => $group->map(function ($item) {
                            // Decode the JSON to get all leave types
                            $leavePolicy = json_decode($item->leave_policy_id, true);
                            $leaveTypes = [
                                'casual_probation' => 'Casual Leave',
                                'maternity' => 'Maternity Leave',
                                'paternity' => 'Paternity Leave',
                                'sick' => 'Sick Leave',
                                'lop' => 'Loss of Pay',
                                'earned_leave' => 'Earned Leave',
                            ];

                            // Loop through all the leave types in the JSON array
                            $leaveDetails = [];
                            foreach ($leavePolicy as $leave) {
                                if ($this->leaveType && $this->leaveType != 'all' && $leave['leave_name'] !== $leaveTypes[$this->leaveType]) {
                                    continue;  // Skip if the leave name doesn't match the selected leave type
                                }
                                $period = $item->period;
                                if (!is_numeric($period) || strlen($period) !== 4) {
                                    Log::error("Invalid period format: " . json_encode($period));
                                    continue;
                                }

                                // Create Carbon instance for the start and end date of the year
                                $startDate = Carbon::createFromFormat('Y', $period)->firstOfYear()->toDateString(); // '2024-01-01'
                                $endDate = Carbon::createFromFormat('Y', $period)->lastOfYear()->toDateString(); // '2024-12-31'
                                $leaveDetails[] = [
                                    'leave_name' => $leave['leave_name'] ?? 'Unknown',
                                    'grant_days' => $leave['grant_days'] ?? 0,
                                    'status' => $item->status,
                                    'created_at' => $item->created_at,
                                    'from_date' => $startDate,  // The start date of the year
                                    'to_date' => $endDate,
                                ];
                            }

                            return $leaveDetails;
                        }),
                    ];
                });
            } elseif ($this->transactionType === 'lapsed') {
                $query = EmployeeLeaveBalances::select(
                    'employee_leave_balances.emp_id',
                    'employee_details.first_name',
                    'employee_details.last_name',
                    DB::raw("JSON_EXTRACT(employee_leave_balances.leave_policy_id, '$') AS leave_policy_id"),  // Extract the entire leave policy array
                    'employee_leave_balances.status',
                    'employee_leave_balances.created_at',
                    'employee_leave_balances.period'
                )
                    ->join('employee_details', 'employee_leave_balances.emp_id', '=', 'employee_details.emp_id')
                    ->where(function ($query) use ($loggedInEmpId) {
                        // Fetch team members whose manager is the logged-in employee
                        $query->where('employee_details.manager_id', $loggedInEmpId)
                            ->whereNotIn('employee_details.employee_status', ['terminated', 'resigned']);
                    })
                    ->where('employee_leave_balances.is_lapsed', 1)
                    ->when($this->leaveType && $this->leaveType != 'all', function ($query) {
                        $leaveTypes = [
                            'casual_probation' => 'Casual Leave',
                            'maternity' => 'Maternity Leave',
                            'paternity' => 'Paternity Leave',
                            'sick' => 'Sick Leave',
                            'lop' => 'Loss of Pay',
                            'earned_leave' => 'Earned Leave',
                        ];

                        if (array_key_exists($this->leaveType, $leaveTypes)) {
                            $query->whereJsonContains('leave_policy_id', [['leave_name' => $leaveTypes[$this->leaveType]]]);
                        }
                    })

                    ->get();


                // Group the data by emp_id
                $lapsedData = $query->groupBy('emp_id')->map(function ($group) {
                    return [
                        'emp_id' => $group->first()->emp_id,
                        'first_name' => $group->first()->first_name,
                        'last_name' => $group->first()->last_name,
                        'leave_details' => $group->map(function ($item) {

                            $period = $item->period;

                            $decemberStart = Carbon::createFromFormat('Y', $period)->month(12)->startOfMonth()->toDateString(); // First day of December
                            $decemberEnd = Carbon::createFromFormat('Y', $period)->month(12)->endOfMonth()->toDateString();   // Last day of December

                            $leaveBalances = LeaveBalances::getLeaveLapsedBalances($item->emp_id, $item->period);



                            // Decode the JSON to get all leave types
                            $leaveDetails1 = json_decode($item->leave_policy_id, true);
                            $leaveDetails = [];
                            $leaveTypes = [
                                'casual_probation' => 'Casual Leave',
                                'maternity' => 'Maternity Leave',
                                'paternity' => 'Paternity Leave',
                                'sick' => 'Sick Leave',
                                'lop' => 'Loss of Pay',
                                'earned_leave' => 'Earned Leave',
                            ];

                            // Loop through all the leave types in the JSON array

                            foreach ($leaveTypes as $key => $leaveName) {

                                if ($this->leaveType && $this->leaveType != 'all' && $leaveName !== $leaveTypes[$this->leaveType]) {
                                    continue;  // Skip if the leave name doesn't match the selected leave type
                                }
                                $balanceKey = $this->normalizeLeaveNameToBalanceKey($leaveName);

                                // This will print the balance key for debugging

                                // Get the balance for this leave type (defaults to 0 if not found)
                                $remainingBalance = $leaveBalances[$balanceKey] ?? 0;


                                // Skip if there's no remaining balance
                                if ($remainingBalance <= 0) {
                                    continue;
                                }

                                // Add formatted leave object to the array with remaining balance (days)
                                $leaveDetails[] = [
                                    'leave_name' => $leaveName,
                                    'grant_days' => $remainingBalance,
                                    'status' => 'Lapsed',
                                    'created_at' => $item->created_at,
                                    'from_date' => $decemberStart,  // The start date of the year
                                    'to_date' => $decemberEnd,      // The end date of the year


                                ];
                            }

                            return $leaveDetails;
                        }),
                    ];
                });
            } elseif ($this->transactionType === 'all') {

                $query = EmployeeLeaveBalances::select(
                    'employee_leave_balances.emp_id',
                    'employee_details.first_name',
                    'employee_details.last_name',
                    DB::raw("JSON_EXTRACT(employee_leave_balances.leave_policy_id, '$') AS leave_policy_id"),  // Extract the entire leave policy array
                    'employee_leave_balances.status',
                    'employee_leave_balances.created_at',
                    'employee_leave_balances.period'
                )
                    ->join('employee_details', 'employee_leave_balances.emp_id', '=', 'employee_details.emp_id')
                    ->where(function ($query) use ($loggedInEmpId) {
                        // Fetch team members whose manager is the logged-in employee
                        $query->where('employee_details.manager_id', $loggedInEmpId)
                            ->whereNotIn('employee_details.employee_status', ['terminated', 'resigned']);
                    })
                    ->when($this->leaveType && $this->leaveType != 'all', function ($query) {
                        $leaveTypes = [
                            'casual_probation' => 'Casual Leave',
                            'maternity' => 'Maternity Leave',
                            'paternity' => 'Paternity Leave',
                            'sick' => 'Sick Leave',
                            'lop' => 'Loss of Pay',
                            'earned_leave' => 'Earned Leave',
                        ];

                        if (array_key_exists($this->leaveType, $leaveTypes)) {
                            $query->whereJsonContains('leave_policy_id', [['leave_name' => $leaveTypes[$this->leaveType]]]);
                        }
                    })
                    ->when($this->employeeType, function ($query) {
                        if ($this->employeeType == 'active') {
                            $query->where(function ($query) {
                                $query->where('employee_details.employee_status', 'active')
                                    ->orWhere('employee_details.employee_status', 'on-probation');
                            });
                        } else {
                            $query->where(function ($query) {
                                $query->where('employee_details.employee_status', 'resigned')
                                    ->orWhere('employee_details.employee_status', 'terminated');
                            });
                        }
                    })

                    ->get();




                // Group the data by emp_id
                $grantedData = $query->groupBy('emp_id')->map(function ($group) {
                    return [
                        'emp_id' => $group->first()->emp_id,
                        'first_name' => $group->first()->first_name,
                        'last_name' => $group->first()->last_name,
                        'leave_details' => $group->map(function ($item) {
                            // Decode the JSON to get all leave types
                            $leavePolicy = json_decode($item->leave_policy_id, true);
                            $leaveTypes = [
                                'casual_probation' => 'Casual Leave',
                                'maternity' => 'Maternity Leave',
                                'paternity' => 'Paternity Leave',
                                'sick' => 'Sick Leave',
                                'lop' => 'Loss of Pay',
                                'earned_leave' => 'Earned Leave',
                            ];

                            // Loop through all the leave types in the JSON array
                            $leaveDetails = [];
                            foreach ($leavePolicy as $leave) {
                                if ($this->leaveType && $this->leaveType != 'all' && $leave['leave_name'] !== $leaveTypes[$this->leaveType]) {
                                    continue;  // Skip if the leave name doesn't match the selected leave type
                                }
                                $period = $item->period;
                                if (!is_numeric($period) || strlen($period) !== 4) {
                                    Log::error("Invalid period format: " . json_encode($period));
                                    continue;
                                }

                                // Create Carbon instance for the start and end date of the year
                                $startDate = Carbon::createFromFormat('Y', $period)->firstOfYear()->toDateString();
                                $endDate = Carbon::createFromFormat('Y', $period)->lastOfYear()->toDateString();

                                $leaveDetails[] = [
                                    'leave_name' => $leave['leave_name'] ?? 'Unknown',
                                    'grant_days' => $leave['grant_days'] ?? 0,
                                    'status' => $item->status,
                                    'created_at' => $item->created_at,
                                    'from_date' => $startDate,
                                    'to_date' => $endDate,
                                ];
                            }

                            return $leaveDetails;
                        }),
                    ];
                });


                $query = EmployeeLeaveBalances::select(
                    'employee_leave_balances.emp_id',
                    'employee_details.first_name',
                    'employee_details.last_name',
                    DB::raw("JSON_EXTRACT(employee_leave_balances.leave_policy_id, '$') AS leave_policy_id"),  // Extract the entire leave policy array
                    'employee_leave_balances.status',
                    'employee_leave_balances.created_at',
                    'employee_leave_balances.period'
                )
                    ->join('employee_details', 'employee_leave_balances.emp_id', '=', 'employee_details.emp_id')
                    ->where(function ($query) use ($loggedInEmpId) {
                        // Fetch team members whose manager is the logged-in employee
                        $query->where('employee_details.manager_id', $loggedInEmpId)
                            ->whereNotIn('employee_details.employee_status', ['terminated', 'resigned']);
                    })
                    ->where('employee_leave_balances.is_lapsed', 1)
                    ->when($this->leaveType && $this->leaveType != 'all', function ($query) {
                        $leaveTypes = [
                            'casual_probation' => 'Casual Leave',
                            'maternity' => 'Maternity Leave',
                            'paternity' => 'Paternity Leave',
                            'sick' => 'Sick Leave',
                            'lop' => 'Loss of Pay',
                            'earned_leave' => 'Earned Leave',
                        ];

                        if (array_key_exists($this->leaveType, $leaveTypes)) {
                            $query->whereJsonContains('leave_policy_id', [['leave_name' => $leaveTypes[$this->leaveType]]]);
                        }
                    })

                    ->get();

                // Group the data by emp_id
                $lapsedData = $query->groupBy('emp_id')->map(function ($group) {
                    return [
                        'emp_id' => $group->first()->emp_id,
                        'first_name' => $group->first()->first_name,
                        'last_name' => $group->first()->last_name,
                        'leave_details' => $group->map(function ($item) {

                            $period = $item->period;
                            $decemberStart = Carbon::createFromFormat('Y', $period)->month(12)->startOfMonth()->toDateString(); // First day of December
                            $decemberEnd = Carbon::createFromFormat('Y', $period)->month(12)->endOfMonth()->toDateString();   // Last day of December

                            $leaveBalances = LeaveBalances::getLeaveLapsedBalances($item->emp_id, $item->period);



                            // Decode the JSON to get all leave types
                            $leaveDetails1 = json_decode($item->leave_policy_id, true);
                            $leaveDetails = [];
                            $leaveTypes = [
                                'casual_probation' => 'Casual Leave',
                                'maternity' => 'Maternity Leave',
                                'paternity' => 'Paternity Leave',
                                'sick' => 'Sick Leave',
                                'lop' => 'Loss of Pay',
                                'earned_leave' => 'Earned Leave',
                            ];

                            // Loop through all the leave types in the JSON array

                            foreach ($leaveTypes as $key => $leaveName) {

                                if ($this->leaveType && $this->leaveType != 'all' && $leaveName !== $leaveTypes[$this->leaveType]) {
                                    continue;  // Skip if the leave name doesn't match the selected leave type
                                }
                                $balanceKey = $this->normalizeLeaveNameToBalanceKey($leaveName);

                                // This will print the balance key for debugging

                                // Get the balance for this leave type (defaults to 0 if not found)
                                $remainingBalance = $leaveBalances[$balanceKey] ?? 0;


                                // Skip if there's no remaining balance
                                if ($remainingBalance <= 0) {
                                    continue;
                                }

                                // Add formatted leave object to the array with remaining balance (days)
                                $leaveDetails[] = [
                                    'leave_name' => $leaveName,
                                    'grant_days' => $remainingBalance,
                                    'status' => 'Lapsed',
                                    'created_at' => $item->created_at,
                                    'from_date' => $decemberStart,  // The start date of the year
                                    'to_date' => $decemberEnd,      // The end date of the year


                                ];
                            }

                            return $leaveDetails;
                        }),
                    ];
                });


                $query = LeaveRequest::select(
                    DB::raw('DATE(from_date) as date_only'),
                    DB::raw('count(*) as total_requests'),
                    'leave_applications.emp_id',
                    'employee_details.first_name',
                    'employee_details.last_name',
                    'leave_applications.leave_type',
                    'leave_applications.from_date as leave_from_date',
                    'leave_applications.to_date as leave_to_date',
                    'leave_applications.reason',
                    'leave_applications.created_at',
                    'leave_applications.from_session',
                    'leave_applications.to_session',
                    'leave_applications.leave_status'
                )
                    ->join('employee_details', 'leave_applications.emp_id', '=', 'employee_details.emp_id')
                    ->when($this->leaveType && $this->leaveType != 'all', function ($query) {
                        $leaveTypes = [
                            'lop' => 'Loss of Pay',
                            'casual_leave' => 'Casual Leave',
                            'sick' => 'Sick Leave',
                            'petarnity' => 'Petarnity Leave',
                            'casual_leave_probation' => 'Casual Leave Probation',
                            'maternity' => 'Maternity Leave',
                            'marriage_leave' => 'Marriage Leave',
                            'earned_leave' => 'Earned Leave',
                        ];

                        if (array_key_exists($this->leaveType, $leaveTypes)) {
                            $query->where('leave_applications.leave_type', $leaveTypes[$this->leaveType]);
                        }
                    })
                    ->where(function ($query) use ($loggedInEmpId) {
                        $query->whereJsonContains('applying_to', [['manager_id' => $loggedInEmpId]])
                            ->orWhereJsonContains('cc_to', [['emp_id' => $loggedInEmpId]]);
                    })
                    ->where(function ($query) {
                        $query->whereBetween('from_date', [$this->fromDate, $this->toDate])
                            ->orWhereBetween('to_date', [$this->fromDate, $this->toDate])
                            ->orWhere(function ($query) {
                                $query->where('from_date', '<=', $this->toDate)
                                    ->where('to_date', '>=', $this->fromDate);
                            });
                    })
                    ->when($this->transactionType && $this->transactionType != 'all', function ($query) {
                        $transactionTypes = [
                            'availed' => 2,
                            'withdrawn' => 4,
                            'rejected' => 3,
                        ];
                        if (array_key_exists($this->transactionType, $transactionTypes)) {
                            $query->where('leave_status', $transactionTypes[$this->transactionType]);
                        }
                    })
                    ->when($this->employeeType, function ($query) {
                        if ($this->employeeType == 'active') {
                            $query->where(function ($query) {
                                $query->where('employee_details.employee_status', 'active')
                                    ->orWhere('employee_details.employee_status', 'on-probation');
                            });
                        } else {
                            $query->where(function ($query) {
                                $query->where('employee_details.employee_status', 'resigned')
                                    ->orWhere('employee_details.employee_status', 'terminated');
                            });
                        }
                    })
                    ->where('leave_status', '!=', 5)
                    ->orderBy('created_at', $this->sortBy === 'oldest_first' ? 'asc' : 'desc')
                    ->groupBy(
                        DB::raw('DATE(from_date)'),
                        'leave_applications.emp_id',
                        'employee_details.first_name',
                        'employee_details.last_name',
                        'leave_applications.leave_type',
                        'leave_applications.from_date',
                        'leave_applications.to_date',
                        'leave_applications.reason',
                        'leave_applications.created_at',
                        'leave_applications.from_session',
                        'leave_applications.to_session',
                        'leave_applications.leave_status'
                    )
                    ->get();

                $leaveTransactionData = $query->groupBy('date_only')->map(function ($group) {
                    return [
                        'date' => Carbon::parse($group->first()->date_only)->format('d M Y'),
                        'emp_id' => $group->first()->emp_id,  // Add emp_id
                        'first_name' => $group->first()->first_name,  // Add first_name
                        'last_name' => $group->first()->last_name,
                        'leave_details' => $group->map(function ($item) {
                            $leaveRequest = new LeaveRequest();
                            $leaveDays = $leaveRequest->calculateLeaveDays(
                                $item->leave_from_date,
                                $item->from_session,
                                $item->leave_to_date,
                                $item->to_session,
                                $item->leave_type
                            );
                            return [
                                'emp_id' => $item->emp_id,
                                'first_name' => $item->first_name,
                                'last_name' => $item->last_name,
                                'leave_type' => $item->leave_type,
                                'leave_from_date' => $item->leave_from_date,
                                'leave_to_date' => $item->leave_to_date,
                                'reason' => $item->reason,
                                'created_at' => $item->created_at,
                                'from_session' => $item->from_session,
                                'to_session' => $item->to_session,
                                'leave_days' => $leaveDays,
                                'leave_status' => $item->leave_status,
                            ];
                        })
                    ];
                });

                $leaveTransactions = $grantedData->concat($leaveTransactionData)->concat($lapsedData);
            } else {


                $query = LeaveRequest::select(
                    DB::raw('DATE(from_date) as date_only'),
                    DB::raw('count(*) as total_requests'),
                    'leave_applications.emp_id',
                    'employee_details.first_name',
                    'employee_details.last_name',
                    'leave_applications.leave_type',
                    'leave_applications.from_date as leave_from_date',
                    'leave_applications.to_date as leave_to_date',
                    'leave_applications.reason',
                    'leave_applications.created_at',
                    'leave_applications.from_session',
                    'leave_applications.to_session',
                    'leave_applications.leave_status'
                )
                    ->join('employee_details', 'leave_applications.emp_id', '=', 'employee_details.emp_id')
                    ->when($this->leaveType && $this->leaveType != 'all', function ($query) {
                        $leaveTypes = [
                            'lop' => 'Loss of Pay',
                            'casual_leave' => 'Casual Leave',
                            'sick' => 'Sick Leave',
                            'petarnity' => 'Petarnity Leave',
                            'casual_leave_probation' => 'Casual Leave Probation',
                            'maternity' => 'Maternity Leave',
                            'marriage_leave' => 'Marriage Leave',
                        ];

                        if (array_key_exists($this->leaveType, $leaveTypes)) {
                            $query->where('leave_applications.leave_type', $leaveTypes[$this->leaveType]);
                        }
                    })
                    ->where(function ($query) use ($loggedInEmpId) {
                        $query->whereJsonContains('applying_to', [['manager_id' => $loggedInEmpId]])
                            ->orWhereJsonContains('cc_to', [['emp_id' => $loggedInEmpId]]);
                    })
                    ->where(function ($query) {
                        $query->whereBetween('from_date', [$this->fromDate, $this->toDate])
                            ->orWhereBetween('to_date', [$this->fromDate, $this->toDate])
                            ->orWhere(function ($query) {
                                $query->where('from_date', '<=', $this->toDate)
                                    ->where('to_date', '>=', $this->fromDate);
                            });
                    })
                    ->when($this->transactionType && $this->transactionType != 'all', function ($query) {
                        $transactionTypes = [
                            'availed' => 2,
                            'withdrawn' => 4,
                            'rejected' => 3,
                        ];
                        if (array_key_exists($this->transactionType, $transactionTypes)) {
                            $query->where('leave_status', $transactionTypes[$this->transactionType]);
                        }
                    })
                    ->when($this->employeeType, function ($query) {
                        if ($this->employeeType == 'active') {
                            $query->where(function ($query) {
                                $query->where('employee_details.employee_status', 'active')
                                    ->orWhere('employee_details.employee_status', 'on-probation');
                            });
                        } else {
                            $query->where(function ($query) {
                                $query->where('employee_details.employee_status', 'resigned')
                                    ->orWhere('employee_details.employee_status', 'terminated');
                            });
                        }
                    })
                    ->where('leave_status', '!=', 5)
                    ->orderBy('created_at', $this->sortBy === 'oldest_first' ? 'asc' : 'desc')
                    ->groupBy(
                        DB::raw('DATE(from_date)'),
                        'leave_applications.emp_id',
                        'employee_details.first_name',
                        'employee_details.last_name',
                        'leave_applications.leave_type',
                        'leave_applications.from_date',
                        'leave_applications.to_date',
                        'leave_applications.reason',
                        'leave_applications.created_at',
                        'leave_applications.from_session',
                        'leave_applications.to_session',
                        'leave_applications.leave_status'
                    )
                    ->get();

                $leaveTransactionData = $query->groupBy('date_only')->map(function ($group) {
                    return [
                        'date' => Carbon::parse($group->first()->date_only)->format('d M Y'),
                        'emp_id' => $group->first()->emp_id,  // Add emp_id
                        'first_name' => $group->first()->first_name,  // Add first_name
                        'last_name' => $group->first()->last_name,
                        'leave_details' => $group->map(function ($item) {
                            $leaveRequest = new LeaveRequest();
                            $leaveDays = $leaveRequest->calculateLeaveDays(
                                $item->leave_from_date,
                                $item->from_session,
                                $item->leave_to_date,
                                $item->to_session,
                                $item->leave_type
                            );
                            return [
                                'emp_id' => $item->emp_id,
                                'first_name' => $item->first_name,
                                'last_name' => $item->last_name,
                                'leave_type' => $item->leave_type,
                                'leave_from_date' => $item->leave_from_date,
                                'leave_to_date' => $item->leave_to_date,
                                'reason' => $item->reason,
                                'created_at' => $item->created_at,
                                'from_session' => $item->from_session,
                                'to_session' => $item->to_session,
                                'leave_days' => $leaveDays,
                                'leave_status' => $item->leave_status,
                            ];
                        })
                    ];
                });
            }

            // Load the view and generate the PDF report
            $employeeDetails = EmployeeDetails::where('emp_id', $loggedInEmpId)->first();

            $pdf = Pdf::loadView('daywiseLeaveTransactionReportPdf', [
                'employeeDetails' => $employeeDetails,
                'leaveTransactions' => isset($leaveTransactions)
                    ? $leaveTransactions
                    : ($this->transactionType == 'granted'
                        ? $grantedData
                        : ($this->transactionType == 'lapsed'
                            ? $lapsedData
                            : $leaveTransactionData)
                    ),

                'toDate' => $this->toDate,
                'fromDate' => $this->fromDate,
                'transactionType' => $this->transactionType
            ]);
            $this->currentSection = '';
            FlashMessageHelper::flashSuccess('DayWise Leave Transaction Report Downloaded Successfully!');

            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->stream();
            }, 'Daywise_leave_transactions.pdf');
        } catch (\Exception $e) {

            Log::error('Error generating Leave Availed Report', [
                'exception' => $e, // Logs the exception details
            ]);
            // Optionally, return a user-friendly error message
            FlashMessageHelper::flashError('An error occurred while generating the report. Please try again.');
        }
    }



    public function leaveBalanceAsOnADayReport()
    {
        $this->validate([
            'toDate' => 'required|date',
        ], [
            'toDate.required' => 'Date is required.',
        ]);
        try {

            // Validate the 'toDate' input



            // Check if any employees are selected
            if (empty($this->leaveBalance)) {
                return redirect()->back()->with('error', 'Select at least one employee detail');
            } else {

                // Fetch employee details for selected employees
                $loggedInEmpId = Auth::guard('emp')->user()->emp_id;


                $employees = EmployeeDetails::where('manager_id', $loggedInEmpId)
                    ->whereIn('emp_id', $this->leaveBalance)
                    ->select('emp_id', 'first_name', 'last_name','employee_status')
                    ->get();




                $combinedData = $employees->map(function ($employee) {
                    $leaveBalances = ReportManagement::getLeaveBalances($employee->emp_id, $this->toDate);



                    return [
                        'emp_id' => $employee->emp_id,
                        'first_name' => $employee->first_name,
                        'last_name' => $employee->last_name,
                        'sick_leave_balance' => $leaveBalances['sickLeaveBalance'] ?? 0,
                        'casual_leave_balance' => $leaveBalances['casualLeaveBalance'] ?? 0,
                        'casual_leave_probation_balance' => $leaveBalances['casualProbationLeaveBalance'] ?? 0,
                        'loss_of_pay_balance' => $leaveBalances['lossOfPayBalance'] ?? 0,
                        'marriage_leave_balance' => $leaveBalances['marriageLeaveBalance'] ?? 0,
                        'maternity_leave_balance' => $leaveBalances['maternityLeaveBalance'] ?? 0,
                        'paternity_leave_balance' => $leaveBalances['paternityLeaveBalance'] ?? 0,
                        'earned_leave_balance' => $leaveBalances['earnedLeaveBalance'] ?? 0,
                    ];
                });



                $employeeDetails = EmployeeDetails::where('emp_id', $loggedInEmpId)->first();

                $pdf = Pdf::loadView('leaveBalanceAsOnDayReport', [
                    'employeeDetails' => $employeeDetails,
                    'leaveTransactions' => $combinedData,
                    'fromDate' => $this->fromDate,
                    'toDate' => $this->toDate,
                ]);
                $this->currentSection = '';
                FlashMessageHelper::flashSuccess('Leave Balance Report Downloaded Successfully!');

                return response()->streamDownload(function () use ($pdf) {
                    echo $pdf->stream();
                }, 'leave_balance_as_on_a_day.pdf');
            }
        } catch (\Exception $e) {


            // Optionally, return a user-friendly error message
            FlashMessageHelper::flashError('An error occurred while generating the report. Please try again.');
        }
    }



    public function downloadLeaveTransactionReport()
    {
        $this->validate([
            'fromDate' => 'required|date',
            'toDate' => 'required|date|after_or_equal:fromDate',
        ], [
            'fromDate.required' => 'From date is required.',
            'toDate.required' => 'To date is required.',
            'toDate.after_or_equal' => 'To date must be a date after or equal to the from date.',
        ]);
        try {

            $loggedInEmpId = Auth::guard('emp')->user()->emp_id;

            if ($this->transactionType === 'granted') {
                // When the transaction type is granted, fetch data from employee_leave_balances

                // Query to fetch leave data
                $query = EmployeeLeaveBalances::select(
                    'employee_leave_balances.emp_id',
                    'employee_details.first_name',
                    'employee_details.last_name',
                    DB::raw("JSON_EXTRACT(employee_leave_balances.leave_policy_id, '$') AS leave_policy_id"),  // Extract the entire leave policy array
                    'employee_leave_balances.status',
                    'employee_leave_balances.created_at',
                    'employee_leave_balances.period'
                )
                    ->join('employee_details', 'employee_leave_balances.emp_id', '=', 'employee_details.emp_id')
                    ->where(function ($query) use ($loggedInEmpId) {
                        // Fetch team members whose manager is the logged-in employee
                        $query->where('employee_details.manager_id', $loggedInEmpId)
                            ->whereNotIn('employee_details.employee_status', ['terminated', 'resigned']);
                    })
                    ->when($this->leaveType && $this->leaveType != 'all', function ($query) {
                        $leaveTypes = [
                            'casual_probation' => 'Casual Leave',
                            'maternity' => 'Maternity Leave',
                            'paternity' => 'Paternity Leave',
                            'sick' => 'Sick Leave',
                            'lop' => 'Loss of Pay',
                        ];

                        if (array_key_exists($this->leaveType, $leaveTypes)) {
                            $query->whereJsonContains('leave_policy_id', [['leave_name' => $leaveTypes[$this->leaveType]]]);
                        }
                    })
                    ->when($this->employeeType, function ($query) {
                        if ($this->employeeType == 'active') {
                            $query->where(function ($query) {
                                $query->where('employee_details.employee_status', 'active')
                                    ->orWhere('employee_details.employee_status', 'on-probation');
                            });
                        } else {
                            $query->where(function ($query) {
                                $query->where('employee_details.employee_status', 'resigned')
                                    ->orWhere('employee_details.employee_status', 'terminated');
                            });
                        }
                    })

                    ->get();
                   
                  

                // Group the data by emp_id
                $grantedData = $query->groupBy('emp_id')->map(function ($group) {
                    return [
                        'emp_id' => $group->first()->emp_id,
                        'first_name' => $group->first()->first_name,
                        'last_name' => $group->first()->last_name,
                        'leave_details' => $group->map(function ($item) {
                            // Decode the JSON to get all leave types
                            $leavePolicy = json_decode($item->leave_policy_id, true);
                            $leaveTypes = [
                                'casual_probation' => 'Casual Leave',
                                'maternity' => 'Maternity Leave',
                                'paternity' => 'Paternity Leave',
                                'sick' => 'Sick Leave',
                                'lop' => 'Loss of Pay',
                            ];

                            // Loop through all the leave types in the JSON array
                            $leaveDetails = [];
                            foreach ($leavePolicy as $leave) {
                                if ($this->leaveType && $this->leaveType != 'all' && $leave['leave_name'] !== $leaveTypes[$this->leaveType]) {
                                    continue;  // Skip if the leave name doesn't match the selected leave type
                                }
                                $period = $item->period;
                                if (!is_numeric($period) || strlen($period) !== 4) {
                                    Log::error("Invalid period format: " . json_encode($period));
                                    continue;
                                }

                                // Create Carbon instance for the start and end date of the year
                                $startDate = Carbon::createFromFormat('Y', $period)->firstOfYear()->toDateString(); // '2024-01-01'
                                $endDate = Carbon::createFromFormat('Y', $period)->lastOfYear()->toDateString(); // '2024-12-31'
                                $leaveDetails[] = [
                                    'leave_name' => $leave['leave_name'] ?? 'Unknown',
                                    'grant_days' => $leave['grant_days'] ?? 0,
                                    'status' => $item->status,
                                    'created_at' => $item->created_at,
                                    'from_date' => $startDate,  // The start date of the year
                                    'to_date' => $endDate,
                                ];
                            }

                            return $leaveDetails;
                        }),
                    ];
                });
            } elseif ($this->transactionType == 'lapsed') {
                $employeeId = auth()->guard('emp')->user()->emp_id;


                $query = EmployeeLeaveBalances::select(
                    'employee_leave_balances.emp_id',
                    'employee_details.first_name',
                    'employee_details.last_name',
                    DB::raw("JSON_EXTRACT(employee_leave_balances.leave_policy_id, '$') AS leave_policy_id"),  // Extract the entire leave policy array
                    'employee_leave_balances.status',
                    'employee_leave_balances.created_at',
                    'employee_leave_balances.period'
                )
                    ->join('employee_details', 'employee_leave_balances.emp_id', '=', 'employee_details.emp_id')
                    ->where(function ($query) use ($loggedInEmpId) {
                        // Fetch team members whose manager is the logged-in employee
                        $query->where('employee_details.manager_id', $loggedInEmpId)
                            ->whereNotIn('employee_details.employee_status', ['terminated', 'resigned']);
                    })
                    ->where('employee_leave_balances.is_lapsed', 1)  // Filter for lapsed leave
                    ->when($this->leaveType && $this->leaveType != 'all', function ($query) {
                        $leaveTypes = [
                            'casual_probation' => 'Casual Leave',
                            'maternity' => 'Maternity Leave',
                            'paternity' => 'Paternity Leave',
                            'sick' => 'Sick Leave',
                            'lop' => 'Loss of Pay',
                        ];

                        if (array_key_exists($this->leaveType, $leaveTypes)) {
                            $query->whereJsonContains('leave_policy_id', [['leave_name' => $leaveTypes[$this->leaveType]]]);
                        }
                    })
                    ->when($this->employeeType, function ($query) {
                        if ($this->employeeType == 'active') {
                            $query->where(function ($query) {
                                $query->where('employee_details.employee_status', 'active')
                                    ->orWhere('employee_details.employee_status', 'on-probation');
                            });
                        } else {
                            $query->where(function ($query) {
                                $query->where('employee_details.employee_status', 'resigned')
                                    ->orWhere('employee_details.employee_status', 'terminated');
                            });
                        }
                    })
                    ->get();
                 


                // Check what is being grouped by emp_id


                // Group the data by emp_id
                $lapsedData = $query->groupBy('emp_id')->map(function ($group) {
                    return [
                        'emp_id' => $group->first()->emp_id,
                        'first_name' => $group->first()->first_name,
                        'last_name' => $group->first()->last_name,
                        'leave_details' => $group->map(function ($item) {

                            $period = $item->period;
                            $decemberStart = Carbon::createFromFormat('Y', $period)->month(12)->startOfMonth()->toDateString(); // First day of December
                            $decemberEnd = Carbon::createFromFormat('Y', $period)->month(12)->endOfMonth()->toDateString();   // Last day of December

                            $leaveBalances = LeaveBalances::getLeaveLapsedBalances($item->emp_id, $item->period);



                            // Decode the JSON to get all leave types
                            $leaveDetails1 = json_decode($item->leave_policy_id, true);
                            $leaveDetails = [];
                            $leaveTypes = [
                                'casual_probation' => 'Casual Leave',
                                'maternity' => 'Maternity Leave',
                                'paternity' => 'Paternity Leave',
                                'sick' => 'Sick Leave',
                                'lop' => 'Loss of Pay',
                            ];

                            // Loop through all the leave types in the JSON array

                            foreach ($leaveTypes as $key => $leaveName) {

                                if ($this->leaveType && $this->leaveType != 'all' && $leaveName !== $leaveTypes[$this->leaveType]) {
                                    continue;  // Skip if the leave name doesn't match the selected leave type
                                }
                                $balanceKey = $this->normalizeLeaveNameToBalanceKey($leaveName);

                                // This will print the balance key for debugging

                                // Get the balance for this leave type (defaults to 0 if not found)
                                $remainingBalance = $leaveBalances[$balanceKey] ?? 0;


                                // Skip if there's no remaining balance
                                if ($remainingBalance <= 0) {
                                    continue;
                                }

                                // Add formatted leave object to the array with remaining balance (days)
                                $leaveDetails[] = [
                                    'leave_name' => $leaveName,
                                    'grant_days' => $remainingBalance,
                                    'status' => 'Lapsed',
                                    'created_at' => $item->created_at,
                                    'from_date' => $decemberStart,  // The start date of the year
                                    'to_date' => $decemberEnd,      // The end date of the year


                                ];
                            }

                            return $leaveDetails;
                        }),
                    ];
                });



                // The $lapsedData will now contain the employee leave details with the remaining balances.

            } elseif ($this->transactionType === 'all') {
                // Query to fetch leave data
                $query = EmployeeLeaveBalances::select(
                    'employee_leave_balances.emp_id',
                    'employee_details.first_name',
                    'employee_details.last_name',
                    DB::raw("JSON_EXTRACT(employee_leave_balances.leave_policy_id, '$') AS leave_policy_id"),  // Extract the entire leave policy array
                    'employee_leave_balances.status',
                    'employee_leave_balances.created_at',
                    'employee_leave_balances.period'
                )
                    ->join('employee_details', 'employee_leave_balances.emp_id', '=', 'employee_details.emp_id')
                    ->where(function ($query) use ($loggedInEmpId) {
                        // Fetch team members whose manager is the logged-in employee
                        $query->where('employee_details.manager_id', $loggedInEmpId)
                            ->whereNotIn('employee_details.employee_status', ['terminated', 'resigned']);
                    })
                    ->when($this->leaveType && $this->leaveType != 'all', function ($query) {
                        $leaveTypes = [
                            'casual_probation' => 'Casual Leave',
                            'maternity' => 'Maternity Leave',
                            'paternity' => 'Paternity Leave',
                            'sick' => 'Sick Leave',
                            'lop' => 'Loss of Pay',
                            'earned_leave' => 'Earned Leave',
                        ];

                        if (array_key_exists($this->leaveType, $leaveTypes)) {
                            $query->whereJsonContains('leave_policy_id', [['leave_name' => $leaveTypes[$this->leaveType]]]);
                        }
                    })
                    ->when($this->employeeType, function ($query) {
                        if ($this->employeeType == 'active') {
                            $query->where(function ($query) {
                                $query->where('employee_details.employee_status', 'active')
                                    ->orWhere('employee_details.employee_status', 'on-probation');
                            });
                        } else {
                            $query->where(function ($query) {
                                $query->where('employee_details.employee_status', 'resigned')
                                    ->orWhere('employee_details.employee_status', 'terminated');
                            });
                        }
                    })

                    ->get();
                   

                // Group the data by emp_id
                $grantedData = $query->groupBy('emp_id')->map(function ($group) {
                    return [
                        'emp_id' => $group->first()->emp_id,
                        'first_name' => $group->first()->first_name,
                        'last_name' => $group->first()->last_name,
                        'leave_details' => $group->map(function ($item) {
                            // Decode the JSON to get all leave types
                            $leavePolicy = json_decode($item->leave_policy_id, true);
                            $leaveTypes = [
                                'casual_leave' => 'Casual Leave',
                                'maternity' => 'Maternity Leave',
                                'paternity' => 'Paternity Leave',
                                'sick' => 'Sick Leave',
                                'lop' => 'Loss of Pay',
                                'earned_leave' => 'Earned Leave',
                                'casual_leave_probation' => 'Casul Leave Probation',
                                'marriage_leave' => 'Marriage Leave',
                            ];

                            // Loop through all the leave types in the JSON array
                            $leaveDetails = [];
                            foreach ($leavePolicy as $leave) {
                                if ($this->leaveType && $this->leaveType != 'all' && $leave['leave_name'] !== $leaveTypes[$this->leaveType]) {
                                    continue;  // Skip if the leave name doesn't match the selected leave type
                                }
                                $period = $item->period;
                                if (!is_numeric($period) || strlen($period) !== 4) {
                                    Log::error("Invalid period format: " . json_encode($period));
                                    continue;
                                }

                                // Create Carbon instance for the start and end date of the year
                                $startDate = Carbon::createFromFormat('Y', $period)->firstOfYear()->toDateString(); // '2024-01-01'
                                $endDate = Carbon::createFromFormat('Y', $period)->lastOfYear()->toDateString(); // '2024-12-31'
                                $leaveDetails[] = [
                                    'leave_name' => $leave['leave_name'] ?? 'Unknown',
                                    'grant_days' => $leave['grant_days'] ?? 0,
                                    'status' => $item->status,
                                    'created_at' => $item->created_at,
                                    'from_date' => $startDate,  // The start date of the year
                                    'to_date' => $endDate,
                                ];
                            }

                            return $leaveDetails;
                        }),
                    ];
                });




                $query = LeaveRequest::select(
                    DB::raw('DATE(from_date) as date_only'),
                    DB::raw('count(*) as total_requests'),
                    'leave_applications.emp_id',
                    'employee_details.first_name',
                    'employee_details.last_name',
                    'leave_applications.leave_type',
                    'leave_applications.from_date as leave_from_date',
                    'leave_applications.to_date as leave_to_date',
                    'leave_applications.reason',
                    'leave_applications.created_at',
                    'leave_applications.from_session',
                    'leave_applications.to_session',
                    'leave_applications.leave_status'
                )
                    ->join('employee_details', 'leave_applications.emp_id', '=', 'employee_details.emp_id')
                    ->when($this->leaveType && $this->leaveType != 'all', function ($query) {
                        $leaveTypes = [
                            'lop' => 'Loss of Pay',
                            'casual_leave' => 'Casual Leave',
                            'sick' => 'Sick Leave',
                            'petarnity' => 'Paternity Leave',
                            'casual_leave_probation' => 'Casual Leave Probation',
                            'maternity' => 'Maternity Leave',
                            'marriage_leave' => 'Marriage Leave',
                            'earned_leave' => 'Earned Leave',
                        ];

                        if (array_key_exists($this->leaveType, $leaveTypes)) {
                            $query->where('leave_applications.leave_type', $leaveTypes[$this->leaveType]);
                        }
                    })
                    ->where(function ($query) use ($loggedInEmpId) {
                        $query->whereJsonContains('applying_to', [['manager_id' => $loggedInEmpId]])
                            ->orWhereJsonContains('cc_to', [['emp_id' => $loggedInEmpId]]);
                    })
                    ->where(function ($query) {
                        $query->whereBetween('from_date', [$this->fromDate, $this->toDate])
                            ->orWhereBetween('to_date', [$this->fromDate, $this->toDate])
                            ->orWhere(function ($query) {
                                $query->where('from_date', '<=', $this->toDate)
                                    ->where('to_date', '>=', $this->fromDate);
                            });
                    })
                    ->when($this->transactionType && $this->transactionType != 'all', function ($query) {
                        $transactionTypes = [
                            'availed' => 2,
                            'withdrawn' => 4,
                            'rejected' => 3,
                        ];
                        if (array_key_exists($this->transactionType, $transactionTypes)) {
                            $query->where('leave_status', $transactionTypes[$this->transactionType]);
                        }
                    })
                    ->when($this->employeeType, function ($query) {
                        if ($this->employeeType == 'active') {
                            $query->where(function ($query) {
                                $query->where('employee_details.employee_status', 'active')
                                    ->orWhere('employee_details.employee_status', 'on-probation');
                            });
                        } else {
                            $query->where(function ($query) {
                                $query->where('employee_details.employee_status', 'resigned')
                                    ->orWhere('employee_details.employee_status', 'terminated');
                            });
                        }
                    })
                    ->where('leave_status', '!=', 5)
                    ->orderBy('created_at', $this->sortBy === 'oldest_first' ? 'asc' : 'desc')
                    ->groupBy(
                        DB::raw('DATE(from_date)'),
                        'leave_applications.emp_id',
                        'employee_details.first_name',
                        'employee_details.last_name',
                        'leave_applications.leave_type',
                        'leave_applications.from_date',
                        'leave_applications.to_date',
                        'leave_applications.reason',
                        'leave_applications.created_at',
                        'leave_applications.from_session',
                        'leave_applications.to_session',
                        'leave_applications.leave_status'
                    )
                    ->get();

                $leaveTransactionData = $query->groupBy('date_only')->map(function ($group) {
                    return [
                        'date' => Carbon::parse($group->first()->date_only)->format('d M Y'),
                        'emp_id' => $group->first()->emp_id,  // Add emp_id
                        'first_name' => $group->first()->first_name,  // Add first_name
                        'last_name' => $group->first()->last_name,
                        'leave_details' => $group->map(function ($item) {
                            $leaveRequest = new LeaveRequest();
                            $leaveDays = $leaveRequest->calculateLeaveDays(
                                $item->leave_from_date,
                                $item->from_session,
                                $item->leave_to_date,
                                $item->to_session,
                                $item->leave_type
                            );
                            return [
                                'leave_type' => $item->leave_type,
                                'leave_from_date' => $item->leave_from_date,
                                'leave_to_date' => $item->leave_to_date,
                                'reason' => $item->reason,
                                'created_at' => $item->created_at,
                                'leave_days' => $leaveDays,
                                'leave_status' => $item->leave_status,
                            ];
                        }),
                    ];
                });



                $query = EmployeeLeaveBalances::select(
                    'employee_leave_balances.emp_id',
                    'employee_details.first_name',
                    'employee_details.last_name',
                    DB::raw("JSON_EXTRACT(employee_leave_balances.leave_policy_id, '$') AS leave_policy_id"),  // Extract the entire leave policy array
                    'employee_leave_balances.status',
                    'employee_leave_balances.created_at',
                    'employee_leave_balances.period'
                )
                    ->join('employee_details', 'employee_leave_balances.emp_id', '=', 'employee_details.emp_id')
                    ->where(function ($query) use ($loggedInEmpId) {
                        // Fetch team members whose manager is the logged-in employee
                        $query->where('employee_details.manager_id', $loggedInEmpId)
                            ->whereNotIn('employee_details.employee_status', ['terminated', 'resigned']);
                    })
                    ->where('employee_leave_balances.is_lapsed', 1)  // Filter for lapsed leave
                    ->when($this->leaveType && $this->leaveType != 'all', function ($query) {
                        $leaveTypes = [
                            'casual_probation' => 'Casual Leave',
                            'maternity' => 'Maternity Leave',
                            'paternity' => 'Paternity Leave',
                            'sick' => 'Sick Leave',
                            'lop' => 'Loss of Pay',
                            'earned_leave' => 'Earned Leave',
                        ];

                        if (array_key_exists($this->leaveType, $leaveTypes)) {
                            $query->whereJsonContains('leave_policy_id', [['leave_name' => $leaveTypes[$this->leaveType]]]);
                        }
                    })
                    ->when($this->employeeType, function ($query) {
                        if ($this->employeeType == 'active') {
                            $query->where(function ($query) {
                                $query->where('employee_details.employee_status', 'active')
                                    ->orWhere('employee_details.employee_status', 'on-probation');
                            });
                        } else {
                            $query->where(function ($query) {
                                $query->where('employee_details.employee_status', 'resigned')
                                    ->orWhere('employee_details.employee_status', 'terminated');
                            });
                        }
                    })
                    ->get();
                   


                // Check what is being grouped by emp_id


                // Group the data by emp_id
                $lapsedData = $query->groupBy('emp_id')->map(function ($group) {
                    return [
                        'emp_id' => $group->first()->emp_id,
                        'first_name' => $group->first()->first_name,
                        'last_name' => $group->first()->last_name,
                        'leave_details' => $group->map(function ($item) {

                            $period = $item->period;
                            $decemberStart = Carbon::createFromFormat('Y', $period)->month(12)->startOfMonth()->toDateString(); // First day of December
                            $decemberEnd = Carbon::createFromFormat('Y', $period)->month(12)->endOfMonth()->toDateString();   // Last day of December

                            $leaveBalances = LeaveBalances::getLeaveLapsedBalances($item->emp_id, $item->period);



                            // Decode the JSON to get all leave types
                            $leaveDetails1 = json_decode($item->leave_policy_id, true);
                            $leaveDetails = [];
                            $leaveTypes = [
                                'casual_probation' => 'Casual Leave',
                                'maternity' => 'Maternity Leave',
                                'paternity' => 'Paternity Leave',
                                'sick' => 'Sick Leave',
                                'lop' => 'Loss of Pay',
                                'earned_leave' => 'Earned Leave',
                            ];

                            // Loop through all the leave types in the JSON array

                            foreach ($leaveTypes as $key => $leaveName) {

                                if ($this->leaveType && $this->leaveType != 'all' && $leaveName !== $leaveTypes[$this->leaveType]) {
                                    continue;  // Skip if the leave name doesn't match the selected leave type
                                }
                                $balanceKey = $this->normalizeLeaveNameToBalanceKey($leaveName);

                                // This will print the balance key for debugging

                                // Get the balance for this leave type (defaults to 0 if not found)
                                $remainingBalance = $leaveBalances[$balanceKey] ?? 0;


                                // Skip if there's no remaining balance
                                if ($remainingBalance <= 0) {
                                    continue;
                                }

                                // Add formatted leave object to the array with remaining balance (days)
                                $leaveDetails[] = [
                                    'leave_name' => $leaveName,
                                    'grant_days' => $remainingBalance,
                                    'status' => 'Lapsed',
                                    'created_at' => $item->created_at,
                                    'from_date' => $decemberStart,  // The start date of the year
                                    'to_date' => $decemberEnd,      // The end date of the year


                                ];
                            }

                            return $leaveDetails;
                        }),
                    ];
                });




                $leaveTransactions = $leaveTransactionData->concat($lapsedData)->concat($grantedData);
            } else {

                $query = LeaveRequest::select(
                    DB::raw('DATE(from_date) as date_only'),
                    DB::raw('count(*) as total_requests'),
                    'leave_applications.emp_id',
                    'employee_details.first_name',
                    'employee_details.last_name',
                    'leave_applications.leave_type',
                    'leave_applications.from_date as leave_from_date',
                    'leave_applications.to_date as leave_to_date',
                    'leave_applications.reason',
                    'leave_applications.created_at',
                    'leave_applications.from_session',
                    'leave_applications.to_session',
                    'leave_applications.leave_status'
                )
                    ->join('employee_details', 'leave_applications.emp_id', '=', 'employee_details.emp_id')
                    ->when($this->leaveType && $this->leaveType != 'all', function ($query) {
                        $leaveTypes = [
                            'lop' => 'Loss of Pay',
                            'casual_leave' => 'Casual Leave',
                            'sick' => 'Sick Leave',
                            'petarnity' => 'Paternity Leave',
                            'casual_leave_probation' => 'Casual Leave Probation',
                            'maternity' => 'Maternity Leave',
                            'marriage_leave' => 'Marriage Leave',
                            'earned_leave' => 'Earned Leave',
                        ];

                        if (array_key_exists($this->leaveType, $leaveTypes)) {
                            $query->where('leave_applications.leave_type', $leaveTypes[$this->leaveType]);
                        }
                    })
                    ->where(function ($query) use ($loggedInEmpId) {
                        $query->whereJsonContains('applying_to', [['manager_id' => $loggedInEmpId]])
                            ->orWhereJsonContains('cc_to', [['emp_id' => $loggedInEmpId]]);
                    })
                    ->where(function ($query) {
                        $query->whereBetween('from_date', [$this->fromDate, $this->toDate])
                            ->orWhereBetween('to_date', [$this->fromDate, $this->toDate])
                            ->orWhere(function ($query) {
                                $query->where('from_date', '<=', $this->toDate)
                                    ->where('to_date', '>=', $this->fromDate);
                            });
                    })
                    ->when($this->transactionType && $this->transactionType != 'all', function ($query) {
                        $transactionTypes = [
                            'availed' => 2,
                            'withdrawn' => 4,
                            'rejected' => 3,
                        ];
                        if (array_key_exists($this->transactionType, $transactionTypes)) {
                            $query->where('leave_status', $transactionTypes[$this->transactionType]);
                        }
                    })
                    ->when($this->employeeType, function ($query) {
                        if ($this->employeeType == 'active') {
                            $query->where(function ($query) {
                                $query->where('employee_details.employee_status', 'active')
                                    ->orWhere('employee_details.employee_status', 'on-probation');
                            });
                        } else {
                            $query->where(function ($query) {
                                $query->where('employee_details.employee_status', 'resigned')
                                    ->orWhere('employee_details.employee_status', 'terminated');
                            });
                        }
                    })
                    ->where('leave_status', '!=', 5)
                    ->orderBy('created_at', $this->sortBy === 'oldest_first' ? 'asc' : 'desc')
                    ->groupBy(
                        DB::raw('DATE(from_date)'),
                        'leave_applications.emp_id',
                        'employee_details.first_name',
                        'employee_details.last_name',
                        'leave_applications.leave_type',
                        'leave_applications.from_date',
                        'leave_applications.to_date',
                        'leave_applications.reason',
                        'leave_applications.created_at',
                        'leave_applications.from_session',
                        'leave_applications.to_session',
                        'leave_applications.leave_status'
                    )
                    ->get();
   

                // Refactor the data grouping
                $leaveTransactionData = $query->groupBy('date_only')->map(function ($group) {
                    return [
                        'date' => Carbon::parse($group->first()->date_only)->format('d M Y'),
                        'emp_id' => $group->first()->emp_id,  // Add emp_id
                        'first_name' => $group->first()->first_name,  // Add first_name
                        'last_name' => $group->first()->last_name,
                        'leave_details' => $group->map(function ($item) {
                            $leaveRequest = new LeaveRequest();
                            $leaveDays = $leaveRequest->calculateLeaveDays(
                                $item->leave_from_date,
                                $item->from_session,
                                $item->leave_to_date,
                                $item->to_session,
                                $item->leave_type
                            );
                            return [
                                'leave_type' => $item->leave_type,
                                'leave_from_date' => $item->leave_from_date,
                                'leave_to_date' => $item->leave_to_date,
                                'reason' => $item->reason,
                                'created_at' => $item->created_at,
                                'leave_days' => $leaveDays,
                                'leave_status' => $item->leave_status,
                            ];
                        }),
                    ];
                });
            }


            // Load the view and generate the PDF report
            $employeeDetails = EmployeeDetails::where('emp_id', $loggedInEmpId)->first();
            $pdf = Pdf::loadView('leaveTransactionReportPdf', [
                'employeeDetails' => $employeeDetails,
                // 'leaveTransactions' => isset($leaveTransactions) ? $leaveTransactions : ($this->transactionType == 'granted' ? $grantedData : $leaveTransactionData),
                'leaveTransactions' => isset($leaveTransactions)
                    ? $leaveTransactions
                    : ($this->transactionType == 'granted'
                        ? $grantedData
                        : ($this->transactionType == 'lapsed'
                            ? $lapsedData
                            : $leaveTransactionData)
                    ),
                'fromDate' => $this->fromDate,
                'toDate' => $this->toDate,
                'transactionType' => $this->transactionType,
            ]);
            $this->currentSection = '';

            FlashMessageHelper::flashSuccess('Leave Transaction Report Downloaded Successfully!');

            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->stream();
            }, 'leave_transactions_report.pdf');
        } catch (\Exception $e) {
            // // Log the exception message
            // Log::error('An error occurred while generating the leave transaction report.', ['error' => $e->getMessage()]);
            Log::error('An error occurred while generating the leave transaction report.', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(), // Logs the full stack trace
            ]);
            FlashMessageHelper::flashError('An error occurred while generating the report. Please try again.');
        }
    }
    // Inside your ReportManagement class, move the function here.
    public function normalizeLeaveNameToBalanceKey($leaveName)
    {
        $leaveName = str_replace(' ', '', ucwords(strtolower($leaveName))); // Remove spaces and capitalize words
        $balanceKey = lcfirst($leaveName) . 'Balance'; // Ensure the first letter is lowercase and append 'Balance'
        return $balanceKey;
    }



    public function downloadNegativeLeaveBalanceReport()
    {
        $this->validate([
            'toDate' => 'required|date',
        ], [
            'toDate.required' => 'Date is required.',
        ]);
        try {



            $loggedInEmpId = Auth::guard('emp')->user()->emp_id;

            $query = LeaveRequest::select(
                DB::raw('DATE(to_date) as date_only'),
                DB::raw('count(*) as total_requests'),
                'leave_applications.emp_id',
                'employee_details.first_name',
                'employee_details.last_name',
                'employee_details.hire_date',
                'employee_details.job_role',
                'leave_applications.leave_type',
                'leave_applications.from_date as leave_from_date',
                'leave_applications.to_date as leave_to_date',
                'leave_applications.from_session',
                'leave_applications.to_session',
                'leave_applications.leave_status'
            )
                ->join('employee_details', 'leave_applications.emp_id', '=', 'employee_details.emp_id')
                ->when($this->leaveType && $this->leaveType != 'all', function ($query) {

                    $leaveTypes = [
                        'lop' => 'Loss of Pay',
                        'casual_leave' => 'Casual Leave',
                        'sick' => 'Sick Leave',
                        'petarnity' => 'Petarnity Leave',
                        'casual_leave_probation' => 'Casual Leave Probation',
                        'maternity' => 'Maternity Leave',
                        'marriage_leave' => 'Marriage Leave',
                    ];

                    if (array_key_exists($this->leaveType, $leaveTypes)) {
                        $query->where('leave_applications.leave_type', $leaveTypes[$this->leaveType]);
                    }
                })
                ->where(function ($query) use ($loggedInEmpId) {
                    $query->whereJsonContains('applying_to', [['manager_id' => $loggedInEmpId]])
                        ->orWhereJsonContains('cc_to', [['emp_id' => $loggedInEmpId]]);
                })
                ->where('to_date', '<=', $this->toDate)
                ->where('leave_applications.leave_status', 2);




            if ($this->employeeType == 'active') {
                $query->where(function ($query) {
                    $query->where('employee_details.employee_status', 'active')
                        ->orWhere('employee_details.employee_status', 'on-probation');
                });
            } else {
                $query->where(function ($query) {
                    $query->where('employee_details.employee_status', 'resigned')
                        ->orWhere('employee_details.employee_status', 'terminated');
                });
            }



            $query = $query->groupBy(
                'date_only',
                'leave_applications.emp_id',
                'employee_details.first_name',
                'employee_details.last_name',
                'employee_details.hire_date',
                'employee_details.job_role',
                'leave_applications.leave_type',
                'leave_applications.to_date',
                'leave_applications.from_date',
                'leave_applications.from_session',
                'leave_applications.to_session',
                'leave_applications.leave_status',
            )
                ->orderBy('date_only', 'asc')
                ->get();







            // Aggregate data by date
            $aggregatedData = $query->groupBy('date_only')->map(function ($group) {
                return [
                    'date' => Carbon::parse($group->first()->date_only)->format('d M Y'),
                    'details' => $group->map(function ($item) {
                        $leaveRequest = new LeaveRequest();
                        $leaveDays = $leaveRequest->calculateLeaveDays(
                            $item->leave_from_date,
                            $item->from_session,
                            $item->leave_to_date,
                            $item->to_session,
                            $item->leave_type
                        );
                        return [
                            'emp_id' => $item->emp_id,
                            'first_name' => $item->first_name,
                            'last_name' => $item->last_name,
                            'hire_date' => $item->hire_date,
                            'job_role' => $item->job_role,
                            'leave_type' => $item->leave_type,
                            'leave_from_date' => $item->leave_from_date,
                            'leave_to_date' => $item->leave_to_date,
                            'from_session' => $item->from_session,
                            'to_session' => $item->to_session,
                            'leave_days' => $leaveDays,
                            'leave_status' => $item->status,
                        ];
                    })
                ];
            });




            $employeeDetails = EmployeeDetails::where('emp_id', $loggedInEmpId)->first();
            $pdf = Pdf::loadView('negativeLeaveBalanceReportPdf', [
                'employeeDetails' => $employeeDetails,
                'leaveTransactions' => $aggregatedData,
                'fromDate' => $this->fromDate,
                'toDate' => $this->toDate,
            ]);
            $this->currentSection = '';
            FlashMessageHelper::flashSuccess('Negative Leave Balance Report Downloaded Successfully!');

            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->stream();
            }, 'negative_leave_balance_report.pdf');
        } catch (\Exception $e) {


            // Optionally, return a user-friendly error message
            FlashMessageHelper::flashError('An error occurred while generating the report. Please try again.');
        }
    }
    public $totalCasualDays;
    public $totalSickDays;
    public $totalLossOfPayDays;
    public $totalCasualLeaveProbationDays;
    public $sickLeaveBalance;
    public $casualLeaveBalance;
    public $casualProbationLeaveBalance;
    public $lossOfPayBalance;
    public $currentYear;
    public $selectedYear;
    public function render()
    {

        $loggedInEmpId = Auth::guard('emp')->user()->emp_id;



        $search = '';
        if ($this->searching == 1) {
            $this->employees = EmployeeDetails::where('manager_id', $loggedInEmpId)
                ->join('emp_parent_details', 'employee_details.emp_id', '=', 'emp_parent_details.emp_id')
                ->select('employee_details.*', 'emp_parent_details.*')
                ->where(function ($query) use ($search) {
                    $query->where('employee_details.first_name', 'like', '%' . $search . '%')
                        ->orWhere('employee_details.last_name', 'like', '%' . $search . '%')
                        ->orWhere('emp_parent_details.mother_occupation', 'like', '%' . $search . '%')
                        ->orWhere('emp_parent_details.father_occupation', 'like', '%' . $search . '%');
                })
                ->get();
        } else {
            $this->employees = EmployeeDetails::where('manager_id', $loggedInEmpId)
                ->join('emp_parent_details', 'employee_details.emp_id', '=', 'emp_parent_details.emp_id')
                ->select('employee_details.*', 'emp_parent_details.*')
                ->get();
        }

        // For Leave Balance On Day


        $this->employees = EmployeeDetails::where('manager_id', $loggedInEmpId)->select('emp_id', 'first_name', 'last_name','employee_status')->get();
        if ($this->searching == 1) {
            $nameFilter = $this->search; // Assuming $this->search contains the name filter
            $this->filteredEmployees = $this->employees->filter(function ($employee) use ($nameFilter) {
                return stripos($employee->first_name, $nameFilter) !== false ||
                    stripos($employee->last_name, $nameFilter) !== false ||
                    stripos($employee->emp_id, $nameFilter) !== false ||
                    stripos($employee->job_title, $nameFilter) !== false ||
                    stripos($employee->city, $nameFilter) !== false ||
                    stripos($employee->state, $nameFilter) !== false;
            });



            if ($this->filteredEmployees->isEmpty()) {
                $this->notFound = true; // Set a flag indicating that the name was not found
            } else {
                $this->notFound = false;
            }
        } else {
            $this->filteredEmployees = $this->employees;
        }

        return view('livewire.report-management', [
            'employeetype' => $this->employeeType,
        ]);
    }
    public static function getLeaveBalances($employeeId, $selectedYear)
    {
        try {

            $selectedParticularYear = Carbon::parse($selectedYear)->year;

            $employeeDetails = EmployeeDetails::where('emp_id', $employeeId)->first();
            $sickLeavePerYear = EmployeeLeaveBalances::getLeaveBalancePerYear($employeeId, 'Sick Leave', $selectedParticularYear);
            $casualLeavePerYear = EmployeeLeaveBalances::getLeaveBalancePerYear($employeeId, 'Casual Leave', $selectedParticularYear);
            $earnedLeavesPeryear = EmployeeLeaveBalances::getLeaveBalancePerYear($employeeId, 'Earned Leave', $selectedParticularYear);
            $casualProbationLeavePerYear = EmployeeLeaveBalances::getLeaveBalancePerYear($employeeId, 'Casual Leave Probation', $selectedParticularYear);
            $lossOfPayPerYear = EmployeeLeaveBalances::getLeaveBalancePerYear($employeeId, 'Loss Of Pay', $selectedParticularYear);
            $marriageLeaves = EmployeeLeaveBalances::getLeaveBalancePerYear($employeeId, 'Marriage Leave', $selectedYear);
            $maternityLeaves = EmployeeLeaveBalances::getLeaveBalancePerYear($employeeId, 'Maternity Leave', $selectedYear);
            $paternityLeaves = EmployeeLeaveBalances::getLeaveBalancePerYear($employeeId, 'Petarnity Leave', $selectedYear);
            if (!$employeeDetails) {
                return null;
            }

            // Get the logged-in employee's approved leave days for all leave types
            $approvedLeaveDays = LeaveHelper::getApprovedLeaveDaysOnSelectedDay($employeeId, $selectedYear);

            $toggleLapsedData = EmployeeLeaveBalances::where('emp_id', $employeeId)
                ->where('is_lapsed', true)
                ->where('period', 'like', "%$selectedYear%")
                ->first();
            if ($toggleLapsedData && $toggleLapsedData->is_lapsed) {
                // If lapsed, set the balance directly to leavePerYear
                $sickLeaveBalance = 0;
                $casualLeaveBalance = 0;
                $casualProbationLeaveBalance = 0;
                $marriageLeaveBalance = 0;
                $maternityLeaveBalance = 0;
                $paternityLeaveBalance = 0;
                $earnedLeaveBalance = 0;
                $lossOfPayBalance = $lossOfPayPerYear - $approvedLeaveDays['totalLossOfPayDays'];
            } else {
                // Calculate leave balances
                $sickLeaveBalance = $sickLeavePerYear - $approvedLeaveDays['totalSickDays'];
                $casualLeaveBalance = $casualLeavePerYear - $approvedLeaveDays['totalCasualDays'];
                $lossOfPayBalance = $approvedLeaveDays['totalLossOfPayDays'];
                $casualProbationLeaveBalance = $casualProbationLeavePerYear - $approvedLeaveDays['totalCasualLeaveProbationDays'];
                $marriageLeaveBalance = $marriageLeaves - $approvedLeaveDays['totalMarriageDays'];
                $maternityLeaveBalance = $maternityLeaves - $approvedLeaveDays['totalMaternityDays'];
                $paternityLeaveBalance = $paternityLeaves - $approvedLeaveDays['totalPaternityDays'];
                $earnedLeaveBalance =  $earnedLeavesPeryear - $approvedLeaveDays['totalEarnedDays'];
            }
            return [
                'sickLeaveBalance' => $sickLeaveBalance,
                'casualLeaveBalance' => $casualLeaveBalance,
                'lossOfPayBalance' => $lossOfPayBalance,
                'casualProbationLeaveBalance' => $casualProbationLeaveBalance,
                'marriageLeaveBalance' => $marriageLeaveBalance,
                'maternityLeaveBalance' => $maternityLeaveBalance,
                'paternityLeaveBalance' => $paternityLeaveBalance,
                'earnedLeaveBalance' => $earnedLeaveBalance
            ];
        } catch (\Exception $e) {
            if ($e instanceof \Illuminate\Database\QueryException) {
                // Handle database query exceptions
                Log::error("Database error in getLeaveBalances(): " . $e->getMessage());
                session()->flash('emp_error', 'Database connection error occurred. Please try again later.');
            } else {
                Log::error("Error in getLeaveBalances(): " . $e->getMessage());
                session()->flash('emp_error', 'Failed to retrieve leave balances. Please try again later.');
            }
            return null;
        }
    }
}