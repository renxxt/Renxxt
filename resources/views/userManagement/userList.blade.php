@extends('layouts.app')
@section('content')
<style>
    td  {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    thead {
        position: sticky;
    }
</style>

<h2 style="margin-left: 20px;">人員管理</h2>
<div class="row">
    <div class="col-md-4" style="margin-left: 20px;">
        <a href="{{ route('userManagement.create') }}" class="btn" style="background-color: #3E517A; color:#FFFFFF;">新增人員</a>
    </div>
    <div class="col-md-4 justify-content-center">
        <select name="departmentID" class="form-control" id="departmentID">
            <option value="0" {{ $departmentID == '0' ? 'selected' : '' }} selected>全部部門</option>
            @if ($departments !== false)
                @foreach ($departments as $row)
                    <option value="{{ $row['departmentID'] }}" {{ $departmentID == $row['departmentID'] ? 'selected' : '' }}>{{ $row['department'] }}</option>
                @endforeach
            @endif
        </select>
    </div>
</div>

<div style="margin-top: 10px;">
    <table class="table table-striped table-bordered table-hover" id="table">
        <thead>
            <tr>
                <th style="width: 1%;">#</th>
                <th>id</th>
                <th>姓名</th>
                <th>工號</th>
                <th>電話</th>
                <th style="width: 20%;">信箱</th>
                <th>部門</th>
                <th>職稱</th>
                <th>上級</th>
                <th>操作</th>
            </tr>
        </thead>
    </table>

    <!-- 刪除確認的 Modal -->
    <div class="modal fade text-center" id="myModal">
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

    <!-- 刪除成功的 Modal 元件 -->
    <div class="modal text-center" id="successModal" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content" style="background-color: #3E517A; color: #FFFFFF">
                <div class="modal-body">
                    <h3>刪除成功</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- 刪除失敗的 Modal 元件 -->
    <div class="modal" id="errorModal" role="dialog">
        <div class="modal-dialog modal-sm" style="background-color: #FF0000">
            <div class="modal-content">
                <div class="modal-body">
                    <h3>刪除失敗</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#table').DataTable({
            language: {
				"infoFiltered": "(從 _MAX_ 項結果中過濾)",
				"sInfo": "顯示第 _START_ 至 _END_ 項結果，共 _TOTAL_ 項",
				"sInfoEmpty": "顯示第 0 至 0 項結果，共 0 項",
				"sLengthMenu": "顯示 _MENU_ 項結果",
				"oPaginate": {
					"sFirst": "首頁",
					"sPrevious": "上頁",
					"sNext": "下頁",
					"sLast": "尾頁"
				},
				"emptyTable": "暫無資料"
			},
            columns: [
                {
                    data: null,
                    render: function (data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    data: 'userID',
                    visible: false
                },
                { data: 'name' },
                { data: 'uid' },
                { data: 'phonenumber' },
                { data: 'email' },
                { data: 'department' },
                { data: 'position' },
                { data: 'superior', defaultContent: "無上級" },
                {
                    data: null,
                    render: function (data, type, row, meta) {
                        var userID = row.userID;
                        var name = row.name;
                        var button = `
                            <a href="/userManagement/user/${userID}" class="btn" style="background-color: #3E517A; color:#FFFFFF">修改</a>
                            <a class="btn btn-danger text-white deleteUser" data-toggle="modal" data-target="#myModal" data-user-id="${userID}" data-user-name="${name}">刪除</a>
                        `;
                        return button;
                    }
                },
            ]
        });

        var dataTable = $('#table').DataTable();
        loadData(dataTable);

        function loadData(dataTable) {
            $.ajax({
                type: 'GET',
                url: "{{ route('api.getUser') }}",
                dataType: 'json',
                success: function(data) {
                    dataTable.clear();
                    dataTable.rows.add(data);
                    dataTable.columns.adjust().draw();
                }
            });
        }

        $('#departmentID').change(function() {
            var departmentID = $(this).val();
            var dataTable = $('#table').DataTable();
            $.ajax({
                type: 'GET',
                url: "{{ route('api.getUser') }}",
                dataType: 'json',
                data: { departmentID: departmentID },
                success: function(data) {
                    dataTable.clear();
                    dataTable.rows.add(data);
                    dataTable.columns.adjust().draw();
                }
            });
        });

        $(document).on('click', '.deleteUser', function() {
            var userID = $(this).data('user-id');
            var name = $(this).data('user-name');
            $('#myModal .modal-body p').text("員工：" + name);
            $('#myModal #delete').attr('data-user-id', userID);
        })

        $(document).on('click', '#delete', function() {
            var userID = $(this).attr('data-user-id');
            $.ajax({
                type: 'DELETE',
                data: {
                    userID: userID,
                    _token: '{{ csrf_token() }}'
                },
                url: "{{ route('userManagement.delete') }}",
                dataType: 'json',
                success: function() {
                    $('#successModal').modal('show');
                    setTimeout(function() {
                        $('#successModal').modal('hide');
                        location.reload();
                    }, 2000);
                },
                error: function() {
                    $('#errorModal').modal('show');
                    setTimeout(function() {
                        $('#errorModal').modal('hide');
                        location.reload();
                    }, 2000);
                },
            });
        })
    });
</script>
@endsection
