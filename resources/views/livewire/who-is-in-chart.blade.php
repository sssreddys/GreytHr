<div class=" m-0 p-0">
  @php
  $notyetin=0;
  $lateArrival=0;
  $onTime=0;
  $onLeave=0;
  @endphp
  @foreach($Swipes as $s1)
  @php
     $swipeTime = \Carbon\Carbon::parse($s1->swipe_time);
     $isLateBy10AM = $swipeTime->format('H:i') > '10:00';
     $isEarlyBy10AM= $swipeTime->format('H:i') <= '10:00' ;
  @endphp
        @if($isLateBy10AM) 
            @php
                $notyetin++; 
                $lateArrival++; 
            @endphp
        @endif 
        @if($isEarlyBy10AM)
            @php 
                $onTime++; 
            @endphp
        @endif
  @endforeach 
       @php 
           $CalculatePresentOnTime=($EarlySwipesCount/$TotalEmployees)*100;
           $CalculatePresentButLate=($LateSwipesCount/$TotalEmployees)*100;
      @endphp 
      <div class="date-form-who-is-in"> 
    <input type="date" wire:model="from_date" wire:change="updateDate" class="form-control" id="fromDate" name="fromDate" style="color: #778899;">
</div>

<div class="cont m-0 p-0 " style="display:flex; justify-content: end;">
  <div class="search-container-who-is-in" >
    <div class="form-group-who-is-in">
      <div class="search-input-who-is-in" >
        <input wire:model="search" type="text" placeholder="Search by name or ID" class="search-text">
        <div class="search-icon-who-is-in" wire:click="searchFilters">
          <i class="fa fa-search" aria-hidden="true"></i>
        </div>
      </div>
    </div>
  </div>
 
</div>
<div cs="mx-1 p-0">
  <div class="container-box-for-employee-information-who-is-in ">
    <!-- Your content goes here -->
    <div style="margin-top:5px;display:flex;align-items:center; text-align:center;justify-content:center;padding:0;">
      <p style="text-align:center;font-size:14px;">Employees Information for <span style="font-weight: 700; ">{{\Carbon\Carbon::parse($currentDate)->format('jS F Y')}}</span></p>
    </div>

    <div class="content" style="display:flex; flex-direction:row;justify-content:space-between;padding:0; border-top:1px solid #52afe9;">
      <div class="col-md-3 field-for-employee-who-is-in">
        <div class="percentage-who-is-in" style="font-weight: 600;font-size:1.225rem;">{{number_format($CalculateAbsentees,2)}}%</div>

        @if($employeesCount1>0)
        <div class="employee-count-who-is-in">{{$employeesCount1}}&nbsp;Employee(s)&nbsp;are&nbsp;Absent</div>
        @else
        <div class="employee-count-who-is-in">No&nbsp;Employee(s)&nbsp;are&nbsp;Absent</div>
        @endif
      </div>
      <div class="col-md-3 field-for-employee-who-is-in">
        <div class="percentage-who-is-in" style="font-weight: 600;font-size:1.225rem;">{{number_format($CalculatePresentButLate,2)}}%</div>
        @if($LateSwipesCount>0)
        <div class="employee-count-who-is-in">{{$LateSwipesCount}}&nbsp;Employee(s)&nbsp;are&nbsp;Late&nbsp;In</div>
        @else
        <div class="employee-count-who-is-in">No&nbsp;Employee(s)&nbsp;are&nbsp;Late&nbsp;In</div>
        @endif
      </div>
      <div class="col-md-3 field-for-employee-who-is-in">
        <div class="percentage-who-is-in" style="font-weight: 600;font-size:1.225rem;">{{number_format($CalculatePresentOnTime,2)}}%</div>
        @if($EarlySwipesCount>0)
        <div class="employee-count-who-is-in">{{$EarlySwipesCount}}&nbsp;Employee(s)&nbsp;are&nbsp;On&nbsp;Time</div>
        @else
        <div class="employee-count-who-is-in">No&nbsp;Employee(s)&nbsp;are&nbsp;On&nbsp;Time</div>
        @endif

      </div>
      <div class="col-md-3 field-for-employee-who-is-in">
        <div class="percentage-who-is-in" style="font-weight: 600;font-size:1.225rem;">{{number_format($CalculateApprovedLeaves,2)}}%</div>

        @if($ApprovedLeaveRequestsCount>0)
        <div class="employee-count-who-is-in">{{$ApprovedLeaveRequestsCount}}&nbsp;Employee(s)&nbsp;are&nbsp;On&nbsp;Leave</div>
        @else
        <div class="employee-count-who-is-in">No&nbsp;Employee(s)&nbsp;are&nbsp;On&nbsp;Leave</div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- containers for attendace -->
