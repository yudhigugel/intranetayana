<div id="breakpoint">
   <table id="table-report-revenue" style="border: 1px solid #000;" cellspacing="0" width="100%">
     <thead>
        <tr>
         <th style="text-align: center;padding: 10px;border: 1px solid black;border-collapse: collapse;" class="all" width="">BUSINESS DATE</th>
         <th style="text-align: center;padding: 10px;border: 1px solid black;border-collapse: collapse;" class="all" width="">RESORT</th>
         <th style="text-align: center;padding: 10px;border: 1px solid black;border-collapse: collapse;" class="all" class="all text-center">ROOM CLASS</th>
         <th style="text-align: center;padding: 10px;border: 1px solid black;border-collapse: collapse;" class="all" class="none text-center" width="">OCC %</th>
         <th style="text-align: center;padding: 10px;border: 1px solid black;border-collapse: collapse;" class="all" class="all text-center" width="">ROOM REVENUE</th>
         <th style="text-align: center;padding: 10px;border: 1px solid black;border-collapse: collapse;" class="all" class="all text-center" width="">FNB REVENUE</th>
         <th style="text-align: center;padding: 10px;border: 1px solid black;border-collapse: collapse;" class="all" class="none text-center">TOTAL REVENUE</th>
        </tr>
     </thead>
     <tbody>
       @if($data_table)
          @foreach($data_table as $key => $value)
            <tr>
              <td style="text-align: center;padding: 10px;border: 1px solid black;border-collapse: collapse;">{{ date('d M Y',strtotime($value->BUSINESS_DATE)) }}</td>
              <td style="text-align: center;padding: 10px;border: 1px solid black;border-collapse: collapse;">{{$value->RESORT}}</td>
              <td style="text-align: center;padding: 10px;border: 1px solid black;border-collapse: collapse;">{{$value->ROOM_CLASS}}</td>
              <td style="text-align: center;padding: 10px;border: 1px solid black;border-collapse: collapse;">{{ number_format($value->OCC_PCT, 2, '.', ',') }} %</td>
              <td style="text-align: center;padding: 10px;border: 1px solid black;border-collapse: collapse;">IDR {{ number_format($value->ROOM_REVENUE, 0, '.', ',') }}</td>
              <td style="text-align: center;padding: 10px;border: 1px solid black;border-collapse: collapse;">IDR {{ number_format($value->FOOD_BEV_REVENUE, 0, '.', ',') }}</td>
              <td style="text-align: center;padding: 10px;border: 1px solid black;border-collapse: collapse;">IDR {{ number_format($value->TOTAL_REVENUE, 0, '.', ',') }}</td>
            </tr>
          @endforeach
        @endif
     </tbody>
   </table>
</div>
          


