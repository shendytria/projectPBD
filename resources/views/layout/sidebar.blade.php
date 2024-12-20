<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="{{ route('admin.dashboard') }}" class="text-nowrap logo-img mt-3">
                <img src="{{ asset('assets/images/logos/Logo-Branding-SMART-UNIVERSITY (1).png')}}" width="150" alt="" />
            </a>
            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-8"></i>
            </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Home</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('admin.dashboard') }}" aria-expanded="false">
                        <span>
                            <i class="fa fa-dashboard"></i>
                        </span>
                        <span class="hide-menu">Stock Barang</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">MASTER DATA</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="/roles" aria-expanded="false">
                        <span>
                            <i class="fa fa-lock"></i>
                        </span>
                        <span class="hide-menu">Role</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="/user" aria-expanded="false">
                        <span>
                            <i class="fa fa-users"></i>
                        </span>
                        <span class="hide-menu">User</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="/satuan" aria-expanded="false">
                        <span>
                            <i class="fa fa-file-alt"></i>
                        </span>
                        <span class="hide-menu">Satuan</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="/barang" aria-expanded="false">
                        <span>
                            <i class="fa fa-cube"></i>
                        </span>
                        <span class="hide-menu">Barang</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="/vendor" aria-expanded="false">
                        <span>
                            <i class="fa fa-truck"></i>
                        </span>
                        <span class="hide-menu">Vendor</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ta ta-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">TRANSAKSI</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="/pengadaan" aria-expanded="false">
                        <span>
                            <i class="fa fa-box"></i>
                        </span>
                        <span class="hide-menu">Pengadaan</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="/penerimaan" aria-expanded="false">
                        <span>
                            <i class="fa fa-gift"></i>
                        </span>
                        <span class="hide-menu">Penerimaan</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="/penjualan" aria-expanded="false">
                        <span>
                            <i class="fa fa-shopping-cart"></i>
                        </span>
                        <span class="hide-menu">Penjualan</span>
                    </a>
                </li>
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">Extra</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="/margin-penjualan/create" aria-expanded="false">
                        <span>
                            <i class="fas fa-dollar-sign"></i>
                        </span>
                        <span class="hide-menu">Margin Penjualan</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link" href="/retur" aria-expanded="false">
                        <span>
                            <i class="fas fa-undo-alt"></i>
                        </span>
                        <span class="hide-menu">Histori Pengembalian</span>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
