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
        <a href="{{ route('userManagement.createUser') }}" class="btn" style="background-color: #3E517A; color:#FFFFFF;">新增人員</a>
    </div>
    <div class="col-md-4 justify-content-center">
        <form method="POST" action="{{ route('userManagement.userList') }}">
            @csrf
            <select name="departmentID" class="form-control" onchange="this.form.submit()">
                <option value="0" {{ $departmentID == '0' ? 'selected' : '' }} selected>全部部門</option>
                @if ($departments !== false)
                    @foreach ($departments as $row)
                        @php
                            $row = get_object_vars($row);
                        @endphp
                        <option value="{{ $row['departmentID'] }}" {{ $departmentID == $row['departmentID'] ? 'selected' : '' }}>{{ $row['department'] }}</option>
                    @endforeach
                @endif
            </select>
        </form>
    </div>
</div>

<div style="margin-top: 10px;">
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th style="width: 1%;">#</th>
                <th>姓名</th>
                <th>工號</th>
                <th>電話</th>
                <th style="width: 20%;">信箱</th>
                <th>部門</th>
                <th>職稱</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @if ($result !== false)
                @php
                    $count = 0;
                @endphp

                @foreach ($result as $row)
                    @php
                        $count += 1;
                        $row = get_object_vars($row);
                    @endphp
                    <tr>
                        <td>{{ $count }}</td>
                        <td>{{ $row['name'] }}</td>
                        <td>{{ $row['uid'] }}</td>
                        <td>{{ $row['phonenumber'] }}</td>
                        <td>{{ $row['email'] }}</td>
                        <td>{{ $row['department'] }}</td>
                        <td>{{ $row['position'] }}</td>
                        <td>
                            <a href="{{ route('userManagement.editUser', ['userID' => $row['userID']]) }}" class="btn" style="background-color: #3E517A; color:#FFFFFF">修改</a>
                            <a class="btn btn-danger text-white" data-toggle="modal" data-target="#myModal{{ $row['userID'] }}">刪除</a>
                            <div class="modal fade text-center" id="myModal{{ $row['userID'] }}">
                                <div class="modal-dialog modal-sm">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <h3>是否刪除？</h3>
                                            <p>員工：{{ $row['name'] }}</p>
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            <button type="button" class="btn" onclick="delUser('{{ $row['userID'] }}')" data-dismiss="modal" style="background-color: #3E517A; color: #FFFFFF">確認刪除</button>
                                            <button type="button" class="btn" data-dismiss="modal" style="background-color: #ECECEA; color: #000000">關閉</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 刪除成功的 Modal 元件 -->
                            <div class="modal text-center" id="successModal" role="dialog">
                                <div class="modal-dialog modal-sm">
                                    <div class="modal-content"  style="background-color: #3E517A; color: #FFFFFF">
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
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>


<script>
    $(document).ready(function() {
        $('.table').DataTable({
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
			}
        })
    });

    function delUser(userID) {
        $.ajax({
            type: 'POST',
            data: {
                userID: userID,
                _token: '{{ csrf_token() }}'
            },
            url: "{{ route('userManagement.deleteUser') }}",
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
    }
</script>
@endsection
