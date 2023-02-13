<div class="main-wrapper" style="overflow-x: auto;overflow-y: hidden;">
  <div class="table-wrapper">
    @if(isset($data['RENTAL_OBJECT_DETAIL']) && count($data['RENTAL_OBJECT_DETAIL']))
      <h5>{{ count($data['RENTAL_OBJECT_DETAIL']) }} Contracts Available</h5>
      @foreach($data['RENTAL_OBJECT_DETAIL'] as $key => $contract)
      <div class="table-container-h table-responsive">
          <table class="table modal-tree-table table-bordered" id="detail-contract-table">
            <thead>
                <tr>
                  <th class="bg-secondary text-white text-left" style="width: 5%">NO.</th>
                  <th class="bg-secondary text-white text-left" style="width: 8%">CONTRACT NO.</th>
                  <th class="bg-secondary text-white text-center" style="width: 15%">CONTRACT NAME</th>
                  <th class="bg-secondary text-white text-center" style="width: 8%">CONTRACT <br> START DATE</th>
                  <th class="bg-secondary text-white text-center" style="width: 8%">CONTRACT <br> END DATE</th>
                  <th class="bg-secondary text-white text-left" style="width: 3%">CURRENCY</th>
                  <th class="bg-secondary text-white text-left" style="width: 13%">TOTAL <br> UNIT PRICE</th>
                  <th class="bg-secondary text-white text-left" style="width: 13%">TOTAL <br> BILL BALANCE</th>
                </tr>
            </thead>
            <tbody>
                
                  <tr data-node-id="{{ $loop->iteration }}">
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-left"><strong>{{ $key }}</strong></td>
                    <td class="text-left">{{ isset($contract['CONTRACT']['CONTRACTNAME']) ? $contract['CONTRACT']['CONTRACTNAME'] : '' }}</td>
                    <td class="text-center">{{ isset($contract['CONTRACT']['CONTRACTSTARTDATE']) ? $contract['CONTRACT']['CONTRACTSTARTDATE'] : '' }}</td>
                    <td class="text-center">{{ isset($contract['CONTRACT']['CONTRACTENDDATE']) ? $contract['CONTRACT']['CONTRACTENDDATE'] : '' }}</td>
                    <td class="text-center">{{ isset($contract['CONTRACT']['CURRENCY']) ? $contract['CONTRACT']['CURRENCY'] : '' }}</td>
                    <td class="text-right">{{ number_format(isset($contract['CONTRACT']['ACCOUNTBALANCEAMOUNT']) ? $contract['CONTRACT']['ACCOUNTBALANCEAMOUNT'] : 0, 2) }}</td>
                    <td class="text-right">{{ number_format(isset($contract['CONTRACT']['BILLBALANCE']) ? $contract['CONTRACT']['BILLBALANCE'] : 0, 2) }}</td>
                  </tr>
                  <tr data-node-id="{{ $loop->iteration }}.1" data-node-pid="{{ $loop->iteration }}">
                    <td colspan="8" class="child">
                      <div id="" class="table-wrapper scrollable">
                        <table class="table table-bordered table-condition-contract" style="white-space: normal;">
                          <thead>
                            <tr>
                              <th class="bg-dark text-white text-left" style="width: 66.4%">CONDITION TYPE</th>
                              {{--<th class="bg-dark text-white text-left" style="width: 19.3%">UNIT PRICE</th>--}}
                              <th class="bg-dark text-white text-left" style="width: 17%">UNIT PRICE</th>
                              <th class="bg-dark text-white text-left" style="width: 17%">BILL BALANCE</th>
                            </tr>
                          </thead>
                          <tbody>
                             @if(isset($contract['CONDITIONS']) && count($contract['CONDITIONS']))
                              @foreach($contract['CONDITIONS'] as $key => $conds)
                              <tr>
                                <td class="text-left">{{ $key }}</td>
                                {{--<td class="text-right">{{ number_format(isset($conds[0]['UNIT_PRICE']) ? $conds[0]['UNIT_PRICE'] : 0, 2) }}</td>--}}
                                <td class="text-right">{{ number_format(isset($conds[0]['ACCOUNTBALANCEAMOUNT']) ? $conds[0]['ACCOUNTBALANCEAMOUNT'] : 0, 2) }}</td>
                                <td class="text-right">{{ number_format(isset($conds[0]['BILLBALANCE']) ? $conds[0]['BILLBALANCE'] : 0, 2) }}</td>
                              </tr>
                              @endforeach
                            @endif
                          </tbody>
                        </table>
                      </div>
                    </td>
                  </tr>
            </tbody>
        </table>
      </div>
      @endforeach
      <div class="mt-1">
        <small class="text-muted">* Data source from SAP</small>
      </div>
    @else
    <div class="text-center">
      <h5>No Contracts Available</h5>
    </div>
    @endif
  </div>
</div>
