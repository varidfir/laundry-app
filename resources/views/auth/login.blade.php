<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Laundry App</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>

        body{
            background: linear-gradient(135deg, #1e293b, #0f172a);
            height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            font-family: Arial;
        }

        .login-card{
            width:380px;
            background:white;
            border-radius:15px;
            padding:30px;
            box-shadow:0 10px 30px rgba(0,0,0,.3);
        }

        .login-title{
            text-align:center;
            margin-bottom:20px;
        }

        .login-title h2{
            margin:0;
            font-weight:bold;
            color:#1e293b;
        }

        .form-control{
            border-radius:10px;
            padding:10px;
        }

        .btn-login{
            width:100%;
            border-radius:10px;
            padding:10px;
            background:#1e293b;
            color:white;
            font-weight:bold;
        }

        .btn-login:hover{
            background:#0f172a;
        }

        .password-input-group {
            position: relative;
        }

        .password-toggle-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #6c757d;
            padding: 5px 8px;
        }

        .password-toggle-btn:hover {
            color: #1e293b;
        }

        .form-control-password {
            padding-right: 40px;
        }

    </style>

</head>
<body>

<div class="login-card">

    <div class="login-title">
        <h2>Laundry App</h2>
        <p class="text-muted">Silakan login untuk masuk</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- EMAIL --}}
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        {{-- PASSWORD --}}
        <div class="mb-3">
            <label>Password</label>
            <div class="password-input-group">
                <input type="password" name="password" class="form-control form-control-password" id="loginPassword" required>
                <button type="button" class="password-toggle-btn" onclick="togglePasswordLogin()">
                    <i class="bi bi-eye" id="loginPasswordIcon"></i>
                </button>
            </div>
        </div>

        {{-- BUTTON --}}
        <button type="submit" class="btn btn-login">
            Login
        </button>

 

    </form>

</div>

<script>
    function togglePasswordLogin() {
        const passwordInput = document.getElementById('loginPassword');
        const passwordIcon = document.getElementById('loginPasswordIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            passwordIcon.classList.remove('bi-eye');
            passwordIcon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            passwordIcon.classList.remove('bi-eye-slash');
            passwordIcon.classList.add('bi-eye');
        }
    }
</script>

</body>
</html>