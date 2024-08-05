<div>
    <?php
    $employeeId = auth()->guard('emp')->user()->emp_id;
    $managerId = DB::table('employee_details')
    ->join('companies', 'employee_details.company_id', '=', 'companies.company_id')
    ->where('employee_details.manager_id', $employeeId)
    ->select('companies.company_logo', 'companies.company_name')
    ->first();
    ?>
    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="#" class="brand">
            <!-- <i class='fa fas-smile icon'></i> -->
            <img class="m-auto" src="<?php echo e(asset('images/hr_new_blue.png')); ?>" alt="Company Logo" style="width: 6em !important;">
        </a>
        <ul class="side-menu">
            <li><a href="/" class="active"><i class='fas fa-home icon'></i> Dashboard</a></li>
            <li><a href="/time-sheet"><i class='fas fa-clock icon'></i> Time Sheet</a></li>
            <li><a href="/Feeds"><i class='fas fa-rss icon'></i> Feeds</a></li>
            <li><a href="/PeoplesList"><i class='fas fa-users icon'></i> People</a></li>
            <!-- <li class="divider" data-text="main">Main</li> -->
            <li>
                <a href="#"><i class='fas fa-file-alt icon'></i> To Do <i class='fa fa-chevron-right icon-right'></i></a>
                <ul class="side-dropdown">
                    <li><a href="/tasks">Tasks</a></li>
                    <li><a href="/employees-review">Review</a></li>
                </ul>
            </li>
            <li>
                <a href="#"><i class='fas fa-solid fa-money-bill-transfer icon'></i> Salary <i class='fa fa-chevron-right icon-right'></i></a>
                <ul class="side-dropdown">
                    <li><a href="/slip">Payslips</a></li>
                    <li><a href="/ytd">YTD Reports</a></li>
                    <li><a href="/itstatement">IT Statement</a></li>
                    <li><a href="/formdeclaration">IT Declaration</a></li>
                    <li><a href="/reimbursement">Reimbursement</a></li>
                    <li><a href="/investment">Proof of Investment</a></li>
                    <li><a href="/salary-revision">Salary Revision</a></li>
                </ul>
            </li>
            <li>
                <a href="#"><i class='fas fa-file-alt icon'></i> Leave <i class='fa fa-chevron-right icon-right'></i></a>
                <ul class="side-dropdown">
                    <li><a href="/leave-page">Leave Apply</a></li>
                    <li><a href="/leave-balances">Leave Balances</a></li>
                    <li><a href="/leave-calender">Leave Calendar</a></li>
                    <li><a href="/holiday-calendar">Holiday Calendar</a></li>
                    <!--[if BLOCK]><![endif]--><?php if($managerId): ?>
                    <li>
                        <a href="/team-on-leave-chart">
                            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('team-on-leave');

$__html = app('livewire')->mount($__name, $__params, 'lw-2523274130-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                        </a>
                    </li>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </ul>
            </li>
            <li>
                <a href="#"><i class='fas fa-clock icon'></i> Attendance <i class='fa fa-chevron-right icon-right'></i></a>
                <ul class="side-dropdown">
                    <li><a href="/Attendance">Attendance Info</a></li>
                    <!--[if BLOCK]><![endif]--><?php if($managerId): ?>
                    <li>
                        <a href="/whoisinchart">
                            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('whoisin');

$__html = app('livewire')->mount($__name, $__params, 'lw-2523274130-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                        </a>
                    </li>
                    <li>
                        <a href="/employee-swipes-data">
                            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('employee-swipes');

$__html = app('livewire')->mount($__name, $__params, 'lw-2523274130-2', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                        </a>
                    </li>
                    <li>
                        <a href="/attendance-muster-data">
                            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('attendance-muster');

$__html = app('livewire')->mount($__name, $__params, 'lw-2523274130-3', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                        </a>
                    </li>
                    <li>
                        <a href="/shift-roaster-data">
                            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('shift-roaster-submodule');

$__html = app('livewire')->mount($__name, $__params, 'lw-2523274130-4', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                        </a>
                    </li>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </ul>
            </li>
            <li><a href="/document"><i class='fas fa-folder icon'></i>Document Center</a></li>
            <li>
                <a href="#"><i class='fas fa-headset icon'></i> Helpdesk <i class='fa fa-chevron-right icon-right'></i></a>
                <ul class="side-dropdown">
                    <li><a href="/HelpDesk">New Requests</a></li>
                    <li><a href="/users">Connect</a></li>
                </ul>
            </li>
            <li><a href="/delegates"><i class='fas fa-user-friends icon'></i> Workflow Delegates</a></li>
            <!--[if BLOCK]><![endif]--><?php if($managerId): ?>
            <li>
                <a href="/reports">

                    <i class="fas fa-file-alt icon"></i> Reports
                </a>
            </li>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            <!-- <li class="divider" data-text="table and forms">Table and forms</li>
			<li><a href="#"><i class='fa fa-table icon' ></i> Tables</a></li>
			<li>
				<a href="#"><i class='fa fas-notepad icon' ></i> Forms <i class='fa fa-chevron-right icon-right' ></i></a>
				<ul class="side-dropdown">
					<li><a href="#">Basic</a></li>
					<li><a href="#">Select</a></li>
					<li><a href="#">Checkbox</a></li>
					<li><a href="#">Radio</a></li>
				</ul>
			</li> -->
        </ul>
        <!-- <div class="ads">
			<div class="wrapper">
				<a href="#" class="btn-upgrade">Upgrade</a>
				<p>Become a <span>PRO</span> member and enjoy <span>All Features</span></p>
			</div>
		</div> -->
    </section>
    <!-- SIDEBAR -->

    <!-- NAVBAR -->
    <section id="content">
        <!-- NAVBAR -->
        <nav>
            <i class='fas fa-bars toggle-sidebar'></i>
            <form action="#">
                <div class="form-group">
                    <input type="text" placeholder="Search...">
                    <i class='fa fa-search icon'></i>
                </div>
            </form>
            <div>
                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('notification');

$__html = app('livewire')->mount($__name, $__params, 'lw-2523274130-5', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
            </div>
            <span class="divider"></span>
            <div class="profile">
                <div class="d-flex brandLogoDiv">
                    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('company-logo');

$__html = app('livewire')->mount($__name, $__params, 'lw-2523274130-6', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                    <img class="navProfileImg" src="https://images.unsplash.com/photo-1517841905240-472988babdf9?ixid=MnwxMjA3fDB8MHxzZWFyY2h8NHx8cGVvcGxlfGVufDB8fDB8fA%3D%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" alt="">
                </div>
                <ul class="profile-link">
                    <li><a href="/ProfileInfo"><i class='fas fa-user-circle icon'></i> Profile</a></li>
                    <li><a href="/Settings"><i class='fas fa-cog'></i> Settings</a></li>
                    <li><a href="#"  style="cursor:poiner;"><i class='fas fa-sign-out-alt'></i> Logout</a></li>
                </ul>
            </div>
            <div style="cursor: pointer;">
              <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('log-out');

$__html = app('livewire')->mount($__name, $__params, 'lw-2523274130-7', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
            </div>
        </nav>
        <!-- NAVBAR -->

        <!-- MAIN -->

        <!-- MAIN -->
    </section>

    <!-- NAVBAR -->
</div><?php /**PATH C:\xampp\htdocs\GreytHr\resources\views/livewire/main-layout.blade.php ENDPATH**/ ?>