<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>BengkelConnect Login</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
          rel="stylesheet">

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:'Poppins', sans-serif;
        }

        body{
            background:#d70000;
            min-height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            padding:20px;
        }

        .wrapper{
            width:100%;
            max-width:450px;
        }

        /* =========================
            LOGO
        ========================== */

        .logo{
            text-align:center;
            color:white;
            margin-bottom:25px;
        }

        .logo h1{
            font-size:42px;
            font-weight:700;
            margin-bottom:8px;
        }

        .logo p{
            font-size:16px;
            opacity:0.95;
        }

        /* =========================
            LOGIN BOX
        ========================== */

        .login-box{
            background:white;
            border-radius:30px;
            padding:40px 35px;
            box-shadow:0 10px 30px rgba(0,0,0,0.15);
        }

        .login-box h2{
            font-size:36px;
            font-weight:700;
            color:#111827;
            margin-bottom:5px;
        }

        .subtitle{
            color:#6b7280;
            margin-bottom:28px;
            font-size:16px;
        }

        /* =========================
            INPUT
        ========================== */

        label{
            font-size:15px;
            font-weight:600;
            color:#111827;
            margin-bottom:10px;
            display:block;
        }

        .form-control{
            width:100%;
            height:58px;
            border-radius:18px;
            border:1px solid #ddd;
            padding:0 18px;
            font-size:16px;
            margin-bottom:22px;
        }

        .form-control:focus{
            border:1px solid red;
            box-shadow:none;
        }

        /* =========================
            PASSWORD
        ========================== */

        .password-box{
            position:relative;
        }

        .password-box input{
            padding-right:55px;
        }

        .toggle-password{
            position:absolute;
            right:20px;
            top:18px;
            font-size:22px;
            color:#777;
            cursor:pointer;
        }

        .toggle-password:hover{
            color:red;
        }

        /* =========================
            REMEMBER
        ========================== */

        .remember{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:25px;
            font-size:14px;
        }

        .remember a{
            text-decoration:none;
            color:red;
            font-weight:600;
        }

        /* =========================
            BUTTON LOGIN
        ========================== */

        .btn-login{
            width:100%;
            height:58px;
            border:none;
            border-radius:18px;
            background:#ff0000;
            color:white;
            font-size:22px;
            font-weight:700;
            transition:0.3s;
        }

        .btn-login:hover{
            background:#c40000;
        }

        /* =========================
            OR
        ========================== */

        .or{
            text-align:center;
            margin:28px 0;
            position:relative;
            color:#777;
            font-size:15px;
        }

        .or::before{
            content:'';
            position:absolute;
            width:40%;
            height:1px;
            background:#ddd;
            left:0;
            top:50%;
        }

        .or::after{
            content:'';
            position:absolute;
            width:40%;
            height:1px;
            background:#ddd;
            right:0;
            top:50%;
        }

        /* =========================
            GUEST BUTTON
        ========================== */

        .btn-guest{
            width:100%;
            height:58px;
            background:#f3f4f6;
            border:none;
            border-radius:18px;
            font-size:18px;
            font-weight:600;
            color:#111827;
            text-decoration:none;
            display:flex;
            justify-content:center;
            align-items:center;
            transition:0.3s;
        }

        .btn-guest:hover{
            background:#e5e7eb;
        }

        /* =========================
            REGISTER
        ========================== */

        .register{
            text-align:center;
            margin-top:28px;
            font-size:16px;
        }

        .register a{
            text-decoration:none;
            color:red;
            font-weight:700;
        }

        .footer{
            text-align:center;
            margin-top:25px;
            color:white;
            font-size:14px;
        }

        @media(max-width:500px){

            .login-box{
                padding:30px 22px;
            }

            .logo h1{
                font-size:34px;
            }

            .login-box h2{
                font-size:30px;
            }

        }

    </style>

</head>

<body>

    <div class="wrapper">

        <!-- LOGO -->
        <div class="logo">

            <h1>
                🔧 BengkelConnect
            </h1>

            <p>
                Layanan Bengkel & Home Service Terpercaya
            </p>

        </div>

        <!-- LOGIN BOX -->
        <div class="login-box">

            <h2>
                Masuk
            </h2>

            <p class="subtitle">
                Masuk ke akun Anda
            </p>

            @if(session('error'))

                <div class="alert alert-danger">

                    {{ session('error') }}

                </div>

            @endif

            @if(session('success'))

                <div class="alert alert-success">

                    {{ session('success') }}

                </div>

            @endif

            <form action="/login" method="POST">

                @csrf

                <!-- EMAIL -->
                <label>Email</label>

                <input type="email"
                       name="email"
                       class="form-control"
                       placeholder="nama@email.com"
                       required>

                <!-- PASSWORD -->
                <label>Password</label>

                <div class="password-box">

                    <input type="password"
                           id="password"
                           name="password"
                           class="form-control"
                           placeholder="Masukkan password"
                           required>

                    <i class="bi bi-eye-slash toggle-password"
                       id="togglePassword"></i>

                </div>

                <!-- REMEMBER -->
                <div class="remember">

                    <div>

                        <input type="checkbox" name="remember">

                        Ingat saya

                    </div>

                    <a href="#">
                        Lupa password?
                    </a>

                </div>

                <!-- LOGIN -->
                <button type="submit"
                        class="btn-login">

                    Masuk

                </button>

            </form>

            <!-- OR -->
            <div class="or">
                Atau
            </div>

            <!-- GUEST -->
            <a href="/dashboard"
               class="btn-guest">

                Lanjutkan Tanpa Login

            </a>

            <!-- REGISTER -->
            <div class="register">

                Belum punya akun?

                <a href="/register">

                    Daftar sekarang

                </a>

            </div>

        </div>

        <!-- FOOTER -->
        <div class="footer">

            © 2026 BengkelConnect. All rights reserved.

        </div>

    </div>

    <!-- SHOW PASSWORD -->
    <script>

        const togglePassword =
        document.getElementById('togglePassword');

        const password =
        document.getElementById('password');

        togglePassword.addEventListener('click', function(){

            const type =
                password.getAttribute('type') === 'password'
                ? 'text'
                : 'password';

            password.setAttribute('type', type);

            this.classList.toggle('bi-eye');

            this.classList.toggle('bi-eye-slash');

        });

    </script>

</body>
</html>