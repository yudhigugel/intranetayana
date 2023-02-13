<table class="table table-bordered table-tree" id="summary-all-table" style="width: auto; margin-bottom: 1em; margin-right: 20px; white-space: nowrap;">
  <thead>
    <tr class="first">
      <th colspan="9" class="bg-secondary text-white" data-title="title-big"><h6 class="mb-0">Current Period</h6></th>
      <th colspan="8" class="bg-secondary text-white" data-title="title-big"><h6 class="mb-0">Year-To-Date</h6></th>
    </tr>
    <tr class="period second fixed">
      <th class="current" style="width: 10%">Category</th>
      <th class="current" style="width: 5.2%">Actual</th>
      <th class="current" style="width: 3%">(%)</th>
      <th class="current" style="width: 5.2%">Last Month</th>
      <th class="current" style="width: 3%">(%)</th>
      <th class="current" style="width: 5.2%">Variance</th>
      <th class="current" style="width: 3%">(%)</th>
      <th class="current" style="width: 5.2%">Last Year</th>
      <th class="current" style="width: 3%">(%)</th>

      <th class="ytd" style="width: 5.2%">Actual</th>
      <th class="ytd" style="width: 3%">(%)</th>
      <th class="ytd" style="width: 5.2%">Last Month</th>
      <th class="ytd" style="width: 3%">(%)</th>
      <th class="ytd" style="width: 5.2%">Variance</th>
      <th class="ytd" style="width: 3%">(%)</th>
      <th class="ytd" style="width: 5.2%">Last Year</th>
      <th class="ytd" style="width: 3%">(%)</th>
    </tr>
  </thead>
  <tbody>
    @if(isset($data['ROOM_STATISTIC_SUMMARY_ALL']['DATA']) && count($data['ROOM_STATISTIC_SUMMARY_ALL']['DATA']))
      @foreach($data['ROOM_STATISTIC_SUMMARY_ALL']['DATA'] as $key => $statistic)      
      <tr class="fixed">
        <td class="text-left">{{ isset($statistic->KPI) ? $statistic->KPI : 'Unknown Statistic' }}</td>

        <!-- MTD -->
        @if(isset($statistic->KPI_CODE) && $statistic->KPI_CODE == 'OCC')
          <td class="text-center">{{ isset($statistic->ActualMTD) ? number_format($statistic->ActualMTD,2)."%" : number_format(0,2)."%" }}</td>
        @elseif(isset($statistic->KPI_CODE) && $statistic->KPI_CODE == 'RDT')
          <td class="text-center">{{ isset($statistic->ActualMTD) ? number_format($statistic->ActualMTD,2) : number_format(0,2) }}</td>
        @else
          <td class="text-center">{{ isset($statistic->ActualMTD) ? number_format($statistic->ActualMTD) : 0 }}</td>
        @endif

        <td class="text-center">-</td>

        @if(isset($statistic->KPI_CODE) && $statistic->KPI_CODE == 'OCC')
          <td class="text-center">{{ isset($statistic->LastMonthMTD) ? number_format($statistic->LastMonthMTD, 2)."%" : number_format(0, 2)."%" }}</td>
        @elseif(isset($statistic->KPI_CODE) && $statistic->KPI_CODE == 'RDT')
          <td class="text-center">{{ isset($statistic->LastMonthMTD) ? number_format($statistic->LastMonthMTD, 2) : number_format(0, 2) }}</td>
        @else
          <td class="text-center">{{ isset($statistic->LastMonthMTD) ? number_format($statistic->LastMonthMTD) : number_format(0) }}</td>
        @endif

        <td class="text-center">-</td>

        @if(isset($statistic->KPI_CODE) && $statistic->KPI_CODE == 'OCC')
          <td class="text-center">{{ isset($statistic->VarianceMTD) ? number_format($statistic->VarianceMTD, 2)."%" : number_format(0, 2)."%" }}</td>
        @elseif(isset($statistic->KPI_CODE) && $statistic->KPI_CODE == 'RDT')
          <td class="text-center">{{ isset($statistic->VarianceMTD) ? number_format($statistic->VarianceMTD, 2) : number_format(0, 2) }}</td>
        @else
          <td class="text-center">{{ isset($statistic->VarianceMTD) ? number_format($statistic->VarianceMTD) : number_format(0) }}</td>
        @endif

        <td class="text-center">{{ isset($statistic->PctgMTD) ? number_format($statistic->PctgMTD, 2)."%" : number_format(0, 2)."%" }}</td>

        @if(isset($statistic->KPI_CODE) && $statistic->KPI_CODE == 'OCC')
          <td class="text-center">{{ isset($statistic->LastYearMTD) ? number_format($statistic->LastYearMTD, 2)."%" : number_format(0)."%" }}</td>
        @elseif(isset($statistic->KPI_CODE) && $statistic->KPI_CODE == 'RDT')
          <td class="text-center">{{ isset($statistic->LastYearMTD) ? number_format($statistic->LastYearMTD, 2) : number_format(0, 2) }}</td>
        @else
          <td class="text-center">{{ isset($statistic->LastYearMTD) ? number_format($statistic->LastYearMTD) : number_format(0) }}</td>
        @endif

        <td class="text-center">-</td>

        <!-- YTD -->
        @if(isset($statistic->KPI_CODE) && $statistic->KPI_CODE == 'OCC')
          <td class="text-center">{{ isset($statistic->ActualYTD) ? number_format($statistic->ActualYTD,2)."%" : number_format(0,2)."%" }}</td>
        @elseif(isset($statistic->KPI_CODE) && $statistic->KPI_CODE == 'RDT')
          <td class="text-center">{{ isset($statistic->ActualYTD) ? number_format($statistic->ActualYTD,2) : number_format(0,2) }}</td>
        @else
          <td class="text-center">{{ isset($statistic->ActualYTD) ? number_format($statistic->ActualYTD) : 0 }}</td>
        @endif

        <td class="text-center">-</td>

        @if(isset($statistic->KPI_CODE) && $statistic->KPI_CODE == 'OCC')
          <td class="text-center">{{ isset($statistic->LastMonthYTD) ? number_format($statistic->LastMonthYTD, 2)."%" : number_format(0, 2)."%" }}</td>
        @elseif(isset($statistic->KPI_CODE) && $statistic->KPI_CODE == 'RDT')
          <td class="text-center">{{ isset($statistic->LastMonthYTD) ? number_format($statistic->LastMonthYTD, 2) : number_format(0, 2) }}</td>
        @else
          <td class="text-center">{{ isset($statistic->LastMonthYTD) ? number_format($statistic->LastMonthYTD) : number_format(0) }}</td>
        @endif

        <td class="text-center">-</td>

        @if(isset($statistic->KPI_CODE) && $statistic->KPI_CODE == 'OCC')
          <td class="text-center">{{ isset($statistic->VarianceYTD) ? number_format($statistic->VarianceYTD, 2)."%" : number_format(0, 2)."%" }}</td>
        @elseif(isset($statistic->KPI_CODE) && $statistic->KPI_CODE == 'RDT')
          <td class="text-center">{{ isset($statistic->VarianceYTD) ? number_format($statistic->VarianceYTD, 2) : number_format(0, 2) }}</td>
        @else
          <td class="text-center">{{ isset($statistic->VarianceYTD) ? number_format($statistic->VarianceYTD) : number_format(0) }}</td>
        @endif

        <td class="text-center">{{ isset($statistic->PctgYTD) ? number_format($statistic->PctgYTD, 2)."%" : number_format(0, 2)."%" }}</td>

        @if(isset($statistic->KPI_CODE) && $statistic->KPI_CODE == 'OCC')
          <td class="text-center">{{ isset($statistic->LastYearYTD) ? number_format($statistic->LastYearYTD, 2)."%" : number_format(0)."%" }}</td>
        @elseif(isset($statistic->KPI_CODE) && $statistic->KPI_CODE == 'RDT')
          <td class="text-center">{{ isset($statistic->LastYearYTD) ? number_format($statistic->LastYearYTD, 2) : number_format(0, 2) }}</td>
        @else
          <td class="text-center">{{ isset($statistic->LastYearYTD) ? number_format($statistic->LastYearYTD) : number_format(0) }}</td>
        @endif

        <td class="text-center">-</td>
      </tr>
      @endforeach
      @if(isset($data['ROOM_STATISTIC_SUMMARY_ALL']['CATEGORY']) && count($data['ROOM_STATISTIC_SUMMARY_ALL']['CATEGORY']))
        @foreach($data['ROOM_STATISTIC_SUMMARY_ALL']['CATEGORY'] as $key_cat => $category)
          <tr class="fixed">
            <td class="text-left">{{ $key_cat }}</td>
            <td class="text-center">{{ isset($category['ACTUAL_CURRENT']) ? number_format($category['ACTUAL_CURRENT']) : 0 }}</td>
            <td class="text-center">-</td>
            <td class="text-center">{{ isset($category['LAST_MONTH_CURRENT']) ? number_format($category['LAST_MONTH_CURRENT']) : 0 }}</td>
            <td class="text-center">-</td>
            <td class="text-center">{{ isset($category['VARIANCE_CURRENT']) ? number_format($category['VARIANCE_CURRENT']) : 0 }}</td>
            <td class="text-center">{{ isset($category['VARIANCE_CURRENT_PCTG']) ? number_format($category['VARIANCE_CURRENT_PCTG'],2)."%" : number_format(0,2)."%" }}</td>
            <td class="text-center">{{ isset($category['LAST_YEAR_CURRENT']) ? number_format($category['LAST_YEAR_CURRENT']) : 0 }}</td>
            <td class="text-center">-</td>

            <td class="text-center">{{ isset($category['ACTUAL_YTD']) ? number_format($category['ACTUAL_YTD']) : 0 }}</td>
            <td class="text-center">-</td>
            <td class="text-center">{{ isset($category['LAST_MONTH_YTD']) ? number_format($category['LAST_MONTH_YTD']) : 0 }}</td>
            <td class="text-center">-</td>
            <td class="text-center">{{ isset($category['VARIANCE_YTD']) ? number_format($category['VARIANCE_YTD']) : 0 }}</td>
            <td class="text-center">{{ isset($category['VARIANCE_YTD_PCTG']) ? number_format($category['VARIANCE_YTD_PCTG'],2)."%" : number_format(0,2)."%" }}</td>
            <td class="text-center">{{ isset($category['LAST_YEAR_YTD']) ? number_format($category['LAST_YEAR_YTD']) : 0 }}</td>
            <td class="text-center">-</td>
          </tr>
        @endforeach
      @endif
    @else
      <tr class="fixed">
        <td class="text-left">Rooms Available Stat</td>
        <td class="text-center">0</td>
        <td class="text-center">0%</td>
        <td class="text-center">0</td>
        <td class="text-center">0%</td>
        <td class="text-center">0</td>
        <td class="text-center">0%</td>
        <td class="text-center">0</td>
        <td class="text-center">0%</td>
        <td class="text-center">0</td>
        <td class="text-center">0%</td>
        <td class="text-center">0</td>
        <td class="text-center">0%</td>
        <td class="text-center">0</td>
        <td class="text-center">0%</td>
        <td class="text-center">0</td>
        <td class="text-center">0%</td>
      </tr>
      <tr class="fixed">
        <td class="text-left">Rooms Sold Stat</td>
        <td class="text-center">0</td>
        <td class="text-center">0%</td>
        <td class="text-center">0</td>
        <td class="text-center">0%</td>
        <td class="text-center">0</td>
        <td class="text-center">0%</td>
        <td class="text-center">0</td>
        <td class="text-center">0%</td>
        <td class="text-center">0</td>
        <td class="text-center">0%</td>
        <td class="text-center">0</td>
        <td class="text-center">0%</td>
        <td class="text-center">0</td>
        <td class="text-center">0%</td>
        <td class="text-center">0</td>
        <td class="text-center">0%</td>
      </tr>
      <tr class="fixed">
        <td class="text-left">Number of Transient Guests</td>
        <td class="text-center">0</td>
        <td class="text-center">0%</td>
        <td class="text-center">0</td>
        <td class="text-center">0%</td>
        <td class="text-center">0</td>
        <td class="text-center">0%</td>
        <td class="text-center">0</td>
        <td class="text-center">0%</td>
        <td class="text-center">0</td>
        <td class="text-center">0%</td>
        <td class="text-center">0</td>
        <td class="text-center">0%</td>
        <td class="text-center">0</td>
        <td class="text-center">0%</td>
        <td class="text-center">0</td>
        <td class="text-center">0%</td>
      </tr>
      <tr class="fixed">
        <td class="text-left">Occupancy</td>
        <td class="text-center">0</td>
        <td class="text-center">0%</td>
        <td class="text-center">0</td>
        <td class="text-center">0%</td>
        <td class="text-center">0</td>
        <td class="text-center">0%</td>
        <td class="text-center">0</td>
        <td class="text-center">0%</td>
        <td class="text-center">0</td>
        <td class="text-center">0%</td>
        <td class="text-center">0</td>
        <td class="text-center">0%</td>
        <td class="text-center">0</td>
        <td class="text-center">0%</td>
        <td class="text-center">0</td>
        <td class="text-center">0%</td>
      </tr>
      <tr class="fixed">
        <td class="text-left">Occupancy Average Rate</td>
        <td class="text-center">0</td>
        <td class="text-center">0%</td>
        <td class="text-center">0</td>
        <td class="text-center">0%</td>
        <td class="text-center">0</td>
        <td class="text-center">0%</td>
        <td class="text-center">0</td>
        <td class="text-center">0%</td>
        <td class="text-center">0</td>
        <td class="text-center">0%</td>
        <td class="text-center">0</td>
        <td class="text-center">0%</td>
        <td class="text-center">0</td>
        <td class="text-center">0%</td>
        <td class="text-center">0</td>
        <td class="text-center">0%</td>
      </tr>
      <tr class="fixed">
        <td class="text-left">RevPAR</td>
        <td class="text-center">0</td>
        <td class="text-center">0%</td>
        <td class="text-center">0</td>
        <td class="text-center">0%</td>
        <td class="text-center">0</td>
        <td class="text-center">0%</td>
        <td class="text-center">0</td>
        <td class="text-center">0%</td>
        <td class="text-center">0</td>
        <td class="text-center">0%</td>
        <td class="text-center">0</td>
        <td class="text-center">0%</td>
        <td class="text-center">0</td>
        <td class="text-center">0%</td>
        <td class="text-center">0</td>
        <td class="text-center">0%</td>
      </tr>
    @endif
    <!-- DIVIDER -->
    <tr>
      <td class="p-2 border-0" colspan="17"></td>
    </tr>
    <!-- END DIVIDER -->

    @if(isset($data['SUMMARY-ALL']['SUMMARY']) && count($data['SUMMARY-ALL']['SUMMARY']))
      @foreach($data['SUMMARY-ALL']['SUMMARY'] as $key_category => $data_pnl)
      @php
        $current_iteration = $loop->iteration;
      @endphp
      <tr class="bg-black text-white fixed" data-node-id="{{ $loop->iteration }}">
        <td class="text-center bg-secondary text-white"></td>
        <td class="text-center bg-black text-white" colspan="8"><h6 class="mb-0">{{ isset($data_pnl['DATA_CATEGORY'][0]->GROUP_DESC) ? $data_pnl['DATA_CATEGORY'][0]->GROUP_DESC : 'Unknown Category' }}</h6></td>
        <td class="text-center bg-black text-white" colspan="8"><h6 class="mb-0">{{ isset($data_pnl['DATA_CATEGORY'][0]->GROUP_DESC) ? $data_pnl['DATA_CATEGORY'][0]->GROUP_DESC : 'Unknown Category' }}</h6></td>
      </tr>
        @foreach($data_pnl['DATA_CATEGORY'] as $pnl)
          <tr class="fixed child" data-node-id="{{ $loop->iteration }}.1" data-node-pid="{{ $current_iteration }}">
            <td class="text-left"><span class="custom-badge">{{ $loop->iteration }}</span>
            {{ isset($pnl->CATEGORY) ? ". ".$pnl->CATEGORY : '-' }}</td>
            <td class="text-center">{{ isset($pnl->ACTUAL_CURRENT) ? number_format($pnl->ACTUAL_CURRENT) : '-' }}</td>
            <td class="text-center">{{ isset($pnl->ACTUAL_CURRENT_PCTG) ? number_format($pnl->ACTUAL_CURRENT_PCTG, 2)."%" : '-' }}</td>
            <td class="text-center">{{ isset($pnl->LAST_MONTH_CURRENT) ? number_format($pnl->LAST_MONTH_CURRENT) : '-' }}</td>
            <td class="text-center">{{ isset($pnl->LAST_MONTH_CURRENT_PCTG) ? number_format($pnl->LAST_MONTH_CURRENT_PCTG, 2)."%" : '-' }}</td>
            <td class="text-center">{{ isset($pnl->VARIANCE_CURRENT) ? number_format($pnl->VARIANCE_CURRENT) : '-' }}</td>
            <td class="text-center">{{ isset($pnl->VARIANCE_CURRENT_PCTG) ? number_format($pnl->VARIANCE_CURRENT_PCTG, 2)."%" : '-' }}</td>
            <td class="text-center">{{ isset($pnl->LAST_YEAR_CURRENT) ? number_format($pnl->LAST_YEAR_CURRENT) : '-' }}</td>
            <td class="text-center">{{ isset($pnl->LAST_YEAR_CURRENT_PCTG) ? number_format($pnl->LAST_YEAR_CURRENT_PCTG, 2)."%" : '-' }}</td>

            <td class="text-center">{{ isset($pnl->ACTUAL_YTD) ? number_format($pnl->ACTUAL_YTD) : '-' }}</td>
            <td class="text-center">{{ isset($pnl->ACTUAL_YTD_PCTG) ? number_format($pnl->ACTUAL_YTD_PCTG, 2)."%" : '-' }}</td>
            <td class="text-center">{{ isset($pnl->LAST_MONTH_YTD) ? number_format($pnl->LAST_MONTH_YTD) : '-' }}</td>
            <td class="text-center">{{ isset($pnl->LAST_MONTH_YTD_PCTG) ? number_format($pnl->LAST_MONTH_YTD_PCTG, 2)."%" : '-' }}</td>
            <td class="text-center">{{ isset($pnl->VARIANCE_YTD) ? number_format($pnl->VARIANCE_YTD) : '-' }}</td>
            <td class="text-center">{{ isset($pnl->VARIANCE_YTD_PCTG) ? number_format($pnl->VARIANCE_YTD_PCTG, 2)."%" : '-' }}</td>
            <td class="text-center">{{ isset($pnl->LAST_YEAR_YTD) ? number_format($pnl->LAST_YEAR_YTD) : '-' }}</td>
            <td class="text-center">{{ isset($pnl->LAST_YEAR_YTD_PCTG) ? number_format($pnl->LAST_YEAR_YTD_PCTG, 2)."%" : '-' }}</td>

          </tr>
        @endforeach
        <tr class="fixed" style="background: #eaeaea" data-node-id="{{ $current_iteration }}.1" data-node-pid="{{ $current_iteration }}">
          <td class="text-center" colspan="0"><b>Total {{ isset($data_pnl['DATA_CATEGORY'][0]->GROUP_DESC) ? $data_pnl['DATA_CATEGORY'][0]->GROUP_DESC : 'Unknown Category' }}</b></td>
          <td class="text-center"><b>{{ isset($data_pnl['SUM_CATEGORY']['ACTUAL_CURRENT']) ? 
          number_format($data_pnl['SUM_CATEGORY']['ACTUAL_CURRENT']) : '-' }}</b></td>
          <td class="text-center"><b>{{ isset($data_pnl['SUM_CATEGORY']['ACTUAL_CURRENT_PCTG']) ? number_format($data_pnl['SUM_CATEGORY']['ACTUAL_CURRENT_PCTG'], 2)."%" : '-' }}</b></td>
          <td class="text-center"><b>{{ isset($data_pnl['SUM_CATEGORY']['LAST_MONTH_CURRENT']) ? number_format($data_pnl['SUM_CATEGORY']['LAST_MONTH_CURRENT']) : '-' }}</b></td>
          <td class="text-center"><b>{{ isset($data_pnl['SUM_CATEGORY']['LAST_MONTH_CURRENT_PCTG']) ? number_format($data_pnl['SUM_CATEGORY']['LAST_MONTH_CURRENT_PCTG'], 2)."%" : '-' }}</b></td>
          <td class="text-center"><b>{{ isset($data_pnl['SUM_CATEGORY']['VARIANCE_CURRENT']) ? number_format($data_pnl['SUM_CATEGORY']['VARIANCE_CURRENT']) : '-' }}</b></td>
          <td class="text-center"><b>{{ isset($data_pnl['SUM_CATEGORY']['VARIANCE_CURRENT_PCTG']) ? number_format($data_pnl['SUM_CATEGORY']['VARIANCE_CURRENT_PCTG'], 2)."%" : '-' }}</b></td>
          <td class="text-center"><b>{{ isset($data_pnl['SUM_CATEGORY']['LAST_YEAR_CURRENT']) ? number_format($data_pnl['SUM_CATEGORY']['LAST_YEAR_CURRENT']) : '-' }}</b></td>
          <td class="text-center"><b>{{ isset($data_pnl['SUM_CATEGORY']['LAST_YEAR_CURRENT_PCTG']) ? number_format($data_pnl['SUM_CATEGORY']['LAST_YEAR_CURRENT_PCTG'], 2)."%" : '-' }}</b></td>

          <td class="text-center"><b>{{ isset($data_pnl['SUM_CATEGORY']['ACTUAL_YTD']) ? 
          number_format($data_pnl['SUM_CATEGORY']['ACTUAL_YTD']) : '-' }}</b></td>
          <td class="text-center"><b>{{ isset($data_pnl['SUM_CATEGORY']['ACTUAL_YTD_PCTG']) ? 
          number_format($data_pnl['SUM_CATEGORY']['ACTUAL_YTD_PCTG'], 2)."%" : '-' }}</b></td>
          <td class="text-center"><b>{{ isset($data_pnl['SUM_CATEGORY']['LAST_MONTH_YTD']) ? 
          number_format($data_pnl['SUM_CATEGORY']['LAST_MONTH_YTD']) : '-' }}</b></td>
          <td class="text-center"><b>{{ isset($data_pnl['SUM_CATEGORY']['LAST_MONTH_YTD_PCTG']) ? number_format($data_pnl['SUM_CATEGORY']['LAST_MONTH_YTD_PCTG'], 2)."%" : '-' }}</b></td>
          <td class="text-center"><b>{{ isset($data_pnl['SUM_CATEGORY']['VARIANCE_YTD']) ? 
          number_format($data_pnl['SUM_CATEGORY']['VARIANCE_YTD']) : '-' }}</b></td>
          <td class="text-center"><b>{{ isset($data_pnl['SUM_CATEGORY']['VARIANCE_YTD_PCTG']) ? number_format($data_pnl['SUM_CATEGORY']['VARIANCE_YTD_PCTG'], 2)."%" : '-' }}</b></td>
          <td class="text-center"><b>{{ isset($data_pnl['SUM_CATEGORY']['LAST_YEAR_YTD']) ? 
          number_format($data_pnl['SUM_CATEGORY']['LAST_YEAR_YTD']) : '-' }}</b></td>
          <td class="text-center"><b>{{ isset($data_pnl['SUM_CATEGORY']['LAST_YEAR_YTD_PCTG']) ? number_format($data_pnl['SUM_CATEGORY']['LAST_YEAR_YTD_PCTG'], 2)."%" : '-' }}</b></td>
        </tr>

        @if($key_category == 'UEX')
          <tr class="fixed" style="background: #eaeaea" data-node-id="{{ $current_iteration }}.1" data-node-pid="{{ $current_iteration }}">
            <td class="text-center" colspan="0"><b>Total Gross Operating Profit</b></td>
            <td class="text-center"><b>{{ isset($data['SUMMARY-ALL']['GROUPING']['GROSS_OPERATING_PROFIT']['ACTUAL_CURRENT']) ? 
            number_format($data['SUMMARY-ALL']['GROUPING']['GROSS_OPERATING_PROFIT']['ACTUAL_CURRENT']) : '-' }}</b></td>
            <td class="text-center"><b>{{ isset($data['SUMMARY-ALL']['GROUPING']['GROSS_OPERATING_PROFIT']['ACTUAL_CURRENT_PCTG']) ? number_format($data['SUMMARY-ALL']['GROUPING']['GROSS_OPERATING_PROFIT']['ACTUAL_CURRENT_PCTG'], 2)."%" : '-' }}</b></td>
            <td class="text-center"><b>{{ isset($data['SUMMARY-ALL']['GROUPING']['GROSS_OPERATING_PROFIT']['LAST_MONTH_CURRENT']) ? number_format($data['SUMMARY-ALL']['GROUPING']['GROSS_OPERATING_PROFIT']['LAST_MONTH_CURRENT']) : '-' }}</b></td>
            <td class="text-center"><b>{{ isset($data['SUMMARY-ALL']['GROUPING']['GROSS_OPERATING_PROFIT']['LAST_MONTH_CURRENT_PCTG']) ? number_format($data['SUMMARY-ALL']['GROUPING']['GROSS_OPERATING_PROFIT']['LAST_MONTH_CURRENT_PCTG'], 2)."%" : '-' }}</b></td>
            <td class="text-center"><b>{{ isset($data['SUMMARY-ALL']['GROUPING']['GROSS_OPERATING_PROFIT']['VARIANCE_CURRENT']) ? number_format($data['SUMMARY-ALL']['GROUPING']['GROSS_OPERATING_PROFIT']['VARIANCE_CURRENT']) : '-' }}</b></td>
            <td class="text-center"><b>{{ isset($data['SUMMARY-ALL']['GROUPING']['GROSS_OPERATING_PROFIT']['VARIANCE_CURRENT_PCTG']) ? number_format($data['SUMMARY-ALL']['GROUPING']['GROSS_OPERATING_PROFIT']['VARIANCE_CURRENT_PCTG'], 2)."%" : '-' }}</b></td>
            <td class="text-center"><b>{{ isset($data['SUMMARY-ALL']['GROUPING']['GROSS_OPERATING_PROFIT']['LAST_YEAR_CURRENT']) ? number_format($data['SUMMARY-ALL']['GROUPING']['GROSS_OPERATING_PROFIT']['LAST_YEAR_CURRENT']) : '-' }}</b></td>
            <td class="text-center"><b>{{ isset($data['SUMMARY-ALL']['GROUPING']['GROSS_OPERATING_PROFIT']['LAST_YEAR_CURRENT_PCTG']) ? number_format($data['SUMMARY-ALL']['GROUPING']['GROSS_OPERATING_PROFIT']['LAST_YEAR_CURRENT_PCTG'], 2)."%" : '-' }}</b></td>

            <td class="text-center"><b>{{ isset($data['SUMMARY-ALL']['GROUPING']['GROSS_OPERATING_PROFIT']['ACTUAL_YTD']) ? 
            number_format($data['SUMMARY-ALL']['GROUPING']['GROSS_OPERATING_PROFIT']['ACTUAL_YTD']) : '-' }}</b></td>
            <td class="text-center"><b>{{ isset($data['SUMMARY-ALL']['GROUPING']['GROSS_OPERATING_PROFIT']['ACTUAL_YTD_PCTG']) ? 
            number_format($data['SUMMARY-ALL']['GROUPING']['GROSS_OPERATING_PROFIT']['ACTUAL_YTD_PCTG'], 2)."%" : '-' }}</b></td>
            <td class="text-center"><b>{{ isset($data['SUMMARY-ALL']['GROUPING']['GROSS_OPERATING_PROFIT']['LAST_MONTH_YTD']) ? 
            number_format($data['SUMMARY-ALL']['GROUPING']['GROSS_OPERATING_PROFIT']['LAST_MONTH_YTD']) : '-' }}</b></td>
            <td class="text-center"><b>{{ isset($data['SUMMARY-ALL']['GROUPING']['GROSS_OPERATING_PROFIT']['LAST_MONTH_YTD_PCTG']) ? number_format($data['SUMMARY-ALL']['GROUPING']['GROSS_OPERATING_PROFIT']['LAST_MONTH_YTD_PCTG'], 2)."%" : '-' }}</b></td>
            <td class="text-center"><b>{{ isset($data['SUMMARY-ALL']['GROUPING']['GROSS_OPERATING_PROFIT']['VARIANCE_YTD']) ? 
            number_format($data['SUMMARY-ALL']['GROUPING']['GROSS_OPERATING_PROFIT']['VARIANCE_YTD']) : '-' }}</b></td>
            <td class="text-center"><b>{{ isset($data['SUMMARY-ALL']['GROUPING']['GROSS_OPERATING_PROFIT']['VARIANCE_YTD_PCTG']) ? number_format($data['SUMMARY-ALL']['GROUPING']['GROSS_OPERATING_PROFIT']['VARIANCE_YTD_PCTG'], 2)."%" : '-' }}</b></td>
            <td class="text-center"><b>{{ isset($data['SUMMARY-ALL']['GROUPING']['GROSS_OPERATING_PROFIT']['LAST_YEAR_YTD']) ? 
            number_format($data['SUMMARY-ALL']['GROUPING']['GROSS_OPERATING_PROFIT']['LAST_YEAR_YTD']) : '-' }}</b></td>
            <td class="text-center"><b>{{ isset($data['SUMMARY-ALL']['GROUPING']['GROSS_OPERATING_PROFIT']['LAST_YEAR_YTD_PCTG']) ? number_format($data['SUMMARY-ALL']['GROUPING']['GROSS_OPERATING_PROFIT']['LAST_YEAR_YTD_PCTG'], 2)."%" : '-' }}</b></td>
          </tr>
        @endif

        <!-- DIVIDER -->
        <tr>
          <td class="p-2 border-0" colspan="17"></td>
        </tr>
        <!-- END DIVIDER -->
      @endforeach
      <tr class="fixed" style="background: #eaeaea" data-node-id="{{ $current_iteration }}.1" data-node-pid="{{ $current_iteration }}">
        <td class="text-center" colspan="0"><b>Total Nett Operating Profit</b></td>
        <td class="text-center"><b>{{ isset($data['SUMMARY-ALL']['GROUPING']['NETT_OPERATING_INCOME']['ACTUAL_CURRENT']) ? 
        number_format($data['SUMMARY-ALL']['GROUPING']['NETT_OPERATING_INCOME']['ACTUAL_CURRENT']) : '-' }}</b></td>
        <td class="text-center"><b>{{ isset($data['SUMMARY-ALL']['GROUPING']['NETT_OPERATING_INCOME']['ACTUAL_CURRENT_PCTG']) ? number_format($data['SUMMARY-ALL']['GROUPING']['NETT_OPERATING_INCOME']['ACTUAL_CURRENT_PCTG'], 2)."%" : '-' }}</b></td>
        <td class="text-center"><b>{{ isset($data['SUMMARY-ALL']['GROUPING']['NETT_OPERATING_INCOME']['LAST_MONTH_CURRENT']) ? number_format($data['SUMMARY-ALL']['GROUPING']['NETT_OPERATING_INCOME']['LAST_MONTH_CURRENT']) : '-' }}</b></td>
        <td class="text-center"><b>{{ isset($data['SUMMARY-ALL']['GROUPING']['NETT_OPERATING_INCOME']['LAST_MONTH_CURRENT_PCTG']) ? number_format($data['SUMMARY-ALL']['GROUPING']['NETT_OPERATING_INCOME']['LAST_MONTH_CURRENT_PCTG'], 2)."%" : '-' }}</b></td>
        <td class="text-center"><b>{{ isset($data['SUMMARY-ALL']['GROUPING']['NETT_OPERATING_INCOME']['VARIANCE_CURRENT']) ? number_format($data['SUMMARY-ALL']['GROUPING']['NETT_OPERATING_INCOME']['VARIANCE_CURRENT']) : '-' }}</b></td>
        <td class="text-center"><b>{{ isset($data['SUMMARY-ALL']['GROUPING']['NETT_OPERATING_INCOME']['VARIANCE_CURRENT_PCTG']) ? number_format($data['SUMMARY-ALL']['GROUPING']['NETT_OPERATING_INCOME']['VARIANCE_CURRENT_PCTG'], 2)."%" : '-' }}</b></td>
        <td class="text-center"><b>{{ isset($data['SUMMARY-ALL']['GROUPING']['NETT_OPERATING_INCOME']['LAST_YEAR_CURRENT']) ? number_format($data['SUMMARY-ALL']['GROUPING']['NETT_OPERATING_INCOME']['LAST_YEAR_CURRENT']) : '-' }}</b></td>
        <td class="text-center"><b>{{ isset($data['SUMMARY-ALL']['GROUPING']['NETT_OPERATING_INCOME']['LAST_YEAR_CURRENT_PCTG']) ? number_format($data['SUMMARY-ALL']['GROUPING']['NETT_OPERATING_INCOME']['LAST_YEAR_CURRENT_PCTG'], 2)."%" : '-' }}</b></td>

        <td class="text-center"><b>{{ isset($data['SUMMARY-ALL']['GROUPING']['NETT_OPERATING_INCOME']['ACTUAL_YTD']) ? 
        number_format($data['SUMMARY-ALL']['GROUPING']['NETT_OPERATING_INCOME']['ACTUAL_YTD']) : '-' }}</b></td>
        <td class="text-center"><b>{{ isset($data['SUMMARY-ALL']['GROUPING']['NETT_OPERATING_INCOME']['ACTUAL_YTD_PCTG']) ? 
        number_format($data['SUMMARY-ALL']['GROUPING']['NETT_OPERATING_INCOME']['ACTUAL_YTD_PCTG'], 2)."%" : '-' }}</b></td>
        <td class="text-center"><b>{{ isset($data['SUMMARY-ALL']['GROUPING']['NETT_OPERATING_INCOME']['LAST_MONTH_YTD']) ? 
        number_format($data['SUMMARY-ALL']['GROUPING']['NETT_OPERATING_INCOME']['LAST_MONTH_YTD']) : '-' }}</b></td>
        <td class="text-center"><b>{{ isset($data['SUMMARY-ALL']['GROUPING']['NETT_OPERATING_INCOME']['LAST_MONTH_YTD_PCTG']) ? number_format($data['SUMMARY-ALL']['GROUPING']['NETT_OPERATING_INCOME']['LAST_MONTH_YTD_PCTG'], 2)."%" : '-' }}</b></td>
        <td class="text-center"><b>{{ isset($data['SUMMARY-ALL']['GROUPING']['NETT_OPERATING_INCOME']['VARIANCE_YTD']) ? 
        number_format($data['SUMMARY-ALL']['GROUPING']['NETT_OPERATING_INCOME']['VARIANCE_YTD']) : '-' }}</b></td>
        <td class="text-center"><b>{{ isset($data['SUMMARY-ALL']['GROUPING']['NETT_OPERATING_INCOME']['VARIANCE_YTD_PCTG']) ? number_format($data['SUMMARY-ALL']['GROUPING']['NETT_OPERATING_INCOME']['VARIANCE_YTD_PCTG'], 2)."%" : '-' }}</b></td>
        <td class="text-center"><b>{{ isset($data['SUMMARY-ALL']['GROUPING']['NETT_OPERATING_INCOME']['LAST_YEAR_YTD']) ? 
        number_format($data['SUMMARY-ALL']['GROUPING']['NETT_OPERATING_INCOME']['LAST_YEAR_YTD']) : '-' }}</b></td>
        <td class="text-center"><b>{{ isset($data['SUMMARY-ALL']['GROUPING']['NETT_OPERATING_INCOME']['LAST_YEAR_YTD_PCTG']) ? number_format($data['SUMMARY-ALL']['GROUPING']['NETT_OPERATING_INCOME']['LAST_YEAR_YTD_PCTG'], 2)."%" : '-' }}</b></td>
      </tr>
      <!-- DIVIDER -->
      <tr>
        <td class="p-2 border-0" colspan="17"></td>
      </tr>
      <!-- END DIVIDER -->
        
      @includeIf('pages.report.finance.pnl_part.SUMMARY_TOTAL_HEADCOUNT', ['data' => isset($data['SUMMARY-ALL']['TOTAL_HEADCOUNT']) ? $data['SUMMARY-ALL']['TOTAL_HEADCOUNT'] : [] ])
      
      @includeIf('pages.report.finance.pnl_part.SUMMARY_TOTAL_PAYROLL', ['data' => isset($data['SUMMARY-ALL']['TOTAL_PAYROLL']) ? $data['SUMMARY-ALL']['TOTAL_PAYROLL'] : [] ])

      @includeIf('pages.report.finance.pnl_part.SUMMARY_PAYROLL_TOTAL_REVENUE', ['data' => isset($data['SUMMARY-ALL']['TOTAL_COST_AGAINTS_REVENUE']['PAYROLL_PER_REVENUE']) ? $data['SUMMARY-ALL']['TOTAL_COST_AGAINTS_REVENUE']['PAYROLL_PER_REVENUE'] : [] ])

      @includeIf('pages.report.finance.pnl_part.SUMMARY_PAYROLL_TOTAL_HEADCOUNT', ['data' => isset($data['SUMMARY-ALL']['TOTAL_COST_AGAINTS_REVENUE']['PAYROLL_PER_HEADCOUNT']) ? $data['SUMMARY-ALL']['TOTAL_COST_AGAINTS_REVENUE']['PAYROLL_PER_HEADCOUNT'] : [] ])
    @endif
  </tbody>
  </table>