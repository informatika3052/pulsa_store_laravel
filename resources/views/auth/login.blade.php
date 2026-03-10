<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | PulsaStore Pro</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%);
            display: flex; align-items: center; justify-content: center;
        }
        .login-card {
            background: rgba(255,255,255,.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 24px;
            padding: 2.5rem;
            width: 100%; max-width: 420px;
            box-shadow: 0 25px 50px rgba(0,0,0,.5);
        }
        .brand-icon {
            width: 64px; height: 64px;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            border-radius: 18px;
            display: flex; align-items: center; justify-content: center;
            font-size: 28px; color: white;
            margin: 0 auto 1.5rem;
            box-shadow: 0 10px 30px rgba(79,70,229,.4);
        }
        .form-control-dark {
            background: rgba(255,255,255,.08) !important;
            border: 1px solid rgba(255,255,255,.15) !important;
            color: white !important;
            border-radius: 10px;
            padding: .75rem 1rem;
        }
        .form-control-dark::placeholder { color: rgba(255,255,255,.4); }
        .form-control-dark:focus {
            background: rgba(255,255,255,.12) !important;
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 3px rgba(99,102,241,.2) !important;
        }
        .btn-login {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            border: none; border-radius: 10px;
            padding: .75rem; font-weight: 600;
            transition: all .2s;
        }
        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(79,70,229,.4);
        }
        .input-group-dark .input-group-text {
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.15);
            border-right: none; color: rgba(255,255,255,.5);
        }
        .input-group-dark .form-control-dark { border-left: none !important; }
        label { color: rgba(255,255,255,.7); font-size: .875rem; font-weight: 500; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="brand-icon"><i class="bi bi-phone-fill"></i></div>
        <h4 class="text-white text-center fw-bold mb-1">PulsaStore Pro</h4>
        <p class="text-center mb-4" style="color:rgba(255,255,255,.5);font-size:.875rem">
            Sistem Manajemen Inventaris Toko Pulsa
        </p>

        @if(session('error'))
            <div class="alert alert-danger d-flex align-items-center gap-2 mb-3" style="border-radius:10px">
                <i class="bi bi-exclamation-triangle-fill"></i>
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email</label>
                <div class="input-group input-group-dark">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="form-control form-control-dark @error('email') is-invalid @enderror"
                        placeholder="email@toko.com" required autofocus>
                </div>
                @error('email')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label">Password</label>
                <div class="input-group input-group-dark">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password"
                        class="form-control form-control-dark @error('password') is-invalid @enderror"
                        placeholder="••••••••" required>
                </div>
                @error('password')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember" style="color:rgba(255,255,255,.6)">
                    Ingat saya
                </label>
            </div>

            <button type="submit" class="btn btn-login btn-primary w-100 text-white">
                <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
            </button>
        </form>

        <p class="text-center mt-4 mb-0" style="color:rgba(255,255,255,.3);font-size:.75rem">
            © {{ date('Y') }} PulsaStore Pro. All rights reserved.
        </p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
