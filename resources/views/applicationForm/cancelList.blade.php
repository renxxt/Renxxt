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

<h2 style="margin-left: 20px;">我的申請列表</h2>

<ul class="nav nav-tabs nav-fill mt-4">
    <li class="nav-item">
        <a class="nav-link" href="{{ route('applicationForm.applicationList') }}">預借申請</a>
    </li>
    <li class="nav-item">
        <a class="nav-link active" href="{{ route('applicationForm.cancelList') }}" style="background-color: #3E517A; color: #FFFFFF;">已取消</a>
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
                <div class="ml-3 mt-3">
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
                        <h6>取消預借時間：{{ $row['cancel']['created_at'] }}</h6>
                        <h6>取消預借原因：{{ $row['cancel']['result'] }}</h6>
                    </ul>
                </div>
            </div>
        @endforeach
    @else
        <div class="card" style="height: 70px;">
            <div class="row mt-auto mb-auto">
                <div class="ml-5">
                    <h4>目前沒有預借取消資料</h4>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
