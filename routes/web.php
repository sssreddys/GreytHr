<?php

use App\Livewire\Activities;
use App\Livewire\ApprovedDetails;
use App\Livewire\AddEmployeeDetails;
use App\Livewire\AddHolidayList;
use App\Livewire\ReviewClosedRegularisation;
use App\Livewire\UpdateEmployeeDetails;
use App\Livewire\Delegates;
use App\Livewire\EmpLogin;
use App\Livewire\EmployeesReview;
use App\Livewire\Everyone;
use App\Livewire\Feeds;
use App\Livewire\Catalog;

use App\Http\Controllers\GoogleDriveController;
use App\Livewire\Attendance;
use App\Livewire\AuthChecking;
use App\Livewire\GoogleLogins;
use App\Livewire\LeaveCalender;
use App\Livewire\LeaveHistory;
use App\Livewire\LeavePending;
use App\Livewire\Payslip;
use App\Livewire\Regularisation;

use App\Livewire\RegularisationPending;
use App\Livewire\EmployeeSwipes;
use App\Livewire\AttendanceMusterData;
use App\Livewire\AttendanceMuster;
use App\Livewire\AttendenceMasterDataNew;
use App\Livewire\Chat\Chat;
use App\Livewire\Chat\Index;
use App\Livewire\EmployeeSwipesData;
use App\Livewire\HelpDesk;
use App\Livewire\Home;
use App\Livewire\Peoples;
use App\Livewire\ProfileInfo;
use App\Livewire\ReviewLeave;
use App\Livewire\ReviewRegularizations;
use App\Livewire\SalaryRevisions;
use App\Livewire\Settings;
use App\Livewire\Review;
use App\Livewire\Tasks;
// use App\Livewire\Loan;
use App\Livewire\Itdeclaration;
use App\Livewire\Itstatement1;
use App\Livewire\Payroll;
use App\Livewire\SalarySlips;
use App\Livewire\PlanA;
use App\Livewire\Documents;
use App\Livewire\Declaration;
use App\Livewire\DocForms;
use App\Livewire\Downloadform;
use App\Livewire\Documentcenter;
use App\Livewire\DocumentCenterLetters;
use App\Livewire\EmpList;
use App\Livewire\Investment;
use App\Livewire\LeaveApply;
use App\Livewire\LeavePage;

// use App\Livewire\SalaryRevisions;
use App\Livewire\Reimbursement;
use App\Livewire\LeaveBalances;
use App\Livewire\WhoIsInChart;
use App\Livewire\LeaveCancel;
use App\Livewire\TeamOnLeave;
use App\Livewire\HolidayCalender;
use App\Livewire\HomeDashboard;
use App\Livewire\LeaveBalanaceAsOnADay;
use App\Livewire\LetterRequests;
use App\Livewire\TeamOnLeaveChart;
use App\Livewire\Sickleavebalance;
use App\Livewire\CasualLeaveBalance;
use App\Livewire\CasualProbationLeaveBalance;

use App\Livewire\ViewDetails;
use App\Livewire\ViewDetails1;
use App\Livewire\ListOfAppliedJobs;
use App\Livewire\RegularisationHistory;
use App\Livewire\HrAttendanceOverviewNew;
use App\Livewire\WhoisinChartHr;
use App\Livewire\TeamOnAttendance;
use App\Livewire\TeamOnAttendanceChart;
use App\Livewire\ViewPendingDetails;
use App\Livewire\Emojies;
use App\Livewire\Employee;
use App\Livewire\EmployeeAssetsDetails;
use App\Livewire\EmpTimeSheet;
use App\Livewire\GrantLeaveBalance;
use App\Livewire\ImageUpload;
use App\Livewire\ItDashboardPage;
use App\Livewire\LeaveBalancesChart;
use App\Livewire\OrganisationChart;
use App\Livewire\ReviewPendingRegularisation;
use App\Livewire\ShiftRoaster;
use Illuminate\Support\Facades\Route;



Route::group(['middleware' => 'checkAuth'], function () {

    Route::get('/emplogin', EmpLogin::class)->name('emplogin');
    Route::get('/CompanyLogin', function () {
        return view('company_login_view');
    });


    Route::get('/login', [GoogleLogins::class, 'redirectToGoogle'])->name('login');
    Route::get('/auth/google/callback', [GoogleLogins::class, 'handleGoogleCallback'])->name('auth/google/callback');
    Route::get('/Jobs', function () {
        return view('jobs_view');
    });
    Route::get('/CreateCV', function () {
        return view('create_cv_view');
    });
});

Route::get('/Login&Register', function () {
    return view('login_and_register_view');
});

