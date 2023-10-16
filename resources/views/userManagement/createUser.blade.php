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

<form method="POST" action="{{ route('userManagement.store') }}" class="mt-4 text-center">
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
        <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="輸入信箱" required>
    </div>
    <div class="form-group">
        <select name="department" class="form-control" id="departmentList">
            <option disabled selected>請選擇部門</option>
            @if ($departments !== false)
                @foreach ($departments as $row)
                    <option value="{{ $row['departmentID'] }}" {{ old('department') == $row['departmentID'] ? 'selected' : '' }}>{{ $row['department'] }}</option>
                @endforeach
            @endif
        </select>
    </div>
    <div class="form-group">
        <select name="position" class="form-control" id="positionList">
            <option disabled selected>請選擇職位</option>
            @if ($positions !== false)
                @foreach ($positions as $row)
                    <option value="{{ $row['positionID'] }}" {{ old('position') == $row['positionID'] ? 'selected' : '' }}>{{ $row['position'] }}</option>
                @endforeach
            @endif
        </select>
    </div>
    <div class="form-group">
        <select name="superior" class="form-control" id="superiorList">
            <option disabled selected>請選擇上級</option>
        </select>
    </div>
    <div>
        <input type="submit" name="submit" class="btn" value="新增" style="background-color: #3E517A; color: white;">
    </div>
</form>

<script>
    $(document).ready(function() {
        $('#positionList').change(function() {
            var positionID = $(this).val();
            superiorList(positionID);
        })

        var positionID = $('#positionList').val();
        if (positionID) {
            superiorList(positionID);
        }

        function superiorList(positionID) {
            $.ajax({
                type: 'GET',
                url: "{{ route('api.getUser') }}",
                dataType: 'json',
                data: { positionID: positionID },
                success: function(data) {
                    var oldSuperior = {{ old('superior')===null ? 'null':old('superior') }};
                    if (data !== false) {
                        $.each(data, function(index, list) {
                            $("#superiorList").append(
                                $("<option></option>")
                                    .attr("value", list.userID)
                                    .prop("selected", (oldSuperior != null && oldSuperior == list.userID))
                                    .text(list.name + " - " + list.position)
                            );
                        });
                    }
                }
            });
        }
    });
</script>
@endsection
