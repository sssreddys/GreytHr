<div>

    <body>
        <div class="msg-container">
            <!--[if BLOCK]><![endif]--><?php if(session()->has('success')): ?>
            <div x-data x-init="checkFadeIn()" class="custom-alert alert-success successAlert row mx-auto" style="text-align:center;margin-left:20%;">
                <p class="mx-auto mb-0"><?php echo e(session('success')); ?> 😀 <span wire:click="hideMessage" style="cursor: pointer; margin-left:20px;">&#10006;</span> </p>
            </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
        <div class="content">
            <div class="row m-0 mb-3">
                <div class="col-md-6 mb-3">
                    <div class="homeCard4 ps-4" style="height: 15em;">
                        <!--[if BLOCK]><![endif]--><?php if($this->greetingText): ?>
                        <h1 class="mt-4 greet-text text-secondary-500 pb-1.5x" style="font-size: 24px; font-family: montserrat;;color:rgb(2, 17, 79); font-weight: 600;"><?php echo e($greetingText); ?></h1>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <p class="fw-bold fs-3">Welcome, Shishir Kumar</p>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="homeCard4" style="background-image: url(<?php echo e(asset('/images/morning_animated-ezgif.gif')); ?>); height: 15em;     background-repeat: no-repeat;
    background-size: 100% 15em;">

                    </div>
                </div>

            </div>


            <!-- main content -->

            <div class="row m-0">
                <div class="col-md-3 mb-4 ">
                    <div class="home-hover">
                        <div class="homeCard4">
                            <div style="color: black; padding:10px 15px;">
                                <p style="font-size:12px;"><?php echo e($currentDate); ?></p>
                                <p style="margin-top: 10px; color: #778899; font-size: 11px;">
                                    <?php
                                    $EmployeeStartshiftTime=$employeeShiftDetails->shift_start_time;
                                    $EmployeeEndshiftTime=$employeeShiftDetails->shift_end_time;
                                    // Create DateTime objects
                                    $startShiftTime = new DateTime($EmployeeStartshiftTime);
                                    $endShiftTime = new DateTime($EmployeeEndshiftTime);
                                    // Format the times
                                    $formattedStartShiftTime = $startShiftTime->format('h:i a');
                                    $formattedEndShiftTime = $endShiftTime->format('H:i a');
                                    ?>
                                    <?php echo e($currentDay); ?> | <?php echo e($formattedStartShiftTime); ?> to <?php echo e($formattedEndShiftTime); ?>

                                </p>
                                <div style="font-size: 14px; display: flex;margin-top:2em;">
                                    <img src="/images/stopwatch.png" class="me-4" alt="Image Description" style="width: 2.7em;">
                                    <p id="current-time" style="margin: auto 0;"></p>
                                </div>
                                <script>
                                    function updateTime() {
                                        const currentTimeElement = document.getElementById('current-time');
                                        const now = new Date();
                                        const hours = String(now.getHours()).padStart(2, '0');
                                        const minutes = String(now.getMinutes()).padStart(2, '0');
                                        const seconds = String(now.getSeconds()).padStart(2, '0');
                                        const currentTime = `${hours} : ${minutes} : ${seconds}`;
                                        currentTimeElement.textContent = currentTime;
                                    }
                                    updateTime();
                                    setInterval(updateTime, 1000);
                                </script>
                                <div class="A" style="display: flex;flex-direction:row;justify-content:space-between; align-items:center;margin-top:2em">
                                    <a style="width:50%;font-size:11px;cursor: pointer;color:blue" wire:click="open">View Swipes</a>
                                    <button id="signButton" style="color: white; width: 80px; height: 26px;font-size:10px; background-color: rgb(2, 17, 79); border: 1px solid #CFCACA; border-radius: 5px; " wire:click="toggleSignState">
                                        <!--[if BLOCK]><![endif]--><?php if($swipes): ?>
                                        <!--[if BLOCK]><![endif]--><?php if($swipes->in_or_out=="OUT"): ?>
                                        Sign In
                                        <?php else: ?>
                                        Sign Out
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        <?php else: ?>
                                        Sign In
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--[if BLOCK]><![endif]--><?php if($ismanager): ?>
                <div class="col-md-3 mb-4 ">
                    <div class="home-hover">
                        <div class="reviews">
                            <div class="homeCard1">
                                <div class="home-heading d-flex justify-content-between px-3 py-2">
                                    <div class="rounded pt-1">
                                        <p style="font-size:12px;color:#778899;font-weight:500;"> Review</p>
                                    </div>
                                    <div>
                                        <a href="/employees-review" style="font-size:16px; "><img src="/images/up-arrow.png" alt="" style="width:20px;height:27px;"></a>
                                    </div>
                                </div>
                                <!--[if BLOCK]><![endif]--><?php if(($this->count) > 0): ?>
                                <div class="notify d-flex justify-content-between  px-3">
                                    <p style="color: black; font-size: 12px; font-weight: 500;">
                                        <?php echo e($count); ?> <br>
                                        <span style="color: #778899; font-size:11px; font-weight: 500;">Things to review</span>
                                    </p>
                                    <img src="<?php echo e(asset('/images/review-pending.jpg')); ?>" alt="" style="height: 40px; width: 40px;">
                                </div>
                                <div class="leave-display d-flex align-items-center border-top p-3 gap-1">
                                    <?php
                                    function getRandomColor() {
                                    $colors = ['#FFD1DC', '#B0E57C', '#ADD8E6', '#E6E6FA', '#FFB6C1'];
                                    return $colors[array_rand($colors)];
                                    }
                                    ?>
                                    <!--[if BLOCK]><![endif]--><?php for($i = 0; $i < min($count, 3); $i++): ?> <?php
                                                                                $leaveRequest = $this->leaveApplied[$i]['leaveRequest'] ?? null;
                                                                                if ($leaveRequest && $leaveRequest->employee) {
                                                                                    $firstName = $leaveRequest->employee->first_name;
                                                                                    $lastName = $leaveRequest->employee->last_name;
                                                                                    $initials = strtoupper(substr($firstName, 0, 1)) . strtoupper(substr($lastName, 0, 1));
                                                                                ?> <div class="circle-container d-flex flex-column mr-3">
                                        <div class="thisCircle d-flex" style="border: 2px solid <?php echo e(getRandomColor()); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo e($firstName); ?> <?php echo e($lastName); ?>">
                                            <span><?php echo e($initials); ?></span>
                                        </div>
                                        <span class="leaveText">Leave</span>
                                </div>

                            <?php
                                                                                }
                            ?>
                            <?php endfor; ?><!--[if ENDBLOCK]><![endif]-->
                            <!--[if BLOCK]><![endif]--><?php if($count > 3): ?>
                            <div class=" remainContent d-flex flex-column align-items-center" wire:click="reviewLeaveAndAttendance">
                                <span>+<?php echo e($count - 3); ?></span>
                                <span class="remaining">More</span>
                            </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                            <?php else: ?>
                            <div class="d-flex flex-column justify-content-center align-items-center">
                                <img src="/images/not_found.png" alt="Image Description" style="width: 7em;">
                                <p class="mb-2 homeText">
                                    Hurrah! You've nothing to review.
                                </p>
                            </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                </div>
            </div>
            <!--[if BLOCK]><![endif]--><?php if($showReviewLeaveAndAttendance): ?>
            <div class="modal" tabindex="-1" role="dialog" style="display: block;">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: rgb(2, 17, 79); height: 50px">
                            <h5 style="padding: 5px; color: white; font-size: 15px;" class="modal-title"><b>Review</b></h5>
                            <button type="button" class="btn-close btn-primary" aria-label="Close" wire:click="closereviewLeaveAndAttendance" style="background-color: white; height:10px;width:10px;">
                            </button>
                        </div>
                        <div class="modal-body">
                            <h6 style="color:#778899;font-size:14px;">Leave Requests</h6>
                            <div class="d-flex flex-row">
                                <!--[if BLOCK]><![endif]--><?php if($count > 3): ?>
                                <!--[if BLOCK]><![endif]--><?php for($i = 3; $i <= $count; $i++): ?> <?php
                                                                    $leaveRequest = $this->leaveApplied[$i]['leaveRequest'] ?? null;
                                                                    if ($leaveRequest && $leaveRequest->employee) {
                                                                        $firstName = $leaveRequest->employee->first_name;
                                                                        $lastName = $leaveRequest->employee->last_name;
                                                                        $initials = strtoupper(substr($firstName, 0, 1)) . strtoupper(substr($lastName, 0, 1));
                                                                    ?> <div class=" d-flex flex-column mr-3">
                                    <div class="thisCircle d-flex" style="border: 2px solid <?php echo e(getRandomColor()); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo e($firstName); ?> <?php echo e($lastName); ?>">
                                        <span><?php echo e($initials); ?></span>
                                    </div>
                                    <span style="display: block;font-size:10px;color:#778899;">Leave</span>
                            </div>

                        <?php
                                                                    }
                        ?>
                        <?php endfor; ?><!--[if ENDBLOCK]><![endif]-->
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <h6 style="color:#778899;font-size:14px;">Attendance Requests</h6>
                        <div class="d-flex flex-row">
                            <!--[if BLOCK]><![endif]--><?php for($i = 0; $i <= $countofregularisations; $i++): ?> <?php
                                                                                // Fetch the regularisation at the current index
                                                                                $regularisation = $this->regularisations[$i] ?? null;
                                                                                if ($regularisation && $regularisation->employee) {
                                                                                    $firstName = $regularisation->employee->first_name;
                                                                                    $lastName = $regularisation->employee->last_name;
                                                                                    $initials = strtoupper(substr($firstName, 0, 1)) . strtoupper(substr($lastName, 0, 1));
                                                                                ?> <div class=" d-flex flex-column mr-3">
                                <div class="thisCircle d-flex" style="border: 2px solid <?php echo e(getRandomColor()); ?>" data-toggle="tooltip" data-placement="top" title="<?php echo e($firstName); ?> <?php echo e($lastName); ?>">
                                    <span><?php echo e($initials); ?></span>
                                </div>
                                <span style="display: block;font-size:10px;color:#778899;text-align:center;overflow: hidden; text-overflow: ellipsis;max-width:30px;white-space:nowrap;">Attendance Regularisation</span>
                        </div>

                    <?php
                                                                                }
                    ?>
                    <?php endfor; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="cancel-btn" style="border:1px solid rgb(2,17,79);" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
