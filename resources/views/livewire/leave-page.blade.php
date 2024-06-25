<div class="m-0 px-4" style="position: relative;">
    <div class="toggle-container">
        <style>
            /* Define your custom CSS classes */
            .custom-nav-tabs {
                background-color: #fff;
                border-radius: 5px;
                display: flex;
                font-weight: 500;
                color: #778899;
                width: 50%;
                font-size: 0.825rem;
                /* Background color for the tabs */
            }

            .custom-nav-link {
                color: #ccc;
                /* Text color for inactive tabs */
            }

            .custom-nav-link.active {
                margin-top: 5px;
                color: white !important;
                background-color: rgb(2, 17, 79);
                border-radius: 5px;
            }
        </style>
        <!-- leave-page.blade.php -->


        @if(session()->has('message'))
        <div class="alert alert-success d-flex justify-content-between" style="font-size:12px;">
            {{ session('message') }}
            <span class="close-btn" onclick="closeMessage()" style="cursor:pointer;">X</span>
        </div>
        @if(session()->has('error'))
        <div class="alert alert-danger" style="font-size:12px;">
            {{ session('error') }}
        </div>
        @endif
        <script>
            // Close the success message after a certain time
            setTimeout(function() {
                closeMessage();
            }, 5000); // Adjust the time limit (in milliseconds) as needed

            function closeMessage() {
                document.querySelector('.alert-success').style.display = 'none';
            }
        </script>
        @endif

        <div class="nav-buttons d-flex justify-content-center mx-2 p-0">
            <ul class="nav custom-nav-tabs">
                <!-- Apply the custom class to the nav -->
                <li class="nav-item flex-grow-1">
                    <a class="nav-link custom-nav-link active" data-section="applyButton" onclick="toggleDetails('applyButton', this)">Apply</a>
                </li>
                <li class="nav-item flex-grow-1">
                    <a class="nav-link custom-nav-link" data-section="pendingButton" onclick="toggleDetails('pendingButton', this)">Pending</a>
                </li>
                <li class="nav-item flex-grow-1">
                    <a class="nav-link custom-nav-link" data-section="historyButton" onclick="toggleDetails('historyButton', this)">History</a>
                </li>
            </ul>
        </div>


        <div id="cardElement" class="side ">

            <div>

                <a onclick="toggleOptions('leave', this)" data-section="leave">Leave</a>

            </div>
            <div class="line"></div>
            <div>

                <a onclick="toggleOptions('restricted', this)" data-section="restricted">Restricted Holiday</a>

            </div>

            <div class="line"></div>
            <div>

                <a onclick="toggleOptions('leaveCancel', this)" data-section="leaveCancel">Leave Cancel</a>

            </div>
            <div class="line"></div>
            <div>

                <a onclick="toggleOptions('compOff', this)" data-section="compOff">Comp Off Grant</a>

            </div>

        </div>


        <div id="leave" class="row mt-2 align-items-center " style="display:none;">

            <div style="width:85%; margin:0 auto;">@livewire('leave-apply') </div>

        </div>

        <div id="restricted" class="row mt-2 w-85 align-items-center" style="display:none;">
            <div style="width:85%; margin:0 auto;">
                <div class="leave-pending rounded">
                    <div class="hide-info">
                        <p style="font-size:10px;">Restricted Holidays (RH) are a set of holidays allocated by the
                            company that are optional for the employee to utilize. The company sets a limit on the
                            amount of holidays that can be used.</p>
                        <p onclick="toggleInfo()" style="font-weight:500; color:#3a9efd;cursor:pointer;">Hide</p>
                    </div>
                    <div class="d-flex justify-content-between">
                        <p style="color:#333; font-size:14px; font-weight:500;text-align:start; ">Applying for
                            Restricted Holiday</p>
                        <p class="info-paragraph" onclick="toggleInfo()">Info</p>
                    </div>
                    <img src="{{asset('/images/pending.png')}}" alt="Pending Image" style="width:40%; margin:0 auto;">
                    <p style="color:#778899; font-size:10px; font-weight:500;  text-align:center;">You have no
                        Restricted Holiday balance, as per our record.</p>
                </div>
            </div>
        </div>
        <div id="leaveCancel" class="row w-85 mt-2 align-items-center" style="display: none;">
            <div style="width:85%; margin:0 auto;"> @livewire('leave-cancel') </div>
        </div>

        <div id="compOff" class="row w-85 mt-2 align-items-center" style="display: none;">
            <div style="width:85%; margin:0 auto;">
                <div>
                    <div class="leave-pending rounded">
                        <div class="hide-info">
                            <p>Compensatory Off is additional leave granted as a compensation for working overtime or on
                                an off day.</p>
                            <p onclick="toggleInfo()" style="font-weight:500; color:#3a9efd;cursor:pointer;">Hide</p>
                        </div>
                        <div class="d-flex justify-content-between">
                            <p style="color:#333; font-size:14px; font-weight:500;text-align:start; ">Applying for Comp.
                                Off Grant</p>
                            <p class="info-paragraph" onclick="toggleInfo()">Info</p>
                        </div>
                        <img src="{{asset('/images/pending.png')}}" alt="Pending Image" style="width:40%; margin:0 auto;">
                        <p style="color:#778899; font-size:0.825rem; font-weight:500;  text-align:center;">You are not
                            eligible to request for compensatory off grant. Please contact your HR for further
                            information.</p>
                    </div>
                </div>
            </div>
        </div>


        {{-- Apply Tab --}}
        <div class="row" id="applyButton">
            <div style="width:85%; margin:0 auto;">@livewire('leave-apply')</div>
        </div>

        {{-- pending --}}
        <div id="pendingButton" class="row rounded mt-4" style="display: none;">

            @if($this->leavePending->isNotEmpty())

            @foreach($this->leavePending as $leaveRequest)

            <div class="container-pending mt-4" style="width:85%; margin:0 auto;">

                <div class="accordion rounded">

                    <div class="accordion-heading rounded" onclick="toggleAccordion(this)">

                        <div class="accordion-title px-2 py-3 rounded">

                            <!-- Display leave details here based on $leaveRequest -->

                            <div class="col accordion-content">

                                <span style="color: #778899; font-size: 12px; font-weight: 500;">Category</span>

                                <span style="color: #36454F; font-size: 12px; font-weight: 500;">Leave</span>

                            </div>

                            <div class="col accordion-content">

                                <span style="color: #778899; font-size: 12px; font-weight: 500;">Leave Type</span>

                                <span style="color: #36454F; font-size: 12px; font-weight: 500;">{{ $leaveRequest->leave_type}}</span>

                            </div>

                            <div class="col accordion-content">

                                <span style="color: #778899; font-size: 12px; font-weight: 500;">No. of Days</span>

                                <span style="color: #36454F; font-size: 12px; font-weight: 500;">

                                    {{ $this->calculateNumberOfDays($leaveRequest->from_date, $leaveRequest->from_session, $leaveRequest->to_date, $leaveRequest->to_session) }}

                                </span>

                            </div>


                            <!-- Add other details based on your leave request structure -->

                            <div class="col accordion-content">

                                <span style="margin-top:0.625rem; font-size: 12px; font-weight: 400; color:#cf9b17;">{{ strtoupper($leaveRequest->status) }}</span>

                            </div>

                            <div class="arrow-btn">
                                <i class="fa fa-angle-down"></i>
                            </div>

                        </div>

                    </div>

                    <div class="accordion-body m-0 p-0">

                        <div style="width:100%; height:1px; border-bottom:1px solid #ccc; margin-bottom:10px;"></div>

                        <div class="content px-2">

                            <span style="color: #778899; font-size: 12px; font-weight: 500;">Duration:</span>

                            <span style="font-size: 11px;">

                                <span style="font-size: 11px; font-weight: 500;">
                                    {{ \Carbon\Carbon::parse($leaveRequest->from_date)->format('d-m-Y') }} </span>

                                ( {{ $leaveRequest->from_session }} )to

                                <span style="font-size: 11px; font-weight: 500;">
                                    {{ \Carbon\Carbon::parse($leaveRequest->to_date)->format('d-m-Y') }}</span>

                                ( {{ $leaveRequest->to_session }} )

                            </span>

                        </div>

                        <div class="content px-2">

                            <span style="color: #778899; font-size: 12px; font-weight: 500;">Reason:</span>

                            <span style="font-size: 11px;">{{ ucfirst( $leaveRequest->reason) }}</span>

                        </div>

                        <div style="width:100%; height:1px; border-bottom:1px solid #ccc; margin-bottom:10px;"></div>

                        <div style="display:flex; flex-direction:row; justify-content:space-between;">

                            <div class="content px-2">

                                <span style="color: #778899; font-size: 12px; font-weight: 500;">Applied on:</span>

                                <span style="color: #333; font-size:12px; font-weight: 500;">{{ $leaveRequest->created_at->format('d M, Y') }}</span>

                            </div>

                            <div class="content px-2">

                                <a href="{{ route('leave-history', ['leaveRequestId' => $leaveRequest->id]) }}">

                                    <span style="color: rgb(2,17,53); font-size: 12px; font-weight: 500;">View
                                        Details</span>

                                </a>
                                <button class="withdraw mb-2" wire:click="cancelLeave({{ $leaveRequest->id }})">Withdraw</button>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

            @endforeach

            @else

            <div class="leave-pending" style="margin-top:30px; background:#fff; margin-left:120px; display:flex; width:75%;flex-direction:column; text-align:center;justify-content:center; border:1px solid #ccc; padding:20px;gap:10px;">

                <img src="{{asset('/images/pending.png')}}" alt="Pending Image" style="width:60%; margin:0 auto;">

                <p style="color:#969ea9; font-size:13px; font-weight:400; ">There are no pending records of any leave
                    transaction</p>

            </div>

            @endif

        </div>



        {{-- history --}}

        <div id="historyButton" class="row rounded mt-4" style="display: none;">
            @if($this->leaveRequests->isNotEmpty())

            @foreach($this->leaveRequests->whereIn('status', ['approved', 'rejected','Withdrawn']) as $leaveRequest)

            <div class="container mt-4" style="width:85%; margin:0 auto;">

                <div class="accordion rounded ">

                    <div class="accordion-heading rounded" onclick="toggleAccordion(this)">

                        <div class="accordion-title px-2 py-3">

                            <!-- Display leave details here based on $leaveRequest -->

                            <div class="col accordion-content">

                                <span style="color: #778899; font-size:12px; font-weight: 500;">Category</span>

                                <span style="color: #36454F; font-size: 12px; font-weight: 500;">Leave</span>

                            </div>

                            <div class="col accordion-content">

                                <span style="color: #778899; font-size:12px; font-weight: 500;">Leave Type</span>

                                <span style="color: #36454F; font-size: 12px; font-weight: 500;">{{ $leaveRequest->leave_type}}</span>

                            </div>

                            <div class="col accordion-content">

                                <span style="color: #778899; font-size:12px; font-weight: 500;">No. of Days</span>

                                <span style="color: #36454F; font-size: 12px; font-weight: 500;">

                                    {{ $this->calculateNumberOfDays($leaveRequest->from_date, $leaveRequest->from_session, $leaveRequest->to_date, $leaveRequest->to_session) }}

                                </span>

                            </div>



                            <!-- Add other details based on your leave request structure -->



                            <div class="col accordion-content">

                                @if(strtoupper($leaveRequest->status) == 'APPROVED')

                                <span style="margin-top:0.625rem; font-size: 12px; font-weight: 500; color:#32CD32;">{{ strtoupper($leaveRequest->status) }}</span>

                                @elseif(strtoupper($leaveRequest->status) == 'REJECTED')

                                <span style="margin-top:0.625rem; font-size: 12px; font-weight: 500; color:#FF0000;">{{ strtoupper($leaveRequest->status) }}</span>

                                @else

                                <span style="margin-top:0.625rem; font-size: 12px; font-weight: 500; color:#778899;">{{ strtoupper($leaveRequest->status) }}</span>

                                @endif

                            </div>

                            <div class="arrow-btn">
                                <i class="fa fa-angle-down"></i>
                            </div>

                        </div>

                    </div>

                    <div class="accordion-body m-0 p-0">

                        <div style="width:100%; height:1px; border-bottom:1px solid #ccc; margin-bottom:10px;"></div>

                        <div class="content px-2">

                            <span style="color: #778899; font-size:12px; font-weight: 500;">Duration:</span>

                            <span style="font-size: 11px;">

                                <span style="font-size: 11px; font-weight: 500;">{{ $leaveRequest->formatted_from_date }}</span>

                                ({{ $leaveRequest->from_session }} ) to

                                <span style="font-size: 11px; font-weight: 500;">{{ $leaveRequest->formatted_to_date }}</span>

                                ( {{ $leaveRequest->to_session }} )

                            </span>

                        </div>

                        <div class="content px-2">

                            <span style="color: #778899; font-size:12px; font-weight: 500;">Reason:</span>

                            <span style="font-size: 11px;">{{ ucfirst($leaveRequest->reason) }}</span>

                        </div>

                        <div style="width:100%; height:1px; border-bottom:1px solid #ccc; margin-bottom:10px;"></div>

                        <div style="display:flex; flex-direction:row; justify-content:space-between;">

                            <div class="content px-2 mb-2">

                                <span style="color: #778899; font-size:12px; font-weight: 400;">Applied on:</span>

                                <span style="color: #333; font-size: 12px; font-weight: 500;">{{ $leaveRequest->created_at->format('d M, Y') }}</span>

                            </div>

                            <div class="content px-2 mb-2">

                                <a href="{{ route('leave-pending', ['leaveRequestId' => $leaveRequest->id]) }}">
                                    <span style="color: rgb(2,17,53); font-size:12px; font-weight: 500;">View
                                        Details</span>
                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            @endforeach

            @else

            <div class="leave-pending" style="margin-top:30px; background:#fff; margin-left:120px; display:flex; width:75%;flex-direction:column; text-align:center;justify-content:center; border:1px solid #ccc; padding:20px;gap:10px;">

                <img src="{{asset('/images/pending.png')}}" alt="Pending Image" style="width:60%; margin:0 auto;">

                <p style="color:#969ea9; font-size:13px; font-weight:400; ">There are no history records of any leave
                    transaction</p>

            </div>

            @endif

        </div>

    </div>
