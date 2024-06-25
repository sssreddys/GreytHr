<div>
    <style>
    .button-container {
        display: flex;
        justify-content: center;
        /* margin-right: 250px; */
        /* Adjust as needed for alignment */
    }

    .rotate-arrow {
        transform: rotate(90deg);
        transition: transform 0.3s ease;
        /* Add a smooth transition effect */
    }
    .activeBtn {
        color: #fff !important;
        background-color: #02114f !important;
    }

    .my-button {
        margin: 0px;
        background-color: #FFFFFF;
        border: 1px solid #a3b2c7;
        /* font-size: 20px;
        height: 50px; */
        padding: 10px 20px;
        /* Adjust as needed for spacing */
    }

    .mother-box {
        border: 1px solid #ccc;
        /* Add a 1px solid gray border */
        padding: 20px;
        /* Add some padding for better visual appearance */
        display: flex;
        /* Add this to center the content */
        align-items: center;
        /* Align the content vertically */
    }

    .apply-button {
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
    }

    .horizontal-line-attendance-info {
        width: 100%;
        /* Set the width to the desired value */
        border-top: 1px solid #000;
        /* You can adjust the color and thickness */
        margin: 0px 0;
        /* Adjust the margin as needed */
    }

    .horizontal-line1-attendance-info {
        width: 100%;
        /* Set the width to the desired value */
        border-top: 1px solid #000;
        /* You can adjust the color and thickness */
        margin: 0px 0;
        /* Adjust the margin as needed */
    }


    .history-button {
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
    }

    .apply-button {
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
        transition: border-color 0.3s, color 0.3s;
        /* Smooth transition effect */
    }

    .apply-button:hover {
        border-color: rgb(2, 17, 79);
        /* Change the border color to green on hover */
        color: rgb(2, 17, 79);
        /* Change the text color to green on hover */
    }

    .pending-button:hover {
        border-color: rgb(2, 17, 79);
        /* Change the border color to green on hover */
        color: rgb(2, 17, 79);
        /* Change the text color to green on hover */
    }

    .history-button:hover {
        border-color: rgb(2, 17, 79);
        /* Change the border color to green on hover */
        color: rgb(2, 17, 79);
        /* Change the text color to green on hover */
    }

    .apply-button:active {
        background-color: rgb(2, 17, 79);
        /* Change background color to green when clicked */
        color: #FFFFFF;
        border-color: rgb(2, 17, 79);
        /* Change text color to white when clicked */
    }

    .pending-button:active {
        background-color: rgb(2, 17, 79);
        /* Change background color to green when clicked */
        color: #FFFFFF;
        border-color: rgb(2, 17, 79);
        /* Change text color to white when clicked */
    }

    .history-button:active {
        background-color: rgb(2, 17, 79);
        /* Change background color to green when clicked */
        color: #FFFFFF;
        border-color: rgb(2, 17, 79);
        /* Change text color to white when clicked */
    }

    .calendar {
        max-width: 300px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.2);
        background-color: #ffff;
        margin-right: 20px;
        /* Add spacing between calendar and content */
    }

    /* Styles for the calendar header */
    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: white;
        padding: 8px;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
    }

    /* Styles for the navigation buttons */

    #prevMonth,
    #nextMonth {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 14px;
        /* Reduce font size */
        padding: 2px 5px;
        /* Reduce padding */
    }

    #currentMonth {
        font-size: 18px;
    }



    .calendar-weekdays {
        display: flex;
        justify-content: space-between;
        background-color: white;
        color: #7f8fa4;
        text-transform: uppercase;
        padding: 3px 0;
        /* Adjust the padding */
        border-top: 1px solid #ccc;
        border-bottom: 1px solid #ccc;
    }

    .approve-button {
        background-color: rgb(2, 17, 79);
        color: white;
        /* White text */
        padding: 10px 20px;
        /* Padding around the text */
        border: none;
        /* No border */
        cursor: pointer;
        /* Cursor style on hover */
        border-radius: 5px;
        /* Rounded corners */
    }

    .reject-button {
        background-color: rgb(2, 17, 79);
        color: white;
        /* White text */
        padding: 10px 20px;
        /* Padding around the text */
        border: none;
        /* No border */
        cursor: pointer;
        /* Cursor style on hover */
        border-radius: 5px;
        /* Rounded corners */
    }

    .weekday {
        text-align: center;
        flex: 1;
        /* padding: 5px; */
        font-weight: 600;
        font-size: 10px;
        margin: 0;
        /* Reset margin */
    }

    /* Style for the Submit button */
    #submitButton {
        background-color: rgb(2, 17, 79);
        color: white;
        /* White text */
        padding: 10px 20px;
        /* Padding around the text */
        border: none;
        /* No border */
        cursor: pointer;
        /* Cursor style on hover */
        border-radius: 5px;
        /* Rounded corners */
    }

    /* Style for the Cancel button */
    #cancelButton {
        /* Red background */
        color: rgb(2, 17, 79);
        /* White text */
        padding: 10px 20px;
        /* Padding around the text */
        border: none;
        /* No border */
        cursor: pointer;
        /* Cursor style on hover */
        border-radius: 5px;
        /* Rounded corners */
        margin-left: 10px;
        /* Add some spacing between buttons */
    }

    /* Hover effect for buttons */
    #submitButton:hover,
    #cancelButton:hover {
        opacity: 0.8;
        /* Reduced opacity on hover */
    }

    /* Styles for the calendar dates */
    .calendar-dates {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 6px;
        padding: 5px;
        justify-items: center;
        /* Center the date elements both horizontally and vertically */
    }

    .accordion {

        border: 0.0625rem solid #ccc;

        margin-bottom: 0.625rem;

        width: 100%;

        margin: 0 auto;

    }

    .container1 {
        width: 600px;
        height: 200px;
        /* margin-right: 300px; */
        background-color: #FFFFFF;
        margin-top: 15px;
        /* margin-top: 420px; */
        border-radius: 10px;
        /* float: right; */
        border: 1px solid #ccc;
        display: block;
    }

    .accordion:hover {

        border: 0.0625rem solid #3a9efd;

    }

    .accordion-heading {

        background-color: white;

        cursor: pointer;

    }



    .accordion-body {

        display: none;

        background-color: #fff;

    }



    .accordion-content {

        display: flex;

        flex-direction: column;

        align-items: center;

    }

    .calendar-box {

        /* margin-left: 50px;
        margin-top: 120px; */
        background-color: white;
        border-radius: 10px;
    }

    .nav1-button {
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 5px 10px;
        cursor: pointer;
        font-size: 14px;
    }

    .nav1-text {
        margin: 0 10px;
        font-weight: bold;
        font-size: 16px;
    }

    #infoBox {
        display: none;
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        text-align: center;
        position: absolute;
        margin-top: 230px;
        left: 56%;
        height: 320px;
        width: 400px;
        transform: translate(-50%, -50%);
        z-index: 999;
        /* Ensure it appears on top of other content */
    }

    .calendar-date-info {
        margin: 10px 0;
        font-size: 18px;
        font-weight: 400;
    }

    #selectedDate {
        font-size: 24px;
        font-weight: bold;
        color: #007bff;
    }

    input[type="text"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-top: 10px;
    }


    .hidden-box {
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        /* margin-top: 300px; */
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        text-align: center;
        /* position: absolute;
        top: 80%;
        left: 67%;
        height: 400px;
        width: 600px; */
        /* transform: translate(-50%, -50%); */
    }

    .apply-box {
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        /* margin-top: 300px; */
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        text-align: center;
        /* position: absolute;
        top: 80%;
        left: 67%;
        height: 400px;
        width: 600px; */
        /* transform: translate(-50%, -50%); */
    }

    .history:hover {
        border: 1px solid rgb(2, 17, 79);

    }

    .hidden-pending-box {

        background-color: #fff;
        margin-top: 230px;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        text-align: center;
        margin-left: 550px;
        position: absolute;
        height: 320px;
        width: 900px;
        transform: translate(-50%, -50%);
    }

    .history-box {

        background-color: #fff;
        margin-top: 250px;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
        text-align: center;
        margin-left: -100px;
        position: absolute;
        height: 320px;
        width: 900px;
        transform: translate(-50%, -50%);
    }

    .hidden-pending-box1 {
        display: none;

        margin-top: 300px;
        padding: 20px;
        border-radius: 5px;

        text-align: center;
        margin-left: 30px;
        position: absolute;
        top: 50%;
        left: 50%;
        height: 320px;
        width: 900px;
        transform: translate(-50%, -50%);
    }

    .calendar-date:hover::before {

        font-size: 20px;
        display: block;
        text-align: center;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }


    /* Styles for the selected option container */
    .custom-dropdown {
        width: 300px;
        position: relative;
    }

    /* Styles for the selected option container */
    .selected-option {
        display: flex;
        align-items: center;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        cursor: pointer;
    }

    /* Styles for the manager image */
    .manager-image {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        margin-right: 10px;
    }

    /* Styles for the dropdown options */
    .dropdown-options {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        background: #fff;
        border: 1px solid #ccc;
        border-top: none;
        border-radius: 0 0 5px 5px;
        z-index: 1;
    }

    .horizontal-line {
        position: absolute;
        width: 942px;
        margin-left: -10px;
        /* Make the line stretch across the container */
        height: 1px;
        /* Adjust the line thickness as needed */
        background-color: #7f8fa4;
        /* Line color */
        margin-top: 10px;
        /* Position the line vertically at the middle */
        transform: translateY(-50%);
        /* Adjust for vertical alignment */
    }

    .horizontal-line1 {
        position: absolute;
        width: 500px;
        margin-left: -10px;
        /* Make the line stretch across the container */
        height: 1px;
        /* Adjust the line thickness as needed */
        background-color: #7f8fa4;
        /* Line color */
        margin-top: 10px;
        /* Position the line vertically at the middle */
        transform: translateY(-50%);
    }

    .horizontal-line2 {
        position: absolute;
        width: 859px;
        margin-left: -10px;
        /* Make the line stretch across the container */
        height: 1px;
        /* Adjust the line thickness as needed */
        background-color: #7f8fa4;
        /* Line color */
        margin-top: 10px;
        /* Position the line vertically at the middle */
        transform: translateY(-50%);
    }

    /* Show the options when the selected option is clicked */
    .custom-dropdown.open .dropdown-options {
        display: block;
    }

    .history {
        border-radius: 5px;
    }

    .history:hover {
        border: 1px solid rgb(2, 17, 79);
    }

    .container-body1:hover {
        border: 1px solid rgb(2, 17, 79);
    }

    .hidden-history-box {


        margin-top: 280px;
        padding: 20px;
        border-radius: 5px;

        text-align: center;
        margin-left: 30px;
        position: absolute;
        top: 50%;
        left: 50%;
        height: 320px;
        width: 900px;
        transform: translate(-50%, -50%);
    }


    .my-button.active-button {
        background-color: rgb(2, 17, 79);
        color: #FFFFFF;
        border-color: rgb(2, 17, 79);
    }

    .calendar-date-info table {
        border-collapse: separate;
        border-spacing: 10px;
        /* Adjust the spacing as needed */
    }

    table {
        border-collapse: collapse;
        width: 75%;
    }

    th,
    td {
        border: 1px solid black;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
        border: none;
        /* Remove border for table headers */
    }


    /* Style for table header cells (th) */
    .calendar-date-info th {
        padding: 8px;
        /* Adjust the padding as needed */
        background-color: #f2f2f2;
        margin-left: -30px;
    }

    /* Restyle the active button on hover */
    .my-button.active-button:hover {
        background-color: rgb(2, 17, 79);
        color: #FFFFFF;
        border-color: rgb(2, 17, 79);
    }

    .calendar-footer {
        text-align: center;
        background: rgb(2, 17, 79);
        /* Blue background color */
        color: #fff;
        /* White text color */
        padding: 8px;
        border-bottom-left-radius: 5px;
        border-bottom-right-radius: 5px;
    }

    .arrow-button::after {
        content: "\25B6";
        /* Unicode character for right-pointing triangle (arrow) */
        font-size: 18px;

    }

    .custom-view-details-button {
        background-color: transparent;
        /* Add other styles as needed */
    }

    #closeButton {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: transparent;
        border: none;
        font-size: 24px;
        cursor: pointer;
    }

    .current-date {
        background-color: #007bff;
        color: #fff;
        border-radius: 50%;
        /* Make it circular */
        /* padding: 3px 3px; */
        /* Adjust padding to center the text vertically */
        text-align: center;
        width: 30px;
        /* Add a fixed width to ensure a circular shape */
        height: 30px;
        /* Add a fixed height to ensure a circular shape */
        line-height: 30px;
        /* Center the text vertically */
        font-size: 14px;
        /* Adjust font size as needed */
    }

  

    /* Calendar Styles */
    /* Apply gap between table header cells */
    thead th {
        padding-left: -40px;
        /* Adjust the horizontal padding as needed */
    }

    /* Apply gap between "To" and "Reason" */
    thead th:nth-child(2) {
        padding-left: 40px;
        /* Adjust the right padding for the second header cell */
    }

    .withdraw-button {
        display: inline-block;
        padding: 10px 20px;
        border-radius: 5px;
        /* Adjust the border radius as needed */
        background-color: rgb(2, 17, 79);
        /* Orange background color */
        color: #fff;
        /* White text color */
        text-align: center;
        border: none;
        /* Remove button border */
        cursor: pointer;
        margin-left: -40px;
        /* Add a pointer cursor on hover */
    }

    .hidden-history-box1 {
        width: 350px;
        height: 70px;
        background-color: #ffff;
        text-align: center;
        padding: 10px;
        margin-right: 4px;
        margin-top: 80px;

    }

    .accordion {
        background-color: #eee;
        color: #444;
        cursor: pointer;
        padding: 18px;
        width: 100%;
        border: none;
        text-align: left;
        outline: none;
        font-size: 15px;
        transition: 0.4s;
    }

    .active,
    .accordion:hover {
        background-color: #ccc;
    }

    .panel {
        padding: 0 18px;
        background-color: white;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.2s ease-out;
    }
    .remarks-container {
    width: 300px; /* Adjust the width as needed */
    margin: 0 auto; /* Center the container horizontally */
}

