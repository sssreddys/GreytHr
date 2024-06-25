<div class="mx-2">
    @if($errorMessage)
    <div id="errorMessage" class="alert alert-danger d-flex justify-content-between" style="font-size:12px;">
        {{ $errorMessage }}
        <span class="close-btn" onclick="closeErrorMessage()" style="cursor:pointer;">X</span>
    </div>
    <script>
            // Close the success message after a certain time
            setTimeout(function() {
                closeErrorMessage();
            }, 5000); // Adjust the time limit (in milliseconds) as needed

            function closeErrorMessage() {
                document.getElementById('errorMessage').style.display = 'none';
            }
        </script>
    @endif

    <div class="applyContainer bg-white">

        <p style="font-weight:500; font-size:14px;">Applying for Leave</p>

        <form wire:submit.prevent="leaveApply" enctype="multipart/form-data">
            <div class="form-row d-flex mt-3">
                <div class="form-group col-md-7" style="position: relative;">
                    <label for="leaveType" style="color: #778899; font-size: 12px; font-weight: 500;">Leave type</label>
                    <select id="leaveType" class="form-control placeholder-small" wire:click="selectLeave" wire:change="saveLeaveApplication" wire:model="leave_type" name="leaveType" style="width: 50%; font-weight: 400; color: #778899; font-size: 12px;">
                        <option value="default">Select Type</option>
                        @php
                        $managerInfo = DB::table('employee_details')
                        ->join('companies', 'employee_details.company_id', '=', 'companies.company_id')
                        ->where('employee_details.manager_id', $employeeId)
                        ->select('companies.company_logo', 'companies.company_name')
                        ->first();
                        @endphp
                        @if (($differenceInMonths < 6) && ($employeeId !==$managerInfo->manager_id))
                            <option value="Causal Leave Probation">Casual Leave Probation</option>
                            @endif
                            <option value="Loss of Pay">Loss of Pay</option>
                            @if($employeeGender && $employeeGender->gender === 'Female')
                            <option value="Maternity Leave">Maternity Leave</option>
                            @else
                            <option value="Paternity Leave">Paternity Leave</option>
                            @endif
                            <option value="Sick Leave">Sick Leave</option>
                            <option value="Causal Leave">Casual Leave</option>
                            <option value="Marriage Leave">Marriage Leave</option>
                    </select>
                    <span style="position: absolute; top: 75%; right: 52%; transform: translateY(-50%);font-size:12px;color:#c8cfd6;">▼</span>
                    <div style="position: absolute; top: 100%; left: 0; width: 100%;">
                        @error('leave_type') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="form-group col-md-4 m-0 p-0">
                    <div class="pay-bal">
                        <span style=" font-size: 12px; font-weight: 500;color:#778899;">Balance :</span>
                        @if(!empty($this->leaveBalances))
                        <div style="flex-direction:row; display: flex; align-items: center;justify-content:center;cursor:pointer;">
                            @if($this->leave_type == 'Sick Leave')
                            <!-- Sick Leave -->
                            <div style="width: 20px; height: 20px; border-radius: 50%; background-color: #e6e6fa; display: flex; align-items: center; justify-content: center; ">
                                <span style="font-size: 10px; color: #50327c;font-weight:500;">SL</span>
                            </div>
                            <span style="font-size: 11px; font-weight: 500; color: #50327c; margin-left: 5px;" title="Sick Leave">{{ $this->leaveBalances['sickLeaveBalance'] }}</span>
                            @elseif($this->leave_type == 'Causal Leave')
                            <!-- Casual Leave -->
                            <div style="width: 20px; height: 20px; border-radius: 50%; background-color: #e7fae7; display: flex; align-items: center; justify-content: center; ">
                                <span style="font-size: 10px; color: #1d421e;font-weight:500;">CL</span>
                            </div>
                            <span style="font-size: 11px; font-weight: 500; color: #1d421e; margin-left: 5px;" title="Causal Leave">{{ $this->leaveBalances['casualLeaveBalance'] }}</span>
                            @elseif($this->leave_type == 'Causal Leave Probation')
                            <!-- Casual Leave Probation -->
                            <div style="width: 20px; height: 20px; border-radius: 50%; background-color: #fff6e5; display: flex; align-items: center; justify-content: center; ">
                                <span style="font-size: 9px; color: #e59400;font-weight:500;" title="Causal Leave Probation">CLP</span>
                            </div>
                            <span style="font-size: 11px; font-weight: 500; color: #1d421e; margin-left: 5px;">{{ $this->leaveBalances['casualProbationLeaveBalance'] }}</span>
                            @elseif($this->leave_type == 'Loss of Pay')
                            <!-- Loss of Pay -->
                            <div style="width: 20px; height: 20px; border-radius: 50%; background-color: #ffebeb; display: flex; align-items: center; justify-content: center; ">
                                <span style="font-size: 10px; color: #890000;font-weight:500;" title="Loss of Pay">LP</span>
                            </div>
                            <span style="font-size: 11px; font-weight: 500; color: #890000; margin-left: 5px;">{{ $this->leaveBalances['lossOfPayBalance'] }}</span>
                            @elseif($this->leave_type == 'Maternity Leave')
                            <!-- Loss of Pay -->
                            <div style="width: 20px; height: 20px; border-radius: 50%; background-color: #ffebeb; display: flex; align-items: center; justify-content: center; ">
                                <span style="font-size: 10px; color: #890000;font-weight:500;" title="Maternity Leave">ML</span>
                            </div>
                            <span style="font-size: 11px; font-weight: 500; color: #890000; margin-left: 5px;">{{ $this->leaveBalances['maternityLeaveBalance'] }}</span>
                            @elseif($this->leave_type == 'Paternity Leave')
                            <!-- Loss of Pay -->
                            <div style="width: 20px; height: 20px; border-radius: 50%; background-color: #ffebeb; display: flex; align-items: center; justify-content: center; ">
                                <span style="font-size: 10px; color: #890000;font-weight:500;" title="Paternity Leave">PL</span>
                            </div>
                            <span style="font-size: 11px; font-weight: 500; color: #890000; margin-left: 5px;">{{ $this->leaveBalances['paternityLeaveBalance'] }}</span>
                            @elseif($this->leave_type == 'Marriage Leave')
                            <!-- Loss of Pay -->
                            <div style="width: 20px; height: 20px; border-radius: 50%; background-color: #ffebeb; display: flex; align-items: center; justify-content: center; ">
                                <span style="font-size: 10px; color: #890000;font-weight:500;" title="Marriage Leave">MRL</span>
                            </div>
                            <span style="font-size: 11px; font-weight: 500; color: #890000; margin-left: 5px;">{{ $this->leaveBalances['marriageLeaveBalance'] }}</span>
                            @endif
                        </div>
                        @endif

                    </div>
                    <div class="form-group">
                        <label for="numberOfDays" style="color: #778899; font-size: 12px; font-weight: 500;">Number of Days :</label>
                        @if($showNumberOfDays)
                        <span id="numberOfDays" style="font-size: 12px;color:#778899;">
                            <!-- Display the calculated number of days -->
                            {{ $this->calculateNumberOfDays($from_date, $from_session, $to_date, $to_session) }}
                        </span>
                        <!-- Add a condition to check if the number of days exceeds the leave balance -->
                        @if(!empty($this->leaveBalances))
                        <!-- Directly access the leave balance for the selected leave type -->
                        @php
                        $calculatedNumberOfDays = $this->calculateNumberOfDays($from_date, $from_session, $to_date, $to_session);
                        @endphp
                        @if($this->leave_type == 'Causal Leave Probation')
                        <!-- Casual Leave Probation -->
                        @if($calculatedNumberOfDays > $this->leaveBalances['casualProbationLeaveBalance'])
                        <!-- Display an error message if the number of days exceeds the leave balance -->
                        <div class="error-message" style="position: absolute;  left: 0;">
                            <span style="color: red; font-weight: normal;font-size:12px;">Insufficient leave balance</span>
                        </div>
                        @php
                        $insufficientBalance = true; @endphp
                        @else
                        <span></span>
                        @endif

                        @elseif($this->leave_type == 'Causal Leave')
                        <!-- Casual Leave Probation -->
                        @if($calculatedNumberOfDays > $this->leaveBalances['casualLeaveBalance'])
                        <!-- Display an error message if the number of days exceeds the leave balance -->
                        <div class="error-message" style="position: absolute;  left: 0;">
                            <span style="color: red; font-weight: normal;font-size:12px;">Insufficient leave balance</span>
                        </div>
                        @php
                        $insufficientBalance = true; @endphp
                        @else
                        <span></span>
                        @endif
                        @elseif($this->leave_type == 'Sick Leave')
                        <!-- Casual Leave Probation -->
                        @if($calculatedNumberOfDays > $this->leaveBalances['sickLeaveBalance'])
                        <!-- Display an error message if the number of days exceeds the leave balance -->
                        <div class="error-message" style="position: absolute;  left: 0;">
                            <span style="color: red; font-weight: normal;font-size:12px;">Insufficient leave balance</span>
                        </div>
                        @php
                        $insufficientBalance = true; @endphp
                        @else
                        <span></span>
                        @endif
                        @elseif($this->leave_type == 'Maternity Leave')
                        <!-- Casual Leave Probation -->
                        @if($calculatedNumberOfDays > $this->leaveBalances['maternityLeaveBalance'])
                        <!-- Display an error message if the number of days exceeds the leave balance -->
                        <div class="error-message" style="position: absolute;  left: 0;">
                            <span style="color: red; font-weight: normal;font-size:12px;">Insufficient leave balance</span>
                        </div>
                        @php
                        $insufficientBalance = true; @endphp
                        @else
                        <span></span>
                        @endif
                        @elseif($this->leave_type == 'Paternity Leave')
                        <!-- Casual Leave Probation -->
                        @if($calculatedNumberOfDays > $this->leaveBalances['paternityLeaveBalance'])
                        <!-- Display an error message if the number of days exceeds the leave balance -->
                        <div class="error-message" style="position: absolute;  left: 0;">
                            <span style="color: red; font-weight: normal;font-size:12px;">Insufficient leave balance</span>
                        </div>
                        @php
                        $insufficientBalance = true; @endphp
                        @else
                        <span></span>
                        @endif
                        @elseif($this->leave_type == 'Marriage Leave')
                        <!-- Casual Leave Probation -->
                        @if($calculatedNumberOfDays > $this->leaveBalances['marriageLeaveBalance'])
                        <!-- Display an error message if the number of days exceeds the leave balance -->
                        <div class="error-message" style="position: absolute;  left: 0;">
                            <span style="color: red; font-weight: normal;font-size:12px;">Insufficient leave balance</span>
                        </div>
                        @php
                        $insufficientBalance = true; @endphp
                        @else
                        <span></span>
                        @endif
                        <!-- end of leavevtyopes -->
                        @endif
                        @endif
                        @else
                        0
                        @endif
                    </div>

                </div>
            </div>
            <div class="form-row d-flex mt-3">
                <div class="form-group col-md-6">
                    <label for="fromDate" style="color: #778899; font-size: 12px; font-weight: 500;">From date</label>
                    <input type="date" wire:model="from_date" wire:change="saveLeaveApplication" class="form-control placeholder-small" id="fromDate" name="fromDate" style="color: #778899;font-size:12px;" wire:change="handleFieldUpdate('from_date')">
                    @error('from_date') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group col-md-6" style="position: relative;">
                    <label for="session" style="color: #778899; font-size: 12px; font-weight: 500;">Sessions</label>
                    <select class="form-control placeholder-small" wire:model="from_session" wire:change="saveLeaveApplication" id="session" name="session" style="font-size:12px;" wire:change="handleFieldUpdate('from_session')">
                        <option value="default">Select session</option>
                        <option value="Session 1">Session 1</option>
                        <option value="Session 2">Session 2</option>
                    </select>
                    <span style="position: absolute; top: 75%; right: 5%; transform: translateY(-50%);font-size:12px;color:#c8cfd6;">▼</span>
                    @error('from_session') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-row d-flex  mt-3">
                <div class="form-group col-md-6">
                    <label for="toDate" style="color: #778899; font-size: 12px; font-weight: 500;">To date</label>
                    <input type="date" wire:model="to_date" wire:change="saveLeaveApplication" class="form-control placeholder-small" id="toDate" name="toDate" style="color: #778899;font-size:12px;" wire:change="handleFieldUpdate('to_date')">
                    @error('to_date') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="form-group col-md-6" style="position: relative;">
                    <label for="session" style="color: #778899; font-size: 12px; font-weight: 500;">Sessions</label>
                    <select class="form-control placeholder-small" wire:model="to_session" wire:change="saveLeaveApplication" id="session" name="session" style="font-size:12px;" wire:change="handleFieldUpdate('to_session')">
                        <option value="default">Select session</option>
                        <option value="Session 1">Session 1</option>
                        <option value="Session 2">Session 2</option>
                    </select>
                    <span style="position: absolute; top: 75%; right: 5%; transform: translateY(-50%);font-size:12px;color:#c8cfd6; @if($errors->has('to_session')) display: none; @endif">
                        ▼
                    </span>
                    @error('to_session')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

            </div>

            <div>
                @if($showApplyingTo)
                <div class="form-group" style="margin-top: 10px;">
                    <div style="display:flex; flex-direction:row;">
                        <label for="applyingToText" id="applyingToText" name="applyingTo" style="color: #778899; font-size: 12px; font-weight: 500; cursor: pointer;">
                            <img src="https://t4.ftcdn.net/jpg/05/35/51/31/360_F_535513106_hwSrSN1TLzoqdfjWpv1zWQR9Y5lCen6q.jpg" alt="" width="35px" height="32px" style="border-radius:50%;color:#778899;">
                            Applying to
                        </label>
                    </div>
                </div>
                @endif
                @if($show_reporting)
                <div class="reporting" wire:ignore.self>
                    @empty($loginEmpManagerProfile)
                    <div class="employee-profile-image-container">
                        <img src="https://th.bing.com/th/id/OIP.Ii15573m21uyos5SZQTdrAHaHa?rs=1&pid=ImgDetMain" class="employee-profile-image-placeholder" style="border-radius:50%;" height="40" width="40" alt="Default Image">
                    </div>
                    @else
                    <div class="employee-profile-image-container">
                        <img height="40" width="40" src="{{ asset('storage/' . $loginEmpManagerProfile) }}" style="border-radius:50%;">
                    </div>
                    @endif
                    <div class="center p-0 m-0">
                        @empty($loginEmpManager)
                        <p style="font-size:10px;margin-bottom:0;">N/A</p>
                        @else
                        <p id="reportToText" class="ellipsis" style="font-size:12px; text-transform: capitalize;padding:0;margin:0;">{{$loginEmpManager}}</p>
                        @endempty

                        @empty($loginEmpManagerId)
                        <p style="font-size:10px;margin-bottom:0;">#(N/A)</p>
                        @else
                        <p style="color:#778899; font-size:10px;margin-bottom:0;" id="managerIdText"><span class="remaining">#{{$loginEmpManagerId}}</span></p>
                        @endempty
                    </div>
                    <div class="downArrow" onclick="toggleSearchContainer()">
                        <i class="fas fa-chevron-down" style=" cursor:pointer"></i>
                    </div>
                </div>
                @endif
                <div class="searchContainer" style="display:none;">
                    <!-- Content for the search container -->
                    <div class="row" style="padding: 0 15px; margin-bottom: 10px;">
                        <div class="col" style="margin: 0px; padding: 0px">
                            <div class="input-group">
                                <input style="font-size: 12px; border-radius: 5px 0 0 5px; cursor: pointer; width:50%;" type="text" class="form-control placeholder-small" placeholder="Search for Emp.Name or ID" aria-label="Search" aria-describedby="basic-addon1">
                                <div class="input-group-append">
                                    <button wire:click="handleSearch('employees')" style="height: 29px; border-radius: 0 5px 5px 0; background-color: #007BFF; color: #fff; border: none; align-items: center; display: flex;" class="btn" type="button">
                                        <i style="margin-right: 5px;" class="fa fa-search"></i> <!-- Adjust margin-right as needed -->
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @foreach($managerFullName as $employee)
                    <div style="display:flex; gap:10px;align-items:center;" onclick="updateApplyingTo('{{ $employee['full_name'] }}', '{{ $employee['emp_id'] }}')">
                        <div>
                            <input type="checkbox" wire:model="selectedManager" value="{{ $employee['emp_id'] }}">
                        </div>
                        @if($employee['image'])
                        <div class="employee-profile-image-container">
                            <img height="35px" width="35px" src="{{ asset('storage/' . $employee['image']) }}" style="border-radius:50%;">
                        </div>
                        @else
                        <div class="employee-profile-image-container">
                            <img src="https://th.bing.com/th/id/OIP.Ii15573m21uyos5SZQTdrAHaHa?rs=1&pid=ImgDetMain" class="employee-profile-image-placeholder" style="border-radius:50%;" height="35px" width="35px" alt="Default Image">
                        </div>
                        @endif
                        <div class="center mt-2 mb-2">
                            <p style=" font-size:12px; font-weight:500;margin-bottom:0;" value="{{ $employee['full_name'] }}">{{ $employee['full_name'] }}</p>
                            <p style="color:#778899; font-size:10px;margin-bottom:0;" value="{{ $employee['full_name'] }}"> #{{ $employee['emp_id'] }} </p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @error('applying_to') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="ccToText" wire:model="from_date" id="applyingToText" name="applyingTo" style="color: #778899; font-size: 12px; font-weight: 500;">
                    CC to
                </label>
                <div class="control-wrapper" style="display: flex; flex-direction: row; gap: 10px;">
                    <a href="javascript:void(0);" class="text-3 text-secondary control" aria-haspopup="true" wire:click="openCcRecipientsContainer" style="text-decoration: none;">
                        <div class="icon-container" wire:click="handleSearch('ccRecipients')" style="display: flex; justify-content: center; align-items: center;">
                            <i class="fa-solid fa-plus" style="color: #778899;"></i>
                        </div>

                    </a>
                    <span class="text-2 text-secondary placeholder" id="ccPlaceholder" style="margin-top: 5px; background: transparent; color: #ccc;">Add</span>

                    <div id="addedEmails" class="emails" style="display: flex; gap: 10px; "></div>

                </div>
                @if($showCcRecipents)
                <div class="ccContainer" x-data="{ open: @entangle('showCcRecipents') }" x-cloak @click.away="open = false" style="max-height: 230px; overflow-y: auto;">
                    <!-- Content for the search container -->
                    <div class="row" style="padding: 0 ; margin:0;">
                        <div class="col-md-10" style="margin: 0px; padding: 0px">
                            <div class="input-group">
                                <input wire:model.debounce.500ms="searchTerm" id="searchInput" style="font-size: 10px; border-radius: 5px 0 0 5px; cursor: pointer; width:50%;" type="text" class="form-control placeholder-small" placeholder="Search for Emp.Name or ID" aria-label="Search" aria-describedby="basic-addon1">
                                    <div class="input-group-append">
                                        <button type="button" wire:click="searchCCRecipients" style="height: 29px; border-radius: 0 5px 5px 0; background-color: #007BFF; color: #fff; border: none; align-items: center; display: flex;" class="btn">
                                            <i style="margin-right: 5px;" class="fa fa-search"></i> <!-- Adjust margin-right as needed -->
                                        </button>
                                    </div>
                            </div>
                        </div>
                        <div class="col-md-2 m-0 p-0">
                            <button wire:click="closeCcRecipientsContainer" type="button" class="close rounded px-1 py-0" aria-label="Close" style="background-color: #333;">
                                <span aria-hidden="true" style="color: white; font-size: 24px;">×</span>
                            </button>
                        </div>
                    </div>
                    @foreach($ccRecipients as $employee)
                    <!-- Display each employee -->
                    <div>
                        <div style="margin-top:10px;display:flex; gap:10px; text-transform: capitalize;align-items:center;" onclick="addEmail('{{ ucwords(strtolower($employee['full_name'])) }}')">
                            <input type="checkbox" wire:model="selectedPeople" value="{{ $employee['emp_id'] }}">
                            @if($employee['image'])
                            <div class="employee-profile-image-container">
                                <img height="35px" width="35px" src="{{ asset('storage/' . $employee['image']) }}" style="border-radius:50%;">
                            </div>
                            @else
                            <div class="employee-profile-image-container">
                                <img src="https://th.bing.com/th/id/OIP.Ii15573m21uyos5SZQTdrAHaHa?rs=1&pid=ImgDetMain" class="employee-profile-image-placeholder" style="border-radius:50%;" height="35px" width="35px" alt="Default Image">
                            </div>
                            @endif
                            <div class="center mb-2 mt-2">
                                <p style="font-size: 12px; font-weight: 500; text-transform: capitalize; margin-bottom:0;">{{ ucwords(strtolower($employee['full_name'])) }}</p>
                                <p style=" color: #778899; font-size: 0.69rem;margin-bottom:0;">#{{ $employee['emp_id'] }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
                @error('cc_to') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="contactDetails" style="color: #778899; font-size: 12px; font-weight: 500;">Contact Details</label>
                <input type="text" wire:model="contact_details" wire:change="saveLeaveApplication" class="form-control placeholder-small" id="contactDetails" name="contactDetails" value="contact_details"  style="color: #778899;width:50%;">
                @error('contact_details') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="reason" style="color: #778899; font-size: 12px; font-weight: 500;">Reason</label>
                <textarea class="form-control" wire:model="reason" id="reason" wire:change="saveLeaveApplication" name="reason" placeholder="Enter a reason" rows="4" style="font-size:12px;color: #778899;"></textarea>
                @error('reason') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <input type="file" wire:model="files" wire:loading.attr="disabled" style="font-size: 12px;color:#778899;" multiple />
                @error('file_paths') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="buttons1">
                <button type="submit" class=" submit-btn"  @if(isset($insufficientBalance)) disabled @endif>Submit</button>
                <button type="button" class=" cancel-btn" wire:click="cancelLeaveApplication" style="border:1px solid rgb(2, 17, 79);">Cancel</button>
            </div>
        </form>
    </div>
    <script>

        // Define a variable to track the visibility state of the search container
        let searchContainerVisible = false;

        function toggleSearchContainer() {
            const searchContainer = document.querySelector('.searchContainer');
            const reportingContainer = document.querySelector('.reporting');

            // Toggle the display of the search container
            searchContainer.style.display = searchContainerVisible ? 'none' : 'block';
            reportingContainer.classList.toggle('active', searchContainerVisible);

            // Update the visibility state
            searchContainerVisible = !searchContainerVisible;
        }

        // Function to handle form submission
        function handleFormSubmission() {
            // You may have additional logic here related to form submission

            // Ensure that the search container remains visible after form submission
            if (!searchContainerVisible) {
                toggleSearchContainer();
            }

        }



        function addEmail(fullName) {
            const addedEmails = document.getElementById('addedEmails');
            const addSpan = document.getElementById('ccPlaceholder');

            // Split the full name into first and last names
            const names = fullName.split(' ');

            // Get the first letter of the first name
            const firstNameAbbreviation = names.length > 0 ? names[0].charAt(0) : '';

            // Get the first letter of the last name
            const lastNameAbbreviation = names.length > 1 ? names[names.length - 1].charAt(0) : '';

            // Combine the first letters of both names to create the email abbreviation
            const emailAbbreviation = firstNameAbbreviation + lastNameAbbreviation;

            // Check if the email abbreviation is already added
            if (isEmailAlreadyAdded(emailAbbreviation)) {
                return; // Do nothing if the email is already added
            }

            // Create a new element to display the added email abbreviation
            const emailElement = document.createElement('div');
            emailElement.textContent = emailAbbreviation;
            emailElement.className = 'added-email';
            emailElement.style.border = '2px solid #778899';
            emailElement.style.color = '#778899';
            emailElement.style.borderRadius = '50%';

            // Add hover effect
            emailElement.addEventListener('mouseover', function() {
                emailElement.style.cursor = 'pointer';
                emailElement.innerHTML = '&#9587;'; // Change the color to black
            });

            emailElement.addEventListener('mouseout', function() {
                emailElement.innerHTML = emailAbbreviation; // Restore the email abbreviation on mouseout
            });

            // Remove on click
            emailElement.addEventListener('click', function() {
                emailElement.remove();
                removeAddedEmail(emailAbbreviation); // Remove from the list of added emails
                if (addedEmails.children.length === 0) {
                    addSpan.style.display = 'block';
                }
            });


            // Append the email element to the addedEmails container
            addedEmails.appendChild(emailElement);
            addSpan.style.display = 'none';
            // Add the email to the list of added emails
            addedEmailList.push(emailAbbreviation);

            //unchecking
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            // console.log(this.checked);
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const {
                        checked
                    } = checkbox;
                    if (!checked) {
                        emailElement.remove();
                        removeAddedEmail?.(emailAbbreviation); // Remove from the list of added emails
                        addedEmails.children.length === 0 && (addSpan.style.display = 'block');
                    }
                });
            });
        }

        // Array to keep track of added emails
        const addedEmailList = [];

        // Function to check if an email is already added
        function isEmailAlreadyAdded(email) {
            return addedEmailList.includes(email);
        }

        // Function to remove an email from the list of added emails
        function removeAddedEmail(email) {
            const index = addedEmailList.indexOf(email);
            if (index !== -1) {
                addedEmailList.splice(index, 1);
            }
        }

        function updateApplyingTo(reportTo, managerId) {
            // Update the values in the reporting container
            document.getElementById('reportToText').innerText = reportTo;
            document.getElementById('managerIdText').innerText = '#' + managerId;

            // Optionally, you can also hide the search container here
            toggleSearchContainer();
        }


        function toggleDetails(tabId) {
            const tabs = ['leaveApply', 'restricted-content', 'leaveCancel-content', 'compOff-content'];

            tabs.forEach(tab => {
                const tabElement = document.getElementById(tab);
                if (tab === tabId) {
                    tabElement.style.display = 'block';
                } else {
                    tabElement.style.display = 'none';
                }
            });
        }
    </script>
    </body>
</div>