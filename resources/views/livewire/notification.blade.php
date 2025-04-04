<div class="d-flex align-items-center gap-3">
    <div id="notificationButton" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
        <a href="#" class="nav-link">
            <i class='fas fa-bell icon'></i>
            @if ($totalnotificationscount > 0)
            <span class="badge">{{$totalnotificationscount}}</span>
            @endif
        </a>
    </div>
    <div>
        <div class="nav-link" onclick="window.location.href='{{ url('/users');}}'">
            <i class='fas fa-comment-dots icon notifications-pointer '></i>
            @if ($chatNotificationCount > 0)
            <span class="badge">
                {{ $chatNotificationCount }}
            </span>
            @endif
        </div>
    </div>
    <div class="offcanvas offcanvas-end notification-detail-container " tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel" style="width: 320px;">
        <div class="offcanvas-header d-flex justify-content-between align-items-center">
            <h6 id="offcanvasRightLabel" class="offcanvasRightLabel">Notifications <span class="lableCount" id="notificationCount">
                    ({{$totalnotificationscount}})</span> </h6>
            <button type="button" class="btn-close text-reset notification-close-btn" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="notification-horizontal-line"></div>
        <div class="offcanvas-body">
            @if($totalBirthdays>0 && $getRemainingBirthday)
            @if($totalBirthdays == 1 )
            <div class="border rounded bg-white p-2 mb-2 leave-request-container">
                <p class="mb-0 notification-text-para"> <a href="#" onclick="window.location.href='{{ url('/Feeds');}}'" title="{{ ucwords(strtolower($getRemainingBirthday->first_name)) }} {{ ucwords(strtolower($getRemainingBirthday->last_name)) }}
                            (#{{ $getRemainingBirthday->emp_id }}) is Celebrating birthday today" class="notification-head">
                        {{ ucwords(strtolower($getRemainingBirthday->first_name)) }} {{ ucwords(strtolower($getRemainingBirthday->last_name)) }}
                        (#{{ $getRemainingBirthday->emp_id }}) is Celebrating birthday today
                    </a></p>
                @if($getRemainingBirthday->gender=='MALE')
                <p class="mb-0 notification-text-para">Wish him a Happy Birthday </p>
                @else
                <p class="mb-0 notification-text-para">Wish her a Happy Birthday </p>
                @endif
                <div class="notify-time">
                    <p class="notify-time-para">{{$birthdayTime}}</p>
                </div>
            </div>
            @else
            <div class="border rounded bg-white p-2 mb-2 leave-request-container">
                <p class="mb-0 notification-text-para"> <a href="#" onclick="window.location.href='{{ url('/Feeds');}}'" class="notification-head">
                        Today {{$totalBirthdays}} employees are Celebrating birthdays
                    </a></p>

                <p class="mb-0 notification-text-para">Wish them a Happy Birthday </p>
                <div class="notify-time">
                    <p class="notify-time-para">{{$birthdayTime}}</p>
                </div>
            </div>
            @endif
            @endif

            <!-- //for experinced employees -->
            @if($totalExpEmp>0 && $getRemainingExpEmp && $getRemainingExpID)
            @if($totalExpEmp == 1 )
            <div class="border rounded bg-white p-2 mb-2 leave-request-container">
                <p class="mb-0 notification-text-para">
                    <a href="#" wire:click="getNotification({{ $getRemainingExpID->first()->id }})"
                        title="Congratulations to {{ ucwords(strtolower($getRemainingExpEmp->first_name)) }} {{ ucwords(strtolower($getRemainingExpEmp->last_name)) }}(#{{ $getRemainingExpEmp->emp_id }}) on completing {{ $successfulYears }} successful year(s)!"
                        class="notification-head">
                        Congratulations to {{ ucwords(strtolower($getRemainingExpEmp->first_name)) }} {{ ucwords(strtolower($getRemainingExpEmp->last_name)) }}
                        (#{{ $getRemainingExpEmp->emp_id }}) on completing {{ $successfulYears }} successful year(s)!
                    </a>
                </p>

                @if($getRemainingExpEmp->gender == 'MALE')
                <p class="mb-0 notification-text-para">
                    Please congratulate {{ ucwords(strtolower($getRemainingExpEmp->first_name)) }} {{ ucwords(strtolower($getRemainingExpEmp->last_name)) }}
                    (#{{ $getRemainingExpEmp->emp_id }}) on completing {{ $successfulYears }} successful year(s) in the company.
                </p>
                @else
                <p class="mb-0 notification-text-para">
                    Please congratulate {{ ucwords(strtolower($getRemainingExpEmp->first_name)) }} {{ ucwords(strtolower($getRemainingExpEmp->last_name)) }}
                    (#{{ $getRemainingExpEmp->emp_id }}) on completing {{ $successfulYears }} successful year(s) in the company.
                </p>
                @endif
                <div class="notify-time">
                    <p class="notify-time-para">{{$joiningTime}}</p>
                </div>
            </div>
            @else
            <div class="border rounded bg-white p-2 mb-2 leave-request-container">
                <p class="mb-0 notification-text-para">
                    <a href="#" wire:click="getNotification({{ json_encode($getRemainingJoineesID ? $getRemainingJoineesID->pluck('id')->toArray() : []) }})" class="notification-head">
                        Congratulations to {{$totalExpEmp}} members on completing their successful year(s)!
                    </a>
                </p>


                <p class="mb-0 notification-text-para">Please congratulate them. </p>
                <div class="notify-time">
                    <p class="notify-time-para">{{$joiningTime}}</p>
                </div>
            </div>
            @endif
            @endif

            <!-- //for newly joined employeess -->
            @if($totalJoinees > 0 && $getRemainingJoinees && $getRemainingJoineesID)
            @if($totalJoinees == 1 )
            <div class="border rounded bg-white p-2 mb-2 leave-request-container">
                <p class="mb-0 notification-text-para">
                    <a href="#" wire:click="getNotification({{ $getRemainingJoineesID->first()->id }})"
                        title="Welcome {{ ucwords(strtolower($getRemainingJoinees->first_name)) }} {{ ucwords(strtolower($getRemainingJoinees->last_name)) }} (#{{ $getRemainingJoinees->emp_id }}) to the team!"
                        class="notification-head">
                        Welcome {{ ucwords(strtolower($getRemainingJoinees->first_name)) }} {{ ucwords(strtolower($getRemainingJoinees->last_name)) }}
                        (#{{ $getRemainingJoinees->emp_id }}) to the team!
                    </a>
                </p>

                @if($getRemainingJoinees->gender == 'MALE')
                <p class="mb-0 notification-text-para">
                    Let's give a warm welcome to {{ ucwords(strtolower($getRemainingJoinees->first_name)) }} {{ ucwords(strtolower($getRemainingJoinees->last_name)) }}
                    (#{{ $getRemainingJoinees->emp_id }}) as he joins the team.
                </p>
                @else
                <p class="mb-0 notification-text-para">
                    Let's give a warm welcome to {{ ucwords(strtolower($getRemainingJoinees->first_name)) }} {{ ucwords(strtolower($getRemainingJoinees->last_name)) }}
                    (#{{ $getRemainingJoinees->emp_id }}) as she joins the team.
                </p>
                @endif
                <div class="notify-time">
                    <p class="notify-time-para">{{$joiningTime}}</p>
                </div>
            </div>
            @else
            <div class="border rounded bg-white p-2 mb-2 leave-request-container">
                <p class="mb-0 notification-text-para">
                    <a href="#" wire:click="getNotification({{ json_encode($getRemainingJoineesID->pluck('id')->toArray()) }})" class="notification-head">
                        Welcome {{$totalJoinees}} new member(s) to the team!
                    </a>
                </p>

                <p class="mb-0 notification-text-para">Let's give them all a warm welcome!</p>
                <div class="notify-time">
                    <p class="notify-time-para">{{$joiningTime}}</p>
                </div>
            </div>
            @endif
            @endif



            @if($totalnotificationscount <= 0)
                <div class="text-center mt-4">
                <p class="mb-0 notification-text">No Notifications</p>
        </div>
        @else
        @foreach ($totalnotifications as $notification)
        @if($notification->notification_type=='task')
        <div>
            <div class="border rounded bg-white p-2 mb-2 leave-request-container">
                <p class="mb-0 notification-text-para"> <a href="#" class="notification-head" wire:click.prevent="reduceTaskCount('{{ $notification->emp_id }}')">
                        {{ ucwords(strtolower($notification->first_name)) }} {{ ucwords(strtolower($notification->last_name)) }}
                        (#{{ $notification->emp_id }})
                    </a></p>
                @if($notification->details_count>1 && $notification->details_count<=10 ) <p class="mb-0 notification-text-para"> Has assigned {{$notification->details_count}} tasks to you.
                    @elseif($notification->details_count>10)
                    <p class="mb-0 notification-text-para"> Has assigned 10+ tasks to you.</p>
                    @else
                    <p class="mb-0 notification-text-para">Has assigned task to you. </p>
                    <div class="notify-time">
                        <p class="notify-time-para">{{$notification->notify_time}}</p>
                    </div>
                    @endif
            </div>
        </div>
        @elseif($notification->notification_type=='task-Reopen')
        <div>
            <div class="border rounded bg-white p-2 mb-2 leave-request-container">
                <p class="mb-0 notification-text-para"> <a href="#" class="notification-head" wire:click.prevent="reduceTaskCount('{{ $notification->emp_id }}')">
                        {{ ucwords(strtolower($notification->first_name)) }} {{ ucwords(strtolower($notification->last_name)) }}
                        (#{{ $notification->emp_id }})
                    </a></p>
                @if($notification->details_count>1 && $notification->details_count<=10 ) <p class="mb-0 notification-text-para"> Has Re-Opened {{$notification->details_count}} tasks to you.
                    @elseif($notification->details_count>10)
                    <p class="mb-0 notification-text-para"> Has Re-Opened 10+ tasks to you.</p>
                    @else
                    <p class="mb-0 notification-text-para">Has Re-Opened task to you. </p>
                    <div class="notify-time">
                        <p class="notify-time-para">{{$notification->notify_time}}</p>
                    </div>
                    @endif
            </div>
        </div>
        @elseif($notification->notification_type=='task-Closed')
        <div>
            <div class="border rounded bg-white p-2 mb-2 leave-request-container">
                <p class="mb-0 notification-text-para"> <a href="#" class="notification-head" wire:click.prevent="reduceTaskCount('{{ $notification->emp_id }}')">
                        {{ ucwords(strtolower($notification->first_name)) }} {{ ucwords(strtolower($notification->last_name)) }}
                        (#{{ $notification->emp_id }})
                    </a></p>
                @if($notification->details_count>1 && $notification->details_count<=10 ) <p class="mb-0 notification-text-para"> Has Closed {{$notification->details_count}} tasks.
                    @elseif($notification->details_count>10)
                    <p class="mb-0 notification-text-para"> Has Closed 10+ tasks.</p>
                    @else
                    <p class="mb-0 notification-text-para">Has Closed the task. </p>
                    <div class="notify-time">
                        <p class="notify-time-para">{{$notification->notify_time}}</p>
                    </div>
                    @endif
            </div>
        </div>

        @elseif($notification->notification_type=='leave')
        <div>
            <div class="border rounded bg-white p-2 mb-2 leave-request-container" title="{{ $notification->leave_type }}">
                <p class="mb-0 notification-text">
                    <a href="#" class="notification-head" wire:click.prevent="reduceLeaveRequestCount('{{ $notification->emp_id }}')">
                        {{ ucwords(strtolower($notification->first_name)) }} {{ ucwords(strtolower($notification->last_name)) }}
                        (#{{ $notification->emp_id }})
                    </a>
                </p>

                @if($notification->details_count>1 && $notification->details_count<=10 )
                    <p class="mb-0 notification-text-para"> Sent {{$notification->details_count}} leave requests.</p>
                    @elseif($notification->details_count>10)
                    <p class="mb-0 notification-text-para"> Sent 10+ leave requests.</p>
                    @else
                    <p class="mb-0 notification-text-para"> Sent a leave request.</p>
                    <div class="notify-time">
                        <p class="notify-time-para">{{$notification->notify_time}}</p>
                    </div>
                    @endif
            </div>
        </div>
        @elseif($notification->notification_type=='leaveApprove')
        <div>
            <div class="border rounded bg-white p-2 mb-2 leave-request-container" title="{{ $notification->leave_type }}">
                <p class="mb-0 notification-text">
                    <a href="#" class="notification-head" wire:click.prevent="reduceLeaveRequestCount('{{ $notification->emp_id }}')">
                        {{ ucwords(strtolower($notification->first_name)) }} {{ ucwords(strtolower($notification->last_name)) }}
                        (#{{ $notification->emp_id }})
                    </a>
                </p>

                @if($notification->details_count>1 && $notification->details_count<=10 )
                    <p class="mb-0 notification-text-para"> Approved {{$notification->details_count}} leave requests.</p>
                    @elseif($notification->details_count>10)
                    <p class="mb-0 notification-text-para"> Approved 10+ leave requests.</p>
                    @else
                    <p class="mb-0 notification-text-para"> Approved your leave request.</p>
                    <div class="notify-time">
                        <p class="notify-time-para">{{$notification->notify_time}}</p>
                    </div>
                    @endif
            </div>
        </div>
        @elseif($notification->notification_type=='leaveReject')
        <div>
            <div class="border rounded bg-white p-2 mb-2 leave-request-container" title="{{ $notification->leave_type }}">
                <p class="mb-0 notification-text">
                    <a href="#" class="notification-head" wire:click.prevent="reduceLeaveRequestCount('{{ $notification->emp_id }}')">
                        {{ ucwords(strtolower($notification->first_name)) }} {{ ucwords(strtolower($notification->last_name)) }}
                        (#{{ $notification->emp_id }})
                    </a>
                </p>

                @if($notification->details_count>1 && $notification->details_count<=10 )
                    <p class="mb-0 notification-text-para"> Rejected {{$notification->details_count}} leave requests.</p>
                    @elseif($notification->details_count>10)
                    <p class="mb-0 notification-text-para"> Rejected 10+ leave requests.</p>
                    @else
                    <p class="mb-0 notification-text-para"> Rejected your leave request.</p>
                    <div class="notify-time">
                        <p class="notify-time-para">{{$notification->notify_time}}</p>
                    </div>
                    @endif
            </div>
        </div>
        @elseif($notification->notification_type=='regularisationApply')
        <div>
            <div class="border rounded bg-white p-2 mb-2 leave-request-container">
                <p class="mb-0 notification-text">
                    <a href="#" class="notification-head">
                        {{ ucwords(strtolower($notification->first_name)) }} {{ ucwords(strtolower($notification->last_name)) }}
                        (#{{ $notification->emp_id }})
                    </a>
                </p>


                <p class="mb-0 notification-text-para">Applied for Attendance Regularisation request.</p>
                <div class="notify-time">
                    <p class="notify-time-para">{{$notification->notify_time}}</p>
                </div>

            </div>
        </div>
        @elseif($notification->notification_type=='regularisationReject')
        <div>
            <div class="border rounded bg-white p-2 mb-2 leave-request-container">
                <p class="mb-0 notification-text">
                    <a href="#" class="notification-head">
                        {{ ucwords(strtolower($notification->first_name)) }} {{ ucwords(strtolower($notification->last_name)) }}
                        (#{{ $notification->emp_id }})
                    </a>
                </p>


                <p class="mb-0 notification-text-para">Rejected your Attendance Regularisation request.</p>
                <div class="notify-time">
                    <p class="notify-time-para">{{$notification->notify_time}}</p>
                </div>

            </div>
        </div>

        @elseif($notification->notification_type=='leaveCancel')
        <div>
            <div class="border rounded bg-white p-2 mb-2 leave-request-container" title="{{ $notification->leave_type }}">
                <p class="mb-0 notification-text">
                    <a href="#" class="notification-head" wire:click.prevent="reduceLeaveRequestCount('{{ $notification->emp_id }}')">
                        {{ ucwords(strtolower($notification->first_name)) }} {{ ucwords(strtolower($notification->last_name)) }}
                        (#{{ $notification->emp_id }})
                    </a>
                </p>

                @if($notification->details_count>1 && $notification->details_count<=10 )
                    <p class="mb-0 notification-text-para"> Sent {{$notification->details_count}} leave cancel requests.</p>
                    @elseif($notification->details_count>10)
                    <p class="mb-0 notification-text-para"> Sent 10+ leave cancel requests.</p>
                    @else
                    <p class="mb-0 notification-text-para"> Sent a leave cancel request.</p>
                    <div class="notify-time">
                        <p class="notify-time-para">{{$notification->notify_time}}</p>
                    </div>
                    @endif
            </div>
        </div>
        @elseif($notification->notification_type=='message')
        <div class="border rounded bg-white p-2 mb-2">

            <p class="mb-0 notification-text-para">
                <a href="#" wire:click.prevent="markAsRead({{ $notification->chatting_id }})" class="notification-head">
                    {{ ucwords(strtolower($notification->first_name)) }}
                    {{ ucwords(strtolower($notification->last_name)) }} (#{{ $notification->emp_id }})

                </a>
            </p>

            @if($notification->details_count>1 && $notification->details_count<=10 ) <p class="mb-0 notification-text-para"> sent {{$notification->details_count}} messages. </p>
                @elseif($notification->details_count>10)
                <p class="mb-0 notification-text-para"> sent 10+ messages.</p>
                @else
                <p class="mb-0 notification-text-para"> sent a message.</p>
                @endif

                <div class="notify-time">
                    <p class="notify-time-para">{{$notification->notify_time}}</p>
                </div>
        </div>
        @elseif($notification->notification_type=='delegate')
        <div>
            <div class="border rounded bg-white p-2 mb-2 leave-request-container">
                <p class="mb-0 notification-text">
                    <a href="#" class="notification-head" onclick="window.location.href='{{ url('/delegates');}}'">
                        {{ ucwords(strtolower($notification->first_name)) }} {{ ucwords(strtolower($notification->last_name)) }}
                        (#{{ $notification->emp_id }})
                    </a>
                </p>

                @if($notification->details_count>1 && $notification->details_count<=10 )
                    <p class="mb-0 notification-text-para"> Added {{$notification->details_count}} Workflow delegates.</p>
                    @elseif($notification->details_count>10)
                    <p class="mb-0 notification-text-para"> Added 10+ Workflow delegates.</p>
                    @else
                    <p class="mb-0 notification-text-para">Added Workflow delegate.</p>
                    <div class="notify-time">
                        <p class="notify-time-para">{{$notification->notify_time}}</p>
                    </div>
                    @endif
            </div>
        </div>
        @endif
        @endforeach
        @endif
    </div>

</div>
</div>