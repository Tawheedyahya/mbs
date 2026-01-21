{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.app2')

@section('content')

<style>
    .login-page {
        min-height: 100vh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        position: relative;
        overflow: hidden;
    }

    .login-page::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -30%;
        width: 80%;
        height: 120%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 60%);
        animation: pulse-bg 8s ease-in-out infinite;
    }

    .login-page::after {
        content: '';
        position: absolute;
        bottom: -40%;
        right: -20%;
        width: 70%;
        height: 100%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, transparent 50%);
        animation: pulse-bg 10s ease-in-out infinite reverse;
    }

    @keyframes pulse-bg {
        0%, 100% { transform: scale(1) rotate(0deg); opacity: 0.5; }
        50% { transform: scale(1.1) rotate(10deg); opacity: 0.8; }
    }

    .login-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        max-width: 1000px;
        width: 100%;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 32px;
        overflow: hidden;
        box-shadow:
            0 25px 80px -20px rgba(0, 0, 0, 0.3),
            0 0 0 1px rgba(255, 255, 255, 0.5);
        position: relative;
        z-index: 1;
    }

    .login-illustration {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 50px 40px;
        position: relative;
        overflow: hidden;
    }

    .login-illustration::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.08'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    .illustration-svg {
        width: 100%;
        max-width: 320px;
        height: auto;
        position: relative;
        z-index: 1;
        filter: drop-shadow(0 20px 40px rgba(0, 0, 0, 0.15));
    }

    .illustration-text {
        text-align: center;
        margin-top: 30px;
        position: relative;
        z-index: 1;
    }

    .illustration-text h2 {
        color: #fff;
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 10px;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .illustration-text p {
        color: rgba(255, 255, 255, 0.9);
        font-size: 1rem;
        line-height: 1.6;
    }

    .login-form-section {
        padding: 60px 50px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .login-header {
        margin-bottom: 40px;
    }

    .login-header .logo {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 30px;
    }

    .login-header .logo svg {
        width: 45px;
        height: 45px;
    }

    .login-header .logo span {
        font-size: 1.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #4facfe 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .login-header h1 {
        font-size: 2rem;
        font-weight: 700;
        color: #1a1a2e;
        margin-bottom: 8px;
    }

    .login-header p {
        color: #6b7280;
        font-size: 1rem;
    }

    .login-form {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .form-group {
        position: relative;
    }

    .form-group label {
        display: block;
        font-size: 0.9rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
    }

    .input-wrapper {
        position: relative;
    }

    .input-wrapper svg {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        width: 20px;
        height: 20px;
        color: #9ca3af;
        transition: color 0.3s ease;
        pointer-events: none;
    }

    .form-input {
        width: 100%;
        padding: 16px 16px 16px 50px;
        font-size: 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 14px;
        background: #f9fafb;
        color: #1f2937;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        outline: none;
        font-family: inherit;
        box-sizing: border-box;
    }

    .form-input::placeholder {
        color: #9ca3af;
    }

    .form-input:hover {
        border-color: #d1d5db;
        background: #fff;
    }

    .form-input:focus {
        border-color: #4facfe;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(79, 172, 254, 0.15);
    }

    .form-input:focus + svg,
    .form-input:focus ~ svg {
        color: #4facfe;
    }

    .form-input.is-invalid {
        border-color: #ef4444;
    }

    .form-input.is-invalid:focus {
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.15);
    }

    .invalid-feedback {
        display: block;
        color: #ef4444;
        font-size: 0.85rem;
        margin-top: 6px;
    }

    .form-options {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }

    .remember-me {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
    }

    .remember-me input[type="checkbox"] {
        width: 20px;
        height: 20px;
        border: 2px solid #d1d5db;
        border-radius: 6px;
        cursor: pointer;
        accent-color: #4facfe;
    }

    .remember-me span {
        font-size: 0.95rem;
        color: #4b5563;
    }

    .forgot-password {
        color: #4facfe;
        font-size: 0.95rem;
        font-weight: 500;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .forgot-password:hover {
        color: #764ba2;
        text-decoration: underline;
    }

    .submit-btn {
        width: 100%;
        padding: 18px 32px;
        font-size: 1.1rem;
        font-weight: 600;
        color: #fff;
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        border: none;
        border-radius: 14px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        position: relative;
        overflow: hidden;
        margin-top: 10px;
    }

    .submit-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.5s ease;
    }

    .submit-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px -10px rgba(79, 172, 254, 0.5);
    }

    .submit-btn:hover::before {
        left: 100%;
    }

    .submit-btn:active {
        transform: translateY(-1px);
    }

    .submit-btn svg {
        width: 20px;
        height: 20px;
    }

    .signup-link {
        text-align: center;
        margin-top: 30px;
        color: #6b7280;
        font-size: 0.95rem;
    }

    .signup-link a {
        color: #4facfe;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .signup-link a:hover {
        color: #764ba2;
        text-decoration: underline;
    }

    /* Floating Elements */
    .floating-elements {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        pointer-events: none;
        z-index: 0;
    }

    .floating-plus {
        position: absolute;
        opacity: 0.15;
        animation: floatElement 5s ease-in-out infinite;
    }

    .floating-plus:nth-child(1) {
        top: 15%;
        left: 8%;
        animation-delay: 0s;
    }

    .floating-plus:nth-child(2) {
        top: 70%;
        right: 10%;
        animation-delay: 1.5s;
    }

    .floating-plus:nth-child(3) {
        bottom: 20%;
        left: 15%;
        animation-delay: 3s;
    }

    .floating-plus:nth-child(4) {
        top: 40%;
        right: 5%;
        animation-delay: 2s;
    }

    @keyframes floatElement {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(15deg); }
    }

    /* Mobile Responsive */
    @media (max-width: 900px) {
        .login-container {
            grid-template-columns: 1fr;
            max-width: 500px;
        }

        .login-illustration {
            padding: 40px 30px;
        }

        .illustration-svg {
            max-width: 250px;
        }

        .illustration-text h2 {
            font-size: 1.5rem;
        }

        .login-form-section {
            padding: 40px 35px;
        }
    }

    @media (max-width: 600px) {
        .login-page {
            padding: 20px 15px;
        }

        .login-container {
            border-radius: 24px;
        }

        .login-illustration {
            padding: 30px 20px;
        }

        .illustration-svg {
            max-width: 200px;
        }

        .illustration-text h2 {
            font-size: 1.3rem;
        }

        .illustration-text p {
            font-size: 0.9rem;
        }

        .login-form-section {
            padding: 30px 24px;
        }

        .login-header h1 {
            font-size: 1.6rem;
        }

        .login-header .logo span {
            font-size: 1.3rem;
        }

        .form-input {
            padding: 14px 14px 14px 46px;
            font-size: 0.95rem;
            border-radius: 12px;
        }

        .submit-btn {
            padding: 16px 24px;
            font-size: 1rem;
            border-radius: 12px;
        }

        .form-options {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
    }

    @media (max-width: 400px) {
        .login-page {
            padding: 15px 10px;
        }

        .login-form-section {
            padding: 25px 20px;
        }

        .login-header {
            margin-bottom: 30px;
        }

        .login-header .logo {
            margin-bottom: 20px;
        }

        .login-form {
            gap: 20px;
        }
    }
</style>

<div class="login-page">
    <!-- Floating Medical Plus Signs -->
    <div class="floating-elements">
        <svg class="floating-plus" width="40" height="40" viewBox="0 0 24 24" fill="#fff">
            <rect x="10" y="3" width="4" height="18" rx="1"/>
            <rect x="3" y="10" width="18" height="4" rx="1"/>
        </svg>
        <svg class="floating-plus" width="30" height="30" viewBox="0 0 24 24" fill="#fff">
            <rect x="10" y="3" width="4" height="18" rx="1"/>
            <rect x="3" y="10" width="18" height="4" rx="1"/>
        </svg>
        <svg class="floating-plus" width="25" height="25" viewBox="0 0 24 24" fill="#fff">
            <rect x="10" y="3" width="4" height="18" rx="1"/>
            <rect x="3" y="10" width="18" height="4" rx="1"/>
        </svg>
        <svg class="floating-plus" width="35" height="35" viewBox="0 0 24 24" fill="#fff">
            <rect x="10" y="3" width="4" height="18" rx="1"/>
            <rect x="3" y="10" width="18" height="4" rx="1"/>
        </svg>
    </div>

    <div class="login-container">
        <!-- Left Side - Hospital Illustration -->
        <div class="login-illustration">
            <svg class="illustration-svg" viewBox="0 0 400 400" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Hospital Building Background -->
                <rect x="80" y="120" width="240" height="220" rx="8" fill="#fff" opacity="0.95"/>

                <!-- Hospital Main Building -->
                <rect x="100" y="140" width="200" height="180" rx="4" fill="#f0f9ff"/>
                <rect x="100" y="140" width="200" height="180" rx="4" stroke="#0ea5e9" stroke-width="2"/>

                <!-- Hospital Cross -->
                <rect x="175" y="155" width="50" height="50" rx="8" fill="#ef4444"/>
                <rect x="190" y="162" width="20" height="36" rx="2" fill="#fff"/>
                <rect x="177" y="173" width="46" height="14" rx="2" fill="#fff"/>

                <!-- Hospital Windows Row 1 -->
                <rect x="115" y="220" width="35" height="30" rx="4" fill="#bae6fd" stroke="#0ea5e9" stroke-width="1.5"/>
                <rect x="160" y="220" width="35" height="30" rx="4" fill="#bae6fd" stroke="#0ea5e9" stroke-width="1.5"/>
                <rect x="205" y="220" width="35" height="30" rx="4" fill="#bae6fd" stroke="#0ea5e9" stroke-width="1.5"/>
                <rect x="250" y="220" width="35" height="30" rx="4" fill="#bae6fd" stroke="#0ea5e9" stroke-width="1.5"/>

                <!-- Hospital Windows Row 2 -->
                <rect x="115" y="260" width="35" height="30" rx="4" fill="#bae6fd" stroke="#0ea5e9" stroke-width="1.5"/>
                <rect x="160" y="260" width="35" height="30" rx="4" fill="#bae6fd" stroke="#0ea5e9" stroke-width="1.5"/>
                <rect x="205" y="260" width="35" height="30" rx="4" fill="#bae6fd" stroke="#0ea5e9" stroke-width="1.5"/>
                <rect x="250" y="260" width="35" height="30" rx="4" fill="#bae6fd" stroke="#0ea5e9" stroke-width="1.5"/>

                <!-- Hospital Door -->
                <rect x="170" y="300" width="60" height="40" rx="4" fill="#0ea5e9"/>
                <rect x="175" y="305" width="22" height="35" rx="2" fill="#0369a1"/>
                <rect x="203" y="305" width="22" height="35" rx="2" fill="#0369a1"/>
                <circle cx="195" cy="322" r="3" fill="#fbbf24"/>
                <circle cx="205" cy="322" r="3" fill="#fbbf24"/>

                <!-- Ambulance -->
                <g>
                    <rect x="50" y="290" width="70" height="45" rx="6" fill="#fff" stroke="#ef4444" stroke-width="2"/>
                    <rect x="50" y="290" width="70" height="15" rx="6" fill="#ef4444"/>
                    <rect x="70" y="295" width="30" height="5" rx="1" fill="#fff"/>
                    <circle cx="65" cy="340" r="10" fill="#374151"/>
                    <circle cx="65" cy="340" r="5" fill="#6b7280"/>
                    <circle cx="105" cy="340" r="10" fill="#374151"/>
                    <circle cx="105" cy="340" r="5" fill="#6b7280"/>
                    <rect x="55" y="308" width="20" height="15" rx="2" fill="#bae6fd"/>
                    <!-- Ambulance Cross -->
                    <rect x="88" y="305" width="18" height="18" rx="3" fill="#ef4444"/>
                    <rect x="94" y="308" width="6" height="12" rx="1" fill="#fff"/>
                    <rect x="90" y="311" width="14" height="6" rx="1" fill="#fff"/>
                    <!-- Light -->
                    <rect x="75" y="283" width="20" height="8" rx="3" fill="#3b82f6">
                        <animate attributeName="opacity" values="1;0.3;1" dur="1s" repeatCount="indefinite"/>
                    </rect>
                </g>

                <!-- Stethoscope -->
                <g transform="translate(280, 250)">
                    <path d="M20 0 Q0 20 5 50" stroke="#374151" stroke-width="4" fill="none" stroke-linecap="round"/>
                    <path d="M40 0 Q60 20 55 50" stroke="#374151" stroke-width="4" fill="none" stroke-linecap="round"/>
                    <path d="M5 50 Q30 80 55 50" stroke="#374151" stroke-width="4" fill="none" stroke-linecap="round"/>
                    <circle cx="30" cy="75" r="15" fill="#374151"/>
                    <circle cx="30" cy="75" r="10" fill="#6b7280"/>
                    <circle cx="20" cy="0" r="6" fill="#9ca3af"/>
                    <circle cx="40" cy="0" r="6" fill="#9ca3af"/>
                </g>

                <!-- Heartbeat Line -->
                <path d="M40 180 L70 180 L80 160 L95 200 L110 170 L120 190 L130 180 L360 180"
                      stroke="#ef4444" stroke-width="3" fill="none" stroke-linecap="round" stroke-linejoin="round" opacity="0.6">
                    <animate attributeName="stroke-dasharray" values="0,500;500,0" dur="2s" repeatCount="indefinite"/>
                </path>

                <!-- Medical Shield -->
                <g transform="translate(300, 120)">
                    <path d="M30 0 L55 10 L55 35 Q55 60 30 75 Q5 60 5 35 L5 10 Z" fill="#10b981"/>
                    <rect x="24" y="20" width="12" height="35" rx="2" fill="#fff"/>
                    <rect x="15" y="32" width="30" height="12" rx="2" fill="#fff"/>
                </g>

                <!-- Floating Hearts -->
                <g opacity="0.7">
                    <path d="M60 100 C60 92 70 92 70 100 C70 92 80 92 80 100 C80 115 70 125 70 125 C70 125 60 115 60 100" fill="#f472b6">
                        <animateTransform attributeName="transform" type="translate" values="0,0; 0,-15; 0,0" dur="3s" repeatCount="indefinite"/>
                    </path>
                    <path d="M320 80 C320 74 328 74 328 80 C328 74 336 74 336 80 C336 92 328 100 328 100 C328 100 320 92 320 80" fill="#a78bfa">
                        <animateTransform attributeName="transform" type="translate" values="0,0; 0,-10; 0,0" dur="2.5s" repeatCount="indefinite"/>
                    </path>
                </g>

                <!-- Ground -->
                <ellipse cx="200" cy="355" rx="150" ry="15" fill="#0ea5e9" opacity="0.2"/>

                <!-- Trees -->
                <g transform="translate(320, 280)">
                    <rect x="15" y="35" width="10" height="25" fill="#92400e"/>
                    <circle cx="20" cy="25" r="20" fill="#22c55e"/>
                    <circle cx="10" cy="30" r="15" fill="#16a34a"/>
                    <circle cx="30" cy="32" r="12" fill="#15803d"/>
                </g>
            </svg>

            <div class="illustration-text">
                <h2>Welcome to HealthCare</h2>
                <p>Your trusted partner for quality medical care and wellness</p>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="login-form-section">
            <div class="login-header">
                <div class="logo">
                    <svg viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="50" height="50" rx="12" fill="url(#logoGradient)"/>
                        <rect x="20" y="10" width="10" height="30" rx="2" fill="#fff"/>
                        <rect x="10" y="20" width="30" height="10" rx="2" fill="#fff"/>
                        <defs>
                            <linearGradient id="logoGradient" x1="0" y1="0" x2="50" y2="50">
                                <stop offset="0%" stop-color="#4facfe"/>
                                <stop offset="100%" stop-color="#00f2fe"/>
                            </linearGradient>
                        </defs>
                    </svg>
                    <span>HealthCare</span>
                </div>
                <h1>Welcome Back!</h1>
                <p>Please sign in to access your account</p>
            </div>

            <form class="login-form" method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-wrapper">
                        <input
                            id="email"
                            type="email"
                            class="form-input @error('email') is-invalid @enderror"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="Enter your email"
                            required
                            autocomplete="email"
                            autofocus
                        >
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                    </div>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <input
                            id="password"
                            type="password"
                            class="form-input @error('password') is-invalid @enderror"
                            name="password"
                            placeholder="Enter your password"
                            required
                            autocomplete="current-password"
                        >
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                    </div>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-options">
                    <label class="remember-me">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span>Remember Me</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="forgot-password" href__="{{ route('password.request') }}">
                            Forgot Password?
                        </a>
                    @endif
                </div>

                <button type="submit" class="submit-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                        <polyline points="10 17 15 12 10 7"/>
                        <line x1="15" y1="12" x2="3" y2="12"/>
                    </svg>
                    Sign In
                </button>
            </form>

            @if (Route::has('register'))
                <p class="signup-link">
                    Don't have an account? <a href__="{{ route('register') }}">Sign Up</a>
                </p>
            @endif
        </div>
    </div>
</div>

@endsection
