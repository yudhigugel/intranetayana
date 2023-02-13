<table class="table table-bordered table-tree" id="fnb-arj-rev-table" style="width: auto; margin-bottom: 1em; margin-right: 20px; white-space: nowrap;">
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
    @if(isset($data['SAMICOS']) && count($data['SAMICOS']))
      @php
        $loop_grouping_showed = [];
        $loop_group_count = 0;
        $dont_show_total_key = [];
      @endphp
      @foreach($data['SAMICOS'] as $key_category => $data_pnl)
      @php
        $current_iteration = $loop->iteration;
        $report_cat = isset($data['GROUPING_GL'][$key_category]) && isset($data['GROUPING_GL'][$key_category][array_keys($data['GROUPING_GL'][$key_category])[0]]['DESC']) ? $data['GROUPING_GL'][$key_category][array_keys($data['GROUPING_GL'][$key_category])[0]]['DESC'].":" : 'Unknown Category';
      @endphp
      <tr class="bg-black text-white fixed" data-node-id="{{ $loop->iteration }}">
        <td class="text-center bg-secondary text-white"></td>
        <td class="text-center bg-black text-white" colspan="8"><h6 class="mb-0">{{ isset($data_pnl['DATA_CATEGORY'][0]->CATEGORY_REPORT) ? $data_pnl['DATA_CATEGORY'][0]->CATEGORY_REPORT : $report_cat }}</h6></td>
        <td class="text-center bg-black text-white" colspan="8"><h6 class="mb-0">{{ isset($data_pnl['DATA_CATEGORY'][0]->CATEGORY_REPORT) ? $data_pnl['DATA_CATEGORY'][0]->CATEGORY_REPORT : $report_cat }}</h6></td>
      </tr>
        @if(isset($data['GROUPING_GL'][$key_category]) && count($data['GROUPING_GL'][$key_category]))
          {{--<tr class="fixed child" data-node-id="{{ $loop->iteration }}.2" data-node-pid="{{ $current_iteration }}">
            <td data-toggle="collapse" data-target="#collapse-{{ $current_iteration }}" class="text-left text-primary" style="cursor: pointer;">
              <i class="mdi mdi-chevron-double-right"></i>&nbsp;&nbsp;{{ isset($data['GROUPING_GL'][$key_category][0]->GROUPING_ROW_DESC) ? strtoupper($data['GROUPING_GL'][$key_category][0]->GROUPING_ROW_DESC) : 'Unknown GL Group' }}
            </td>
            <td class="text-center text-primary">{{ isset($data['GROUPING_GL']['SUM_CATEGORY'][$key_category]['ACTUAL_CURRENT']) ? number_format($data['GROUPING_GL']['SUM_CATEGORY'][$key_category]['ACTUAL_CURRENT']) : '-' }}</td>
            <td class="text-center text-primary">{{ isset($data['GROUPING_GL']['SUM_CATEGORY'][$key_category]['ACTUAL_CURRENT_PCTG']) ? number_format($data['GROUPING_GL']['SUM_CATEGORY'][$key_category]['ACTUAL_CURRENT_PCTG'], 2)."%" : '-' }}</td>

            <td class="text-center text-primary">{{ isset($data['GROUPING_GL']['SUM_CATEGORY'][$key_category]['LAST_MONTH_CURRENT']) ? number_format($data['GROUPING_GL']['SUM_CATEGORY'][$key_category]['LAST_MONTH_CURRENT']) : '-' }}</td>
            <td class="text-center text-primary">{{ isset($data['GROUPING_GL']['SUM_CATEGORY'][$key_category]['LAST_MONTH_CURRENT_PCTG']) ? number_format($data['GROUPING_GL']['SUM_CATEGORY'][$key_category]['LAST_MONTH_CURRENT_PCTG'], 2)."%" : '-' }}</td>

            <td class="text-center text-primary">{{ isset($data['GROUPING_GL']['SUM_CATEGORY'][$key_category]['VARIANCE_CURRENT']) ? number_format($data['GROUPING_GL']['SUM_CATEGORY'][$key_category]['VARIANCE_CURRENT']) : '-' }}</td>
            <td class="text-center text-primary">{{ isset($data['GROUPING_GL']['SUM_CATEGORY'][$key_category]['VARIANCE_CURRENT_PCTG']) ? number_format($data['GROUPING_GL']['SUM_CATEGORY'][$key_category]['VARIANCE_CURRENT_PCTG'], 2)."%" : '-' }}</td>

            <td class="text-center text-primary">{{ isset($data['GROUPING_GL']['SUM_CATEGORY'][$key_category]['LAST_YEAR_CURRENT']) ? number_format($data['GROUPING_GL']['SUM_CATEGORY'][$key_category]['LAST_YEAR_CURRENT']) : '-' }}</td>
            <td class="text-center text-primary">{{ isset($data['GROUPING_GL']['SUM_CATEGORY'][$key_category]['LAST_YEAR_CURRENT_PCTG']) ? number_format($data['GROUPING_GL']['SUM_CATEGORY'][$key_category]['LAST_YEAR_CURRENT_PCTG'], 2)."%" : '-' }}</td>
            
            <td class="text-center text-primary">{{ isset($data['GROUPING_GL']['SUM_CATEGORY'][$key_category]['ACTUAL_YTD']) ? number_format($data['GROUPING_GL']['SUM_CATEGORY'][$key_category]['ACTUAL_YTD']) : '-' }}</td>
            <td class="text-center text-primary">{{ isset($data['GROUPING_GL']['SUM_CATEGORY'][$key_category]['ACTUAL_YTD_PCTG']) ? number_format($data['GROUPING_GL']['SUM_CATEGORY'][$key_category]['ACTUAL_YTD_PCTG'], 2)."%" : '-' }}</td>

            <td class="text-center text-primary">{{ isset($data['GROUPING_GL']['SUM_CATEGORY'][$key_category]['LAST_MONTH_YTD']) ? number_format($data['GROUPING_GL']['SUM_CATEGORY'][$key_category]['LAST_MONTH_YTD']) : '-' }}</td>
            <td class="text-center text-primary">{{ isset($data['GROUPING_GL']['SUM_CATEGORY'][$key_category]['LAST_MONTH_YTD_PCTG']) ? number_format($data['GROUPING_GL']['SUM_CATEGORY'][$key_category]['LAST_MONTH_YTD_PCTG'], 2)."%" : '-' }}</td>

            <td class="text-center text-primary">{{ isset($data['GROUPING_GL']['SUM_CATEGORY'][$key_category]['VARIANCE_YTD']) ? number_format($data['GROUPING_GL']['SUM_CATEGORY'][$key_category]['VARIANCE_YTD']) : '-' }}</td>
            <td class="text-center text-primary">{{ isset($data['GROUPING_GL']['SUM_CATEGORY'][$key_category]['VARIANCE_YTD_PCTG']) ? number_format($data['GROUPING_GL']['SUM_CATEGORY'][$key_category]['VARIANCE_YTD_PCTG'], 2)."%" : '-' }}</td>

            <td class="text-center text-primary">{{ isset($data['GROUPING_GL']['SUM_CATEGORY'][$key_category]['LAST_YEAR_YTD']) ? number_format($data['GROUPING_GL']['SUM_CATEGORY'][$key_category]['LAST_YEAR_YTD']) : '-' }}</td>
            <td class="text-center">{{ isset($data['GROUPING_GL']['SUM_CATEGORY'][$key_category]['LAST_YEAR_YTD_PCTG']) ? number_format($data['GROUPING_GL']['SUM_CATEGORY'][$key_category]['LAST_YEAR_YTD_PCTG'], 2)."%" : '-' }}</td>
          </tr>--}}

          {{--
          @foreach($data['GROUPING_GL'][$key_category] as $keyGL => $grouping_gl)
            <tr id="collapse-{{ $current_iteration }}" class="fixed child collapse show" data-node-id="{{ $loop->iteration }}.1" data-node-pid="{{ $current_iteration }}.2">
              <td class="text-left">
                <div style="margin-left: 1.3em">
                  <span class="custom-badge">{{ $loop->iteration }}</span>
                  {{ isset($grouping_gl->DESC_COA_COST_CENTER) ? ". ".$grouping_gl->DESC_COA_COST_CENTER : '-' }}
                </div>
              </td>
              <td class="text-center">{{ isset($grouping_gl->ACTUAL_CURRENT_PERIOD) ? number_format($grouping_gl->ACTUAL_CURRENT_PERIOD) : '-' }}</td>
              <td class="text-center">{{ isset($grouping_gl->ACTUAL_CURRENT_PCTG) ? number_format($grouping_gl->ACTUAL_CURRENT_PCTG, 2)."%" : '-' }}</td>
              <td class="text-center">{{ isset($grouping_gl->LAST_MONTH_CURRENT_PERIOD) ? number_format($grouping_gl->LAST_MONTH_CURRENT_PERIOD) : '-' }}</td>
              <td class="text-center">{{ isset($grouping_gl->LAST_MONTH_CURRENT_PCTG) ? number_format($grouping_gl->LAST_MONTH_CURRENT_PCTG, 2)."%" : '-' }}</td>
              <td class="text-center">{{ isset($grouping_gl->VARIANCE_CURRENT_PERIOD) ? number_format($grouping_gl->VARIANCE_CURRENT_PERIOD) : '-' }}</td>
              <td class="text-center">{{ isset($grouping_gl->VARIANCE_CURRENT_PCTG) ? number_format($grouping_gl->VARIANCE_CURRENT_PCTG, 2)."%" : '-' }}</td>
              <td class="text-center">{{ isset($grouping_gl->LAST_YEAR_CURRENT_PERIOD) ? number_format($grouping_gl->LAST_YEAR_CURRENT_PERIOD) : '-' }}</td>
              <td class="text-center">{{ isset($grouping_gl->LAST_YEAR_CURRENT_PCTG) ? number_format($grouping_gl->LAST_YEAR_CURRENT_PCTG, 2)."%" : '-' }}</td>

              <td class="text-center">{{ isset($grouping_gl->ACTUAL_YTD) ? number_format($grouping_gl->ACTUAL_YTD) : '-' }}</td>
              <td class="text-center">{{ isset($grouping_gl->ACTUAL_YTD_PCTG) ? number_format($grouping_gl->ACTUAL_YTD_PCTG, 2)."%" : '-' }}</td>
              <td class="text-center">{{ isset($grouping_gl->LAST_MONTH_YTD) ? number_format($grouping_gl->LAST_MONTH_YTD) : '-' }}</td>
              <td class="text-center">{{ isset($grouping_gl->LAST_MONTH_YTD_PCTG) ? number_format($grouping_gl->LAST_MONTH_YTD_PCTG, 2)."%" : '-' }}</td>
              <td class="text-center">{{ isset($grouping_gl->VARIANCE_YTD) ? number_format($grouping_gl->VARIANCE_YTD) : '-' }}</td>
              <td class="text-center">{{ isset($grouping_gl->VARIANCE_YTD_PCTG) ? number_format($grouping_gl->VARIANCE_YTD_PCTG, 2)."%" : '-' }}</td>
              <td class="text-center">{{ isset($grouping_gl->LAST_YEAR_YTD) ? number_format($grouping_gl->LAST_YEAR_YTD) : '-' }}</td>
              <td class="text-center">{{ isset($grouping_gl->LAST_YEAR_YTD_PCTG) ? number_format($grouping_gl->LAST_YEAR_YTD_PCTG, 2)."%" : '-' }}</td>
            </tr>
          @endforeach
          --}}

          @foreach($data['GROUPING_GL'][$key_category] as $keyGroup => $group_data)
            <tr class="fixed child" data-node-id="{{ $loop->iteration }}.2" data-node-pid="{{ $current_iteration }}">
              <td data-toggle="collapse" data-target="#collapse-{{ $current_iteration }}" class="text-left text-primary" style="cursor: pointer;">
                <i class="mdi mdi-chevron-double-right"></i>&nbsp;&nbsp;{{ isset($group_data['DESC']) ? strtoupper($group_data['DESC']) : 'Unknown GL Group' }}
              </td>

              <td class="text-center text-primary">{{ isset($group_data['SUMMARY_ALL']['ACTUAL_CURRENT']) ? number_format($group_data['SUMMARY_ALL']['ACTUAL_CURRENT']) : '-' }}</td>
              <td class="text-center text-primary">{{ isset($group_data['SUMMARY_ALL']['ACTUAL_CURRENT_PCTG']) ? number_format($group_data['SUMMARY_ALL']['ACTUAL_CURRENT_PCTG'], 2)."%" : '-' }}</td>

              <td class="text-center text-primary">{{ isset($group_data['SUMMARY_ALL']['LAST_MONTH_CURRENT']) ? number_format($group_data['SUMMARY_ALL']['LAST_MONTH_CURRENT']) : '-' }}</td>
              <td class="text-center text-primary">{{ isset($group_data['SUMMARY_ALL']['LAST_MONTH_CURRENT_PCTG']) ? number_format($group_data['SUMMARY_ALL']['LAST_MONTH_CURRENT_PCTG'], 2)."%" : '-' }}</td>

              <td class="text-center text-primary">{{ isset($group_data['SUMMARY_ALL']['VARIANCE_CURRENT']) ? number_format($group_data['SUMMARY_ALL']['VARIANCE_CURRENT']) : '-' }}</td>
              <td class="text-center text-primary">{{ isset($group_data['SUMMARY_ALL']['VARIANCE_CURRENT_PCTG']) ? number_format($group_data['SUMMARY_ALL']['VARIANCE_CURRENT_PCTG'], 2)."%" : '-' }}</td>

              <td class="text-center text-primary">{{ isset($group_data['SUMMARY_ALL']['LAST_YEAR_CURRENT']) ? number_format($group_data['SUMMARY_ALL']['LAST_YEAR_CURRENT']) : '-' }}</td>
              <td class="text-center text-primary">{{ isset($group_data['SUMMARY_ALL']['LAST_YEAR_CURRENT_PCTG']) ? number_format($group_data['SUMMARY_ALL']['LAST_YEAR_CURRENT_PCTG'], 2)."%" : '-' }}</td>
              
              <td class="text-center text-primary">{{ isset($group_data['SUMMARY_ALL']['ACTUAL_YTD']) ? number_format($group_data['SUMMARY_ALL']['ACTUAL_YTD']) : '-' }}</td>
              <td class="text-center text-primary">{{ isset($group_data['SUMMARY_ALL']['ACTUAL_YTD_PCTG']) ? number_format($group_data['SUMMARY_ALL']['ACTUAL_YTD_PCTG'], 2)."%" : '-' }}</td>

              <td class="text-center text-primary">{{ isset($group_data['SUMMARY_ALL']['LAST_MONTH_YTD']) ? number_format($group_data['SUMMARY_ALL']['LAST_MONTH_YTD']) : '-' }}</td>
              <td class="text-center text-primary">{{ isset($group_data['SUMMARY_ALL']['LAST_MONTH_YTD_PCTG']) ? number_format($group_data['SUMMARY_ALL']['LAST_MONTH_YTD_PCTG'], 2)."%" : '-' }}</td>

              <td class="text-center text-primary">{{ isset($group_data['SUMMARY_ALL']['VARIANCE_YTD']) ? number_format($group_data['SUMMARY_ALL']['VARIANCE_YTD']) : '-' }}</td>
              <td class="text-center text-primary">{{ isset($group_data['SUMMARY_ALL']['VARIANCE_YTD_PCTG']) ? number_format($group_data['SUMMARY_ALL']['VARIANCE_YTD_PCTG'], 2)."%" : '-' }}</td>

              <td class="text-center text-primary">{{ isset($group_data['SUMMARY_ALL']['LAST_YEAR_YTD']) ? number_format($group_data['SUMMARY_ALL']['LAST_YEAR_YTD']) : '-' }}</td>
              <td class="text-center">{{ isset($group_data['SUMMARY_ALL']['LAST_YEAR_YTD_PCTG']) ? number_format($group_data['SUMMARY_ALL']['LAST_YEAR_YTD_PCTG'], 2)."%" : '-' }}</td>
            </tr>

            @if(isset($group_data['DATA_GROUP']))
              @foreach($group_data['DATA_GROUP'] as $keyGL => $grouping_gl)
                <tr id="collapse-{{ $current_iteration }}" class="fixed child collapse show" data-node-id="{{ $loop->iteration }}.1" data-node-pid="{{ $current_iteration }}.2">
                  <td class="text-left">
                    <div style="margin-left: 1.3em;">
                      <span class="custom-badge">{{ $loop->iteration }}</span>
                      {{ isset($grouping_gl['DESC']) ? ". ".strtoupper($grouping_gl['DESC']) : '-' }}
                    </div>
                  </td>
                  <td class="text-center" >{{ isset($group_data['DATA_GROUP'][$keyGL]['SUMMARY']['ACTUAL_CURRENT']) ? number_format($group_data['DATA_GROUP'][$keyGL]['SUMMARY']['ACTUAL_CURRENT']) : '-' }}</td>
                  <td class="text-center" >{{ isset($group_data['DATA_GROUP'][$keyGL]['SUMMARY']['ACTUAL_CURRENT_PCTG']) ? number_format($group_data['DATA_GROUP'][$keyGL]['SUMMARY']['ACTUAL_CURRENT_PCTG'], 2)."%" : '-' }}</td>
                  <td class="text-center" >{{ isset($group_data['DATA_GROUP'][$keyGL]['SUMMARY']['LAST_MONTH_CURRENT']) ? number_format($group_data['DATA_GROUP'][$keyGL]['SUMMARY']['LAST_MONTH_CURRENT']) : '-' }}</td>
                  <td class="text-center" >{{ isset($group_data['DATA_GROUP'][$keyGL]['SUMMARY']['LAST_MONTH_CURRENT_PCTG']) ? number_format($group_data['DATA_GROUP'][$keyGL]['SUMMARY']['LAST_MONTH_CURRENT_PCTG'], 2)."%" : '-' }}</td>
                  <td class="text-center" >{{ isset($group_data['DATA_GROUP'][$keyGL]['SUMMARY']['VARIANCE_CURRENT']) ? number_format($group_data['DATA_GROUP'][$keyGL]['SUMMARY']['VARIANCE_CURRENT']) : '-' }}</td>
                  <td class="text-center" >{{ isset($group_data['DATA_GROUP'][$keyGL]['SUMMARY']['VARIANCE_CURRENT_PCTG']) ? number_format($group_data['DATA_GROUP'][$keyGL]['SUMMARY']['VARIANCE_CURRENT_PCTG'], 2)."%" : '-' }}</td>
                  <td class="text-center" >{{ isset($group_data['DATA_GROUP'][$keyGL]['SUMMARY']['LAST_YEAR_CURRENT']) ? number_format($group_data['DATA_GROUP'][$keyGL]['SUMMARY']['LAST_YEAR_CURRENT']) : '-' }}</td>
                  <td class="text-center" >{{ isset($group_data['DATA_GROUP'][$keyGL]['SUMMARY']['LAST_YEAR_CURRENT_PCTG']) ? number_format($group_data['DATA_GROUP'][$keyGL]['SUMMARY']['LAST_YEAR_CURRENT_PCTG'], 2)."%" : '-' }}</td>

                  <td class="text-center" >{{ isset($group_data['DATA_GROUP'][$keyGL]['SUMMARY']['ACTUAL_YTD']) ? number_format($group_data['DATA_GROUP'][$keyGL]['SUMMARY']['ACTUAL_YTD']) : '-' }}</td>
                  <td class="text-center" >{{ isset($group_data['DATA_GROUP'][$keyGL]['SUMMARY']['ACTUAL_YTD_PCTG']) ? number_format($group_data['DATA_GROUP'][$keyGL]['SUMMARY']['ACTUAL_YTD_PCTG'], 2)."%" : '-' }}</td>
                  <td class="text-center" >{{ isset($group_data['DATA_GROUP'][$keyGL]['SUMMARY']['LAST_MONTH_YTD']) ? number_format($group_data['DATA_GROUP'][$keyGL]['SUMMARY']['LAST_MONTH_YTD']) : '-' }}</td>
                  <td class="text-center" >{{ isset($group_data['DATA_GROUP'][$keyGL]['SUMMARY']['LAST_MONTH_YTD_PCTG']) ? number_format($group_data['DATA_GROUP'][$keyGL]['SUMMARY']['LAST_MONTH_YTD_PCTG'], 2)."%" : '-' }}</td>
                  <td class="text-center" >{{ isset($group_data['DATA_GROUP'][$keyGL]['SUMMARY']['VARIANCE_YTD']) ? number_format($group_data['DATA_GROUP'][$keyGL]['SUMMARY']['VARIANCE_YTD']) : '-' }}</td>
                  <td class="text-center" >{{ isset($group_data['DATA_GROUP'][$keyGL]['SUMMARY']['VARIANCE_YTD_PCTG']) ? number_format($group_data['DATA_GROUP'][$keyGL]['SUMMARY']['VARIANCE_YTD_PCTG'], 2)."%" : '-' }}</td>
                  <td class="text-center" >{{ isset($group_data['DATA_GROUP'][$keyGL]['SUMMARY']['LAST_YEAR_YTD']) ? number_format($group_data['DATA_GROUP'][$keyGL]['SUMMARY']['LAST_YEAR_YTD']) : '-' }}</td>
                  <td class="text-center" >{{ isset($group_data['DATA_GROUP'][$keyGL]['SUMMARY']['LAST_YEAR_YTD_PCTG']) ? number_format($group_data['DATA_GROUP'][$keyGL]['SUMMARY']['LAST_YEAR_YTD_PCTG'], 2)."%" : '-' }}</td>
                </tr>
                @if(isset($grouping_gl['DATA']) && count($grouping_gl['DATA']) > 1)
                  @foreach($grouping_gl['DATA'] as $data_children)
                    <tr id="collapse-{{ $current_iteration }}" class="fixed child collapse show" data-node-id="{{ $loop->iteration }}.1" data-node-pid="{{ $current_iteration }}.2">
                      <td class="text-left">
                        <div style="margin-left: 2em">
                          {{-- <span class="custom-badge">{{ "-" }}</span> --}}
                          {{ isset($data_children->DESC_COA_COST_CENTER) ? "- ".strtoupper($data_children->DESC_COA_COST_CENTER) : '-' }}
                        </div>
                      </td>
                      <td class="text-center">{{ isset($data_children->ACTUAL_CURRENT_PERIOD) ? number_format($data_children->ACTUAL_CURRENT_PERIOD) : '-' }}</td>
                      <td class="text-center">{{ isset($data_children->ACTUAL_CURRENT_PCTG) ? number_format($data_children->ACTUAL_CURRENT_PCTG, 2)."%" : '-' }}</td>
                      <td class="text-center">{{ isset($data_children->LAST_MONTH_CURRENT_PERIOD) ? number_format($data_children->LAST_MONTH_CURRENT_PERIOD) : '-' }}</td>
                      <td class="text-center">{{ isset($data_children->LAST_MONTH_CURRENT_PCTG) ? number_format($data_children->LAST_MONTH_CURRENT_PCTG, 2)."%" : '-' }}</td>
                      <td class="text-center">{{ isset($data_children->VARIANCE_CURRENT_PERIOD) ? number_format($data_children->VARIANCE_CURRENT_PERIOD) : '-' }}</td>
                      <td class="text-center">{{ isset($data_children->VARIANCE_CURRENT_PCTG) ? number_format($data_children->VARIANCE_CURRENT_PCTG, 2)."%" : '-' }}</td>
                      <td class="text-center">{{ isset($data_children->LAST_YEAR_CURRENT_PERIOD) ? number_format($data_children->LAST_YEAR_CURRENT_PERIOD) : '-' }}</td>
                      <td class="text-center">{{ isset($data_children->LAST_YEAR_CURRENT_PCTG) ? number_format($data_children->LAST_YEAR_CURRENT_PCTG, 2)."%" : '-' }}</td>

                      <td class="text-center">{{ isset($data_children->ACTUAL_YTD) ? number_format($data_children->ACTUAL_YTD) : '-' }}</td>
                      <td class="text-center">{{ isset($data_children->ACTUAL_YTD_PCTG) ? number_format($data_children->ACTUAL_YTD_PCTG, 2)."%" : '-' }}</td>
                      <td class="text-center">{{ isset($data_children->LAST_MONTH_YTD) ? number_format($data_children->LAST_MONTH_YTD) : '-' }}</td>
                      <td class="text-center">{{ isset($data_children->LAST_MONTH_YTD_PCTG) ? number_format($data_children->LAST_MONTH_YTD_PCTG, 2)."%" : '-' }}</td>
                      <td class="text-center">{{ isset($data_children->VARIANCE_YTD) ? number_format($data_children->VARIANCE_YTD) : '-' }}</td>
                      <td class="text-center">{{ isset($data_children->VARIANCE_YTD_PCTG) ? number_format($data_children->VARIANCE_YTD_PCTG, 2)."%" : '-' }}</td>
                      <td class="text-center">{{ isset($data_children->LAST_YEAR_YTD) ? number_format($data_children->LAST_YEAR_YTD) : '-' }}</td>
                      <td class="text-center">{{ isset($data_children->LAST_YEAR_YTD_PCTG) ? number_format($data_children->LAST_YEAR_YTD_PCTG, 2)."%" : '-' }}</td>
                    </tr>
                  @endforeach
                @endif

              @endforeach
            @endif
          <!-- END FOREACH GROUPING GL -->
          @endforeach
        @endif

        @foreach($data_pnl['DATA_CATEGORY'] as $pnl)
          <tr class="fixed child" data-node-id="{{ $loop->iteration }}.1" data-node-pid="{{ $current_iteration }}">
            <td class="text-left"><span class="custom-badge">{{ $loop->iteration }}</span>
            {{ isset($pnl->DESC_COA_COST_CENTER) ? ". ".$pnl->DESC_COA_COST_CENTER : '-' }}</td>
            <td class="text-center">{{ isset($pnl->ACTUAL_CURRENT_PERIOD) ? number_format($pnl->ACTUAL_CURRENT_PERIOD) : '-' }}</td>
            <td class="text-center">{{ isset($pnl->ACTUAL_CURRENT_PCTG) ? number_format($pnl->ACTUAL_CURRENT_PCTG, 2)."%" : '-' }}</td>
            <td class="text-center">{{ isset($pnl->LAST_MONTH_CURRENT_PERIOD) ? number_format($pnl->LAST_MONTH_CURRENT_PERIOD) : '-' }}</td>
            <td class="text-center">{{ isset($pnl->LAST_MONTH_CURRENT_PCTG) ? number_format($pnl->LAST_MONTH_CURRENT_PCTG, 2)."%" : '-' }}</td>
            <td class="text-center">{{ isset($pnl->VARIANCE_CURRENT_PERIOD) ? number_format($pnl->VARIANCE_CURRENT_PERIOD) : '-' }}</td>
            <td class="text-center">{{ isset($pnl->VARIANCE_CURRENT_PCTG) ? number_format($pnl->VARIANCE_CURRENT_PCTG, 2)."%" : '-' }}</td>
            <td class="text-center">{{ isset($pnl->LAST_YEAR_CURRENT_PERIOD) ? number_format($pnl->LAST_YEAR_CURRENT_PERIOD) : '-' }}</td>
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

        <!-- JIKA TIDAK MENGGUNAKAN GROUPING TOTAL, LANGSUNG TAMPILKAN BERDASARKAN KATEGORI YANG DI LOOP -->
        @if(!in_array($key_category, $dont_show_total_key))
          <tr class="fixed" style="background: #eaeaea" data-node-id="{{ $current_iteration }}.1" data-node-pid="{{ $current_iteration }}">
            <td class="text-center" colspan="0"><b>Total {{ isset($data_pnl['DATA_CATEGORY'][0]->CATEGORY_REPORT) ? $data_pnl['DATA_CATEGORY'][0]->CATEGORY_REPORT : $report_cat }}</b></td>
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
            <td class="text-center">{{ isset($data_pnl['SUM_CATEGORY']['ACTUAL_YTD_PCTG']) ? 
            number_format($data_pnl['SUM_CATEGORY']['ACTUAL_YTD_PCTG'], 2)."%" : '-' }}</td>
            <td class="text-center"><b>{{ isset($data_pnl['SUM_CATEGORY']['LAST_MONTH_YTD']) ? 
            number_format($data_pnl['SUM_CATEGORY']['LAST_MONTH_YTD']) : '-' }}</b></td>
            <td class="text-center">{{ isset($data_pnl['SUM_CATEGORY']['LAST_MONTH_YTD_PCTG']) ? number_format($data_pnl['SUM_CATEGORY']['LAST_MONTH_YTD_PCTG'], 2)."%" : '-' }}</td>
            <td class="text-center"><b>{{ isset($data_pnl['SUM_CATEGORY']['VARIANCE_YTD']) ? 
            number_format($data_pnl['SUM_CATEGORY']['VARIANCE_YTD']) : '-' }}</b></td>
            <td class="text-center">{{ isset($data_pnl['SUM_CATEGORY']['VARIANCE_YTD_PCTG']) ? number_format($data_pnl['SUM_CATEGORY']['VARIANCE_YTD_PCTG'], 2)."%" : '-' }}</td>
            <td class="text-center"><b>{{ isset($data_pnl['SUM_CATEGORY']['LAST_YEAR_YTD']) ? 
            number_format($data_pnl['SUM_CATEGORY']['LAST_YEAR_YTD']) : '-' }}</b></td>
            <td class="text-center">{{ isset($data_pnl['SUM_CATEGORY']['LAST_YEAR_YTD_PCTG']) ? number_format($data_pnl['SUM_CATEGORY']['LAST_YEAR_YTD_PCTG'], 2)."%" : '-' }}</td>
          </tr>

          @if(isset($data['SHOW_GROUP_AFTER_CAT'][$key_category]))
          <!-- TOTAL GROUPING -->
          <!-- TOTAL PAYROLL / DENGAN SALES INCOME -->
          <!-- MTD TOTAL PAYROLL -->
          <tr class="fixed" style="background: #eaeaea" data-node-id="{{ $loop->iteration }}.1" data-node-pid="{{ $current_iteration }}">
            <td class="text-center" colspan="0"><b>{{ $data['GROUPING']['SAMICOS'][$data['SHOW_GROUP_AFTER_CAT'][$key_category]]['GROUPING_DESC'].":" }}</b></td>
            <td class="text-center"><b>{{ isset($data['GROUPING']['SAMICOS'][$data['SHOW_GROUP_AFTER_CAT'][$key_category]]['DATA']['ACTUAL_CURRENT']) ? number_format($data['GROUPING']['SAMICOS'][$data['SHOW_GROUP_AFTER_CAT'][$key_category]]['DATA']['ACTUAL_CURRENT']) : '-' }}</b></td>
            <td class="text-center"><b>{{ isset($data['GROUPING']['SAMICOS'][$data['SHOW_GROUP_AFTER_CAT'][$key_category]]['DATA']['ACTUAL_CURRENT_PCTG']) ? number_format($data['GROUPING']['SAMICOS'][$data['SHOW_GROUP_AFTER_CAT'][$key_category]]['DATA']['ACTUAL_CURRENT_PCTG'], 2)."%" : '-' }}</b></td>

            <td class="text-center"><b>{{ isset($data['GROUPING']['SAMICOS'][$data['SHOW_GROUP_AFTER_CAT'][$key_category]]['DATA']['LAST_MONTH_CURRENT']) ? number_format($data['GROUPING']['SAMICOS'][$data['SHOW_GROUP_AFTER_CAT'][$key_category]]['DATA']['LAST_MONTH_CURRENT']) : '-' }}</b></td>
            <td class="text-center"><b>{{ isset($data['GROUPING']['SAMICOS'][$data['SHOW_GROUP_AFTER_CAT'][$key_category]]['DATA']['LAST_MONTH_CURRENT_PCTG']) ? number_format($data['GROUPING']['SAMICOS'][$data['SHOW_GROUP_AFTER_CAT'][$key_category]]['DATA']['LAST_MONTH_CURRENT_PCTG'], 2)."%" : '-' }}</b></td>

            <td class="text-center"><b>{{ isset($data['GROUPING']['SAMICOS'][$data['SHOW_GROUP_AFTER_CAT'][$key_category]]['DATA']['VARIANCE_CURRENT']) ? number_format($data['GROUPING']['SAMICOS'][$data['SHOW_GROUP_AFTER_CAT'][$key_category]]['DATA']['VARIANCE_CURRENT']) : '-' }}</b></td>
            <td class="text-center"><b>{{ isset($data['GROUPING']['SAMICOS'][$data['SHOW_GROUP_AFTER_CAT'][$key_category]]['DATA']['VARIANCE_CURRENT_PCTG']) ? number_format($data['GROUPING']['SAMICOS'][$data['SHOW_GROUP_AFTER_CAT'][$key_category]]['DATA']['VARIANCE_CURRENT_PCTG'], 2)."%" : '-' }}</b></td>

            <td class="text-center"><b>{{ isset($data['GROUPING']['SAMICOS'][$data['SHOW_GROUP_AFTER_CAT'][$key_category]]['DATA']['LAST_YEAR_CURRENT']) ? number_format($data['GROUPING']['SAMICOS'][$data['SHOW_GROUP_AFTER_CAT'][$key_category]]['DATA']['LAST_YEAR_CURRENT']) : '-' }}</b></td>
            <td class="text-center"><b>{{ isset($data['GROUPING']['SAMICOS'][$data['SHOW_GROUP_AFTER_CAT'][$key_category]]['DATA']['LAST_YEAR_CURRENT_PCTG']) ? number_format($data['GROUPING']['SAMICOS'][$data['SHOW_GROUP_AFTER_CAT'][$key_category]]['DATA']['LAST_YEAR_CURRENT_PCTG'], 2)."%" : '-' }}</b></td>

            <!-- YTD TOTAL PAYROLL -->
            <td class="text-center"><b>{{ isset($data['GROUPING']['SAMICOS'][$data['SHOW_GROUP_AFTER_CAT'][$key_category]]['DATA']['ACTUAL_YTD']) ? number_format($data['GROUPING']['SAMICOS'][$data['SHOW_GROUP_AFTER_CAT'][$key_category]]['DATA']['ACTUAL_YTD']) : '-' }}</b></td>
            <td class="text-center"><b>{{ isset($data['GROUPING']['SAMICOS'][$data['SHOW_GROUP_AFTER_CAT'][$key_category]]['DATA']['ACTUAL_YTD_PCTG']) ? number_format($data['GROUPING']['SAMICOS'][$data['SHOW_GROUP_AFTER_CAT'][$key_category]]['DATA']['ACTUAL_YTD_PCTG'], 2)."%" : '-' }}</b></td>

            <td class="text-center"><b>{{ isset($data['GROUPING']['SAMICOS'][$data['SHOW_GROUP_AFTER_CAT'][$key_category]]['DATA']['LAST_MONTH_YTD']) ? number_format($data['GROUPING']['SAMICOS'][$data['SHOW_GROUP_AFTER_CAT'][$key_category]]['DATA']['LAST_MONTH_YTD']) : '-' }}</b></td>
            <td class="text-center"><b>{{ isset($data['GROUPING']['SAMICOS'][$data['SHOW_GROUP_AFTER_CAT'][$key_category]]['DATA']['LAST_MONTH_YTD_PCTG']) ? number_format($data['GROUPING']['SAMICOS'][$data['SHOW_GROUP_AFTER_CAT'][$key_category]]['DATA']['LAST_MONTH_YTD_PCTG'], 2)."%" : '-' }}</b></td>

            <td class="text-center"><b>{{ isset($data['GROUPING']['SAMICOS'][$data['SHOW_GROUP_AFTER_CAT'][$key_category]]['DATA']['VARIANCE_YTD']) ? number_format($data['GROUPING']['SAMICOS'][$data['SHOW_GROUP_AFTER_CAT'][$key_category]]['DATA']['VARIANCE_YTD']) : '-' }}</b></td>
            <td class="text-center"><b>{{ isset($data['GROUPING']['SAMICOS'][$data['SHOW_GROUP_AFTER_CAT'][$key_category]]['DATA']['VARIANCE_YTD_PCTG']) ? number_format($data['GROUPING']['SAMICOS'][$data['SHOW_GROUP_AFTER_CAT'][$key_category]]['DATA']['VARIANCE_YTD_PCTG'], 2)."%" : '-' }}</b></td>

            <td class="text-center"><b>{{ isset($data['GROUPING']['SAMICOS'][$data['SHOW_GROUP_AFTER_CAT'][$key_category]]['DATA']['LAST_YEAR_YTD']) ? number_format($data['GROUPING']['SAMICOS'][$data['SHOW_GROUP_AFTER_CAT'][$key_category]]['DATA']['LAST_YEAR_YTD']) : '-' }}</b></td>
            <td class="text-center"><b>{{ isset($data['GROUPING']['SAMICOS'][$data['SHOW_GROUP_AFTER_CAT'][$key_category]]['DATA']['LAST_YEAR_YTD_PCTG']) ? number_format($data['GROUPING']['SAMICOS'][$data['SHOW_GROUP_AFTER_CAT'][$key_category]]['DATA']['LAST_YEAR_YTD_PCTG'], 2)."%" : '-' }}</b></td>
          </tr>
          @endif
        @endif
        <!-- END JIKA TIDAK MENGGUNAKAN GROUPING TOTAL, LANGSUNG TAMPILKAN BERDASARKAN KATEGORI YANG DI LOOP -->

      <!-- DIVIDER -->
      <tr>
        <td class="p-2 border-0" colspan="17"></td>
      </tr>
      <!-- END DIVIDER -->
      @endforeach
      
    @else
      <tr>
        <td colspan="17" class="text-center">No PNL data available</td>
      </tr>
    @endif
  </tbody>
  </table>