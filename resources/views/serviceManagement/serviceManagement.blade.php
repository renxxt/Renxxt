@extends('layouts.app')
@section('content')
<style>
    .bi {
        color: #000000;
        font-size: 30px;
    }

    .bi-trash-fill {
        color: #FF0000;
    }

    hr {
        border-top: 1px solid;
        width: 95%;
    }
</style>

<div class="row" style="margin-left: 20px;">
    <h2>服務管理</h2>
    <div class="mr-5 dropdown" style="margin: auto;">
        <a class="bi bi-plus-square" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
          <a class="dropdown-item" href="{{ route('serviceManagement.attribute.create') }}">新增類別</a>
          <a class="dropdown-item" href="{{ route('serviceManagement.device.create') }}">新增設備</a>
          <a class="btn dropdown-item" data-toggle="modal" data-target="#createQuestion">新增表單問項</a>
        </div>
    </div>
</div>

@if (session()->has('messageData'))
    @foreach (session('messageData') as $messageData)
        <div class="alert alert-dismissible alert-{{$messageData[ 'type' ]}} col-md-4" role="alert">
            <button type="button" class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>
            </button>
            <ul>{{ $messageData['message'] }}</ul>
        </div>
    @endforeach
@endif

@if ($attributes !== false)
    @foreach ($attributes as $row)
        <div class="card mt-4" style="height: 70px;">
            <div class="row mt-auto mb-auto">
                <div class="ml-5">
                    <h4>{{ $row['name'] }}</h4>
                </div>
                <div class="ml-auto mr-4">
                    <a class="bi bi-trash-fill mr-2 deleteCheck" data-toggle="modal" data-target="#deleteModal" data-attr-id="{{ $row['attributeID'] }}" data-attr-name="{{ $row['name'] }}"></a>
                    <a href="{{ route('serviceManagement.attribute.show', ['id' => $row['attributeID']]) }}" class="bi bi-pencil-square mr-2"></a>
                    <a class="bi bi-eye-slash-fill mr-2" id="hideAttribute" data-id="{{ $row['attributeID'] }}" style="display: {{ $row['display'] == 0 ? '' : 'none' }};"></a>
                    <a class="bi bi-eye-fill mr-2" id="showAttribute" data-id="{{ $row['attributeID'] }}" style="display: {{ $row['display'] == 1 ? '' : 'none' }};"></a>
                    <a data-toggle="collapse" href="#collapse{{ $row['attributeID'] }}" aria-expanded="false" aria-controls="collapse" data-id="{{ $row['attributeID'] }}" class="bi bi-caret-down-fill mr-2"></a>
                </div>
            </div>
        </div>
        <div class="card collapse" id="collapse{{ $row['attributeID'] }}">
            <div class="ml-3 mt-3">
                <ul style="list-style-type: none;">
                    <li>批准層級：{{ $row['approved_level'] }}</li>
                    <li>批准層數：{{ $row['approved_layers'] }}</li>
                    <li>同伴同行人數：{{ $row['companion_number'] }}</li>
                    <li class="mt-2">
                    @if ($row['pickup_form'] == 1)
                        <button type="button" class="btn" id="pickupForm" style="background-color: #3E517A; color: #FFFFFF" data-attribute-id="{{ $row['attributeID'] }}">取用表單</button>
                    @endif
                    @if ($row['return_form'] == 1)
                        <a class="btn btn-success text-white" data-toggle="modal" id="returnForm" data-attribute-id="{{ $row['attributeID'] }}">歸還表單</a>
                    @endif
                    </li>
                </ul>
            </div>
            <hr class="ml-auto mr-auto">
            <div class="mx-5">
                <table class="table table-striped table-bordered table-hover" id="table{{ $row['attributeID'] }}">
                    <thead>
                        <tr>
                            <th style="width: 1%;">#</th>
                            <th>設備</th>
                            <th>類型</th>
                            <th>保管人</th>
                            <th>擺放地點</th>
                            <th style="width: 10%;">預借金額</th>
                            <th style="width: 15%;">操作</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    @endforeach
@endif

<div class="modal fade text-center" id="createQuestion">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body">
                <h3>新增表單問項</h3>
                <input type="text" name="question" class="form-control mt-2" required>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn" id="storeQuestion" data-dismiss="modal" style="background-color: #3E517A; color: #FFFFFF">新增</button>
                <button type="button" class="btn" data-dismiss="modal" style="background-color: #ECECEA; color: #000000">關閉</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade text-center" id="deleteModal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body">
                <h3>是否刪除？</h3>
                <p></p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn" id="delete" data-dismiss="modal" style="background-color: #3E517A; color: #FFFFFF">確認刪除</button>
                <button type="button" class="btn" data-dismiss="modal" style="background-color: #ECECEA; color: #000000">關閉</button>
            </div>
        </div>
    </div>
