<nav class="navbar navbar-expand-lg bg-white border-bottom">
    <div class="container">
        <a class="app-brand" href="{{ route('dashboard') }}">
            <span class="app-brand-mark"><i class="bi bi-box-seam"></i></span>
            <span>Sistem Inventory Aset</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavigation" aria-controls="mainNavigation" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavigation">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                <li class="nav-item">
                    <a class="nav-link @if (request()->routeIs('dashboard')) active @endif" href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2 me-1"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if (request()->routeIs('inventories.*')) active @endif" href="{{ route('inventories.index') }}">
                        <i class="bi bi-boxes me-1"></i> Inventory
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if (request()->routeIs('inventory-transactions.*')) active @endif" href="{{ route('inventory-transactions.index') }}">
                        <i class="bi bi-receipt me-1"></i> Transactions
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if (request()->routeIs('reports.*')) active @endif" href="{{ route('reports.index') }}">
                        <i class="bi bi-file-earmark-bar-graph me-1"></i> Laporan
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <button class="nav-link dropdown-toggle @if (request()->routeIs('item-types.*') || request()->routeIs('buildings.*') || request()->routeIs('rooms.*') || request()->routeIs('transaction-types.*')) active @endif" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-database me-1"></i> Master Data
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item @if (request()->routeIs('item-types.*')) active @endif" href="{{ route('item-types.index') }}">
                                Item Types
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item @if (request()->routeIs('buildings.*')) active @endif" href="{{ route('buildings.index') }}">
                                Buildings
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item @if (request()->routeIs('rooms.*')) active @endif" href="{{ route('rooms.index') }}">
                                Rooms
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item @if (request()->routeIs('transaction-types.*')) active @endif" href="{{ route('transaction-types.index') }}">
                                Transaction Types
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>

            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><span class="dropdown-item-text small text-muted">{{ Auth::user()->email }}</span></li>
                    <li><span class="dropdown-item-text small">{{ Str::headline(Auth::user()->role) }}</span></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">Log Out</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
