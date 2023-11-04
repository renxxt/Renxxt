@extends('layouts.app')
@section('content')
<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
<style>
    .toggle {
        border-radius: 20rem;
    }
</style>

<h2 style="margin-left: 20px;">新增設備</h2>

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

<form method="POST" action="{{ route('serviceManagement.device.update') }}" class="mt-4 text-center">
    @csrf
    <input type="hidden" name="_method" value="PUT">
    @if ($result !== false)
        <div class="row">
            <label class="col-sm-2 col-form-label">設備名字</label>
            <div class="col-sm-10">
                <input type="text" name="name" class="form-control" value="{{ $result['name'] }}" required>
            </div>
        </div>
        <div class="row mt-3">
            <label class="col-sm-2 col-form-label">設備類型</label>
            <div class="col-sm-10">
                <input type="text" name="type" class="form-control" value="{{ $result['type'] }}" required>
            </div>
        </div>
        <div class="row mt-3">
            <label class="col-sm-2 col-form-label">設備類別</label>
            <div class="col-sm-10">
                <select name="attributeID" class="form-control">
                    <option disabled selected>請選擇設備類別</option>
                    @if ($attributes !== false)
                        @foreach ($attributes as $row)
                            <option value="{{ $row['attributeID'] }}" {{ $result['attributeID'] == $row['attributeID'] ? 'selected' : '' }}>{{ $row['name'] }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="row mt-3">
            <label class="col-sm-2 col-form-label">保管人工號</label>
            <div class="col-sm-10">
                <input type="text" name="uid" class="form-control" value="{{ $result['uid'] }}" required>
            </div>
        </div>
        <div class="row mt-3">
            <label class="col-sm-2 col-form-label">擺放地點</label>
            <div class="col-sm-10">
                <input type="text" name="storage_location" class="form-control" value="{{ $result['storage_location'] }}" required>
            </div>
        </div>
        <div class="row mt-3">
            <label class="col-sm-2 col-form-label">預借金額</label>
            <div class="col-sm-10">
                <input type="text" name="price" class="form-control" value="{{ $result['price'] }}" required>
            </div>
        </div>
        <div class="row">
            <label class="col-sm-2 mt-4">可見/隱藏</label>
            <div class="col-sm-1 mt-3">
                <input type="checkbox" name="display" data-toggle="toggle" {{ $result['display'] == 1 ? 'checked' : '' }}>
            </div>
        </div>
        <div class="mt-4">
            <input name="deviceID" value="{{ $result['deviceID'] }}" hidden>
            <input type="submit" name="submit" class="btn" value="修改" style="background-color: #3E517A; color: white;">
        </div>
    @endif
</form>
@endsection
