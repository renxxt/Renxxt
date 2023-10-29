@extends('layouts.app')
@section('content')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<style>
    .bi {
        color: #000000;
        font-size: 25px;
    }

    .bi-trash-fill {
        color: #FF0000;
    }

    .col-sm-6 {
        width: 50%;
        float: left;
    }

    .stagedList {
        background-color: #ECECEA;
        padding-right: 0px;
        padding-left: 0px;
        margin-right: auto;
        margin-left: auto;
        width: 650px;
        height: 50%;
        overflow-y: scroll;
        border: 2px solid #000;
    }

    form {
        padding-right: 0px;
        padding-left: 0px;
        margin-right: auto;
        margin-left: auto;
        max-width: 1200px;
        max-height: 850px;
    }

    .button {
        width: 100%;
        display: flex;
        justify-content: center;
    }

    .button>input {
        width: 15%;
        margin: 15px;
    }

    .alert {
        padding-right: 0px;
        padding-left: 0px;
        margin-right: auto;
        margin-left: auto;
    }
</style>

<h2 style="margin-left: 20px;">預借申請表單</h2>

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

<form method="POST" action="{{ route('staged.store') }}" class="mt-4 text-center">
    @csrf
    <div>
        <div class="col-sm-6 mb-4">
            <div class="row">
                <label class="col-sm-2 col-form-label">使用時間</label>
                <div class="col-sm-10">
                    <div class="row" style="margin-left: 2px;">
                        <input type="datetime" name="estimated_pickup_time" class="form-control col-md-5" placeholder="輸入開始時間" required>
                        <h4 class="col-md-auto"> ~ </h4>
                        <input type="datetime" name="estimated_return_time" class="form-control col-md-5" placeholder="輸入結束時間" required>
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
                                <option value="{{ $row['attributeID'] }}">{{ $row['name'] }}</option>
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
                    </select>
                </div>
            </div>
            <div class="row mt-3">
                <label class="col-sm-2 col-form-label">使用目的</label>
                <div class="col-sm-10">
                    <input type="text" name="target" class="form-control" placeholder="輸入使用目的" required>
                </div>
            </div>
            <div class="row mt-3">
                <a class="bi bi-plus-square" type="button" id="createBtn"></a>
            </div>
            <div class="row">
                <label class="col-sm-2 col-form-label">陪同人</label>
                <div class="col-sm-10">
                    <input type="text" name="companion[]" class="form-control" placeholder="輸入陪同人工號">
                </div>
            </div>
        </div>

        <div class="col-sm-6 mb-4">
            <ul class="stagedList list-group" style="min-height: 50%; max-height: 25%;">
                @if ($stagedList !== false)
                    @php
                        $count = 0;
                    @endphp
                    @foreach ($stagedList as $row)
                        @php
                            $count++;
                        @endphp
                        <li class="list-group-item">
                            {{ $count . ". " . $row['attribute'] . " " . $row['device'] . " " . $row['estimated_pickup_time'] . "~" . $row['estimated_return_time'] }}
                            <a class="bi bi-trash-fill ml-3 mr-2" data-id="{{ $row['applicationID'] }}"></a>
                            <a class="bi bi-pencil-square" data-id="{{ $row['applicationID'] }}"></a>
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>
    </div>

    <div class="button">
        <input type="submit" name="submit" class="btn" value="加入清單" style="background-color: white; color: black; border: 1px solid black;">
        <input type="button" class="btn" id="storeForm" value="送出" style="background-color: #3E517A; color: white;">
    </div>
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

