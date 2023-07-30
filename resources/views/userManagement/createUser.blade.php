@extends('layouts.app')
@section('content')

<h2 style="margin-left: 20px;">新增人員</h2>

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

<form method="POST" action="{{ route('userManagement.createUser') }}" class="mt-4 text-center">
    @csrf
    <div class="form-group">
        <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="輸入姓名" required>
    </div>
    <div class="form-group">
        <input type="text" name="uid" class="form-control" value="{{ old('uid') }}" placeholder="輸入工號" required>
    </div>
    <div class="form-group">
        <input type="text" name="phonenumber" class="form-control" value="{{ old('phonenumber') }}" placeholder="輸入電話" required>
    </div>
    <div class="form-group">
        <input type="text" name="email" class="form-control" value="{{ old('email') }}" placeholder="輸入信箱" required>
    </div>
    <div class="form-group">
        <select name="department" class="form-control">
            <option disabled selected>請選擇部門</option>
            @if ($departments !== false)
                @foreach ($departments as $row)
                    @php
                        $dep = get_object_vars($row);
                    @endphp
                    <option value="{{ $dep['departmentID'] }}" {{ old('department') == $dep['departmentID'] ? 'selected' : '' }}>{{ $dep['department'] }}</option>
                @endforeach
            @endif
        </select>
    </div>
    <div class="form-group">
        <select name="position" class="form-control">
            <option disabled selected>請選擇職位</option>
            @if ($positions !== false)
                @foreach ($positions as $row)
                    @php
                        $pos = get_object_vars($row);
                    @endphp
                    <option value="{{ $pos['positionID'] }}" {{ old('position') == $pos['positionID'] ? 'selected' : '' }}>{{ $pos['position'] }}</option>
                @endforeach
            @endif
        </select>
    </div>
    <div>
        <input type="submit" name="submit" class="btn" value="新增" style="background-color: #3E517A; color: white;">
    </div>
</form>
@endsection
