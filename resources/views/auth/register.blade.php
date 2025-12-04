@extends('layouts.Tailwind')
@section('title', 'Add_Member')

@section('content')

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Add Member</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}" id="add-member-form">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>
						
						 <!-- STATUS -->
                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Status') }}</label>

                       		<div class="col-md-6">

                                <select id="status" name="status" class="form-control" required autocomplete="status">
                                    <option value="" disabled selected>เลือกสถานะของคุณ</option>
                                    <option value="1">1. UPDATE ทั้งหมด</option>
                                    <option value="2">2. UPDATE CR และ SAQ</option>
                                    <option value="3">3. UPDATE TSSR และ CIVILWORK</option>
                                    <option value="4">4. SUPERUSER</option>
                                    <option value="5">5. INVENTORY USER</option> <!-- ตัวเลือกนี้จะแสดงเป็นช่องว่าง -->
                                    <option value="6">6. INVENTORY ADMIN</option> <!-- ตัวเลือกนี้จะแสดงเป็นช่องว่าง -->
                                    <option value="">7. Search Refcode AND IT Clinic</option>
                                </select>
                                    <p style="margin-top: 10px; color:red "  >
                                 - TRACKING STATUS 1 - 3 <br>
                                 - SUPERUSER 4 <br>
                                 - ERP INVENTORY 5-6 <br>
								 - SEARCH REFCODE , IT CLINIC 7
                                 </p>
                            </div>
                        </div>


                    
                        <!--    <div class="row mb-3">
                            <label for="Option" class="col-md-4 col-form-label text-md-end">Option</label>
                            

                            <div class="col-md-6">
                                <input id="Option" type="Option" class="form-control @error('password') is-invalid @enderror" name="Option" required autocomplete="Option">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div> -->

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="button" class="btn btn-success" onclick="confirmSubmission()">
                                    Add Member
                                </button>
                                <a href="/home" class="btn btn-danger">Home</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function confirmSubmission() {
    if (confirm("ต้องการเพิ่มสมาชิก ")) {
        document.getElementById('add-member-form').submit();
    }
}
</script>

@endsection
