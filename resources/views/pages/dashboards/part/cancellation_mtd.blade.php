<div class="table-container-h table-responsive table-floating-overflow tableFixHead" >
    <table class="table table-border datatable-able" id="content-table" style="width: 2500px" >
        <thead>
            <tr>
            <th class="bg-red text-white" style="width: 2%">No</th>
            <th class="bg-red text-white" style="width: 4%">Date</th>
            <th class="bg-red text-white" style="width: 6%">Full Name</th>
            {{--<th class="bg-red text-white" style="width: 6.6%">Guest Country</th>--}}
            <th class="bg-red text-white" style="width: 10%">Segment</th>
            <th class="bg-red text-white" style="width: 3%">Room Type</th>
            <th class="bg-red text-white" style="width: 3%">Conf No.</th>
            <th class="bg-red text-white" style="width: 7%">C A G S <br> (Company, Agent, Group Source)</th>
            <th class="bg-red text-white" style="width: 4%">Room Class</th>
            <th class="bg-red text-white" style="width: 3%">Arrival Date</th>
            <th class="bg-red text-white" style="width: 3%">Departure Date</th>
            <th class="bg-red text-white" style="width: 2%">Room Night</th>
            <th class="bg-red text-white" style="width: 2%">Rate Code</th>
            <th class="bg-red text-white" style="width: 4%">Room Rate</th>
            <th class="bg-red text-white" style="width: 3%">Cncl Code</th>
            <th class="bg-red text-white" style="width: 17%">Cncl Reason</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($data['cancellation_mtd_details']) && $data['cancellation_mtd_details'])
              @foreach($data['cancellation_mtd_details'] as $cl_detail)
                <tr>
                  <td class="text-center">{{ $loop->iteration }}</td>
                  <td class="text-center">{{ date('d M Y' ,strtotime($cl_detail->ReportDate)) ?? '-' }}</td>
                  <td class="text-left">{{ $cl_detail->FullName ?? '-' }}</td>
                  {{--<td class="text-left">-</td>--}}
                  <td class="text-left">{{ $cl_detail->Segment ?? '-' }}</td>
                  <td class="text-left">{{ $cl_detail->RoomType ?? '-' }}</td>
                  <td class="text-left">{{ $cl_detail->ConfirmationNo ?? '-' }}</td>
                  <td class="text-left">{{ $cl_detail->CAGS ?? '-' }}</td>
                  <td class="text-left">{{ $cl_detail->RoomClass ?? '-' }}</td>
                  <td class="text-center">{{ date('d M Y' ,strtotime($cl_detail->ArrivalDate)) ?? '-' }}</td>
                  <td class="text-center">{{ date('d M Y' ,strtotime($cl_detail->DepartureDate)) ?? '-' }}</td>
                  <td class="text-right">{{ number_format($cl_detail->RoomNight) ?? '-' }}</td>
                  <td class="text-left">{{ $cl_detail->RateCode ?? '-' }}</td>
                  <td class="text-right">{{ number_format($cl_detail->RoomRate) ?? '-' }}</td>
                  <td class="text-left">{{ $cl_detail->CxlCode ?? '-' }}</td>
                  <td class="text-left">{{ $cl_detail->CxlReason ?? '-' }}</td>
                </tr>
              @endforeach
            @else
              <tr>
                <td class="text-center" colspan="15">No Data</td>
              </tr>
            @endif
        </tbody>
    </table>
</div>