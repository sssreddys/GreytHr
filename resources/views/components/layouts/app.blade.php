<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @guest
        <link rel="icon" type="image/x-icon" href="{{ asset('public/images/hr_expert.png') }}">
        <title>
            HR Strategies Pro
        </title>
    @endguest

    @auth('emp')
        @php
            $employeeId = auth()->guard('emp')->user()->emp_id;
            $employee = DB::table('employee_details')
                ->join('companies', 'employee_details.company_id', '=', 'companies.company_id')
                ->where('employee_details.emp_id', $employeeId)
                ->select('companies.company_logo', 'companies.company_name')
                ->first();
            $mangerid = DB::table('employee_details')
                ->join('companies', 'employee_details.company_id', '=', 'companies.company_id')
                ->where('employee_details.manager_id', $employeeId)
                ->select('companies.company_logo', 'companies.company_name')
                ->first();
        @endphp
        <link rel="icon" type="image/x-icon" href="{{ asset($employee->company_logo) }}">
        <title>
            {{ $employee->company_name }}
        </title>
    @endauth

    <livewire:styles />

    @vite(['public/css/app.css', 'public/js/app.js'])


    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.x.x/dist/alpine.js" defer></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

    <!-- Font Family -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">


    <!-- DateRangePicker CSS and JS -->
    <link rel="stylesheet" type="text/css"
        href="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.css" />
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.min.js"></script>
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

    <!-- npm Charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/app.js') }}" defer data-turbolinks-track="reload"></script>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v0.x.x/dist/livewire-sortable.js"></script>

    <script src="{{ asset('vendor/livewire/livewire.js') }}"></script>

    <!-- Add these links to your HTML -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>

    <!-- Hierarchy Select Js -->
    <!-- <script src="js/hierarchy-select.min.js"></script> -->

    <!-- Semantic UI CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.4/semantic.min.css">
    <!-- Semantic UI JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.4/semantic.min.js"></script>

    <!-- Hierarchy Select CSS -->
    <!-- <link rel="stylesheet" href="css/hierarchy-swelect.min.css"> -->
    <!-- Emoji links -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.2/emojionearea.min.css" />
    <script src="https://twemoji.maxcdn.com/v/latest/twemoji.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/emojionearea/3.4.2/emojionearea.min.js"></script>

    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="{{ asset('livewire/livewire.js') }}" defer></script>

    <style>
        @import url('/public/app.css');
        .marginlefting {
            margin-left: 60px !important;
        }

        .displaynone {
            display: none !important;
        }


        .displayblock {
            display: block !important;
        }

        .item {
            margin-bottom: 20px !important;
            margin-top: 10px !important;
            font-size: 12px;
            padding: 10px;
            font-weight: 500 !important;
            color: #778899 !important;
        }

        .active {
            /* Define your active color here */
            color: rgb(2, 17, 79) !important;
            /* Example color */
            /* Add any other styles you want for the active state */
        }

        .sidebar .item i {
            font-size: 14px;
            margin-top: -5px !important;
        }

        .logo {
            height: 58px !important;
            padding: 10px !important;
        }

        .logo img {
            width: 9em !important;
            height: 38px !important;
        }

        .title.item {
            padding: .92857143em 1.14285714em !important;
        }

        .dropdown .menu .header {
            padding-top: 3.9px !important;
            padding-bottom: 3.9px !important;
        }

        .ui.left.sidebar,
        .ui.right.sidebar {
            width: 210px;
        }

        .ui.vertical.menu .item:before {
            height: 0px !important;
        }

        .menu-item-label.active {
            background-color: #fff;
            margin: 0px 3px;
            padding: 0 3px;
            color: #000 !important;
            border-radius: 5px;
        }

        .menu-item-label.active:hover {
            background-color: #fff;
            margin: 0px 3px;
            padding: 0 3px;
            border-radius: 5px;
        }

        .menu-item-label {
            cursor: pointer;
            color: #fff !important;
            margin: 3px;
        }

        .menu-item-label:hover {
            background: white;
            color: black !important;
            border-radius: 5px;
        }

        /* .content {
            margin-left: 20px;
            flex: 1;
        } */

        .content-item {
            display: none;
        }

        .ui.vertical.menu .active.item {
            background-color: none !important;
            padding: 5px 2px !important;
            border-radius: 5px !important;
        }

        .content-item.active {
            display: block;
            padding-left: 10px;
        }

    </style>

    @livewireScripts

    @stack('styles')
</head>

@guest
    <livewire:emplogin />
