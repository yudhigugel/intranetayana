<div class="table-container-h table-responsive table-floating-overflow" style="max-height: 500px">
  <table class="table table-border" id="content-table">
      <thead style="white-space: nowrap;" class="no-wrap-th">
          <tr class="fixed first">
            <th class="bg-secondary text-white header" style="z-index: 5"><b>Resort / Subresort</b></th>
            <th class="bg-secondary text-white period header" style="z-index: 5"><b>Description</b></th>
            @php
              $tanggal_pertama = date('d M Y',strtotime($data['date_forecast']));
              $hari_pertama_echo = date('l',strtotime($tanggal_pertama));
            @endphp
            <!-- Hari pertama -->
            <th class="bg-secondary text-white">
              <b>
                {{$tanggal_pertama}} <br> ({{$hari_pertama_echo}})
              </b>
            </th>
            <!-- Hari selanjutnya -->
            @for ($i=1;$i<=13;$i++)
              @php
                  $tanggal_echo = date('d M Y',strtotime("+ $i day", strtotime ($tanggal_pertama) ));
                  $hari_echo = date('l',strtotime($tanggal_echo));
              @endphp
              <th class="bg-secondary text-white" ><b>{{$tanggal_echo}} <br> ({{$hari_echo}})</b></th>
            @endfor
          </tr>
      </thead>
      <tbody>
      @if(isset($data['forecast_7_days_rooms']) && $data['forecast_7_days_rooms'])
          <!-- Loop Company Code -->
          @foreach($data['forecast_7_days_rooms'] as $company_code => $resort)
            <tr>
              <td colspan="16" class="bg-black text-white text-center"><b>{{ $company_code }}</b></td>
            </tr>
            @if(isset($resort['DATA_SUBRESORT']) && $resort['DATA_SUBRESORT'])
              @foreach($resort['DATA_SUBRESORT'] as $frcst_resort => $frc_rooms)
                @php
                  $loop_now_forecast = 0;
                @endphp

                  @foreach($frc_rooms as $fc_rooms)
                    <tr class="fixed">
                      @if($loop_now_forecast == 0)
                        <td class="text-center header" style="vertical-align: middle;" rowspan="{{ count($frc_rooms) }}">
                        <b>{{ $frcst_resort }}</b>
                        </td>
                      @endif

                      <td class="text-left header period"><b>{{ $fc_rooms->RoomCategory }}</b></td>
                      @if(strtolower($fc_rooms->RoomCategory) == 'occupancy %')
                      <td class="text-right">{{ number_format(isset($fc_rooms->Day1) ? $fc_rooms->Day1 : 0, 2)."%" ?? '-' }}</td>
                      <td class="text-right">{{ number_format(isset($fc_rooms->Day2) ? $fc_rooms->Day2 : 0, 2)."%" ?? '-' }}</td>
                      <td class="text-right">{{ number_format(isset($fc_rooms->Day3) ? $fc_rooms->Day3 : 0, 2)."%" ?? '-' }}</td>
                      <td class="text-right">{{ number_format(isset($fc_rooms->Day4) ? $fc_rooms->Day4 : 0, 2)."%" ?? '-' }}</td>
                      <td class="text-right">{{ number_format(isset($fc_rooms->Day5) ? $fc_rooms->Day5 : 0, 2)."%" ?? '-' }}</td>
                      <td class="text-right">{{ number_format(isset($fc_rooms->Day6) ? $fc_rooms->Day6 : 0, 2)."%" ?? '-' }}</td>
                      <td class="text-right">{{ number_format(isset($fc_rooms->Day7) ? $fc_rooms->Day7 : 0, 2)."%" ?? '-' }}</td>
                      <td class="text-right">{{ number_format(isset($fc_rooms->Day8) ? $fc_rooms->Day8 : 0, 2)."%" ?? '-' }}</td>
                      <td class="text-right">{{ number_format(isset($fc_rooms->Day9) ? $fc_rooms->Day9 : 0, 2)."%" ?? '-' }}</td>
                      <td class="text-right">{{ number_format(isset($fc_rooms->Day10) ? $fc_rooms->Day10 : 0, 2)."%" ?? '-' }}</td>
                      <td class="text-right">{{ number_format(isset($fc_rooms->Day11) ? $fc_rooms->Day11 : 0, 2)."%" ?? '-' }}</td>
                      <td class="text-right">{{ number_format(isset($fc_rooms->Day12) ? $fc_rooms->Day12 : 0, 2)."%" ?? '-' }}</td>
                      <td class="text-right">{{ number_format(isset($fc_rooms->Day13) ? $fc_rooms->Day13 : 0, 2)."%" ?? '-' }}</td>
                      <td class="text-right">{{ number_format(isset($fc_rooms->Day14) ? $fc_rooms->Day14 : 0, 2)."%" ?? '-' }}</td>

                      @else
                      <td class="text-right">{{ number_format(isset($fc_rooms->Day1) ? $fc_rooms->Day1 : 0 ) ?? '-' }}</td>
                      <td class="text-right">{{ number_format(isset($fc_rooms->Day2) ? $fc_rooms->Day2 : 0) ?? '-' }}</td>
                      <td class="text-right">{{ number_format(isset($fc_rooms->Day3) ? $fc_rooms->Day3 : 0) ?? '-' }}</td>
                      <td class="text-right">{{ number_format(isset($fc_rooms->Day4) ? $fc_rooms->Day4 : 0) ?? '-' }}</td>
                      <td class="text-right">{{ number_format(isset($fc_rooms->Day5) ? $fc_rooms->Day5 : 0) ?? '-' }}</td>
                      <td class="text-right">{{ number_format(isset($fc_rooms->Day6) ? $fc_rooms->Day6 : 0) ?? '-' }}</td>
                      <td class="text-right">{{ number_format(isset($fc_rooms->Day7) ? $fc_rooms->Day7 : 0) ?? '-' }}</td>
                      <td class="text-right">{{ number_format(isset($fc_rooms->Day8) ? $fc_rooms->Day8 : 0) ?? '-' }}</td>
                      <td class="text-right">{{ number_format(isset($fc_rooms->Day9) ? $fc_rooms->Day9 : 0) ?? '-' }}</td>
                      <td class="text-right">{{ number_format(isset($fc_rooms->Day10) ? $fc_rooms->Day10 : 0) ?? '-' }}</td>
                      <td class="text-right">{{ number_format(isset($fc_rooms->Day11) ? $fc_rooms->Day11 : 0) ?? '-' }}</td>
                      <td class="text-right">{{ number_format(isset($fc_rooms->Day12) ? $fc_rooms->Day12 : 0) ?? '-' }}</td>
                      <td class="text-right">{{ number_format(isset($fc_rooms->Day13) ? $fc_rooms->Day13 : 0) ?? '-' }}</td>
                      <td class="text-right">{{ number_format(isset($fc_rooms->Day14) ? $fc_rooms->Day14 : 0) ?? '-' }}</td>

                      @endif
                    </tr>

                    @php
                      $loop_now_forecast++;
                    @endphp
                  @endforeach
              @endforeach

              @if(isset($resort['DATA_TOTAL']) && $resort['DATA_TOTAL'])
                @php
                  $loop_now_forecast = 0;
                @endphp

                @foreach($resort['DATA_TOTAL'] as $fc_total)
                  <tr class="fixed total" style="background: #eaeaea !important">
                    @if($loop_now_forecast == 0)
                      <td class="text-center header total" style="vertical-align: middle;" rowspan="{{ count($resort['DATA_TOTAL']) }}">
                      <b>TOTAL ALL</b>
                      </td>
                    @endif

                    <td class="text-left header period total"><b>{{ $fc_total->RoomCategory }}</b></td>
                    @if(strtolower($fc_total->RoomCategory) == 'occupancy %')
                    <td class="text-right">{{ number_format(isset($fc_total->Day1) ? $fc_total->Day1 : 0, 2)."%" ?? '-' }}</td>
                    <td class="text-right">{{ number_format(isset($fc_total->Day2) ? $fc_total->Day2 : 0, 2)."%" ?? '-' }}</td>
                    <td class="text-right">{{ number_format(isset($fc_total->Day3) ? $fc_total->Day3 : 0, 2)."%" ?? '-' }}</td>
                    <td class="text-right">{{ number_format(isset($fc_total->Day4) ? $fc_total->Day4 : 0, 2)."%" ?? '-' }}</td>
                    <td class="text-right">{{ number_format(isset($fc_total->Day5) ? $fc_total->Day5 : 0, 2)."%" ?? '-' }}</td>
                    <td class="text-right">{{ number_format(isset($fc_total->Day6) ? $fc_total->Day6 : 0, 2)."%" ?? '-' }}</td>
                    <td class="text-right">{{ number_format(isset($fc_total->Day7) ? $fc_total->Day7 : 0, 2)."%" ?? '-' }}</td>
                    <td class="text-right">{{ number_format(isset($fc_total->Day8) ? $fc_total->Day8 : 0, 2)."%" ?? '-' }}</td>
                    <td class="text-right">{{ number_format(isset($fc_total->Day9) ? $fc_total->Day9 : 0, 2)."%" ?? '-' }}</td>
                    <td class="text-right">{{ number_format(isset($fc_total->Day10) ? $fc_total->Day10 : 0, 2)."%" ?? '-' }}</td>
                    <td class="text-right">{{ number_format(isset($fc_total->Day11) ? $fc_total->Day11 : 0, 2)."%" ?? '-' }}</td>
                    <td class="text-right">{{ number_format(isset($fc_total->Day12) ? $fc_total->Day12 : 0, 2)."%" ?? '-' }}</td>
                    <td class="text-right">{{ number_format(isset($fc_total->Day13) ? $fc_total->Day13 : 0, 2)."%" ?? '-' }}</td>
                    <td class="text-right">{{ number_format(isset($fc_total->Day14) ? $fc_total->Day14 : 0, 2)."%" ?? '-' }}</td>

                    @else
                    <td class="text-right">{{ number_format(isset($fc_total->Day1) ? $fc_total->Day1 : 0 ) ?? '-' }}</td>
                    <td class="text-right">{{ number_format(isset($fc_total->Day2) ? $fc_total->Day2 : 0) ?? '-' }}</td>
                    <td class="text-right">{{ number_format(isset($fc_total->Day3) ? $fc_total->Day3 : 0) ?? '-' }}</td>
                    <td class="text-right">{{ number_format(isset($fc_total->Day4) ? $fc_total->Day4 : 0) ?? '-' }}</td>
                    <td class="text-right">{{ number_format(isset($fc_total->Day5) ? $fc_total->Day5 : 0) ?? '-' }}</td>
                    <td class="text-right">{{ number_format(isset($fc_total->Day6) ? $fc_total->Day6 : 0) ?? '-' }}</td>
                    <td class="text-right">{{ number_format(isset($fc_total->Day7) ? $fc_total->Day7 : 0) ?? '-' }}</td>
                    <td class="text-right">{{ number_format(isset($fc_total->Day8) ? $fc_total->Day8 : 0) ?? '-' }}</td>
                    <td class="text-right">{{ number_format(isset($fc_total->Day9) ? $fc_total->Day9 : 0) ?? '-' }}</td>
                    <td class="text-right">{{ number_format(isset($fc_total->Day10) ? $fc_total->Day10 : 0) ?? '-' }}</td>
                    <td class="text-right">{{ number_format(isset($fc_total->Day11) ? $fc_total->Day11 : 0) ?? '-' }}</td>
                    <td class="text-right">{{ number_format(isset($fc_total->Day12) ? $fc_total->Day12 : 0) ?? '-' }}</td>
                    <td class="text-right">{{ number_format(isset($fc_total->Day13) ? $fc_total->Day13 : 0) ?? '-' }}</td>
                    <td class="text-right">{{ number_format(isset($fc_total->Day14) ? $fc_total->Day14 : 0) ?? '-' }}</td>

                    @endif
                  </tr>

                  @php
                    $loop_now_forecast++;
                  @endphp
                @endforeach

              @endif
            @else
              <tr>
                <td colspan="16" class="bg-black text-white text-center">No Data Available</td>
              </tr>
            @endif

          @endforeach
      @else
        <tr>
          <td colspan="16" class="bg-black text-white text-center">No Data Available</td>
        </tr>
      @endif
      </tbody>
  </table>
</div>