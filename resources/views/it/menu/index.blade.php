@extends('layout.main')
@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h5>Akses Menu</h5>
                        <a data-bs-toggle="modal" data-bs-target="#addMenuAccess" class="btn btn-sm btn-primary">Add</a>
                    </div>
                    <div class="accordion" id="jenisUserAccordion">
                        @foreach ($groupedMenus as $jenisUser => $menus)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading-{{ $loop->index }}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse-{{ $loop->index }}" aria-expanded="false"
                                        aria-controls="collapse-{{ $loop->index }}">
                                        Menu {{ ucfirst($jenisUser) }}
                                    </button>
                                </h2>
                                <div id="collapse-{{ $loop->index }}" class="accordion-collapse collapse"
                                    aria-labelledby="heading-{{ $loop->index }}" data-bs-parent="#jenisUserAccordion">
                                    <div class="accordion-body">
                                        <ul class="list-unstyled">
                                            @foreach ($menus as $menuUser)
                                                <li class="mb-2">
                                                    <strong>Name:</strong> {{ $menuUser->menu->menu_name }} <br>
                                                    <strong>Link:</strong> <a>{{ $menuUser->menu->menu_link }}</a> <br>
                                                    <strong>Icon:</strong> {{ $menuUser->menu->menu_icon }}"> <br>
                                                    <a href="" class="btn btn-sm btn-danger mt-2">Remove Access</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="addMenuAccess" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Add Access</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.aksesMenu.store') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="">Jenis User</label>
                                <select name="jenisUser_id" class="form-select" id="jenisUser_id">
                                    <option value="">pilih user</option>
                                    @foreach ($jenis_user as $item)
                                        <option value="{{ $item->id }}">{{ $item->jenis_user }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Berikan akses menu ke</label>
                                <select name="menu_id" class="form-select" id="menu_id">
                                    <option value="">Pilih menu</option>
                                    @foreach ($allMenu as $menuUser)
                                        <option value="{{ $menuUser->id }}">{{ $menuUser->menu_name }}</option>
                                    @endforeach
                                </select>
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


        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-row justify-content-between">
                        <h5>Daftar Menu</h5>
                        <a data-bs-toggle="modal" class="btn btn-sm btn-primary" data-bs-target="#staticBackdrop">Add</a>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nama Menu</th>
                                <th scope="col">Link</th>
                                <th scope="col">Icon</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($allMenu as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->menu_name }}</td>
                                    <td>{{ $item->menu_link }}</td>
                                    <td>
                                        <i class="{{ $item->menu_icon }}"></i>
                                    </td>
                                    <td>
                                        <a href="" data-bs-toggle="modal" class="btn btn-sm btn-primary"
                                            data-bs-target="#staticBackdrop{{ $item->id }}">Edit</a>
                                        <form action="{{ route('admin.menu.delete', $item->id) }}" method="POST">
                                            @csrf
                                            <input type="submit" class="btn btn-sm btn-danger" value="delete">
                                        </form>
                                    </td>
                                </tr>

                                <!-- Edit Menu Modal -->
                                <div class="modal fade" id="staticBackdrop{{ $item->id }}" data-bs-backdrop="static"
                                    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="editModalLabel{{ $item->id }}">Edit
                                                    Menu</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- AJAX Update Menu Form -->
                                                <form id="editMenuForm-{{ $item->id }}" method="POST">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label for="menu_name_{{ $item->id }}">Nama Menu</label>
                                                        <input type="text" class="form-control"
                                                            id="menu_name_{{ $item->id }}" name="menu_name"
                                                            value="{{ $item->menu_name }}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="menu_link_{{ $item->id }}">Link Menu</label>
                                                        <input type="text" class="form-control"
                                                            id="menu_link_{{ $item->id }}" name="menu_link"
                                                            value="{{ $item->menu_link }}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="menu_icon_{{ $item->id }}">Icon Menu</label>
                                                        <input type="text" class="form-control"
                                                            id="menu_icon_{{ $item->id }}" name="menu_icon"
                                                            value="{{ $item->menu_icon }}">
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary update-menu"
                                                    data-id="{{ $item->id }}">Save changes</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Menu</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addMenu" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="">Nama Menu</label>
                            <input type="text" class="form-control" name="menu_name" placeholder="blablabla">
                        </div>
                        <div class="form-group">
                            <label for="">Link Menu</label>
                            <input type="text" class="form-control" name="menu_link" placeholder="/blabl/abla">
                        </div>
                        <div class="form-group">
                            <label for="">Icon Menu</label>
                            <input type="text" class="form-control" name="menu_icon" placeholder="<i class='fa-contoh fa-contoh'></i>">
                        </div>
                        <p class="mt-3 fw-bold">Icon menu bisa didapat di <a target="_blank" href="https://fontawesome.com/search?o=r&m=free">sini</a></p>
                </div>
                <div class="modal-footer text-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- add menu --}}
    <script>
        $(document).ready(function() {
            $('#addMenu').on('submit', function(e) {
                e.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    url: '{{ route('admin.menu.create') }}',
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#staticBackdrop').modal('hide');
                        Swal.fire({
                            title: "Menu Added",
                            icon: "success",
                            confirmButtonText: "OK" // Display OK button
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload(); // Refresh after clicking OK
                            }
                        });
                    },
                    error: function(error) {
                        Swal.fire({
                            title: "Error",
                            text: "Failed to add the menu",
                            icon: "error",
                            confirmButtonText: "OK"
                        });
                    }
                });
            });
        });
    </script>

    {{-- edit menu --}}
    <script>
        $(document).ready(function() {
            // Update Menu using AJAX
            $('.update-menu').click(function() {
                var id = $(this).data('id');
                var menu_name = $('#menu_name_' + id).val();
                var menu_link = $('#menu_link_' + id).val();
                var menu_icon = $('#menu_icon_' + id).val();

                $.ajax({
                    url: 'AksesMenu/update/' + id,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        menu_name: menu_name,
                        menu_link: menu_link,
                        menu_icon: menu_icon
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            // Update table row data without refreshing
                            $('#menu-' + id).find('td:nth-child(2)').text(menu_name);
                            $('#menu-' + id).find('td:nth-child(3)').text(menu_link);
                            $('#menu-' + id).find('td:nth-child(4) i').attr('class', menu_icon);
                            $('#staticBackdrop' + id).modal('hide'); // Close modal
                            Swal.fire({
                                title: "Success",
                                text: "Edit Berhasil",
                                icon: "success",
                                confirmButtonColor: "#3085d6",
                                confirmButtonText: "Ok"
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = "/admin/AksesMenu";
                                }
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: "Error",
                            text: response.message,
                            icon: "error",
                            confirmButtonText: "OK"
                        });
                    }
                });
            });
        });
    </script>
@endsection
