@extends('layouts.app')
@section('content')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<style>
    .bi {
        color: #000000;
        font-size: 30px;
    }

    .card, .nav-item {
        border: 1px solid #000000;
        border-radius: 5px
    }

    .nav-item>.active {
        border: transparent;
    }
</style>

<div class="row" style="margin-left: 20px;">
    <h2>我的申請列表</h2>
    <div class="mr-5" style="margin: auto;">
        <a class="btn" href="{{ route('applicationForm.create') }}" style="background-color: #3E517A; color: #FFFFFF;">申請服務</a>
    </div>
</div>

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

<ul class="nav nav-tabs nav-fill mt-4">
    <li class="nav-item">
        <a class="nav-link active" href="{{ route('applicationForm.applicationList') }}" style="background-color: #3E517A; color: #FFFFFF;">預借申請</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('applicationForm.cancelList') }}">已取消</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('applicationForm.completedList') }}">已歸還</a>
    </li>
</ul>

<div class="tab-content">
    @if (count($result) !== 0)
        @foreach ($result as $row)
            <div class="card" style="height: 70px;">
                <div class="row mt-auto mb-auto">
                    <div class="ml-5">
                        <h4>{{ $row['uuid'] }}</h4>
                    </div>
                    <div class="row ml-auto mr-4">
                        <h5 class="mt-auto mb-auto" style="color: #FF0000; margin-right: 50px;">
                            @if ($row['state'] == 0)
                                待審核
                            @elseif ($row['state'] == 1)
                                已審核
                            @else
                                使用中
                            @endif
                        </h5>
                        <div class="mt-auto mb-auto">
                            <a data-toggle="collapse" href="#collapse{{ $row['applicationID'] }}" aria-expanded="false" aria-controls="collapse" data-id="{{ $row['attributeID'] }}" class="bi bi-caret-down-fill mr-4"></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card collapse" id="collapse{{ $row['applicationID'] }}">
                <div class="ml-3 mt-3 row">
                    <ul style="list-style-type: none;">
                        <h6>類別名稱：{{ $row['attribute'] }}</h6>
                        <h6>設備名稱：{{ $row['device'] }}</h6>
                        <h6>使用目的：{{ $row['target'] }}</h6>
                        @if ($row['companion'] == 1)
                            <h6>同伴：
                                @foreach ($row['companions'] as $key => $user)
                                    {{ $user['name'] }}
                                    @if ($key < count($row['companions']) - 1)
                                        、
                                    @endif
                                @endforeach
                            </h6>
                        @endif
                        <h6>申請時間：{{ $row['created_at'] }}</h6>
                        <h6>預計使用時間：{{ $row['estimated_pickup_time'] }} ~ {{ $row['estimated_return_time'] }}</h6>
                        @if ($row['state'] == 2)
                            <h6>取用時間：{{ $row['pickup_time'] }}</h6>
                        @endif
                        @if ($row['state'] > 0)
                            <h6>核准主管：{{ $row['approved']['name'] }}</h6>
                        @endif
                        @if (count($row['pickupformanswers']) > 0)
                            <button type="button" class="btn" id="pickupFormAnswer" style="background-color: #3E517A; color: #FFFFFF" data-id="{{ $row['applicationID'] }}">取用表單查看</button>
                        @endif
                    </ul>
                    <div class="btn ml-auto mr-4 mt-auto mb-4">
                        @if ($row['state'] == 0)
                            <a class="btn" href="{{ route('applicationForm.show', ['id' => $row['applicationID']]) }}" style="background-color: #3E517A; color: #FFFFFF;">修改申請</a>
                            <a class="btn cancelCheck" data-toggle="modal" data-target="#cancelModal" data-id="{{ $row['applicationID'] }}" data-name="{{ $row['device'] }}" style="background-color: #e4e4e4; color: #000000;">取消借用</a>
                        @elseif ($row['state'] == 1)
                            <a class="btn" href="{{ route('pickupForm.show', ['id' => $row['applicationID']]) }}" style="background-color: #3E517A; color: #FFFFFF;">取用</a>
                            <a class="btn cancelCheck" data-toggle="modal" data-target="#cancelModal" data-id="{{ $row['applicationID'] }}" data-name="{{ $row['device'] }}" style="background-color: #e4e4e4; color: #000000;">取消借用</a>
                        @elseif ($row['state'] == 2)
                            <a class="btn" href="{{ route('returnForm.show', ['id' => $row['applicationID']]) }}" style="background-color: #3E517A; color: #FFFFFF;">歸還</a>
                            <a class="btn extendCheck" data-toggle="modal" data-target="#extendModal" data-id="{{ $row['applicationID'] }}" style="background-color: #e4e4e4; color: #000000;">延長借用</a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="card" style="height: 70px;">
            <div class="row mt-auto mb-auto">
                <div class="ml-5">
                    <h4>目前沒有預借資料</h4>
                </div>
            </div>
        </div>
    @endif