<div class="modal" id="failModal" role="dialog">
    <div class="modal-dialog modal-sm" style="background-color: #FF0000">
        <div class="modal-content">
            <div id="failContent">
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
            if (returnTime != '' && attributeID != '') {
                getDevice(pickupTime, returnTime, attributeID);
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
            if (pickupTime != '' && attributeID > 0) {
                getDevice(pickupTime, returnTime, attributeID);
            }
		});

		$('input[name="estimated_return_time"]').on('cancel.daterangepicker', function(ev, picker) {
			$(this).val('');
		});

        $('#attributeID').change(function() {
            var attributeID = $('#attributeID').val();
			var pickupTime = $('input[name="estimated_pickup_time"]').val();
			var returnTime = $('input[name="estimated_return_time"]').val();
            if (pickupTime != '' && returnTime != '') {
                getDevice(pickupTime, returnTime, attributeID);
            }
        });

        function getDevice(pickupTime, returnTime, attributeID) {
			$('#device').children().remove();
            $.ajax({
                type: 'POST',
                url: "{{ route('api.getDevice') }}",
                dataType: 'json',
                data: {
                    estimated_pickup_time: pickupTime,
                    estimated_return_time: returnTime,
                    attributeID: attributeID
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

        $(document).on('click', '.bi-trash-fill', function() {
            var applicationID = $(this).data('id');
            var item = $(this).closest('li');
            $.ajax({
                type: 'DELETE',
                url: "{{ route('staged.delete') }}",
                dataType: 'json',
                data: { applicationID: applicationID },
                success: function(data) {
                    item.remove();
                }
            });
        })

        $(document).on('click', '.bi-pencil-square', function() {
            var applicationID = $(this).data('id');
            var item = $(this).closest('li');
            check = false;
            $('#device').children().remove();
            $.ajax({
                type: 'POST',
                url: "{{ route('staged.show') }}",
                dataType: 'json',
                data: { applicationID: applicationID },
                success: function(data) {
                    result = data['result'];
                    deviceList = data['deviceList'];
                    $('input[name="estimated_pickup_time"]').val(result.estimated_pickup_time);
                    $('input[name="estimated_return_time"]').val(result.estimated_return_time);
                    $('select[name="attributeID"]').val(result.attributeID);
                    $('input[name="target"]').val(result.target);
                    var id = `<input name="applicationID" value="${result.applicationID}" hidden>`;
                    $(id).insertAfter('input[name="submit"]');
                    var boo;
                    if (deviceList.length > 0) {
                        $('#device').append('<option disabled selected>請選擇設備</option>');
                        $.each(deviceList, function(index, list) {
                            var listItem = $('<option value="' + list.deviceID + '">' + list.name + '</option>');
                            if (result.deviceID == list.deviceID) {
                                boo = true;
                                listItem.prop('selected', true);
                            }
                            $('#device').append(listItem);
                        });
                    } else {
                        $('#device').append('<option>此時段暫無該屬性可預借的設備</option>');
                    }

                    if (boo == false) {
                        $('#failModal').modal('show');
                        $('#failContent h3').remove();
                        $('#failContent').append('<h3>' + result.device + '已被預借</h3>');
                        setTimeout(function() {
                            $('#failModal').modal('hide');
                        }, 2000);
                    }

                    if (result['companions'].length !== 0) {
                        $.each(result['companions'], function(index, list) {
                            if (index === 0) {
                                $('input[name="companion[]"]').val(list.uid);
                            } else {
                                var button = `
                                    <input type="text" name="companion[]" class="form-control mt-3" placeholder="輸入陪同人工號" value="${list.uid}">
                                `;

                                $(button).insertAfter('input[name="companion[]"]:last');
                            }
                        });
                    } else {
                        $('input[name="companion[]"]').val('');
                    }
                }
            });
        })

        $(document).on('click', '#storeForm', function() {
            $.ajax({
                type: 'POST',
                url: "{{ route('applicationForm.store') }}",
                dataType: 'json',
                success: function(data) {
                    if (data.messageData) {
                        $('#failContent h3').remove();
                        if (data.messageData.type === "notFound") {
                            $('#failContent').append('<h3>目前無預借資料</h3>');
                        } else {
                            var result = data.messageData.message;
                            $('#failContent').append('<h3>該時段（' + result.estimated_pickup_time + '~' + result.estimated_return_time + '）' + result.name + '已被預借</h3>');
                        }

                        $('#failModal').modal('show');
                        setTimeout(function() {
                            $('#failModal').modal('hide');
                        }, 3000);
                    } else if (data.result) {
                        window.location.href = "{{ route('applicationForm.applicationList') }}";
                    }
                }
            });
        })
    })
</script>
@endsection