Route::middleware(['auth:web','handleSession'])->group(function () {
    Route::get('/CreateCV', function () {
        return view('create_cv_view');
    });
    Route::get('/Jobs', function () {
        return view('jobs_view');
    });


    Route::get('/AllNotifications', function () {
        return view('all-notifications_view');
    });
    Route::get('/NotificationList{jobId}', function ($jobId) {
        return view('notification_list_view', compact('jobId'));
    })->name('job-interview-details');

    Route::get('/UserProfile', function () {
        return view('user_profile_view');
    });
    Route::get('/full-job-view/{jobId}', function ($jobId) {
        return view('full_job_details_view', compact('jobId'));
    })->name('full-job-view');

    Route::get('/AppliedJobs', function () {
        return view('applied_jobs_view');
    });
    Route::get('/list-of-applied-jobs', ListOfAppliedJobs::class)->name('list-of-applied-jobs');
    Route::get('/Companies', function () {
        return view('companies_view');
    });
    Route::get('/company-based-jobs/{companyId}', function ($companyId) {
        return view('company_based_jobs_view', compact('companyId'));
    })->name('company-based-jobs');
    Route::get('/VendorScreen', function () {
        return view('vendor_screen_view');
    });
});



Route::middleware(['auth:com','handleSession'])->group(function () {
    Route::get('/PostJobs', function () {
        return view('post_jobs_view');
    });


    Route::get('/VendorsSubmittedCVs', function () {
        return view('vendors-submitted-cvs');
    });
    Route::get('/JobSeekersAppliedJobs', function () {
        return view('job-seekers-applied-jobs');
    });

    Route::get('/empregister', function () {
        return view('emp-register-view');
    });
    // Route::get('/emplist', EmpList::class)->name('emplist');
    Route::get('/emplist', function () {
        return view('emp-list-view');
    });

    Route::get('/emp-update/{empId}', function ($empId) {
        return view('emp-update-view', compact('empId'));
    })->name('emp-update');
});

Route::middleware(['auth:hr','handleSession'])->group(function () {
    Route::get('/hrPage', AuthChecking::class)->name('hrPage');
    Route::get('/home-dashboard', HomeDashboard::class)->name('admin-home');
    Route::get('/letter-requests', LetterRequests::class)->name('letter-requests');
    Route::get('/add-employee-details/{employee?}', AddEmployeeDetails::class)->name('add-employee-details');
    Route::get('/update-employee-details', UpdateEmployeeDetails::class)->name('update-employee-details');
    Route::get('/whoisinhrchart', WhoisinChartHr::class)->name('whoisinhrchart');
    // Route::get('/hrleaveOverview', HrLeaveOverview::class)->name('hrleaveOverview');
    Route::get('/hrAttendanceOverview', HrAttendanceOverviewNew::class)->name('hrAttendanceOverview');
    Route::get('/addLeaves', GrantLeaveBalance::class)->name('leave-grant');
    // Route::get('/add-holiday-list', AddHolidayList::class)->name('holiday-list');
    // Route::get('/linechart', LineChart::class)->name('linechart');
});

Route::middleware(['auth:finance','handleSession'])->group(function () {
    Route::get('/financePage', AuthChecking::class)->name('financePage');
});

Route::middleware(['auth:it','handleSession'])->group(function () {
    Route::get('/itPage', AuthChecking::class)->name('IT-requests');
    Route::get('/emp-assets-details', EmployeeAssetsDetails::class)->name('employee-asset-details');
    Route::get('/ithomepage', ItDashboardPage::class)->name('ithomepage');
});

Route::middleware(['auth:admins','handleSession'])->group(function () {
    Route::get('/adminPage', AuthChecking::class)->name('auth-checking');

});


