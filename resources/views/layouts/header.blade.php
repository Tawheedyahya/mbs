<nav class="navbar navbar-expand-lg navbar-light bg-primary text-black shadow sticky-top">
    <div class="container-fluid">

        <!-- Sidebar toggle (mobile) -->
        <button class="btn sidebar-toggle me-2" type="button" data-bs-toggle="offcanvas"
            data-bs-target="#sidebar" aria-label="Open menu">
            ☰
        </button>


        <a class="navbar-brand text-white" href="#">
            {{-- {{ config('app.name') }} --}}
            {{str_replace('_',' ',ucwords(auth()->user()->role))}}
        </a>

        <div class="ms-auto d-flex align-items-center">
            @auth
                <span class="me-3 text-white">{{str_replace('_',' ',ucwords(auth()->user()->name)) }}</span>

                <a class="btn btn-outline-danger btn-sm bg-danger text-white" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Logout
                </a>
            @endauth
        </div>
    </div>
</nav>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>