</div>
<div class="modal-backdrop fade show blurred-backdrop"></div>
<?php endif; ?><!--[if ENDBLOCK]><![endif]-->
<?php endif; ?><!--[if ENDBLOCK]><![endif]-->

<div class="col-md-3 mb-4 ">
    <div class="home-hover">
        <div class="homeCard6" style="padding:10px 15px;">
            <div style="display:flex; justify-content:space-between;">
                <p style="font-size:12px;color:#778899;font-weight:500;">Upcoming Holidays</p>
                <a href="/holiday-calender" style="font-size:16px; "><img src="/images/up-arrow.png" alt="" style="width:20px;height:27px;"></a>
            </div>

            <!--[if BLOCK]><![endif]--><?php if($calendarData->isEmpty()): ?>
            <p style="color:#778899;font-size:10px;">Uh oh! No holidays to show.</p>
            <?php else: ?>
            <?php
            $count = 0;
            ?>

            <div class="row m-0">
                <div class="col-7 p-0">
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $calendarData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <!--[if BLOCK]><![endif]--><?php if(!empty($entry->festivals)): ?>
                    <div>
                        <p style="color: #677A8E; font-size: 11px;margin-bottom:10px; ">
                            <span style="font-weight: 500;"><?php echo e(date('d M', strtotime($entry->date))); ?> <span style="font-size: 10px; font-weight: normal;"><?php echo e(date('l', strtotime($entry->date))); ?></span></span>
                            <br>
                            <span style="font-size: 11px; font-weight: normal;"><?php echo e(ucfirst($entry->festivals)); ?></span>
                        </p>
                    </div>
                    <?php
                    $count++;
                    ?>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                    <!--[if BLOCK]><![endif]--><?php if($count >= 3): ?>
                    <?php break; ?>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </div>
                <div class="col-5 m-auto p-0">
                    <img src="/images/A day off.gif" style="width: 8em">
                </div>
            </div>


            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>
