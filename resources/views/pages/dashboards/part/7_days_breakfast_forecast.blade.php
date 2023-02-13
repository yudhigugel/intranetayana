<div class="table-container-h table-responsive">
    <table class="table table-border" id="content-table">
        <thead>
            <tr>
                <th class="bg-black text-white" rowspan="2">Outlet</th>
                @php
                  $tanggal_pertama = date('d M Y', strtotime("+ 1 day",strtotime($data['date_forecast'])));
                  $hari_pertama_echo = date('l',strtotime($tanggal_pertama));
                @endphp
                <!-- Hari pertama -->
                <th class="bg-black text-white" colspan="2">
                  {{$tanggal_pertama}} <br> ({{$hari_pertama_echo}})
                </th>
                <!-- Hari selanjutnya -->
                @for ($i=1;$i<=6;$i++)
                  @php
                      $tanggal_echo = date('d M Y',strtotime("+ $i day", strtotime ($tanggal_pertama) ));
                      $hari_echo = date('l',strtotime($tanggal_echo));
                  @endphp
                  <th class="bg-black text-white" colspan="2">{{$tanggal_echo}} <br> ({{$hari_echo}})</th>
                @endfor
            </tr>
            <tr>
                @for ($i=1;$i<=7;$i++)
                <th class="bg-black text-white">Adult</th>
                <th class="bg-black text-white">Children</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @if (count($data['7days_breakfast']) > 0)
                @foreach ($data['7days_breakfast'] as $forecast_bf)
                    <tr>
                        <td>{{ $forecast_bf->BREAKFAST_OUTLET}}</td>
                        <td class="text-right">{{ (int) $forecast_bf->DAY1_ADULT}}</td>
                        <td class="text-right">{{ (int) $forecast_bf->DAY1_CHILDREN}}</td>
                        <td class="text-right">{{ (int) $forecast_bf->DAY2_ADULT}}</td>
                        <td class="text-right">{{ (int) $forecast_bf->DAY2_CHILDREN}}</td>
                        <td class="text-right">{{ (int) $forecast_bf->DAY3_ADULT}}</td>
                        <td class="text-right">{{ (int) $forecast_bf->DAY3_CHILDREN}}</td>
                        <td class="text-right">{{ (int) $forecast_bf->DAY4_ADULT}}</td>
                        <td class="text-right">{{ (int) $forecast_bf->DAY4_CHILDREN}}</td>
                        <td class="text-right">{{ (int) $forecast_bf->DAY5_ADULT}}</td>
                        <td class="text-right">{{ (int) $forecast_bf->DAY6_CHILDREN}}</td>
                        <td class="text-right">{{ (int) $forecast_bf->DAY6_ADULT}}</td>
                        <td class="text-right">{{ (int) $forecast_bf->DAY6_CHILDREN}}</td>
                        <td class="text-right">{{ (int) $forecast_bf->DAY7_ADULT}}</td>
                        <td class="text-right">{{ (int) $forecast_bf->DAY7_CHILDREN}}</td>
                    </tr>

                @endforeach
            @else
                <tr>
                    <td colspan="15" class="text-center"> No data available </td>
                </tr>
            @endif

        </tbody>
    </table>
</div>