<div class="content " style=" display:flex;">

  <div class="col">
    <div class="container5-who-is-in">
      <div class="heading-who-is-in">
        <h3>Absent&nbsp;({{ str_pad($employeesCount1, 2, '0', STR_PAD_LEFT) }})</h3>

        <i class="fas fa-download" wire:click="downloadExcelForAbsent" style="cursor:pointer;"></i>

      </div>

      <div>
        <table class="who-is-in-table" style="margin-top:-10px">
          <thead>

            <tr>
              <th>Employee</th>
              <th>Expected&nbsp;In&nbsp;Time</th>
            </tr>


          </thead>
          <tbody>
            @if($notFound)
            <tr>
              <td colspan="2" style="text-align: center;">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQi7kQkKftvg3JCfA63d_BWJbSNrTwsX9QQPRUS7Okm5iTciCkig3wOxRUQB59BO6AM0Ng&usqp=CAU" style="width: 30px;">
                <p style="font-weight: normal; font-size: 12px; color:#778899;margin-top:5px;">No employees are absent today</p>
              </td>
            </tr>
            @else
            @foreach($Employees1 as $e1)
            <tr style="border-bottom: 1px solid #ddd;">
              <td style="font-size:10px;font-weight:700;max-width:120px;overflow: hidden;white-space: nowrap; text-overflow: ellipsis;">
                {{ ucwords(strtolower($e1->first_name)) }} {{ ucwords(strtolower($e1->last_name)) }}<br />
                <span class="text-muted" style="font-weight:normal;font-size:10px;">#{{$e1->emp_id}}</span>
              </td>
              <td style="font-weight:700;font-size:10px;">10:00:00</td>
            </tr>

            <!-- Add more rows with dashes as needed -->
            @endforeach
            @endif
          </tbody><!-- Add table rows (tbody) and data here if needed -->
        </table>
      </div>
    </div>
  </div>
  <div class="col">
    <div class="container5-who-is-in" >
      <div class="heading-who-is-in">
        <h3>Late&nbsp;Arrivals&nbsp;({{ str_pad($lateArrival, 2, '0', STR_PAD_LEFT) }})</h3>

        <i class="fas fa-download" wire:click="downloadExcelForLateArrivals" style="cursor:pointer;"></i>

      </div>

      <div>
        <table class="who-is-in-table" style="margin-top:-10px">
          @if($lateArrival > 0)
          <thead>
            <tr>
              <th>Employee</th>
              <th>Late&nbsp;By</th>

            </tr>
          </thead>
          <tbody>

            @foreach($Swipes as $s1)
          
            @php
            $swipeTime = \Carbon\Carbon::parse($s1->swipe_time);
            $lateArrivalTime = $swipeTime->diff(\Carbon\Carbon::parse('10:00'))->format('%H:%I');
            $isLateBy10AM = $swipeTime->format('H:i') > '10:00';
            @endphp

            @if($isLateBy10AM)
           
            <tr style="border-bottom: 1px solid #ddd;">
              <td style="font-size:10px;font-weight:700;max-width:110px;overflow: hidden;white-space: nowrap; text-overflow: ellipsis;">
                @php
                $firstNameParts = explode(' ', strtolower($s1->first_name));
                $lastNameParts = explode(' ', strtolower($s1->last_name));
                @endphp
              
                @foreach($firstNameParts as $part)
                {{ ucfirst(strtolower($part)) }}
                @endforeach

                @foreach($lastNameParts as $part)
                {{ ucfirst(strtolower($part)) }}
                @endforeach

                <br /><span class="text-muted" style="font-weight:normal;font-size:10px;">#{{$s1->emp_id}}</span>
              </td>
              <td style="font-weight:700;font-size:10px;">{{$lateArrivalTime}}<br /><span class="text-muted" style="font-size:10px;font-weight:300;">{{$s1->swipe_time}}</span></td>
            </tr>

            @endif

            @endforeach

          </tbody><!-- Add table rows (tbody) and data here if needed -->
          @else

          <tr>
            <td colspan="2" style="text-align: center;margin-top:40px;margin-left:60px;">
              <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQi7kQkKftvg3JCfA63d_BWJbSNrTwsX9QQPRUS7Okm5iTciCkig3wOxRUQB59BO6AM0Ng&usqp=CAU" style="width: 30px;margin-top:20px">
              <p style="font-weight: normal; font-size: 10px; color:#778899;margin-top:5px;">No employees are late today</p>
            </td>
          </tr>

          @endif
        </table>
      </div>
    </div>
  </div>
  <div class="col">
    <div class="container5-who-is-in" style="margin-left:-20px;">
      <div class="heading-who-is-in">
        <h3>On&nbsp;Time&nbsp;({{ str_pad($onTime, 2, '0', STR_PAD_LEFT) }})</h3>

        <i class="fas fa-download" wire:click="downloadExcelForEarlyArrivals" style="cursor:pointer;"></i>

      </div>

      <div>
        <table class="who-is-in-table" style="margin-top:-10px">
          @if($onTime > 0)
          <thead style="width: 100px;">
            <tr>
              <th style="width:100px">Employee</th>
              <th>Early&nbsp;By</th>

            </tr>
          </thead>
          <tbody>

            @foreach($Swipes as $s1)
            @php
               $swipeTime = \Carbon\Carbon::parse($s1->swipe_time);
               $earlyArrivalTime = $swipeTime->diff(\Carbon\Carbon::parse('10:00'))->format('%H:%I');
               $isEarlyBy10AM = $swipeTime->format('H:i') <= '10:00' ;
            @endphp
            @if($isEarlyBy10AM) 
            <tr style="border-bottom: 1px solid #ddd;">
              <td style="font-size:10px;font-weight:700;overflow: hidden; text-overflow: ellipsis; white-space: nowrap;max-width:100px;">{{ ucwords(strtolower($s1->first_name)) }}{{ ucwords(strtolower($s1->last_name)) }}<br /><span class="text-muted" style="font-weight:normal;font-size:10px;">#{{$s1->emp_id}}</span></td>
              <td style="font-weight:700;font-size:10px;">{{$earlyArrivalTime}}<br /><span class="text-muted" style="font-size:10px;font-weight:300;">{{$s1->swipe_time}}</span></td>
            </tr>

            @endif
            @endforeach

          </tbody><!-- Add table rows (tbody) and data here if needed -->
          @else
          <tr>
            <td colspan="2 mt-2" style="text-align: center;">
              <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQi7kQkKftvg3JCfA63d_BWJbSNrTwsX9QQPRUS7Okm5iTciCkig3wOxRUQB59BO6AM0Ng&usqp=CAU" style="width: 30px;margin-top:20px;">
              <p style="font-weight: normal; font-size: 10px; color:#778899;margin-top:5px;">No&nbsp;employees&nbsp;are&nbsp;on&nbsp;time&nbsp;today</p>
            </td>
          </tr>
          @endif
        </table>
      </div>
    </div>
  </div>
  <div class="col">
    <div class="container5-who-is-in" style="margin-left:-20px;">
      <div class="heading-who-is-in">
        <h3>On&nbsp;Leave&nbsp;({{ str_pad($ApprovedLeaveRequestsCount, 2, '0', STR_PAD_LEFT) }})</h3>

        <i class="fas fa-download" wire:click="downloadExcelForLeave" style="cursor: pointer;"></i>

      </div>

      <div>
        <table class="who-is-in-table" style="margin-top:-10px">
          @if($ApprovedLeaveRequestsCount > 0)
          <thead>
            <tr>
              <th>Employee</th>
              <th>Number&nbsp;of&nbsp;days</th>

            </tr>
          </thead>
          <tbody>
            @if($notFound3)
            <tr>
              <td colspan="2" style="text-align: center;">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQi7kQkKftvg3JCfA63d_BWJbSNrTwsX9QQPRUS7Okm5iTciCkig3wOxRUQB59BO6AM0Ng&usqp=CAU" style="width: 30px;">
                <p style="font-weight: normal; font-size: 12px; color:#778899;margin-top:5px;">No employees are on leave today</p>
              </td>
            </tr>

            @else


            @foreach($ApprovedLeaveRequests as $alr)


            <tr style="border-bottom: 1px solid #ddd;">
              <td style="font-size:10px;font-weight:700;overflow: hidden; text-overflow: ellipsis; white-space: nowrap;max-width:100px;">
                @php
                $firstNameParts = explode(' ', strtolower($alr->first_name));
                $lastNameParts = explode(' ', strtolower($alr->last_name));
                @endphp
                @foreach($firstNameParts as $part)
                {{ ucfirst(strtolower($part)) }}
                @endforeach

                @foreach($lastNameParts as $part)
                {{ ucfirst(strtolower($part)) }}
                @endforeach
                <br /><span class="text-muted" style="font-weight:normal;font-size:10px;">#{{$alr->emp_id}}</span>
              </td>
              <td style="font-weight:700;font-size:10px;">{{$alr->number_of_days}} Day(s)<br />
                <div style="background-color: rgb(176, 255, 176); border: 1px solid green; color: green;border-radius:15px; padding: 2px; text-align: center;">
                  Approved
                </div>
              </td>

            </tr>
            @endforeach

            @endif
          </tbody>
          <!-- Add table rows (tbody) and data here if needed -->
          @else
          <tr>
            <td colspan="2" style="text-align: center;">
              <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQi7kQkKftvg3JCfA63d_BWJbSNrTwsX9QQPRUS7Okm5iTciCkig3wOxRUQB59BO6AM0Ng&usqp=CAU" style="width: 30px;margin-top:20px;">
              <p style="font-weight: normal; font-size: 10px; color:#778899;margin-top:5px;">No employees are on leave&nbsp;today</p>
            </td>
          </tr>
          @endif
        </table>
      </div>
    </div>
  </div>
</div>

<!-- third col -->

<script>
  document.addEventListener('livewire:load', function() {
    Livewire.on('updatePlaceholder', value => {
      const input = document.getElementById('fromDate');
      if (value) {
        input.setAttribute('placeholder', value);
      } else {
        input.setAttribute('placeholder', 'Select Date Range');
      }
    });
  });
</script>


</div>