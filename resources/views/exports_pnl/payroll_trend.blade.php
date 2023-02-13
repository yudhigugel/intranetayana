<table class="table table-bordered" id="payroll-trend-table" style="width: auto; margin-bottom: 1em; margin-right: 20px; white-space: nowrap;">
  <thead>
    @if(isset($data['months']) && count($data['months']) > 0)
      <tr class="first">
        <th rowspan="2" style="width: 13%" class="bg-secondary text-white">Type</th>
        @foreach($data['months'] as $month)
          <th style="width: 7.25%" class="bg-secondary text-white">{{ isset($month['period']) ? $month['period'] : '-' }}</th>
        @endforeach
      </tr>
      <tr class="second">
        @foreach($data['months'] as $month)
          <th style="width: 7.25%" class="bg-secondary text-white">{{ isset($month['month_name']) ? $month['month_name'] : '-' }}</th>
        @endforeach
      </tr>
      <tr>
        <th><b>Payroll Trend Analysis</b></th>
        @foreach($data['months'] as $month)
          <th><b>Actual</b></th>
        @endforeach
      </tr>
      <tr>
        <th style="font-weight: normal;">Staff Payroll</th>
        @foreach($data['months'] as $month)
          <th>0</th>
        @endforeach
      </tr>
      <tr>
        <th style="font-weight: normal;">Overtime Payroll</th>
        @foreach($data['months'] as $month)
          <th>0</th>
        @endforeach
      </tr>
      <tr>
        <th style="font-weight: normal;">Bonus & Festive</th>
        @foreach($data['months'] as $month)
          <th>0</th>
        @endforeach
      </tr>
      <tr>
        <th style="font-weight: normal;">Salaries Recovered</th>
        @foreach($data['months'] as $month)
          <th>0</th>
        @endforeach
      </tr>
      <tr>
        <th style="font-weight: normal;">Miscellaneous Payroll</th>
        @foreach($data['months'] as $month)
          <th>0</th>
        @endforeach
      </tr>
      <tr>
        <th style="font-weight: normal;">Contract Labor / Daily Workers</th>
        @foreach($data['months'] as $month)
          <th>0</th>
        @endforeach
      </tr>
      <tr>
        <th style="font-weight: normal;">Pension Fund</th>
        @foreach($data['months'] as $month)
          <th>0</th>
        @endforeach
      </tr>
      <tr>
        <th style="font-weight: normal;">Vacation</th>
        @foreach($data['months'] as $month)
          <th>0</th>
        @endforeach
      </tr>
      <tr>
        <th style="font-weight: normal;">Employee Meals</th>
        @foreach($data['months'] as $month)
          <th>0</th>
        @endforeach
      </tr>
      <tr>
        <th style="background-color: #eaeaea !important">Total All</th>
        @foreach($data['months'] as $month)
          <th style="background-color: #eaeaea !important">0</th>
        @endforeach
      </tr>
      <tr>
        <th style="background-color: #eaeaea !important">Total Gross Revenue</th>
        @foreach($data['months'] as $month)
          <th style="background-color: #eaeaea !important">0</th>
        @endforeach
      </tr>
      <tr>
        <th style="background-color: #eaeaea !important">Ratio</th>
        @foreach($data['months'] as $month)
          <th style="background-color: #eaeaea !important">0</th>
        @endforeach
      </tr>
      <tr>
        <th style="background-color: #eaeaea !important">Total Room Sold</th>
        @foreach($data['months'] as $month)
          <th style="background-color: #eaeaea !important">0</th>
        @endforeach
      </tr>
      <tr>
        <th style="background-color: #eaeaea !important">Total Head Count</th>
        @foreach($data['months'] as $month)
          <th style="background-color: #eaeaea !important">0</th>
        @endforeach
      </tr>

    @else
    <tr>
      <th>No Data Available</th>
    </tr>
    @endif
  </thead>
</table>