@extends('layouts.app')
@section('content')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<style>
    #list {
        border-radius: 5px;
    }

    .list-group-item {
        border: 1px solid #000000;
    }
</style>

<div class="row" style="margin-left: 20px;">
    <h2 style="margin-left: 20px;">職位管理</h2>
    <a class="btn mr-5" data-toggle="modal" data-target="#create" style="margin: auto; background-color: #3E517A; color: #FFFFFF">新增</a>
</div>

<ul class="list-group mt-4 col-md-4 ml-auto mr-auto" id="list">
    @if (count($result) !== 0)
        @foreach ($result as $row)
            <li class="list-group-item row" data-id="{{ $row['positionID'] }}" data-name="{{ $row['position'] }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-arrows-vertical mr-4" viewBox="0 0 16 16">
                    <path d="M8.354 14.854a.5.5 0 0 1-.708 0l-2-2a.5.5 0 0 1 .708-.708L7.5 13.293V2.707L6.354 3.854a.5.5 0 1 1-.708-.708l2-2a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 2.707v10.586l1.146-1.147a.5.5 0 0 1 .708.708l-2 2Z"/>
                </svg>
                {{ $row['position'] }}
            </li>
        @endforeach
    @else
        <li class="list-group-item row">
            暫無職位資料
        </li>
    @endif
</ul>

<div class="modal fade text-center" id="create">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body">
                <h3 class="mb-3">新增職位</h3>
                <input type="text" name="position" class="form-control mt-2" required>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn" id="store" data-dismiss="modal" style="background-color: #3E517A; color: #FFFFFF">新增</button>
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
        $("#list").sortable({
            update: function(event, ui) {
                var sortedItems = [];
                $("#list li").each(function(index, element) {
                    var positionID = $(element).data('id');
                    var position = $(element).data('name');
                    var order = index + 2;
                    sortedItems.push({ positionID: positionID, position: position, order: order });
                });

                $.ajax({
                    type: 'POST',
                    url: "{{ route('positionManagement.changeOrder') }}",
                    dataType: 'json',
                    data: {
                        sortedItems: sortedItems,
                        _token: '{{ csrf_token() }}'
                    },
                });
            }
        });

        $("#list").disableSelection();

        $(document).on('click', '#store', function() {
            $('#successContent h3').remove();
            $('#errorContent h3').remove();
            if ($('input[name="position"]').val() !== '') {
                var position = $('input[name="position"]').val();
                $.ajax({
                    type: 'POST',
                    url: "{{ route('positionManagement.store') }}",
                    dataType: 'json',
                    data: {
                        position: position,
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
                        $('input[name="position"]').val('');
                        $('#errorContent').append('<h3>新增失敗</h3>');
                        $('#errorModal').modal('show');
                        setTimeout(function() {
                            $('#errorModal').modal('hide');
                        }, 2000);
                    },
                });
            } else {
                $('input[name="position"]').val('');
                $('#errorContent').append('<h3>新增失敗</h3>');
                $('#errorModal').modal('show');
                setTimeout(function() {
                    $('#errorModal').modal('hide');
                }, 2000);
            }
        })
    })
</script>
@endsection