</div>
<div class="col-md-3 mb-4 ">
    <div class="home-hover">
        <div class="homeCard1" style="padding:10px 15px;">
            <div style="display:flex; justify-content:space-between;">
                <p style="font-size:12px;color:#778899;font-weight:500;">Time Sheet</p>
            </div>
            <div class="mt-2" class="d-flex align-items-center justify-content-center">
                <p class="homeText mt-2">
                    Submit your time sheet for this week.
                </p>
            </div>
            <div class="B mb-3" style="color:  #677A8E; font-size: 12px;display:flex;justify-content:center; margin-top: 15px;">
                <a href="/time-sheet" class="button-link">
                    <button class="custom-btn" style="border:1px solid #fd2885;border-radius:5px;padding:4px 7px;color:#fd2885;font-weight:500;background:#fffbfd;">
                        Submit Time Sheet
                    </button>
                </a>
            </div>

        </div>
    </div>
</div>

<div class="col-md-3 mb-4 ">
    <div class="home-hover">
        <div class="homeCard2" style="padding:10px 15px;justify-content:center;display: flex;flex-direction:column;">
            <div>
                <p style="font-size:12px;color:#778899;font-weight:500;">Apply for a Leave</p>
            </div>
            <div class="mt-2" style="display: flex;align-items:center;text-align:center;">
                <p class="homeText">Kindly click on the "Apply" button below to submit your leave application.</p>
            </div>
            <div class="B mb-3" style="color:  #677A8E; font-size: 12px;display:flex;justify-content:center; margin-top: 15px;">
                <a href="/leave-page" class="button-link">
                    <button class="leaveCustom-btn">Apply</button>
                </a>
            </div>
        </div>
    </div>
</div>

