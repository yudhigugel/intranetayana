<div class="table-container-h table-responsive table-floating-overflow" style="max-height: 500px">
    <table class="table table-border" id="content-table">
      <thead style="white-space: nowrap;" class="no-wrap-th">
          <tr class="first fixed">
            <th style="width: 10%;z-index: 5" class="bg-secondary text-white text-center header"><b>Resort / Subresort</b></th>
            <th class="bg-secondary text-white text-left header period" style="z-index: 5"><b>Period</b></th>
            <th class="bg-secondary text-white text-left"><b>Total</b> <br>Rooms In Hotel</th>
            <th class="bg-secondary text-white text-left"><b>Total</b> <br>Room To Sell </th>
            <th class="bg-secondary text-white text-left"><b>Room</b> <br>Total Occupancy</th>
            <th class="bg-secondary text-white text-left"><b>Room</b> <br>Occupancy (%)</th>
            <th class="bg-secondary text-white text-left"><b>Room</b> <br>Total Revenue</th>
            <th class="bg-secondary text-white text-left"><b>Room</b> <br>ADR</th>
            <th class="bg-secondary text-white text-left"><b>Room</b> <br>RevPar</th>
            <th class="bg-secondary text-white text-left"><b>F&amp;B</b> <br>Total Guest</th>
            <th class="bg-secondary text-white text-left"><b>F&amp;B</b> <br>Total Revenue</th>
            <th class="bg-secondary text-white text-left"><b>Spa</b> <br>Total Guest</th>
            <th class="bg-secondary text-white text-left"><b>Spa</b> <br>Total Revenue</th>
            <th class="bg-secondary text-white text-left"><b>Others</b> <br>Total Revenue</th>
            <th class="bg-secondary text-white text-left"><b>Resort</b> <br>Total Revenue</th>
          </tr>
      </thead>
      <tbody>
        @if(isset($data['resort_summary']) && $data['resort_summary'])
            <!-- Loop Company Code -->
            @foreach($data['resort_summary'] as $company_code => $resort)
                <tr>
                  <td colspan="15" class="bg-black text-white text-center"><b>{{ $company_code }}</b></td>
                </tr>
                <!-- Loop Resort / Property -->
                @if(isset($resort['PROPERTY']))
                  @foreach($resort['PROPERTY'] as $key_resort => $per_resort)
                    <!-- Loop TOday MTD YTD -->
                    @php
                      $loop_now = 0;
                    @endphp
                    @foreach($per_resort as $key_period => $resort_sm)
                    <tr class="fixed">
                      @if($loop_now == 0)
                      <td class="text-center header" style="vertical-align: middle;" rowspan="{{ count($per_resort) }}">
                      <b>{{ $key_resort }}</b>
                      </td>
                      @endif
                      <td class="period header">
                        @if(strtolower($key_period) == 'today')
                        {{ isset($data['date_resort']) ? date('d M Y',strtotime($data['date_resort'])) : date('d M Y') }}
                        @else
                        {{ $key_period }}
                        @endif
                      </td>
                      <td class="text-right">{{ number_format($resort_sm[0]->TotalPhysicalRoom ?? 0) }}</td>
                      <td class="text-right">{{ number_format($resort_sm[0]->TotalAvailability ?? 0) }}</td>
                      <td class="text-right">{{ number_format($resort_sm[0]->TotalOccupancy ?? 0) }}</td>
                      <td class="text-right">{{ number_format($resort_sm[0]->OccupancyPctg ?? 0, 2)."%" }}</td>
                      <td class="text-right">{{ number_format($resort_sm[0]->TodayRevenue ?? 0)}}</td>
                      <td class="text-right">{{ number_format($resort_sm[0]->ADR ?? 0) }}</td>
                      <td class="text-right">{{ number_format($resort_sm[0]->RevPar ?? 0) }}</td>
                      <td class="text-right">{{ number_format($resort_sm[0]->FnBTotalGuest ?? 0) }}</td>
                      <td class="text-right">{{ number_format($resort_sm[0]->FnBTotalRevenue ?? 0) }}</td>
                      <td class="text-right">{{ number_format($resort_sm[0]->SpaTotalGuest ?? 0) }}</td>
                      <td class="text-right">{{ number_format($resort_sm[0]->SpaTotalRevenue ?? 0) }}</td>
                      <td class="text-right">{{ number_format($resort_sm[0]->OtherTotalRevenue ?? 0) }}</td>
                      <td class="text-right">{{ number_format($resort_sm[0]->ResortTotalRevenue ?? 0) }}</td>
                    </tr>
                    @php
                      $loop_now ++;
                    @endphp
                    @endforeach
                    <!-- END Loop Today MTD YTD -->
                  @endforeach
                  <!-- END Loop Resort / Property -->
                <!-- CURRENT -->
                <tr class="fixed" style="background: #eaeaea !important">
                  <td class="text-center header total" rowspan="3" style="vertical-align: middle;"><b>TOTAL ALL</b></td>
                  <td class="period header total"><b>{{ isset($data['date_resort']) ? date('d M Y', strtotime($data['date_resort'])) : date('d F Y') }}</b></td>
                  <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['TODAY_PHYSICAL']) ? number_format($resort['TOTAL_SUMMARY']['TODAY_PHYSICAL']) : '-' }}</b></td>
                  <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['TODAY_AVAILABLE']) ? number_format($resort['TOTAL_SUMMARY']['TODAY_AVAILABLE']) : '-' }}</b></td>
                  <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['TODAY_OCCUPANCY']) ? number_format($resort['TOTAL_SUMMARY']['TODAY_OCCUPANCY']) : '-' }}</b></td>
                  <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['TODAY_OCC_PCTG']) ? number_format($resort['TOTAL_SUMMARY']['TODAY_OCC_PCTG'], 2)."%" : '-' }}</b></td>
                  <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['TODAY_ROOM_REVENUE']) ? number_format($resort['TOTAL_SUMMARY']['TODAY_ROOM_REVENUE']) : '-' }}</b></td>
                  <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['TODAY_ADR']) ? number_format($resort['TOTAL_SUMMARY']['TODAY_ADR']) : '-' }}</b></td>
                  <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['TODAY_REVPAR']) ? number_format($resort['TOTAL_SUMMARY']['TODAY_REVPAR']) : '-' }}</b></td>
                  <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['TODAY_GUEST']) ? number_format($resort['TOTAL_SUMMARY']['TODAY_GUEST']) : '-' }}</b></td>
                  <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['TODAY_FNB_REVENUE']) ? number_format($resort['TOTAL_SUMMARY']['TODAY_FNB_REVENUE']) : '-' }}</b></td>
                  <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['TODAY_SPA_GUEST']) ? number_format($resort['TOTAL_SUMMARY']['TODAY_SPA_GUEST']) : '-' }}</b></td>
                  <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['TODAY_SPA_REVENUE']) ? number_format($resort['TOTAL_SUMMARY']['TODAY_SPA_REVENUE']) : '-' }}</b></td>
                  <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['OTHER_TOTAL_REVENUE']) ? number_format($resort['TOTAL_SUMMARY']['OTHER_TOTAL_REVENUE']) : '-' }}</b></td>
                  <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['TODAY_TOTAL_REVENUE']) ? number_format($resort['TOTAL_SUMMARY']['TODAY_TOTAL_REVENUE']) : '-' }}</b></td>
                </tr>
                <!-- MTD -->
                <tr class="total" style="background-color: #eaeaea">
                  <td class="period header total"><b>MTD</b></td>
                  <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['MTD_PHYSICAL']) ? number_format($resort['TOTAL_SUMMARY']['MTD_PHYSICAL']) : '-' }}</b></td>
                  <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['MTD_AVAILABLE']) ? number_format($resort['TOTAL_SUMMARY']['MTD_AVAILABLE']) : '-' }}</b></td>
                  <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['MTD_OCCUPANCY']) ? number_format($resort['TOTAL_SUMMARY']['MTD_OCCUPANCY']) : '-' }}</b></td>
                  <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['MTD_OCC_PCTG']) ? number_format($resort['TOTAL_SUMMARY']['MTD_OCC_PCTG'],2)."%" : '-' }}</b></td>
                  <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['MTD_ROOM_REVENUE']) ? number_format($resort['TOTAL_SUMMARY']['MTD_ROOM_REVENUE']) : '-' }}</b></td>
                  <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['MTD_ADR']) ? number_format($resort['TOTAL_SUMMARY']['MTD_ADR']) : '-' }}</b></td>
                  <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['MTD_REVPAR']) ? number_format($resort['TOTAL_SUMMARY']['MTD_REVPAR']) : '-' }}</b></td>
                  <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['MTD_GUEST']) ? number_format($resort['TOTAL_SUMMARY']['MTD_GUEST']) : '-' }}</b></td>
                  <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['MTD_FNB_REVENUE']) ? number_format($resort['TOTAL_SUMMARY']['MTD_FNB_REVENUE']) : '-' }}</b></td>
                  <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['MTD_SPA_GUEST']) ? number_format($resort['TOTAL_SUMMARY']['MTD_SPA_GUEST']) : '-' }}</b></td>
                  <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['MTD_SPA_REVENUE']) ? number_format($resort['TOTAL_SUMMARY']['MTD_SPA_REVENUE']) : '-' }}</b></td>
                  <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['MTD_OTHER_TOTAL_REVENUE']) ? number_format($resort['TOTAL_SUMMARY']['MTD_OTHER_TOTAL_REVENUE']) : '-' }}</b></td>
                  <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['MTD_TOTAL_REVENUE']) ? number_format($resort['TOTAL_SUMMARY']['MTD_TOTAL_REVENUE']) : '-' }}</b></td>
                </tr>
                <!-- YTD -->
                <tr class="total" style="background-color: #eaeaea">
                  <td class="period header total"><b>YTD</b></td>
                  <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['YTD_PHYSICAL']) ? number_format($resort['TOTAL_SUMMARY']['YTD_PHYSICAL']) : '-' }}</b></td>
                  <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['YTD_AVAILABLE']) ? number_format($resort['TOTAL_SUMMARY']['YTD_AVAILABLE']) : '-' }}</b></td>
                  <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['YTD_OCCUPANCY']) ? number_format($resort['TOTAL_SUMMARY']['YTD_OCCUPANCY']) : '-' }}</b></td>
                  <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['YTD_OCC_PCTG']) ? number_format($resort['TOTAL_SUMMARY']['YTD_OCC_PCTG'],2)."%" : '-' }}</b></td>
                  <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['YTD_ROOM_REVENUE']) ? number_format($resort['TOTAL_SUMMARY']['YTD_ROOM_REVENUE']) : '-' }}</b></td>
                  <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['YTD_ADR']) ? number_format($resort['TOTAL_SUMMARY']['YTD_ADR']) : '-' }}</b></td>
                  <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['YTD_REVPAR']) ? number_format($resort['TOTAL_SUMMARY']['YTD_REVPAR']) : '-' }}</b></td>
                  <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['YTD_GUEST']) ? number_format($resort['TOTAL_SUMMARY']['YTD_GUEST']) : '-' }}</b></td>
                  <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['YTD_FNB_REVENUE']) ? number_format($resort['TOTAL_SUMMARY']['YTD_FNB_REVENUE']) : '-' }}</b></td>
                  <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['YTD_SPA_GUEST']) ? number_format($resort['TOTAL_SUMMARY']['YTD_SPA_GUEST']) : '-' }}</b></td>
                  <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['YTD_SPA_REVENUE']) ? number_format($resort['TOTAL_SUMMARY']['YTD_SPA_REVENUE']) : '-' }}</b></td>
                  <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['YTD_OTHER_TOTAL_REVENUE']) ? number_format($resort['TOTAL_SUMMARY']['YTD_OTHER_TOTAL_REVENUE']) : '-' }}</b></td>
                  <td style="text-align: right;"><b>{{ isset($resort['TOTAL_SUMMARY']['YTD_TOTAL_REVENUE']) ? number_format($resort['TOTAL_SUMMARY']['YTD_TOTAL_REVENUE']) : '-' }}</b></td>
                </tr>
                @else
                  <tr>
                    <td colspan="15" class="bg-black text-white text-center">No Data Available</td>
                  </tr>
                @endif
            @endforeach
          @else
            <tr>
              <td colspan="15" class="bg-black text-white text-center">No Data Available</td>
            </tr>
          @endif
      </tbody>
    </table>
</div>