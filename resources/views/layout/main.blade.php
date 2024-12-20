<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pemograman Basis Data</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logos/logounairbiru.png')}}" />
    <link rel="stylesheet" href="{{ asset('assets/css/styles.min.css')}}" />
    <link src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"></link>
</head>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <!-- Sidebar Start -->
            @include('layout.sidebar')
        <!--  Sidebar End -->
        <!--  Main wrapper -->
        <div class="body-wrapper">
            <!--  Header Start -->
            @include('layout.navbar')
            <!--  Header End -->
            <div class="container-fluid">

            @yield('content')

            </div>
        </div>
    </div>
    @include('layout.js')
</body>

</html>
