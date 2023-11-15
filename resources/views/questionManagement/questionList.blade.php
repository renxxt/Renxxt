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
    <h2 style="margin-left: 20px;">表單問項管理</h2>
    <a class="btn mr-5" data-toggle="modal" data-target="#create" style="margin: auto; background-color: #3E517A; color: #FFFFFF">新增</a>
</div>

<ul class="list-group mt-4 col-md-4 ml-auto mr-auto" id="list">
    @if (count($result) !== 0)
        @foreach ($result as $row)
            <li class="list-group-item">
                <div class="row ml-4">
                    {{ $row['question'] }}
                    <div class="ml-auto mr-4">
                        <a class="bi bi-trash-fill mr-2 deleteCheck" data-toggle="modal" data-target="#deleteModal" data-id="{{ $row['questionID'] }}" data-name="{{ $row['question'] }}"></a>
                        <a class="bi bi-pencil-square mr-2" id="editBtn" data-id="{{ $row['questionID'] }}" data-name="{{ $row['question'] }}"></a>
                    </div>
                </div>
            </li>
        @endforeach
    @else
        <li class="list-group-item row">
            暫無問項資料
        </li>
    @endif
</ul>

<div class="modal fade text-center" id="create">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body">
                <h3 class="mb-3">新增問項</h3>
                <input type="text" name="question" class="form-control mt-2" required>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn" id="store" data-dismiss="modal" style="background-color: #3E517A; color: #FFFFFF">新增</button>
                <button type="button" class="btn" data-dismiss="modal" style="background-color: #ECECEA; color: #000000">關閉</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade text-center" id="deleteModal">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body">
                <h3>是否刪除？</h3>
                <p></p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn" id="delete" data-dismiss="modal" style="background-color: #3E517A; color: #FFFFFF">確認刪除</button>
                <button type="button" class="btn" data-dismiss="modal" style="background-color: #ECECEA; color: #000000">關閉</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade text-center" id="edit">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body" id="editContent">
                <h3 class="mb-3">修改問項</h3>
                <input type="text" id="question" class="form-control mt-2" required>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn" id="update" data-dismiss="modal" style="background-color: #3E517A; color: #FFFFFF">修改</button>
                <button type="button" class="btn" id="close" style="background-color: #ECECEA; color: #000000">關閉</button>
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
            if ($('input[name="question"]').val() !== '') {
                var question = $('input[name="question"]').val();
                $.ajax({
                    type: 'POST',
                    url: "{{ route('questionManagement.store') }}",
                    dataType: 'json',
                    data: {
                        question: question,
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
                $('#create').hide();
                $('#errorContent').append('<h3>欄位必填</h3>');
                $('#errorModal').modal('show');
                setTimeout(function() {
                    $('#errorModal').modal('hide');
                    location.reload();
                }, 2000);
            }
        })

        $(document).on('click', '.deleteCheck', function() {
            var questionID = $(this).data('id');
            var question = $(this).data('name');
            $('#deleteModal #delete').attr('data-id', questionID);
            $('#deleteModal .modal-body p').text(question);
        })

        $(document).on('click', '#delete', function() {
            $('#content h3').remove();
            var questionID = $(this).data('id');
            $.ajax({
                type: 'DELETE',
                data: {
                    questionID: questionID,
                    _token: '{{ csrf_token() }}'
                },
                url: "{{ route('questionManagement.delete') }}",
                dataType: 'json',
                success: function() {
                    $('#edit').hide();
                    $('#successContent').append('<h3>刪除成功</h3>');
                    $('#successModal').modal('show');
                    setTimeout(function() {
                        $('#successModal').modal('hide');
                        location.reload();
                    }, 2000);
                },
                error: function() {
                    $('#edit').hide();
                    $('#errorContent').append('<h3>刪除失敗</h3>');
                    $('#errorModal').modal('show');
                    setTimeout(function() {
                        $('#errorModal').modal('hide');
                        location.reload();
                    }, 2000);
                },
            });
        })

        $(document).on('click', '#editBtn', function() {
            $('#editContent h3').remove();
            var questionID = $(this).data('id');
            var question = $(this).data('name');

            $('#question').before('<h3 class="mb-3">修改 - ' + question + '</h3>');
            $('#edit').modal('show');
            $('#update').attr('data-id', questionID);
        })

        $(document).on('click', '#update', function() {
            $('#content h3').remove();
            if ($('#question').val() !== '') {
                var question = $('#question').val();
                var questionID = $(this).data('id');
                $.ajax({
                    type: 'PUT',
                    url: "{{ route('questionManagement.update') }}",
                    dataType: 'json',
                    data: {
                        questionID: questionID,
                        question: question,
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

        $(document).on('click', '#close', function() {
            $('#edit').modal('hide');
        })
    })
</script>
@endsection
