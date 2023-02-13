<!DOCTYPE html>
<html>
<head>
	<title>REPORT ROOM CLASS {{ isset($data['date']) ? $data['date'] : date('Y-m-d') }}</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<style type="text/css">
		.table-report td,
		.table-report th{
			font-size: 12px;
		}
		.table-report th{
			vertical-align: middle !important;
		}
		.table-report tfoot td{
			font-weight: bold;
		}
	</style>
</head>
<body>
	<div class="container-fluid">
		<div style="margin-bottom: 2em">
			<h4><b>DAILY HOTEL REVENUE</b></h4>
			<div><h4>{{ isset($data['date']) ? date('d F, Y',strtotime($data['date'])) : date('d F, Y') }}</h4></div>
		</div>

		@if(isset($data['room_class']) && count($data['room_class']))
		@foreach($data['room_class'] as $resort => $ref)
		<div style="margin-bottom: 2em">
			<div class="wrapper" style="display: flex;align-items: center">
				<div>
					{{--<img style="width: 80px;height: 80px" class="img img-responsive" src="{{ asset('bali/ayana_template/img/new_logo/REPORT_LOGO/'.$resort.'.png') }}">--}}
				</div>
				<div style="padding-top: 0px">
					<h4 style="margin-bottom: 2rem" class="text-muted"><b>{{ $resort }}</b></h4>
				</div>
			</div>
			<table class="table table-bordered table-report">
			   	<thead>
				    <tr class="bg-info">
				      <th style="font-size: 12px;width: 15%">ROOM CLASS</th>
				      <th style="font-size: 12px">TODAY AVAILABILITY</th>
				      <th style="font-size: 12px">TODAY OCCUPANCY</th>
				      <th style="font-size: 12px">TODAY OCCUPANCY (%)</th>
				      <th style="font-size: 12px">TODAY REVENUE</th>

				      <th style="font-size: 12px">MTD AVAILABILITY</th>
				      <th style="font-size: 12px">MTD OCCUPANCY</th>
				      <th style="font-size: 12px">MTD OCCUPANCY (%)</th>
				      <th style="font-size: 12px">MTD REVENUE</th>

				      <th style="font-size: 12px">YTD AVAILABILITY</th>
				      <th style="font-size: 12px">YTD OCCUPANCY</th>
				      <th style="font-size: 12px">YTD OCCUPANCY (%)</th>
				      <th style="font-size: 12px">YTD REVENUE</th>
				    </tr>
			  	</thead>
				<tbody>
					@foreach($ref as $data_revenue)
					<tr>
						<td>{{ $data_revenue->RoomClass }}</td>

						<td style="text-align: right;">{{ number_format($data_revenue->TodayAvailability) }}</td>
						<td style="text-align: right;">{{ number_format($data_revenue->TodayOccupancy) }}</td>
						<td style="text-align: right;">{{ number_format($data_revenue->TodayOccupancyPctg, 2) }}%</td>
						<td style="text-align: right;">{{ number_format($data_revenue->TodayRevenue) }}</td>

						<td style="text-align: right;">{{ number_format($data_revenue->AvailabilityMTD) }}</td>
						<td style="text-align: right;">{{ number_format($data_revenue->OccupancyMTD) }}</td>
						<td style="text-align: right;">{{ number_format($data_revenue->OccupancyMTDPctg) }}%</td>
						<td style="text-align: right;">{{ number_format($data_revenue->RevenueMTD) }}</td>

						<td style="text-align: right;">{{ number_format($data_revenue->AvailabilityYTD) }}</td>
						<td style="text-align: right;">{{ number_format($data_revenue->OccupancyYTD) }}</td>
						<td style="text-align: right;">{{ number_format($data_revenue->OccupancyYTDPctg) }}%</td>
						<td style="text-align: right;">{{ number_format($data_revenue->RevenueYTD) }}</td>

					</tr>
					@endforeach
				</tbody>
				<tfoot>
					<tr style="background-color: #eaeaea">
						<td><b>TOTAL ALL</b></td>
						<td style="text-align: right;">{{ isset($data['TOTAL_REVENUE'][$resort]['TODAY_AVAILABILITY']) ? number_format($data['TOTAL_REVENUE'][$resort]['TODAY_AVAILABILITY']) : '-' }}</td>
						<td style="text-align: right;">{{ isset($data['TOTAL_REVENUE'][$resort]['TODAY_OCCUPANCY']) ? number_format($data['TOTAL_REVENUE'][$resort]['TODAY_OCCUPANCY']) : '-' }}</td>
						<td style="text-align: right;">{{ isset($data['TOTAL_REVENUE'][$resort]['TODAY_OCCUPANCY_PCTG']) ? number_format($data['TOTAL_REVENUE'][$resort]['TODAY_OCCUPANCY_PCTG'], 2)."%" : '-' }}</td>
						<td style="text-align: right;">{{ isset($data['TOTAL_REVENUE'][$resort]['TODAY_REVENUE']) ? number_format($data['TOTAL_REVENUE'][$resort]['TODAY_REVENUE']) : '-' }}</td>

						<td style="text-align: right;">{{ isset($data['TOTAL_REVENUE'][$resort]['MTD_AVAILABILITY']) ? number_format($data['TOTAL_REVENUE'][$resort]['MTD_AVAILABILITY']) : '-' }}</td>
						<td style="text-align: right;">{{ isset($data['TOTAL_REVENUE'][$resort]['MTD_OCCUPANCY']) ? number_format($data['TOTAL_REVENUE'][$resort]['MTD_OCCUPANCY']) : '-' }}</td>
						<td style="text-align: right;">{{ isset($data['TOTAL_REVENUE'][$resort]['MTD_OCCUPANCY_PCTG']) ? number_format($data['TOTAL_REVENUE'][$resort]['MTD_OCCUPANCY_PCTG'], 2)."%" : '-' }}</td>
						<td style="text-align: right;">{{ isset($data['TOTAL_REVENUE'][$resort]['MTD_REVENUE']) ? number_format($data['TOTAL_REVENUE'][$resort]['MTD_REVENUE']) : '-' }}</td>

						<td style="text-align: right;">{{ isset($data['TOTAL_REVENUE'][$resort]['YTD_AVAILABILITY']) ? number_format($data['TOTAL_REVENUE'][$resort]['YTD_AVAILABILITY']) : '-' }}</td>
						<td style="text-align: right;">{{ isset($data['TOTAL_REVENUE'][$resort]['YTD_OCCUPANCY']) ? number_format($data['TOTAL_REVENUE'][$resort]['YTD_OCCUPANCY']) : '-' }}</td>
						<td style="text-align: right;">{{ isset($data['TOTAL_REVENUE'][$resort]['YTD_OCCUPANCY_PCTG']) ? number_format($data['TOTAL_REVENUE'][$resort]['YTD_OCCUPANCY_PCTG'], 2)."%" : '-' }}</td>
						<td style="text-align: right;">{{ isset($data['TOTAL_REVENUE'][$resort]['YTD_REVENUE']) ? number_format($data['TOTAL_REVENUE'][$resort]['YTD_REVENUE']) : '-' }}</td>
					</tr>
				</tfoot>
			</table>
		</div>
		<hr style="border-top: 1px solid #c7c7c7">
		@endforeach
		@else
		<table class="table table-bordered table-report">
		   	<thead>
			    <tr class="bg-info">
			      <th style="font-size: 12px;width: 15%">ROOM CLASS</th>
			      <th style="font-size: 12px">TODAY AVAILABILITY</th>
			      <th style="font-size: 12px">TODAY OCCUPANCY</th>
			      <th style="font-size: 12px">TODAY OCCUPANCY (%)</th>
			      <th style="font-size: 12px">TODAY REVENUE</th>

			      <th style="font-size: 12px">MTD AVAILABILITY</th>
			      <th style="font-size: 12px">MTD OCCUPANCY</th>
			      <th style="font-size: 12px">MTD OCCUPANCY (%)</th>
			      <th style="font-size: 12px">MTD REVENUE</th>

			      <th style="font-size: 12px">YTD AVAILABILITY</th>
			      <th style="font-size: 12px">YTD OCCUPANCY</th>
			      <th style="font-size: 12px">YTD OCCUPANCY (%)</th>
			      <th style="font-size: 12px">YTD REVENUE</th>
			    </tr>
		  	</thead>
			<tbody>
				<tr>
					<td colspan="13" class="text-center">No Data Available</td>
				</tr>
			</tbody>
		</table>
		@endif
	</div>
</body>
</html>