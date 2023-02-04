<!doctype html>
<html>

<head>
    @platformHead(before)
    @platformHead(after)
</head>

<body>
    @platformBody(before)
    @yield('content')
    @platformBody(after)
</body>

</html>
