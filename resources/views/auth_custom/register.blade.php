<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="text-center d-flex align-items-center bg-light" style="min-height: 100vh;">

    <main class="form-signin w-100" style="max-width: 360px; margin: auto; padding: 2rem;">
        <h1 class="h3 mb-3 fw-normal">Create an account</h1>

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

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div class="form-floating mb-3">
                <input
                    type="text"
                    class="form-control @error('name') is-invalid @enderror"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    placeholder="Your Name"
                >
                <label for="name">Full Name</label>
                @error('name')
                    <div class="invalid-feedback text-start">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email -->
            <div class="form-floating mb-3">
                <input
                    type="email"
                    class="form-control @error('email') is-invalid @enderror"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="username"
                    placeholder="name@example.com"
                >
                <label for="email">Email address</label>
                @error('email')
                    <div class="invalid-feedback text-start">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-floating mb-3">
                <input
                    type="password"
                    class="form-control @error('password') is-invalid @enderror"
                    id="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    placeholder="Password"
                >
                <label for="password">Password</label>
                @error('password')
                    <div class="invalid-feedback text-start">{{ $message }}</div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="form-floating mb-3">
                <input
                    type="password"
                    class="form-control"
                    id="password_confirmation"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="Confirm Password"
                >
                <label for="password_confirmation">Confirm Password</label>
            </div>

            <!-- Submit -->
            <button class="w-100 btn btn-lg btn-success" type="submit">
                Register
            </button>

            <!-- Login Link -->
            <div class="mt-3">
                <a href="{{ route('login') }}" class="text-decoration-none small">
                    Already have an account? Login
                </a>
            </div>
        </form>
    </main>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
