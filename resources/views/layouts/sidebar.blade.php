<div class="offcanvas offcanvas-start bg-warning text-white" tabindex="-1" id="sidebar" style="width: 250px;">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title"></h5>
        <button type="button" class="btn-close btn-close-white mobile-only" data-bs-dismiss="offcanvas">
        </button>

    </div>

    <div class="offcanvas-body p-0">
        <ul class="nav flex-column p-3">

            {{-- DOCTOR --}}
            @can('doctor')
                <li class="nav-item mb-2">
                    <a class="nav-link
                        {{ request()->routeIs('doctor.dashboard') ? 'active' : '' }}"
                        href="{{ route('doctor.dashboard') }}">
                        Dashboard
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a class="nav-link
                        {{ request()->routeIs('doctor.appointments*') ? 'active' : '' }}"
                        href="#">
                        Appointments
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link
                        {{ request()->routeIs('doctor.schedule') ? 'active' : '' }}"
                        href="#">
                        Schedule
                    </a>
                </li>
            @endcan

            {{-- SUPER ADMIN --}}
            @can('super_admin')
                <li class="nav-item mb-2">
                    <a class="nav-link
                        {{ request()->routeIs('super_admin.dashboard') ? 'active' : '' }}"
                        href="{{ route('super_admin.dashboard') }}">
                        Dashboard
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a class="nav-link
                        {{ request()->routeIs('super_admin.hospitals*') ? 'active' : '' }}"
                        href="{{ route('super_admin.hospitals_add_view') }}">
                        Add hospitals
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a class="nav-link
                        {{ request()->routeIs('super_admin.enquiries*') ? 'active' : '' }}"
                        href="#">
                        View enquiry
                    </a>
                </li>
            @endcan

            {{-- HOSPITAL ADMIN --}}
            @can('hospital_admin')
                <li class="nav-item mb-2">
                    <a class="nav-link
                        {{ request()->routeIs('hospital_admin.dashboard') ? 'active' : '' }}"
                        href="#">
                        Dashboard
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a class="nav-link
                        {{ request()->routeIs('hospital_admin.doctors*') ? 'active' : '' }}"
                        href="{{ route('hospital_admin.doctors_add_view') }}">
                        Doctors
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link
                        {{ request()->routeIs('hospital_admin.specialization*') ? 'active' : '' }}"
                        href="{{ route('hospital_admin.specialization') }}">
                        Specialization
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a class="nav-link
                        {{ request()->routeIs('hospital_admin.bookings*') ? 'active' : '' }}"
                        href="#">
                        Overall bookings
                    </a>
                </li>
            @endcan

        </ul>
    </div>
</div>
