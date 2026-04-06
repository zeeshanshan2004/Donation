<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background: linear-gradient(135deg, #000000, #2d2b28);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
            overflow: hidden;
        }

        /* Floating particles background */
        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            top: 0;
            left: 0;
            z-index: 0;
        }
        .particle {
            position: absolute;
            background-color: rgba(220, 205, 138, 0.7);
            border-radius: 50%;
            animation: floatUp 10s linear infinite;
        }
        @keyframes floatUp {
            0% { transform: translateY(100vh) scale(0.5); opacity: 0; }
            50% { opacity: 1; }
            100% { transform: translateY(-10vh) scale(1.2); opacity: 0; }
        }

        /* Glassmorphic login popup */
       .login-card {
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(220, 205, 138, 0.3);
    backdrop-filter: blur(14px);
    border-radius: 18px;
    box-shadow:
        0 0 40px rgba(220, 205, 138, 0.55),
        0 0 80px rgba(220, 205, 138, 0.25),
        inset 0 0 25px rgba(255, 255, 255, 0.05);
    width: 100%;
    max-width: 440px;
    padding: 40px;
    text-align: center;
    position: relative;
    z-index: 2;
    animation: popupAppear 1s ease forwards;
    transform: scale(0.9);
    opacity: 0;
    transition: all 0.4s ease;
}

.login-card:hover {
    transform: scale(0.93);
    box-shadow:
        0 0 60px rgba(220, 205, 138, 0.7),
        0 0 100px rgba(220, 205, 138, 0.3),
        inset 0 0 30px rgba(255, 255, 255, 0.07);
}


        @keyframes popupAppear {
            to { transform: scale(1); opacity: 1; }
        }

        .logo {
            display: block;
            margin: 0 auto 15px auto;
            height: 95px;
            width: auto;
            transition: 0.3s ease;
            filter: drop-shadow(0 0 10px rgba(220, 205, 138, 0.6));
        }
        .logo:hover {
            transform: scale(1.08);
        }

        .ai-header {
            display: inline-block;
            background: linear-gradient(135deg, #000000, #1a1a1a);
            padding: 10px 28px;
            border-radius: 40px;
            color: #dccd8a;
            font-weight: 600;
            box-shadow: 0 0 25px rgba(220, 205, 138, 0.8);
            animation: aiGlow 3s infinite ease-in-out;
            margin-bottom: 25px;
        }

        @keyframes aiGlow {
            0%, 100% {
                box-shadow: 0 0 15px rgba(220, 205, 138, 0.5);
                transform: scale(1);
            }
            50% {
                box-shadow: 0 0 25px rgba(220, 205, 138, 0.9);
                transform: scale(1.05);
            }
        }

        label, .form-control {
            color: #fff;
        }
        .form-control {
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(220, 205, 138, 0.3);
            color: #fff;
        }
        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .btn-dark {
            background-color: #000;
            border: 1px solid #dccd8a;
            color: #dccd8a;
            transition: 0.3s;
        }
        .btn-dark:hover {
            background-color: #dccd8a;
            color: #000;
        }
    </style>
</head>
<body>

    <!-- Floating Particles -->
    <div class="particles" id="particles"></div>

    <!-- Login Card -->
    <div class="login-card">
        <img src="{{ asset('admin-assets/images/logo.png') }}" alt="Logo" class="logo">

        <div class="ai-header">
             Kelsie-Lichtenfeld Admin
        </div>

        @if(session('error'))
            <div class="alert alert-danger py-2">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf
            <div class="mb-3 text-start">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Enter email" required>
            </div>

            <div class="mb-3 text-start">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-dark">Login</button>
            </div>
        </form>
    </div>

    <!-- Welcome AI Popup -->
    <!-- Particle container in your dashboard blade -->
<div id="particles"></div>

<style>
#particles {
    position: fixed;
    top: 0; left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none; /* taake clickable content pe effect na ho */
    z-index: 0;
}
.particle {
    position: absolute;
    background: #dccd8a;
    border-radius: 50%;
    opacity: 0.7;
    animation: float 6s infinite ease-in-out;
}

@keyframes float {
    0% { transform: translateY(0px); opacity: 0.5; }
    50% { transform: translateY(-30px); opacity: 1; }
    100% { transform: translateY(0px); opacity: 0.5; }
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const container = document.getElementById("particles");
    for (let i = 0; i < 16; i++) {
        const p = document.createElement("div");
        p.classList.add("particle");
        const size = Math.random() * 8 + 3; // size random
        p.style.width = `${size}px`;
        p.style.height = `${size}px`;
        p.style.left = `${Math.random() * 100}%`;
        p.style.top = `${Math.random() * 100}%`;
        p.style.animationDelay = `${Math.random() * 5}s`;
        container.appendChild(p);
    }
});
</script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
