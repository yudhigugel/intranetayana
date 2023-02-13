{{-- <div class="pb-0 bg-white mb-3" id="header">
  <div class="row">
    <div class="col-7">
          <h2> SAP PURCHASE ORDER </h2>
          <h3> Purchase Order Detail</h3>
          <h5> @php echo ($data['date_start']!==$data['date_end'])? date('d F Y',strtotime($data['date_start'])).' - '.date('d F Y',strtotime($data['date_end'])) : date('d F Y',strtotime($data['date_start'])) @endphp</h5>
    </div>
    <div class="pt-3 col-5">
      <form method="GET" action="">
        <div class="form-group col-md-6 float-right">
          <div class="mb-1">
            <small style="color:#000;text-align: right;">Pick a date</small>
          </div>
          <input type="text" class="form-control" name="date" id="daterange" value="{{date('m/d/Y',strtotime($data['date_start'])) }} - {{ date('m/d/Y',strtotime($data['date_end'])) }}">

        </div>
      </form>
    </div>
  </div>
</div> --}}
<div class="main-wrapper" style="overflow-x: auto;overflow-y: hidden;">
  <div class="table-wrapper">
    <div class="table-container-h table-responsive">
        <table class="table table-bordered" id="detail-po-table">
          <thead>
              <tr>
                <th class="bg-secondary text-white text-left" style="width: 4%">NO.</th>
                <th class="bg-secondary text-white text-left" style="width: 14%">SAP <br>MATERIAL GROUP</th>
                <th class="bg-secondary text-white text-left" style="width: 13%">SAP <br>MATERIAL NO.</th>
                <th class="bg-secondary text-white text-left" style="width: 25%">SAP <br>MATERIAL DESC</th>
                {{--<th class="bg-secondary text-white text-left">PO <br> UNIT</th>--}}
                <th class="bg-secondary text-white text-left">UoM</th>
                {{--<th class="bg-secondary text-white text-left">PO <br> CURRENCY</th>--}}
                <th class="bg-secondary text-white text-left">CURR</th>
                {{--<th class="bg-secondary text-white text-left">PO <br> QTY</th>--}}
                <th class="bg-secondary text-white text-left">QTY</th>
                <th class="bg-secondary text-white text-left" style="width: 10%">UNIT <br> PRICE</th>
                <th class="bg-secondary text-white text-left" style="width: 10%">EXTENDED AMOUNT</th>
              </tr>
          </thead>
          <tbody>
              @if(isset($data['po']))
                @php
                  $qty_all=0;
                  $price_all=0;
                  $total_all=0;
                @endphp
                @foreach($data['po'] as $dummy)
                @php
                  $qty_all += $dummy->PO_QTY_ITEM;
                  $price_all += $dummy->PO_PRICE;
                  $total_all += $dummy->TOTAL_AMOUNT;
                @endphp
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-center">{{ $dummy->MATERIAL_GROUP }}</a></td>
                    <td class="text-center">{{ $dummy->MATERIAL_NO }}</td>
                    <td class="text-left">{{ $dummy->MATERIAL_DESC }}</td>
                    <td class="text-center">{{ $dummy->PO_UNIT }}</td>
                    <td class="text-center">{{ $dummy->PO_CURRENCY }}</td>
                    <td class="text-right">{{ number_format($dummy->PO_QTY_ITEM) }}</td>
                    <td class="text-right">{{ number_format($dummy->PO_PRICE) }}</td>
                    <td class="text-right">{{ number_format($dummy->TOTAL_AMOUNT) }}</td>
                </tr>
                @endforeach
              @endif
          </tbody>
          <tfoot>
            <tr>
              <td colspan="6" style="text-align: right;background: #ececec;color:#000;font-weight:bold;">TOTAL ALL</td>
              <td style="text-align: right;background: #ececec;color:#000;font-weight:bold;">{{ number_format($qty_all) }}</td>
              <td style="text-align: right;background: #ececec;color:#000;font-weight:bold;">{{ number_format($price_all) }}</td>
              <td style="text-align: right;background: #ececec;color:#000;font-weight:bold;">{{ number_format($total_all) }}</td>
            </tr>
          </tfoot>
      </table>
      <div class="mt-1">
        <small class="text-muted">* Data source from SAP</small>
      </div>
    </div>
  </div>
</div>
