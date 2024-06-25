<div>
<style>
          body{
            font-family: 'Montserrat', sans-serif;
        }
        input::placeholder {
    color: #ccc; /* Change this to the desired color */
}
        .detail-container {
        display: flex;
        flex-direction: column;
        width: 100%;
        gap: 10px;
        padding: 5px;
        background-color: none;
    }
    .detail1-container
    {
        display: flex;
        flex-direction: column;
        width: 100%;
        gap: 10px;
        padding: 5px;
        background-color: none;
    }
          .approved-leave{
            display: flex;
        flex-direction: row;
        width: 100%;
        gap: 10px;
        padding: 5px;
        background-color: none;
          }
 
    .heading {
    flex: 8; /* Adjust the flex value to control the size of the heading container */
    padding: 20px;
    width: 100%;
    background: #fff;
    border: 1px solid #ccc;
    border-radius:5px;
}
 
.side-container {
    flex: 4; /* Adjust the flex value to control the size of the side container */
    background-color: #fff;
    text-align: center;
    padding: 20px;
    height: 230px;
    border-radius:5px;
    border:1px solid #dcdcdc;
}
   
        .view-container{
            border:1px solid #ccc;
            background:#ffffe8;
            display:flex;
            width:80%;
            padding:5px 10px;
            border-radius:5px;
            height:auto;
        }
        .middle-container{
            background:#fff;
            display:flex;
            flex-direction:row;
            justify-content:space-between;
            margin:0.975rem auto;
        }
 
        .field {
            display: flex;
            justify-content: start;
            flex-direction: column;
        }
 
        .pay-bal  {
         display:flex;
         gap:10px;
        }
 
        .details {
       
            line-height:2;
        }
 
        .details p {
            margin: 0;
        }
        .vertical-line {
            width: 1px; /* Width of the vertical line */
            height: 70px; /* Height of the vertical line */
            background-color: #ccc;
            margin-left:-10px; /* Color of the vertical line */
        }
        .tooltip-container {
        position: relative;
    }
    .tooltip-text {
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 2px;
        white-space: nowrap;
    
    }
   
    .tooltip-text:hover::after {
    content: attr(data-tooltip);
    font-weight: bold;
    position: absolute;
    background-color: #f2f2f2;
    border: 1px solid black;
    padding: 10px;
    z-index: 1;
    width: 200px;
    top: calc(100% + 10px); /* Position below the tooltip-text */
    left: 50%; /* Position in the center horizontally */
    transform: translateX(-50%); /* Center horizontally */
    border-radius: 8px; /* Rounded corners */
    clip-path: polygon(0% 0%, 100% 0%, 100% 100%, 0% 100%); /* Square shape */
}



    
        .group h6{
            font-weight:600;
            font-size:0.875rem;
        }
        .table-container
        {
            width: auto;
            height:200px;
            background-color: #fff;
            margin-left:10px;
            border-radius: 5px;
            border:1px solid #ccc;
            display: flex;
        }
        .group h5{
            font-weight: 400;
            font-size: 1rem;
            white-space: nowrap; /* Prevent text from wrapping */
            overflow: hidden; /* Hide overflowing text */
            text-overflow: ellipsis;
            margin-top:0.975rem;
        }
        .group {
            margin-left:10px;
        }
     
        .data{
            display:flex;
            flex-direction:column;
   
        }
        .cirlce{
            height:0.75rem; width:0.75rem; background: #778899; border-radius:50%;
        }
        .v-line{
            height:100px; width:0.5px; background: #778899; border-right:1px solid #778899; margin-left:5px;
        }
        table {
        width: 75%;
        border-collapse: collapse;
    }
    th, td {
        border-bottom: 1px solid #dcdcdc; /* Change the color and style as needed */
        padding: 4px;  /* Adjust padding as needed */
        text-align:  left;/* Adjust text alignment as needed */
        font-weight: 200;
    }
    td{
        text-align: left;
    }
    th {
        background-color: #f3faff;
        font-size: 12px;
        text-align: left; /* Center align column headers */
    }
    .overflow-cell {
        max-width: 70px; /* Adjust the maximum width of the cell */
        white-space: nowrap; /* Prevent text wrapping */
        overflow: hidden; /* Hide overflow text */
        text-overflow: ellipsis; /* Display an ellipsis (...) when text overflows */
    }
    td{
        font-size: 14px;
    }
        .leave{
            display:flex; flex-direction:row; gap:50px; background:#fff;
            border-bottom:1px solid #ccc;padding-bottom:10px;
        }
        @media screen and (max-width: 1060px) {
           .detail-container{
            width:100%;
            display:flex;
            flex-direction:column;
           }
           .heading {
        flex: 1; /* Change the flex value for the heading container */
        padding: 10px; /* Modify padding as needed */
        width: 100%;
    }
 
    .side-container {
        flex: 1; /* Change the flex value for the side container */
        padding: 10px; /* Modify padding as needed */
        height: auto;
        width: 100%;/* Allow the height to adjust based on content */
    }
    

}
    </style>
    
    <div class="detail-container ">
        
        <div class="approved-leave d-flex gap-3">
            <div class="heading mb-3">
                <div class="heading-2" >
                    <div class="d-flex flex-row justify-content-between rounded">
                    <div class="field">
                               
                                    @if($regularisationrequest->status=='approved')
                                        <span style="color: #778899; font-size: 12px; font-weight: 500;">
                                            Approved by
                                        </span>
                                    @elseif($regularisationrequest->status=='rejected')
                                        <span style="color: #778899; font-size: 12px; font-weight: 500;">
                                            Rejected by
                                        </span>
                                    @endif  
                           
                                
                               
                                  
                                        <span style="color: #333; font-weight: 500;font-size:12px;text-transform:uppercase;">
                                            hii{{$ManagerName->first_name}}&nbsp;{{$ManagerName->last_name}}
                                        </span>
                                   
                                       
                                 
                        </div>
 
                     <div>
                        <span style="color: #32CD32; font-size: 12px; font-weight: 500; text-transform:uppercase;">
                      
                                @if($regularisationrequest->status=='rejected')
                                    <span style="margin-top:0.625rem; font-size: 12px; font-weight: 500; color:#f66;text-transform:uppercase;">{{$regularisationrequest->status}}</span>
                                @elseif($regularisationrequest->status=='approved')
                                    <span style="margin-top:0.625rem; font-size: 12px; font-weight: 500; color:#32CD32;text-transform:uppercase;">{{$regularisationrequest->status}}</span>
                                @endif    
                        </span>
                   </div>
                </div>
            <div class="middle-container">
                <div class="view-container m-0 p-0">
                     <div class="first-col" style="display:flex; gap:40px;">
                            <div class="field p-2">
                                <span style="color: #778899; font-size:11px; font-weight: 500;">Remarks</span>
                                
                                   <span style="font-size: 12px; font-weight: 600;text-align:center;">-<br></span>
                                
                                
                            </div>
                           
                            <div class="vertical-line"></div>
                         </div>
                         <div class="box" style="display:flex;  margin-left:30px;  text-align:center; padding:5px;">
                            <div class="field p-2">
                                <span style="color: #778899; font-size:10px; font-weight: 500;">No. of days</span>
                                <span style=" font-size: 12px; font-weight: 600;">{{$totalEntries}}</span>
                            </div>
                        </div>
                     </div>
                 </div>
              </div>
 
        
        </div>
        <div class="side-container mx-2 ">
            <h6 style="color: #778899; font-size: 12px; font-weight: 500; text-align:start;"> Application Timeline </h6>
           <div  style="display:flex; ">
           <div style="margin-top:20px;">
             <div class="cirlce"></div>
             <div class="v-line"></div>
              <div class=cirlce></div>
            </div>
              <div style="display:flex; flex-direction:column; gap:60px;">
              <div class="group">
              <div style="padding-top:20px;margin-top:-15px;">
                <h5 style="color: #333; font-size: 12px; font-weight: 400; text-align:start;">
                          @if($regularisationrequest->status=='approved')
                               Accept<br><span style="color: #778899; font-size: 12px; font-weight: 400; text-align:start;">by</span>
                          @elseif($regularisationrequest->status=='rejected')
                               rccept<br><span style="color: #778899; font-size: 12px; font-weight: 400; text-align:start;">by</span>
                          @endif
                           
                           <span style="color: #778899; font-weight: 500;">
                               {{strtoupper($ManagerName->first_name)}}&nbsp;{{strtoupper($ManagerName->last_name)}}
                           </span><br>
                        @if($regularisationrequest->status=='approved')   
                           <span style="color: #778899; font-size: 11px; font-weight: 400;text-align:start;">
                                    
                                    
                                    @if(\Carbon\Carbon::parse($regularisationrequest->approved_date)->isToday())
                                                                 Today
                                                    @elseif(\Carbon\Carbon::parse($regularisationrequest->approved_date)->isYesterday())
                                                                Yesterday
                                                    @else
                                                         {{ \Carbon\Carbon::parse($regularisationrequest->approved_date)->format('jS M, Y') }}
           
                                                    @endif
                                                    &nbsp;&nbsp;&nbsp;
                                                        {{ \Carbon\Carbon::parse($regularisationrequest->approved_date)->format('h:i A') }}
                                    </span>
                        @elseif($regularisationrequest->status=='rejected')   
                        <span style="color: #778899; font-size: 11px; font-weight: 400;text-align:start;">
                                    
                                    
                                    @if(\Carbon\Carbon::parse($regularisationrequest->rejected_date)->isToday())
                                                                 Today
                                                    @elseif(\Carbon\Carbon::parse($regularisationrequest->rejected_date)->isYesterday())
                                                                Yesterday
                                                    @else
                                                         {{ \Carbon\Carbon::parse($regularisationrequest->rejected_date)->format('jS M, Y') }}
           
                                                    @endif
                                                    &nbsp;&nbsp;&nbsp;
                                                        {{ \Carbon\Carbon::parse($regularisationrequest->rejected_date)->format('h:i A') }}
                                    </span>
                        @endif              
                    <br>
                    
                </h5>
            </div>
 
           </div>
           <div class="group">
               <div style="margin-top:-15px;">
                  <h5 style="color: #333; font-size: 12px; font-weight: 400; text-align:start;">Submitted<br>
                          <span style="color: #778899; font-size: 11px; font-weight: 400;text-align:start;">
                                    
                                    
                          @if(\Carbon\Carbon::parse($regularisationrequest->created_at)->isToday())
                                                       Today
                                          @elseif(\Carbon\Carbon::parse($regularisationrequest->created_at)->isYesterday())
                                                      Yesterday
                                          @else
                                               {{ \Carbon\Carbon::parse($regularisationrequest->created_at)->format('jS M, Y') }}
 
                                          @endif
                                          &nbsp;&nbsp;&nbsp;
                                              {{ \Carbon\Carbon::parse($regularisationrequest->created_at)->format('h:i A') }}
                          </span>
                    </h5>
               </div>
           </div>
              </div>
           
           </div>
             
        </div>
        </div>
    </div>
  
  <div class="table-container">
  <table style="width: 50%; height: 60%;">
  <thead style="background-color: white;">
        <tr>
            <th colspan="3" style="color: #778899; font-weight: 500; padding: 12px;">Dates Applied for Regularisation</th>
        </tr>
    </thead>
    <thead>
        <tr>
            <th style="width: 30%; padding:8px;">Date</th>
            <th style="width: 20%; padding:8px;">Approve/Reject</th>
            <th style="width: 50%; padding:8px; border-right: 1px solid #dcdcdc;">Approver Remarks</th>
        </tr>
    </thead>
    @foreach($regularisationEntries as $r1)
    <tbody>
        <tr>
            <td style="width: 30%; font-size: 12px;padding:8px;">{{ \Carbon\Carbon::parse($r1['date'])->format('d M, Y') }}</td>
            @if($regularisationrequest->status == 'rejected')
            <td style="width: 20%; text-transform: uppercase; color: #f66; font-size: 12px;padding:8px;">{{$regularisationrequest->status}}</td>
            @elseif($regularisationrequest->status == 'approved')
            <td style="width: 20%; text-transform: uppercase; color: #32CD32; font-size: 12px;padding:8px;">{{$regularisationrequest->status}}</td>
            @endif
            @if(empty($regularisationrequest->approver_remarks))
            <td style="width: 50%; border-right: 1px solid #dcdcdc; font-size: 12px;padding:8px;">-</td>
            @else
            <td style="width: 50%; border-right: 1px solid #dcdcdc; font-size: 12px;padding:8px;">{{ucfirst($regularisationrequest->approver_remarks)}}</td>
            @endif
        </tr>
    </tbody>
    @endforeach
