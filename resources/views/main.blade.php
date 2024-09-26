<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="{{ asset('css/custom.css')}}">

</head>
<body>
@php
    $user = Auth::user();
    $dashboardRoute = 'dashboard'; // Default route if no role found

    if ($user->hasRole('Administrator')) {
        $dashboardRoute = 'administrator.dashboard';
    } elseif ($user->hasRole('Teacher')) {
        $dashboardRoute = 'teacher.dashboard';
    } elseif ($user->hasRole('Student')) {
        $dashboardRoute = 'student.dashboard';
    } elseif ($user->hasRole('Parent')) {
        $dashboardRoute = 'parent.dashboard';
    }
@endphp
    <nav class="navbar navbar-light navbar-expand-lg  header-login">
        <div class="container">
            <a class="navbar-brand mr-auto" href="#"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">

                        <a class="nav-link" href="{{ route('logout') }}">Logout</a>
            </div>
        </div>
    </nav>
    <div class="container-fluid">
    <div class="row flex-nowrap">
        <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 bg-dark">
            <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
            <div class="dropdown pb-4">
                    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
            
                        <div class="rounded-circle" id="divProfile"> {{ substr(strtoupper($user->name), 0, 1) }}</div>
                        <span class="d-none d-sm-inline mx-1"> {{ strtoupper($user->name) }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                        <li><a class="dropdown-item" href="{{ route('profile') }}">Profile</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="{{ route('logout') }}">Sign out</a></li>
                    </ul>
                </div>
                <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start" id="menu">
                    <li>
                        <a href="{{ route($dashboardRoute) }}" class="nav-link px-0 align-middle">
                            <i class="fs-4 bi-speedometer2"></i> <span class="ms-1 d-none d-sm-inline">Dashboard</span> </a>
                    </li>
                    @if ($user->hasRole('Administrator')) 
                    <li>
                        <a href="{{ route('userList') }}" class="nav-link px-0 align-middle">
                            <i class="fs-4 bi-table"></i> <span class="ms-1 d-none d-sm-inline">User List</span></a>
                    </li>
                    @endif
                    <li>
                        <a href="{{ route('profile') }}" class="nav-link px-0 align-middle">
                            <i class="fs-4 bi-table"></i> <span class="ms-1 d-none d-sm-inline">Profile</span></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col py-3 container">
        @yield('content')
        </div>
    </div>
</div> 
</body>
</html>