</div>



<script>
    function toggleInfo() {
        const hideInfoDiv = document.querySelector('.hide-info');
        const infoParagraph = document.querySelector('.info-paragraph');

        hideInfoDiv.style.display = (hideInfoDiv.style.display === 'none' || hideInfoDiv.style.display === '') ? 'flex' :
            'none';
        infoParagraph.style.display = (infoParagraph.style.display === 'none' || infoParagraph.style.display === '') ?
            'block' : 'none';
    }

    function toggleDetails(sectionId, clickedLink) {
        const tabs = ['applyButton', 'pendingButton', 'historyButton'];

        const links = document.querySelectorAll('.custom-nav-link');
        links.forEach(link => link.classList.remove('active'));

        clickedLink.classList.add('active');

        tabs.forEach(tab => {
            const tabElement = document.getElementById(tab);
            if (tab === sectionId) {
                tabElement.style.display = 'block';
            } else {
                tabElement.style.display = 'none';
            }
        });

        // Hide the content of other containers when 'pendingButton' or 'historyButton' is clicked
        const cardElement = document.getElementById('cardElement');
        if (sectionId === 'pendingButton' || sectionId === 'historyButton') {
            otherContainers.forEach(container => {
                const containerElement = document.getElementById(container);
                containerElement.style.display = 'none';
                cardElement.style.display = 'none';
            });
        } else {
            // Show all containers when other buttons are clicked
            const allContainers = ['leave'];
            allContainers.forEach(container => {
                const containerElement = document.getElementById(container);
                containerElement.style.display = 'block';
            });
        }
    }



    function toggleOptions(sectionId, clickedLink) {
        const tabs = ['leave', 'restricted', 'leaveCancel', 'compOff'];

        const links = document.querySelectorAll('.side a');
        links.forEach(link => link.classList.remove('active'));

        clickedLink.classList.add('active');

        tabs.forEach(tab => {
            const tabElement = document.getElementById(tab);
            if (tab === sectionId) {
                tabElement.style.display = 'block';
            } else {
                tabElement.style.display = 'none';
            }
        });

        // Hide the content of other containers
        const otherContainers = ['pendingButton', 'historyButton'];
        otherContainers.forEach(container => {
            const containerElement = document.getElementById(container);
            containerElement.style.display = 'none';
        });
        if (sectionId !== 'leave' && sectionId !== 'applyButton') {
            // Hide the 'applyButton' and 'leave' sections
            document.getElementById('leave').style.display = 'none';
            document.getElementById('applyButton').style.display = 'none';
        } else {
            // Show the 'applyButton' and 'leave' sections for other sections
            document.getElementById('leave').style.display = 'block';
            document.getElementById('applyButton').style.display = 'block';
        }
    }

    function toggleAccordion(element) {
        const accordionBody = element.nextElementSibling;

        if (accordionBody.style.display === 'block') {
            accordionBody.style.display = 'none';
            element.classList.remove('active'); // Remove active class
        } else {
            accordionBody.style.display = 'block';
            element.classList.add('active'); // Add active class
        }
    }
</script>