@else

    <body>

        <div>

            <div class="row m-0 p-0 " style="height: 100vh;background:#f5f5f5;">

            <div class="ui sidebar vertical left menu overlay visible p-0" style="-webkit-transition-duration: 0.1s; overflow: visible !important;">
                <div class="item logos text-center">
                    @livewire('company-logo')
                    <!-- <img src="https://image.flaticon.com/icons/svg/866/866218.svg" /><img src="https://image.flaticon.com/icons/svg/866/866218.svg" style="display: none" /> -->
                </div>

                <div class="row m-0">
                    <div class="col-3 p-0 py-1" style="min-height: 100vh; background: rgb(2,17,79);border-top-right-radius: 5px;">
                        <div class="primarySideBar">
                            @auth('emp')
                                <div class="text-center menu-item-label active" data-target="#home" title="Home">
                                    <i class="fas fa-home" style="padding: 15px 0px;" ></i>
                                </div>
                                <div class="text-center menu-item-label" data-target="#feeds" title="Feeds">
                                    <i class="fas fa-rss" style="padding: 15px 0px;" ></i>
                                </div>
                                <div class="text-center menu-item-label" data-target="#people" title="People">
                                    <i class="fas fa-users" style="padding: 15px 0px;" ></i>
                                </div>
                                <div class="text-center menu-item-label" data-target="#todo" title="To do">
                                    <i class="fas fa-file-alt" style="padding: 15px 0px;" ></i>
                                </div>
                                <div class="text-center menu-item-label" data-target="#salary" title="Salary">
                                    <i class="fas fa-solid fa-money-bill-transfer" style="padding: 15px 0px;" ></i>
                                </div>
                                <div class="text-center menu-item-label" data-target="#leave" title="Leave">
                                    <i class="fas fa-file-alt" style="padding: 15px 0px;" ></i>
                                </div>
                                <div class="text-center menu-item-label" data-target="#attendance" title="Attendance">
                                    <i class="fas fa-clock" style="padding: 15px 0px;" ></i>
                                </div>
                                <div class="text-center menu-item-label" data-target="#document-center" title="Document Center">
                                    <i class="fas fa-folder" style="padding: 15px 0px;" ></i>
                                </div>
                                <div class="text-center menu-item-label" data-target="#helpdesk" title="Heldesk">
                                    <i class="fas fa-headset" style="padding: 15px 0px;" ></i>
                                </div>
                                <div class="text-center menu-item-label" data-target="#workflow-delegates" title="Workflow-Delegates">
                                    <i class="fas fa-user-friends" style="padding: 15px 0px;" ></i>
                                </div>
                                <div class="text-center menu-item-label" data-target="#timesheet" title="Time Sheet">
                                    <i class="fas fa-calendar-alt" style="padding: 15px 0px;" ></i>
                                </div>
                            @endauth

                            @auth('hr')
                                <div class="text-center menu-item-label active" data-target="#home-dashboard" title="Home">
                                    <i class="fas fa-home" style="padding: 15px 0px;" ></i>
                                </div>
                                <div class="text-center menu-item-label" data-target="#hr-request" title="HR Requests">
                                    <i class="fas fa-laptop" style="padding: 15px 0px;" ></i>
                                </div>
                                <div class="text-center menu-item-label" data-target="#grant-leaves" title="Grant Leaves">
                                    <i class="fas fa-envelope" style="padding: 15px 0px;" ></i>
                                </div>
                                <div class="text-center menu-item-label" data-target="#letter-request" title="Letter Request">
                                    <i class="fas fa-envelope" style="padding: 15px 0px;" ></i>
                                </div>
                                <div class="text-center menu-item-label" data-target="#add-holiday-list" title="Add Holidays">
                                    <i class="fas fa-envelope" style="padding: 15px 0px;" ></i>
                                </div>
                            @endauth

                            @auth('it')
                                <div class="text-center menu-item-label active" data-target="#it-request">
                                    <i class="fas fa-laptop" style="padding: 15px 0px;" ></i>
                                </div>
                            @endauth

                            @auth('finance')
                                <div class="text-center menu-item-label active" data-target="#finance-request">
                                    <i class="fas fa-dollar-sign" style="padding: 15px 0px;" ></i>
                                </div>
                            @endauth
                        </div>
                    </div>
                    <div class="col-9 p-0">
                        @auth('emp')
                        <div class="ui accordion scrollable-container content-item active" id="home" style="border: none">
                            <div class="sidebar-heading pb-2 pt-2 text-start"><p class="fw-bold">Home</p></div>
                            <a class="item p-0 m-0" href="/" onclick="setActiveLink(this)">Home</a>
                        </div>

                        <div class="ui accordion scrollable-container content-item" id="feeds" style="border: none">
                            <div class="sidebar-heading pb-2 pt-2 text-start"><p class="fw-bold">Feeds</p></div>
                            <a class="item p-0 m-0" href="/Feeds" onclick="setActiveLink(this)">Feeds</a>
                        </div>
                        <div class="ui accordion scrollable-container content-item" id="people" style="border: none">
                            <div class="sidebar-heading pb-2 pt-2 text-start"><p class="fw-bold">People</p></div>
                            <a class="item p-0 m-0" href="/PeoplesList" onclick="setActiveLink(this)">People</a>
                        </div>
                        <div class="ui accordion scrollable-container content-item" id="todo" style="border: none">
                            <div class="sidebar-heading pb-2 pt-2 text-start"><p class="fw-bold">To do</p></div>
                            <a class="item p-0 m-0" href="/tasks" onclick="setActiveLink(this)">Tasks</a>
                            <a class="item p-0 m-0" href="/employees-review" onclick="setActiveLink(this)">Review</a>
                        </div>
                        <div class="ui accordion scrollable-container content-item" id="salary" style="border: none">
                            <div class="sidebar-heading pb-2 pt-2 text-start"><p class="fw-bold">Salary</p></div>
                            <a class="item p-0 m-0" href="/formdeclaration" onclick="setActiveLink(this)">IT Declaration</a>
                            <a class="item p-0 m-0" href="/itstatement" onclick="setActiveLink(this)">IT Statement</a>
                            <a class="item p-0 m-0" href="/slip" onclick="setActiveLink(this)">Payslips</a>
                            <a class="item p-0 m-0" href="/reimbursement" onclick="setActiveLink(this)">Reimbursement</a>
                            <a class="item p-0 m-0" href="/investment" onclick="setActiveLink(this)">Proof of Investment</a>
                            <a class="item p-0 m-0" href="/salary-revision" onclick="setActiveLink(this)">Salary Revision</a>
                        </div>
                        <div class="ui accordion scrollable-container content-item" id="leave" style="border: none">
                            <div class="sidebar-heading pb-2 pt-2 text-start"><p class="fw-bold">Leave</p></div>
                            <a class="item p-0 m-0" href="/leave-page" onclick="setActiveLink(this)">Leave Apply</a>
                            <a class="item p-0 m-0" href="/leave-balances" onclick="setActiveLink(this)">Leave Balances</a>
                            <a class="item p-0 m-0" href="/leave-calender" onclick="setActiveLink(this)">Leave Calendar</a>
                            <a class="item p-0 m-0" href="/holiday-calender" onclick="setActiveLink(this)">Holiday Calendar</a>
                            @if ($mangerid)
                            <a class="item p-0 m-0" href="/team-on-leave-chart" onclick="setActiveLink(this)">@livewire('team-on-leave')</a>
                            @endif
                        </div>
                        <div class="ui accordion scrollable-container content-item" id="attendance" style="border: none">
                            <div class="sidebar-heading pb-2 pt-2 text-start"><p class="fw-bold">Attendance</p></div>
                            <a class="item p-0 m-0" href="/Attendance" onclick="setActiveLink(this)">Attendance Info</a>
                            @if ($mangerid)
                            <a class="item p-0 m-0" href="/whoisinchart" onclick="setActiveLink(this)">@livewire('whoisin')</a>
                            <a class="item p-0 m-0" href="/employee-swipes-data" onclick="setActiveLink(this)">@livewire('employee-swipes')</a>
                            <a class="item p-0 m-0" href="/attendance-muster-data" onclick="setActiveLink(this)">@livewire('attendance-muster')</a>
                            <a class="item p-0 m-0" href="/shift-roaster-data" onclick="setActiveLink(this)">@livewire('shift-roaster-submodule')</a>
                            @endif
                        </div>
                        <div class="ui accordion scrollable-container content-item" id="document-center" style="border: none">
                            <div class="sidebar-heading pb-2 pt-2 text-start"><p class="fw-bold">Documents</p></div>
                            <a class="item p-0 m-0" href="/document" onclick="setActiveLink(this)">Document Center</a>
                        </div>
                        <div class="ui accordion scrollable-container content-item" id="helpdesk" style="border: none">
                            <div class="sidebar-heading pb-2 pt-2 text-start"><p class="fw-bold">Helpdesk</p></div>
                            <a class="item p-0 m-0" href="/HelpDesk" onclick="setActiveLink(this)">Helpdesk</a>
                        </div>
                        <div class="ui accordion scrollable-container content-item" id="workflow-delegates" style="border: none">
                            <div class="sidebar-heading pb-2 pt-2 text-start"><p class="fw-bold">Workflow</p></div>
                            <a class="item p-0 m-0" href="/delegates" onclick="setActiveLink(this)">Workflow Delegates</a>
                        </div>
                        <div class="ui accordion scrollable-container content-item" id="timesheet" style="border: none">
                            <div class="sidebar-heading pb-2 pt-2 text-start"><p class="fw-bold">Time Sheet</p></div>
                            <a class="item p-0 m-0" href="/timesheet-page" onclick="setActiveLink(this)">Employee Time Sheet</a>
                        </div>
                        @endauth

                        @auth('hr')
                        <div class="ui accordion scrollable-container content-item active" id="home-dashboard" style="border: none">
                            <div class="sidebar-heading pb-2 pt-2 text-start"><p class="fw-bold">Home</p></div>
                            <a class="item p-0 m-0" href="/home-dashboard" onclick="setActiveLink(this)">Home</a>
                        </div>
                        <div class="ui accordion scrollable-container content-item" id="hr-request" style="border: none">
                            <div class="sidebar-heading pb-2 pt-2 text-start"><p class="fw-bold">HR</p></div>
                            <a class="item p-0 m-0" href="/hrPage" onclick="setActiveLink(this)">HR Requests</a>
                        </div>
                        <div class="ui accordion scrollable-container content-item" id="grant-leaves" style="border: none">
                            <div class="sidebar-heading pb-2 pt-2 text-start"><p class="fw-bold">Leaves</p></div>
                            <a class="item p-0 m-0" href="/addLeaves" onclick="setActiveLink(this)">Grant Leaves</a>
                        </div>
                        <div class="ui accordion scrollable-container content-item" id="letter-request" style="border: none">
                            <div class="sidebar-heading pb-2 pt-2 text-start"><p class="fw-bold">Letter</p></div>
                            <a class="item p-0 m-0" href="/letter-requests" onclick="setActiveLink(this)">Letter Requests</a>
                        </div>
                        <div class="ui accordion scrollable-container content-item" id="add-holiday-list" style="border: none">
                            <div class="sidebar-heading pb-2 pt-2 text-start"><p class="fw-bold">Holidays</p></div>
                            <a class="item p-0 m-0" href="/add-holiday-list" onclick="setActiveLink(this)">Add Holidays</a>
                        </div>
                        @endauth

                        @auth('it')
                        <div class="ui accordion scrollable-container content-item active" id="it-request" style="border: none">
                            <div class="sidebar-heading pb-2 pt-2 text-start"><p class="fw-bold">IT</p></div>
                            <a class="item p-0 m-0" href="#" onclick="setActiveLink(this)">IT Requests</a>
                        </div>
                        @endauth

                        @auth('finance')
                        <div class="ui accordion scrollable-container content-item active" id="finance-requestt" style="border: none">
                            <div class="sidebar-heading pb-2 pt-2 text-start"><p class="fw-bold">Finance</p></div>
                            <a class="item p-0 m-0" href="#" onclick="setActiveLink(this)">Finance Requests</a>
                        </div>
                        @endauth
                    </div>
                </div>
                

                @auth('emp')
                <div class="ui dropdown item displaynone">
                    <z>Home</z>
                    <!-- <i class="icon demo-icon heart icon-heart"></i> -->
                    <i class="fas fa-home icon demo-icon"></i>
                
                    <div class="menu">
                        <div class="header">Home</div>
                        <div class="ui divider"></div>
                        <a class="item p-0 m-0" href="/" onclick="setActiveLink(this)">Home</a>
                    </div>
                </div>
                <div class="ui dropdown item displaynone">
                    <z>Feeds</z>
                    <!-- <i class="icon demo-icon heart icon-heart"></i> -->
                    <i class="fas fa-rss icon demo-icon"></i>
                
                    <div class="menu">
                        <div class="header">Feeds</div>
                        <div class="ui divider"></div>
                        <a class="item p-0 m-0" href="/Feeds" onclick="setActiveLink(this)">Feeds</a>
                    </div>
                </div>
                <div class="ui dropdown item displaynone">
                    <z>People</z>
                    <!-- <i class="icon demo-icon heart icon-heart"></i> -->
                    <i class="fas fa-users icon demo-icon"></i>
                
                    <div class="menu">
                        <div class="header">People</div>
                        <div class="ui divider"></div>
                        <a class="item p-0 m-0" href="/PeoplesList" onclick="setActiveLink(this)">People</a>
                    </div>
                </div>

                <div class="ui dropdown item displaynone">
                    <z>Todo</z>
                    <i class="icon demo-icon fas fa-file-alt"></i>
                
                    <div class="menu">
                        <div class="header">Todo</div>
                        <div class="ui divider"></div>
                        <a class="item p-0 m-0" href="/tasks" onclick=" setActiveLink(this)">Tasks</a>
                        <a class="item p-0 m-0" href="/employees-review" onclick=" setActiveLink(this)">Review</a>
                    </div>
                </div>

                <div class="ui dropdown item displaynone">
                    <z>Salary</z>
                    <i class="fas fa-solid fa-money-bill-transfer demo-icon "></i>
                
                    <div class="menu">
                        <div class="header">Salary</div>
                        <div class="ui divider"></div>
                        <a class="item item" href="/formdeclaration" onclick=" setActiveLink(this)">IT Declaration</a>
                        <a class="item item" href="/itstatement" onclick=" setActiveLink(this)">IT Statement</a>
                        <a class="item item" href="/slip" onclick=" setActiveLink(this)">Payslips</a>
                        <a class="item item" href="/reimbursement" onclick=" setActiveLink(this)">Reimbursement</a>
                        <a class="item item" href="/investment" onclick=" setActiveLink(this)">Proof of Investment</a>
                        <a class="item item" href="/salary-revision" onclick=" setActiveLink(this)">Salary Revision</a>
                    </div>
                </div>
                <div class="ui dropdown item displaynone">
                    <z>Leave</z>
                    <i class="fas fa-file-alt icon demo-icon "></i>
                
                    <div class="menu">
                        <div class="header">Leave</div>
                        <div class="ui divider"></div>
                        <a class="item p-0 m-0" href="/leave-page" onclick=" setActiveLink(this)">Leave Apply</a>
                        <a class="item p-0 m-0" href="/leave-balances" onclick=" setActiveLink(this)">Leave Balances</a>
                        <a class="item p-0 m-0" href="/leave-calender" onclick=" setActiveLink(this)">Leave Calendar</a>
                        <a class="item p-0 m-0" href="/holiday-calender" onclick=" setActiveLink(this)">Holiday Calendar</a>
                        @if ($mangerid)
                        <a class="item p-0 m-0" href="/team-on-leave-chart" onclick=" setActiveLink(this)">@livewire('team-on-leave')</a>
                        @endif
                    </div>
                </div>
                <div class="ui dropdown item displaynone">
                    <z>Attendance</z>
                    <i class="fas fa-clock icon demo-icon "></i>
                
                    <div class="menu">
                        <div class="header">Attendance</div>
                        <div class="ui divider"></div>
                        <a class="item p-0 m-0" href="/Attendance" onclick="setActiveLink(this)">Attendance Info</a>
                        @if ($mangerid)
                        <a class="item p-0 m-0" href="/whoisinchart" onclick="setActiveLink(this)">@livewire('whoisin')</a>
                        <a class="item p-0 m-0" href="/employee-swipes-data" onclick="setActiveLink(this)">@livewire('employee-swipes')</a>
                        <a class="item p-0 m-0" href="/attendance-muster-data" onclick="setActiveLink(this)">@livewire('attendance-muster')</a>
                        @endif
                    </div>
                </div>

                <div class="ui dropdown item displaynone">
                    <z>Document Center</z>
                    <!-- <i class="icon demo-icon heart icon-heart"></i> -->
                    <i class="fas fa-folder icon demo-icon"></i>
                
                    <div class="menu">
                        <div class="header">Document Center</div>
                        <div class="ui divider"></div>
                        <a class="item p-0 m-0" href="/document" onclick="setActiveLink(this)">Document Center</a>
                    </div>
                </div>
                <div class="ui dropdown item displaynone">
                    <z>Helpdesk</z>
                    <!-- <i class="icon demo-icon heart icon-heart"></i> -->
                    <i class="fas fa-headset demo-icon"></i>
                
                    <div class="menu">
                        <div class="header">Helpdesk</div>
                        <div class="ui divider"></div>
                        <a class="itemp-0 m-0" href="/headset" onclick="setActiveLink(this)">Helpdesk</a>
                    </div>
                </div>
                <div class="ui dropdown item displaynone">
                    <z>Workflow</z>
                    <!-- <i class="icon demo-icon heart icon-heart"></i> -->
                    <i class="fas fa-user-friends demo-icon"></i>
                
                    <div class="menu">
                        <div class="header">Workflow</div>
                        <div class="ui divider"></div>
                        <a class="item p-0 m-0" href="/delegates" onclick="setActiveLink(this)">Workflow Delegates</a>
                    </div>
                </div>
                <div class="ui dropdown item displaynone">
                    <z>Time Sheet</z>
                    <!-- <i class="icon demo-icon heart icon-heart"></i> -->
                    <i class="fas fa-calendar-alt demo-icon"></i>

                    <div class="menu">
                        <div class="header">Time Sheet</div>
                        <div class="ui divider"></div>
                        <a class="itemp-0 m-0" href="/timesheet-page" onclick="setActiveLink(this)">Time Sheet</a>
                    </div>
                </div>
                @endauth

                @auth('hr')
                <div class="ui dropdown item displaynone">
                    <z>Home</z>
                    <!-- <i class="icon demo-icon heart icon-heart"></i> -->
                    <i class="fas fa-home demo-icon"></i>
                
                    <div class="menu">
                        <div class="header">Home</div>
                        <div class="ui divider"></div>
                        <a class="item m-0 p-0" href="/home-dashboard" onclick="setActiveLink(this)">Home</a>
                    </div>
                </div>
                <div class="ui dropdown item displaynone">
                    <z>HR Requests</z>
                    <!-- <i class="icon demo-icon heart icon-heart"></i> -->
                    <i class="fas fa-envelope demo-icon"></i>
                
                    <div class="menu">
                        <div class="header">HR Requests</div>
                        <div class="ui divider"></div>
                        <a class="item p-0 m-0 " href="/hrPage" onclick="setActiveLink(this)">HR Requests</a>
                    </div>
                </div>
                <div class="ui dropdown item displaynone">
                    <z>Grant Leaves</z>
                    <!-- <i class="icon demo-icon heart icon-heart"></i> -->
                    <i class="fas fa-envelope demo-icon"></i>
                
                    <div class="menu">
                        <div class="header">Grant Leaves</div>
                        <div class="ui divider"></div>
                        <a class="item p-0 m-0" href="/addLeaves" onclick="setActiveLink(this)">Grant Leaves</a>
                    </div>
                </div>
                <div class="ui dropdown item displaynone">
                    <z>Letter Requests</z>
                    <!-- <i class="icon demo-icon heart icon-heart"></i> -->
                    <i class="fas fa-envelope demo-icon"></i>
                
                    <div class="menu">
                        <div class="header">Letter Requests</div>
                        <div class="ui divider"></div>
                        <a class="item p-0 m-0" href="/letter-requests" onclick="setActiveLink(this)">Letter Requests</a>
                    </div>
                </div>
                <div class="ui dropdown item displaynone">
                    <z>Add Holidays</z>
                    <!-- <i class="icon demo-icon heart icon-heart"></i> -->
                    <i class="fas fa-envelope demo-icon"></i>
                
                    <div class="menu">
                        <div class="header">Add Holidays</div>
                        <div class="ui divider"></div>
                        <a class="item" href="/add-holiday-list" onclick="setActiveLink(this)">Add Holidays</a>
                    </div>
                </div>
                @endauth
                @auth('it')
                <div class="ui dropdown item displaynone">
                    <z>IT Requests</z>
                    <!-- <i class="icon demo-icon heart icon-heart"></i> -->
                    <i class="fas fa-laptop demo-icon"></i>
                
                    <div class="menu">
                        <div class="header">IT Requests</div>
                        <div class="ui divider"></div>
                        <a class="item" href="#" onclick="setActiveLink(this)">IT Requests</a>
                    </div>
                </div>
                @endauth
                @auth('finance')
                <div class="ui dropdown item displaynone">
                    <z>Finance Requests</z>
                    <!-- <i class="icon demo-icon heart icon-heart"></i> -->
                    <i class="fas fa-dollar-sign demo-icon"></i>
                
                    <div class="menu">
                        <div class="header">Finance Requests</div>
                        <div class="ui divider"></div>
                        <a class="item" href="#" onclick="setActiveLink(this)">Finance Requests</a>
                    </div>
                </div>
                @endauth
            </div>
            <div class="pusher p-0 bg-white">
                <div class="ui menu asd borderless" style="margin-bottom: 0;background: #02114f; border-radius: 0!important; border: 0; margin-left: 210px; -webkit-transition-duration: 0.1s;">
                    <!-- <a class="item hamButton">
                        <i class="icon content text-white"></i>
                    </a> -->
                    <!-- <div class="item">
                        <div class="bg-success ui primary button hamButton">
                            <i class="icon content text-white m-0"></i>
                        </div>
                    </div> -->
                    <a class="item">@livewire('page-title')</a>
                    <div class="right menu">
                        @auth('emp')
                        <!-- <div class="item">
                            <div class="ui primary">
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span>
                                    <input type="text" class="form-control" placeholder="Search..." aria-label="Username" aria-describedby="basic-addon1">
                                </div>
                            </div>
                        </div> -->
                        <div class="item">
                            @php
                            $chatNotificationCount = DB::table('messages')
                            ->where('receiver_id', $employeeId)
                            ->count();
                            @endphp
                            <a href="/users" style="color: white; text-decoration: none;">
                                <i class="fa fa-comment" style="position: relative;display: inline-block; vertical-align: middle;font-size: 18px; margin-left: 10px;">
                                    <span class="badge bg-danger" style="position: absolute; top: -10px; right: -3px; font-size:9px;">{{ $chatNotificationCount }}</span></i>
                            </a>

                        </div>

                        <div class="ui dropdown item m-auto text-white">
                            Quick Links <i class="dropdown icon text-white"></i>
                            <div class="menu">
                                <a href="/tasks" class="item">Tasks</a>
                                <a href="/HelpDesk" class="item">Helpdesk</a>
                            </div>
                        </div>
                        <div class="item">
                            @php
                            $loggedInEmpId = Auth::guard('emp')->user()->emp_id;
                            $matchingLeaveRequests = DB::table('leave_applications')
                            ->join('employee_details', 'leave_applications.emp_id', '=', 'employee_details.emp_id')
                            ->select('employee_details.first_name', 'employee_details.last_name', 'leave_applications.leave_type', 'leave_applications.emp_id', 'leave_applications.reason')
                            ->where('leave_applications.status', 'pending')
                            ->where(function ($query) use ($loggedInEmpId) {
                            $query->whereJsonContains('leave_applications.applying_to', [['manager_id' => $loggedInEmpId]])
                            ->orWhereJsonContains('leave_applications.cc_to', [['emp_id' => $loggedInEmpId]]);
                            })
                            ->get();
                            $matchingLeaveRequestsCount = $matchingLeaveRequests->count();
                            $employeeId = auth()->guard('emp')->user()->emp_id;

                            // Count notifications where receiver_id matches the logged-in employee's emp_id
                            $chatNotificationCount = DB::table('messages')
                            ->where('receiver_id', $employeeId)
                            ->count();

                            function updateNotificationCount($matchingLeaveRequestsCount, $chatNotificationCount) {
                            // For example, decrement the notification count
                            $notificationCount = $matchingLeaveRequestsCount + $chatNotificationCount;
                            $notificationCount -= 1;
                            // Update the HTML element to reflect the new count
                            echo "<script>
                                document.getElementById('notificationCount').innerText = $notificationCount;
                            </script>";
                            }
                            @endphp
                            <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight" style="background:none;border:none;">
                                <i style="color: white; position: relative;font-size:18px;" class="fas mr-1 fa-bell">
                                    <span class="badge bg-danger" style="position: absolute; top: -9px; right: -2px; font-size:9px;">{{ $matchingLeaveRequestsCount +  $chatNotificationCount }}</span>
                                </i>
                            </button>
                        </div>
                        <div class="offcanvas offcanvas-end " tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel" style="width:320px;background:#f5f8f9;">
                            <div class="offcanvas-header d-flex justify-content-between align-items-center">
                                <h6 id="offcanvasRightLabel" class="offcanvasRightLabel">Notifications <span class="lableCount" id="notificationCount"> ({{ $matchingLeaveRequestsCount + $chatNotificationCount }})</span> </h6>
                                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close" style="font-size: 7px; width: 15px; height: 15px; border-radius: 50%; padding: 2px;border:1px solid #778899;"></button>
                            </div>
                            <div style="border-bottom:1px solid #ccc;"></div>
                            <div class="offcanvas-body">
                                <!-- ///leave notifcations -->
                                @if ($mangerid)
                                @foreach ($matchingLeaveRequests as $request)
                                <div class="leave-request-container mb-2">
                                    <div class="border rounded bg-white p-2" style="text-decoration:none;" title="{{ $request->leave_type }}">
                                        <p class="mb-0 notification-text">EMPLOYEE LEAVE REQUESTS</p>
                                        <a href="/employees-review" class="notification-head">{{ ucwords(strtolower($request->first_name)) }} {{ ucwords(strtolower($request->last_name)) }} (#{{ $request->emp_id }})</a>
                                        <p class="mb-0 notification-text-para">Above employee applied a leave request of Reason : {{ ucfirst(strtolower($request->reason)) }} </p>
                                    </div>
                                </div>
                                @endforeach
                                @endif
                                <!-- ////chat notifications -->
                                @php
                                $employeeId = auth()->guard('emp')->user()->emp_id;
                                // Count notifications where receiver_id matches the logged-in employee's emp_id
                                $senderDetails = DB::table('messages')
                                ->join('employee_details', 'messages.sender_id', '=', 'employee_details.emp_id')
                                ->where('receiver_id', $employeeId)
                                ->select('messages.*', 'employee_details.first_name', 'employee_details.last_name')
                                ->get();
                                @endphp
                                @foreach ($senderDetails as $request)
                                <div class="leave-request-container mb-4">
                                    <div class="border rounded bg-white p-2" style="text-decoration:none;">
                                        <p class="mb-0 notification-text">Chat Notifications</p>
                                        <a href="{{ route('chat', ['query' => $request->chating_id]) }}" class="notification-head" onclick="updateNotificationCount($matchingLeaveRequestsCount, $chatNotificationCount)">
                                            {{ $request->first_name }} {{ $request->last_name }} (#{{ $request->sender_id }})
                                        </a>
                                        <p class="mb-0 notification-text-para">message : {{ ucfirst(strtolower($request->body)) }} </p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="item">
                            <div class="ui primary pointer">
                                @livewire('profile-card')
                            </div>
                        </div>
                        @endauth
                        <div class="item">
                            <div >
                                @livewire('log-out')
                            </div>
                        </div>

                    </div>
                </div>

                <div class="ui csd" style="margin-left: 210px;">
                    <!-- <h2 class="ui header">Sample Content</h2> -->
                    <div class="slot pt-4 ">
                        {{ $slot }}
                    </div>
                </div>
            </div>
            </div>

            <!-- Modal -->
            <div class="modal fade backdropModal" id="navigateLoader" data-bs-backdrop="static" data-bs-keyboard="false"
                tabindex="-1" aria-labelledby="navigateLoaderLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content" style="background-color : transparent; border : none">
                        <!-- <div class="modal-header">
                            <h1 class="modal-title fs-5" id="navigateLoaderLabel">Modal title</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div> -->

                        <div class="modal-body">
                            <div class="logo text-center mb-1" style="padding-top: 20px;">
                                @livewire('company-logo')
                            </div>

                            <div class="d-flex justify-content-center m-4">
                                <div class="spinner-grow text-primary" style="width: 3rem; height: 3rem;" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Understood</button>
        </div> -->
                    </div>
                </div>
            </div>

        </div>



        @livewireScripts
        <script>
            $(".hamButton").on("click", function() {
            $(".ui.sidebar").toggleClass("very thin icon");
            $(".asd").toggleClass("marginlefting");
            $(".csd").toggleClass("marginlefting");
            $(".sidebar z").toggleClass("displaynone");
            $(".ui.accordion").toggleClass("displaynone");
            $(".profileCard").toggleClass("displaynone");
            $(".ui.dropdown.item").toggleClass("displayblock");

            $(".logo").find('img').toggle();

        });

        $(".ui.dropdown").dropdown({
            allowCategorySelection: true,
            transition: "fade up",
            context: 'sidebar',
            on: "hover"
        });

        $('.ui.accordion').accordion({
            selector: {

            }
        });

        $(document).ready(function() {
            $('.menu-item-label').click(function() {
                // Remove active class from all menu items
                $('.menu-item-label').removeClass('active');

                // Add active class to the clicked menu item
                $(this).addClass('active');

                // Get the target content ID from the clicked menu item's data-target attribute
                var target = $(this).data('target');

                // Hide all content items
                $('.content-item').removeClass('active');

                // Show the targeted content item
                $(target).addClass('active');
            });
        });



        function redirectToPage(page) {
            window.location.href = page;
        }

            function myMenu() {
                document.getElementById("menu-popup").classList.toggle("displayBlock");
            }

            // function myMenuSmall() {
            //     document.getElementById("menu-small").classList.toggle("hideMinBar");
            //     document.getElementById("menu-popup").classList.toggle("showMinBar");
            // }

            function myMenuSmall() {
                var menuSmall = document.getElementById("menu-small");
                var menuPopup = document.getElementById("menu-popup");

                // Toggle the "hideMinBar" class
                menuSmall.classList.toggle("hideMinBar");
                menuPopup.classList.toggle("showMinBar");

                // Store the state in localStorage
                if (menuSmall.classList.contains("hideMinBar")) {
                    localStorage.setItem("sidebarState", "hidden");
                } else {
                    localStorage.setItem("sidebarState", "visible");
                }
            }

            // Retrieve the sidebar state on page load
            window.onload = function() {
                var sidebarState = localStorage.getItem("sidebarState");
                var menuSmall = document.getElementById("menu-small");
                var menuPopup = document.getElementById("menu-popup");

                // Set the sidebar state based on the stored value
                if (sidebarState === "hidden") {
                    menuSmall.classList.add("hideMinBar");
                    menuPopup.classList.add("showMinBar");
                }
            };


            function myMenuSmallHover() {
                document.getElementById("menu-small").classList.toggle("showMinBar");
                document.getElementById("menu-popup-hover").classList.toggle("hideMinBar");
            }

            if (localStorage.getItem("pageIcon") && localStorage.getItem("pageTitle")) {

                var storedIcon = localStorage.getItem("pageIcon");

                var storedTitle = localStorage.getItem("pageTitle");

                document.getElementById("pageIcon").innerHTML = storedIcon;

                document.getElementById("pageTitle").textContent = storedTitle;

            }

            function toggleLeaveDropdown(event) {
                event.stopPropagation();
                const leaveOptions = document.getElementById("leave-options");
                const leaveCaret = document.getElementById("leave-caret");

                if (leaveOptions.style.display === "block") {
                    leaveOptions.style.display = "none";
                    leaveCaret.classList.remove("fa-caret-up");
                    leaveCaret.classList.add("fa-caret-down");
                } else {
                    leaveOptions.style.display = "block";
                    leaveCaret.classList.remove("fa-caret-down");
                    leaveCaret.classList.add("fa-caret-up");
                }
            }

            function toggleLeaveDropdown2(event) {
                event.stopPropagation();
                const leaveOptions = document.getElementById("leave-options-2");
                const leaveCaret = document.getElementById("leave-caret-2");

                if (leaveOptions.style.display === "block") {
                    leaveOptions.style.display = "none";
                    leaveCaret.classList.remove("fa-caret-up");
                    leaveCaret.classList.add("fa-caret-down");
                } else {
                    leaveOptions.style.display = "block";
                    leaveCaret.classList.remove("fa-caret-down");
                    leaveCaret.classList.add("fa-caret-up");
                }
            }

            function toggleAttendanceDropdown() {
                const AttendanceOptions = document.getElementById("attendance-options");
                const AttendanceCaret = document.getElementById("attendance-caret");

                if (AttendanceOptions.style.display === "block") {
                    AttendanceOptions.style.display = "none";
                    AttendanceCaret.classList.remove("fa-caret-up");
                    AttendanceCaret.classList.add("fa-caret-down");
                } else {
                    AttendanceOptions.style.display = "block";
                    AttendanceCaret.classList.remove("fa-caret-down");
                    AttendanceCaret.classList.add("fa-caret-up");
                }
            }

            function toggleAttendanceDropdown2() {
                const AttendanceOptions = document.getElementById("attendance-options-2");
                const AttendanceCaret = document.getElementById("attendance-caret-2");

                if (AttendanceOptions.style.display === "block") {
                    AttendanceOptions.style.display = "none";
                    AttendanceCaret.classList.remove("fa-caret-up");
                    AttendanceCaret.classList.add("fa-caret-down");
                } else {
                    AttendanceOptions.style.display = "block";
                    AttendanceCaret.classList.remove("fa-caret-down");
                    AttendanceCaret.classList.add("fa-caret-up");
                }
            }

            function toggleSalaryDropdown() {
                const salaryOptions = document.getElementById("salary-options");
                const salaryCaret = document.getElementById("salary-caret");
                const leaveOptions = document.getElementById("leave-options");
                const leaveCaret = document.getElementById("leave-caret");

                if (salaryOptions.style.display === "block") {
                    salaryOptions.style.display = "none";
                    leaveOptions.style.display = "none";
                    salaryCaret.classList.remove("fa-caret-up");
                    salaryCaret.classList.add("fa-caret-down");
                } else {
                    salaryOptions.style.display = "block";
                    salaryCaret.classList.remove("fa-caret-down");
                    salaryCaret.classList.add("fa-caret-up");
                }
            }

            function toggleSalaryDropdown2() {
                const salaryOptions = document.getElementById("salary-options-2");
                const salaryCaret = document.getElementById("salary-caret-2");
                const leaveOptions = document.getElementById("leave-options-2");
                const leaveCaret = document.getElementById("leave-caret-2");

                if (salaryOptions.style.display === "block") {
                    salaryOptions.style.display = "none";
                    leaveOptions.style.display = "none";
                    salaryCaret.classList.remove("fa-caret-up");
                    salaryCaret.classList.add("fa-caret-down");
                } else {
                    salaryOptions.style.display = "block";
                    salaryCaret.classList.remove("fa-caret-down");
                    salaryCaret.classList.add("fa-caret-up");
                }
            }

            var todoDropdownClicked = false;

            function toggleToDoDropdown() {
                const todoOptions = document.getElementById("todo-options");
                const todoCaret = document.getElementById("todo-caret");
                const salaryOptions = document.getElementById("salary-options");
                const salaryCaret = document.getElementById("salary-caret");
                const leaveOptions = document.getElementById("leave-options");
                const leaveCaret = document.getElementById("leave-caret");

                // Check the status of other dropdowns and close them if open
                if (salaryOptions.style.display === "block") {
                    salaryOptions.style.display = "none";
                    salaryCaret.classList.remove("fa-caret-up");
                    salaryCaret.classList.add("fa-caret-down");
                }

                if (leaveOptions.style.display === "block") {
                    leaveOptions.style.display = "none";
                    leaveCaret.classList.remove("fa-caret-up");
                    leaveCaret.classList.add("fa-caret-down");
                }

                // Toggle the state of the current dropdown
                if (todoOptions.style.display === "block" && !todoDropdownClicked) {
                    todoOptions.style.display = "none";
                    todoCaret.classList.remove("fa-caret-up");
                    todoCaret.classList.add("fa-caret-down");
                } else {
                    todoOptions.style.display = "block";
                    todoCaret.classList.remove("fa-caret-down");
                    todoCaret.classList.add("fa-caret-up");
                    todoDropdownClicked = false; // Reset the flag after toggling
                }
            }

            function toggleToDoDropdown2() {
                const todoOptions = document.getElementById("todo-options-2");
                const todoCaret = document.getElementById("todo-caret-2");
                const salaryOptions = document.getElementById("salary-options-2");
                const salaryCaret = document.getElementById("salary-caret-2");
                const leaveOptions = document.getElementById("leave-options-2");
                const leaveCaret = document.getElementById("leave-caret-2");

                // Check the status of other dropdowns and close them if open
                if (salaryOptions.style.display === "block") {
                    salaryOptions.style.display = "none";
                    salaryCaret.classList.remove("fa-caret-up");
                    salaryCaret.classList.add("fa-caret-down");
                }

                if (leaveOptions.style.display === "block") {
                    leaveOptions.style.display = "none";
                    leaveCaret.classList.remove("fa-caret-up");
                    leaveCaret.classList.add("fa-caret-down");
                }

                // Toggle the state of the current dropdown
                if (todoOptions.style.display === "block" && !todoDropdownClicked) {
                    todoOptions.style.display = "none";
                    todoCaret.classList.remove("fa-caret-up");
                    todoCaret.classList.add("fa-caret-down");
                } else {
                    todoOptions.style.display = "block";
                    todoCaret.classList.remove("fa-caret-down");
                    todoCaret.classList.add("fa-caret-up");
                    todoDropdownClicked = false; // Reset the flag after toggling
                }
            }


            function selectOption(option, pageTitle) {
                const accordionItems = document.querySelectorAll('.nav-link');
                // Update the pageTitle
                updatePageTitle(pageTitle);
                // Close the dropdown if open
                toggleAttendanceDropdown();
                toggleLeaveDropdown();
                toggleSalaryDropdown();
            }

            function updatePageTitle(newTitle) {
                document.getElementById("pageTitle").textContent = newTitle;
                localStorage.setItem("pageTitle", newTitle);
            }

            // function setActiveLink(link) {
            //     // Remove active-link class from all links
            //     var links = document.querySelectorAll('.nav-link');
            //     links.forEach(function(el) {
            //         el.classList.remove('active-link');
            //     });

            //     // Add active-link class to the parent of the clicked link (li element)
            //     link.parentNode.classList.add('active-link');
            // }

            function setActiveLink(link, targetUrl) {
                var currentUrl = window.location.pathname;

                // Check if the target URL is the same as the current URL
                if (currentUrl !== targetUrl) {
                    openModal();
                    // Remove active class from all links
                    var links = document.querySelectorAll('.nav-link');
                    links.forEach(function(element) {
                        element.classList.remove('active');
                    });

                    // Add active class to the clicked link
                    link.classList.add('active');

                } else {
                    // If target URL is same as current URL, prevent modal opening
                    event.preventDefault();
                    console.log("Already on the same page.");
                }
            }

            function openModal() {
                var modal = new bootstrap.Modal(document.getElementById('navigateLoader'));
                modal.show();
            }



            // Check and set active link on page load
            document.addEventListener("DOMContentLoaded", function() {
                var currentPath = window.location.pathname;
                var links = document.querySelectorAll('.nav-link');

                links.forEach(function(link) {
                    if (link.getAttribute("href") === currentPath) {
                        link.parentNode.classList.add('active-link');
                    }
                });
            });
        </script>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    </body>

@endguest

</html>