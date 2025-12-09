<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <script src="{{ asset('js/sidebar.js') }}"></script>


    @include('sweetalert::alert')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.2/font/bootstrap-icons.min.css">

    <!-- favicon  -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>


    <!-- Bootstrap Datepicker -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

    <!-- Bootstrap Datepicker JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

    <link rel="stylesheet" href="">

    <!-- นำเข้า Bootstrap Icons ถ้ายังไม่ได้นำเข้า -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">

    <!-- tailwindcss -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/sass/app.scss', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- thai sarabun -->
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;600;700&display=swap" rel="stylesheet">


</head>

<style>
    .swal-title,
    .swal-text {
        font-family: 'Sarabun', sans-serif;
    }
</style>


<body>

    <!-- Navbar -->
    <nav
        class="bg-gradient-to-r from-[rgb(177,230,207)] to-[rgb(182,247,247)] shadow-[rgba(0, 0, 0, 0.24) 0px 3px 8px]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- โลโก้ -->
                <div class="flex items-center">
                    <a href="{{ url('user/home') }}">
                        <img src="{{ asset('/GTN.jpg') }}" alt="GTN Logo" class="h-10">
                    </a>
                </div>

                <!-- เมนูหลัก -->
                <div class="hidden md:flex items-center space-x-6">
                    @guest
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-green-600 px-4 py-2 transition">
                                {{ __('Login') }}
                            </a>
                        @endif
                    @else
                        <div class="flex items-center relative">

                            <!-- ชื่อผู้ใช้ -->
                            <button id="user-menu-button"
                                class="text-gray-700 hover:text-green-600 px-2 py-2 transition ml-auto">
                                {{ Auth::user()->name }}
                            </button>

                            @php
                                $user = Auth::user();
                            @endphp

                            <!-- 🔔 Admin Notification (Pending Jobs) -->
                            @if ($user && $user->status == 4)
                                <div class="relative">
                                    <button id="notification-button"
                                        class="text-gray-700 hover:text-green-600 px-4 py-2 transition">
                                        <i class="fas fa-bell"></i>

                                        @if ($countPending > 0)
                                            <span
                                                class="absolute -top-1 -right-1 bg-red-600 text-white text-xs px-1.5 rounded-full">
                                                {{ $countPending }}
                                            </span>
                                        @endif
                                    </button>

                                    <!-- Dropdown -->
                                    <div id="notification-dropdown"
                                        class="hidden absolute right-0 mt-2 w-80 bg-white shadow-lg rounded-lg z-50">
                                        <div class="p-2 font-semibold border-b" style="font-family: 'Sarabun', sans-serif;">
                                            การแจ้งเตือนจากผู้ขอเพิ่มงาน
                                        </div>

                                        @forelse($pendingJobs as $job)
                                            <a href="{{ route('user.sda.home') }}" class="block p-2 hover:bg-gray-100 border-b">
                                                <span class="font-medium">{{ $job->Requester }}</span>
                                                ขอเพิ่มงาน: {{ $job->Site_Code ?? 'ไม่มีหัวข้อ' }}
                                            </a>
                                        @empty
                                            <div class="p-2 text-gray-500">ไม่มีแจ้งเตือน</div>
                                        @endforelse
                                    </div>
                                </div>
                            @endif

                            <!-- 🟢 User Notification (Status Changed) -->
                            @if ($user && $user->status != 4)
                                <div class="relative">
                                    <button id="notification-button-user"
                                        class="text-gray-700 hover:text-green-600 px-4 py-2 transition">
                                        <i class="fas fa-bell"></i>

                                        @if ($countNotifications > 0)
                                            <span
                                                class="absolute -top-1 -right-1 bg-red-600 text-white text-xs px-1.5 rounded-full"
                                                id="user-noti-badge">
                                                {{ $countNotifications }}
                                            </span>
                                        @endif

                                    </button>

                                    <!-- Dropdown -->
                                    <div id="notification-dropdown-user"
                                        class="hidden absolute right-0 mt-2 w-80 bg-white shadow-lg rounded-lg z-50">
                                        <div class="p-2 font-semibold border-b" style="font-family: 'Sarabun', sans-serif;">
                                            การแจ้งเตือน
                                        </div>

                                        @foreach ($notifications as $item)
                                            @php
                                                $color =
                                                    $item->Job_Adding_Status == 'Approved'
                                                        ? 'text-green-600'
                                                        : 'text-red-600';
                                            @endphp

                                            <a href="{{ route('notification.read', $item->id) }}"
                                                class="block p-2 hover:bg-gray-100 border-b transition">

                                                <div style="font-family: 'Sarabun', sans-serif;"
                                                    class="{{ $item->is_read ? 'opacity-50 text-gray-500' : '' }}">

                                                    {{ $item->Site_Code }} ถูกเปลี่ยนสถานะเป็น:
                                                    <span class="font-bold {{ $color }}">
                                                        {{ $item->Job_Adding_Status }}
                                                    </span>
                                                </div>

                                            </a>
                                        @endforeach




                                    </div>
                                </div>
                            @endif


                        </div>


                        <!-- JS Dropdown -->
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {

                                const adminBtn = document.getElementById('notification-button');
                                const adminDropdown = document.getElementById('notification-dropdown');

                                const userBtn = document.getElementById('notification-button-user');
                                const userDropdown = document.getElementById('notification-dropdown-user');

                                // Toggle Admin
                                if (adminBtn) {
                                    adminBtn.addEventListener('click', (e) => {
                                        e.stopPropagation(); // กัน event ไหลไป document
                                        adminDropdown.classList.toggle('hidden');
                                    });
                                }

                                // Toggle User
                                if (userBtn) {
                                    userBtn.addEventListener('click', (e) => {
                                        e.stopPropagation();
                                        userDropdown.classList.toggle('hidden');
                                    });
                                }

                                // ปิด dropdown เมื่อคลิกบริเวณอื่นบนหน้า
                                document.addEventListener('click', function(e) {

                                    // ปิดของ Admin ถ้าคลิกนอก dropdown และนอกปุ่ม
                                    if (adminDropdown && !adminDropdown.contains(e.target) && !adminBtn.contains(e.target)) {
                                        adminDropdown.classList.add('hidden');
                                    }

                                    // ปิดของ User ถ้าคลิกนอก dropdown และนอกปุ่ม
                                    if (userDropdown && !userDropdown.contains(e.target) && !userBtn.contains(e.target)) {
                                        userDropdown.classList.add('hidden');
                                    }
                                });
                            });
                        </script>


                    @endguest
                </div>


                <!-- ปุ่มเปิดเมนู (มือถือ) -->
                <div class="md:hidden flex items-center">
                    <button id="menu-toggle" class="text-gray-700 focus:outline-none">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-16 6h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- เมนู (มือถือ) -->
        <div id="mobile-menu" class="md:hidden hidden bg-white border-t shadow-md">
            @guest
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="block text-gray-700 px-4 py-2 hover:bg-gray-200">Login</a>
                @endif
            @else
                <a href="/home" class="block text-gray-700 px-4 py-2 hover:bg-emerald-200">ดูข้อมูลทั้งหมด</a>
                <a href="{{ route('logout') }}" class="block text-gray-700 px-4 py-2 hover:bg-emerald-200"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    ออกจากระบบ
                </a>
            @endguest
        </div>
    </nav>

    <!-- เนื้อหาหลัก -->
    <div class="containor ">
        @yield('content')
    </div>
    </div>

    <!-- JavaScript สำหรับ Dropdown & Mobile Menu -->
    <script>
        // เปิด-ปิดเมนูมือถือ
        document.getElementById("menu-toggle").addEventListener("click", function() {
            document.getElementById("mobile-menu").classList.toggle("hidden");
        });

        // เปิด-ปิด Dropdown
        document.getElementById("user-menu-button").addEventListener("click", function() {
            document.getElementById("dropdown-menu").classList.toggle("hidden");
        });
    </script>
</body>

</html>
