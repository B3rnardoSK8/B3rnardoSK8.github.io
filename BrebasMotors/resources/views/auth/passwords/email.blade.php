<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Forgot Password - Lagoa Admin Dashboard">
    <title>Forgot Password - Lagoa Admin</title>

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

        .btn-reset {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            color: #fff;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-reset:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            color: #fff;
        }

        .btn-back {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            color: #495057;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            width: 100%;
            transition: all 0.2s;
        }

        .btn-back:hover {
            background: #e9ecef;
            color: #495057;
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

        .alert {
            border-radius: 8px;
            padding: 0.75rem 1rem;
            margin-bottom: 1.5rem;
        }

        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1.5rem;
        }

        .info-box i {
            color: #667eea;
            margin-right: 0.5rem;
        }
    </style>
</head>

<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-logo">
                <i class="bi bi-lock"></i>
            </div>

            <div class="auth-header">
                <h1>Forgot Password?</h1>
                <p>No worries, we'll send you reset instructions</p>
            </div>

            <!-- Info Box -->
            <div class="info-box">
                <i class="bi bi-info-circle"></i>
                <small class="text-muted">
                    Enter your email address and we'll send you a link to reset your password.
                </small>
            </div>

            <!-- Success Alert (hidden by default, shown via JS) -->

            @if (session('status'))
                <div class="alert alert-success d-none" id="successAlert" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    <span id="successMessage">
                        Password reset link sent! Check your email inbox.
                        {{ session('status') }}
                    </span>
                </div>
            @endif


            <!-- Error Alert (hidden by default, shown via JS) -->
            <div class="alert alert-danger d-none" id="errorAlert">
                <i class="bi bi-exclamation-circle me-2"></i>
                <span id="errorMessage">
                    Please enter a valid email address.
                </span>
            </div>

            <!-- Forgot Password Form -->
            <!-- In Laravel, this will be: <form action="{{ route('password.email') }}" method="POST"> -->

            <form method="POST" id="forgotPasswordForm" action="{{ route('password.email') }}">
                @csrf
                <!-- Email Field -->
                <div class="mb-4">
                    <label for="email" class="form-label">Email Address</label>

                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                        name="email" value="{{ old('email') }}" placeholder="your.email@example.com" required
                        autocomplete="email" autofocus>

                    @error('email')
                        <div class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                    <small class="text-muted d-block mt-1">
                        Enter the email address associated with your account
                    </small>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-reset mb-3">
                    <i class="bi bi-envelope me-2"></i>
                    Send Reset Link
                </button>

                <!-- Back to Login Button -->
                <a href="{{ route('login') }}" class="btn btn-back">
                    <i class="bi bi-arrow-left me-2"></i>
                    Back to Login
                </a>
            </form>

            <!-- Footer Links -->
            <div class="auth-footer">
                <p class="text-muted mb-0">
                    Don't have an account?
                    <a href="{{ route('register') }}">Sign up now</a>
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

    <!-- Forgot Password Validation Script -->
    <script>
        // Form elements
        const forgotPasswordForm = document.getElementById('forgotPasswordForm');
        const emailInput = document.getElementById('email');
        const successAlert = document.getElementById('successAlert');
        const errorAlert = document.getElementById('errorAlert');

        successAlert.classList.remove('d-none');

        // Real-time validation on blur
        emailInput.addEventListener('blur', function() {
            if (emailInput.value && emailInput.checkValidity()) {
                emailInput.classList.remove('is-invalid');
                emailInput.classList.add('is-valid');
            } else if (emailInput.value) {
                emailInput.classList.remove('is-valid');
                emailInput.classList.add('is-invalid');
            }
        });

        // Remove validation states when typing
        emailInput.addEventListener('input', function() {
            if (emailInput.classList.contains('is-invalid') || emailInput.classList.contains('is-valid')) {
                emailInput.classList.remove('is-invalid', 'is-valid');
            }
        });
    </script>
</body>

</html>
