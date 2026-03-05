<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Lagoa Admin</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

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
        }

        .form-control {
            padding: 0.75rem 1rem;
            border-radius: 8px;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
        }

        .btn-reset {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            color: #fff;
            padding: 0.75rem;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            transition: 0.2s;
        }

        .btn-reset:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            color: #fff;
        }

        .btn-back {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 0.75rem;
            border-radius: 8px;
            width: 100%;
            color: #495057;
        }

        .btn-back:hover {
            background: #e9ecef;
        }

        .alert {
            border-radius: 8px;
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
                <h1>Reset Password</h1>
                <p>Enter your new password below</p>
            </div>

            <!-- Alerts -->
            <div class="alert alert-success d-none" id="successAlert">
                <i class="bi bi-check-circle me-2"></i>
                Password reset successfully.
            </div>

            <div class="alert alert-danger d-none" id="errorAlert">
                <i class="bi bi-exclamation-circle me-2"></i>
                Please correct the errors.
            </div>

            <!-- Form -->
            <!-- Laravel: <form method="POST" action="{{ route('password.update') }}"> -->
            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" required>
                    <div class="invalid-feedback">Enter a valid email.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" required minlength="6">
                    <div class="invalid-feedback">Minimum 6 characters.</div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirmPassword" required>
                    <div class="invalid-feedback">Passwords do not match.</div>
                </div>

                <button type="submit" class="btn btn-reset mb-3">
                    <i class="bi bi-arrow-repeat me-2"></i>
                    Reset Password
                </button>

                <a href="{{ route('login') }}" class="btn btn-back">
                    <i class="bi bi-arrow-left me-2"></i>
                    Back to Login
                </a>
            </form>
        </div>

        <div class="text-center mt-4">
            <p class="text-white mb-0">&copy; 2024 Lagoa Admin</p>
        </div>
    </div>
</body>

</html>
