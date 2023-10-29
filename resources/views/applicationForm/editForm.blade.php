@extends('layouts.app')
@section('content')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<style>
    .bi {
        color: #000000;
        font-size: 25px;
    }
</style>

<h2 style="margin-left: 20px;">修改預借申請</h2>

@if($errors->any())
    <div class="alert alert-dismissible alert-danger" role="alert">
        @foreach($errors->all() as $error)
            <button type="button" class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>
            </button>
            <ul>{{ $error }}</ul>
        @endforeach
    </div>
@endif

@if (session()->has('messageData'))
    @foreach (session('messageData') as $messageData)
        <div class="alert alert-dismissible alert-{{$messageData['type']}} col-md-4" role="alert">
            <button type="button" class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>
            </button>
            <ul>{{ $messageData['message'] }}</ul>
        </div>
    @endforeach
@endif

<form method="POST" action="{{ route('applicationForm.update') }}" class="mt-4 text-center">
    @csrf
    <input type="hidden" name="_method" value="PUT">
    @if ($result !== false)
        <div class="row">
            <label class="col-sm-2 col-form-label">使用時間</label>
            <div class="col-sm-10">
                <div class="row" style="margin-left: 2px;">
                    <input type="datetime" name="estimated_pickup_time" class="form-control col-md-5" placeholder="輸入開始時間" value="{{ $result['estimated_pickup_time'] }}" required>
                    <h4 class="col-md-auto"> ~ </h4>
                    <input type="datetime" name="estimated_return_time" class="form-control col-md-5" placeholder="輸入結束時間" value="{{ $result['estimated_return_time'] }}" required>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <label class="col-sm-2 col-form-label">屬性</label>
            <div class="col-sm-10">
                <select id="attributeID" name="attributeID" class="form-control">
                    <option disabled selected>請選擇屬性</option>
                    @if ($attributes !== false)
                        @foreach ($attributes as $row)
                            <option value="{{ $row['attributeID'] }}" {{ $result['attributeID'] == $row['attributeID'] ? 'selected' : '' }}>{{ $row['name'] }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="row mt-3">
            <label class="col-sm-2 col-form-label">設備</label>
            <div class="col-sm-10">
                <select id="device" name="deviceID" class="form-control">
                    <option disabled selected>請選擇設備</option>
                    @if ($devices !== false)
                        @foreach ($devices as $row)
                            <option value="{{ $row['deviceID'] }}" {{ $result['deviceID'] == $row['deviceID'] ? 'selected' : '' }}>{{ $row['name'] }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="row mt-3">
            <label class="col-sm-2 col-form-label">使用目的</label>
            <div class="col-sm-10">
                <input type="text" name="target" class="form-control" value="{{ $result['target'] }}" placeholder="輸入使用目的" required>
            </div>
        </div>
        <div class="row mt-3">
            <a class="bi bi-plus-square" type="button" id="createBtn"></a>
        </div>
        <div class="row">
            <label class="col-sm-2 col-form-label">陪同人</label>
            <div class="col-sm-10">
                @if (count($result['companions']) !== 0)
                    @foreach ($result['companions'] as $row)
                        <input type="text" name="companion[]" class="form-control mt-3" value="{{ $row['uid'] }}" placeholder="輸入陪同人工號">
                    @endforeach
                @else
                    <input type="text" name="companion[]" class="form-control" placeholder="輸入陪同人工號">
                @endif
            </div>
        </div>
        <div class="mt-4">
            <input name="applicationID" id="applicationID" value="{{ $result['applicationID'] }}" hidden>
            <input type="submit" name="submit" class="btn" value="修改" style="background-color: #3E517A; color: white;">
        </div>
    @endif
</form>

<div class="modal" id="timeErrorModal" role="dialog">
    <div class="modal-dialog modal-sm" style="background-color: #FF0000">
        <div class="modal-content">
            <div class="modal-body">
                <h4>開始時間要早於結束時間</h4>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('input[name="estimated_pickup_time"]').daterangepicker({
            autoUpdateInput: false,
            singleDatePicker: true,
            timePicker: true,
            timePicker24Hour: true,
            minDate: new Date(),
            locale: {
                cancelLabel: '取消',
                applyLabel: '確認',
                format: 'YYYY-MM-DD H:mm',
            }
        });

        $('input[name="estimated_return_time"]').daterangepicker({
            autoUpdateInput: false,
            singleDatePicker: true,
            timePicker: true,
            timePicker24Hour: true,
            minDate: new Date(),
            locale: {
                cancelLabel: '取消',
                applyLabel: '確認',
                format: 'YYYY-MM-DD H:mm',
            }
        });

        $('input[name="estimated_pickup_time"]').on('apply.daterangepicker', function(ev, picker) {
			$(this).val(picker.startDate.format('YYYY-MM-DD H:mm'));
            var pickupTime = $(this).val();
            var returnTime = $('input[name="estimated_return_time"]').val();
            if (returnTime != '') {
                if (new Date(pickupTime.replace("-", "/").replace("-", "/")) > new Date(returnTime.replace("-", "/"))) {
                    $('#timeErrorModal').modal('show');
                    setTimeout(function() {
                        $('#timeErrorModal').modal('hide');
                    }, 2000);
                }
            }

			var attributeID = $('#attributeID').val();
			var applicationID = $('#applicationID').val();
            if (returnTime != '' && attributeID != '') {
                getDevice(pickupTime, returnTime, attributeID, applicationID);
            }
		});

		$('input[name="estimated_pickup_time"]').on('cancel.daterangepicker', function(ev, picker) {
			$(this).val('');
		});

        $('input[name="estimated_return_time"]').on('apply.daterangepicker', function(ev, picker) {
			$(this).val(picker.endDate.format('YYYY-MM-DD H:mm'));
			var pickupTime = $('input[name="estimated_pickup_time"]').val();
            var returnTime = $(this).val();
            if (pickupTime != '') {
                if (new Date(pickupTime.replace("-", "/").replace("-", "/")) > new Date(returnTime.replace("-", "/"))) {
                    $('#timeErrorModal').modal('show');
                    setTimeout(function() {
                        $('#timeErrorModal').modal('hide');
                    }, 2000);
                }
            }

            var attributeID = $('#attributeID').val();
            var applicationID = $('#applicationID').val();
            if (pickupTime != '' && attributeID > 0) {
                getDevice(pickupTime, returnTime, attributeID, applicationID);
            }
		});

		$('input[name="estimated_return_time"]').on('cancel.daterangepicker', function(ev, picker) {
			$(this).val('');
		});

        $('#attributeID').change(function() {
            var attributeID = $('#attributeID').val();
			var pickupTime = $('input[name="estimated_pickup_time"]').val();
			var returnTime = $('input[name="estimated_return_time"]').val();
			var applicationID = $('input[name="applicationID"]').val();
            if (pickupTime != '' && returnTime != '') {
                getDevice(pickupTime, returnTime, attributeID, applicationID);
            }
        });

        function getDevice(pickupTime, returnTime, attributeID, applicationID) {
			$('#device').children().remove();
            $.ajax({
                type: 'POST',
                url: "{{ route('api.getDevice') }}",
                dataType: 'json',
                data: {
                    estimated_pickup_time: pickupTime,
                    estimated_return_time: returnTime,
                    attributeID: attributeID,
                    applicationID: applicationID
                },
                success: function(data) {
                    if (data !== false) {
			            $('#device').append('<option disabled selected>請選擇設備</option>');
                        $.each(data, function(index, list) {
                            var listItem = $('<option value="' + list.deviceID + '">' + list.name + '</option>');
                            $('#device').append(listItem);
                        });
                    }
                },
                error: function(data) {
                    $('#device').append('<option>此時段暫無該屬性可預借的設備</option>');
                }
            });
        }

        $(document).on('click', '#createBtn', function() {
            var button = `
                <input type="text" name="companion[]" class="form-control mt-3" placeholder="輸入陪同人工號">
            `;

            $(button).insertAfter('input[name="companion[]"]:last');
        })
    })
</script>

@endsection
