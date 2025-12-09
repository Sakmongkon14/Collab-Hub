<aside class="w-64 bg-blue-950 text-white flex flex-col p-2 space-y-4">

    <style>
        .swal-title,
        .swal-text {
            font-family: 'Sarabun', sans-serif;
        }
    </style>

    <div
        class="flex justify-center items-center py-3 mx-3 mt-2 
            bg-white/10 rounded-xl backdrop-blur-sm shadow-sm">
        <h2 class="text-xl font-extrabold tracking-wide text-white uppercase">
            Collab Hub
        </h2>
    </div>

    <div class="mt-4 space-y-4 overflow-y-auto h-[calc(100vh-120px)] pr-1">

        <a href="{{ route('user.home') }}"
            class="flex items-center gap-2 px-3 py-2 rounded-md transition
    @if (Request::is('billing*')) bg-white text-blue-800 shadow-md @else hover:bg-white hover:text-blue-800 @endif">
            <i class="fas fa-home"></i>
            <span class="text-base font-bold whitespace-nowrap">Home</span>
        </a>


        <!-- PR Dropdown -->
        <div x-data="{ open: false }" class="flex flex-col">
            <!-- ปุ่ม PR -->
            <button type="button" @click="open = !open"
                class="flex items-center justify-between gap-2 px-3 py-2 rounded-md transition w-full
               hover:bg-white hover:text-blue-800">
                <div class="flex items-center gap-2">
                    <i class="fas fa-database"></i>
                    <span class="text-base font-bold ">Project Database</span>
                </div>
                <i :class="open ? 'fa-solid fa-chevron-up' : 'fa-solid fa-chevron-down'" class="text-white"></i>
            </button>

            <!-- เมนูย่อย -->
            <div x-show="open || window.location.pathname.startsWith('/pr')" x-transition
                class="flex flex-col pl-8 mt-2 space-y-1">

                <!-- PR -->
                <a href="#"
                    class="px-3 py-1 rounded-md transition font-semibold {{ Route::currentRouteName() === 'pr.home' ? 'bg-white text-blue-800' : 'text-blue hover:bg-white hover:text-blue-800' }}">
                    New Site
                </a>

                <!-- Purchase -->
                <a href="#"
                    class="px-3 py-1 rounded-md transition font-semibold {{ Route::currentRouteName() === 'pr.purchase' ? 'bg-white text-blue-800' : 'text-blue hover:bg-white hover:text-blue-800' }}">
                    54_NT_BTO
                </a>

                <!-- Purchase -->
                <a href="#"
                    class="px-3 py-1 rounded-md transition font-semibold {{ Route::currentRouteName() === 'pr.purchase' ? 'bg-white text-blue-800' : 'text-blue hover:bg-white hover:text-blue-800' }}">
                    Project View
                </a>


            </div>
        </div>


        <!-- ERP -->
        <div x-data="{ open: false }" class="flex flex-col">
            <!-- ปุ่ม PR -->
            <button type="button" @click="open = !open"
                class="flex items-center justify-between gap-2 px-3 py-2 rounded-md transition w-full
               hover:bg-white hover:text-blue-800">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-building"></i>
                    <span class="text-base font-bold ">ERP</span>
                </div>
                <i :class="open ? 'fa-solid fa-chevron-up' : 'fa-solid fa-chevron-down'" class="text-white"></i>
            </button>

            <!-- เมนูย่อย -->
            <div x-show="open || window.location.pathname.startsWith('/pr')" x-transition
                class="flex flex-col pl-8 mt-2 space-y-1">

                <!-- PR -->
                <a href="#"
                    class="px-3 py-1 rounded-md transition font-semibold {{ Route::currentRouteName() === 'pr.home' ? 'bg-white text-blue-800' : 'text-blue hover:bg-white hover:text-blue-800' }}">
                    Refcode
                </a>

                <!-- Purchase -->
                <a href="#"
                    class="px-3 py-1 rounded-md transition font-semibold {{ Route::currentRouteName() === 'pr.purchase' ? 'bg-white text-blue-800' : 'text-blue hover:bg-white hover:text-blue-800' }}">
                    Billing

                </a>

                <a href="#"
                    class="px-3 py-1 rounded-md transition font-semibold {{ Route::currentRouteName() === 'pr.purchase' ? 'bg-white text-blue-800' : 'text-blue hover:bg-white hover:text-blue-800' }}">
                    Inventory

                </a>
            </div>
        </div>


        <div x-data="{ open: false }" class="flex flex-col">
            <!-- ปุ่ม IT Support -->
            <button type="button" @click="open = !open"
                class="flex items-center justify-between gap-2 px-3 py-2 rounded-md transition w-full
               hover:bg-white hover:text-blue-800">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-headset"></i>
                    <span class="text-base font-bold ">IT Support</span>
                </div>
                <i :class="open ? 'fa-solid fa-chevron-up' : 'fa-solid fa-chevron-down'" class="text-white"></i>
            </button>

            <!-- เมนูย่อย -->
            <div x-show="open || window.location.pathname.startsWith('/pr')" x-transition
                class="flex flex-col pl-8 mt-2 space-y-1">

                <!-- Add Member -->
                <a href="#"
                    class="px-3 py-1 rounded-md transition font-semibold {{ Route::currentRouteName() === 'pr.home' ? 'bg-white text-blue-800' : 'text-blue hover:bg-white hover:text-blue-800' }}">
                    IT Clinic
                </a>

                <!-- Member Total -->
                <a href="#"
                    class="px-3 py-1 rounded-md transition font-semibold {{ Route::currentRouteName() === 'pr.purchase' ? 'bg-white text-blue-800' : 'text-blue hover:bg-white hover:text-blue-800' }}">
                    Report

                </a>

                <a href="#"
                    class="px-3 py-1 rounded-md transition font-semibold {{ Route::currentRouteName() === 'pr.purchase' ? 'bg-white text-blue-800' : 'text-blue hover:bg-white hover:text-blue-800' }}">
                    Databases

                </a>
            </div>
        </div>


        <div x-data="{ open: false }" class="flex flex-col">
            <!-- ปุ่ม IT Support -->
            <button type="button" @click="open = !open"
                class="flex items-center justify-between gap-2 px-3 py-2 rounded-md transition w-full
               hover:bg-white hover:text-blue-800">
                <div class="flex items-center gap-2">
                    <i class="fa-regular fa-user"></i>
                    <span class="text-base font-bold">Admin</span>
                </div>
                <i :class="open ? 'fa-solid fa-chevron-up' : 'fa-solid fa-chevron-down'" class="text-white"></i>
            </button>

            <!-- เมนูย่อย -->
            <div x-show="open || window.location.pathname.startsWith('/pr')" x-transition
                class="flex flex-col pl-8 mt-2 space-y-1">

                @php
                    $isAuthorized = Auth::check() && Auth::user()->status == 4;
                @endphp

                <!-- Add Member -->
                <a href="#"
                    class="px-3 py-1 rounded-md transition font-semibold {{ Route::currentRouteName() === 'pr.purchase' ? 'bg-white text-blue-800' : 'text-blue hover:bg-white hover:text-blue-800' }}">
                    Add Member
                </a>

                <!-- Member Total --> 
                <a href="#"
                    class="px-3 py-1 rounded-md transition font-semibold {{ Route::currentRouteName() === 'pr.purchase' ? 'bg-white text-blue-800' : 'text-blue hover:bg-white hover:text-blue-800' }}">
                    Member Total
                </a>

            </div>
        </div>


        <div x-data="{ open: false }" class="flex flex-col">
            <!-- ปุ่ม IT Support -->
            <button type="button" @click="open = !open"
                class="flex items-center justify-between gap-2 px-3 py-2 rounded-md transition w-full
               hover:bg-white hover:text-blue-800">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-briefcase"></i>
                    <span class="text-base font-bold">New Job Assignment</span>
                </div>
                <i :class="open ? 'fa-solid fa-chevron-up' : 'fa-solid fa-chevron-down'" class="text-white"></i>
            </button>

            <!-- เมนูย่อย -->
            <div x-show="open || window.location.pathname.startsWith('/pr')" x-transition
                class="flex flex-col pl-8 mt-2 space-y-1">

                @php
                    $isAuthorized = Auth::check() && Auth::user()->status == 4;
                @endphp

                <!-- Add Member -->
                <a href="{{ $isAuthorized ? route('user.sda.home') : route('addjob.user') }}"
                    class="px-3 py-1 rounded-md transition font-semibold {{ Route::currentRouteName() === 'newjobassignment.addjob' ? 'bg-white text-blue-800' : 'text-blue hover:bg-white hover:text-blue-800' }}">
                    Add Job
                </a>


            </div>
        </div>

    </div>

    <!-- ปุ่มออกจากระบบ -->
    <div class="mt-auto px-2 pb-4 mt-2">
        <a href="{{ route('logout') }}"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
            class="flex items-center justify-center gap-2 w-full 
               bg-red-600 text-white font-bold py-2 rounded-lg shadow-md
               transition transform hover:bg-red-700 hover:scale-[1.02]"
            style="font-family: 'Sarabun', sans-serif;">
            <i class="fas fa-sign-out-alt"></i>
            ออกจากระบบ
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </div>



</aside>

<script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.12.0/cdn.min.js" defer></script>
