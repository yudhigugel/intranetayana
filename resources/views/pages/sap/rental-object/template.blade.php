<!-- Modal -->
<div class="modal fade" id="modal-detail-contract" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header px-4">
        <h5 class="modal-title" id="exampleModalLabel">Contract Detail | <span class="contract-number"></span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="overlay in align-items-center">
            <img width="3%" src="{{ asset('image/loader.gif') }}" alt="Report Loader">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="card-body main-wrapper" style="overflow-x: auto;overflow-y: hidden;">
    @if(Session::has('error_rental'))
      <div class="alert alert-fill-danger alert-dismissable p-3 mb-3" role="alert">
        <i class="mdi mdi-alert-circle"></i>
        {{ Session::get('error_rental') }}
        <button type="button" class="close" style="line-height: 0.7" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif
    <h2>Rental Object Summary</h2>
    <div class="table-wrapper">
      <div class="table-container-h table-responsive">
          <table class="table table-border data-t-able no-filter" id="content-table">
            <thead>
                <tr>
                <th class="bg-secondary text-white text-left" style="width: 6.5%"></th>
                <th class="bg-secondary text-white text-left" style="width: 20%">TOTAL <br> RENTAL OBJECT</th>
                <th class="bg-secondary text-white text-left" style="width: 20%">TOTAL <br> UNSOLD / VACANT UNIT</th>
                <th class="bg-secondary text-white text-left" style="width: 20%">TOTAL <br> SOLD / OCCUPIED UNIT</th>
                <th class="bg-secondary text-white text-left" style="width: 20%">OCCUPANCY RATE (%)</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($data['RENTAL_OBJECT_SUMMARY']) && count($data['RENTAL_OBJECT_SUMMARY']) > 0)
                  @foreach($data['RENTAL_OBJECT_SUMMARY'] as $plant => $data_sum)
                  <tr>
                    <td class="text-left">{{ $plant }}</td>
                    <td class="text-right"><strong>{{ isset($data_sum['TOTAL_ALL']) ? number_format($data_sum['TOTAL_ALL']) : 0 }}</strong></td>
                    <td class="text-right"><strong>{{ isset($data_sum['TOTAL_VACANT']) ? number_format($data_sum['TOTAL_VACANT']) : 0 }}</strong></td>
                    <td class="text-right"><strong>{{ isset($data_sum['TOTAL_OCCUPIED']) ? number_format($data_sum['TOTAL_OCCUPIED']) : 0 }}</strong></td>
                    <td class="text-right"><strong>{{ isset($data_sum['TOTAL_OCCUPANCY_RATE']) ? number_format($data_sum['TOTAL_OCCUPANCY_RATE'], 2) : number_format(0, 2) }}%</strong></td>
                  </tr>
                  @endforeach
                @else
                {{-- <tr>
                  <td colspan="10" class="text-center">No data available</td>
                </tr> --}}
                @endif
            </tbody>
            <tfoot>
              <tr>
                <td class="text-left" style="text-align: right;background: #ececec;color:#000;font-weight:bold;">TOTAL ALL</td>
                <td class="text-right" style="text-align: right;background: #ececec;color:#000;font-weight:bold;"><strong>{{ number_format( (isset($data['RENTAL_OBJECT_SUMMARY_ALL']['TOTAL_ALL']) ? array_sum($data['RENTAL_OBJECT_SUMMARY_ALL']['TOTAL_ALL']) : 0) ) }}</strong></td>
                <td class="text-right" style="text-align: right;background: #ececec;color:#000;font-weight:bold;"><strong>{{ number_format( (isset($data['RENTAL_OBJECT_SUMMARY_ALL']['TOTAL_VACANT']) ? array_sum($data['RENTAL_OBJECT_SUMMARY_ALL']['TOTAL_VACANT']) : 0) ) }}</strong></td>
                <td class="text-right" style="text-align: right;background: #ececec;color:#000;font-weight:bold;"><strong>{{ number_format( (isset($data['RENTAL_OBJECT_SUMMARY_ALL']['TOTAL_OCCUPIED']) ? array_sum($data['RENTAL_OBJECT_SUMMARY_ALL']['TOTAL_OCCUPIED']) : 0) ) }}</strong></td>
                <td class="text-right" style="text-align: right;background: #ececec;color:#000;font-weight:bold;"><strong>{{ number_format( (isset($data['RENTAL_OBJECT_SUMMARY_ALL']['TOTAL_OCCUPANCY_RATE']) && count($data['RENTAL_OBJECT_SUMMARY_ALL']['TOTAL_OCCUPANCY_RATE']) > 0 ? (array_sum($data['RENTAL_OBJECT_SUMMARY_ALL']['TOTAL_OCCUPANCY_RATE']) / count($data['RENTAL_OBJECT_SUMMARY_ALL']['TOTAL_OCCUPANCY_RATE'])) : 0) , 2) }}%
                </strong></td>
              </tr>
            </tfoot>
        </table>
        <!-- <div class="mt-1">
          <small class="text-muted">* Data source from SAP</small>
        </div> -->
      </div>
    </div>
