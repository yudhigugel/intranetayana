<div class="table-container-h table-responsive tableFixHead">
  <table class="table table-border" id="content-table">
      <thead>
          <tr>
            <th class="bg-secondary text-white">No.</th>
            <th class="bg-secondary text-white">Cruise & Boat</th>
            <th class="bg-secondary text-white">Revenue</th>
            <th class="bg-secondary text-white">Revenue MTD</th>
            <th class="bg-secondary text-white">Revenue YTD</th>
          </tr>
      </thead>
      <tbody>
        @if(isset($data['pnb1_revenue']) && $data['pnb1_revenue'])
            @php
              $revenue=$revenue_MTD=$revenue_YTD=0;
            @endphp
            @foreach($data['pnb1_revenue'] as $pnb)
              <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="text-left">{{ isset($pnb->Outlet) ? $pnb->Outlet : '-' }}</td>
                <td class="text-right">{{ isset($pnb->Revenue) ? number_format($pnb->Revenue) : 0 }}</td>
                <td class="text-right">{{ isset($pnb->Revenue_MTD) ? number_format($pnb->Revenue_MTD) : 0 }}</td>
                <td class="text-right">{{ isset($pnb->Revenue_YTD) ? number_format($pnb->Revenue_YTD) : 0 }}</td>
              </tr>

              @php
                $revenue += isset($pnb->Revenue) ? $pnb->Revenue : 0;
                $revenue_MTD += isset($pnb->Revenue_MTD) ? $pnb->Revenue_MTD : 0;
                $revenue_YTD += isset($pnb->Revenue_YTD) ? $pnb->Revenue_YTD : 0;
              @endphp
            @endforeach
        @else
          <tr>
            <td class="text-center" colspan="10">No Data</td>
          </tr>
        @endif
      </tbody>
      @if(isset($revenue))
      <tfoot>
         <tr style="background: #eaeaea !important">
            <td class="text-center header total" colspan="2" style="vertical-align: middle;"><b>TOTAL ALL</b></td>
            <td class="text-right"><b>{{ number_format($revenue) }}</b></td>
            <td class="text-right"><b>{{ number_format($revenue_MTD) }}</b></td>
            <td class="text-right"><b>{{ number_format($revenue_YTD) }}</b></td>
          </tr>
      </tfoot>
      @endif
  </table>
</div>