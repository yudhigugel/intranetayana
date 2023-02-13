<form id="modalDetailAjax" data-loader-file="/image/gif/cube.gif">
    <div class="form-group">
        <input type="hidden" name="id" id="form_id" value="{{$data['data_form'][0]['REIMBURSEMENT_ID']}}">
        <div class="row mb-3">
            <div class="col-md-6">
                <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;">Process Reimbursement</h3>
                <div>
                    @if ($data['boleh_pay']==true)

                        <button type="button" onclick="payReimbursement();" class="btn btn-lg btn-success text-white">PAY</button>
                        <button type="button" onclick="cancelReimbursement();" class="btn btn-lg btn-danger">CANCEL</button>
                    @else
                    <div class="alert alert-warning" role="alert" id="parked_alert" >
                        <h4 class="alert-heading">Information!</h4>
                        <p><b>You cannot pay this reimbursement before posting all included cash advance into SAP Cash Receipts</b></p>
                        <p>Please open this reimbursement's CA No. to make posting </p>
                    </div>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;">Edit Reimbursement</h3>
                <div class="form-group">
                    <label>O/S</label>
                    <input type="text" name="os" id="form_os" class="form-control" placeholder="insert O/S..." value="{{ $data['data_form'][0]['OS'] }}">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" id="form_desc" class="form-control" placeholder="insert description...">{{ $data['data_form'][0]['REIMBURSE_DESC'] }}</textarea>
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-success text-white float-left" id="saveEdit" onclick="editReimbursement();">SAVE</button>
                    <img src="{{ asset('image/loader.gif') }}" alt="Report Loader" style="width:30px;display:table;float:left;margin-left:10px;display:none;" id="loader">
                    <div class="clearfix" style="margin-bottom:10px;"></div>
                    <div class="alert alert-success col-md-12" id="successUpdateReimburse" role="alert" style="display: none;">
                        Success update reimbursement
                    </div>
                    <div class="alert alert-danger col-md-12" id="failedUpdateReimburse" role="alert" style="display: none;">
                        Failed to update reimbursement
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;">Items </h3>
        <div class="mb-3" style="overflow-x:auto;">
        <table class="table table-bordered table-striped datatable approvalList" id="approvalList" style="white-space: nowrap;">
            <thead>
                <tr>
                    <th>Reimburse No.</th>
                    <th>CA No.</th>
                    <th>O/S</th>
                    <th>DHBR Date</th>
                    <th>Reimburse Desc.</th>
                    <th>Description</th>
                    <th>Amount</th>
                    <th>Currency</th>
                    <th>Reimburse Date</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total=0;
                @endphp
                @foreach ($data['data_form'] as $data_form)
                @php
                    $total=$total+ $data_form['CA_ITEM_AMOUNT'];
                @endphp
                    <tr>
                        <td>{{ $data_form['REIMBURSEMENT_ID']}}</td>
                        <td>{{ $data_form['FORM_ID'] }}</td>
                        <td>{{ empty($data_form['OS']) ? '-' : $data_form['OS'] }}</td>
                        <td>{{ date('d/m/Y',strtotime($data_form['REIMBURSE_INSERT_DATE'])) }}</td>
                        <td>{{ empty($data_form['REIMBURSE_DESC']) ? '-' : $data_form['REIMBURSE_DESC'] }}</td>
                        <td>{{ $data_form['CA_ITEM_DESC'] }}</td>
                        <td>{{ $data_form['CA_ITEM_AMOUNT_PARSE'] }}</td>
                        <td>{{ $data_form['CA_CURRENCY'] }}</td>
                        <td>{{ date('d/m/Y',strtotime($data_form['REIMBURSE_INSERT_DATE'])) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" class="text-right"><h3>TOTAL</h3></td>
                    <td colspan="3" class="text-left"><h3>{{ number_format($total)}}</h3></td>
                </tr>
            </tfoot>
        </table>
        </div>
    </div>
</form>
<script>

    function editReimbursement(){
        var desc = $("#form_desc").val();
        var os = $("#form_os").val();
        var id= $("#form_id").val();

        var data={
            'desc' : desc,
            'os' : os,
            'id' : id
        }
        $.ajax({
            url : '/finance/cash-advance-gc/update-reimbursement',
            type : 'POST',
            data : data,
            beforeSend : function (){
                $("#loader").show();
                $("#form_desc").attr('readonly','');
                $("#form_os").attr('readonly','');
                $("#form_id").attr('readonly','');
                $("#saveEdit").attr('disabled','');
            },
            success : function(response){
                $("#loader").hide();
                $("#form_desc").removeAttr('readonly');
                $("#form_os").removeAttr('readonly');
                $("#form_id").removeAttr('readonly');
                $("#saveEdit").removeAttr('disabled');
                if(response){
                    $("#successUpdateReimburse").fadeTo(2000, 500).slideUp(500, function(){
                        $("#successUpdateReimburse").slideUp(500);
                    });
                }else{
                    $("#failedUpdateReimburse").fadeTo(2000, 500).slideUp(500, function(){
                        $("#failedUpdateReimburse").slideUp(500);
                    });
                }
            },
            error : function(xhr){
                // Swal.fire('Oops..', 'Something went wrong, please try again', 'error');

            }
        });
    }

    function cancelReimbursement(){

        var id= $("#form_id").val();

        Swal.fire({
            title: 'Are you sure?',
            text: "You are going to Cancel this reimbursement",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Continue'
            }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type:"POST",
                    url: "/finance/cash-advance-gc/cancel-reimbursement",
                    data: { id : id },
                    success: function(data) {
                        if(data.hasOwnProperty('status') && data.status=="success"){
                            Swal.fire({
                                title :'Reimbursement Canceled',
                                text: "Reimbursement Canceled Successfully",
                                icon : 'success',
                            }).then((result)=>{
                                location.reload();
                            });
                        }else{
                            Swal.fire('Oops..', 'Failed to Cancel Reimbursement, please contact administrator', 'error');
                        }
                    }
                });
            }
})

    }

    function payReimbursement(){
        var id= $("#form_id").val();

        Swal.fire({
            title: 'Are you sure?',
            text: "You are going to Pay this reimbursement",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Pay'
            }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type:"POST",
                    url: "/finance/cash-advance-gc/pay-reimbursement",
                    data: { id : id },
                    success: function(data) {
                        if(data.hasOwnProperty('status') && data.status=="success"){
                            Swal.fire({
                                title :'Reimbursement Paid',
                                text: "Reimbursement Paid Successfully",
                                icon : 'success',
                            }).then((result)=>{
                                location.reload();
                            });
                        }else{
                            Swal.fire('Oops..', 'Failed to Pay Reimbursement, please contact administrator', 'error');
                        }
                    }
                });
            }
        })
    }

    $(function(){
        // Initialize select2 for all Class
        $('.select2').select2({
          placeholder: "Select an option",
          allowClear: true
        });


        $('form#modalDetailAjax').submit(function(e){
            e.preventDefault();
            var form = this;
            var is_parked = $("#is_parked").val(); // flag kalau CA sudah di park, maka nanti ini mentrigger delete existing CA

            $data = {
                'company_code' : $('#company_code').val(),
                'plant' : $('#plant_park').val(),
                'currency' : $('#currency_park').val(),
                'cajo_number' : $('#cajo_number').val(),
                'business_trans' : $('#business_trans').val(),
                'purpose' : $('#purpose_park').val(),
                'ca_number' : $('#ca_number_park').val(),
                'grand_total' : $('#grand_total_park').val(),
                'ca_category' : $("#ca_category").val(),
                'is_parked' : is_parked
            }
            Swal.fire({
                title: "Sending data...",
                text: "Please wait!",
                imageUrl: form.getAttribute("data-loader-file"),
                imageWidth: 140,
                imageHeight: 140,
                showConfirmButton: false,
                allowOutsideClick: false
            });

            setTimeout(function(){
                $.ajax({
                    url : '/finance/cash-advance/insert-cash-advance',
                    type : 'GET',
                    data : {...$data},
                    success : function(response){
                      if(response.hasOwnProperty('status') && response.status == 'success'){
                        Swal.fire({
                          icon: 'success',
                          title: 'Cash Advance Parked',
                          text: response.message,
                        }).then((result) =>{
                            location.reload();
                        });
                      } else {
                        Swal.fire('Oops..', response.message, 'error');
                      }
                    },
                    error : function(xhr){
                      Swal.fire('Oops..', 'Something went wrong, please try again', 'error');
                      console.log("EXCEPTION OCCURED IN SENDING PARKED DATA TO SAP");
                    }
                });
            }, 500);
            return false;
        })
    })
</script>