</table>

     <table style="width: 50%;height:60%">
           <thead style="padding:20px;">
                <tr>
                     <th style="padding: 20px;"></th>
                     <th></th>
                     <th></th>
                     <th></th>
                </tr>
            </thead>
        <thead style="height:40%;margin-top:4px;">
            <tr>

                <th style="padding: 8px;width:30%;">Shift</th>
                <th style="padding: 8px;width:20%;">First In Time</th>
                <th style="padding: 0;width:20%;">Last Out Time</th>
                <th style="padding: 8px;border-right:1px solid #dcdcdc;width:30%;">Reason</th>
            </tr>
        </thead>


        @foreach($regularisationEntries as $r1)
        <tbody class="regularisationEntries"style="height:40%;">

                <td style="width:30%;font-size:12px;padding: 8px;">10:00 am to 07:00 pm</td>
                <td style="width:20%;font-size:12px;padding: 8px;">

                       @if(empty($r1['from']))
                            10:00
                       @else
                            {{ $r1['from'] }}
                       @endif

                </td>
                <td style="width:20%;font-size:12px;padding: 0;">
                       
                       @if(empty($r1['to']))
                            19:00
                       @else
                            {{ $r1['to'] }}
                       @endif
                       
                </td>
                <td class="tooltip-container" style="width:30%; border-right: 1px solid #dcdcdc;padding: 8px;">
                            <span class="tooltip-text" style="font-size: 12px;" data-tooltip="{{$r1['reason']}}">{{ ucwords(strtolower($r1['reason'])) }}</span>
                </td>
        </tbody>
        @endforeach
    </table>
  </div>


</div>