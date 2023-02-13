<form method="POST" id="formApproveModal" enctype="multipart/form-data" data-url-post="{{url('vcc-parsing/data/update')}}" data-loader-file="{{ url('/image/gif/cube.gif') }}">
    {{ csrf_field() }}
    <div class="form-group">

        <div class="row mb-3">
            <div class="col-md-6">
                <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;"> VCC Information</h3>
                <table class="table table-bordered detail-tb">
                    <tr><td><span class="caption-field">ID</span></td><td><input type="text" name="id" id="" class="form-control form-control-edit" readonly value="{{$data->id}}"></td></tr>
                    <tr><td><span class="caption-field">Payment ID</span></td><td><input type="text" name="paymentid" id="" class="form-control form-control-edit" readonly value="{{$data->paymentid}}"></td></tr>
                    <tr><td><span class="caption-field">Reservation ID</span></td><td><input type="text" name="reservationid" id="" class="form-control form-control-edit" readonly value="{{$data->reservationid}}"></td></tr>
                    <tr><td><span class="caption-field">OTA Name</span></td><td><input type="text" name="ota_name" id="" class="form-control form-control-edit" readonly value="{{$data->ota_name}}"></td></tr>
                    <tr><td><span class="caption-field">Property Name</span></td><td><input type="text" name="property_name" id="" class="form-control form-control-edit" readonly value="{{$data->property_name}}"></td></tr>
                    <tr><td><span class="caption-field">Guest Name</span></td><td><input type="text" name="guestname" id="" class="form-control form-control-edit" readonly value="{{$data->guestname}}"></td></tr>
                    <tr><td><span class="caption-field">Amount</span></td><td><input type="text" name="vccamount" id="" class="form-control form-control-edit" readonly value="{{$data->vccamount}}"></td></tr>
                    <tr><td><span class="caption-field">Valid Start</span></td><td><input type="text" name="vccdatestart" id="" class="form-control form-control-edit" readonly value="{{$data->vccdatestart}}"></td></tr>
                    <tr><td><span class="caption-field">Valid End</span></td><td><input type="text" name="vccdateend" id="" class="form-control form-control-edit" readonly value="{{$data->vccdateend}}"></td></tr>
                    <tr><td><span class="caption-field">VCC</span></td><td><input type="text" name="vcc" id="" class="form-control form-control-edit" readonly value="{{$data->vcc}}"></td></tr>
                    <tr><td><span class="caption-field">Valid Until</span></td><td><input type="text" name="validuntil" id="" class="form-control form-control-edit" readonly value="{{$data->validuntil}}"></td></tr>
                    <tr><td><span class="caption-field">CVC</span></td><td><input type="text" name="cvc" id="" class="form-control form-control-edit" readonly value="{{$data->cvc}}"></td></tr>
                    <tr><td><span class="caption-field">Date Checkin</span></td><td><input type="text" name="datecheckin" id="" class="form-control form-control-edit" readonly value="{{$data->datecheckin}}"></td></tr>
                    <tr><td><span class="caption-field">Date Checkout</span></td><td><input type="text" name="datecheckout" id="" class="form-control form-control-edit" readonly value="{{$data->datecheckout}}"></td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <h3 style="border-bottom:1px solid #e9e9e9; padding:10px 0px;"> Processing Information</h3>
                <table class="table table-bordered detail-tb">
                    <tr><td><span class="caption-field">Date Fetched From Mail</span></td><td><input type="text" name="date_fetched_from_mail" id="" class="form-control form-control-edit" readonly value="{{$data->date_fetched_from_mail}}"></td></tr>
                    <tr><td><span class="caption-field">Is Processed </span></td><td>
                        {{-- <input type="text" name="is_processed" id="" class="form-control form-control-edit" readonly value="{{$data->is_processed}}"> --}}
                        <select name="is_processed" id="" class="form-control form-control-edit" readonly>
                            <option value="0" {{ $data->is_processed == 0 ? 'selected' : ''}}>To be Processed</option>
                            <option value="1" {{ $data->is_processed == 1 ? 'selected' : ''}}>Already Processed</option>
                        </select>
                    </td></tr>
                    <tr><td><span class="caption-field">Processed Date</span></td><td><input type="text" name="is_processed_date" id="" class="form-control form-control-edit" readonly value="{{$data->is_processed_date}}"></td></tr>
                    <tr><td><span class="caption-field">Processed Status</span></td><td><input type="text" name="is_processed_status" id="" class="form-control form-control-edit" readonly value="{{$data->is_processed_status}}"></td></tr>
                    <tr><td colspan="2" style="background:#ececec;vertical-align:middle;"><h4> Midtrans Status </h4></td></tr>
                    <tr><td><span class="caption-field">Midtrans ID</span></td><td><input type="text" name="midtrans_order_id" id="" class="form-control form-control-edit" readonly value="{{$data->midtrans_order_id}}"></td></tr>
                    <tr><td><span class="caption-field">Midtrans Transaction Status</span></td><td><input type="text" name="midtrans_transaction_status" id="" class="form-control form-control-edit" readonly value="{{$data->midtrans_transaction_status}}"></td></tr>
                    <tr><td><span class="caption-field">Finish Approval Code</span></td><td><input type="text" name="finish_approval_code" id="" class="form-control form-control-edit" readonly value="{{$data->finish_approval_code}}"></td></tr>
                    <tr><td colspan="2" style="background:#ececec;vertical-align:middle;"><h4> Opera Status </h4></td></tr>
                    <tr><td><span class="caption-field">Validated in Opera</span></td><td><input type="text" name="is_validated_opera" id="" class="form-control form-control-edit" readonly value="{{$data->is_validated_opera}}"></td></tr>
                    <tr><td><span class="caption-field">Posted in Opera</span></td><td><input type="text" name="is_posted_deposit_opera" id="" class="form-control form-control-edit" readonly value="{{$data->is_posted_deposit_opera}}"></td></tr>
                    <tr><td><span class="caption-field">Opera Reservation ID</span></td><td><input type="text" name="opera_reservation_id" id="" class="form-control form-control-edit" readonly value="{{$data->opera_reservation_id}}"></td></tr>
                    <tr><td><span class="caption-field">Opera Fetchbooking JSON</span></td><td><input type="text" name="opera_fetch_json" id="" class="form-control form-control-edit" readonly value="{{$data->opera_fetch_json}}"></td></tr>
                </table>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-12">
            {{-- <input type="submit" id="submitApproveModal" value="APPROVE REQUEST" class="btn btn-success text-white"> --}}
            <button type="button" id="buttonEdit"  onclick="toggleEdit();" class="btn btn-primary text-white">Edit </button>
            <button type="submit" id="buttonSave" class="btn btn-success text-white" style="display: none;">Save</button>
            <button type="button" id="buttonCancel" onclick="cancelEdit()" class="btn btn-danger text-white" style="display: none;">Cancel</button>
            <button type="button" id="buttonBack" data-dismiss="modal" class="btn btn-secondary">Close</button>
        </div>
    </div>