.remarks-container input[type="text"] {
    width: 100%; /* Take up the full width of the container */
    padding: 8px; /* Adjust padding as needed */
    border: 1px solid #ccc; /* Add border */
    border-radius: 4px; /* Add border radius */
    box-sizing: border-box; /* Include padding and border in the width */
}
.calendar-dates {
        display: flex;
        flex-wrap: wrap;
        text-align: center;
    }

    .calendar-week {
        display: flex;
        width: 100%;
        font-weight: bold;
    }
.calendar-date {
        width: calc(100% / 7);
        padding: 10px;
        font-size:12px;
        font-weight: bold;
        box-sizing: border-box;
        cursor: pointer;
    }

    .calendar-date.current-date {
        background-color: #24a7f8;
        color: #fff;
        border-radius: 50%;
        padding:10px;
        height: 30px;
        width:30px;
        display: flex;
        margin: 0 auto;
        align-items: center;
    }

    .calendar-date.selected-date {
        background-color: #f8f8f8;
        border:1px solid #24a7f8;
        padding:10px;
        height: 25px;
        width:2px;
        display: flex;
        margin: 0 auto;
        align-items: center;
    }

    .calendar-date.other-month-date {
        color: #a3b2c7;
        font-weight:bold;
        font-size:12px;
    }
    .calendar-date.after-today {
        color: #a3b2c7;
        font-weight:bold;
        font-size:12px;
    }
    </style>
   
    <div class="button-container">
        <button class="my-button apply-button <?php echo $isApply == 1 ? 'activeBtn' : ''; ?>" wire:click="applyButton">Apply</button>
        <button class="my-button pending-button" wire:click="pendingButton">Pending</button>
        <button class="my-button history-button" wire:click="historyButton">History</button>
    </div>
    @if($isApply==1)

    <div class="h row m-0 mt-4">
        <div class="col-md-5 mb-3">
            <div class="calendar-box">
                <div class="calendar-header">
                    <button class="btn btn-outline-primary p-0" wire:click="previousMonth"><i style="line-height: inherit;" class="fas fa-chevron-left"></i></button>
                    <p style="margin-top:7px; font-weight: 600">
                        {{ \Carbon\Carbon::createFromDate($this->year, $this->month, 1)->format('F Y') }}</p>
                    <button class="btn btn-outline-primary p-0" wire:click="nextMonth"><i style="line-height: inherit;" class="fas fa-chevron-right"></i></button>
                </div>

                <!-- Calendar Weekdays -->
                <div class="calendar-weekdays">
                    <div class="weekday">Sun</div>
                    <div class="weekday">Mon</div>
                    <div class="weekday">Tue</div>
                    <div class="weekday">Wed</div>
                    <div class="weekday">Thu</div>
                    <div class="weekday">Fri</div>
                    <div class="weekday">Sat</div>
                </div>

                <!-- Calendar Dates -->
                <div class="calendar-dates">
                        @foreach($calendar as $week)
                        <div class="calendar-week">
                            @foreach($week as $day)
                                <div class="calendar-date {{ $day['isCurrentDate'] ? 'current-date' : '' }} {{ in_array($day['date'], $selectedDates) ? 'selected-date' : '' }}{{ $day['isCurrentMonth'] ? '' : 'other-month-date' }}{{$day['isAfterToday'] ? 'after-today' : '' }}"wire:click="selectDate('{{ $day['date'] }}')">{{ $day['day'] }}</div>
                             @endforeach
                        </div>
                        @endforeach
                </div>
                <div class="calendar-footer"style="text-align: center;
    @if($isdatesApplied)
        background-color: rgb(176, 255, 176);
        color: green; /* Change text color accordingly */
    @else
        background: rgb(2, 17, 79);
        color: #fff;
    @endif
    padding: 8px;">
     
    @if($isdatesApplied==true)  
      <i class="far fa-check-circle"></i>
      All exception days are already applied.
    @else
       No exceptions dates to regularise.  
    @endif  
  </div>
 
    @if(count($selectedDates)>0&&$this->isdatesApplied==0)
    <div style="background-color:white;height:auto;">
        JUMP TO <br>
        @foreach($selectedDates as $s1)
        {{date('d', strtotime($s1))}}&nbsp;&nbsp; {{ date('D', strtotime($s1)) }}<br>
        @endforeach
    </div>    
    @endif

            </div>
        </div>

        <div class="col-md-7 mb-3">
            <div style="display:flex;flex-direction:column;">
                @if(count($selectedDates)>0)
                    <div class="remarks-container">
                           <input type="text" placeholder="Enter Remarks" wire:model="remarks">
                    </div>

                @foreach($regularisationEntries as $index => $regularisationEntry)
                
                <div class="col-md-5">
                    <div class="container1" style="background-color:white;">
                  
                        <!-- Content goes here -->
                        <div class="row m-0">
                            <div class="col-2 pb-1 pt-1 p-0" style="border-right: 1px solid black; text-align: center;">
                                <p class="mb-1" style="font-weight:bold;font-size:20px;">
                                    {{date('d', strtotime($regularisationEntry['date']))}}</p>
                                <p class="text-muted m-0" style="font-weight:600;font-size:14px;">
                                    {{ date('D', strtotime($regularisationEntry['date'])) }}
                                </p>
                            </div>
                            <div class="col-5 pb-1 pt-1">
                                <p class="text-overflow mb-1"
                                    style="overflow: hidden;text-overflow: ellipsis;white-space: nowrap;font-weight: 600;">
                                    10:00 am to 07:00 pm</p>

                            </div>

                        </div>
                        <button type="button" class="btn btn-cross" style="position: absolute; top: 20px; right: -340px;">
                            <i class="fas fa-times"wire:click="deleteStoredArray({{ $index }})"></i>
                        </button>

                        <div class="horizontal-line-attendance-info"></div>


                        <div style=" overflow-x: auto; max-width: 100%;">
                            <table>
                                <thead>
                                    <tr>
                                        <th style="font-weight:normal;font-size:12px;">From</th>
                                        <th style="font-weight:normal;font-size:12px;">To</th>
                                        <th style="font-weight:normal;font-size:12px;">Reason</th>

                                    </tr>
                                </thead>
                                <tbody>


                                    <tr>

                                        <td>
                                            <label style="font-size:12px" for="from">From:</label>
                                            <input type="text" wire:model="regularisationEntries.{{ $index }}.from"
                                                required><br>
                                            @error("regularisationEntries.$index.from") <span
                                                class="error">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td>
                                            <label style="font-size:12px" for="to">To:</label>
                                            <input type="text" wire:model="regularisationEntries.{{ $index }}.to"
                                                required><br>
                                            @error("regularisationEntries.$index.to") <span
                                                class="error">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td>
                                            <label style="font-size:12px" for="reason">Reason:</label>
                                            <input type="text" wire:model="regularisationEntries.{{ $index }}.reason"
                                                required><br>
                                            @error("regularisationEntries.$index.reason") <span
                                                class="error">{{ $message }}</span> @enderror

                                        </td>


                                    </tr>


                                    <!-- Add more rows with dashes as needed -->
                                </tbody>
                                <!-- Add table rows (tbody) and data here if needed -->
                            </table>

                        </div>
                    </div>
                    @endforeach
                       <div style="display:flex;flex-direction:row;margin-left: 50px;margin-top:10px;">
                            <button type="button" wire:click="storearraydates"style="color: #24a7f8;border:1px solid #24a7f8;background: #fff;border-radius:5px;">Submit</button>
                            <a href="/Attendance"style="color:#24a7f8; margin-left: 10px;">Cancel</a>
                       </div>
                </div>
                @else
                <div class="apply-box">
                    <img src="https://gt-linckia.s3.amazonaws.com/static-ess-v6.3.0-prod-144/emptystate_regularisation.svg"
                        style="margin-top:80px;">
                    <p style="color: #7f8fa4;font-weight:400;font-size: 20px;">Smart! Your attendance is sorted.</p>
                    <p style="color: #a3b2c7;font-weight:400;font-size: 16px;margin-top:-20px;">Still want to apply
                        regularization? Select dates(s).</p>
                </div>
                @endif
                @elseif($isPending==1)
                @if(count($pendingRegularisations)>0)

                @foreach($pendingRegularisations as $pr)
                @if ($pr->regularisation_entries!='[]')
                @php
                $regularisationEntries = json_decode($pr->regularisation_entries, true);
                $numberOfEntries = count($regularisationEntries);
                $firstItem = reset($regularisationEntries); // Get the first item
                $lastItem = end($regularisationEntries); // Get the last item
                @endphp



                <div class="accordion-heading rounded" onclick="toggleAccordion(this)" style="margin-top:10px;">

                    <div class="accordion-title p-2 rounded">

                        <!-- Display leave details here based on $leaveRequest -->

                        <div class="col accordion-content">

                            <span style="color: #778899; font-size: 12px; font-weight: 500;">Pending&nbsp;With</span>

                            <span
                                style="color: #36454F; font-size: 12px; font-weight: 500;text-transform:uppercase;">{{ucwords(strtolower($EmployeeDetails->first_name))}}&nbsp;{{ucwords(strtolower($EmployeeDetails->last_name))}}</span>

                        </div>



                        <div class="col accordion-content">

                            <span style="color: #778899; font-size: 12px; font-weight: 500;">No. of Days</span>

                            <span style="color: #36454F; font-size: 12px; font-weight: 500;">

                                {{$numberOfEntries}}

                            </span>

                        </div>


                        <!-- Add other details based on your leave request structure -->

                        <div class="col accordion-content">

                            <span
                                style="margin-top:0.625rem; font-size: 12px; font-weight: 400; color:#cf9b17;text-transform:uppercase;">{{$pr->status}}</span>

                        </div>

                        <div class="arrow-btn">
                            <i class="fa fa-angle-down"></i>
                        </div>

                    </div>

                </div>
                <div class="accordion-body m-0 p-0">

                    <div style="width:100%; height:1px; border-bottom:1px solid #ccc; margin-bottom:10px;"></div>

                    <div class="content px-2">

                        <span style="color: #778899; font-size: 12px; font-weight: 500;">Dates Applied:</span>
                        @if($numberOfEntries>1)
                        <span style="font-size: 11px;">

                            <span style="font-size: 11px; font-weight: 500;"></span>

                            {{ date('(d', strtotime($firstItem['date'])) }} -


                            <span style="font-size: 11px; font-weight: 500;"></span>

                            @if (!empty($lastItem['date']))
                               {{ date('d)', strtotime($lastItem['date'])) }} {{ date('M Y', strtotime($lastItem['date'])) }}
                            @endif

                          
                        </span>
                        @else
                        <span style="font-size: 11px;">

                            <span style="font-size: 11px; font-weight: 500;">
                                {{ date('d', strtotime($lastItem['date'])) }}
                                {{ date('M Y', strtotime($lastItem['date'])) }}
                                <!-- This will retrieve the day -->
                            </span>

                            @endif
                    </div>



                    <div style="width:100%; height:1px; border-bottom:1px solid #ccc; margin-bottom:10px;"></div>

                    <div style="display:flex; flex-direction:row; justify-content:space-between;">

                        <div class="content px-2">

                            <span style="color: #778899; font-size: 12px; font-weight: 500;">Applied on:</span>

                            <span
                                style="color: #333; font-size:12px; font-weight: 500;">{{ date('d M, Y', strtotime($pr->created_at)) }}</span>

                        </div>

                        <div class="content px-2">

                            <a href="{{ route('regularisation-pending', ['id' => $pr->id]) }}">

                                <span style="color: #3a9efd; font-size: 12px; font-weight: 500;">View Details</span>

                            </a>
                            <button class="withdraw mb-2" data-toggle="modal" data-target="#withdrawModal">Withdraw</button>

                        </div>

                    </div>

                </div>
                <div class="modal fade" id="withdrawModal" tabindex="-1" role="dialog" aria-labelledby="withdrawModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                  <div class="modal-header"style="background-color:rgba(163, 178, 199, 0.15);">
                                           <h5 class="modal-title" id="withdrawModalLabel"style="color:#666;font-weight:600;font-size:15px;">Withdraw Request</h5>
                                           <div style="width: 25px; height: 25px; border-radius: 50%; border:2px solid #666; display: flex; justify-content: center; align-items: center; position: relative;">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="border: none; background-color: transparent; position: absolute; left: 2px;margin-left:-16px">
                                                    <span aria-hidden="true"style="font-weight:400;font-size:30px;color:666">&times;</span>
                                                </button>
                                            </div>
                                  </div>
                                  <div class="modal-body">
                                         <p style="font-size:14px;">Are you sure you want to withdraw this application?</p>
                                  </div>
                                  <div class="modal-footer justify-content-center"style="border:none;"> <!-- Centered footer -->
                                     <div>
                                         <button type="button"  class="rejectBtn"wire:click="withdraw({{$pr->id}})"style="width:90px;">Confirm</button> 
                                         <button type="button" class="approveBtn btn-primary" data-dismiss="modal"style="width:90px;">Cancel</button>
                                         
                                     </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                @endif
                @endforeach
                @else
                <div class="hidden-pending-box">
                    <img src="https://gt-linckia.s3.amazonaws.com/static-ess-v6.3.0-prod-144/review-list-empty.svg"
                        style="margin-top:80px;">
                    <p style="color: #a3b2c7;font-weight:400;font-size: 20px;margin-top:20px;">Hey, you have no
                        regularization records to view.</p>
                </div>
                @endif
                @elseif($isHistory==1)
                @if(count($historyRegularisations)>0)
                @foreach($historyRegularisations as $hr)

                @php
                $regularisationEntries = json_decode($hr->regularisation_entries, true);
                $numberOfEntries = count($regularisationEntries);
                $firstEntry = reset($regularisationEntries);
                $lastEntry = end($regularisationEntries);
                @endphp

                @if(($hr->status=='pending'&&$hr->is_withdraw==1)||$hr->status=='approved'||$hr->status=='rejected')
                <div class="accordion-heading rounded" onclick="toggleAccordion(this)" style="margin-top:10px;">

                    <div class="accordion-title p-2 rounded">

                        <!-- Display leave details here based on $leaveRequest -->

                        <div class="accordion-content">
                            @if($hr->status=='pending')
                            <span style="color: #778899; font-size: 12px; font-weight: 500;">Withdrawn&nbsp;By</span>
                            @elseif($hr->status=='rejected')
                            <span style="color: #778899; font-size: 12px; font-weight: 500;">Rejected&nbsp;By</span>
                            @elseif($hr->status=='approved')
                            <span style="color: #778899; font-size: 12px; font-weight: 500;">Regularized&nbsp;By</span>
                            @endif
                            @if($hr->status=='pending'&&$hr->is_withdraw==1)
                            <span style="color: #36454F; font-size: 12px; font-weight: 500;">Me</span>
                            @else
                            <span
                                style="color: #36454F; font-size: 12px; font-weight: 500;">{{$EmployeeDetails->first_name}}&nbsp;{{$EmployeeDetails->last_name}}</span>
                            @endif
                        </div>



                        <div class="accordion-content">

                            <span style="color: #778899; font-size: 12px; font-weight: 500;">No. of Days</span>

                            <span style="color: #36454F; font-size: 12px; font-weight: 500;">

                                {{$numberOfEntries}}

                            </span>

                        </div>


                        <!-- Add other details based on your leave request structure -->

                        <div class="accordion-content">
                            @if($hr->status=='approved')
                            <span
                                style="margin-top:0.625rem; font-size: 12px; font-weight: 400; color:green;text-transform:uppercase;">closed</span>
                            @elseif($hr->status=='rejected')
                            <span
                                style="margin-top:0.625rem; font-size: 12px; font-weight: 400; color:#f66;text-transform:uppercase;">{{$hr->status}}</span>
                            @elseif($hr->status=='pending'&&$hr->is_withdraw==1)
                            <span
                                style="margin-top:0.625rem; font-size: 12px; font-weight: 400; color:#cf9b17;text-transform:uppercase;">withdrawn</span>
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
                        @if($numberOfEntries>1)
                        <span style="color: #778899; font-size: 12px; font-weight: 500;">Dates Applied:
                            {{ date('d M Y', strtotime($firstEntry['date'])) }} to
                            {{ date('d M Y', strtotime($lastEntry['date'])) }}</span>
                        @elseif($numberOfEntries==1)
                        <span style="color: #778899; font-size: 12px; font-weight: 500;">Dates Applied:
                            {{ date('d M Y', strtotime($firstEntry['date'])) }}</span>
                        @endif
                    </div>



                    <div style="width:100%; height:1px; border-bottom:1px solid #ccc; margin-bottom:10px;"></div>

                    <div style="display:flex; flex-direction:row; justify-content:space-between;">

                        <div class="content px-2">
                            @if($hr->status=='pending')
                            <span style="color: #778899; font-size: 12px; font-weight: 500;">Withdrawn on:</span><br>
                            <span
                                style="color: #333; font-size:12px; font-weight: 500;">{{ date('d M, Y', strtotime($hr->created_at)) }}</span>
                            @elseif($hr->status=='rejected')
                            <span style="color: #778899; font-size: 12px; font-weight: 500;">Rejected on:</span><br>
                            <span
                                style="color: #333; font-size:12px; font-weight: 500;">{{ date('d M, Y', strtotime($hr->created_at)) }}</span>
                            @elseif($hr->status=='approved')
                            <span style="color: #778899; font-size: 12px; font-weight: 500;">Regularized on:</span><br>
                            <span
                                style="color: #333; font-size:12px; font-weight: 500;">{{ date('d M, Y', strtotime($hr->created_at)) }}</span>
                            @endif
                        </div>

                        <div class="content px-2">

                            <a href="{{ route('regularisation-history', ['id' => $hr->id]) }}">

                                <span style="color: #3a9efd; font-size: 12px; font-weight: 500;">View Details</span>

                            </a>


                        </div>

                    </div>

                </div>
                @endif
                @endforeach
                @else
                <div class="history-box">
                    <img src="https://gt-linckia.s3.amazonaws.com/static-ess-v6.3.0-prod-144/review-list-empty.svg"
                        style="margin-top:80px;">
                    <p style="color: #a3b2c7;font-weight:400;font-size: 20px;margin-top:20px;">Hey, you have no
                        regularization records to view.history box</p>
                </div>
                @endif
                @endif
                <script>
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
            </div>
        </div>