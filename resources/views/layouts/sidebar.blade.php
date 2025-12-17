
{{-- @extends('admin') --}}
@section('sidebar')
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-nav">
            <div class="nav-section">Main</div>

            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="nav-icon bi bi-speedometer2"></i>
                <span class="nav-label">Dashboard</span>
            </a>

            <a href="{{ route('admin.user.index') }}" class="nav-item {{ request()->routeIs('admin.user.*') ? 'active' : '' }}">
                <i class="nav-icon bi bi-person-circle"></i>
                <span class="nav-label">Users</span>
            </a>

            <a href="{{ route('admin.interest.index') }}"
            class="nav-item {{ request()->routeIs('admin.interest.*') ? 'active' : '' }}">
               <i class="nav-icon bi bi-heart-fill"></i>
                <span class="nav-label">Interest</span>
            </a>




            <div class="nav-divider"></div>
            <form method="POST" action="{{ route('admin.logout') }}" class="nav-item">
                @csrf
                <button type="submit" class="btn btn-link nav-link p-0 m-0">
                    <i class="nav-icon bi bi-box-arrow-right"></i>
                    <span class="nav-label">Logout</span>
                </button>
            </form>

        </div>
    </aside>
@endsection
