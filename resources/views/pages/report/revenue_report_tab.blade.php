@extends('layouts.default')

@section('title', 'Revenue Report')

@section('styles')
<style type="text/css">
  .overlay {  
    display: none;
    justify-content: center;
    align-items: flex-start;
    position: absolute;
    z-index: 2;
    opacity: 0;
    background: rgba(255, 255, 255, 0.86);
    transition: opacity 200ms ease-in-out;
    margin: -15px 0 0 0;
    top: 15px;
    left: 0;
    width:100%;
    height: 100%;
  }
  .overlay.in {
    opacity: 1;
    display: flex;
  }
  .fl-scrolls {
    bottom:0;
    height:35px;
    overflow:auto;
    position:fixed;
  }
  .fl-scrolls div {
    height:1px;
    overflow:hidden;
  }
  .fl-scrolls div:before {
    content:""; /* fixes #6 */
  }
  .fl-scrolls-hidden {
    bottom:9999px;
  }
  .sticky {
    position: fixed;
    top: 45px;
    z-index: 99;
    box-shadow: 0px 3px 7px -5px #878787;
  }
  .sticky + .main-wrapper {
    padding-top: auto;
  }
  #header{
    transform: all .7s ease-in-out;
  }
</style>
@endsection

@section('content')
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-custom bg-inverse-primary">
      <li class="breadcrumb-item"><a href="/">Oracle Opera</a></li> 
      <li aria-current="page" class="breadcrumb-item active"><span>Summary Revenue</span></li></ol>
  </nav>

  <!-- Modal -->
  <div class="modal fade" id="modal-comparison" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Compare Revenue</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="wrapper">
            <form method="POST" action="{{ route('revenue_report.all') }}" id="form-comparison">
              {{ csrf_field() }}
              <!-- <div class="mb-3">
                <div class="text-left form-group ml-2 mr-2">
                  <label class="label"><b>Choose Resort / Hotel</b></label>
                  <div style="margin-top: -10px" class="mb-2">
                    <small class="text-muted">* Leave unchecked to get all resort / hotel</small>
                  </div>
                  <div class="d-flex justify-content-center">
                    <div class="form-group flex-fill">
                      <input type="checkbox" name="resort-select[]" value="ARSB">
                      <label class="form-check-label">ARSB - Ayana Resort & Spa Bali</label>
                    </div>
                    <div class="form-group flex-fill">
                      <input type="checkbox" name="resort-select[]" value="RJB">
                      <label class="form-check-label">RJB - Rimba Jimbaran Bali</label>
                    </div>
                  </div>
                </div>
              </div> -->

              <div class="d-flex mb-3">
                <div class="text-left form-group flex-fill ml-2 mr-2">
                  <label class="label"><b>Reference Year</b></label>
                  <select class="form-control" name="reference-year">
                    <?php $now_year = date('Y');
                    while ($now_year >= 2015) {
                    ?>
                      <option value="<?= $now_year; ?>" <?php if($now_year == date('Y')){ echo 'selected'; }?> ><?= $now_year; ?></option>
                    <?php
                    $now_year -= 1; 
                    } ?>
                  </select>
                </div>
                <div class="text-left form-group flex-fill ml-2 mr-2">
                  <label class="label"><b>Year To Compare</b></label>
                  <select class="form-control" name="to-compare-year">
                    <?php $now_year = date('Y');
                    while ($now_year >= 2015) {
                    ?>
                      <option value="<?= $now_year; ?>" <?php if($now_year == date('Y')){ echo 'selected'; }?> ><?= $now_year; ?></option>
                    <?php
                    $now_year -= 1; 
                    } ?>
                  </select>
                </div>
              </div>
              <div class="form-quartal mr-2 ml-2 text-left form-group">
                <label class="label"><b>Choose Quarter</b></label>
                <div style="margin-top: -10px" class="mb-2">
                  <small class="text-muted">* Leave unchecked to get all Quarter</small>
                </div>
                <div class="d-flex justify-content-center">
                  <div class="form-group flex-fill">
                    <input type="checkbox" id="q1-check" name="quartal-select[]" value="Q1">
                    <label class="form-check-label">Q1</label>
                  </div>
                  <div class="form-group flex-fill">
                    <input type="checkbox" id="q2-check" name="quartal-select[]" value="Q2">
                    <label class="form-check-label">Q2</label>
                  </div>
                  <div class="form-group flex-fill">
                    <input type="checkbox" id="q3-check" name="quartal-select[]" value="Q3">
                    <label class="form-check-label">Q3</label>
                  </div>
                  <div class="form-group flex-fill">
                    <input type="checkbox" id="q4-check" name="quartal-select[]" value="Q4">
                    <label class="form-check-label">Q4</label>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onclick="$('#modal-comparison').modal('hide');$('.overlay').addClass('in');setTimeout(function(){document.getElementById('form-comparison').submit()}, 500)">Proceed</button>
        </div>
      </div>
    </div>
  </div>

  <div class="row flex-grow" id="main-header">
        <div class="col-sm-12 stretch-card" style="position: relative;">
            <div class="overlay">
              <img style="width: 40px;" src="{{ asset('image/loader.gif') }}" alt="Report Loader">
            </div>
            <div class="card">
                <div class="card-body main-wrapper pb-0 bg-white" id="header">
                    {{-- <div class="px-0">
                        <div class="d-flex justify-content-between mb-3 border-bottom">
                            <h4 class="card-title mx-auto text-center">
                                <img src="{{ url('/image/ayana_logo.png')}}" style="height:100px;width:auto;margin:10px auto;display:table;">
                                <span>Room Revenue Report {{ $year_to_find }}</span>
                            </h4>
                        </div>
                    </div> --}}

                    <div class="filter-container mb-3">
                        @if(isset($comparison) && $comparison)
                          <a href="{{ route('revenue_report.all') }}" class="btn disabled text-white btn-primary quarter-filter quarter-filter-all">All Quarter</a>
                          <a href="{{ route('revenue_report.quartal', ['quartal' => 'Q1'.$year_lookup]) }}" class="btn disabled text-white btn-primary quarter-filter quarter-filter-q1 quarter-hide">Q1</a>
                          <a href="{{ route('revenue_report.quartal', ['quartal' => 'Q2'.$year_lookup]) }}" class="btn disabled text-white btn-primary quarter-filter quarter-filter-q2 quarter-hide">Q2</a>
                          <a href="{{ route('revenue_report.quartal', ['quartal' => 'Q3'.$year_lookup]) }}" class="btn disabled text-white text-white btn-primary quarter-filter quarter-filter-q3">Q3</a>
                          <a href="{{ route('revenue_report.quartal', ['quartal' => 'Q4'.$year_lookup]) }}" class="btn disabled text-white btn-primary quarter-filter quarter-filter-q4">Q4</a>
                        @else
                        <a onclick="$('.overlay').addClass('in')" href="{{ route('revenue_report.all') }}" class="btn @if(isset($QUARTAL) && count($QUARTAL) == 4) disabled @endif text-white btn-primary quarter-filter quarter-filter-all">All Quarter</a>
                          <a onclick="$('.overlay').addClass('in')" href="{{ route('revenue_report.quartal', ['quartal' => 'Q1'.$year_lookup]) }}" class="btn @if(isset($QUARTAL) && count($QUARTAL) == 1 && array_keys($QUARTAL)[0] == 'Q1') disabled @endif text-white btn-primary quarter-filter quarter-filter-q1 quarter-hide">Q1</a>
                          <a onclick="$('.overlay').addClass('in')" href="{{ route('revenue_report.quartal', ['quartal' => 'Q2'.$year_lookup]) }}" class="btn @if(isset($QUARTAL) && count($QUARTAL) == 1 && array_keys($QUARTAL)[0] == 'Q2') disabled @endif text-white btn-primary quarter-filter quarter-filter-q2 quarter-hide">Q2</a>
                          <a onclick="$('.overlay').addClass('in')" href="{{ route('revenue_report.quartal', ['quartal' => 'Q3'.$year_lookup]) }}" class="btn @if(isset($QUARTAL) && count($QUARTAL) == 1 && array_keys($QUARTAL)[0] == 'Q3') disabled @endif text-white text-white btn-primary quarter-filter quarter-filter-q3">Q3</a>
                          <a onclick="$('.overlay').addClass('in')" href="{{ route('revenue_report.quartal', ['quartal' => 'Q4'.$year_lookup]) }}" class="btn @if(isset($QUARTAL) && count($QUARTAL) == 1 && array_keys($QUARTAL)[0] == 'Q4') disabled @endif text-white btn-primary quarter-filter quarter-filter-q4">Q4</a>
                        @endif

                        <div class="float-right">
                            <div class="dropdown">
                              <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownYearButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                {{ $year_to_find }}
                              </button>
                              <div class="dropdown-menu" aria-labelledby="dropdownYearButton">
                                <?php 
                                $now_date = date('Y');
                                $uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
                                while ($now_date >= 2015) {
                                ?>
                                <a onclick="$('.overlay').addClass('in')" class="dropdown-item text-center" href="<?= implode('?', [$uri_parts[0], 'year='.$now_date]) ?>">{{ $now_date }}</a>
                                <?php 
                                $now_date -= 1;
                                } ?>
                              </div>
                            <a class="btn btn-primary text-white" data-toggle="modal" data-target="#modal-comparison"><i class="fa fa-calendar"></i>&nbsp;&nbsp;Compare Revenue</a>
                            @if(isset($comparison) && $comparison)
                              <a class="btn btn-danger text-white" href="{{ route('revenue_report.all') }}"><i class="fa fa-window-close"></i></a>
                            @endif
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>

                    @if(isset($comparison) && $comparison)
                    <nav>
                      <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" data-toggle="tab" href="#reference-year-{{ $comparison['reference_year'] }}" role="tab" aria-selected="true">{{ $comparison['reference_year'] }} (Reference)</a>
                        <a class="nav-item nav-link" data-toggle="tab" href="#to-compare-year-{{ $comparison['to_compare_year'] }}" role="tab" aria-selected="false">{{ $comparison['to_compare_year'] }} (To-Compare)</a>
                        <!-- <a class="nav-item nav-link" data-toggle="tab" href="#" role="tab" aria-selected="false">%</a> -->
                      </div>
                    </nav>
                    @endif

                </div>
                <div class="card-body main-wrapper" style="overflow-x: auto;overflow-y: hidden;">
                    @if(isset($comparison) && !$comparison)
                    <div class="table-wrapper">
                      <div class="table-container-h table-responsive">
                          <table style="width:99%;
                            font-family:Arial;
                            font-size:12px;
                            border-collapse:collapse;
                            border-spacing:0;
                            background-color:#fff;
                            white-space: nowrap;
                            /*border: 1px solid #ddd;*/
                            margin: 0 1% 0 .5%;
                            border: 0" cellpadding="4">
                            <?php
                            // VAR TO SUM HERE
                            foreach($QUARTAL as $q_name => $month_name){
                              ?>
                                <thead>
                                  <tr>
                                    <td colspan="19" style="border: 0"><h2 style="margin: 1em 2px 5px;text-align: left;font-family:Arial; font-weight: bold;"><?= $q_name.' '.$row_fetched['year']; ?></h2></td>
                                  </tr>
                                </thead>

                                <?php
                                // LOOP RESORT
                                foreach ($row_fetched['hotel_data'] as $resortname => $resortrevenue) {
                                $resort_code = explode('-', $resortname, 2);
                                $resort_code = array_key_exists(0, $resort_code) ? $resort_code[0] : "Unknown";
                                ?>
                                  <thead>
                                    <tr class="text-center">
                                      <th colspan="25" class="bg-secondary text-white" style="border: 1px solid #ddd"><?= strtoupper($resortname); ?></th>
                                    </tr>
                                  </thead>

                                  <thead>
                                    <!-- LOOP MONTH EACH QUARTAL -->
                                    <?php foreach($month_name as $month_each_q){
                                      $show_month_once = true;
                                    ?>
                                      <tr>
                                        <th style="border: 1px solid #ddd">Month</th>
                                        <th style="border: 1px solid #ddd">Room Class</th>
                                        <th style="border: 1px solid #ddd">Nights</th>
                                        <th style="border: 1px solid #ddd">% Occupancy</th>
                                        <th style="border: 1px solid #ddd">Room Revenue</th>
                                        <th style="border: 1px solid #ddd">F&B Revenue</th>
                                        <th style="border: 1px solid #ddd">Other Revenue</th>
                                        <th style="border: 1px solid #ddd">Total Revenue</th>
                                      </tr>
                                      <!-- BEGIN ROOM LOOPING -->
                                      <?php foreach($resortrevenue['ROOM_DETAIL'] as $room_name => $data_month) { ?>
                                        <tr>
                                          <!-- LOOP MONTH NAME ONLY ONCE -->
                                          <?php if($show_month_once){ ?>
                                          <td rowspan="<?php echo count($resortrevenue['ROOM_DETAIL']); ?>" 
                                          style="font-family: Arial;
                                          padding: 5px;
                                          vertical-align: middle;
                                          text-align: center;
                                          font-weight: bold;
                                          border: 1px solid #ddd;
                                          width: 70px">
                                          <a style="font-family: Arial;
                                              font-size: 12px;
                                              cursor: pointer;
                                              text-decoration: none;
                                              font-weight: normal;
                                              color: #000;
                                              "><?= $month_each_q ?>
                                          </a>
                                          </td>
                                          <?php } ?>
                                          <!-- END LOOP MONTH NAME ONLY ONCE -->
                                          <td
                                          style="font-family: Arial;
                                          padding: 5px;
                                          vertical-align: middle;
                                          text-align: left;
                                          font-weight: bold;
                                          border: 1px solid #ddd;
                                          width: 100px">
                                          <a style="font-family: Arial;
                                              font-size: 12px;
                                              cursor: pointer;
                                              text-decoration: none;
                                              font-weight: normal;
                                              color: #1d80ff;
                                              "><?= $room_name ?>
                                          </a>
                                          </td>
                                          <!-- START LOOP FIELD EACH ROOM -->
                                          <?php foreach($data_month[$month_each_q] as $key => $detail){ ?>
                                            <td
                                            style="font-family: Arial;
                                            padding: 5px;
                                            vertical-align: middle;
                                            text-align: right;
                                            font-weight: bold;
                                            border: 1px solid #ddd;">
                                            <a style="font-family: Arial;
                                                font-size: 12px;
                                                cursor: pointer;
                                                text-decoration: none;
                                                font-weight: normal;
                                                ">
                                                <?php if(trim(strtolower($key))=='occ_pr'){ ?>
                                                  <?php
                                                    echo number_format($detail, 2, ".", ',')."%"; 
                                                  ?>
                                                <?php } else { ?>
                                                  <?php 
                                                    echo number_format($detail, 0, ".", ','); 
                                                  ?>
                                                <?php }
                                                ?>
                                            </a>
                                            </td>
                                          <?php
                                          // STORE VALUE EACH MONTH EACH CATEGORY FIELD
                                          $summary[$resortname][$month_each_q][$key][] = $detail;
                                          // Store summary for quartal
                                          $summaryQ[$resortname][$q_name][$room_name][$key][] = $detail;
                                          } ?>
                                          <!-- END LOOP FIELD EACH HROOM -->
                                          </tr>
                                      <?php 
                                      $show_month_once = false;
                                      } ?>
                                      <!-- END ROOM LOOPING -->
                                      <!-- START SUMMARY EACH MONTH -->
                                      <tr style="background: #ececec">
                                        <td colspan="2" 
                                        style="font-family: Arial;
                                        padding: 5px;
                                        vertical-align: middle;
                                        text-align: center;
                                        font-weight: bold;
                                        border: 1px solid #ddd;">
                                        <a style="font-family: Arial;
                                            font-size: 12px;
                                            cursor: pointer;
                                            text-decoration: none;
                                            font-weight: normal;
                                            /*color: #1d80ff;*/
                                            "><b>TOTAL <?= $month_each_q ?></b>
                                        </a>
                                        </td>
                                        <td
                                        style="font-family: Arial;
                                        padding: 5px;
                                        vertical-align: middle;
                                        text-align: right;
                                        font-weight: bold;
                                        border: 1px solid #ddd;">
                                        <a style="font-family: Arial;
                                            font-size: 12px;
                                            cursor: pointer;
                                            text-decoration: none;
                                            font-weight: normal;
                                            /*color: #1d80ff;*/
                                            "><b>
                                            <?php
                                             $summaryQ[$resortname][$q_name]['OCC'][] = array_sum($summary[$resortname][$month_each_q]['OCC']);
                                             echo number_format(array_sum($summary[$resortname][$month_each_q]['OCC']), 0, ".", ','); 
                                            ?></b>
                                        </a>
                                        </td>
                                        <td
                                        style="font-family: Arial;
                                        padding: 5px;
                                        vertical-align: middle;
                                        text-align: right;
                                        font-weight: bold;
                                        border: 1px solid #ddd;">
                                        <a style="font-family: Arial;
                                            font-size: 12px;
                                            cursor: pointer;
                                            text-decoration: none;
                                            font-weight: normal;"><b>
                                            <?php
                                             if(array_filter($summary[$resortname][$month_each_q]['OCC_PR'])){
                                                $total = array_sum($summary[$resortname][$month_each_q]['OCC_PR']) / count(array_filter($summary[$resortname][$month_each_q]['OCC_PR']));
                                                $summaryQ[$resortname][$q_name]['OCC_PR'][] = $total;
                                                echo number_format($total, 2, ".", ',')."%";
                                             } else {
                                                $summaryQ[$resortname][$q_name]['OCC_PR'][] = 0;
                                                echo number_format(0, 2, ".", ',')."%";
                                             }
                                            ?></b>
                                        </a>
                                        </td>
                                        <td
                                        style="font-family: Arial;
                                        padding: 5px;
                                        vertical-align: middle;
                                        text-align: right;
                                        font-weight: bold;
                                        border: 1px solid #ddd;">
                                        <a style="font-family: Arial;
                                            font-size: 12px;
                                            cursor: pointer;
                                            text-decoration: none;
                                            font-weight: normal;
                                            /*color: #1d80ff;*/
                                            "><b>
                                            <?php
                                             $summaryQ[$resortname][$q_name]['ROOM_REV'][] = array_sum($summary[$resortname][$month_each_q]['ROOM_REV']);
                                             echo number_format(array_sum($summary[$resortname][$month_each_q]['ROOM_REV']), 0, ".", ','); 
                                            ?></b>
                                        </a>
                                        </td>
                                        <td
                                        style="font-family: Arial;
                                        padding: 5px;
                                        vertical-align: middle;
                                        text-align: right;
                                        font-weight: bold;
                                        border: 1px solid #ddd;">
                                        <a style="font-family: Arial;
                                            font-size: 12px;
                                            cursor: pointer;
                                            text-decoration: none;
                                            font-weight: normal;
                                            /*color: #1d80ff;*/
                                            "><b>
                                            <?php
                                             $summaryQ[$resortname][$q_name]['FNB_REV'][] = array_sum($summary[$resortname][$month_each_q]['FNB_REV']);
                                             echo number_format(array_sum($summary[$resortname][$month_each_q]['FNB_REV']), 0, ".", ','); 
                                            ?></b>
                                        </a>
                                        </td>
                                        <td
                                        style="font-family: Arial;
                                        padding: 5px;
                                        vertical-align: middle;
                                        text-align: right;
                                        font-weight: bold;
                                        border: 1px solid #ddd;">
                                        <a style="font-family: Arial;
                                            font-size: 12px;
                                            cursor: pointer;
                                            text-decoration: none;
                                            font-weight: normal;
                                            /*color: #1d80ff;*/
                                            "><b>
                                            <?php
                                             $summaryQ[$resortname][$q_name]['OTH_REV'][] = array_sum($summary[$resortname][$month_each_q]['OTH_REV']);
                                             echo number_format(array_sum($summary[$resortname][$month_each_q]['OTH_REV']), 0, ".", ','); 
                                            ?></b>
                                        </a>
                                        </td>
                                        <td
                                        style="font-family: Arial;
                                        padding: 5px;
                                        vertical-align: middle;
                                        text-align: right;
                                        font-weight: bold;
                                        border: 1px solid #ddd;">
                                        <a style="font-family: Arial;
                                            font-size: 12px;
                                            cursor: pointer;
                                            text-decoration: none;
                                            font-weight: normal;
                                            /*color: #1d80ff;*/
                                            "><b>
                                            <?php
                                             $summaryQ[$resortname][$q_name]['TOTAL_REV'][] = array_sum($summary[$resortname][$month_each_q]['TOTAL_REV']);
                                             echo number_format(array_sum($summary[$resortname][$month_each_q]['TOTAL_REV']), 0, ".", ','); 
                                            ?></b>
                                        </a>
                                        </td>
                                      </tr>
                                      <!-- END SUMMARY EACH MONTH -->
                                    </thead>
                                <?php
                                  // END LOOP MONTH PER Q
                                  }
                                ?>
                                  <!-- START SUMMARY EACH Q -->
                                  <tr style="background: #ececec">
                                    <td colspan="2" 
                                    style="font-family: Arial;
                                    padding: 5px;
                                    vertical-align: middle;
                                    text-align: center;
                                    font-weight: bold;
                                    border: 1px solid #ddd;">
                                    <a style="font-family: Arial;
                                        font-size: 12px;
                                        cursor: pointer;
                                        text-decoration: none;
                                        font-weight: normal;
                                        /*color: #1d80ff;*/
                                        "><b>TOTAL <?= $q_name ?> <?= $resortname ?></b>
                                    </a>
                                    </td>
                                    <td
                                      style="font-family: Arial;
                                      padding: 5px;
                                      vertical-align: middle;
                                      text-align: right;
                                      font-weight: bold;
                                      border: 1px solid #ddd;">
                                      <a style="font-family: Arial;
                                        font-size: 12px;
                                        cursor: pointer;
                                        text-decoration: none;
                                        font-weight: normal;
                                        /*color: #1d80ff;*/
                                        "><b>
                                        <?php
                                         echo number_format(array_sum($summaryQ[$resortname][$q_name]['OCC']), 0, ".", ','); 
                                        ?></b>
                                      </a>
                                    </td>
                                    <td
                                      style="font-family: Arial;
                                      padding: 5px;
                                      vertical-align: middle;
                                      text-align: right;
                                      font-weight: bold;
                                      border: 1px solid #ddd;">
                                      <a style="font-family: Arial;
                                        font-size: 12px;
                                        cursor: pointer;
                                        text-decoration: none;
                                        font-weight: normal;
                                        /*color: #1d80ff;*/
                                        "><b>
                                        <?php
                                         if(array_filter($summaryQ[$resortname][$q_name]['OCC_PR'])){
                                            $total_q = array_sum($summaryQ[$resortname][$q_name]['OCC_PR']) / count(array_filter($summaryQ[$resortname][$q_name]['OCC_PR']));
                                            echo number_format($total_q, 2, ".", ',')."%";
                                         }
                                         else {
                                            echo number_format(0, 2, ".", ',')."%";
                                         }
                                        ?></b>
                                      </a>
                                    </td>
                                    <td
                                      style="font-family: Arial;
                                      padding: 5px;
                                      vertical-align: middle;
                                      text-align: right;
                                      font-weight: bold;
                                      border: 1px solid #ddd;">
                                      <a style="font-family: Arial;
                                        font-size: 12px;
                                        cursor: pointer;
                                        text-decoration: none;
                                        font-weight: normal;
                                        /*color: #1d80ff;*/
                                        "><b>
                                        <?php
                                         echo number_format(array_sum($summaryQ[$resortname][$q_name]['ROOM_REV']), 0, ".", ','); 
                                        ?></b>
                                      </a>
                                    </td>
                                    <td
                                      style="font-family: Arial;
                                      padding: 5px;
                                      vertical-align: middle;
                                      text-align: right;
                                      font-weight: bold;
                                      border: 1px solid #ddd;">
                                      <a style="font-family: Arial;
                                        font-size: 12px;
                                        cursor: pointer;
                                        text-decoration: none;
                                        font-weight: normal;
                                        /*color: #1d80ff;*/
                                        "><b>
                                        <?php
                                         echo number_format(array_sum($summaryQ[$resortname][$q_name]['FNB_REV']), 0, ".", ','); 
                                        ?></b>
                                      </a>
                                    </td>
                                    <td
                                      style="font-family: Arial;
                                      padding: 5px;
                                      vertical-align: middle;
                                      text-align: right;
                                      font-weight: bold;
                                      border: 1px solid #ddd;">
                                      <a style="font-family: Arial;
                                        font-size: 12px;
                                        cursor: pointer;
                                        text-decoration: none;
                                        font-weight: normal;
                                        /*color: #1d80ff;*/
                                        "><b>
                                        <?php
                                         echo number_format(array_sum($summaryQ[$resortname][$q_name]['OTH_REV']), 0, ".", ','); 
                                        ?></b>
                                      </a>
                                    </td>
                                    <td
                                      style="font-family: Arial;
                                      padding: 5px;
                                      vertical-align: middle;
                                      text-align: right;
                                      font-weight: bold;
                                      border: 1px solid #ddd;">
                                      <a style="font-family: Arial;
                                        font-size: 12px;
                                        cursor: pointer;
                                        text-decoration: none;
                                        font-weight: normal;
                                        /*color: #1d80ff;*/
                                        "><b>
                                        <?php
                                         echo number_format(array_sum($summaryQ[$resortname][$q_name]['TOTAL_REV']), 0, ".", ','); 
                                        ?></b>
                                      </a>
                                    </td>
                                  </tr>
                                  <!-- END SUMMARY EACH Q -->
                                <?php
                                // END LOOP RESORTNAME
                                }
                              // END LOOP QUARTAL
                              $show_export = false;
                            }
                            ?>
                        </table>
                      </div>
                      <!-- END TABLE RESPONSIVE -->
                      <!-- YTD START -->
                      <div class="table-container-h table-responsive">
                        <table style="width:99%;
                          font-family:Arial;
                          font-size:12px;
                          border-collapse:collapse;
                          border-spacing:0;
                          background-color:#fff;
                          white-space: nowrap;
                          /*border: 1px solid #ddd;*/
                          margin: 0 1% 0 .5%;
                          border: 0" cellpadding="4">
                          <thead>
                            <tr>
                              <td colspan="19"><h2 style="margin: 1.5em 2px 5px;text-align: left;font-family:Arial; font-weight: bold;">YTD <?= $row_fetched['year']; ?></h2></td>
                              <!-- Only show export Once -->
                            </tr>
                          </thead>
                          <!-- START LOOP RESORT NAME -->
                          <?php
                          foreach($QUARTAL as $q_name => $month_name){ 
                            foreach ($row_fetched['hotel_data'] as $resortname => $resortrevenue) {
                              $show_q_once = true;
                          ?>
                              <thead>
                                <tr class="text-center">
                                  <th colspan="25" class="bg-secondary text-white" style="border: 1px solid #ddd"><?= strtoupper($resortname); ?></th>
                                </tr>
                              </thead>

                              <thead>
                                <tr>
                                  <th style="border: 1px solid #ddd">Quarter</th>
                                  <th style="border: 1px solid #ddd">Room Class</th>
                                  <th style="border: 1px solid #ddd">Nights</th>
                                  <th style="border: 1px solid #ddd">% Occupancy</th>
                                  <th style="border: 1px solid #ddd">Room Revenue</th>
                                  <th style="border: 1px solid #ddd">F&B Revenue</th>
                                  <th style="border: 1px solid #ddd">Other Revenue</th>
                                  <th style="border: 1px solid #ddd">Total Revenue</th>
                                </tr>

                              <?php foreach($resortrevenue['ROOM_DETAIL'] as $room_name => $data_month){ ?>
                                <tr>
                                  <?php if($show_q_once){ ?>
                                  <td rowspan="<?php echo count($resortrevenue['ROOM_DETAIL']); ?>" 
                                  style="font-family: Arial;
                                  padding: 5px;
                                  vertical-align: middle;
                                  text-align: center;
                                  font-weight: bold;
                                  border: 1px solid #ddd;
                                  width: 70px">
                                  <a style="font-family: Arial;
                                      font-size: 12px;
                                      cursor: pointer;
                                      text-decoration: none;
                                      font-weight: normal;
                                      color: #000;
                                      "><?= $q_name ?>
                                  </a>
                                  </td>
                                  <?php } ?>
                                  <td
                                  style="font-family: Arial;
                                  padding: 5px;
                                  vertical-align: middle;
                                  text-align: left;
                                  font-weight: bold;
                                  border: 1px solid #ddd;
                                  width: 100px">
                                  <a style="font-family: Arial;
                                    font-size: 12px;
                                    cursor: pointer;
                                    text-decoration: none;
                                    font-weight: normal;
                                    color: #1d80ff;
                                    "><?= $room_name ?>
                                  </a>
                                  </td>
                                  <?php foreach($summaryQ[$resortname][$q_name][$room_name] as $key => $detail){ ?>
                                    <td
                                    style="font-family: Arial;
                                    padding: 5px;
                                    vertical-align: middle;
                                    text-align: right;
                                    font-weight: bold;
                                    border: 1px solid #ddd;">
                                    <a style="font-family: Arial;
                                        font-size: 12px;
                                        cursor: pointer;
                                        text-decoration: none;
                                        font-weight: normal;
                                        ">
                                        <?php if(trim(strtolower($key))=='occ_pr'){ ?>
                                          <?php
                                            if(array_filter($detail)) {
                                              $total_each_q = array_sum($detail) / count(array_filter($detail));
                                              echo number_format($total_each_q, 2, ".", ',')."%";
                                            }
                                            else {
                                              echo number_format(0, 0, ".", ',')."%";
                                            }
                                          ?>
                                        <?php } else { ?>
                                          <?php 
                                            echo number_format(array_sum($detail), 0, ".", ','); 
                                          ?>
                                        <?php }
                                        ?>
                                    </a>
                                    </td>
                                  <?php
                                  } ?>
                                </tr>
                              <?php 
                              $show_q_once = false;
                              } ?>
                              </thead>

                              <!-- START SUMMARY EACH Q -->
                              <tr style="background: #ececec">
                                <td colspan="2" 
                                style="font-family: Arial;
                                padding: 5px;
                                vertical-align: middle;
                                text-align: center;
                                font-weight: bold;
                                border: 1px solid #ddd;">
                                <a style="font-family: Arial;
                                    font-size: 12px;
                                    cursor: pointer;
                                    text-decoration: none;
                                    font-weight: normal;
                                    /*color: #1d80ff;*/
                                    "><b>TOTAL <?= $q_name ?> <?= $resortname ?></b>
                                </a>
                                </td>
                                <td
                                  style="font-family: Arial;
                                  padding: 5px;
                                  vertical-align: middle;
                                  text-align: right;
                                  font-weight: bold;
                                  border: 1px solid #ddd;">
                                  <a style="font-family: Arial;
                                    font-size: 12px;
                                    cursor: pointer;
                                    text-decoration: none;
                                    font-weight: normal;
                                    /*color: #1d80ff;*/
                                    "><b>
                                    <?php
                                     $summaryYTDQ['all_resort'][$q_name]['OCC'][] = array_sum($summaryQ[$resortname][$q_name]['OCC']);
                                     echo number_format(array_sum($summaryQ[$resortname][$q_name]['OCC']), 0, ".", ','); 
                                    ?></b>
                                  </a>
                                </td>
                                <td
                                  style="font-family: Arial;
                                  padding: 5px;
                                  vertical-align: middle;
                                  text-align: right;
                                  font-weight: bold;
                                  border: 1px solid #ddd;">
                                  <a style="font-family: Arial;
                                    font-size: 12px;
                                    cursor: pointer;
                                    text-decoration: none;
                                    font-weight: normal;
                                    /*color: #1d80ff;*/
                                    "><b>
                                    <?php
                                     if(array_filter($summaryQ[$resortname][$q_name]['OCC_PR'])){
                                        $total_q = array_sum($summaryQ[$resortname][$q_name]['OCC_PR']) / count(array_filter($summaryQ[$resortname][$q_name]['OCC_PR']));
                                        $summaryYTDQ['all_resort'][$q_name]['OCC_PR'][] = $total_q;
                                        echo number_format($total_q, 2, ".", ',')."%";
                                     }
                                     else {
                                        $summaryYTDQ['all_resort'][$q_name]['OCC_PR'][] = 0;
                                        echo number_format(0, 2, ".", ',')."%";
                                     }
                                    ?></b>
                                  </a>
                                </td>
                                <td
                                  style="font-family: Arial;
                                  padding: 5px;
                                  vertical-align: middle;
                                  text-align: right;
                                  font-weight: bold;
                                  border: 1px solid #ddd;">
                                  <a style="font-family: Arial;
                                    font-size: 12px;
                                    cursor: pointer;
                                    text-decoration: none;
                                    font-weight: normal;
                                    /*color: #1d80ff;*/
                                    "><b>
                                    <?php
                                     $summaryYTDQ['all_resort'][$q_name]['ROOM_REV'][] = array_sum($summaryQ[$resortname][$q_name]['ROOM_REV']);
                                     echo number_format(array_sum($summaryQ[$resortname][$q_name]['ROOM_REV']), 0, ".", ','); 
                                    ?></b>
                                  </a>
                                </td>
                                <td
                                  style="font-family: Arial;
                                  padding: 5px;
                                  vertical-align: middle;
                                  text-align: right;
                                  font-weight: bold;
                                  border: 1px solid #ddd;">
                                  <a style="font-family: Arial;
                                    font-size: 12px;
                                    cursor: pointer;
                                    text-decoration: none;
                                    font-weight: normal;
                                    /*color: #1d80ff;*/
                                    "><b>
                                    <?php
                                     $summaryYTDQ['all_resort'][$q_name]['FNB_REV'][] = array_sum($summaryQ[$resortname][$q_name]['FNB_REV']);
                                     echo number_format(array_sum($summaryQ[$resortname][$q_name]['FNB_REV']), 0, ".", ','); 
                                    ?></b>
                                  </a>
                                </td>
                                <td
                                  style="font-family: Arial;
                                  padding: 5px;
                                  vertical-align: middle;
                                  text-align: right;
                                  font-weight: bold;
                                  border: 1px solid #ddd;">
                                  <a style="font-family: Arial;
                                    font-size: 12px;
                                    cursor: pointer;
                                    text-decoration: none;
                                    font-weight: normal;
                                    /*color: #1d80ff;*/
                                    "><b>
                                    <?php
                                     $summaryYTDQ['all_resort'][$q_name]['OTH_REV'][] = array_sum($summaryQ[$resortname][$q_name]['OTH_REV']);
                                     echo number_format(array_sum($summaryQ[$resortname][$q_name]['OTH_REV']), 0, ".", ','); 
                                    ?></b>
                                  </a>
                                </td>
                                <td
                                  style="font-family: Arial;
                                  padding: 5px;
                                  vertical-align: middle;
                                  text-align: right;
                                  font-weight: bold;
                                  border: 1px solid #ddd;">
                                  <a style="font-family: Arial;
                                    font-size: 12px;
                                    cursor: pointer;
                                    text-decoration: none;
                                    font-weight: normal;
                                    /*color: #1d80ff;*/
                                    "><b>
                                    <?php
                                     $summaryYTDQ['all_resort'][$q_name]['TOTAL_REV'][] = array_sum($summaryQ[$resortname][$q_name]['TOTAL_REV']);
                                     echo number_format(array_sum($summaryQ[$resortname][$q_name]['TOTAL_REV']), 0, ".", ','); 
                                    ?></b>
                                  </a>
                                </td>
                              </tr>
                              <!-- END SUMMARY EACH Q -->
                          <?php 
                          // END LOOP RESORTNAME
                          }
                          ?>
                          <!-- START SUMMARY QUARTAL ALL RESORT -->
                          <tr style="background: #ececec">
                            <td colspan="2" 
                              style="font-family: Arial;
                              padding: 5px;
                              vertical-align: middle;
                              text-align: center;
                              font-weight: bold;
                              border: 1px solid #ddd;">
                              <a style="font-family: Arial;
                                  font-size: 12px;
                                  cursor: pointer;
                                  text-decoration: none;
                                  font-weight: normal;
                                  /*color: #1d80ff;*/
                                  "><b>TOTAL <?= $q_name ?> All Resort</b>
                              </a>
                              </td>
                            <?php foreach($summaryYTDQ['all_resort'][$q_name] as $key => $detail){ ?>
                              <td
                              style="font-family: Arial;
                              padding: 5px;
                              vertical-align: middle;
                              text-align: right;
                              font-weight: bold;
                              border: 1px solid #ddd;">
                              <a style="font-family: Arial;
                                  font-size: 12px;
                                  cursor: pointer;
                                  text-decoration: none;
                                  font-weight: bold;
                                  ">
                                  <?php if(trim(strtolower($key))=='occ_pr'){ ?>
                                    <?php
                                      if(array_filter($detail)) {
                                        $total_occ_pr = array_sum($detail) / count(array_filter($detail));
                                        $summaryYTDAllResort['all_resort'][$key][] = $total_occ_pr;
                                        echo number_format($total_occ_pr, 2, ".", ',')."%";
                                      }
                                      else {
                                        $summaryYTDAllResort['all_resort'][$key][] = 0;
                                        echo number_format(0, 0, ".", ',')."%";
                                      }
                                    ?>
                                  <?php } else { ?>
                                    <?php
                                      $summaryYTDAllResort['all_resort'][$key][] = array_sum($detail);
                                      echo number_format(array_sum($detail), 0, ".", ','); 
                                    ?>
                                  <?php }
                                  ?>
                              </a>
                              </td>
                            <?php } ?>
                          </tr>
                          <!-- END SUMMARY ALL REPORT EACH QUARTAL -->
                        <?php
                        // END LOOP QUARTAL
                        }
                        ?>

                        <tr style="background: #ececec">
                            <td colspan="2" 
                              style="font-family: Arial;
                              padding: 5px;
                              vertical-align: middle;
                              text-align: center;
                              font-weight: bold;
                              border: 1px solid #ddd;">
                              <a style="font-family: Arial;
                                  font-size: 12px;
                                  cursor: pointer;
                                  text-decoration: none;
                                  font-weight: normal;
                                  /*color: #1d80ff;*/
                                  "><b>TOTAL YTD All Resort</b>
                              </a>
                              </td>
                            <?php foreach($summaryYTDAllResort['all_resort'] as $key => $detail){ ?>
                              <td
                              style="font-family: Arial;
                              padding: 5px;
                              vertical-align: middle;
                              text-align: right;
                              font-weight: bold;
                              border: 1px solid #ddd;">
                              <a style="font-family: Arial;
                                  font-size: 12px;
                                  cursor: pointer;
                                  text-decoration: none;
                                  font-weight: bold;
                                  ">
                                   <?php if(trim(strtolower($key))=='occ_pr'){ ?>
                                    <?php
                                      if(array_filter($detail)) {
                                        $total_occ_all_resort = array_sum($detail) / count(array_filter($detail));
                                        echo number_format($total_occ_all_resort, 2, ".", ',')."%";
                                      }
                                      else {
                                        echo number_format(0, 0, ".", ',')."%";
                                      }
                                    ?>
                                  <?php } else { ?>
                                    <?php 
                                      echo number_format(array_sum($detail), 0, ".", ','); 
                                    ?>
                                  <?php }
                                  ?>
                              </a>
                              </td>
                            <?php } ?>
                          </tr>
                        </table>
                      </div>
                    <!-- END TABLE WRAPPER FOR YTD AND QUARTAL TABLE -->
                    </div>
                    @else
                    <!-- START REVENUE COMPARISON -->
                    <div class="tab-content" id="tab-comparison-content">
                      <!-- START REFERENCE YEAR -->
                      <div class="tab-pane fade show active" id="reference-year-{{ $comparison['reference_year'] }}" role="tabpanel">
                        <div class="table-container-h table-responsive">
                            <table style="width:99%;
                              font-family:Arial;
                              font-size:12px;
                              border-collapse:collapse;
                              border-spacing:0;
                              background-color:#fff;
                              white-space: nowrap;
                              /*border: 1px solid #ddd;*/
                              margin: 0 1% 0 .5%;
                              border: 0" cellpadding="4">
                              <?php
                              // VAR TO SUM HERE
                              foreach($QUARTAL as $q_name => $month_name){
                                ?>
                                  <thead>
                                    <tr>
                                      <td colspan="19" style="border: 0"><h2 style="margin: 1em 2px 5px;text-align: left;font-family:Arial; font-weight: bold;"><?= $q_name.' '.$row_fetched[$comparison['reference_year']]['year']; ?></h2></td>
                                    </tr>
                                  </thead>

                                  <?php
                                  // LOOP RESORT
                                  foreach ($row_fetched[$comparison['reference_year']]['hotel_data'] as $resortname => $resortrevenue) {
                                  $resort_code = explode('-', $resortname, 2);
                                  $resort_code = array_key_exists(0, $resort_code) ? $resort_code[0] : "Unknown";
                                  ?>
                                    <thead>
                                      <tr class="text-center">
                                        <th colspan="25" class="bg-secondary text-white" style="border: 1px solid #ddd"><?= strtoupper($resortname); ?></th>
                                      </tr>
                                    </thead>

                                    <thead>
                                      <!-- LOOP MONTH EACH QUARTAL -->
                                      <?php foreach($month_name as $month_each_q){
                                        $show_month_once = true;
                                      ?>
                                        <tr>
                                          <th style="border: 1px solid #ddd">Month</th>
                                          <th style="border: 1px solid #ddd">Room Class</th>
                                          <th style="border: 1px solid #ddd">Nights</th>
                                          <th style="border: 1px solid #ddd">% Occupancy</th>
                                          <th style="border: 1px solid #ddd">Room Revenue</th>
                                          <th style="border: 1px solid #ddd">F&B Revenue</th>
                                          <th style="border: 1px solid #ddd">Other Revenue</th>
                                          <th style="border: 1px solid #ddd">Total Revenue</th>
                                        </tr>
                                        <!-- BEGIN ROOM LOOPING -->
                                        <?php foreach($resortrevenue['ROOM_DETAIL'] as $room_name => $data_month) { ?>
                                          <tr>
                                            <!-- LOOP MONTH NAME ONLY ONCE -->
                                            <?php if($show_month_once){ ?>
                                            <td rowspan="<?php echo count($resortrevenue['ROOM_DETAIL']); ?>" 
                                            style="font-family: Arial;
                                            padding: 5px;
                                            vertical-align: middle;
                                            text-align: center;
                                            font-weight: bold;
                                            border: 1px solid #ddd;
                                            width: 70px">
                                            <a style="font-family: Arial;
                                                font-size: 12px;
                                                cursor: pointer;
                                                text-decoration: none;
                                                font-weight: normal;
                                                color: #000;
                                                "><?= $month_each_q ?>
                                            </a>
                                            </td>
                                            <?php } ?>
                                            <!-- END LOOP MONTH NAME ONLY ONCE -->
                                            <td
                                            style="font-family: Arial;
                                            padding: 5px;
                                            vertical-align: middle;
                                            text-align: left;
                                            font-weight: bold;
                                            border: 1px solid #ddd;
                                            width: 100px">
                                            <a style="font-family: Arial;
                                                font-size: 12px;
                                                cursor: pointer;
                                                text-decoration: none;
                                                font-weight: normal;
                                                color: #1d80ff;
                                                "><?= $room_name ?>
                                            </a>
                                            </td>
                                            <!-- START LOOP FIELD EACH ROOM -->
                                            <?php foreach($data_month[$month_each_q] as $key => $detail){ ?>
                                              <td
                                              style="font-family: Arial;
                                              padding: 5px;
                                              vertical-align: middle;
                                              text-align: right;
                                              font-weight: bold;
                                              border: 1px solid #ddd;">
                                              <a style="font-family: Arial;
                                                  font-size: 12px;
                                                  cursor: pointer;
                                                  text-decoration: none;
                                                  font-weight: normal;
                                                  ">
                                                  <?php if(trim(strtolower($key))=='occ_pr'){ ?>
                                                    <?php
                                                      echo number_format($detail, 2, ".", ',')."%"; 
                                                    ?>
                                                  <?php } else { ?>
                                                    <?php 
                                                      echo number_format($detail, 0, ".", ','); 
                                                    ?>
                                                  <?php }
                                                  ?>
                                              </a>
                                              </td>
                                            <?php
                                            // STORE VALUE EACH MONTH EACH CATEGORY FIELD
                                            $summary[$resortname][$month_each_q][$key][] = $detail;
                                            // Store summary for quartal
                                            $summaryQ[$resortname][$q_name][$room_name][$key][] = $detail;
                                            } ?>
                                            <!-- END LOOP FIELD EACH HROOM -->
                                            </tr>
                                        <?php 
                                        $show_month_once = false;
                                        } ?>
                                        <!-- END ROOM LOOPING -->
                                        <!-- START SUMMARY EACH MONTH -->
                                        <tr style="background: #ececec">
                                          <td colspan="2" 
                                          style="font-family: Arial;
                                          padding: 5px;
                                          vertical-align: middle;
                                          text-align: center;
                                          font-weight: bold;
                                          border: 1px solid #ddd;">
                                          <a style="font-family: Arial;
                                              font-size: 12px;
                                              cursor: pointer;
                                              text-decoration: none;
                                              font-weight: normal;
                                              /*color: #1d80ff;*/
                                              "><b>TOTAL <?= $month_each_q ?></b>
                                          </a>
                                          </td>
                                          <td
                                          style="font-family: Arial;
                                          padding: 5px;
                                          vertical-align: middle;
                                          text-align: right;
                                          font-weight: bold;
                                          border: 1px solid #ddd;">
                                          <a style="font-family: Arial;
                                              font-size: 12px;
                                              cursor: pointer;
                                              text-decoration: none;
                                              font-weight: normal;
                                              /*color: #1d80ff;*/
                                              "><b>
                                              <?php
                                               $summaryQ[$resortname][$q_name]['OCC'][] = array_sum($summary[$resortname][$month_each_q]['OCC']);
                                               echo number_format(array_sum($summary[$resortname][$month_each_q]['OCC']), 0, ".", ','); 
                                              ?></b>
                                          </a>
                                          </td>
                                          <td
                                          style="font-family: Arial;
                                          padding: 5px;
                                          vertical-align: middle;
                                          text-align: right;
                                          font-weight: bold;
                                          border: 1px solid #ddd;">
                                          <a style="font-family: Arial;
                                              font-size: 12px;
                                              cursor: pointer;
                                              text-decoration: none;
                                              font-weight: normal;"><b>
                                              <?php
                                               if(array_filter($summary[$resortname][$month_each_q]['OCC_PR'])){
                                                  $total = array_sum($summary[$resortname][$month_each_q]['OCC_PR']) / count(array_filter($summary[$resortname][$month_each_q]['OCC_PR']));
                                                  $summaryQ[$resortname][$q_name]['OCC_PR'][] = $total;
                                                  echo number_format($total, 2, ".", ',')."%";
                                               } else {
                                                  $summaryQ[$resortname][$q_name]['OCC_PR'][] = 0;
                                                  echo number_format(0, 2, ".", ',')."%";
                                               }
                                              ?></b>
                                          </a>
                                          </td>
                                          <td
                                          style="font-family: Arial;
                                          padding: 5px;
                                          vertical-align: middle;
                                          text-align: right;
                                          font-weight: bold;
                                          border: 1px solid #ddd;">
                                          <a style="font-family: Arial;
                                              font-size: 12px;
                                              cursor: pointer;
                                              text-decoration: none;
                                              font-weight: normal;
                                              /*color: #1d80ff;*/
                                              "><b>
                                              <?php
                                               $summaryQ[$resortname][$q_name]['ROOM_REV'][] = array_sum($summary[$resortname][$month_each_q]['ROOM_REV']);
                                               echo number_format(array_sum($summary[$resortname][$month_each_q]['ROOM_REV']), 0, ".", ','); 
                                              ?></b>
                                          </a>
                                          </td>
                                          <td
                                          style="font-family: Arial;
                                          padding: 5px;
                                          vertical-align: middle;
                                          text-align: right;
                                          font-weight: bold;
                                          border: 1px solid #ddd;">
                                          <a style="font-family: Arial;
                                              font-size: 12px;
                                              cursor: pointer;
                                              text-decoration: none;
                                              font-weight: normal;
                                              /*color: #1d80ff;*/
                                              "><b>
                                              <?php
                                               $summaryQ[$resortname][$q_name]['FNB_REV'][] = array_sum($summary[$resortname][$month_each_q]['FNB_REV']);
                                               echo number_format(array_sum($summary[$resortname][$month_each_q]['FNB_REV']), 0, ".", ','); 
                                              ?></b>
                                          </a>
                                          </td>
                                          <td
                                          style="font-family: Arial;
                                          padding: 5px;
                                          vertical-align: middle;
                                          text-align: right;
                                          font-weight: bold;
                                          border: 1px solid #ddd;">
                                          <a style="font-family: Arial;
                                              font-size: 12px;
                                              cursor: pointer;
                                              text-decoration: none;
                                              font-weight: normal;
                                              /*color: #1d80ff;*/
                                              "><b>
                                              <?php
                                               $summaryQ[$resortname][$q_name]['OTH_REV'][] = array_sum($summary[$resortname][$month_each_q]['OTH_REV']);
                                               echo number_format(array_sum($summary[$resortname][$month_each_q]['OTH_REV']), 0, ".", ','); 
                                              ?></b>
                                          </a>
                                          </td>
                                          <td
                                          style="font-family: Arial;
                                          padding: 5px;
                                          vertical-align: middle;
                                          text-align: right;
                                          font-weight: bold;
                                          border: 1px solid #ddd;">
                                          <a style="font-family: Arial;
                                              font-size: 12px;
                                              cursor: pointer;
                                              text-decoration: none;
                                              font-weight: normal;
                                              /*color: #1d80ff;*/
                                              "><b>
                                              <?php
                                               $summaryQ[$resortname][$q_name]['TOTAL_REV'][] = array_sum($summary[$resortname][$month_each_q]['TOTAL_REV']);
                                               echo number_format(array_sum($summary[$resortname][$month_each_q]['TOTAL_REV']), 0, ".", ','); 
                                              ?></b>
                                          </a>
                                          </td>
                                        </tr>
                                        <!-- END SUMMARY EACH MONTH -->
                                      </thead>
                                  <?php
                                    // END LOOP MONTH PER Q
                                    }
                                  ?>
                                    <!-- START SUMMARY EACH Q -->
                                    <tr style="background: #ececec">
                                      <td colspan="2" 
                                      style="font-family: Arial;
                                      padding: 5px;
                                      vertical-align: middle;
                                      text-align: center;
                                      font-weight: bold;
                                      border: 1px solid #ddd;">
                                      <a style="font-family: Arial;
                                          font-size: 12px;
                                          cursor: pointer;
                                          text-decoration: none;
                                          font-weight: normal;
                                          /*color: #1d80ff;*/
                                          "><b>TOTAL <?= $q_name ?> <?= $resortname ?></b>
                                      </a>
                                      </td>
                                      <td
                                        style="font-family: Arial;
                                        padding: 5px;
                                        vertical-align: middle;
                                        text-align: right;
                                        font-weight: bold;
                                        border: 1px solid #ddd;">
                                        <a style="font-family: Arial;
                                          font-size: 12px;
                                          cursor: pointer;
                                          text-decoration: none;
                                          font-weight: normal;
                                          /*color: #1d80ff;*/
                                          "><b>
                                          <?php
                                           echo number_format(array_sum($summaryQ[$resortname][$q_name]['OCC']), 0, ".", ','); 
                                          ?></b>
                                        </a>
                                      </td>
                                      <td
                                        style="font-family: Arial;
                                        padding: 5px;
                                        vertical-align: middle;
                                        text-align: right;
                                        font-weight: bold;
                                        border: 1px solid #ddd;">
                                        <a style="font-family: Arial;
                                          font-size: 12px;
                                          cursor: pointer;
                                          text-decoration: none;
                                          font-weight: normal;
                                          /*color: #1d80ff;*/
                                          "><b>
                                          <?php
                                           if(array_filter($summaryQ[$resortname][$q_name]['OCC_PR'])){
                                              $total_q = array_sum($summaryQ[$resortname][$q_name]['OCC_PR']) / count(array_filter($summaryQ[$resortname][$q_name]['OCC_PR']));
                                              echo number_format($total_q, 2, ".", ',')."%";
                                           }
                                           else {
                                              echo number_format(0, 2, ".", ',')."%";
                                           }
                                          ?></b>
                                        </a>
                                      </td>
                                      <td
                                        style="font-family: Arial;
                                        padding: 5px;
                                        vertical-align: middle;
                                        text-align: right;
                                        font-weight: bold;
                                        border: 1px solid #ddd;">
                                        <a style="font-family: Arial;
                                          font-size: 12px;
                                          cursor: pointer;
                                          text-decoration: none;
                                          font-weight: normal;
                                          /*color: #1d80ff;*/
                                          "><b>
                                          <?php
                                           echo number_format(array_sum($summaryQ[$resortname][$q_name]['ROOM_REV']), 0, ".", ','); 
                                          ?></b>
                                        </a>
                                      </td>
                                      <td
                                        style="font-family: Arial;
                                        padding: 5px;
                                        vertical-align: middle;
                                        text-align: right;
                                        font-weight: bold;
                                        border: 1px solid #ddd;">
                                        <a style="font-family: Arial;
                                          font-size: 12px;
                                          cursor: pointer;
                                          text-decoration: none;
                                          font-weight: normal;
                                          /*color: #1d80ff;*/
                                          "><b>
                                          <?php
                                           echo number_format(array_sum($summaryQ[$resortname][$q_name]['FNB_REV']), 0, ".", ','); 
                                          ?></b>
                                        </a>
                                      </td>
                                      <td
                                        style="font-family: Arial;
                                        padding: 5px;
                                        vertical-align: middle;
                                        text-align: right;
                                        font-weight: bold;
                                        border: 1px solid #ddd;">
                                        <a style="font-family: Arial;
                                          font-size: 12px;
                                          cursor: pointer;
                                          text-decoration: none;
                                          font-weight: normal;
                                          /*color: #1d80ff;*/
                                          "><b>
                                          <?php
                                           echo number_format(array_sum($summaryQ[$resortname][$q_name]['OTH_REV']), 0, ".", ','); 
                                          ?></b>
                                        </a>
                                      </td>
                                      <td
                                        style="font-family: Arial;
                                        padding: 5px;
                                        vertical-align: middle;
                                        text-align: right;
                                        font-weight: bold;
                                        border: 1px solid #ddd;">
                                        <a style="font-family: Arial;
                                          font-size: 12px;
                                          cursor: pointer;
                                          text-decoration: none;
                                          font-weight: normal;
                                          /*color: #1d80ff;*/
                                          "><b>
                                          <?php
                                           echo number_format(array_sum($summaryQ[$resortname][$q_name]['TOTAL_REV']), 0, ".", ','); 
                                          ?></b>
                                        </a>
                                      </td>
                                    </tr>
                                    <!-- END SUMMARY EACH Q -->
                                  <?php
                                  // END LOOP RESORTNAME
                                  }
                                // END LOOP QUARTAL
                                $show_export = false;
                              }
                              ?>
                          </table>
                        </div>
                        <!-- END TABLE RESPONSIVE -->
                        <!-- YTD START -->
                        <div class="table-container-h table-responsive">
                          <table style="width:99%;
                            font-family:Arial;
                            font-size:12px;
                            border-collapse:collapse;
                            border-spacing:0;
                            background-color:#fff;
                            white-space: nowrap;
                            /*border: 1px solid #ddd;*/
                            margin: 0 1% 0 .5%;
                            border: 0" cellpadding="4">
                            <thead>
                              <tr>
                                <td colspan="19"><h2 style="margin: 1.5em 2px 5px;text-align: left;font-family:Arial; font-weight: bold;">YTD <?= $row_fetched[$comparison['reference_year']]['year']; ?></h2></td>
                                <!-- Only show export Once -->
                              </tr>
                            </thead>
                            <!-- START LOOP RESORT NAME -->
                            <?php
                            foreach($QUARTAL as $q_name => $month_name){ 
                              foreach ($row_fetched[$comparison['reference_year']]['hotel_data'] as $resortname => $resortrevenue) {
                                $show_q_once = true;
                            ?>
                                <thead>
                                  <tr class="text-center">
                                    <th colspan="25" class="bg-secondary text-white" style="border: 1px solid #ddd"><?= strtoupper($resortname); ?></th>
                                  </tr>
                                </thead>

                                <thead>
                                  <tr>
                                    <th style="border: 1px solid #ddd">Quarter</th>
                                    <th style="border: 1px solid #ddd">Room Class</th>
                                    <th style="border: 1px solid #ddd">Nights</th>
                                    <th style="border: 1px solid #ddd">% Occupancy</th>
                                    <th style="border: 1px solid #ddd">Room Revenue</th>
                                    <th style="border: 1px solid #ddd">F&B Revenue</th>
                                    <th style="border: 1px solid #ddd">Other Revenue</th>
                                    <th style="border: 1px solid #ddd">Total Revenue</th>
                                  </tr>

                                <?php foreach($resortrevenue['ROOM_DETAIL'] as $room_name => $data_month){ ?>
                                  <tr>
                                    <?php if($show_q_once){ ?>
                                    <td rowspan="<?php echo count($resortrevenue['ROOM_DETAIL']); ?>" 
                                    style="font-family: Arial;
                                    padding: 5px;
                                    vertical-align: middle;
                                    text-align: center;
                                    font-weight: bold;
                                    border: 1px solid #ddd;
                                    width: 70px">
                                    <a style="font-family: Arial;
                                        font-size: 12px;
                                        cursor: pointer;
                                        text-decoration: none;
                                        font-weight: normal;
                                        color: #000;
                                        "><?= $q_name ?>
                                    </a>
                                    </td>
                                    <?php } ?>
                                    <td
                                    style="font-family: Arial;
                                    padding: 5px;
                                    vertical-align: middle;
                                    text-align: left;
                                    font-weight: bold;
                                    border: 1px solid #ddd;
                                    width: 100px">
                                    <a style="font-family: Arial;
                                      font-size: 12px;
                                      cursor: pointer;
                                      text-decoration: none;
                                      font-weight: normal;
                                      color: #1d80ff;
                                      "><?= $room_name ?>
                                    </a>
                                    </td>
                                    <?php foreach($summaryQ[$resortname][$q_name][$room_name] as $key => $detail){ ?>
                                      <td
                                      style="font-family: Arial;
                                      padding: 5px;
                                      vertical-align: middle;
                                      text-align: right;
                                      font-weight: bold;
                                      border: 1px solid #ddd;">
                                      <a style="font-family: Arial;
                                          font-size: 12px;
                                          cursor: pointer;
                                          text-decoration: none;
                                          font-weight: normal;
                                          ">
                                          <?php if(trim(strtolower($key))=='occ_pr'){ ?>
                                            <?php
                                              if(array_filter($detail)) {
                                                $total_each_q = array_sum($detail) / count(array_filter($detail));
                                                echo number_format($total_each_q, 2, ".", ',')."%";
                                              }
                                              else {
                                                echo number_format(0, 0, ".", ',')."%";
                                              }
                                            ?>
                                          <?php } else { ?>
                                            <?php 
                                              echo number_format(array_sum($detail), 0, ".", ','); 
                                            ?>
                                          <?php }
                                          ?>
                                      </a>
                                      </td>
                                    <?php
                                    } ?>
                                  </tr>
                                <?php 
                                $show_q_once = false;
                                } ?>
                                </thead>

                                <!-- START SUMMARY EACH Q -->
                                <tr style="background: #ececec">
                                  <td colspan="2" 
                                  style="font-family: Arial;
                                  padding: 5px;
                                  vertical-align: middle;
                                  text-align: center;
                                  font-weight: bold;
                                  border: 1px solid #ddd;">
                                  <a style="font-family: Arial;
                                      font-size: 12px;
                                      cursor: pointer;
                                      text-decoration: none;
                                      font-weight: normal;
                                      /*color: #1d80ff;*/
                                      "><b>TOTAL <?= $q_name ?> <?= $resortname ?></b>
                                  </a>
                                  </td>
                                  <td
                                    style="font-family: Arial;
                                    padding: 5px;
                                    vertical-align: middle;
                                    text-align: right;
                                    font-weight: bold;
                                    border: 1px solid #ddd;">
                                    <a style="font-family: Arial;
                                      font-size: 12px;
                                      cursor: pointer;
                                      text-decoration: none;
                                      font-weight: normal;
                                      /*color: #1d80ff;*/
                                      "><b>
                                      <?php
                                       $summaryYTDQ['all_resort'][$q_name]['OCC'][] = array_sum($summaryQ[$resortname][$q_name]['OCC']);
                                       echo number_format(array_sum($summaryQ[$resortname][$q_name]['OCC']), 0, ".", ','); 
                                      ?></b>
                                    </a>
                                  </td>
                                  <td
                                    style="font-family: Arial;
                                    padding: 5px;
                                    vertical-align: middle;
                                    text-align: right;
                                    font-weight: bold;
                                    border: 1px solid #ddd;">
                                    <a style="font-family: Arial;
                                      font-size: 12px;
                                      cursor: pointer;
                                      text-decoration: none;
                                      font-weight: normal;
                                      /*color: #1d80ff;*/
                                      "><b>
                                      <?php
                                       if(array_filter($summaryQ[$resortname][$q_name]['OCC_PR'])){
                                          $total_q = array_sum($summaryQ[$resortname][$q_name]['OCC_PR']) / count(array_filter($summaryQ[$resortname][$q_name]['OCC_PR']));
                                          $summaryYTDQ['all_resort'][$q_name]['OCC_PR'][] = $total_q;
                                          echo number_format($total_q, 2, ".", ',')."%";
                                       }
                                       else {
                                          $summaryYTDQ['all_resort'][$q_name]['OCC_PR'][] = 0;
                                          echo number_format(0, 2, ".", ',')."%";
                                       }
                                      ?></b>
                                    </a>
                                  </td>
                                  <td
                                    style="font-family: Arial;
                                    padding: 5px;
                                    vertical-align: middle;
                                    text-align: right;
                                    font-weight: bold;
                                    border: 1px solid #ddd;">
                                    <a style="font-family: Arial;
                                      font-size: 12px;
                                      cursor: pointer;
                                      text-decoration: none;
                                      font-weight: normal;
                                      /*color: #1d80ff;*/
                                      "><b>
                                      <?php
                                       $summaryYTDQ['all_resort'][$q_name]['ROOM_REV'][] = array_sum($summaryQ[$resortname][$q_name]['ROOM_REV']);
                                       echo number_format(array_sum($summaryQ[$resortname][$q_name]['ROOM_REV']), 0, ".", ','); 
                                      ?></b>
                                    </a>
                                  </td>
                                  <td
                                    style="font-family: Arial;
                                    padding: 5px;
                                    vertical-align: middle;
                                    text-align: right;
                                    font-weight: bold;
                                    border: 1px solid #ddd;">
                                    <a style="font-family: Arial;
                                      font-size: 12px;
                                      cursor: pointer;
                                      text-decoration: none;
                                      font-weight: normal;
                                      /*color: #1d80ff;*/
                                      "><b>
                                      <?php
                                       $summaryYTDQ['all_resort'][$q_name]['FNB_REV'][] = array_sum($summaryQ[$resortname][$q_name]['FNB_REV']);
                                       echo number_format(array_sum($summaryQ[$resortname][$q_name]['FNB_REV']), 0, ".", ','); 
                                      ?></b>
                                    </a>
                                  </td>
                                  <td
                                    style="font-family: Arial;
                                    padding: 5px;
                                    vertical-align: middle;
                                    text-align: right;
                                    font-weight: bold;
                                    border: 1px solid #ddd;">
                                    <a style="font-family: Arial;
                                      font-size: 12px;
                                      cursor: pointer;
                                      text-decoration: none;
                                      font-weight: normal;
                                      /*color: #1d80ff;*/
                                      "><b>
                                      <?php
                                       $summaryYTDQ['all_resort'][$q_name]['OTH_REV'][] = array_sum($summaryQ[$resortname][$q_name]['OTH_REV']);
                                       echo number_format(array_sum($summaryQ[$resortname][$q_name]['OTH_REV']), 0, ".", ','); 
                                      ?></b>
                                    </a>
                                  </td>
                                  <td
                                    style="font-family: Arial;
                                    padding: 5px;
                                    vertical-align: middle;
                                    text-align: right;
                                    font-weight: bold;
                                    border: 1px solid #ddd;">
                                    <a style="font-family: Arial;
                                      font-size: 12px;
                                      cursor: pointer;
                                      text-decoration: none;
                                      font-weight: normal;
                                      /*color: #1d80ff;*/
                                      "><b>
                                      <?php
                                       $summaryYTDQ['all_resort'][$q_name]['TOTAL_REV'][] = array_sum($summaryQ[$resortname][$q_name]['TOTAL_REV']);
                                       echo number_format(array_sum($summaryQ[$resortname][$q_name]['TOTAL_REV']), 0, ".", ','); 
                                      ?></b>
                                    </a>
                                  </td>
                                </tr>
                                <!-- END SUMMARY EACH Q -->
                            <?php 
                            // END LOOP RESORTNAME
                            }
                            ?>
                            <!-- START SUMMARY QUARTAL ALL RESORT -->
                            <tr style="background: #ececec">
                              <td colspan="2" 
                                style="font-family: Arial;
                                padding: 5px;
                                vertical-align: middle;
                                text-align: center;
                                font-weight: bold;
                                border: 1px solid #ddd;">
                                <a style="font-family: Arial;
                                    font-size: 12px;
                                    cursor: pointer;
                                    text-decoration: none;
                                    font-weight: normal;
                                    /*color: #1d80ff;*/
                                    "><b>TOTAL <?= $q_name ?> All Resort</b>
                                </a>
                                </td>
                              <?php foreach($summaryYTDQ['all_resort'][$q_name] as $key => $detail){ ?>
                                <td
                                style="font-family: Arial;
                                padding: 5px;
                                vertical-align: middle;
                                text-align: right;
                                font-weight: bold;
                                border: 1px solid #ddd;">
                                <a style="font-family: Arial;
                                    font-size: 12px;
                                    cursor: pointer;
                                    text-decoration: none;
                                    font-weight: bold;
                                    ">
                                    <?php if(trim(strtolower($key))=='occ_pr'){ ?>
                                      <?php
                                        if(array_filter($detail)) {
                                          $total_occ_pr = array_sum($detail) / count(array_filter($detail));
                                          $summaryYTDAllResort['all_resort'][$key][] = $total_occ_pr;
                                          echo number_format($total_occ_pr, 2, ".", ',')."%";
                                        }
                                        else {
                                          $summaryYTDAllResort['all_resort'][$key][] = 0;
                                          echo number_format(0, 0, ".", ',')."%";
                                        }
                                      ?>
                                    <?php } else { ?>
                                      <?php
                                        $summaryYTDAllResort['all_resort'][$key][] = array_sum($detail);
                                        echo number_format(array_sum($detail), 0, ".", ','); 
                                      ?>
                                    <?php }
                                    ?>
                                </a>
                                </td>
                              <?php } ?>
                            </tr>
                            <!-- END SUMMARY ALL REPORT EACH QUARTAL -->
                          <?php
                          // END LOOP QUARTAL
                          }
                          ?>

                          <tr style="background: #ececec">
                              <td colspan="2" 
                                style="font-family: Arial;
                                padding: 5px;
                                vertical-align: middle;
                                text-align: center;
                                font-weight: bold;
                                border: 1px solid #ddd;">
                                <a style="font-family: Arial;
                                    font-size: 12px;
                                    cursor: pointer;
                                    text-decoration: none;
                                    font-weight: normal;
                                    /*color: #1d80ff;*/
                                    "><b>TOTAL YTD All Resort</b>
                                </a>
                                </td>
                              <?php foreach($summaryYTDAllResort['all_resort'] as $key => $detail){ ?>
                                <td
                                style="font-family: Arial;
                                padding: 5px;
                                vertical-align: middle;
                                text-align: right;
                                font-weight: bold;
                                border: 1px solid #ddd;">
                                <a style="font-family: Arial;
                                    font-size: 12px;
                                    cursor: pointer;
                                    text-decoration: none;
                                    font-weight: bold;
                                    ">
                                     <?php if(trim(strtolower($key))=='occ_pr'){ ?>
                                      <?php
                                        if(array_filter($detail)) {
                                          $total_occ_all_resort = array_sum($detail) / count(array_filter($detail));
                                          echo number_format($total_occ_all_resort, 2, ".", ',')."%";
                                        }
                                        else {
                                          echo number_format(0, 0, ".", ',')."%";
                                        }
                                      ?>
                                    <?php } else { ?>
                                      <?php 
                                        echo number_format(array_sum($detail), 0, ".", ','); 
                                      ?>
                                    <?php }
                                    ?>
                                </a>
                                </td>
                              <?php } ?>
                            </tr>
                          </table>
                        </div>
                      <!-- END TABLE WRAPPER FOR YTD AND QUARTAL TABLE -->
                      </div>
                      <!-- END REFERENCE YEAR -->


                      <!-- START TO COMPARE YEAR -->
                      <div class="tab-pane fade show" id="to-compare-year-{{ $comparison['to_compare_year'] }}" role="tabpanel">
                        <div class="table-container-h table-responsive">
                            <table style="width:99%;
                              font-family:Arial;
                              font-size:12px;
                              border-collapse:collapse;
                              border-spacing:0;
                              background-color:#fff;
                              white-space: nowrap;
                              /*border: 1px solid #ddd;*/
                              margin: 0 1% 0 .5%;
                              border: 0" cellpadding="4">
                              <?php
                              // VAR TO SUM HERE
                              foreach($QUARTAL as $q_name => $month_name){
                                ?>
                                  <thead>
                                    <tr>
                                      <td colspan="19" style="border: 0"><h2 style="margin: 1em 2px 5px;text-align: left;font-family:Arial; font-weight: bold;"><?= $q_name.' '.$row_fetched[$comparison['to_compare_year']]['year']; ?></h2></td>
                                    </tr>
                                  </thead>

                                  <?php
                                  // LOOP RESORT
                                  foreach ($row_fetched[$comparison['to_compare_year']]['hotel_data'] as $resortname => $resortrevenue) {
                                  $resort_code = explode('-', $resortname, 2);
                                  $resort_code = array_key_exists(0, $resort_code) ? $resort_code[0] : "Unknown";
                                  ?>
                                    <thead>
                                      <tr class="text-center">
                                        <th colspan="25" class="bg-secondary text-white" style="border: 1px solid #ddd"><?= strtoupper($resortname); ?></th>
                                      </tr>
                                    </thead>

                                    <thead>
                                      <!-- LOOP MONTH EACH QUARTAL -->
                                      <?php foreach($month_name as $month_each_q){
                                        $show_month_once = true;
                                      ?>
                                        <tr>
                                          <th style="border: 1px solid #ddd">Month</th>
                                          <th style="border: 1px solid #ddd">Room Class</th>
                                          <th style="border: 1px solid #ddd">Nights</th>
                                          <th style="border: 1px solid #ddd">% Occupancy</th>
                                          <th style="border: 1px solid #ddd">Room Revenue</th>
                                          <th style="border: 1px solid #ddd">F&B Revenue</th>
                                          <th style="border: 1px solid #ddd">Other Revenue</th>
                                          <th style="border: 1px solid #ddd">Total Revenue</th>
                                        </tr>
                                        <!-- BEGIN ROOM LOOPING -->
                                        <?php foreach($resortrevenue['ROOM_DETAIL'] as $room_name => $data_month) { ?>
                                          <tr>
                                            <!-- LOOP MONTH NAME ONLY ONCE -->
                                            <?php if($show_month_once){ ?>
                                            <td rowspan="<?php echo count($resortrevenue['ROOM_DETAIL']); ?>" 
                                            style="font-family: Arial;
                                            padding: 5px;
                                            vertical-align: middle;
                                            text-align: center;
                                            font-weight: bold;
                                            border: 1px solid #ddd;
                                            width: 70px">
                                            <a style="font-family: Arial;
                                                font-size: 12px;
                                                cursor: pointer;
                                                text-decoration: none;
                                                font-weight: normal;
                                                color: #000;
                                                "><?= $month_each_q ?>
                                            </a>
                                            </td>
                                            <?php } ?>
                                            <!-- END LOOP MONTH NAME ONLY ONCE -->
                                            <td
                                            style="font-family: Arial;
                                            padding: 5px;
                                            vertical-align: middle;
                                            text-align: left;
                                            font-weight: bold;
                                            border: 1px solid #ddd;
                                            width: 100px">
                                            <a style="font-family: Arial;
                                                font-size: 12px;
                                                cursor: pointer;
                                                text-decoration: none;
                                                font-weight: normal;
                                                color: #1d80ff;
                                                "><?= $room_name ?>
                                            </a>
                                            </td>
                                            <!-- START LOOP FIELD EACH ROOM -->
                                            <?php foreach($data_month[$month_each_q] as $key => $detail){ ?>
                                              <td
                                              style="font-family: Arial;
                                              padding: 5px;
                                              vertical-align: middle;
                                              text-align: right;
                                              font-weight: bold;
                                              border: 1px solid #ddd;">
                                              <a style="font-family: Arial;
                                                  font-size: 12px;
                                                  cursor: pointer;
                                                  text-decoration: none;
                                                  font-weight: normal;
                                                  ">
                                                  <?php if(trim(strtolower($key))=='occ_pr'){ ?>
                                                    <?php
                                                      echo number_format($detail, 2, ".", ',')."%"; 
                                                    ?>
                                                  <?php } else { ?>
                                                    <?php 
                                                      echo number_format($detail, 0, ".", ','); 
                                                    ?>
                                                  <?php }
                                                  ?>
                                              </a>
                                              </td>
                                            <?php
                                            // STORE VALUE EACH MONTH EACH CATEGORY FIELD
                                            $summary[$resortname][$month_each_q][$key][] = $detail;
                                            // Store summary for quartal
                                            $summaryQ[$resortname][$q_name][$room_name][$key][] = $detail;
                                            } ?>
                                            <!-- END LOOP FIELD EACH HROOM -->
                                            </tr>
                                        <?php 
                                        $show_month_once = false;
                                        } ?>
                                        <!-- END ROOM LOOPING -->
                                        <!-- START SUMMARY EACH MONTH -->
                                        <tr style="background: #ececec">
                                          <td colspan="2" 
                                          style="font-family: Arial;
                                          padding: 5px;
                                          vertical-align: middle;
                                          text-align: center;
                                          font-weight: bold;
                                          border: 1px solid #ddd;">
                                          <a style="font-family: Arial;
                                              font-size: 12px;
                                              cursor: pointer;
                                              text-decoration: none;
                                              font-weight: normal;
                                              /*color: #1d80ff;*/
                                              "><b>TOTAL <?= $month_each_q ?></b>
                                          </a>
                                          </td>
                                          <td
                                          style="font-family: Arial;
                                          padding: 5px;
                                          vertical-align: middle;
                                          text-align: right;
                                          font-weight: bold;
                                          border: 1px solid #ddd;">
                                          <a style="font-family: Arial;
                                              font-size: 12px;
                                              cursor: pointer;
                                              text-decoration: none;
                                              font-weight: normal;
                                              /*color: #1d80ff;*/
                                              "><b>
                                              <?php
                                               $summaryQ[$resortname][$q_name]['OCC'][] = array_sum($summary[$resortname][$month_each_q]['OCC']);
                                               echo number_format(array_sum($summary[$resortname][$month_each_q]['OCC']), 0, ".", ','); 
                                              ?></b>
                                          </a>
                                          </td>
                                          <td
                                          style="font-family: Arial;
                                          padding: 5px;
                                          vertical-align: middle;
                                          text-align: right;
                                          font-weight: bold;
                                          border: 1px solid #ddd;">
                                          <a style="font-family: Arial;
                                              font-size: 12px;
                                              cursor: pointer;
                                              text-decoration: none;
                                              font-weight: normal;"><b>
                                              <?php
                                               if(array_filter($summary[$resortname][$month_each_q]['OCC_PR'])){
                                                  $total = array_sum($summary[$resortname][$month_each_q]['OCC_PR']) / count(array_filter($summary[$resortname][$month_each_q]['OCC_PR']));
                                                  $summaryQ[$resortname][$q_name]['OCC_PR'][] = $total;
                                                  echo number_format($total, 2, ".", ',')."%";
                                               } else {
                                                  $summaryQ[$resortname][$q_name]['OCC_PR'][] = 0;
                                                  echo number_format(0, 2, ".", ',')."%";
                                               }
                                              ?></b>
                                          </a>
                                          </td>
                                          <td
                                          style="font-family: Arial;
                                          padding: 5px;
                                          vertical-align: middle;
                                          text-align: right;
                                          font-weight: bold;
                                          border: 1px solid #ddd;">
                                          <a style="font-family: Arial;
                                              font-size: 12px;
                                              cursor: pointer;
                                              text-decoration: none;
                                              font-weight: normal;
                                              /*color: #1d80ff;*/
                                              "><b>
                                              <?php
                                               $summaryQ[$resortname][$q_name]['ROOM_REV'][] = array_sum($summary[$resortname][$month_each_q]['ROOM_REV']);
                                               echo number_format(array_sum($summary[$resortname][$month_each_q]['ROOM_REV']), 0, ".", ','); 
                                              ?></b>
                                          </a>
                                          </td>
                                          <td
                                          style="font-family: Arial;
                                          padding: 5px;
                                          vertical-align: middle;
                                          text-align: right;
                                          font-weight: bold;
                                          border: 1px solid #ddd;">
                                          <a style="font-family: Arial;
                                              font-size: 12px;
                                              cursor: pointer;
                                              text-decoration: none;
                                              font-weight: normal;
                                              /*color: #1d80ff;*/
                                              "><b>
                                              <?php
                                               $summaryQ[$resortname][$q_name]['FNB_REV'][] = array_sum($summary[$resortname][$month_each_q]['FNB_REV']);
                                               echo number_format(array_sum($summary[$resortname][$month_each_q]['FNB_REV']), 0, ".", ','); 
                                              ?></b>
                                          </a>
                                          </td>
                                          <td
                                          style="font-family: Arial;
                                          padding: 5px;
                                          vertical-align: middle;
                                          text-align: right;
                                          font-weight: bold;
                                          border: 1px solid #ddd;">
                                          <a style="font-family: Arial;
                                              font-size: 12px;
                                              cursor: pointer;
                                              text-decoration: none;
                                              font-weight: normal;
                                              /*color: #1d80ff;*/
                                              "><b>
                                              <?php
                                               $summaryQ[$resortname][$q_name]['OTH_REV'][] = array_sum($summary[$resortname][$month_each_q]['OTH_REV']);
                                               echo number_format(array_sum($summary[$resortname][$month_each_q]['OTH_REV']), 0, ".", ','); 
                                              ?></b>
                                          </a>
                                          </td>
                                          <td
                                          style="font-family: Arial;
                                          padding: 5px;
                                          vertical-align: middle;
                                          text-align: right;
                                          font-weight: bold;
                                          border: 1px solid #ddd;">
                                          <a style="font-family: Arial;
                                              font-size: 12px;
                                              cursor: pointer;
                                              text-decoration: none;
                                              font-weight: normal;
                                              /*color: #1d80ff;*/
                                              "><b>
                                              <?php
                                               $summaryQ[$resortname][$q_name]['TOTAL_REV'][] = array_sum($summary[$resortname][$month_each_q]['TOTAL_REV']);
                                               echo number_format(array_sum($summary[$resortname][$month_each_q]['TOTAL_REV']), 0, ".", ','); 
                                              ?></b>
                                          </a>
                                          </td>
                                        </tr>
                                        <!-- END SUMMARY EACH MONTH -->
                                      </thead>
                                  <?php
                                    // END LOOP MONTH PER Q
                                    }
                                  ?>
                                    <!-- START SUMMARY EACH Q -->
                                    <tr style="background: #ececec">
                                      <td colspan="2" 
                                      style="font-family: Arial;
                                      padding: 5px;
                                      vertical-align: middle;
                                      text-align: center;
                                      font-weight: bold;
                                      border: 1px solid #ddd;">
                                      <a style="font-family: Arial;
                                          font-size: 12px;
                                          cursor: pointer;
                                          text-decoration: none;
                                          font-weight: normal;
                                          /*color: #1d80ff;*/
                                          "><b>TOTAL <?= $q_name ?> <?= $resortname ?></b>
                                      </a>
                                      </td>
                                      <td
                                        style="font-family: Arial;
                                        padding: 5px;
                                        vertical-align: middle;
                                        text-align: right;
                                        font-weight: bold;
                                        border: 1px solid #ddd;">
                                        <a style="font-family: Arial;
                                          font-size: 12px;
                                          cursor: pointer;
                                          text-decoration: none;
                                          font-weight: normal;
                                          /*color: #1d80ff;*/
                                          "><b>
                                          <?php
                                           echo number_format(array_sum($summaryQ[$resortname][$q_name]['OCC']), 0, ".", ','); 
                                          ?></b>
                                        </a>
                                      </td>
                                      <td
                                        style="font-family: Arial;
                                        padding: 5px;
                                        vertical-align: middle;
                                        text-align: right;
                                        font-weight: bold;
                                        border: 1px solid #ddd;">
                                        <a style="font-family: Arial;
                                          font-size: 12px;
                                          cursor: pointer;
                                          text-decoration: none;
                                          font-weight: normal;
                                          /*color: #1d80ff;*/
                                          "><b>
                                          <?php
                                           if(array_filter($summaryQ[$resortname][$q_name]['OCC_PR'])){
                                              $total_q = array_sum($summaryQ[$resortname][$q_name]['OCC_PR']) / count(array_filter($summaryQ[$resortname][$q_name]['OCC_PR']));
                                              echo number_format($total_q, 2, ".", ',')."%";
                                           }
                                           else {
                                              echo number_format(0, 2, ".", ',')."%";
                                           }
                                          ?></b>
                                        </a>
                                      </td>
                                      <td
                                        style="font-family: Arial;
                                        padding: 5px;
                                        vertical-align: middle;
                                        text-align: right;
                                        font-weight: bold;
                                        border: 1px solid #ddd;">
                                        <a style="font-family: Arial;
                                          font-size: 12px;
                                          cursor: pointer;
                                          text-decoration: none;
                                          font-weight: normal;
                                          /*color: #1d80ff;*/
                                          "><b>
                                          <?php
                                           echo number_format(array_sum($summaryQ[$resortname][$q_name]['ROOM_REV']), 0, ".", ','); 
                                          ?></b>
                                        </a>
                                      </td>
                                      <td
                                        style="font-family: Arial;
                                        padding: 5px;
                                        vertical-align: middle;
                                        text-align: right;
                                        font-weight: bold;
                                        border: 1px solid #ddd;">
                                        <a style="font-family: Arial;
                                          font-size: 12px;
                                          cursor: pointer;
                                          text-decoration: none;
                                          font-weight: normal;
                                          /*color: #1d80ff;*/
                                          "><b>
                                          <?php
                                           echo number_format(array_sum($summaryQ[$resortname][$q_name]['FNB_REV']), 0, ".", ','); 
                                          ?></b>
                                        </a>
                                      </td>
                                      <td
                                        style="font-family: Arial;
                                        padding: 5px;
                                        vertical-align: middle;
                                        text-align: right;
                                        font-weight: bold;
                                        border: 1px solid #ddd;">
                                        <a style="font-family: Arial;
                                          font-size: 12px;
                                          cursor: pointer;
                                          text-decoration: none;
                                          font-weight: normal;
                                          /*color: #1d80ff;*/
                                          "><b>
                                          <?php
                                           echo number_format(array_sum($summaryQ[$resortname][$q_name]['OTH_REV']), 0, ".", ','); 
                                          ?></b>
                                        </a>
                                      </td>
                                      <td
                                        style="font-family: Arial;
                                        padding: 5px;
                                        vertical-align: middle;
                                        text-align: right;
                                        font-weight: bold;
                                        border: 1px solid #ddd;">
                                        <a style="font-family: Arial;
                                          font-size: 12px;
                                          cursor: pointer;
                                          text-decoration: none;
                                          font-weight: normal;
                                          /*color: #1d80ff;*/
                                          "><b>
                                          <?php
                                           echo number_format(array_sum($summaryQ[$resortname][$q_name]['TOTAL_REV']), 0, ".", ','); 
                                          ?></b>
                                        </a>
                                      </td>
                                    </tr>
                                    <!-- END SUMMARY EACH Q -->
                                  <?php
                                  // END LOOP RESORTNAME
                                  }
                                // END LOOP QUARTAL
                                $show_export = false;
                              }
                              ?>
                          </table>
                        </div>
                        <!-- END TABLE RESPONSIVE -->
                        <!-- YTD START -->
                        <div class="table-container-h table-responsive">
                          <table style="width:99%;
                            font-family:Arial;
                            font-size:12px;
                            border-collapse:collapse;
                            border-spacing:0;
                            background-color:#fff;
                            white-space: nowrap;
                            /*border: 1px solid #ddd;*/
                            margin: 0 1% 0 .5%;
                            border: 0" cellpadding="4">
                            <thead>
                              <tr>
                                <td colspan="19"><h2 style="margin: 1.5em 2px 5px;text-align: left;font-family:Arial; font-weight: bold;">YTD <?= $row_fetched[$comparison['to_compare_year']]['year']; ?></h2></td>
                                <!-- Only show export Once -->
                              </tr>
                            </thead>
                            <!-- START LOOP RESORT NAME -->
                            <?php
                            foreach($QUARTAL as $q_name => $month_name){ 
                              foreach ($row_fetched[$comparison['to_compare_year']]['hotel_data'] as $resortname => $resortrevenue) {
                                $show_q_once = true;
                            ?>
                                <thead>
                                  <tr class="text-center">
                                    <th colspan="25" class="bg-secondary text-white" style="border: 1px solid #ddd"><?= strtoupper($resortname); ?></th>
                                  </tr>
                                </thead>

                                <thead>
                                  <tr>
                                    <th style="border: 1px solid #ddd">Quarter</th>
                                    <th style="border: 1px solid #ddd">Room Class</th>
                                    <th style="border: 1px solid #ddd">Nights</th>
                                    <th style="border: 1px solid #ddd">% Occupancy</th>
                                    <th style="border: 1px solid #ddd">Room Revenue</th>
                                    <th style="border: 1px solid #ddd">F&B Revenue</th>
                                    <th style="border: 1px solid #ddd">Other Revenue</th>
                                    <th style="border: 1px solid #ddd">Total Revenue</th>
                                  </tr>

                                <?php foreach($resortrevenue['ROOM_DETAIL'] as $room_name => $data_month){ ?>
                                  <tr>
                                    <?php if($show_q_once){ ?>
                                    <td rowspan="<?php echo count($resortrevenue['ROOM_DETAIL']); ?>" 
                                    style="font-family: Arial;
                                    padding: 5px;
                                    vertical-align: middle;
                                    text-align: center;
                                    font-weight: bold;
                                    border: 1px solid #ddd;
                                    width: 70px">
                                    <a style="font-family: Arial;
                                        font-size: 12px;
                                        cursor: pointer;
                                        text-decoration: none;
                                        font-weight: normal;
                                        color: #000;
                                        "><?= $q_name ?>
                                    </a>
                                    </td>
                                    <?php } ?>
                                    <td
                                    style="font-family: Arial;
                                    padding: 5px;
                                    vertical-align: middle;
                                    text-align: left;
                                    font-weight: bold;
                                    border: 1px solid #ddd;
                                    width: 100px">
                                    <a style="font-family: Arial;
                                      font-size: 12px;
                                      cursor: pointer;
                                      text-decoration: none;
                                      font-weight: normal;
                                      color: #1d80ff;
                                      "><?= $room_name ?>
                                    </a>
                                    </td>
                                    <?php foreach($summaryQ[$resortname][$q_name][$room_name] as $key => $detail){ ?>
                                      <td
                                      style="font-family: Arial;
                                      padding: 5px;
                                      vertical-align: middle;
                                      text-align: right;
                                      font-weight: bold;
                                      border: 1px solid #ddd;">
                                      <a style="font-family: Arial;
                                          font-size: 12px;
                                          cursor: pointer;
                                          text-decoration: none;
                                          font-weight: normal;
                                          ">
                                          <?php if(trim(strtolower($key))=='occ_pr'){ ?>
                                            <?php
                                              if(array_filter($detail)) {
                                                $total_each_q = array_sum($detail) / count(array_filter($detail));
                                                echo number_format($total_each_q, 2, ".", ',')."%";
                                              }
                                              else {
                                                echo number_format(0, 0, ".", ',')."%";
                                              }
                                            ?>
                                          <?php } else { ?>
                                            <?php 
                                              echo number_format(array_sum($detail), 0, ".", ','); 
                                            ?>
                                          <?php }
                                          ?>
                                      </a>
                                      </td>
                                    <?php
                                    } ?>
                                  </tr>
                                <?php 
                                $show_q_once = false;
                                } ?>
                                </thead>

                                <!-- START SUMMARY EACH Q -->
                                <tr style="background: #ececec">
                                  <td colspan="2" 
                                  style="font-family: Arial;
                                  padding: 5px;
                                  vertical-align: middle;
                                  text-align: center;
                                  font-weight: bold;
                                  border: 1px solid #ddd;">
                                  <a style="font-family: Arial;
                                      font-size: 12px;
                                      cursor: pointer;
                                      text-decoration: none;
                                      font-weight: normal;
                                      /*color: #1d80ff;*/
                                      "><b>TOTAL <?= $q_name ?> <?= $resortname ?></b>
                                  </a>
                                  </td>
                                  <td
                                    style="font-family: Arial;
                                    padding: 5px;
                                    vertical-align: middle;
                                    text-align: right;
                                    font-weight: bold;
                                    border: 1px solid #ddd;">
                                    <a style="font-family: Arial;
                                      font-size: 12px;
                                      cursor: pointer;
                                      text-decoration: none;
                                      font-weight: normal;
                                      /*color: #1d80ff;*/
                                      "><b>
                                      <?php
                                       $summaryYTDQ['all_resort'][$q_name]['OCC'][] = array_sum($summaryQ[$resortname][$q_name]['OCC']);
                                       echo number_format(array_sum($summaryQ[$resortname][$q_name]['OCC']), 0, ".", ','); 
                                      ?></b>
                                    </a>
                                  </td>
                                  <td
                                    style="font-family: Arial;
                                    padding: 5px;
                                    vertical-align: middle;
                                    text-align: right;
                                    font-weight: bold;
                                    border: 1px solid #ddd;">
                                    <a style="font-family: Arial;
                                      font-size: 12px;
                                      cursor: pointer;
                                      text-decoration: none;
                                      font-weight: normal;
                                      /*color: #1d80ff;*/
                                      "><b>
                                      <?php
                                       if(array_filter($summaryQ[$resortname][$q_name]['OCC_PR'])){
                                          $total_q = array_sum($summaryQ[$resortname][$q_name]['OCC_PR']) / count(array_filter($summaryQ[$resortname][$q_name]['OCC_PR']));
                                          $summaryYTDQ['all_resort'][$q_name]['OCC_PR'][] = $total_q;
                                          echo number_format($total_q, 2, ".", ',')."%";
                                       }
                                       else {
                                          $summaryYTDQ['all_resort'][$q_name]['OCC_PR'][] = 0;
                                          echo number_format(0, 2, ".", ',')."%";
                                       }
                                      ?></b>
                                    </a>
                                  </td>
                                  <td
                                    style="font-family: Arial;
                                    padding: 5px;
                                    vertical-align: middle;
                                    text-align: right;
                                    font-weight: bold;
                                    border: 1px solid #ddd;">
                                    <a style="font-family: Arial;
                                      font-size: 12px;
                                      cursor: pointer;
                                      text-decoration: none;
                                      font-weight: normal;
                                      /*color: #1d80ff;*/
                                      "><b>
                                      <?php
                                       $summaryYTDQ['all_resort'][$q_name]['ROOM_REV'][] = array_sum($summaryQ[$resortname][$q_name]['ROOM_REV']);
                                       echo number_format(array_sum($summaryQ[$resortname][$q_name]['ROOM_REV']), 0, ".", ','); 
                                      ?></b>
                                    </a>
                                  </td>
                                  <td
                                    style="font-family: Arial;
                                    padding: 5px;
                                    vertical-align: middle;
                                    text-align: right;
                                    font-weight: bold;
                                    border: 1px solid #ddd;">
                                    <a style="font-family: Arial;
                                      font-size: 12px;
                                      cursor: pointer;
                                      text-decoration: none;
                                      font-weight: normal;
                                      /*color: #1d80ff;*/
                                      "><b>
                                      <?php
                                       $summaryYTDQ['all_resort'][$q_name]['FNB_REV'][] = array_sum($summaryQ[$resortname][$q_name]['FNB_REV']);
                                       echo number_format(array_sum($summaryQ[$resortname][$q_name]['FNB_REV']), 0, ".", ','); 
                                      ?></b>
                                    </a>
                                  </td>
                                  <td
                                    style="font-family: Arial;
                                    padding: 5px;
                                    vertical-align: middle;
                                    text-align: right;
                                    font-weight: bold;
                                    border: 1px solid #ddd;">
                                    <a style="font-family: Arial;
                                      font-size: 12px;
                                      cursor: pointer;
                                      text-decoration: none;
                                      font-weight: normal;
                                      /*color: #1d80ff;*/
                                      "><b>
                                      <?php
                                       $summaryYTDQ['all_resort'][$q_name]['OTH_REV'][] = array_sum($summaryQ[$resortname][$q_name]['OTH_REV']);
                                       echo number_format(array_sum($summaryQ[$resortname][$q_name]['OTH_REV']), 0, ".", ','); 
                                      ?></b>
                                    </a>
                                  </td>
                                  <td
                                    style="font-family: Arial;
                                    padding: 5px;
                                    vertical-align: middle;
                                    text-align: right;
                                    font-weight: bold;
                                    border: 1px solid #ddd;">
                                    <a style="font-family: Arial;
                                      font-size: 12px;
                                      cursor: pointer;
                                      text-decoration: none;
                                      font-weight: normal;
                                      /*color: #1d80ff;*/
                                      "><b>
                                      <?php
                                       $summaryYTDQ['all_resort'][$q_name]['TOTAL_REV'][] = array_sum($summaryQ[$resortname][$q_name]['TOTAL_REV']);
                                       echo number_format(array_sum($summaryQ[$resortname][$q_name]['TOTAL_REV']), 0, ".", ','); 
                                      ?></b>
                                    </a>
                                  </td>
                                </tr>
                                <!-- END SUMMARY EACH Q -->
                            <?php 
                            // END LOOP RESORTNAME
                            }
                            ?>
                            <!-- START SUMMARY QUARTAL ALL RESORT -->
                            <tr style="background: #ececec">
                              <td colspan="2" 
                                style="font-family: Arial;
                                padding: 5px;
                                vertical-align: middle;
                                text-align: center;
                                font-weight: bold;
                                border: 1px solid #ddd;">
                                <a style="font-family: Arial;
                                    font-size: 12px;
                                    cursor: pointer;
                                    text-decoration: none;
                                    font-weight: normal;
                                    /*color: #1d80ff;*/
                                    "><b>TOTAL <?= $q_name ?> All Resort</b>
                                </a>
                                </td>
                              <?php foreach($summaryYTDQ['all_resort'][$q_name] as $key => $detail){ ?>
                                <td
                                style="font-family: Arial;
                                padding: 5px;
                                vertical-align: middle;
                                text-align: right;
                                font-weight: bold;
                                border: 1px solid #ddd;">
                                <a style="font-family: Arial;
                                    font-size: 12px;
                                    cursor: pointer;
                                    text-decoration: none;
                                    font-weight: bold;
                                    ">
                                    <?php if(trim(strtolower($key))=='occ_pr'){ ?>
                                      <?php
                                        if(array_filter($detail)) {
                                          $total_occ_pr = array_sum($detail) / count(array_filter($detail));
                                          $summaryYTDAllResort['all_resort'][$key][] = $total_occ_pr;
                                          echo number_format($total_occ_pr, 2, ".", ',')."%";
                                        }
                                        else {
                                          $summaryYTDAllResort['all_resort'][$key][] = 0;
                                          echo number_format(0, 0, ".", ',')."%";
                                        }
                                      ?>
                                    <?php } else { ?>
                                      <?php
                                        $summaryYTDAllResort['all_resort'][$key][] = array_sum($detail);
                                        echo number_format(array_sum($detail), 0, ".", ','); 
                                      ?>
                                    <?php }
                                    ?>
                                </a>
                                </td>
                              <?php } ?>
                            </tr>
                            <!-- END SUMMARY ALL REPORT EACH QUARTAL -->
                          <?php
                          // END LOOP QUARTAL
                          }
                          ?>

                          <tr style="background: #ececec">
                              <td colspan="2" 
                                style="font-family: Arial;
                                padding: 5px;
                                vertical-align: middle;
                                text-align: center;
                                font-weight: bold;
                                border: 1px solid #ddd;">
                                <a style="font-family: Arial;
                                    font-size: 12px;
                                    cursor: pointer;
                                    text-decoration: none;
                                    font-weight: normal;
                                    /*color: #1d80ff;*/
                                    "><b>TOTAL YTD All Resort</b>
                                </a>
                                </td>
                              <?php foreach($summaryYTDAllResort['all_resort'] as $key => $detail){ ?>
                                <td
                                style="font-family: Arial;
                                padding: 5px;
                                vertical-align: middle;
                                text-align: right;
                                font-weight: bold;
                                border: 1px solid #ddd;">
                                <a style="font-family: Arial;
                                    font-size: 12px;
                                    cursor: pointer;
                                    text-decoration: none;
                                    font-weight: bold;
                                    ">
                                     <?php if(trim(strtolower($key))=='occ_pr'){ ?>
                                      <?php
                                        if(array_filter($detail)) {
                                          $total_occ_all_resort = array_sum($detail) / count(array_filter($detail));
                                          echo number_format($total_occ_all_resort, 2, ".", ',')."%";
                                        }
                                        else {
                                          echo number_format(0, 0, ".", ',')."%";
                                        }
                                      ?>
                                    <?php } else { ?>
                                      <?php 
                                        echo number_format(array_sum($detail), 0, ".", ','); 
                                      ?>
                                    <?php }
                                    ?>
                                </a>
                                </td>
                              <?php } ?>
                            </tr>
                          </table>
                        </div>
                      <!-- END TABLE WRAPPER FOR YTD AND QUARTAL TABLE -->
                      <!-- END TO COMPARE YEAR -->
                      </div>
                    <!-- END REVENUE COMPARISON -->
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
  <script type="text/javascript" src="/js/app/report/jquery.floatingscroll.js"></script>
  <script>
    console.log('invoked custom scripts');
    $(".main-wrapper").floatingScroll();

    $(function(){

      // When the user scrolls the page, execute myFunction
      window.onscroll = function() {myFunction()}
      // Get the header
      var header = document.getElementById("main-header");
      var header_sticky = document.getElementById('header');
      // Get the offset position of the navbar
      var sticky = header.offsetTop - 60;
      var header_width = (header.offsetWidth - 12)+"px" || 'auto';
      // Add the sticky class to the header when you reach its scroll position. Remove "sticky" when you leave the scroll position

      function myFunction() {
        if (window.pageYOffset >= sticky) {
          header_sticky.classList.add("sticky");
          var header_change_width = document.getElementById("main-header").offsetWidth;
          header_change_width = (header_change_width - 12)+"px" || 'auto';
          header_sticky.style.width = header_change_width;
        } else {
          header_sticky.classList.remove("sticky");
          header_sticky.style.width = 'auto';
        }
      }

      $(window).resize(function(){
        var header_change_width = document.getElementById("main-header").offsetWidth;
        header_change_width = (header_change_width- 12)+"px" || 'auto';
        header_sticky.style.width = header_change_width;
      });
    });
  </script>
@endsection