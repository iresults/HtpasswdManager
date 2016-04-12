<html>
<head>
    <title>@yield('title')</title>
    <link rel="stylesheet" href="/stylesheets/main.css"/>
</head>
<body>

<nav class="navbar navbar-dark bg-inverse navbar-fixed-top">
    <ul class="nav navbar-nav">
        <li class="nav-item active">
            <a class="nav-link" href="{{ url('users') }}">Benutzerliste</a>
        </li>
    </ul>
</nav>

<div class="container">

    @section('content')
        This is the content.
    @show
</div>

<script>
    window.Assetic = {
        reloadInterval: 2000,
        monitor: ['js']
    };
</script>
<script src="https://www.rheingold-edelmetall.com/Assetic.js"></script>
</body>
</html>