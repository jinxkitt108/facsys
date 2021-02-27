
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>CPSU-FMS</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->

  <meta name="csrf-token" content="{{ csrf_token() }}">
 <link rel="stylesheet" href="/css/app.css">
</head>
<body class="hold-transition sidebar-mini">
    <div id="app"></div>
<!-- ./wrapper -->

@auth
<script>
    window.user = @json(auth()->user())
</script>
@endauth
<script src="/js/app.js"></script>
</body>
</html>
