<div class="table-container-h table-responsive">
  <table class="table table-border" id="content-table">
      <thead>
          <tr>
          <th class="bg-red text-white text-left"></th>
          <th class="bg-red text-white text-left"><b>Room</b> <br>Total Cancellation</th>
          <th class="bg-red text-white text-left"><b>Room</b> <br>Total Loss Revenue</th>
          <th class="bg-red text-white text-left"><b>Resort</b> <br>Actual Room Revenue</th>
          </tr>
      </thead>
      <tbody>
          @if(isset($data['cancellation_summary']) && $data['cancellation_summary'])
            @foreach($data['cancellation_summary'] as $cancellation_sm)
              <tr>
                <td class="text-left">
                  <strong>
                  @php
                    if(isset($cancellation_sm->Description) && strtolower($cancellation_sm->Description) == 'today')
                      $desc = isset($data['date_resort']) ? date('d M, Y', strtotime($data['date_resort'])) : date('d M, Y');
                    else 
                      $desc = $cancellation_sm->Description;
                  @endphp
                  {{ $desc }}
                  </strong>
                </td>
                <td class="text-right">{{ number_format($cancellation_sm->RoomTotalCancellation ?? 0) }}</td>
                <td class="text-right">{{ number_format($cancellation_sm->RoomTotalLossRevenue ?? 0) }}</td>
                <td class="text-right">{{ number_format($cancellation_sm->TotalRevenue ?? 0)}}</td>
              </tr>
            @endforeach
          @else
            <tr>
              <td class="text-center" colspan="15">No Data</td>
            </tr>
          @endif
      </tbody>
  </table>
</div>