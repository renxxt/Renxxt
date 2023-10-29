<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RENXXT</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <style>
        .navbar {
            background: #3E517A;
            color: #FFFFFF;
        }

        .wrapper {
            display: flex;
            align-items: stretch;
        }

        #sidebar.active {
            margin-left: -180px;
        }

        #sidebar {
            min-width: 180px;
            max-width: 180px;
            min-height: 93vh;
            background: #3E517A;
            transition: all 0.3s;
        }

        #sidebar ul.components {
            padding: 20px 0;
        }

        #sidebar ul li a {
            text-align: center;
            padding: 15px;
            font-size: 1.1em;
            display: block;
            color: #FFFFFF;
            border-bottom: 2px solid #FFFFFF;
        }

        #sidebar ul li a:hover {
            color: #000000;
            background: #fff;
        }

        #sidebar ul li.active>a {
            color: #000000;
            background: #3E517A;
        }

        table {
            font-size: 1.2em;
        }

        form, .alert {
			padding-right: 0px;
			padding-left: 0px;
			margin-right: auto;
			margin-left: auto;
			max-width: 650px;
			max-height: 850px;
		}
    </style>
</head>

<body>
    <script>
        $(document).ready(function() {
            $('#sidebarCollapse').on('click', function() {
                $('#sidebar').toggleClass('active');
            });
        });
    </script>

    <div>
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div>
                <button type="button" id="sidebarCollapse" class="btn">
                    <span class="navbar-toggler-icon" stroke="white"></span>
                </button>
                <a class="navbar-brand" href="" style="margin-left: 60px; color: white;">RenxxT</a>
            </div>
            <div class="collapse navbar-collapse">
				<ul class="nav ml-auto">
					{{-- <li><a>您好, {{ Auth::user()->name }}</a></li> --}}
					<li class="mx-4">
						{{-- <a href="{{ route('logout') }}">登出</a> --}}
					</li>
				</ul>
			</div>
        </nav>
    </div>

    <div class="wrapper">
        <nav id="sidebar" class="active">
            <ul class="components">
                @if(session('order') === 1)
                    <li>
                        <a href="{{ route('userManagement.userList') }}">人員管理</a>
                    </li>
                @endif
                @if(session('order') > 1)
                    <li>
                        <a href="{{ route('profile') }}">基本資料維護</a>
                    </li>
                @endif
                @if(Auth::user()->order > 1 && Auth::user() < $max)
                @endif
            </ul>
        </nav>

        <div class="col-lg-12 mt-4">
            @yield('content')
        </div>
    </div>
