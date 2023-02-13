<div class="card-body main-wrapper" style="overflow-x: auto;overflow-y: hidden; padding-top: 50px">
    <div class="table-wrapper">
      <div class="table-container-h table-responsive" style="overflow-x: unset;">
          <table class="table table-bordered" id="content-table">
            <thead>
              <tr>
                <th style="width: 30.5%" class="text-left">Balance Type</th>
                <th style="width: 9.92%" class="text-left">Balance IDR</th>
                <th style="width: 9.92%" class="text-left">Balance USD</th>
                <th style="width: 9.92%" class="text-left">Balance JPY</th>
                <th style="width: 9.92%" class="text-left">Balance SGD</th>
                <th style="width: 9.92%" class="text-left">Balance EUR</th>
                <th style="width: 9.92%" class="text-left">Balance CNY</th>
                <th style="width: 9.92%" class="text-left">Total Balance IDR</th>
              </tr>
              {{--<tr>
                <th class="text-left">MIDPLAZA GROUP Bank Balance</th>
                <th class="text-right">{{ isset($data['CASH_BALANCE']['SUM_GROUP_IDR']) ? number_format($data['CASH_BALANCE']['SUM_GROUP_IDR'], 2) : '0' }}</th>
                <th  class="text-right">{{ isset($data['CASH_BALANCE']['SUM_GROUP_USD']) ? number_format($data['CASH_BALANCE']['SUM_GROUP_USD'], 2) : '0' }}</th>
                <th class="text-right">{{ isset($data['CASH_BALANCE']['SUM_GROUP_JPY']) ? number_format($data['CASH_BALANCE']['SUM_GROUP_JPY'], 2) : '0' }}</th>
                <th  class="text-right">{{ isset($data['CASH_BALANCE']['SUM_GROUP_SGD']) ? number_format($data['CASH_BALANCE']['SUM_GROUP_SGD'], 2) : '0' }}</th>
                <th  class="text-right">{{ isset($data['CASH_BALANCE']['SUM_GROUP_EUR']) ? number_format($data['CASH_BALANCE']['SUM_GROUP_EUR'], 2) : '0' }}</th>
                <th  class="text-right">{{ isset($data['CASH_BALANCE']['SUM_GROUP_CNY']) ? number_format($data['CASH_BALANCE']['SUM_GROUP_CNY'], 2) : '0' }}</th>
                <th class="text-right">{{ isset($data['CASH_BALANCE']['SUM_GROUP_ALL']) ? number_format($data['CASH_BALANCE']['SUM_GROUP_ALL'], 2) : '0' }}</th>
              </tr>
              <tr>
                <th class="text-left">MIDPLAZA GROUP Time Deposit</th>
                <th class="text-right">{{ isset($data['TIME_DEPOSIT']['SUM_GROUP_IDR']) ? number_format($data['TIME_DEPOSIT']['SUM_GROUP_IDR'], 2) : '0' }}</th>
                <th  class="text-right">{{ isset($data['TIME_DEPOSIT']['SUM_GROUP_USD']) ? number_format($data['TIME_DEPOSIT']['SUM_GROUP_USD'], 2) : '0' }}</th>
                <th class="text-right">{{ isset($data['TIME_DEPOSIT']['SUM_GROUP_JPY']) ? number_format($data['TIME_DEPOSIT']['SUM_GROUP_JPY'], 2) : '0' }}</th>
                <th  class="text-right">{{ isset($data['TIME_DEPOSIT']['SUM_GROUP_SGD']) ? number_format($data['TIME_DEPOSIT']['SUM_GROUP_SGD'], 2) : '0' }}</th>
                <th  class="text-right">{{ isset($data['TIME_DEPOSIT']['SUM_GROUP_EUR']) ? number_format($data['TIME_DEPOSIT']['SUM_GROUP_EUR'], 2) : '0' }}</th>
                <th  class="text-right">{{ isset($data['TIME_DEPOSIT']['SUM_GROUP_CNY']) ? number_format($data['TIME_DEPOSIT']['SUM_GROUP_CNY'], 2) : '0' }}</th>
                <th class="text-right">{{ isset($data['TIME_DEPOSIT']['SUM_GROUP_ALL']) ? number_format($data['TIME_DEPOSIT']['SUM_GROUP_ALL'], 2) : '0' }}</th>
              </tr>--}}

              <tr>
                <th class="text-left">MIDPLAZA GROUP Balance</th>
                <th class="text-right">{{ isset($data['BALANCE']['SUM_IDR']) ? number_format($data['BALANCE']['SUM_IDR'], 2) : '0' }}</th>
                <th  class="text-right">{{ isset($data['BALANCE']['SUM_USD']) ? number_format($data['BALANCE']['SUM_USD'], 2) : '0' }}</th>
                <th class="text-right">{{ isset($data['BALANCE']['SUM_JPY']) ? number_format($data['BALANCE']['SUM_JPY'], 2) : '0' }}</th>
                <th  class="text-right">{{ isset($data['BALANCE']['SUM_SGD']) ? number_format($data['BALANCE']['SUM_SGD'], 2) : '0' }}</th>
                <th  class="text-right">{{ isset($data['BALANCE']['SUM_EUR']) ? number_format($data['BALANCE']['SUM_EUR'], 2) : '0' }}</th>
                <th  class="text-right">{{ isset($data['BALANCE']['SUM_CNY']) ? number_format($data['BALANCE']['SUM_CNY'], 2) : '0' }}</th>
                <th class="text-right">{{ isset($data['BALANCE']['SUM_ALL']) ? number_format($data['BALANCE']['SUM_ALL'], 2) : '0' }}</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td colspan="8" class="parent-child">
                  <table class="table table-tree">
                    <thead>
                      @php
                        $bank_loop = 2;
                      @endphp
                      @if(isset($data['RFC']) && $data['RFC'])
                        @foreach($data['RFC'] as $company_code => $cash_balance)
                        <tr data-node-id="{{ $loop->iteration }}">
                          <th class="bg-black text-white" style="width: 5%">{{ $loop->iteration }}</th>
                          <th class="text-left bg-black text-white" style="width: 25.56%"><span class="text-company">{{ $company_code }}</span></th>
                          <th class="text-left bg-black text-white text-right" style="width: 9.92%">{{ isset($cash_balance['TOTAL_COMPANY']['IDR']) ? number_format($cash_balance['TOTAL_COMPANY']['IDR'], 2) : number_format(0, 2) }}</th>
                          <th class="text-left bg-black text-white text-right" style="width: 9.92%">{{ isset($cash_balance['TOTAL_COMPANY']['USD']) ? number_format($cash_balance['TOTAL_COMPANY']['USD'], 2) : number_format(0, 2) }}</th>
                          <th class="text-left bg-black text-white text-right" style="width: 9.92%">{{ isset($cash_balance['TOTAL_COMPANY']['JPY']) ? number_format($cash_balance['TOTAL_COMPANY']['JPY'], 2) : number_format(0, 2) }}</th>
                          <th class="text-left bg-black text-white text-right" style="width: 9.92%">{{ isset($cash_balance['TOTAL_COMPANY']['SGD']) ? number_format($cash_balance['TOTAL_COMPANY']['SGD'], 2) : number_format(0, 2) }}</th>
                          <th class="text-left bg-black text-white text-right" style="width: 9.92%">{{ isset($cash_balance['TOTAL_COMPANY']['EUR']) ? number_format($cash_balance['TOTAL_COMPANY']['EUR'], 2) : number_format(0, 2) }}</th>
                          <th class="text-left bg-black text-white text-right" style="width: 9.92%">{{ isset($cash_balance['TOTAL_COMPANY']['CNY']) ? number_format($cash_balance['TOTAL_COMPANY']['CNY'], 2) : number_format(0, 2) }}</th>
                          <th class="text-left bg-black text-white text-right" style="width: 9.92%">{{ isset($cash_balance['TOTAL_COMPANY']['TOTAL_IDR']) ? number_format($cash_balance['TOTAL_COMPANY']['TOTAL_IDR'], 2) : number_format(0, 2) }}</th>
                        </tr>
                        <tr data-node-id="{{ $loop->iteration }}.1" data-node-pid="{{ $loop->iteration }}">
                          @php
                            $now_iteration_company = (String)$loop->iteration.'1';
                          @endphp
                          <td colspan="9" class="child">
                            @if(isset($cash_balance['PLANT_BALANCE']) && $cash_balance['PLANT_BALANCE'])
                              @foreach($cash_balance['PLANT_BALANCE'] as $plant_name => $cash_deposit)
                              <div>
                                <div class="bg-black text-white p-2"><strong>{{ $plant_name }}</strong></div>
                                <table class="table table-bordered">
                                  <!-- START CASH BALANCE LOOP -->
                                  <thead>
                                    <tr>
                                      <th class="text-left" colspan="3" style="width: 30.5%">SAP Exchange Rate</th>
                                      <th class="text-right" colspan='2' style="width: 19.84%">{{ number_format($cash_deposit['RATE_USD'], 2) }}</th>
                                      <th class="text-right" style="width: 9.92%">{{ number_format($cash_deposit['RATE_JPY'], 2) }}</th>
                                      <th class="text-right" style="width: 9.92%">{{ number_format($cash_deposit['RATE_SGD'], 2) }}</th>
                                      <th class="text-right" style="width: 9.92%">{{ number_format($cash_deposit['RATE_EUR'], 2) }}</th>
                                      <th class="text-right" style="border-right: none !important; width: 9.92%">{{ number_format($cash_deposit['RATE_CNY'], 2) }}</th>
                                      <th class="text-right" style="border-left: none !important; width: 9.92%"></th>
                                    </tr>
                                  </thead>
                                  <thead>
                                    <tr>
                                      <th class="bg-secondary text-white text-left" style="width: 5%">No.</th>
                                      <th class="bg-secondary text-white text-left" style="width: 19.56%">Bank</th>
                                      <th class="bg-secondary text-white text-left" style="width: 6%">GL Account</th>
                                      <th class="bg-secondary text-white text-left" style="width: 9.92%">Balance IDR</th>
                                      <th class="bg-secondary text-white text-left" style="width: 9.92%">Balance USD</th>
                                      <th class="bg-secondary text-white text-left" style="width: 9.92%">Balance JPY</th>
                                      <th class="bg-secondary text-white text-left" style="width: 9.92%">Balance SGD</th>
                                      <th class="bg-secondary text-white text-left" style="width: 9.92%">Balance EUR</th>
                                      <th class="bg-secondary text-white text-left" style="width: 9.92%">Balance CNY</th>
                                      <th class="bg-secondary text-white text-left" style="width: 9.92%">Total Balance IDR</th>
                                    </tr>
                                  </thead>
                                  <thead>
                                    <tr class="">
                                      <th class="text-center" style="width: 5%">{{ $loop->iteration}}.1</th>
                                      <th class="text-left" colspan='2'>Bank Balance</th>
                                      <th class="text-right">{{ isset($cash_deposit['CASH_BALANCE']['SUM_IDR']) ? number_format($cash_deposit['CASH_BALANCE']['SUM_IDR'], 2) : '0' }}</th>
                                      <th class="text-right">{{ isset($cash_deposit['CASH_BALANCE']['SUM_USD']) ? number_format($cash_deposit['CASH_BALANCE']['SUM_USD'], 2) : '0' }}</th>
                                      <th class="text-right">{{ isset($cash_deposit['CASH_BALANCE']['SUM_JPY']) ? number_format($cash_deposit['CASH_BALANCE']['SUM_JPY'], 2) : '0' }}</th>
                                      <th class="text-right">{{ isset($cash_deposit['CASH_BALANCE']['SUM_SGD']) ? number_format($cash_deposit['CASH_BALANCE']['SUM_SGD']) : '0' }}</th>
                                      <th class="text-right">{{ isset($cash_deposit['CASH_BALANCE']['SUM_EUR']) ? number_format($cash_deposit['CASH_BALANCE']['SUM_EUR']) : '0' }}</th>
                                      <th class="text-right">{{ isset($cash_deposit['CASH_BALANCE']['SUM_CNY']) ? number_format($cash_deposit['CASH_BALANCE']['SUM_CNY']) : '0' }}</th>
                                      <th class="text-right">{{ isset($cash_deposit['CASH_BALANCE']['SUM_ALL']) ? number_format($cash_deposit['CASH_BALANCE']['SUM_ALL'], 2) : '0' }}</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    @if(isset($cash_deposit['CASH_BALANCE']['data']) && $cash_deposit['CASH_BALANCE']['data'])
                                      @php
                                        $parent_loop = 1;
                                      @endphp
                                      @foreach($cash_deposit['CASH_BALANCE']['data'] as $key_bank => $cash_group)
                                          <tr data-node-id="{{ $loop->iteration }}.1.{{ $bank_loop }}" class="bg-primary text-white">
                                            <td colspan="3" class="text-left">Grouping Account - {{ $key_bank }}</td>
                                            <td class="text-right">{{ number_format(isset($cash_group->GROUP_IDR) ? $cash_group->GROUP_IDR : 0, 2) }}</td>
                                            <td class="text-right">{{ number_format(isset($cash_group->GROUP_USD) ? $cash_group->GROUP_USD : 0, 2) }}</td>
                                            <td class="text-right">{{ number_format(isset($cash_group->GROUP_JPY) ? $cash_group->GROUP_JPY : 0, 2) }}</td>
                                            <td class="text-right">{{ number_format(isset($cash_group->GROUP_SGD) ? $cash_group->GROUP_SGD : 0, 2) }}</td>
                                            <td class="text-right">{{ number_format(isset($cash_group->GROUP_EUR) ? $cash_group->GROUP_EUR : 0, 2) }}</td>
                                            <td class="text-right">{{ number_format(isset($cash_group->GROUP_CNY) ? $cash_group->GROUP_CNY : 0, 2) }}</td>
                                            <td class="text-right">{{ number_format(isset($cash_group->GROUP_ALL) ? $cash_group->GROUP_ALL : 0, 2) }}</td>
                                          </tr>
                                          @foreach($cash_group as $cash)
                                          <tr data-node-id="{{ $loop->iteration }}.1" data-node-pid="{{ $parent_loop }}.1.{{ $bank_loop }}">
                                            <td class="text-left child" colspan='2'>
                                              @if(isset($cash->STATUS))
                                                @if(strtolower(trim($cash->STATUS)) == 'bbmp' || strtolower(trim($cash->STATUS)) == 'bbnp' || empty(strtolower(trim($cash->STATUS))))
                                                  <span class="p-1 bg-danger d-inline-block mr-1 rounded" data-toggle="tooltip" data-placement="top" title="MT940 is not yet configured"></span>
                                                @elseif(strtolower(trim($cash->STATUS)) == 'lmpc' || strtolower(trim($cash->STATUS)) == 'npc')
                                                  <span class="p-1 bg-warning d-inline-block mr-1 rounded" data-toggle="tooltip" data-placement="top" title="MT940 is on progress"></span>
                                                @elseif(strtolower(trim($cash->STATUS)) == 'kpab')
                                                  <span class="p-1 bg-success d-inline-block mr-1 rounded" data-toggle="tooltip" data-placement="top" title="MT940 is live and ready"></span>
                                                @endif
                                              @endif
                                              <span style="font-size: 12px; font-weight: bold" class="text-primary mb-1">{{ strtoupper($cash->BANK_NAME) }}</span> 
                                              <br>
                                              <div class="my-1">
                                              <span style="font-size: 10px">
                                                <span class="px-2 py-1 bg-secondary text-white">CODE : {{ $cash->BANK_CODE ? $cash->BANK_CODE : '-' }}</span>
                                                <span class="px-2 py-1 bg-secondary text-white">ACCOUNT : {{ $cash->BANKN ? $cash->BANKN : '-' }}</span>
                                              </span>
                                              </div>
                                            </td>
                                            <td class="text-center">{{ $cash->HKONT }}</td>
                                            <td class="text-right">{{ number_format(isset($cash->BALANCE_IDR) ? $cash->BALANCE_IDR : 0, 2) }}</td>
                                            <td class="text-right">{{ number_format(isset($cash->BALANCE_USD) ? $cash->BALANCE_USD : 0, 2) }}</td>
                                            <td class="text-right">{{ number_format(isset($cash->BALANCE_JPY) ? $cash->BALANCE_JPY : 0, 2) }}</td>
                                            <td class="text-right">{{ number_format(isset($cash->BALANCE_SGD) ? $cash->BALANCE_SGD : 0, 2) }}</td>
                                            <td class="text-right">{{ number_format(isset($cash->BALANCE_EUR) ? $cash->BALANCE_EUR : 0, 2) }}</td>
                                            <td class="text-right">{{ number_format(isset($cash->BALANCE_CNY) ? $cash->BALANCE_CNY : 0, 2) }}</td>
                                            <td class="text-right">{{ number_format(isset($cash->EQUIV_IDR) ? $cash->EQUIV_IDR : 0, 2) }}</td>
                                          </tr>
                                          @endforeach
                                        @php
                                          $bank_loop++;
                                          $parent_loop++;
                                        @endphp
                                      @endforeach
                                    @else
                                    <tr>
                                      <td class="text-center" colspan="15">No data available</td>
                                    </tr>
                                    @endif
                                  <tbody>
                                  <!-- END CASH BALANCE LOOP -->

                                  <!-- TIME DEPOSIT LOOP -->
                                  <thead>
                                    <tr class="">
                                      <th class="text-center" style="width: 5%">{{ $loop->iteration}}.2</th>
                                      <th class="text-left" colspan='2'>Time Deposit</th>
                                      <th class="text-right">{{ isset($cash_deposit['TIME_DEPOSIT']['SUM_IDR']) ? number_format($cash_deposit['TIME_DEPOSIT']['SUM_IDR'], 2) : '0' }}</th>
                                      <th class="text-right">{{ isset($cash_deposit['TIME_DEPOSIT']['SUM_USD']) ? number_format($cash_deposit['TIME_DEPOSIT']['SUM_USD'], 2) : '0' }}</th>
                                      <th class="text-right">{{ isset($cash_deposit['TIME_DEPOSIT']['SUM_JPY']) ? number_format($cash_deposit['TIME_DEPOSIT']['SUM_JPY'], 2) : '0' }}</th>
                                      <th class="text-right">{{ isset($cash_deposit['TIME_DEPOSIT']['SUM_SGD']) ? number_format($cash_deposit['TIME_DEPOSIT']['SUM_SGD'], 2) : '0' }}</th>
                                      <th class="text-right">{{ isset($cash_deposit['TIME_DEPOSIT']['SUM_EUR']) ? number_format($cash_deposit['TIME_DEPOSIT']['SUM_EUR'], 2) : '0' }}</th>
                                      <th class="text-right">{{ isset($cash_deposit['TIME_DEPOSIT']['SUM_CNY']) ? number_format($cash_deposit['TIME_DEPOSIT']['SUM_CNY'], 2) : '0' }}</th>
                                      <th class="text-right">{{ isset($cash_deposit['TIME_DEPOSIT']['SUM_ALL']) ? number_format($cash_deposit['TIME_DEPOSIT']['SUM_ALL'], 2) : '0' }}</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    @if(isset($cash_deposit['TIME_DEPOSIT']['data']) && $cash_deposit['TIME_DEPOSIT']['data'])
                                      @foreach($cash_deposit['TIME_DEPOSIT']['data'] as $time_ds)
                                        <tr>
                                          <td class="text-left" colspan='2'> {{ $time_ds->TXT50 }}</td>
                                          <td class="text-center">{{ $time_ds->SAKNR }}</td>
                                          <td class="text-right">{{ number_format(isset($time_ds->CURR_IDR) ? $time_ds->CURR_IDR : 0, 2) }}</td>
                                          <td class="text-right">{{ number_format(isset($time_ds->CURR_USD) ? $time_ds->CURR_USD : 0, 2) }}</td>
                                          <td class="text-right">{{ number_format(isset($time_ds->CURR_JPY) ? $time_ds->CURR_JPY : 0, 2) }}</td>
                                          <td class="text-right">{{ number_format(isset($time_ds->CURR_SGD) ? $time_ds->CURR_SGD : 0, 2) }}</td>
                                          <td class="text-right">{{ number_format(isset($time_ds->CURR_EUR) ? $time_ds->CURR_EUR : 0, 2) }}</td>
                                          <td class="text-right">{{ number_format(isset($time_ds->CURR_CNY) ? $time_ds->CURR_CNY : 0, 2) }}</td>
                                          <td class="text-right">{{ number_format(isset($time_ds->EQUIV_IDR) ? $time_ds->EQUIV_IDR : 0, 2) }}</td>
                                        </tr>
                                      @endforeach
                                    @else
                                    <tr>
                                      <td class="text-center" colspan="15">No data available</td>
                                    </tr>
                                    @endif
                                  </tbody>
                                  <!-- END TIME DEPOSIT LOOP -->

                                  <!-- OTHER FINANCIAL ASSETS LOOP -->
                                  <thead>
                                    <tr class="">
                                      <th class="text-center" style="width: 5%">{{ $loop->iteration }}.3</th>
                                      <th class="text-left" colspan='2'>Other Financial Assets</th>
                                      <th class="text-right">{{ isset($cash_deposit['OTHER_FIN_ASSET']['SUM_IDR']) ? number_format($cash_deposit['OTHER_FIN_ASSET']['SUM_IDR'], 2) : '0' }}</th>
                                      <th class="text-right">{{ isset($cash_deposit['OTHER_FIN_ASSET']['SUM_USD']) ? number_format($cash_deposit['OTHER_FIN_ASSET']['SUM_USD'], 2) : '0' }}</th>
                                      <th class="text-right">{{ isset($cash_deposit['OTHER_FIN_ASSET']['SUM_JPY']) ? number_format($cash_deposit['OTHER_FIN_ASSET']['SUM_JPY'], 2) : '0' }}</th>
                                      <th class="text-right">{{ isset($cash_deposit['OTHER_FIN_ASSET']['SUM_SGD']) ? number_format($cash_deposit['OTHER_FIN_ASSET']['SUM_SGD'], 2) : '0' }}</th>
                                      <th class="text-right">{{ isset($cash_deposit['OTHER_FIN_ASSET']['SUM_EUR']) ? number_format($cash_deposit['OTHER_FIN_ASSET']['SUM_EUR'], 2) : '0' }}</th>
                                      <th class="text-right">{{ isset($cash_deposit['OTHER_FIN_ASSET']['SUM_CNY']) ? number_format($cash_deposit['OTHER_FIN_ASSET']['SUM_CNY'], 2) : '0' }}</th>
                                      <th class="text-right">{{ isset($cash_deposit['OTHER_FIN_ASSET']['SUM_ALL']) ? number_format($cash_deposit['OTHER_FIN_ASSET']['SUM_ALL'], 2) : '0' }}</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    @if(isset($cash_deposit['OTHER_FIN_ASSET']['data']) && $cash_deposit['OTHER_FIN_ASSET']['data'])
                                      @foreach($cash_deposit['OTHER_FIN_ASSET']['data'] as $oth_fin)
                                        <tr>
                                          <td class="text-left" colspan='2'> {{ $oth_fin->TXT50 }}</td>
                                          <td class="text-center">{{ $oth_fin->SAKNR }}</td>
                                          <td class="text-right">{{ number_format(isset($oth_fin->CURR_IDR) ? $oth_fin->CURR_IDR : 0, 2) }}</td>
                                          <td class="text-right">{{ number_format(isset($oth_fin->CURR_USD) ? $oth_fin->CURR_USD : 0, 2) }}</td>
                                          <td class="text-right">{{ number_format(isset($oth_fin->CURR_JPY) ? $oth_fin->CURR_JPY : 0, 2) }}</td>
                                          <td class="text-right">{{ number_format(isset($oth_fin->CURR_SGD) ? $oth_fin->CURR_SGD : 0, 2) }}</td>
                                          <td class="text-right">{{ number_format(isset($oth_fin->CURR_EUR) ? $oth_fin->CURR_EUR : 0, 2) }}</td>
                                          <td class="text-right">{{ number_format(isset($oth_fin->CURR_CNY) ? $oth_fin->CURR_CNY : 0, 2) }}</td>
                                          <td class="text-right">{{ number_format(isset($oth_fin->EQUIV_IDR) ? $oth_fin->EQUIV_IDR : 0, 2) }}</td>
                                        </tr>
                                      @endforeach
                                    @else
                                    <tr>
                                      <td class="text-center" colspan="15">No data available</td>
                                    </tr>
                                    @endif
                                  </tbody>

                                </table>
                              </div>
                              @endforeach
                            @else
                              <div class="text-center">No plant's balance found</div>
                            @endif
                          </td>
                        </tr>

                        @endforeach
                      @else
                      <tr>
                        <td class="text-center" colspan="15">No data available</td>
                      </tr>
                      @endif
                    </thead>
                </table>
              </td>
            </tr>
          </tbody>
        </table>
        <div class="mt-1">
          <small class="text-muted">* Data source from SAP</small>
        </div>
      </div>
    </div>
</div>

        