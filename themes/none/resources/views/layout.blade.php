<!doctype html>
<html>

<head>
    @platformHead(before)
    @platformHead(after)
</head>

<body class="{{ theme_class() }}">
    @platformBody(before)
    @yield('content')
    @platformBody(after)
</body>

</html>
