<div class="table-responsive form-group">
   <table class="table table-bordered" cellspacing="0" id="table-cancellation-summary">
     <thead>
      @if(isset($cancellation_header_room_nite) && count($cancellation_header_room_nite) > 0)
      <tr>
       <th colspan="14" class="bg-secondary text-white no-sort">ROOM NITE SUMMARY (ARRIVAL BASED)</th>
      </tr>
      <tr>
         @foreach($cancellation_header_room_nite as $key => $mon)
          <th class="no-sort">{{ ucfirst($key) }}</th>
         @endforeach
          <th class="no-sort" style="width: 5%">Total</th>
      </tr>
      @else
      <tr>
       <th class="bg-secondary text-white no-sort">ROOM NITE SUMMARY (ARRIVAL BASED)</th>
      </tr>
      @endif

     </thead>
     <tbody>
        @if(isset($cancellation_summary) && count($cancellation_summary))
          @foreach($cancellation_summary as $data_cancel)
            @php
              $total_nite = 0;
            @endphp
             <tr>
             @foreach($data_cancel as $key_data => $data_summary)
                @php
                  if($key_data != 'year' && $key_data != 'Year') 
                    $total_nite += is_int((int)$data_summary) ? (int)$data_summary : 0;
                @endphp
                <td>{{ $data_summary }}</td>
             @endforeach
             <td><strong>{{ $total_nite }}</strong></td>
             </tr>
          @endforeach
        @else
          {{-- <tr>
            <td colspan="1">No data available</td>
          </tr> --}}
        @endif
     </tbody>
  </table>
</div>

<div class="table-responsive form-group">
   <table class="table table-bordered" cellspacing="0" id="table-cancellation" style="white-space: nowrap;">
     <thead>
      <tr>
         <th class="bg-secondary text-white" style="width: 5%">NO</th>
         <th class="bg-secondary text-white" style="width: 5%">MARKET <br> SEGMENT</th>
         <th class="bg-secondary text-white">ROOM</th>
         <th class="bg-secondary text-white" style="width: 5%">ROOM TYPE</th>
         <th class="bg-secondary text-white">CONF NO</th>
         <th class="bg-secondary text-white">FULL NAME</th>
         <th class="bg-secondary text-white">RTC</th>
         <th class="bg-secondary text-white">NITE</th>
         <th class="bg-secondary text-white">ARRIVAL</th>
         <th class="bg-secondary text-white">DEPARTURE</th>
         <th class="bg-secondary text-white">RATE CODE</th>
         <th class="bg-secondary text-white">CURRENCY</th>
         <th class="bg-secondary text-white">ROOM RATE</th>
         <th class="bg-secondary text-white" style="width: 8%">CANCEL CODE</th>
         <th class="bg-secondary text-white">INSERT DATE</th>
      </tr>
     </thead>
     <tbody>
      @php
        $total_nite = 0;
      @endphp
      @if(isset($cancellation_daily) && count($cancellation_daily))
        @foreach($cancellation_daily as $cancel) 
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ strtoupper($cancel->MarketSegment) }}</td>
            <td>{{ $cancel->RoomNo ? strtoupper($cancel->RoomNo) : "-" }}</td>
            <td>{{ strtoupper($cancel->RoomType) }}</td>
            <td>{{ $cancel->ConfNo }}</td>
            <td class="text-left">{{ $cancel->FullName }}</td>
            <td>{{ $cancel->RTC }}</td>
            <td>{{ number_format($cancel->NITE, 0, ',','.') }}</td>
            <td>{{ $cancel->Arrival ? date("d-M-y", strtotime($cancel->Arrival)) : '-' }}</td>
            <td>{{ $cancel->Departure ? date("d-M-y", strtotime($cancel->Departure)) : '-' }}</td>
            <td class="text-left">{{ strtoupper($cancel->RateCode) }}</td>
            <td class="text-center">{{ isset($cancel->Currency) ? $cancel->Currency : '' }}</td>
            <td class="text-right">{{ number_format($cancel->RoomRate, 0, ',','.') }}</td>
            <td class="text-left">{{ $cancel->CancelCode }}</td>
            <td>{{ $cancel->BookingDate ? date("d-M-y", strtotime($cancel->BookingDate)) : '-' }}</td>
          </tr>
          @php
          $total_nite += (int)$cancel->NITE;
          @endphp
        @endforeach
      @else
        {{--<tr>
          <td class="text-center" colspan="17">No data Available</td>
        </tr>--}}
      @endif

      {{--<tr style="background: #ececec;color:#000;font-weight:bold;">
        <td colspan="7" class="text-right">TOTAL NITE</td>
        <td>{{ $total_nite }}</td>
        <td colspan="7"></td>
      </tr>--}}
     </tbody>
  </table>
</div>