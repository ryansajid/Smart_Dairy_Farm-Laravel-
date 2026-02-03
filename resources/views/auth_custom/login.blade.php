<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        :root {
            --bg-page: radial-gradient(circle at center, #1e293b 0%, #020617 100%);
            --bg-card: #0f172a;
            --accent-blue: #3b82f6;
            --neon-glow: 0 0 12px rgba(59, 130, 246, 0.8), 0 0 25px rgba(59, 130, 246, 0.4);
            --text-main: #ffffff;
            --text-muted: #94a3b8;
            --input-bg: #1e293b;
            --input-border: #334155;
        }

        html, body {
            height: 100%;
        }

        body {
            background: var(--bg-page);
            color: var(--text-main);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            margin: 0;
        }

        .form-signin {
            background-color: var(--bg-card);
            border-radius: 28px;
            width: 100%;
            max-width: 500px;
            padding: 50px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.9), 
                        0 0 40px rgba(59, 130, 246, 0.05);
        }

        .header-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 45px;
        }

        .logo-v {
            background: white;
            color: #0f172a;
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 24px;
            border-radius: 10px;
        }

        .brand-text h2 {
            font-size: 18px;
            font-weight: 700;
            margin: 0;
            letter-spacing: 0.5px;
        }

        .brand-text span {
            font-size: 11px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .page-title {
            font-size: 36px;
            font-weight: 800;
            color: white;
            opacity: 0.95;
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin: 45px 0 25px 0;
            color: var(--accent-blue);
            font-size: 14px;
            font-weight: 800;
            letter-spacing: 1.8px;
            text-transform: uppercase;
            text-shadow: var(--neon-glow);
        }

        .section-header::after {
            content: "";
            flex: 1;
            height: 1.5px;
            background: linear-gradient(90deg, var(--accent-blue) 0%, transparent 100%);
            opacity: 0.5;
        }

        .form-label {
            color: var(--text-muted);
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .input-wrapper {
            position: relative;
        }

        .form-control {
            background-color: var(--input-bg);
            border: 1.5px solid var(--input-border);
            border-radius: 12px;
            color: white;
            padding: 14px 18px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background-color: var(--input-bg);
            border-color: var(--accent-blue);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
            color: white;
            outline: none;
        }

        .form-control.is-invalid {
            border-color: var(--alert-danger);
        }

        .invalid-feedback {
            color: #fca5a5;
            font-size: 12px;
            margin-top: 5px;
        }

        .input-wrapper i {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--accent-blue);
            text-shadow: 0 0 8px var(--accent-blue);
            font-size: 14px;
            pointer-events: none;
        }

        .input-wrapper .toggle-btn {
            position: absolute;
            right: 45px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            font-size: 13px;
            z-index: 10;
        }

        .btn-approve {
            background: linear-gradient(135deg, #4f46e5 0%, #2563eb 100%);
            color: white;
            padding: 14px 32px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            border: none;
            box-shadow: 0 12px 24px -6px rgba(37, 99, 235, 0.5);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-approve:hover {
            transform: translateY(-3px);
            box-shadow: 0 18px 32px -6px rgba(37, 99, 235, 0.6);
            color: white;
        }

        .form-check-input {
            background-color: var(--input-bg);
            border-color: var(--input-border);
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: var(--accent-blue);
            border-color: var(--accent-blue);
        }

        .form-check-label {
            font-size: 13px;
            color: var(--text-muted);
        }

        .alert-danger {
            background-color: rgba(220, 38, 38, 0.1);
            border: 1px solid var(--alert-danger);
            color: #fca5a5;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .alert-success {
            background-color: rgba(22, 163, 74, 0.1);
            border: 1px solid var(--alert-success);
            color: #86efac;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .alert-danger ul {
            margin: 0;
            padding-left: 20px;
        }

        .fa-spin { margin-right: 8px; }

        /* Button Group Styling */
        .btn-group-custom {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-top: 50px;
            padding-top: 35px;
            border-top: 1px solid rgba(255,255,255,0.05);
        }

        .btn-base {
            padding: 14px 32px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            border: none;
        }

        .btn-cancel {
            background: #334155;
            color: #cbd5e1;
        }

        .btn-cancel:hover {
            background: #475569;
            transform: translateY(-3px);
        }
    </style>
</head>
<body>

    <main class="form-signin">
        <!-- Header -->
        <div class="header-flex">
            <div class="d-flex align-items-center gap-3">
                <div class="logo-v">V</div>
                <div class="brand-text">
                    <h2>UCB BANK</h2>
                    <span>VISITOR SYSTEM</span>
                </div>
            </div>
            <h1 class="page-title">Login</h1>
        </div>

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="alert alert-danger text-start">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Session Status (e.g., "You have been logged out.") -->
        @if (session('status'))
            <div class="alert alert-success text-start">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="section-header">Credentials</div>
            
            <!-- Email -->
            <div class="mb-4">
                <label class="form-label" for="email">Email Address</label>
                <div class="input-wrapper">
                    <input
                        type="email"
                        class="form-control @error('email') is-invalid @enderror"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="name@email.com"
                    >
                    <i class="fas fa-envelope"></i>
                </div>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label class="form-label" for="password">Password</label>
                <div class="input-wrapper">
                    <input
                        type="password"
                        class="form-control @error('password') is-invalid @enderror"
                        id="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••"
                    >
                    <button type="button" class="toggle-btn" id="togglePassword">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </button>
                    <i class="fas fa-lock"></i>
                </div>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        id="remember_me"
                        name="remember"
                    >
                    <label class="form-check-label ms-1" for="remember_me">
                        Remember me
                    </label>
                </div>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" style="font-size: 13px; color: var(--accent-blue); text-decoration: none;">
                        Forgot password?
                    </a>
                @endif
            </div>

            <!-- Submit Button -->
            <div class="btn-group-custom">
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-base btn-cancel" style="text-decoration: none;">
                        Cancel
                    </a>
                @else
                    <button type="button" class="btn-base btn-cancel" onclick="history.back()">
                        Cancel
                    </button>
                @endif
                <button class="btn-base btn-approve" type="submit" id="loginBtn">
                    <i class="fas fa-check-circle"></i> Sign In
                </button>
            </div>
        </form>
    </main>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Password Toggle Functionality -->
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            eyeIcon.classList.toggle('fa-eye');
            eyeIcon.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>
