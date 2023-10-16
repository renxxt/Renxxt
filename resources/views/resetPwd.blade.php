<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>重置密碼</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #ececea;
        }

        .card {
			padding-right: 0px;
			padding-left: 0px;
			margin-right: auto;
			margin-left: auto;
		}

        .form-group>input {
			width: 70%;
            padding: 5px 15px;
			margin-right: auto;
			margin-left: auto;
		}

        .alert {
			padding-right: 0px;
			padding-left: 0px;
			margin-right: auto;
			margin-left: auto;
		}
    </style>
</head>

<body>
    <h3 class="text-center mt-4">RENxxT</h3>
    @if($errors->any())
        <div class="alert alert-dismissible alert-danger col-md-4" role="alert">
            @foreach($errors->all() as $error)
                <button type="button" class="close" data-dismiss="alert">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <ul>{{ $error }}</ul>
            @endforeach
        </div>
    @endif
    @if (session()->has('messageData'))
        @foreach (session('messageData') as $messageData)
            <div class="alert alert-dismissible alert-{{$messageData[ 'type' ]}} col-md-4" role="alert">
                <button type="button" class="close" data-dismiss="alert">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <ul>{{ $messageData['message'] }}</ul>
            </div>
        @endforeach
    @endif
    <div class="card text-center col-md-4">
        <form method="POST" action="{{ route('resetPwd') }}" class="mt-4">
            @csrf
            <div class="form-group mt-4">
                <input type="password" name="password" class="form-control" placeholder="輸入新密碼" required>
            </div>
            <div class="form-group mt-4">
                <input type="password" name="password_confirmation" class="form-control" placeholder="新密碼確認" required>
            </div>
            <a href="{{ route('login') }}" style="font-size: small; margin-left: 58%; color: #3E517A;">返回登入</a>
            <div>
                <input name="id" value="{{ $id }}" hidden>
                <input name="hash" value="{{ $hash }}" hidden>
				<input type="submit" name="submit" class="btn" value="送出" style="background-color: #3E517A; color: white;">
			</div>
        </form>
    </div>
</body>

</html>