</form>
<hr style="border-bottom:#ececec 2px solid;">
<div class="row">
    <div class="col-md-12">
        <h3>VCC Logs</h3>
        <div style="max-height:300px;overflow:scroll;position:relative">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Message</th>
                        <th>Log</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($log as $log)
                        <tr>
                            <td>{{ date('d M Y H:i:s',strtotime($log->DATE)) }} </td>
                            <td>{{ $log->STATUS}} </td>
                            <td>{{ $log->MESSAGE}} </td>
                            <td><textarea class="form-control">{{$log->LOG}} </textarea> </td>
                            <td><textarea class="form-control">{{$log->DATA}} </textarea> </td>
                        </tr>
                    @endforeach
                </tbody>


            </table>

        </div>

    </div>


</div>
<script>

    $("#formApproveModal").submit(function(e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.
        var form = $(this);
        var url_post=$("#formApproveModal").attr('data-url-post');
        var loader=$("#formApproveModal").attr('data-loader-file');
        var form = new FormData(this);

        $.ajax({
            type: "POST",
            url: url_post,
            data: form,
            cache:false,
            contentType: false,
            processData: false,
            beforeSend: function() {
            Swal.fire({
                title: "Loading...",
                text: "Please wait!",
                imageUrl: loader,
                showConfirmButton: false
            });
            },
            success: function(data) {
            if (data.code==200) {

                if (data.message) {

                    Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: data.message,
                    }).then((result) =>{
                        cancelEdit();
                    });

                }
            } else {

                Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: data.message,
                });

            }

            },
            error: function(err) {

                //console.log(err);
                Swal.fire({
                icon: "error",
                title: "Oops...",
                text : err && err.responseJSON && err.responseJSON.message
                });

            }
        });
    });

    function toggleEdit(){
        var ele = document.getElementsByClassName('form-control-edit');
        for(var e = 0; e < ele.length; e++) { // For each element
            var elt = ele[e];
            elt.removeAttribute("readonly");
        }

        document.getElementById('buttonCancel').style.display="inline";
        document.getElementById('buttonSave').style.display="inline";
        document.getElementById('buttonEdit').style.display="none";
    }

    function cancelEdit(){
        var ele = document.getElementsByClassName('form-control-edit');
        for(var e = 0; e < ele.length; e++) { // For each element
            var elt = ele[e];
            elt.setAttribute("readonly",'');
        }

        document.getElementById('buttonCancel').style.display="none";
        document.getElementById('buttonSave').style.display="none";
        document.getElementById('buttonEdit').style.display="inline";
    }
</script>
