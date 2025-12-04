@extends('layouts.Tailwind')
@section('title', 'Login')
@section('content')

<style>
    /* Set the full-screen background */
    .login-bg {
    background: url('https://img.pikbest.com/wp/202347/pastel-green-background-3d-rendering-of-a-winner-s-podium-on-matching_9767186.jpg!bw700') no-repeat center center fixed;
    background-size: cover;
    height: 88vh; /* หรือใช้ % เช่น height: 89%; */
}

.login-bg {
    background: url('https://img.pikbest.com/wp/202347/pastel-green-background-3d-rendering-of-a-winner-s-podium-on-matching_9767186.jpg!bw700') no-repeat center center fixed;
    background-size: cover;
    height: 88vh; /* เปลี่ยนความสูงให้เป็น 89% ของ viewport */
}


    /* Card styling */
    .login-card {
        background-color: rgba(255, 255, 255, 0.1); /* Transparent background */
        padding: 2rem;
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.18);
        margin-top: -50px;
		opacity: 0;
        animation: zoomIn 1s ease forwards, floatlogin 4s ease-in-out infinite;
        animation-delay: 0s, 1s ;
    }

    /* Input fields */
    .form-control {
        background-color: rgba(255, 255, 255, 0.3);
        border: none;
        border-radius: 50px;
        padding: 10px 20px;
        color: #fff;
    }

    .form-control::placeholder {
        color: rgba(255, 255, 255, 0.7);
    }

    .form-control:focus {
        outline: none;
        box-shadow: 0 0 5px rgba(255, 255, 255, 0.7);
    }

    /* Button styles */
    .btn-primary {
        background-color: #fff;
        color: #000;
        border-radius: 50px;
        border: none;
        padding: 10px 20px;
    }

    .btn-primary:hover {
        background-color: #f0f0f0;
    }

    /* Text and link styling */
    .card-body {
        color: #fff;
    }

    .card-body a {
        color: #fff;
        text-decoration: none;
    }

    .card-body a:hover {
        text-decoration: underline;
    }
	
	    /* กำหนดแอนิเมชัน zoomIn */
@keyframes zoomIn {
    0% {
        transform: scale(0); /* เริ่มจากด้านซ้ายสุด */
        opacity: 0; /* เริ่มต้นด้วยความโปร่งใส */
    }
    100% {
        transform: scale(1); /* เคลื่อนที่ไปยังตำแหน่งเดิม */
        opacity: 1; /* เปลี่ยนเป็นไม่โปร่งใส */
    }
}
@keyframes floatlogin {
    0% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-20px);

    }

    100% {
        transform: translateY(0);
    }
}
</style>

<div class="container-fluid d-flex justify-content-center align-items-center vh-88 login-bg">
    <div class="login-card">
        <div class="card-body">
            <h2 class="text-center mb-4">Login</h2>
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group mb-3">
                    <input id="email" type="email" placeholder="Email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <input id="password" type="password" placeholder="Password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
             <!--       @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}">Forgot pass</a>
                    @endif
			-->
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </div>

                <div class="text-center mt-3">
               
                </div>
            </form>
        </div>
    </div>
</div>
	


                                @if (Route::has('password.request'))
                                    
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
