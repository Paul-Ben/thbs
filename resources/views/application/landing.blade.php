<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="icon" href="{{ asset('assets/img/favicon_io/favicon.ico') }}" type="image/x-icon" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BSUTH - Application Portal</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .background-logo {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 90vw;
            max-width: 600px;
            opacity: 0.09;
            transform: translate(-50%, -50%) scale(1);
            z-index: 0;
            pointer-events: none;
        }

        .login-card {
            position: relative;
            z-index: 2;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.18);
            padding: 3rem 2rem 2.5rem 2rem;
            width: 100%;
            animation: fadeIn 0.6s ease-in-out;
        }

        @media (max-width: 767px) {
            .background-logo {
                display: none;
            }

            .login-card {
                padding: 2rem 0.7rem 1.5rem 0.7rem;
            }
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body,
        html {
            height: 100%;
            background: #f8f9fa;
        }

        .left-image-container {
            position: relative;
            height: 100vh;
            overflow: hidden;
            background: #f0f2f5;
            background: transparent;
        }

        .logo-bg {
            position: absolute;
            left: 50%;
            top: 0;
            transform: translateX(-50%) translateY(0);
            width: 65%;
            opacity: 0.15;
            z-index: 1;
        }

        .student-img {
            position: relative;
            z-index: 3;
            width: 80%;
            object-fit: cover;
            object-position: 0 80px;
            /* move image content down to show face */
        }

        .wave {
            font-size: 2.2rem;
            margin-right: 0.5rem;
        }

        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
        }

        .brand-heading {
            color: #1a2035;
            font-weight: 600;
        }

        .form-label {
            color: #1a2035;
            font-weight: 500;
        }

        .form-control {
            border-radius: 12px;
            background: transparent !important;
            color: #1a2035;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #f96332;
        }

        .btn-signin {
            background-color: #1a2035;
            color: #fff;
            border-radius: 12px;
            padding: 0.75rem 1.25rem;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }

        .btn-signin:hover {
            background-color: #f96332;
            color: #fff;
        }

        .btn-outline-primary {
            border-color: #f96332 !important;
            color: #f96332 !important;
            font-weight: 500;
            border-radius: 12px;
        }

        .btn-outline-primary:hover,
        .btn-outline-primary:focus {
            background-color: #f96332 !important;
            color: #fff !important;
        }

        .btn-outline-secondary {
            border-color: #1a2035 !important;
            color: #1a2035 !important;
            font-weight: 500;
            border-radius: 12px;
        }

        .btn-outline-secondary:hover,
        .btn-outline-secondary:focus {
            background-color: #1a2035 !important;
            color: #fff !important;
        }

        .extra-links {
            margin-top: 2rem;
            text-align: center;
            font-size: 0.95rem;
        }

        .extra-links a {
            color: #1a2035;
            font-weight: 500;
            margin: 0 8px;
        }

        .extra-links a:hover {
            color: #f96332;
        }
    </style>
</head>

<body>
    <div class="container-fluid g-0">
        <div class="row g-0 min-vh-100">
           
            <div class="col-md-6 d-none d-md-flex align-items-end justify-content-center left-image-container">
                <img src="{{ asset('assets/img/bsth-logo.jpeg') }}" alt="Logo" class="logo-bg">
                <img src="{{ asset('assets/img/student_smiling1.png') }}" alt="Smiling Student"
                    class="student-img mb-4">
            </div>
           
            <div class="col-md-6 login-container d-flex flex-column align-items-center justify-content-start pt-3">
                <div class="w-100" style="max-width: 500px;">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <img src="{{ asset('assets/img/bsth-logo.jpeg') }}" alt="BSTH Logo" style="width:80px;">
                    </div>
                    <div class="text-center mb-2">
                        <div style="font-size:1.2em; font-weight:bold; color:#900;">BENUE STATE UNIVERSITY TEACHING
                            HOSPITAL MAKURDI</div>
                        <div style="font-size:1em; font-weight:bold;">INSTITUTE OF HEALTH AND TECHNOLOGY</div>
                        <div
                            style="margin:0.5em auto; background-color:#000; color:#fff; font-size:1.1em; font-weight:bold; display:inline-block; padding:2px 16px; border-radius:4px;">
                            Welcome back <span style="font-size:1.2em;">👋</span>
                        </div>
                    </div>
                    <form method="POST" action="#" class="mt-4 mb-4">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-signin w-100 mb-3">Sign In</button>
                    </form>
                    <div class="extra-links mt-4">
                        <a href="{{ route('application.create') }}">Apply</a>
                        <a href="#">All Programs</a>
                        <a href="#">About Us</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS Bundle CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