<!--[if BLOCK]><![endif]--><?php if($ismanager): ?>
<div class="col-md-6 mb-4 ">
    <div class="home-hover">
        <div class="homeCard6" style="padding:10px 15px;">
            <div style="color: #677A8E;  font-weight:500; display:flex;justify-content:space-between;">
                <p style="font-size:12px;"> Who is in?</p>
                <a href="/whoisinchart" style="font-size:16px; "><img src="/images/up-arrow.png" alt="" style="width:20px;height:27px;"></a>
            </div>
            <div>
                <div class="who-is-in d-flex flex-column justify-content-start ">
                    <p class="section-name">
                        Not Yet In (<?php echo e($CountAbsentEmployees); ?>)
                    </p>
                    <div class="team-leave d-flex flex-row gap-3">
                        <?php
                        function getRandomAbsentColor() {
                        $colors = ['#FFD1DC', '#D2E0FB', '#ADD8E6', '#E6E6FA', '#F1EAFF','#FFC5C5'];
                        return $colors[array_rand($colors)];
                        }
                        ?>
                        <!--[if BLOCK]><![endif]--><?php if($CountAbsentEmployees > 0): ?>
                        <!--[if BLOCK]><![endif]--><?php for($i = 0; $i < min($CountAbsentEmployees, 4); $i++): ?> <!--[if BLOCK]><![endif]--><?php if(isset($AbsentEmployees[$i])): ?> <?php $employee=$AbsentEmployees[$i]; $randomColorAbsent='#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0' , STR_PAD_LEFT); ?> <a href="/whoisinchart" style="text-decoration: none;">
                            <div class="thisCircle" style="border: 2px solid <?php echo e(getRandomAbsentColor()); ?>;" data-toggle="tooltip" data-placement="top" title="<?php echo e(ucwords(strtolower($employee['first_name']))); ?> <?php echo e(ucwords(strtolower($employee['last_name']))); ?>">
                                <span class="initials">
                                    <?php echo e(strtoupper(substr(trim($employee['first_name']), 0, 1))); ?><?php echo e(strtoupper(substr(trim($employee['last_name']), 0,1))); ?>

                                </span>
                            </div>
                            </a>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <?php endfor; ?><!--[if ENDBLOCK]><![endif]-->
                            <?php else: ?>
                            <p style="font-size:12px;color:orange">No employees are absent today</p>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <!--[if BLOCK]><![endif]--><?php if($CountAbsentEmployees > 5): ?>
                            <div class="remainContent d-flex flex-column align-items-center mt-2">
                                <span>+<?php echo e($CountAbsentEmployees - 9); ?></span>
                                <p class="mb-0" style="margin-top:-5px;">More</p>
                            </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
                <!-- /second row -->

                <div class="who-is-in d-flex flex-column justify-content-start ">
                    <p class="section-name mt-1">
                        Late Arrival (<?php echo e($CountLateSwipes); ?>)
                    </p>
                    <div class="team-leave d-flex flex-row  gap-3">
                        <?php
                        function getRandomLateColor() {
                        $colors = ['#FFD1DC', '#D2E0FB', '#ADD8E6', '#E6E6FA', '#F1EAFF','#FFC5C5'];
                        return $colors[array_rand($colors)];
                        }
                        ?>
                        <!--[if BLOCK]><![endif]--><?php if($CountLateSwipes>0): ?>
                        <!--[if BLOCK]><![endif]--><?php for($i = 0; $i < min($CountLateSwipes, 9); $i++): ?> <?php $employee=$LateSwipes[$i]; ?> <!--[if BLOCK]><![endif]--><?php if(isset($LateSwipes[$i])): ?> <a href="/whoisinchart" style="text-decoration: none;">
                            <div class="circle" style="border: 2px solid <?php echo e(getRandomAbsentColor()); ?>;border-radius:50%;width: 35px;height: 35px;display: flex;align-items: center;justify-content: center;" data-toggle="tooltip" data-placement="top" title="<?php echo e(ucwords(strtolower($employee['first_name']))); ?> <?php echo e(ucwords(strtolower($employee['last_name']))); ?>">
                                <span class="initials">
                                    <?php echo e(strtoupper(substr(trim($employee['first_name']), 0, 1))); ?><?php echo e(strtoupper(substr(trim($employee['last_name']), 0,1))); ?>

                                </span>
                            </div>
                            </a>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <?php endfor; ?><!--[if ENDBLOCK]><![endif]-->
                            <?php else: ?>
                            <p style="font-size:12px;color:orange">No employees arrived late today</p>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <!--[if BLOCK]><![endif]--><?php if($CountLateSwipes > 9): ?>
                            <div class="remainContent d-flex flex-column align-items-center mt-2">
                                <span>+<?php echo e($CountLateSwipes - 9); ?></span>
                                <p class="mb-0" style="margin-top:-5px;">More</p>
                            </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>

                <!-- /third row -->

                <div class="who-is-in d-flex flex-column justify-content-start">
                    <p class="section-name mt-1">
                        On Time (<?php echo e($CountEarlySwipes); ?>)
                    </p>
                    <div class="team-leave d-flex flex-row mr gap-3">
                        <?php
                        function getRandomEarlyColor() {
                        $colors = ['#FFD1DC', '#D2E0FB', '#ADD8E6', '#E6E6FA', '#F1EAFF','#FFC5C5'];
                        return $colors[array_rand($colors)];
                        }
                        ?>
                        <!--[if BLOCK]><![endif]--><?php if($CountEarlySwipes): ?>
                        <!--[if BLOCK]><![endif]--><?php for($i = 0; $i < min($CountEarlySwipes, 9); $i++): ?> <!--[if BLOCK]><![endif]--><?php if(isset($EarlySwipes[$i])): ?> <?php $employee=$EarlySwipes[$i]; $randomColorEarly='#' . str_pad(dechex(mt_rand(0xCCCCCC, 0xFFFFFF)), 6, '0' , STR_PAD_LEFT); ?> <a href="/whoisinchart" style="text-decoration: none;"></a>
                            <div class="circle" style="border: 2px solid <?php echo e(getRandomAbsentColor()); ?>;border-radius:50%;width: 35px;height: 35px;display: flex;align-items: center;justify-content: center;" data-toggle="tooltip" data-placement="top" title="<?php echo e(ucwords(strtolower($employee['first_name']))); ?> <?php echo e(ucwords(strtolower($employee['last_name']))); ?>">
                                <span class="initials">
                                    <?php echo e(strtoupper(substr(trim($employee['first_name']), 0, 1))); ?><?php echo e(strtoupper(substr(trim($employee['last_name']), 0,1))); ?>

                                </span>
                            </div>
                            </a>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <?php endfor; ?><!--[if ENDBLOCK]><![endif]-->
                            <?php else: ?>
                            <p style="font-size:12px;color:orange">No employees arrived early today</p>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <!--[if BLOCK]><![endif]--><?php if($CountEarlySwipes > 9): ?>
                            <div class="remainContent d-flex flex-column align-items-center mt-2">
                                <span>+<?php echo e($CountEarlySwipes - 9); ?></span>
                                <p class="mb-0" style="margin-top:-5px;">More</p>
                            </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<?php endif; ?><!--[if ENDBLOCK]><![endif]-->

