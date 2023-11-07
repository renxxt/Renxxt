@extends('layouts.app')
@section('content')
<style>
    .bi {
        color: #000000;
        font-size: 20px;
    }

    .bi-trash-fill {
        color: #FF0000;
    }

    #list {
        border-radius: 5px;
    }

    .list-group-item {
        border: 1px solid #000000;
    }
</style>

<div class="row" style="margin-left: 20px;">
    <h2 style="margin-left: 20px;">部門管理</h2>
    <a class="btn mr-5" data-toggle="modal" data-target="#create" style="margin: auto; background-color: #3E517A; color: #FFFFFF">新增</a>
</div>

<ul class="list-group mt-4 col-md-4 ml-auto mr-auto" id="list">
    @if (count($result) !== 0)
        @foreach ($result as $row)
            <li class="list-group-item">
                <div class="row ml-4">
                    {{ $row['department'] }}
                    <div class="ml-auto mr-4">
                        <a class="bi bi-pencil-square mr-2" id="editBtn" data-id="{{ $row['departmentID'] }}" data-name="{{ $row['department'] }}"></a>
                    </div>
                </div>
            </li>
        @endforeach
    @else
        <li class="list-group-item row">
            暫無部門資料
        </li>
    @endif
</ul>

<div class="modal fade text-center" id="create">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body">
                <h3 class="mb-3">新增部門</h3>
                <input type="text" name="department" class="form-control mt-2" required>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn" id="store" data-dismiss="modal" style="background-color: #3E517A; color: #FFFFFF">新增</button>
                <button type="button" class="btn" data-dismiss="modal" style="background-color: #ECECEA; color: #000000">關閉</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade text-center" id="edit">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body" id="editContent">
                <h3 class="mb-3">修改部門</h3>
                <input type="text" id="department" class="form-control mt-2" required>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn" id="update" data-dismiss="modal" style="background-color: #3E517A; color: #FFFFFF">修改</button>
                <button type="button" class="btn" data-dismiss="modal" style="background-color: #ECECEA; color: #000000">關閉</button>
            </div>
        </div>
    </div>
</div>

<div class="modal text-center" id="successModal" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content" style="background-color: #3E517A; color: #FFFFFF">
            <div class="modal-body" id="successContent">
            </div>
        </div>
    </div>
</div>

<div class="modal" id="errorModal" role="dialog">
    <div class="modal-dialog modal-sm" style="background-color: #FF0000">
        <div class="modal-content">
            <div class="modal-body" id="errorContent">
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $(document).on('click', '#store', function() {
            $('#successContent h3').remove();
            $('#errorContent h3').remove();
            if ($('input[name="department"]').val() !== '') {
                var department = $('input[name="department"]').val();
                $.ajax({
                    type: 'POST',
                    url: "{{ route('departmentManagement.store') }}",
                    dataType: 'json',
                    data: {
                        department: department,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        $('#create').hide();
                        $('#successContent').append('<h3>新增成功</h3>');
                        $('#successModal').modal('show');
                        setTimeout(function() {
                            $('#successModal').modal('hide');
                            location.reload();
                        }, 2000);
                    },
                    error: function() {
                        $('input[name="department"]').val('');
                        $('#create').hide();
                        $('#errorContent').append('<h3>新增失敗</h3>');
                        $('#errorModal').modal('show');
                        setTimeout(function() {
                            $('#errorModal').modal('hide');
                            location.reload();
                        }, 2000);
                    },
                });
            } else {
                $('input[name="department"]').val('');
                $('#create').hide();
                $('#errorContent').append('<h3>欄位必填</h3>');
                $('#errorModal').modal('show');
                setTimeout(function() {
                    $('#errorModal').modal('hide');
                    location.reload();
                }, 2000);
            }
        })

        $(document).on('click', '#editBtn', function() {
            $('#editContent h3').remove();
            var departmentID = $(this).data('id');
            var department = $(this).data('name');

            $('#department').before('<h3 class="mb-3">修改 - ' + department + '</h3>');
            $('#edit').modal('show');
            $('#update').attr('data-id', departmentID);
        })

        $(document).on('click', '#update', function() {
            $('#successContent h3').remove();
            $('#errorContent h3').remove();
            if ($('#department').val() !== '') {
                var department = $('#department').val();
                var departmentID = $(this).data('id');
                $.ajax({
                    type: 'PUT',
                    url: "{{ route('departmentManagement.update') }}",
                    dataType: 'json',
                    data: {
                        departmentID: departmentID,
                        department: department,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        $('#edit').hide();
                        $('#successContent').append('<h3>更新成功</h3>');
                        $('#successModal').modal('show');
                        setTimeout(function() {
                            $('#successModal').modal('hide');
                            location.reload();
                        }, 2000);
                    },
                    error: function() {
                        $('#edit').hide();
                        $('#errorContent').append('<h3>更新失敗</h3>');
                        $('#errorModal').modal('show');
                        setTimeout(function() {
                            $('#errorModal').modal('hide');
                            location.reload();
                        }, 2000);
                    },
                });
            } else {
                $('#edit').hide();
                $('#errorContent').append('<h3>欄位必填</h3>');
                $('#errorModal').modal('show');
                setTimeout(function() {
                    $('#errorModal').modal('hide');
                    location.reload();
                }, 2000);
            }
        })
    })
</script>
@endsection
