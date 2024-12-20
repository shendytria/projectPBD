{{-- <!--
=========================================================
* Material Dashboard 2 - v3.0.0
=========================================================

* Product Page: https://www.creative-tim.com/product/material-dashboard
* Copyright 2021 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="assets/img/favicon.png">
    <title>
        Material Dashboard 2 by Creative Tim
    </title>
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css"
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
    <!-- Nucleo Icons -->
    <link href="assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <!-- CSS Files -->
    <link id="pagestyle" href="assets/css/material-dashboard.css?v=3.0.0" rel="stylesheet" />
</head>

<body class="">
    <main class="main-content  mt-0">
        <section>
            <div class="page-header min-vh-100">
                <div class="container">
                    <div class="row">
                        <div
                            class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 start-0 text-center justify-content-center flex-column">
                            <div class="position-relative bg-gradient-dark h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center"
                                style="background-image: url('assets/img/illustrations/illustration-signup.jpg'); background-size: cover;">
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column ms-auto me-auto ms-lg-auto me-lg-5">
                            <div class="card card-plain">
                                <div class="card-header">
                                    <h4 class="font-weight-bolder">Sign Up</h4>
                                    <p class="mb-0">Enter your email and password to register</p>
                                </div>
                                <div class="card-body">
                                    @if (session('error'))
                                        <div class="alert alert-warning">
                                            <p>{{ session('error') }}</p>
                                        </div>
                                    @endif
                                    @foreach ($errors->all() as $error)
                                        <div class="alert alert-warning">
                                            <p class="text-white">{{ $error }}</p>
                                        </div>
                                    @endforeach
                                    <form action="{{ route('auth.register') }}" method="POST">
                                        @csrf
                                        <div class="input-group input-group-outline mb-3">
                                            <label class="form-label">Full Name</label>
                                            <input type="text" name="name" class="form-control">
                                        </div>
                                        <div class="input-group input-group-outline mb-3">
                                            <label class="form-label">Username</label>
                                            <input type="text" name="username" class="form-control">
                                        </div>
                                        <div class="input-group input-group-outline mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control">
                                        </div>
                                        <div class="input-group input-group-outline mb-3">
                                            <label class="form-label">No HP</label>
                                            <input type="password" name="no_hp" class="form-control">
                                        </div>
                                        <div class="input-group input-group-outline mb-3">
                                            <label class="form-label">Password</label>
                                            <input type="password" name="password" class="form-control">
                                        </div>
                                        <div class="text-center">
                                            <button type="submit"
                                                class="btn btn-lg bg-gradient-dark btn-lg w-100 mt-4 mb-0">Sign
                                                Up</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                    <p class="mb-2 text-sm mx-auto">
                                        Already have an account?
                                        <a href="{{ route('indexlogin') }}"
                                            class="text-dark text-gradient font-weight-bold">Sign in</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <!--   Core JS Files   -->
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>
    <script src="assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="assets/js/material-dashboard.min.js?v=3.0.0"></script>
</body>

</html> --}}


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pemograman Basis Data</title>
    <link rel="shortcut icon" type="image/png" href="assets/images/logos/logounairbiru.png" />
    <link rel="stylesheet" href="assets/css/styles.min.css" />
</head>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <div
            class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
            <div class="d-flex align-items-center justify-content-center w-100">
                <div class="row justify-content-center w-100">
                    <div class="col-md-8 col-lg-6 col-xxl-3">
                        <div class="card mb-0">
                            <div class="card-body">
                                <a href="./index.html" class="text-nowrap logo-img text-center d-block py-3 w-100">
                                    <img src="{{ asset('assets/images/logos/Logo-Branding-SMART-UNIVERSITY (1).png') }}" width="180" alt="">
                                </a>
                                <form id="formRegister" method="POST">
                                    <div class="mb-3">
                                        <label for="exampleInputtext1" class="form-label">Name</label>
                                        <input type="text" name="name" id="name" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label for="" class="form-label">Username</label>
                                        <input type="text" name="username" id="username" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label for="" class="form-label">Email</label>
                                        <input type="text" name="email" id="email" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label for="" class="form-label">No HP</label>
                                        <input type="number" name="no_hp" id="no_hp" class="form-control">
                                    </div>
                                    <div class="mb-4">
                                        <label for="exampleInputPassword1" class="form-label">Password</label>
                                        <input type="password" name="password" id="password" class="form-control"
                                            id="exampleInputPassword1">
                                    </div>
                                    <button id="regisButton" class="btn btn-dark w-100 py-8 fs-4 mb-4 rounded-2">Sign
                                        Up</button>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <p class="fs-4 mb-0 fw-bold">Already have an Account?</p>
                                        <a class="text-dark fw-bold ms-2" href="./authentication-login.html">Sign
                                            In</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            $(document).ready(function() {
                $('#regisButton').click(function(e) {
                    e.preventDefault();

                    // Prepare the data
                    var formData = {
                        name: $('#name').val(),
                        username: $('#username').val(),
                        email: $('#email').val(),
                        no_hp: $('#no_hp').val(),
                        password: $('#password').val(),
                        _token: '{{ csrf_token() }}'
                    };

                    $.ajax({
                        url: '{{ route('auth.register') }}',
                        method: 'POST',
                        data: formData,
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    title: "Success",
                                    text: "Registrasi Berhasil",
                                    icon: "success",
                                    confirmButtonColor: "#3085d6",
                                    confirmButtonText: "Login"
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = "/";
                                    }
                                });
                            } else {
                                Swal.fire({
                                    title: "Error",
                                    text: response.message,
                                    icon: "error",
                                    confirmButtonText: "OK"
                                });
                            }
                        },
                        error: function(xhr) {
                            // Handle error here
                            Swal.fire({
                                title: "Error",
                                text: 'Registration failed. Please try again.',
                                icon: "error",
                                confirmButtonText: "OK"
                            });
                            console.log(xhr.responseText);  // Log the actual error response
                        }
                    });

                });
            });
        </script>

</body>

</html>
