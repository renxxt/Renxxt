@extends('layouts.app')
@section('content')

<h2 style="margin-left: 20px;">修改人員</h2>

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

<form method="POST" action="{{ route('userManagement.updateUser') }}" class="mt-4 text-center">
    @csrf
    @if ($result !== false)
        @foreach ($result as $row)
            @php
                $row = get_object_vars($row);
            @endphp
            <div class="form-group">
                <input type="text" name="name" class="form-control" value="{{ $row['name'] }}" placeholder="輸入姓名" required>
            </div>
            <div class="form-group">
                <input type="text" name="uid" class="form-control" value="{{ $row['uid'] }}" placeholder="輸入工號" disabled>
            </div>
            <div class="form-group">
                <input type="text" name="phonenumber" class="form-control" value="{{ $row['phonenumber'] }}" placeholder="輸入電話" required>
            </div>
            <div class="form-group">
                <input type="text" name="email" class="form-control" value="{{ $row['email'] }}" placeholder="輸入信箱" required>
            </div>
            <div class="form-group">
                <select name="department" class="form-control">
                    <option disabled>請選擇部門</option>
                    @if ($departments !== false)
                        @foreach ($departments as $dep)
                            @php
                                $dep = get_object_vars($dep);
                            @endphp
                            <option value="{{ $dep['departmentID'] }}"  {{ $row['departmentID'] == $dep['departmentID'] ? 'selected' : '' }}>{{ $dep['department'] }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="form-group">
                <select name="position" class="form-control">
                    <option disabled>請選擇職位</option>
                    @if ($positions !== false)
                        @foreach ($positions as $pos)
                            @php
                                $pos = get_object_vars($pos);
                            @endphp
                            <option value="{{ $pos['positionID'] }}" {{ $row['positionID'] == $pos['positionID'] ? 'selected' : '' }}>{{ $pos['position'] }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div>
                <input name="userID" value="{{ $row['userID'] }}" hidden>
                <input type="submit" name="submit" class="btn" value="修改" style="background-color: #3E517A; color: white;">
            </div>
        @endforeach
    @endif
</form>
@endsection
