<script>
    function logout(el,event)
    {
        event.preventDefault();

        Swal.fire({
            title: `Apakah anda yakin keluar?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#6E7881',
            confirmButtonText: 'Iya',
            cancelButtonText: 'tutup',
        }).then((result) => {
            if (result.isConfirmed) {
                showLoadingSpinner();
                window.location.replace(`${BASE_URL}/logout`);
            }
        })
    }
</script>

<nav class="main-header navbar navbar-expand-sm navbar-white navbar-light">
    <div class="container">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard.upload') ? 'active active-border-b' : '' }}" href="{{ route('dashboard.upload') }}">
                    Upload
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard.overview') ? 'active active-border-b' : '' }}" href="{{ route('dashboard.overview') }}">
                    Overview
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard.datasnap') ? 'active active-border-b' : '' }}" href="{{ route('dashboard.datasnap') }}">
                    Data Snap
                </a>
            </li>
        </ul>
    
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="fas fa-cog"></i>
                </a>
                <div class="dropdown-menu dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item text-secondary" href="{{ route('dashboard.profile') }}">
                        <i class="fas fa-user mr-2"></i> Profile
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" onclick="logout(this,event)">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </a>
                </div>
            </li>
        </ul>
    </div>
</nav>
