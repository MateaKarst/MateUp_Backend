<div>
    <nav class="navbar navbar-expand-lg navbar-light bg-light" style="padding: 20px 25px 10px 25px;">
        <a class="navbar-brand" href="{{ route('home') }}"><strong style="font-size: 25px;">Admin Panel</strong></a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item" style="margin-right: 15px;">
                    <a class="nav-link" href="{{ route('user-management') }}"><strong>User Management</strong></a>
                </li>
                <li class="nav-item" style="margin-right: 15px;">
                    <a class="nav-link" href="#"><strong>Option</strong></a>
                </li>
                <li class="nav-item" style="margin-right: 15px;">
                    <a class="nav-link" href="#"><strong>Option</strong></a>
                </li>
                <li class="nav-item" style="margin-left: 25px;">
                    <livewire:auth.logout />
                </li>
            </ul>
        </div>
    </nav>
</div>