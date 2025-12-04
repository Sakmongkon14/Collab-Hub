@extends('layouts.Tailwind')
@section('title', 'ERP PR Home')
@section('content')

    <!-- แสดงข้อความสำเร็จ -->
    @if (session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: 'success',
                    title: 'สำเร็จ!',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'ตกลง',
                    confirmButtonColor: '#22c55e'
                    /*
                    showConfirmButton: false,
                    timer: 3000, // ปิดอัตโนมัติใน 3 วินาที
                    timerProgressBar: true
                    */

                });
            });
        </script>
    @endif

    <!-- แสดงข้อความข้อผิดพลาด -->
    @if ($errors->has('message'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: 'error',
                    title: 'ข้อผิดพลาด!',
                    text: '{{ $errors->first('message') }}',
                    confirmButtonText: 'ตกลง',
                    confirmButtonColor: '#d33'
                });
            });
        </script>
    @endif

<div class="flex h-[calc(100vh-64px)] overflow-hidden">
    <!-- Sidebar -->
    @include('layouts.user')

    <!-- Main Content -->
    
</div>



@endsection