</div>

<div class="modal text-center" id="successModal" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content" style="background-color: #3E517A; color: #FFFFFF">
            <div class="modal-body">
                <h3>刪除成功</h3>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="errorModal" role="dialog">
    <div class="modal-dialog modal-sm" style="background-color: #FF0000">
        <div class="modal-content">
            <div class="modal-body">
                <h3>刪除失敗</h3>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="storeSuccessModal" role="dialog">
    <div class="modal-dialog modal-sm" style="background-color: #FF0000">
        <div class="modal-content">
            <div class="modal-body">
                <h3>新增成功</h3>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="storeErrorModal" role="dialog">
    <div class="modal-dialog modal-sm" style="background-color: #FF0000">
        <div class="modal-content">
            <div class="modal-body">
                <h3>新增失敗</h3>
            </div>
        </div>
    </div>
</div>

<div class="modal fade text-center" id="pickupModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <h3 class="mb-3">取用表單</h3>
                <ul class="pickup_list" style="padding: 0;">
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="modal fade text-center" id="returnModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <h3 class="mb-3">歸還表單</h3>
                <ul class="return_list" style="padding: 0;">
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        @foreach ($attributes as $row)
            var tableId = 'table{{ $row['attributeID'] }}';

            $('#' + tableId).DataTable({
                language: {
                    "infoFiltered": "(從 _MAX_ 項結果中過濾)",
                    "sInfo": "顯示第 _START_ 至 _END_ 項結果，共 _TOTAL_ 項",
                    "sInfoEmpty": "顯示第 0 至 0 項結果，共 0 項",
                    "sLengthMenu": "顯示 _MENU_ 項結果",
                    "oPaginate": {
                        "sFirst": "首頁",
                        "sPrevious": "上頁",
                        "sNext": "下頁",
                        "sLast": "尾頁"
                    },
                    "emptyTable": "暫無資料"
                },
                columns: [
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    { data: 'name' },
                    { data: 'type' },
                    { data: 'userName' },
                    { data: 'storage_location' },
                    { data: 'price' },
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            var deviceID = row.deviceID;
                            var name = row.name;
                            var display = row.display
                            var button = `
                                <a class="bi bi-trash-fill mr-2 deleteCheck" data-toggle="modal" data-target="#deleteModal" data-device-id="${deviceID}" data-device-name="${name}"></a>
                                <a href="/serviceManagement/device/${deviceID}" class="bi bi-pencil-square mr-2"></a>
                                <a class="bi bi-eye-slash-fill mr-2" id="hideDevice" data-id="${deviceID}" style="display: ${display == 0 ? '' : 'none'};"></a>
                                <a class="bi bi-eye-fill mr-2" id="showDevice" data-id="${deviceID}" style="display: ${display == 1 ? '' : 'none'};"></a>
                            `;
                            return button;
                        }
                    },
                ]
            });
        @endforeach

        $(document).on('click', '#showAttribute', function() {
            var attributeID = $(this).data('id');
            updateAttributeDisplay(attributeID, 0);
        })

        $(document).on('click', '#hideAttribute', function() {
            var attributeID = $(this).data('id');
            updateAttributeDisplay(attributeID, 1);
        })

        function updateAttributeDisplay(attributeID, display) {
            $.ajax({
                type: 'POST',
                url: "{{ route('serviceManagement.attribute.changeDisplay') }}",
                dataType: 'json',
                data: {
                    attributeID: attributeID,
                    display: display,
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    var show = $('#showAttribute[data-id="' + attributeID + '"]');
                    var hide = $('#hideAttribute[data-id="' + attributeID + '"]');
                    if (display == 0) {
                        show.hide();
                        hide.show();
                    } else {
                        show.show();
                        hide.hide();
                    }
                }
            });
        }

        $(document).on('click', '#showDevice', function() {
            var deviceID = $(this).data('id');
            updateDeviceDisplay(deviceID, 0);
        })

        $(document).on('click', '#hideDevice', function() {
            var deviceID = $(this).data('id');
            updateDeviceDisplay(deviceID, 1);
        })

        function updateDeviceDisplay(deviceID, display) {
            $.ajax({
                type: 'POST',
                url: "{{ route('serviceManagement.device.changeDisplay') }}",
                dataType: 'json',
                data: {
                    deviceID: deviceID,
                    display: display,
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    var show = $('#showDevice[data-id="' + deviceID + '"]');
                    var hide = $('#hideDevice[data-id="' + deviceID + '"]');
                    if (display == 0) {
                        show.hide();
                        hide.show();
                    } else {
                        show.show();
                        hide.hide();
                    }
                }
            });
        }

        $(document).on('click', '.bi-caret-down-fill', function() {
            var attributeID = $(this).data('id');
            var dataTable = $('#table'+ attributeID).DataTable();
            $.ajax({
                type: 'GET',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                url: "{{ route('api.device.list') }}",
                dataType: 'json',
                data: { attributeID: attributeID },
                success: function(data) {
                    dataTable.clear();
                    dataTable.rows.add(data);
                    dataTable.columns.adjust().draw();
                }
            });
        })

        $(document).on('click', '#storeQuestion', function() {
            if ($('input[name="question"]').val() !== '') {
                var question = $('input[name="question"]').val();
                $.ajax({
                    type: 'POST',
                    url: "{{ route('serviceManagement.question.store') }}",
                    dataType: 'json',
                    data: {
                        question: question,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        $('#createQuestion').hide();
                        $('#storeSuccessModal').modal('show');
                        setTimeout(function() {
                            $('#storeSuccessModal').modal('hide');
                        }, 2000);
                    },
                    error: function() {
                        $('#storeErrorModal').modal('show');
                        setTimeout(function() {
                            $('#storeErrorModal').modal('hide');
                        }, 2000);
                    },
                });
            } else {
                $('#storeErrorModal').modal('show');
                setTimeout(function() {
                    $('#storeErrorModal').modal('hide');
                }, 2000);
            }
        })

        $(document).on('click', '.deleteCheck', function() {
            if ($(this).is('[data-attr-id]')) {
                var attributeID = $(this).data('attr-id');
                var name = $(this).data('attr-name');
                var type = "設備類別：";
                $('#deleteModal #delete').attr('data-attr-id', attributeID);
            } else if ($(this).is('[data-device-id]')) {
                var deviceID = $(this).data('device-id');
                var name = $(this).data('device-name');
                var type = "設備：";
                $('#deleteModal #delete').attr('data-device-id', deviceID);
            }
            $('#deleteModal .modal-body p').text(type + name);
        })

        $(document).on('click', '#delete', function() {
            if ($(this).is('[data-attr-id]')) {
                var id = $(this).attr('data-attr-id');
                var url = "{{ route('serviceManagement.attribute.delete') }}";
            } else if ($(this).is('[data-device-id]')) {
                var id = $(this).attr('data-device-id');
                var url = "{{ route('serviceManagement.device.delete') }}";
            }
            $.ajax({
                type: 'DELETE',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}'
                },
                url: url,
                dataType: 'json',
                success: function() {
                    $('#successModal').modal('show');
                    setTimeout(function() {
                        $('#successModal').modal('hide');
                        location.reload();
                    }, 2000);
                },
                error: function() {
                    $('#errorModal').modal('show');
                    setTimeout(function() {
                        $('#errorModal').modal('hide');
                        location.reload();
                    }, 2000);
                },
            });
        })

        $(document).on('click', '#pickupForm', function() {
            var attributeID = $(this).data('attribute-id');
            $.ajax({
                type: 'POST',
                url: "{{ route('api.pickupForm.list') }}",
                dataType: 'json',
                data: {
                    attributeID: attributeID,
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    if (data !== false) {
                        $.each(data, function(index, list) {
                            var listItem = $('<li class="list-group-item list-group-item-primary">' + list.question + '</li>');
                            $('.pickup_list').append(listItem);
                        });
                    }
                }
            });
            $('#pickupModal').modal('show');
        })

        $('#pickupModal').on('hide.bs.modal', function () {
            $('.pickup_list').empty();
        });

        $(document).on('click', '#returnForm', function() {
            var attributeID = $(this).data('attribute-id');
            $.ajax({
                type: 'POST',
                url: "{{ route('api.returnForm.list') }}",
                dataType: 'json',
                data: {
                    attributeID: attributeID,
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    if (data !== false) {
                        $.each(data, function(index, list) {
                            var listItem = $('<li class="list-group-item list-group-item-primary">' + list.question + '</li>');
                            $('.return_list').append(listItem);
                        });
                    }
                }
            });
            $('#returnModal').modal('show');
        })

        $('#returnModal').on('hide.bs.modal', function () {
            $('.return_list').empty();
        });
    });
</script>
@endsection
