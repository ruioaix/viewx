<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="icon" href="{{ action('MainController@index') }}/favicon.ico">
    <title>@yield('title')</title>

    <!-- Bootstrap core CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

    @yield('script')

    <!-- Custom styles for this template -->
    <style> 
        body { padding-top: 70px; } 
        :target:before {
            content:"";
            display:block;
            height:60px; /* fixed header height*/
            margin:-60px 0 0; /* negative fixed header height */
        }	
    </style>

</head>
<body>
    <!-- Fixed navbar -->
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="{{ action('MainController@index') }}">Crawl System Info</a></li>
                    <li class="dropdown">
                      <a href="{{ action('LoginController@health') }}" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> Login <span class="caret"></span></a>
                      <ul class="dropdown-menu">
                        <li><a href="{{ action('LoginController@circle') }}">Circle</a></li>
                        <li><a href="{{ action('LoginController@health') }}">Health</a></li>
                      </ul>
                    </li>

                    <li class="dropdown">
                      <a href="{{ action('TaskController@monitor') }}" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> XQ Task<span class="caret"></span></a>
                      <ul class="dropdown-menu">
                        <li><a href="{{ action('TaskController@manage') }}">Task Manage</a></li>
                        <li><a href="{{ action('TaskController@monitor') }}">Task Monitor</a></li>
                        <li><a href="{{ action('TaskController@adjust') }}">XQ Adjust Common</a></li>
                        <li><a href="{{ action('TaskController@cheating') }}">XQ Check Cheating</a></li>
                      </ul>
                    </li>
                    <li class="dropdown">
                      <a href="{{ action('ToolsController@fixparecord') }}" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> Tools<span class="caret"></span></a>
                      <ul class="dropdown-menu">
                        <li><a href="{{ action('ToolsController@fixparecord') }}">fix proxy&account record</a></li>
                        <li><a href="{{ action('AdjustController@monitor') }}">xueqiu adjust data</a></li>
                      </ul>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                      <a href="{{ action('MainController@all') }}" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> RXQ<span class="caret"></span></a>
                      <ul class="dropdown-menu">
                        <li><a href="{{ action('MainController@all') }}">ALL</a></li>
                        <li><a href="{{ action('MainController@start') }}">Start</a></li>
                        <li><a href="{{ action('MainController@artisan') }}">Artisan</a></li>
                      </ul>
                    </li>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </nav>

    <div class="container">
        @yield('content')
    </div> 

</body>
</html>