<!-- TEAM ON LEAVE -->
<!--[if BLOCK]><![endif]--><?php if($this->showLeaveApplies): ?>
<div class="col-md-3 mb-4 ">
    <div class="home-hover">
        <div class="reviews">
            <div class="homeCard4">
                <div class="team-heading px-3 mt-2 d-flex justify-content-between">
                    <div>
                        <p class="pt-1 teamOnLeave"> Team On Leave</pclass>
                    </div>
                    <div>
                        <a href="/team-on-leave-chart" style="font-size:16px; "><img src="/images/up-arrow.png" alt="" style="width:20px;height:27px;"></a>
                    </div>
                </div>
                <!--[if BLOCK]><![endif]--><?php if(($this->teamCount) > 0): ?>
                <div class="team-Notify px-3">
                    <p style="color: #778899; font-size: 11px; font-weight: 500;">
                        Today (<?php echo e($teamCount); ?>) </p>
                    <div class="team-leave d-flex flex-row  gap-3">
                        <?php
                        function getRandomLightColor() {
                        $colors = ['#FFD1DC', '#B0E57C', '#ADD8E6', '#E6E6FA', '#FFB6C1'];
                        return $colors[array_rand($colors)];
                        }
                        ?>

                        <!--[if BLOCK]><![endif]--><?php for($i = 0; $i < min($teamCount, 4); $i++): ?> <?php
                                                                        $teamLeave = $this->teamOnLeave[$i] ?? null;
                                                                        if ($teamLeave) {
                                                                            $initials = strtoupper(substr($teamLeave->employee->first_name, 0, 1) . substr($teamLeave->employee->last_name, 0, 1));
                                                                        ?> <div class="thisCircle" style="  border: 2px solid <?php echo e(getRandomLightColor()); ?>;" data-toggle="tooltip" data-placement="top" title="<?php echo e(ucwords(strtolower($teamLeave->employee->first_name))); ?> <?php echo e(ucwords(strtolower($teamLeave->employee->last_name))); ?>">
                            <span><?php echo e($initials); ?></span>
                    </div>

                <?php
                                                                        }
                ?>
                <?php endfor; ?>
                <!--[if BLOCK]><![endif]--><?php if($teamCount > 4): ?>
                <div class="remainContent d-flex mt-3 flex-column align-items-center">
                    <span>+<?php echo e($teamCount - 4); ?></span>
                    <p class="mb-0" style="margin-top:-5px;">More</p>
                </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>

                <div class="mt-4">
                    <p class="homeText font-weight-500 text-start">
                        This month (<?php echo e($upcomingLeaveApplications); ?>) </p>
                    <!--[if BLOCK]><![endif]--><?php if($upcomingLeaveRequests): ?>
                    <div wire:ignore class="mt-2 d-flex align-items-center gap-3 mb-3">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $upcomingLeaveRequests->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $requests): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                        $randomColorList = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
                        ?>
                        <div wire:ignore class="d-flex gap-4 align-items-center">
                            <div class="thisCircle" style="border: 1px solid <?php echo e($randomColorList); ?>">
                                <span><?php echo e(substr($requests->employee->first_name, 0, 1)); ?><?php echo e(substr($requests->employee->last_name, 0, 1)); ?> </span>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        <!--[if BLOCK]><![endif]--><?php if($upcomingLeaveRequests->count() > 3): ?>
                        <div class="remainContent d-flex flex-column align-items-center"> <!-- Placeholder color -->
                            <span>+<?php echo e($upcomingLeaveRequests->count() - 3); ?> </span>
                            <span style="margin-top:-5px;">More</span>
                        </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    <p class="homeText"><a href="/team-on-leave-chart">Click here</a> to see who will be on leave in the upcoming days!</p>
                </div>
            </div>
            <?php else: ?>
            <div style="display:flex;justify-content:center;flex-direction:column;align-items:center;">
                <img src="<?php echo e(asset('images/no data.png')); ?>" alt="Image Description" style="width: 120px; height:100px;">
                <p class="homeText">
                    Wow! No leaves planned today.
                </p>
            </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>
</div>
</div>
<?php endif; ?><!--[if ENDBLOCK]><![endif]-->




