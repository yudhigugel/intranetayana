<div class="table-container-h table-responsive tableFixHead">
    <table class="table table-border datatable-vip" id="content-table">
        <thead>
            <tr>
            <th class="bg-secondary text-white" style="width: 7%">Date</th>
            <th class="bg-secondary text-white" style="width: 6%">VIP Code</th>
            <th class="bg-secondary text-white" style="width: 15%">Full Name</th>
            <th class="bg-secondary text-white" >Company</th>
            <th class="bg-secondary text-white" style="width: 7%">Room No</th>
            <th class="bg-secondary text-white" style="width: 10%">Check In</th>
            <th class="bg-secondary text-white" style="width: 10%">Check Out</th>
            <th class="bg-secondary text-white" >Notes</th>
            <th class="bg-secondary text-white" >Preference</th>
            </tr>
        </thead>
        <tbody>
          @if(isset($data['guestVIP_details']) && $data['guestVIP_details'])
              @foreach($data['guestVIP_details'] as $vip_detail)
                <tr>
                  <td class="text-center">{{ date('d M Y', strtotime($vip_detail->ReportDate)) ?? '-' }}</td>
                  <td class="text-center">{{ $vip_detail->VIPCode ?? '-' }}</td>
                  <td class="text-left">{{ $vip_detail->FullName ?? '-' }}</td>
                  <td class="text-left">{{ $vip_detail->Company ?? '-' }}</td>
                  <td class="text-center">{{ $vip_detail->RoomNo ?? '-' }}</td>
                  <td class="text-center">{{ $vip_detail->CheckIn ? date('d M Y', strtotime($vip_detail->CheckIn)) : '-' }}</td>
                  <td class="text-center">{{ $vip_detail->CheckOut ? date('d M Y', strtotime($vip_detail->CheckOut)) : '-' }}</td>
                  <td class="text-left">{{ $vip_detail->Notes ?? '-' }}</td>
                  <td class="text-left">{{ $vip_detail->Preference ?? '-' }}</td>
                </tr>
              @endforeach
            @endif
        </tbody>
    </table>
</div>