</div>
<div class="card-body main-wrapper" style="overflow-x: hidden;overflow-y: hidden;">
    <h2>Rental Object List</h2>
    <div class="table-wrapper">
      <div class="table-container-h table-responsive">
        <table class="table table-border table-tree no-wrap" style="max-width: 100%;display: block;overflow: auto; white-space: nowrap;" id="content-table">
          @php 
            $company_info = isset($data['FILTER']['P_COMPANY']) ? $data['FILTER']['P_COMPANY'] : '';
          @endphp 
          @if(isset($data['RENTAL_OBJECT_DETAIL']) && $data['RENTAL_OBJECT_DETAIL'])
            @foreach($data['RENTAL_OBJECT_DETAIL'] as $company_code => $refx)
              <thead style="display: block;width: 100%;max-width: 100%">
                <tr data-node-id="{{ $loop->iteration }}" style="display: flex;max-width: 100%">
                  <th class="bg-secondary text-white" style="width: 7%">{{ $loop->iteration }}</th>
                  <th class="text-left bg-secondary text-white" style="width: 93%">{{ isset($data['PLANT']) ? isset($data['PLANT'][$company_code]) ? " (".$company_code.") ".$data['PLANT'][$company_code] : $company_code : $company_code }}</th>
                </tr>
                <tr class="parent-list" data-node-id="{{ $loop->iteration }}.1" data-node-pid="{{ $loop->iteration }}" style="display: block;">
                  <td colspan="2" class="child" style="display: block;">
                    <div class="filter-wrapper mb-3">
                      <div class="row filter-align px-2 text-left" style="position: relative;">
                      </div>
                    </div>
                    <div id="" class="table-wrapper scrollable">
                      <table class="table table-bordered no-wrap data-t-able filterable" style="white-space: normal;">
                          <thead>
                            <tr>
                              <th class="bg-secondary text-white" style="width: 3%">NO.</th>
                              <th class="bg-secondary text-white" style="width: 8%">SAP <br> RENTAL OBJECT ID</th>
                              <th class="bg-secondary text-white" style="width: 13%">SAP <br> RENTAL OBJECT NAME</th>
                              
                              <th class="bg-secondary text-white" style="width: 5%">SAP <br> CUSTOMER ID</th>
                              <th class="bg-secondary text-white" style="width: 12%">SAP <br> CUSTOMER NAME</th>
                              <th class="bg-secondary text-white" style="width: 5%">SAP <br> CUSTOMER PHONE NO.</th>
                              <th class="bg-secondary text-white" style="width: 12%">SAP <br> CUSTOMER EMAIL</th>

                              <th class="bg-secondary text-white" style="width: 4%">SAP <br> BILL TO ID</th>
                              <th class="bg-secondary text-white" style="width: 12%">SAP <br> BILL TO NAME</th>
                              <th class="bg-secondary text-white" style="width: 5%">UNIT STATUS</th>
                              
                              <th class="bg-secondary text-white" style="width: 5%">MEASUREMENT <br> AMOUNT</th>
                              <th class="bg-secondary text-white" style="width: 5%">UoM</th>
                              
                              {{--<th class="bg-secondary text-white" style="width: 3%">Currency</th>--}}
                              {{--<th class="bg-secondary text-white" style="width: 5%">Bill Balance</th>--}}
                              <th class="bg-secondary text-white" style="width: 15%">PENDING MAINTENANCE</th>
                            </tr>
                          </thead>
                          <tbody>
                              @if($refx && count($refx) > 0)
                              @foreach($refx as $key => $rental)
                                <tr>
                                  <td class="text-center">{{ $loop->iteration }}</td>
                                  @if(isset($rental['CONTRACT']) && count($rental['CONTRACT']) > 0)
                                    <td class="text-center"><a data-rental-unit="{{ $key }}" data-company-code="{{ $company_info }}" data-plant="{{ $company_code }}" class="text-primary detail-link" style="cursor: pointer;" data-toggle="modal" data-target="#modal-detail-contract">{{ $key }}</a></td>
                                  @else
                                    <td class="text-center">{{ $key }}</td>
                                  @endif
                                  <td class="text-left">{{ isset($rental['RENTAL_INFO']['SAPRENTALOBJECTNAME']) ? strtoupper($rental['RENTAL_INFO']['SAPRENTALOBJECTNAME']) : '' }}</td>
                                  <td class="text-left">{{ isset($rental['CUSTOMER']['SAPCUSTOMERID']) ? strtoupper($rental['CUSTOMER']['SAPCUSTOMERID']) : '' }}</td>
                                  <td class="text-left">{{ isset($rental['CUSTOMER']['SAPCUSTOMERNAME']) ? strtoupper($rental['CUSTOMER']['SAPCUSTOMERNAME']) : '' }}</td>
                                  <td class="text-left">{!! isset($rental['CUSTOMER']['PHONE_NO']) ? $rental['CUSTOMER']['PHONE_NO'] : '' !!}</td>
                                  <td class="text-left">{!! isset($rental['CUSTOMER']['EMAIL_ADDRES']) ? $rental['CUSTOMER']['EMAIL_ADDRES'] : '' !!}</td>
                                  <td class="text-left">{{ isset($rental['CUSTOMER']['SAPBILLTOID']) ? $rental['CUSTOMER']['SAPBILLTOID'] : '' }}</td>
                                  <td class="text-left">{{ isset($rental['CUSTOMER']['SAPBILLTONAME']) ? $rental['CUSTOMER']['SAPBILLTONAME'] : '' }}</td>
                                  <td class="text-left">{!! isset($rental['RENTAL_INFO']['STATUS']) ? $rental['RENTAL_INFO']['STATUS'] : '' !!}</td>
                                  
                                  <td class="text-right">
                                  @php 
                                    if(isset($rental['RENTAL_INFO']['MEASUREMENT_AMOUNT']) && $rental['RENTAL_INFO']['MEASUREMENT_AMOUNT']){
                                      $i = count($rental['RENTAL_INFO']['MEASUREMENT_AMOUNT']);
                                      foreach($rental['RENTAL_INFO']['MEASUREMENT_AMOUNT'] as $key => $rental_amount){
                                        if($i > 1)
                                          echo number_format($key, 2)."<br>";
                                        else
                                          echo number_format($key, 2);
                                      }
                                    }
                                  @endphp
                                  </td>
                                  <td class="text-left">{!! isset($rental['RENTAL_INFO']['MEASUREMENT_UNIT']) ? $rental['RENTAL_INFO']['MEASUREMENT_UNIT'] : '' !!}</td>

                                  {{--<td class="text-left">{{ isset($rental['CUSTOMER']['CURRENCY']) ? $rental['CUSTOMER']['CURRENCY'] : '' }}</td>--}}
                                  {{--<td class="text-right">{{ isset($rental['CUSTOMER']['BILLBALANCE']) ? number_format($rental['CUSTOMER']['BILLBALANCE'], 2) : '' }}</td>--}}

                                  <td class="text-center">{{ isset($rental['CUSTOMER']['PENDINGMAINTENANCEORDER']) ? $rental['CUSTOMER']['PENDINGMAINTENANCEORDER'] : '' }}</td>
                                </tr>
                              @endforeach
                            @else
                            {{-- <tr>
                              <td class="text-center" colspan="15">No data available</td>
                            </tr> --}}
                            @endif
                          </tbody>
                      </table>
                    </div>
                  </td>
                </tr>
              </thead>
            @endforeach
          @else
            <thead>
              <tr>
                <th class="bg-secondary text-white" >No</th>
                <th class="bg-secondary text-white" >SAP <br> Rental Object ID</th>
                <th class="bg-secondary text-white" >SAP <br> Rental Object Name</th>
                <th class="bg-secondary text-white" >SAP <br> Contract No.</th>
                <th class="bg-secondary text-white" >SAP <br> Contract Name</th>

                <th class="bg-secondary text-white" >SAP <br> Customer ID</th>
                <th class="bg-secondary text-white" >SAP <br> Customer Name</th>
                <th class="bg-secondary text-white" >SAP <br> Bill To ID</th>
                <th class="bg-secondary text-white" >SAP <br> Bill To Name</th>
                <th class="bg-secondary text-white" >Status</th>
                <th class="bg-secondary text-white" >Contract <br> Start Date</th>
                <th class="bg-secondary text-white" >Contract <br> End Date</th>
                <th class="bg-secondary text-white" >Additional Service <br> Condition Type</th>
                <th class="bg-secondary text-white" >Account <br> Balance</th>
                <th class="bg-secondary text-white">Measurement <br> Unit</th>
                <th class="bg-secondary text-white">Bill Balance</th>
                <th class="bg-secondary text-white">Frequency</th>
                <th class="bg-secondary text-white">Pending Maintenance</th>
              </tr>
            </thead>
            <tbody>
                <tr>
                  <td class="text-center" colspan="18">No data available</td>
                </tr>
            </tbody>
          @endif
        </table>
        <!-- <div class="mt-1">
          <small class="text-muted">* Data source from SAP</small>
        </div> -->
      </div>
    </div>
</div>