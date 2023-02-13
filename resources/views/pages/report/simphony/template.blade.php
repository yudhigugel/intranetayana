<div class="filter-wrapper form-group mb-3">
  <div class="row filter-align align-items-center">
  </div>
  <hr class="my-3">
  <div class="d-flex" style="justify-content: space-around;">
    <div class="">
      <h6 class="text-primary"><i class="mdi mdi-account-check"></i>&nbsp;TOTAL GUEST</h6>
      <h3 class="text-black guest-today-all">0</h3>
    </div>
    <div class="">
      <h6 class="text-primary"><i class="mdi mdi-chart-line"></i>&nbsp;TOTAL REVENUE</h6>
      <h3 class="text-black revenue-today-all">0</h3>
    </div>
    <div class="">
      <h6 class="text-primary"><i class="mdi mdi-account-check"></i>&nbsp;TOTAL MTD GUEST</h6>
      <h3 class="text-black guest-mtd-all">0</h3>
    </div>
    <div class="">
      <h6 class="text-primary"><i class="mdi mdi-chart-line"></i>&nbsp;TOTAL MTD REVENUE</h6>
      <h3 class="text-black revenue-mtd-all">0</h3>
    </div>
    <div class="">
      <h6 class="text-primary"><i class="mdi mdi-account-check"></i>&nbsp;TOTAL YTD GUEST</h6>
      <h3 class="text-black guest-ytd-all">0</h3>
    </div>
    <div class="">
      <h6 class="text-primary"><i class="mdi mdi-chart-line"></i>&nbsp;TOTAL YTD REVENUE</h6>
      <h3 class="text-black revenue-ytd-all">0</h3>
    </div>
  </div> 
</div>
<div class="table-wrapper">
  <div class="table-container-h table-responsive">
      <table class="table table-border" id="content-table">
        <thead>
          <tr>
            <th class="bg-secondary text-white text-center" style="width: 5%">NO</th>
            <th class="bg-secondary text-white text-center" style="width: 15%">OUTLET NAME</a></th>
            <th class="bg-secondary text-white text-left"   style="width: 8%">IS F&B OUTLET</a></th>
            <th class="bg-secondary text-white text-center" style="width: 8%">RESORT</a></th>
            <th class="bg-secondary text-white text-center" style="width: 10%">SUB RESORT</a></th>
            <th class="bg-secondary text-white text-center" style="width: 8%">GUEST</a></th>
            <th class="bg-secondary text-white text-center" style="width: 10%">REVENUE (IDR)</a></th>
            <th class="bg-secondary text-white text-center" style="width: 8%">MTD GUEST</a></th>
            <th class="bg-secondary text-white text-center" style="width: 10%">MTD REVENUE (IDR)</a></th>
            <th class="bg-secondary text-white text-center" style="width: 8%">YTD GUEST</a></th>
            <th class="bg-secondary text-white text-center" style="width: 10%">YTD REVENUE (IDR)</a></th>
          </tr>
        </thead>
        <tbody>
          @if(isset($outlet) && $outlet)
            @php 
              $total_today_rev=$total_mtd_rev=$total_today_guest=$total_mtd_guest=0;
            @endphp
            @foreach($outlet as $ot)
              <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $ot->OUTLET }}</td>
                <td>{{ $ot->FB_YN == 'Y' ? 'YES' : 'NO' }}</td>
                <td>{{ $ot->RESORT }}</td>
                <td data-resort="{{ $ot->RESORT }}">{{ $ot->SUBRESORT }}</td>
                <td class="text-right">{{ number_format($ot->GUEST_TODAY ? $ot->GUEST_TODAY : 0) }}</td>
                <td class="text-right">
                  <a href="{{ route('ayana.pos_revenue.outlet') }}?{{ http_build_query(['BUSINESSDATESTART'=>app('request')->query('business_date', date('Y-m-d')),'BUSINESSDATEEND'=>app('request')->query('business_date', date('Y-m-d')), 'COSTCENTERID'=>$ot->SAPCOSTCENTER]) }}">{{ number_format($ot->TODAY_REVENUE ? $ot->TODAY_REVENUE : 0) }}</a>
                </td>
                <td class="text-right">{{ number_format($ot->GUEST_MTD ? $ot->GUEST_MTD : 0) }}</td>
                <td class="text-right">
                  <a href="{{ route('ayana.pos_revenue.outlet') }}?{{ http_build_query(['BUSINESSDATESTART'=>date('Y-m-01',strtotime(app('request')->query('business_date', date('Y-m-01')))),'BUSINESSDATEEND'=>app('request')->query('business_date', date('Y-m-d')), 'COSTCENTERID'=>$ot->SAPCOSTCENTER]) }}">{{ number_format($ot->MTD_REVENUE ? $ot->MTD_REVENUE : 0) }}</a>
                </td>
                <td class="text-right">{{ number_format($ot->GUEST_YTD ? $ot->GUEST_YTD : 0) }}</td>
                <td class="text-right">
                  <a href="{{ route('ayana.pos_revenue.outlet.ytd') }}?{{ http_build_query(['BUSINESSDATESTART'=>date('Y-m-01',strtotime(app('request')->query('business_date', date('Y-m-01')))),'BUSINESSDATEEND'=>app('request')->query('business_date', date('Y-m-d')), 'COSTCENTERID'=>$ot->SAPCOSTCENTER]) }}">{{ number_format($ot->YTD_REVENUE ? $ot->YTD_REVENUE : 0) }}</a>
                </td>
              </tr>
            @endforeach
          @endif
        </tbody>
      </table>
  </div>
</div>