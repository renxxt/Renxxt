@extends('layouts.app')
@section('content')
<style>
    .bi {
        color: #000000;
        font-size: 30px;
    }

    .tab-content, .nav-item {
        border: 1px solid #000000;
        border-radius: 5px
    }

    .nav-item>.active {
        border: transparent;
    }
</style>

<h2 style="margin-left: 20px;">預借申請管理</h2>

<ul class="nav nav-tabs nav-fill mt-4">
    <li class="nav-item">
        <a class="nav-link" href="{{ route('applicationManagement.applicationList') }}">預借申請</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('applicationManagement.cancelList') }}">已取消</a>
    </li>
    <li class="nav-item">
        <a class="nav-link active" href="{{ route('applicationManagement.completedList') }}" style="background-color: #3E517A; color: #FFFFFF;">已歸還</a>
    </li>
</ul>

<div class="tab-content">
    @if (count($result) !== 0)
        @foreach ($result as $row)
            <div class="card" style="height: 70px;">
                <div class="row mt-auto mb-auto">
                    <div class="ml-5 row">
                        <h4>{{ $row['uuid'] }}</h4>
                        <h6 class="mx-5 mt-auto mb-auto">預借類別：{{ $row['attribute'] }}</h6>
                        <h6 class="mt-auto mb-auto">預計使用時間：{{ $row['estimated_pickup_time'] }} ~ {{ $row['estimated_return_time'] }}</h6>
                    </div>
                    <div class="ml-auto mr-4">
                        <div class="mt-auto mb-auto">
                            <a data-toggle="collapse" href="#collapse{{ $row['applicationID'] }}" aria-expanded="false" aria-controls="collapse" data-id="{{ $row['attributeID'] }}" class="bi bi-caret-down-fill mr-4"></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card collapse" id="collapse{{ $row['applicationID'] }}">
                <div class="ml-3 mt-3 row">
                    <ul style="list-style-type: none;">
                        <h6>申請人：{{ $row['name'] }}</h6>
                        <h6>單位：{{ $row['department'] }}</h6>
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
                        <h6>取用時間：{{ $row['pickup_time'] }}</h6>
                        <h6>歸還時間：{{ $row['return_time'] }}</h6>
                    </ul>
                    <div class="btn ml-auto mr-4 mt-auto mb-4">
                        @if ($row['pickup_form'] == 1)
                            <button type="button" class="btn" id="pickupFormAnswer" style="background-color: #3E517A; color: #FFFFFF" data-id="{{ $row['applicationID'] }}">取用表單查看</button>
                        @endif
                        @if ($row['return_form'] == 1)
                            <button type="button" class="btn" id="returnFormAnswer" style="background-color: #ECECEA; color: #000000" data-id="{{ $row['applicationID'] }}">歸還表單查看</button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="card" style="height: 70px;">
            <div class="row mt-auto mb-auto">
                <div class="ml-5">
                    <h4>目前沒有預借歸還資料</h4>
                </div>
            </div>
        </div>
    @endif
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

        $(document).on('click', '#returnFormAnswer', function() {
            var applicationID = $(this).data('id');
            $.ajax({
                type: 'POST',
                url: "{{ route('api.returnFormAnswer.list') }}",
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
    })
</script>
@endsection
