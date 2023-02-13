<div class="table-container-h table-responsive">
    <table class="table table-border" id="content-table"s>
        <thead>
            <tr>
            <th class="bg-secondary text-white" style="width:5%">No</th>
            <th class="bg-secondary text-white" style="width:5%">SAP Material ID</th>
            <th class="bg-secondary text-white" style="width:40%">Food Name</th>
            <th class="bg-secondary text-white" style="width:5%">Qty</th>
            <th class="bg-secondary text-white" style="width:5%">Qty <br>MTD</th>
            {{--<th class="bg-secondary text-white" style="width:10%">Qty Warehouse <br> (Non Recipe)</th>--}}
            <th class="bg-secondary text-white" style="width:15%">Revenue</th>
            <th class="bg-secondary text-white" style="width:15%">Revenue <br>MTD</th>

            </tr>
        </thead>
        <tbody id="food-tbody">
            @if(count($data['top5_food'])>0)
              @php
                $i=0;
              @endphp
              @foreach ($data['top5_food'] as $tsf)
              @php
                $i++;
              @endphp
                <tr>
                  <td class="text-center">{{$i}}</td>
                  <td style="text-align: left;">{{ isset($tsf->SAPMATERIALCODE) ? $tsf->SAPMATERIALCODE : '-' }}</td>
                  <td style="text-align: left;">{{ isset($tsf->MENUITEMNAME) ? $tsf->MENUITEMNAME : '-'}}</td>
                  <td style="text-align: right;">{{ isset($tsf->TODAY_QTY) ? number_format($tsf->TODAY_QTY) : 0 }}</td>
                  <td style="text-align: right;">{{ isset($tsf->MTD_QTY) ? number_format($tsf->MTD_QTY) : 0 }}</td>
                  {{--<td style="text-align: right;">-</td>--}}
                  <td style="text-align: right;">{{ isset($tsf->TODAY_REV) ? number_format($tsf->TODAY_REV) : 0}}</td>
                  <td style="text-align: right;">{{ isset($tsf->MTD_REV) ? number_format($tsf->MTD_REV) : 0}}</td>
                </tr>
              @endforeach
            @else
              @php
                $padding = number_format((float)((0.5*count($data['top5_food']))), 1);
                $line = number_format((float)((1.5*count($data['top5_food']))+0.13), 2);
                if($padding <= 0)
                  $padding = 0.5;
                if($line < 1.5)
                  $line = 1.5;
              @endphp
              <tr>
                  <td style="padding-top: {{ $padding }}rem;padding-bottom: {{ $padding }}rem; line-height: {{ $line }}!important" colspan="8" class="text-center"> No data available </td>
              </tr>
            @endif
        </tbody>
    </table>
</div>
<div class="table-container-h table-responsive">
    <table class="table table-border" id="content-table">
        <thead>
            <tr>
              <th class="bg-green text-white" style="width:5%">No</th>
              <th class="bg-green text-white" style="width:5%">SAP Material ID</th>
              <th class="bg-green text-white" style="width:40%">Beverage Name</th>
              <th class="bg-green text-white" style="width:5%">Qty</th>
              <th class="bg-green text-white" style="width:5%">Qty <br>MTD</th>
              {{--<th class="bg-green text-white" style="width:10%">Qty Warehouse <br> (Non Recipe)</th>--}}
              <th class="bg-green text-white" style="width:15%">Revenue</th>
              <th class="bg-green text-white" style="width:15%">Revenue <br>MTD</th>
            </tr>
        </thead>
        <tbody id="beverage-tbody">
            @if(count($data['top5_beverage'])>0)
              @foreach ($data['top5_beverage'] as $tsb)
              <tr>
                  <td class="text-center">{{$loop->iteration}}</td>
                  <td style="text-align: left;">{{ isset($tsb->SAPMATERIALCODE) ? $tsb->SAPMATERIALCODE : '-' }}</td>
                  <td style="text-align: left;">{{ isset($tsb->MENUITEMNAME) ? $tsb->MENUITEMNAME : '-'}}</td>
                  <td style="text-align: right;">{{ isset($tsb->TODAY_QTY) ? number_format($tsb->TODAY_QTY) : 0 }}</td>
                  <td style="text-align: right;">{{ isset($tsb->MTD_QTY) ? number_format($tsb->MTD_QTY) : 0 }}</td>
                  {{--<td style="text-align: right;">-</td>--}}
                  <td style="text-align: right;">{{ isset($tsb->TODAY_REV) ? number_format($tsb->TODAY_REV) : 0}}</td>
                  <td style="text-align: right;">{{ isset($tsb->MTD_REV) ? number_format($tsb->MTD_REV) : 0}}</td>
              </tr>
              @endforeach
            @else
              @php
                $padding = number_format((float)((0.5*count($data['top5_food']))), 1);
                $line = number_format((float)((1.5*count($data['top5_food']))+0.13), 2);
                if($padding <= 0)
                  $padding = 0.5;
                if($line < 1.5)
                  $line = 1.5;
              @endphp
              <tr>
                  <td style="padding-top: {{ $padding }}rem;padding-bottom: {{ $padding }}rem; line-height: {{ $line }}!important" colspan="8" class="text-center">No Data Available</td>
              </tr>
            @endif
        </tbody>
    </table>
</div>