<div class="col-md-4 mb-4 ">
    <div class="home-hover">
        <!--[if BLOCK]><![endif]--><?php if($salaryRevision->isEmpty()): ?>
        <div class="homeCard5">
            <div class="py-2 px-3">
                <div class="d-flex justify-content-between">
                    <p style="font-size:12px;color:#778899;font-weight:500;">Payslip</p>
                    <a href="/slip" style="font-size:16px; "><img src="/images/up-arrow.png" alt="" style="width:20px;height:27px;"></a>
                </div>

                <div style="display:flex;align-items:center;flex-direction:column;">
                    <img src="https://cdn3.iconfinder.com/data/icons/human-resources-70/133/9-512.png" alt="" style="height:75px;width:75px;">
                    <p style="color: #677A8E;  margin-bottom: 20px; font-size:12px;"> We are working on your payslip!</p>
                </div>
            </div>
        </div>
        <?php else: ?>
        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $salaryRevision; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $salaries): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="homeCard5" style="padding:10px 15px;">
            <div class="d-flex justify-content-between">
                <p style="font-size:12px;color:#778899;font-weight:500;">Payslip</p>
                <a href="/slip" style="font-size:16px; "><img src="/images/up-arrow.png" alt="" style="width:20px;height:27px;"></a>
            </div>

            <div wire:ignore class="d-flex justify-content-between align-items-center mt-3">
                <div style="position: relative;">
                    <!--  -->
                    <canvas id="combinedPieChart" width="100" height="100"></canvas>
                </div>
                <div class="c" style="font-size: 12px; font-weight: normal; font-weight: 500; color: #9E9696;display:flex; flex-direction:column;justify-content:flex-end;">
                    <p style="color:#333;"><?php echo e(date('M Y', strtotime('-1 month'))); ?></p>
                    <p style="display:flex;justify-content:end;flex-direction:column;align-items:end; color:#333;"><?php echo e(date('t', strtotime('-1 month'))); ?> <br>
                        <span style="color:#778899;">Paid days</span>
                    </p>
                </div>
            </div>

            <div style="display:flex ;flex-direction:column; margin-top:20px;  ">
                <div class="net-salary">
                    <div style="display:flex;gap:10px;">
                        <div style="padding:2px;width:2px;height:17px;background:#000000;border-radius:2px;"></div>
                        <p style="font-size:11px;margin-bottom:10px;">Gross Pay</p>
                    </div>
                    <p style="font-size:12px;"><?php echo e($showSalary ? '₹ ' . number_format($salaries->calculateTotalAllowance(), 2) : '*********'); ?></p>
                </div>
                <div class="net-salary">
                    <div style="display:flex;gap:10px;">
                        <div style="padding:2px;width:2px;height:17px;background:#B9E3C6;border-radius:2px;"></div>
                        <p style="font-size:11px;margin-bottom:10px;">Deduction</p>
                    </div>
                    <p style="font-size:12px;"><?php echo e($showSalary ? '₹ ' . number_format($salaries->calculateTotalDeductions() ?? 0, 2) : '*********'); ?></p>

                </div>
                <div class="net-salary">
                    <div style="display:flex;gap:10px;">
                        <div style="padding:2px;width:2px;height:17px;background:#1C9372;border-radius:2px;"></div>
                        <p style="font-size:11px;margin-bottom:10px;">Net Pay</p>
                    </div>
                    <!--[if BLOCK]><![endif]--><?php if($salaries->calculateTotalAllowance() - $salaries->calculateTotalDeductions() > 0): ?>
                    <p style="font-size:12px;"> <?php echo e($showSalary ? '₹ ' .number_format(max($salaries->calculateTotalAllowance() - $salaries->calculateTotalDeductions(), 0), 2) : '*********'); ?></p>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>
            <div class="show-salary" style="display: flex; color: #1090D8; justify-content:space-between;font-size: 12px;  margin-top: 20px; font-weight: 100;">
                <a href="/your-download-route" id="pdfLink2023_4" class="pdf-download" download>Download PDF</a>
                <a wire:click="toggleSalary" class="showHideSalary">
                    <?php echo e($showSalary ? 'Hide Salary' : 'Show Salary'); ?>

                </a>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>
</div>

<div class="col-md-4 mb-4 ">
    <div class="home-hover mb-4">
        <div class="homeCard2">
            <div class="px-3 py-2" style="color: #677A8E; font-weight:500;">
                <p class="pt-1" style="font-size:12px;">Quick Access</p>
            </div>
            <div style="display: flex; justify-content: space-between; position: relative;">
                <div class="quick col-md-7 px-3 py-0">
                    <a href="/reimbursement" class="quick-link">Reimbursement</a>
                    <a href="/itstatement" class="quick-link">IT Statement</a>
                    <a href="#" class="quick-link">YTD Reports</a>
                    <a href="#" class="quick-link">Loan Statement</a>
                </div>
                <div class="col-md-5" style="text-align: center; background-color: #FFF8F0; padding: 5px 10px; border-radius: 10px; font-size: 8px; font-family: montserrat; position: absolute; bottom: 0; right: 0;">
                    <img src="images/quick_access.png" style="padding-top: 2em; width: 6em">
                    <p class="pt-4">Use quick access to view important salary details.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="home-hover">
        <div class="homeCard4" style="padding:10px 15px;justify-content:center;display: flex;flex-direction:column;">
            <div>
                <p style="font-size:12px;color:#778899;font-weight:500;">POI</p>
            </div>
            <div style="display:flex;gap:10px;align-items:center;margin:10px auto;">
                <img src="images/pen.png" alt="Image Description" style="width: 4em;">
                <p class="homeText">Hold on! You can submit your Proof of Investments (POI) once released.</p>
            </div>
        </div>
    </div>
