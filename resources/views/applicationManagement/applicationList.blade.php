@extends('layouts.app')
@section('content')
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

<h2 style="margin-left: 20px;">預借申請管理</h2>

<ul class="nav nav-tabs nav-fill mt-4">
    <li class="nav-item">
        <a class="nav-link active" href="{{ route('applicationManagement.applicationList') }}" style="background-color: #3E517A; color: #FFFFFF;">預借申請</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('applicationManagement.cancelList') }}">已取消</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('applicationManagement.completedList') }}">已歸還</a>
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
                        @if ($row['state'] == 2)
                            <h6>取用時間：{{ $row['pickup_time'] }}</h6>
                        @endif
                        @if ($row['state'] > 0)
                            <h6>核准時間：{{ $row['approved']['created_at'] }}</h6>
                        @endif
                        @if (count($row['pickupformanswers']) > 0)
                            <button type="button" class="btn" id="pickupFormAnswer" style="background-color: #3E517A; color: #FFFFFF" data-id="{{ $row['applicationID'] }}">取用表單查看</button>
                        @endif
                    </ul>
                    <div class="btn ml-auto mr-4 mt-auto mb-4">
                        @if ($row['state'] == 0)
                            <a class="btn" id="approve" data-id="{{ $row['applicationID'] }}" style="background-color: #3E517A; color: #FFFFFF;">核准</a>
                        @elseif ($row['state'] == 1)
                        @elseif ($row['state'] == 2)
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
        $(document).on('click', '#approve', function() {
            $('#content h3').remove();
            var id = $(this).data('id');
            $.ajax({
                type: 'POST',
                data: {
                    applicationID: id,
                    _token: '{{ csrf_token() }}'
                },
                url: "{{ route('applicationManagement.approve') }}",
                dataType: 'json',
                success: function() {
                    $('#content').append('<h3>審核成功</h3>');
                    $('#contentModal').modal('show');
                    setTimeout(function() {
                        $('#contentModal').modal('hide');
                        location.reload();
                    }, 2000);
                },
                error: function() {
                    $('#content').append('<h3>審核失敗</h3>');
                    $('#contentModal').modal('show');
                    setTimeout(function() {
                        $('#contentModal').modal('hide');
                        location.reload();
                    }, 2000);
                },
            });
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