Route::middleware(['auth:emp','handleSession'])->group(function () {
    Route::get('/google-redirect', [GoogleDriveController::class, 'auth'])
        ->name('google-redirect');
    Route::get('/google-callback', [GoogleDriveController::class, 'callback'])
        ->name('google-callback');

    Route::get('/', Home::class)->name('home');
    Route::get('/doc-forms', DocForms::class);
    Route::get('/LeaveBalanceAsOnADay', LeaveBalanaceAsOnADay::class);

    // Attendance Routes
    Route::get('/Attendance', Attendance::class)->name('Attendance info');
    Route::get('/whoisinchart', WhoIsInChart::class)->name('whoisin');
    Route::get('/regularisation', Regularisation::class)->name('regularisation');
    Route::get('/regularisation-pending/{id}', RegularisationPending::class)->name('regularisation-pending');
    Route::get('/regularisation-history/{id}', RegularisationHistory::class)->name('regularisation-history');
    Route::get('/employee-swipes', EmployeeSwipes::class)->name('employee-swipes');
    Route::get('/employee-swipes-data', EmployeeSwipesData::class)->name('employee-swipes-data');
    Route::get('/attendance-muster', AttendanceMuster::class)->name('attendance-muster');
    Route::get('/attendance-muster-data', AttendenceMasterDataNew::class)->name('attendance-muster-data');
    Route::get('/shift-roaster-data', ShiftRoaster::class)->name('shift-roaster-data');
    Route::get('/ProfileInfo', ProfileInfo::class)->name('profile.info');
    Route::get('/Settings', Settings::class)->name('settings');
    Route::get('/review-pending-regularation/{id}', ReviewPendingRegularisation::class)->name('review-pending-regularation');
    Route::get('/review-closed-regularation/{id}', ReviewClosedRegularisation::class)->name('review-closed-regularation');
    Route::get('/timesheet-page', EmpTimeSheet::class)->name('time-sheet');


    //Feeds Module
    Route::get('/Feeds', Feeds::class)->name('feeds');
    Route::get('/events', Activities::class);
    Route::get('/everyone', Everyone::class);

    //People module
    Route::get('/PeoplesList', Peoples::class)->name('people');


    //Helpdesk module

    Route::get('/HelpDesk', HelpDesk::class)->name('help-desk');

    Route::get('/catalog', Catalog::class);

    // Related salary module and ITdeclaration Document center
    Route::get('/payslip', Payroll::class);
    Route::get('/slip', SalarySlips::class)->name('payslips');
    Route::get('/itdeclaration', Itdeclaration::class)->name('itdeclaration');
    Route::get('/itstatement', Itstatement1::class)->name('IT-Statement');
    Route::get('/plan-A', PlanA::class)->name('plan-a');
    Route::get('/document-center-letters', DocumentCenterLetters::class);
    Route::get('/delegates', Delegates::class)->name('work-flow-delegates');
    Route::get('/salary-revision', SalaryRevisions::class)->name('salary-revision');
    Route::get('/plan-C', PlanA::class)->name('plan-a');
    Route::get('/formdeclaration', Declaration::class)->name('IT-Declaration');
    Route::get('/document', Documentcenter::class)->name('Document-center');
    Route::get('/reimbursement', Reimbursement::class)->name('reimbursement');
    Route::get('/investment', Investment::class)->name('proof-of-investment');
    Route::get('/documents', Documents::class);


    //leave module
    Route::get('/leave-page', LeavePage::class)->name('leave-page');
    Route::get('/approved-details/{leaveRequestId}', ApprovedDetails::class)->name('approved-details');
    Route::get('/view-details/{leaveRequestId}', ViewDetails::class)->name('view-details');
    Route::get('/leave-apply', LeaveApply::class);
    Route::get('/holiday-calender', HolidayCalender::class)->name('holiday-calendar');
    Route::get('/leave-balances', LeaveBalances::class)->name('leave-balance');
    Route::get('/casualleavebalance',CasualLeaveBalance::class)->name('casualleavebalance');
    Route::get('/casualprobationleavebalance',CasualProbationLeaveBalance::class)->name('casualprobationleavebalance');
    Route::get('/leave-cancel', LeaveCancel::class)->name('leave-cancel');
    Route::get('/leave-calender', LeaveCalender::class)->name('leave-calendar');
    Route::get('/leave-history/{leaveRequestId}', LeaveHistory::class)->name('leave-history');
    Route::get('/leave-pending/{leaveRequestId}', LeavePending::class)->name('leave-pending');
    Route::get('/team-on-leave', TeamOnLeave::class);
    Route::get('/team-on-leave-chart', TeamOnLeaveChart::class)->name('team-on-leave');
    // Route::get('/leaveBalChart', LeaveBalancesChart::class)->name('leave-details');
    Route::get('/navigate-to-helpdesk', [EmployeesReview::class, 'navigateToHelpdesk'])->name('navigate.to.helpdesk');

    // TODO module
    Route::get('/tasks', Tasks::class)->name('tasks');
    Route::get('/employees-review', EmployeesReview::class)->name('review');
    Route::get('/review-regularizations', ReviewRegularizations::class)->name('regularizations');

    // ####################################### Chat Module Routes #########################endregion
    Route::get('/chat',Index::class)->name('chat.index');
    Route::get('/chat/{query}',Chat::class)->name('chat');
    Route::get('/e',Employee::class)->name('employee');
    Route::get('/image',ImageUpload::class)->name('image');
    //*******************************************  End Of Chat Module Routes *************************/
});





Route::get('/itform', function () {
    return view('itform');
});
//Download routes
Route::get('/your-download-route', function () {
    return view('download-pdf');
});
Route::get('/downloadform', function () {
    return view('downloadform');
});
