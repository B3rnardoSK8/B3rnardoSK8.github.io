<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login - Lagoa Admin Dashboard">
    <title>Login - Lagoa Admin</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="assets/css/custom.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-container {
            width: 100%;
            max-width: 450px;
            padding: 2rem;
        }

        .auth-card {
            background: #fff;
            border-radius: 12px;
            padding: 2.5rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }

        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .auth-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1e2139;
            margin-bottom: 0.5rem;
        }

        .auth-header p {
            color: #6c757d;
            margin: 0;
        }

        .auth-logo {
            width: 60px;
            height: 60px;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 2rem;
        }

        .form-label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .form-control {
            padding: 0.75rem 1rem;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
        }

        .form-control.is-invalid:focus {
            border-color: #d92550;
            box-shadow: 0 0 0 0.2rem rgba(217, 37, 80, 0.15);
        }

        .form-control.is-valid:focus {
            border-color: #3ac47d;
            box-shadow: 0 0 0 0.2rem rgba(58, 196, 125, 0.15);
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            color: #fff;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            color: #fff;
        }

        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
        }

        .auth-footer a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }

        .divider {
            text-align: center;
            margin: 1.5rem 0;
            position: relative;
        }

        .divider::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #dee2e6;
        }

        .divider span {
            background: #fff;
            padding: 0 1rem;
            position: relative;
            color: #6c757d;
            font-size: 0.9rem;
        }

        .alert {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            margin-bottom: 1.5rem;
        }
    </style>
</head>

<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-logo">
                <i class="bi bi-water"></i>
            </div>

            <div class="auth-header">
                <h1>Welcome Back</h1>
                <p>Sign in to your account to continue</p>
            </div>

            <!-- Success Alert (hidden by default, shown via JS) -->
            <div class="alert alert-success d-none" id="successAlert">
                <i class="bi bi-check-circle me-2"></i>
                <span id="successMessage">Login successful!</span>
            </div>

            <!-- Error Alert (hidden by default, shown via JS) -->
            <div class="alert alert-danger d-none" id="errorAlert">
                <i class="bi bi-exclamation-circle me-2"></i>
                <span id="errorMessage">Invalid credentials. Please try again.</span>
            </div>

            <!-- Login Form -->
            <!-- In Laravel, this will be: <form action="{{ route('login') }}" method="POST"> -->

            <form id="loginForm" method="POST" action="{{ route('login') }}">
                @csrf
                <!-- Email Field -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    {{--   <input type="email" class="form-control" id="email" name="email"
                        placeholder="your.email@example.com" required>
                    <div class="invalid-feedback">
                        Please enter a valid email address.
                    </div> --}}

                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                        name="email" placeholder="your.email@example.com" value="{{ old('email') }}" required
                        autocomplete="email" autofocus>

                    @error('email')
                        <div class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror


                </div>

                <!-- Password Field -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="position-relative">
                        {{-- <input type="password" class="form-control" id="password" name="password"
                            placeholder="Enter your password" required minlength="6"> --}}

                        <input id="password" type="password"
                            class="form-control @error('password') is-invalid @enderror" name="password" required
                            autocomplete="current-password" placeholder="Enter your password" minlength="8">

                        <button type="button"
                            class="btn btn-link position-absolute end-0 top-50 translate-middle-y text-decoration-none"
                            id="togglePassword" tabindex="-1">
                            <i class="bi bi-eye" id="togglePasswordIcon"></i>
                        </button>
                    </div>
                    {{-- <div class="invalid-feedback">
                        Password must be at least 6 characters long.
                    </div> --}}
                    @error('password')
                        <div class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        {{-- <input class="form-check-input" type="checkbox" id="rememberMe" name="remember"> --}}
                        <input class="form-check-input" type="checkbox" name="remember" id="rememberMe"
                            {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="rememberMe">
                            Remember me
                        </label>
                    </div>
                    {{-- <a href="forgot-password.html" class="text-decoration-none">Forgot Password?</a> --}}
                    @if (Route::has('password.request'))
                        <a class="text-decoration-none" href="{{ route('password.request') }}">
                            {{ __('Forgot Password?') }}
                        </a>
                    @endif
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-login">
                    <i class="bi bi-box-arrow-in-right me-2"></i>
                    Sign In
                </button>
            </form>

            <!-- Divider -->
            <div class="divider">
                <span>or</span>
            </div>

            <!-- Footer Links -->
            <div class="auth-footer">
                <p class="text-muted mb-0">
                    Don't have an account?
                    <a href="{{ route('register') }}">Create one now</a>
                </p>
            </div>
        </div>

        <!-- Copyright -->
        <div class="text-center mt-4">
            <p class="text-white mb-0">&copy; 2024 Lagoa Admin. All rights reserved.</p>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Login Validation Script -->
    <script>
        // Form validation

        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');


        // Real-time validation on input
        emailInput.addEventListener('blur', function() {
            if (emailInput.value && emailInput.checkValidity()) {
                emailInput.classList.remove('is-invalid');
                emailInput.classList.add('is-valid');
            } else if (emailInput.value) {
                emailInput.classList.remove('is-valid');
                emailInput.classList.add('is-invalid');
            }
        });

        passwordInput.addEventListener('blur', function() {
            if (passwordInput.value && passwordInput.value.length >= 8) {
                passwordInput.classList.remove('is-invalid');
                passwordInput.classList.add('is-valid');
            } else if (passwordInput.value) {
                passwordInput.classList.remove('is-valid');
                passwordInput.classList.add('is-invalid');
            }
        });
    </script>
</body>

</html>
