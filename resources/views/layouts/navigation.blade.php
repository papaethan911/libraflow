<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
	<div class="container">
		<a class="navbar-brand" href="{{ route('dashboard') }}">LibraFlow</a>
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
                        </button>
		<div class="collapse navbar-collapse" id="mainNavbar">
			<ul class="navbar-nav me-auto mb-2 mb-lg-0">
				<li class="nav-item">
					<a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
				</li>
				<li class="nav-item">
					<a class="nav-link {{ request()->routeIs('books.*') ? 'active' : '' }}" href="{{ route('books.index') }}">Books</a>
				</li>
            @if(!auth()->user()->isAdmin())
				<li class="nav-item">
					<a class="nav-link {{ request()->routeIs('borrowings.self_checkout') ? 'active' : '' }}" href="{{ route('borrowings.self_checkout') }}">Self-Checkout</a>
				</li>
            @endif
            @if(auth()->user()->isAdmin())
				<li class="nav-item">
					<a class="nav-link {{ request()->routeIs('borrowings.*') ? 'active' : '' }}" href="{{ route('borrowings.index') }}">Borrowings</a>
				</li>
				<li class="nav-item">
					<a class="nav-link {{ request()->routeIs('analytics.*') ? 'active' : '' }}" href="{{ route('analytics.index') }}">Analytics</a>
				</li>
            @endif
			</ul>
			<ul class="navbar-nav ms-auto">
				<li class="nav-item dropdown">
					<a class="nav-link" href="#" id="userDropdown" role="button" onclick="toggleDropdown(event)">
						{{ Auth::user()->name }} <span class="text-muted small">({{ Auth::user()->student_id }})</span>
						<span class="dropdown-arrow">▼</span>
					</a>
					<ul class="dropdown-menu dropdown-menu-end" id="userDropdownMenu" style="display: none;">
						<li><a class="dropdown-item" href="{{ route('settings') }}">Settings</a></li>
						<li><a class="dropdown-item" href="{{ route('profile.qr') }}">My QR Code</a></li>
                @if(auth()->user()->isAdmin())
						<li><a class="dropdown-item" href="{{ route('admin.settings') }}">System Settings</a></li>
                @endif
						<li><hr class="dropdown-divider"></li>
						<li>
							<form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
								<button type="submit" class="dropdown-item text-danger border-0 bg-transparent w-100 text-start">Log Out</button>
                </form>
						</li>
					</ul>
				</li>
			</ul>
        </div>
    </div>
</nav>