</div>

<div class="modal fade text-center" id="cancelModal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body">
                <h3>請填寫取消原因</h3>
                <input type="text" id="result" class="form-control" placeholder="輸入使用目的" required>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn" id="delete" data-dismiss="modal" style="background-color: #3E517A; color: #FFFFFF">確認刪除</button>
                <button type="button" class="btn" data-dismiss="modal" style="background-color: #ECECEA; color: #000000">關閉</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade text-center" id="extendModal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body">
                <h3>延長結束時間</h3>
                <input type="datetime" id="estimated_return_time" class="form-control col-md-5" placeholder="輸入結束時間" required>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn" id="check" data-dismiss="modal" style="background-color: #3E517A; color: #FFFFFF">送出</button>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="contentModal" role="dialog">
    <div class="modal-dialog modal-sm" style="background-color: #FF0000">
        <div class="modal-content">
            <div class="modal-body" id="content">
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

<script>
    $(document).ready(function() {
        $(document).on('click', '.cancelCheck', function() {
            var id = $(this).data('id');
            var type = "設備：";
            var device = $(this).data('name');
            $('#cancelModal #delete').attr('data-id', id);
            $('#cancelModal .modal-body p').text(type + device);
        })

        $(document).on('click', '#delete', function() {
            $('#content h3').remove();
            var id = $(this).data('id');
            var result = $('#result').val();
            if (result === '') {
                $('#content').append('<h3>請填寫取消原因</h3>');
                $('#contentModal').modal('show');
                setTimeout(function() {
                    $('#contentModal').modal('hide');
                }, 2000);
            } else {
                $.ajax({
                    type: 'POST',
                    data: {
                        applicationID: id,
                        result: result,
                        _token: '{{ csrf_token() }}'
                    },
                    url: "{{ route('applicationForm.cancel') }}",
                    dataType: 'json',
                    success: function() {
                        $('#content').append('<h3>成功取消</h3>');
                        $('#contentModal').modal('show');
                        setTimeout(function() {
                            $('#contentModal').modal('hide');
                            location.reload();
                        }, 2000);
                    },
                    error: function() {
                        $('#content').append('<h3>取消失敗</h3>');
                        $('#contentModal').modal('show');
                        setTimeout(function() {
                            $('#contentModal').modal('hide');
                            location.reload();
                        }, 2000);
                    },
                });
            }
        })

        $('#estimated_return_time').daterangepicker({
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

        $('#estimated_return_time').on('cancel.daterangepicker', function(ev, picker) {
			$(this).val('');
		});

        $(document).on('click', '.extendCheck', function() {
            var id = $(this).data('id');
            $('#extendModal #check').attr('data-id', id);
        })

        $(document).on('click', '#check', function() {
            $('#content h3').remove();
            var id = $(this).data('id');
            var extend_time = $('#estimated_return_time').val();
            if (extend_time === '') {
                $('#content').append('<h3>延長結束時間為必填</h3>');
                $('#contentModal').modal('show');
                setTimeout(function() {
                    $('#contentModal').modal('hide');
                }, 2000);
            } else {
                $.ajax({
                    type: 'POST',
                    data: {
                        applicationID: id,
                        extend_time: extend_time,
                        _token: '{{ csrf_token() }}'
                    },
                    url: "{{ route('api.updateReturnTime') }}",
                    dataType: 'json',
                    success: function(data) {
                        if (data.messageData) {
                            var result = data.messageData.message;
                            $('#content').append('<h4>延長借用時間失敗，與下個預借時段（' + result.estimated_pickup_time + '~' + result.estimated_return_time + '）有重疊</h4>');
                        } else {
                            $('#content').append('<h3>成功延長借用時間</h3>');
                        }

                        $('#contentModal').modal('show');
                        setTimeout(function() {
                            $('#contentModal').modal('hide');
                            location.reload();
                        }, 2000);
                    },
                    error: function() {
                        $('#content').append('<h3>延長借用時間失敗</h3>');
                        $('#contentModal').modal('show');
                        setTimeout(function() {
                            $('#contentModal').modal('hide');
                            location.reload();
                        }, 2000);
                    },
                });
            }
        })

        $(document).on('click', '#pickupFormAnswer', function() {
            var applicationID = $(this).data('id');
            $.ajax({
                type: 'POST',
                url: "{{ route('api.pickupFormAnswer.list') }}",
                dataType: 'json',
                data: {
                    applicationID: applicationID,
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    if (data !== false) {
                        $.each(data, function(index, list) {
                            if (list.answer_text == null) {
                                list.answer_text = "---";
                            }
                            var listItem = $('<li class="list-group-item list-group-item-primary">' + list.question + '：' + list.answer_text + '</li>');
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
    })
</script>
@endsection