</div>


<?php
$employeeId = auth()->guard('emp')->user()->emp_id;
$totalTasksAssignedBy = \App\Models\Task::with('emp')
->where(function ($query) use ($employeeId) {
$query->where('assignee', 'LIKE', "%($employeeId)%"); // Check if assignee contains employee ID
})
->select('emp_id')
->get();
$totalTasksCountAssignedBy = $totalTasksAssignedBy->count();

$totalTasksAssignedTo = \App\Models\Task::with('emp')
->where('emp_id', $employeeId)
->select('emp_id')
->get();
$totalTasksCountAssignedTo = $totalTasksAssignedTo->count();
$totalTasksCount = $totalTasksCountAssignedTo + $totalTasksCountAssignedBy ;
// Fetch task records with emp_id
$taskRecords = \App\Models\Task::with('emp')
->where(function ($query) use ($employeeId) {
$query->where('assignee', 'LIKE', "%($employeeId)%"); // Check if assignee contains employee ID
})
->whereDate('created_at', now()->toDateString()) // Filter tasks created today
->select('emp_id')
->get();

// Extract unique emp_ids
$empIds = $taskRecords->pluck('emp_id')->unique()->toArray();

// Fetch employee details based on emp_ids
$employeeDetails = \App\Models\EmployeeDetails::whereIn('emp_id', $empIds)->get();

// Create a string to hold first and last names
$employeeNames = $employeeDetails->map(function ($employee) {
return $employee->first_name . ' ' . $employee->last_name;
})->implode(', ');

// Count the number of records
$taskCount = $taskRecords->count();
?>
<div class="col-md-4 mb-4 ">
    <div class="home-hover mb-4">
        <div class="homeCard1">
            <div class="d-flex justify-content-between px-3" style="color: #778899;font-weight:500;margin-top: 10px;">
                <p class="mb-0" style="font-size:12px;">Track</p>
                <!--[if BLOCK]><![endif]--><?php if($totalTasksCount): ?>
                <span class="mb-0" style="font-size:12px;"> Total tasks: <?php echo e($totalTasksCount); ?></span>
                <?php else: ?>
                <span class="mb-0" style="font-size:12px;"> Total tasks: 0</span>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                <?php
                $countAssignedByOpen = $totalTasksAssignedBy->where('status', 'Open')->count();
                $countAssignedToOpen = $totalTasksAssignedTo->where('status', 'Open')->count();
                ?>

                <!--[if BLOCK]><![endif]--><?php if($countAssignedByOpen > 0 && $countAssignedToOpen > 0): ?>
                pending tasks <?php echo e($countAssignedByOpen + $countAssignedToOpen); ?>

                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            </div>
            <!--[if BLOCK]><![endif]--><?php if($taskCount > 0): ?>
            <div class="p-2 px-3">
                <span data-bs-toggle="tooltip" data-bs-placement="top" title="Task Requests: <?php echo e(ucwords(strtolower($employeeNames))); ?>" style="color:#778899;font-size:12px;cursor:pointer;">New tasks: <?php echo e($taskCount); ?></span>
            </div>
            <?php else: ?>
            <div style="text-align: center">
                <img src="images/track.png" alt="Image Description" style="width: 9em;">
                <div class="track">
                    <p class="homeText">All good! You've nothing new to track.</p>
                </div>
            </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>
    <div class="home-hover">
        <div class="homeCard2" style="padding:10px 15px;justify-content:center;display: flex;flex-direction:column;">
            <div>
                <p style="font-size:12px;color:#778899;font-weight:500;">IT Declaration</p>
            </div>
            <div class="pt-2 d-flex align-items-center gap-2">
                <img src="images/thumb-up.png" alt="Image Description" style="width: 5em;">
                <p class="homeText">Hurrah! Considered your IT declaration for Apr 2023.</p>
            </div>
            <div class="B" style="color:  #677A8E;   font-size: 12px;display:flex;justify-content:end; margin-top: 1em;">
                <a href="/formdeclaration" class="button-link">
                    <button class="custom-btn" style="width:60px;border:1px solid #058383;border-radius:5px;padding:3px 5px;color:#058383;background:#fff;">View</button>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="col-md-6 mb-4">
    <div class="homeCard1 p-1">
        <div class="d-flex justify-content-between" style="color: #778899;font-weight:500;margin-top: 10px;">
            <p style="font-size:12px;">Track</p>
            <!--[if BLOCK]><![endif]--><?php if($totalTasksCount): ?>
            <span class="mb-0" style="font-size:12px;"> Total tasks: <?php echo e($totalTasksCount); ?></span>
            <?php else: ?>
            <span class="mb-0" style="font-size:12px;"> Total tasks: 0</span>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>

        <table class="table table-hover table-borderless" style="border-radius: 10px;">
            <thead class="table-secondary" style="border-radius: 10px;">
                <tr>
                    <th>Project Name</th>
                    <th>Project Type</th>
                    <th>Deadline</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Redesign Fintech</td>
                    <td>UI Design</td>
                    <td>Feb 6</td>
                    <td><span style="color: red">In Progress</span></td>
                </tr>
                <tr>
                    <td>Prototype Fintech</td>
                    <td>UI Design</td>
                    <td>Feb 6</td>
                    <td><span style="color: yellow">In review</span></td>
                </tr>
                <tr>
                    <td>Wireframe Fintech</td>
                    <td>UI Design</td>
                    <td>Feb 6</td>
                    <td><span style="color: green">Complete</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!--[if BLOCK]><![endif]--><?php if($showAlertDialog): ?>
