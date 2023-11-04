@extends('layouts.app')
@section('content')
<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
<style>
    .toggle {
        border-radius: 20rem;
    }
</style>

<h2 style="margin-left: 20px;">{{ $state == 1 ? '取用表單' : '歸還表單' }}</h2>

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

<form method="POST" action="{{ $state == 1 ? route('pickupForm.store') : route('returnForm.store') }}" class="mt-4 text-center">
    @csrf
    @if ($result !== false)
        @foreach ($result as $row)
            <div class="row mt-4">
                @if ($row['required'] == 0)
                    <label class="col-sm-2 col-form-label">
                        <span class="text-danger font-weight-bold">*</span>
                        {{ $row['question'] }}
                    </label>
                @else
                    <label class="col-sm-2 col-form-label">{{ $row['question'] }}</label>
                @endif
                <div class="col-sm-10">
                    <input name="questions[applicationID]" value="{{ $applicationID }}" hidden>
                    <input name="questions[questionID]" value="{{ $row['questionID'] }}" hidden>
                    <input type="text" name="questions[answer_text]" class="form-control" value="{{ old('answer_text') }}" {{ $row['required'] == 0 ? 'required' : ''}}>
                </div>
            </div>
        @endforeach
    @endif
    <div class="mt-4">
        <input name="applicationID" value="{{ $applicationID }}" hidden>
        <input type="submit" name="submit" class="btn" value="送出" style="background-color: #3E517A; color: white;">
    </div>
</form>
@endsection
