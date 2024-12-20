@extends('layout.main')
@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-row justify-content-between">
                        <h5>Daftar User</h5>
                        <a data-bs-toggle="modal" class="btn btn-sm btn-primary" data-bs-target="#exampleModal">Add</a>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nama User</th>
                                <th scope="col">Username</th>
                                <th scope="col">Role</th>
                                <th scope="col">Email</th>
                                <th scope="col">action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->username }}</td>
                                    <td>{{ $item->jenis_user->jenis_user }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>
                                        <a href="{{ route('admin.user.edit', $item->id) }}"
                                            class="btn btn-sm btn-warning">edit</a>
                                        <a href="{{ route('admin.user.delete', $item->id) }}"
                                            class="btn btn-sm btn-danger">delete</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-row justify-content-between">
                        <h5>Daftar User</h5>
                        <a data-bs-toggle="modal" class="btn btn-sm btn-primary" data-bs-target="#tambahuser">Add</a>
                    </div>
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($role as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->jenis_user }}</td>
                                    <td>
                                        <a href="{{ route('admin.role.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="{{ route('admin.role.delete', $item->id) }}" class="btn btn-sm btn-primary">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="tambahuser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Role</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.role.store') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="name">Nama Role</label>
                            <input type="text" class="form-control" name="jenis_user" required>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('auth.register') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="name">Nama Lengkap</label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" class="form-control" id="email" name="email">
                        </div>
                        <div class="form-group">
                            <label for="role">Role</label>
                            <select name="role" id="jenisUser_id" class="form-select">
                                <option value="">Pilih Role</option>
                                @foreach ($role as $item)
                                    <option value="{{ $item->id }}">{{ $item->jenis_user }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="text" class="form-control" id="password" name="password">
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" id="regisButton" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

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
                        role: $('#role').val(),
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
                                        // Close the modal
                                        $('#exampleModal').modal('hide');
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
                            Swal.fire({
                                title: "Error",
                                text: 'Registration failed. Please try again.',
                                icon: "error",
                                confirmButtonText: "OK"
                            });
                        }
                    });

                });
            });
        </script>
    @endsection