<div class="modal" tabindex="-1" role="dialog" style="display: block;">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: rgb(2, 17, 79); height: 50px">
                <h5 style="padding: 5px; color: white; font-size: 15px;" class="modal-title"><b>Swipes</b></h5>
                <button type="button" class="btn-close btn-primary" data-dismiss="modal" aria-label="Close" wire:click="close" style="background-color: white; height:10px;width:10px;">
                </button>
            </div>
            <div class="modal-body" style="max-height:300px;overflow-y:auto">
                <div class="row">
                    <div class="col" style="font-size: 12px;color:#778899;font-weight:500;">Date : <span style="color: #333;"><?php echo e($currentDate); ?></span></div>
                    <div class="col" style="font-size: 12px;color:#778899;font-weight:500;">Shift Time : <span style="color: #333;">10:00 to 19:00</span></div>
                </div>
                <table class="swipes-table mt-2 border" style="width: 100%;">
                    <tr style="background-color: #f6fbfc;">
                        <th style="width:50%;font-size: 12px; text-align:start;padding:5px 10px;color:#778899;font-weight:500;">Swipe Time</th>
                        <th style="width:50%;font-size: 12px; text-align:start;padding:5px 10px;color:#778899;font-weight:500;">Sign-In / Sign-Out</th>
                    </tr>

                    <!--[if BLOCK]><![endif]--><?php if(!is_null($swipeDetails) && $swipeDetails->count() > 0): ?>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $swipeDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $swipe): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr style="border:1px solid #ccc;">
                        <td style="width:50%;font-size: 10px; color: #778899;text-align:start;padding:5px 10px"><?php echo e($swipe->swipe_time); ?></td>
                        <td style="width:50%;font-size: 10px; color: #778899;text-align:start;padding:5px 10px"><?php echo e($swipe->in_or_out); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    <?php else: ?>
                    <tr>
                        <td class="homeText" colspan="2">No swipe records found for today.</td>
                    </tr>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal-backdrop fade show blurred-backdrop"></div>
<?php endif; ?><!--[if ENDBLOCK]><![endif]-->

</div>
</div>


</body>

</div>
<script>
    // Function to check if an element is in the viewport
    function isElementInViewport(el) {
        var rect = el.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    }

    // Function to check for elements to fade in
    function checkFadeIn() {
        // alert("scroll");
        const fadeInSection = document.querySelectorAll('.');
        fadeInSection.forEach((element) => {
            if (isElementInViewport(element)) {
                element.classList.add('fade-in');
            }
        });
    }

    // Initial check on page load
    window.addEventListener('load', checkFadeIn);
    var combinedData = {
        datasets: [{
                data: [{
                        {
                            !empty($salaries) ? $salaries - > calculateTotalAllowance() : 0
                        }
                    },
                    2, // Placeholder value for the second dataset
                ],
                backgroundColor: [
                    '#000000', // Color for Gross Pay
                ],
            },
            {
                data: [{
                        {
                            !empty($salaries) && method_exists($salaries, 'calculateTotalDeductions') ? $salaries - > calculateTotalDeductions() : 0
                        }
                    },
                    {
                        {
                            !empty($salaries) && method_exists($salaries, 'calculateTotalAllowance') ? $salaries - > calculateTotalAllowance() - $salaries - > calculateTotalDeductions() : 0
                        }
                    },
                ],
                backgroundColor: [
                    '#B9E3C6', // Color for Deductions
                    '#1C9372', // Color for Net Pay
                ],
            },
        ],
    };

    var outerCtx = document.getElementById('combinedPieChart').getContext('2d');

    var combinedPieChart = new Chart(outerCtx, {
        type: 'doughnut',
        data: combinedData,
        options: {
            cutout: '60%', // Adjust the cutout to control the size of the outer circle
            legend: {
                display: false,
            },
            tooltips: {
                enabled: false,
            },
        },
    });
</script><?php /**PATH C:\xampp\htdocs\GreytHr\resources\views/livewire/home.blade.php ENDPATH**/ ?>