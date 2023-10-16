<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RENXXT</title>
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
    @if ($errors->any())
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
		<form method="POST" action="{{ route('login') }}" class="mt-4">
			@csrf
            <div class="form-group">
				<input type="email" name="email" class="form-control" placeholder="輸入信箱">
			</div>
			<div class="form-group">
				<input type="password" name="password" class="form-control" placeholder="輸入密碼">
			</div>
            <a href="{{ route('forgetPwd') }}" style="font-size: small; margin-left: 58%; color: #3E517A;">忘記密碼</a>
			<div>
				<input type="submit" name="submit" class="btn" value="登入" style="background-color: #3E517A; color: white;">
			</div>
		</form>
	</div>
