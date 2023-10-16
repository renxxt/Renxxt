@extends('layouts.app')
@section('content')

<h2 style="margin-left: 20px;">基本資料維護</h2>

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

<form method="POST" action="{{ route('profile.update') }}" class="mt-4 text-center">
    @csrf
    <input type="hidden" name="_method" value="PUT">
    @if ($result !== false)
        <div class="form-group">
            <input type="text" name="name" class="form-control" value="{{ $result['name'] }}" placeholder="輸入姓名" required>
        </div>
        <div class="form-group">
            <input type="text" name="uid" class="form-control" value="{{ $result['uid'] }}" disabled>
        </div>
        <div class="form-group">
            <input type="text" name="phonenumber" class="form-control" value="{{ $result['phonenumber'] }}" placeholder="輸入電話" required>
        </div>
        <div class="form-group">
            <input type="text" name="email" class="form-control" value="{{ $result['email'] }}" placeholder="輸入信箱" required>
        </div>
        <div class="form-group">
            <input type="text" name="department" class="form-control" value="{{ $result['department'] }}" disabled>
        </div>
        <div class="form-group">
            <input type="text" name="position" class="form-control" value="{{ $result['position'] }}" disabled>
        </div>
        <div>
            <input name="userID" value="{{ Auth::user()->userID }}" hidden>
            <input type="submit" name="submit" class="btn" value="修改" style="background-color: #3E517A; color: white;">
        </div>
    @endif
</form>
@endsection
