@extends('auth.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center align-items-center" style="min-height: 100vh; background-color: #1a1a2e;">
        <div class="col-md-5">
            <div class="card border-0 shadow-lg" style="background-color: #16213e; border-radius: 15px;">
                <div class="card-header border-0" style="background-color: #0f3460; border-radius: 15px 15px 0 0;">
                    <h3 class="text-center text-white my-3"><i class="bi bi-lock me-2"></i>Login</h3>
                </div>

                <div class="card-body px-5 py-4">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label text-white">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-dark border-dark text-white"><i class="bi bi-envelope"></i></span>
                                <input id="email" type="email" class="form-control bg-dark border-dark text-white @error('email') is-invalid @enderror" 
                                       name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            </div>
                            @error('email')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label text-white">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-dark border-dark text-white"><i class="bi bi-key"></i></span>
                                <input id="password" type="password" class="form-control bg-dark border-dark text-white @error('password') is-invalid @enderror" 
                                       name="password" required autocomplete="current-password">
                            </div>
                            @error('password')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4 form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label text-white" for="remember">Remember Me</label>
                        </div>

                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary btn-lg" style="background-color: #e94560; border: none;">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Login
                            </button>
                        </div>

                        <div class="text-center">
                            @if (Route::has('password.request'))
                                <a class="text-decoration-none text-white-50" href="{{ route('password.request') }}">
                                    Forgot Your Password?
                                </a>
                            @endif
                            <p class="text-white-50 mt-3">Don't have an account? 
                                <a href="{{ route('register') }}" class="text-primary text-decoration-none">Register here</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ✅ SweetAlert2 JS --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const success = @json(session('success'));
        const error = @json(session('error'));

        if (success) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: success,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        }

        if (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        }
    });
</script>
@endsection
