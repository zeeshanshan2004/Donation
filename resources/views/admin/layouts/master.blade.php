<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<body style="background-color: #dccd8a;"> <!-- Original background color -->

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
        <!-- Left: Logo -->
        <div class="d-flex align-items-center">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('admin-assets/images/logo.png') }}" alt="Admin Logo"
                    style="height: 60px; width: auto; margin-right: 12px;">
            </a>
        </div>

        <!-- Center: Title -->
        <div class="mx-auto text-center ai-navbar-popup">
            <span>Kelsie-Lichtenfeld, {{ auth('admin')->user()->name ?? 'Admin' }}</span>
        </div>

        <!-- Right: Logout -->
        <div class="d-flex align-items-center">
            <a href="{{ route('admin.logout') }}" class="btn btn-sm btn-outline-light ms-3 logout-btn">Logout</a>
        </div>
    </nav>

    <!-- Page Wrapper -->
    <div class="d-flex" style="min-height: calc(100vh - 70px);"> <!-- minus navbar height -->

        <!-- Sidebar -->
        <div class="sidebar text-white p-3"
            style="width: 220px; min-height: 100vh; position: sticky; top: 0; background-color: #000;">
            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-link text-white {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        Dashboard
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{ route('admin.causes.index') }}"
                        class="nav-link text-white {{ request()->routeIs('admin.causes.*') ? 'active' : '' }}">
                        Causes
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{ route('admin.faqs.index') }}"
                        class="nav-link text-white {{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">
                        FAQs
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{ route('admin.categories.index') }}"
                        class="nav-link text-white {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        Categories
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{ route('admin.donations.index') }}"
                        class="nav-link text-white {{ request()->routeIs('admin.donations.*') ? 'active' : '' }}">
                        Donations
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{ route('admin.kyc.index') }}"
                        class="nav-link text-white {{ request()->routeIs('admin.kyc.*') ? 'active' : '' }}">
                        KYC Verifications
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{ route('admin.packages.index') }}"
                        class="nav-link text-white {{ request()->routeIs('admin.packages.*') ? 'active' : '' }}">
                        Packages
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="{{ route('admin.terms_conditions.edit') }}"
                        class="nav-link text-white {{ request()->routeIs('admin.terms_conditions.*') ? 'active' : '' }}">
                        Terms & Conditions
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="{{ url('/admin/chat') }}"
                        class="nav-link text-white {{ request()->is('admin/chat') ? 'active' : '' }}">
                        Chat Support
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="{{ route('admin.settings.index') }}"
                        class="nav-link text-white {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        System Settings
                    </a>
                </li>




            </ul>
        </div>

        <!-- Main Content -->
        <div class="content flex-grow-1 p-4">
            @yield('content')
        </div>

    </div>

    <!-- Footer -->
    <footer class="text-center py-3 mt-auto text-muted" style="background-color: #dccd8a;">
        © {{ date('Y') }} Kelsie-Lichtenfeld. All rights reserved.
    </footer>

    @if(session('success'))
        <script>
            Swal.fire({
                title: 'Success!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonColor: '#000'
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                title: 'Error!',
                text: "{{ session('error') }}",
                icon: 'error',
                confirmButtonColor: '#000'
            });
        </script>
    @endif

    <!-- Loader Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const loader = document.createElement('div');
            loader.innerHTML = `
        <div id="loaderOverlay" style="
            display:none;
            position:fixed;top:0;left:0;width:100%;height:100%;
            background:rgba(0,0,0,0.5);
            z-index:9999;
            justify-content:center;
            align-items:center;
            color:white;
            font-size:18px;">
            <div class="spinner-border text-light" role="status"></div>
            <span class="ms-2">Saving...</span>
        </div>
    `;
            document.body.appendChild(loader);

            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function () {
                    document.getElementById('loaderOverlay').style.display = 'flex';
                });
            });
        });
    </script>

    @yield('scripts')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


    <style>
        /* Sidebar Links */
        .sidebar .nav-link {
            font-size: 0.95rem;
            border-radius: 8px;
            padding: 8px 12px;
            transition: background 0.3s;
        }

        .sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar .nav-link.active {
            background: rgba(220, 205, 138, 0.4);
            font-weight: 600;
        }

        /* Navbar Popup */
        .ai-navbar-popup {
            background: linear-gradient(135deg, #0d0d0d, #1c1c1c);
            border: 1px solid #3c3c3c;
            padding: 8px 28px;
            border-radius: 40px;
            font-size: 16px;
            box-shadow: 0 0 15px rgba(220, 205, 138, 0.5);
            color: #dccd8a;
            animation: aiGlow 3s infinite ease-in-out;
            display: inline-block;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
        }

        @keyframes aiGlow {

            0%,
            100% {
                box-shadow: 0 0 12px rgba(220, 205, 138, 0.4);
                transform: scale(1);
            }

            50% {
                box-shadow: 0 0 22px rgba(220, 205, 138, 0.7);
                transform: scale(1.05);
            }
        }

        /* Logout Button */
        .logout-btn {
            font-weight: 600;
            color: #fff;
            border-color: #fff;
            transition: 0.3s ease, box-shadow 0.3s ease;
        }

        .logout-btn:hover {
            color: #dccd8a;
            border-color: #dccd8a;
            box-shadow: 0 0 15px rgba(220, 205, 138, 0.7), 0 0 30px rgba(220, 205, 138, 0.3);
            transform: scale(1.05);
        }

        /* Navbar Image Hover */
        .navbar img {
            transition: 0.3s ease;
        }

        .navbar img:hover {
            transform: scale(1.08);
        }
    </style>

</body>

</html>