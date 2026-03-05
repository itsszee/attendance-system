@props(['title' => 'Authentication', 'subtitle' => 'Attendance System', 'icon' => '<i class="fas fa-clipboard-check"></i>'])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} - Attendance System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Animated Background Circles */
        .bg-circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            animation: float 20s infinite;
        }

        .bg-circle:nth-child(1) {
            width: 300px;
            height: 300px;
            top: -150px;
            right: -150px;
            animation-delay: 0s;
        }

        .bg-circle:nth-child(2) {
            width: 200px;
            height: 200px;
            bottom: -100px;
            left: -100px;
            animation-delay: 5s;
        }

        .bg-circle:nth-child(3) {
            width: 150px;
            height: 150px;
            top: 50%;
            left: 10%;
            animation-delay: 10s;
        }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -30px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
        }

        .auth-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.3);
            max-width: 440px;
            width: 100%;
            padding: 50px 40px;
            position: relative;
            z-index: 10;
            animation: slideUp 0.6s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo-section {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }

        .logo-icon i {
            font-size: 40px;
            color: white;
        }

        h1 {
            font-size: 28px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 8px;
        }

        .subtitle {
            color: #666;
            font-size: 15px;
        }

        .alert {
            background: #fee;
            border: 1px solid #fcc;
            color: #c33;
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
            animation: shake 0.4s;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }

        .alert i {
            flex-shrink: 0;
            margin-top: 2px;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }

        .alert ul {
            margin: 0;
            padding-left: 20px;
            list-style: none;
        }

        .alert li {
            margin: 4px 0;
        }

        .success-alert {
            background: #efe;
            border: 1px solid #cfc;
            color: #3c3;
        }

        .info-alert {
            background: #eef;
            border: 1px solid #ccf;
            color: #33c;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 18px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 14px 16px 14px 48px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: #fafafa;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .form-group small {
            display: block;
            margin-top: 6px;
            color: #999;
            font-size: 12px;
        }

        .checkbox-wrapper {
            display: flex;
            align-items: flex-start;
            margin-bottom: 24px;
        }

        .checkbox-wrapper input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-right: 8px;
            margin-top: 2px;
            cursor: pointer;
            padding: 0;
            flex-shrink: 0;
        }

        .checkbox-wrapper label {
            margin: 0;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
        }

        .checkbox-wrapper a {
            color: #667eea;
            text-decoration: none;
        }

        .checkbox-wrapper a:hover {
            text-decoration: underline;
        }

        .btn-primary {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(102, 126, 234, 0.4);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-secondary {
            width: 100%;
            padding: 14px;
            background: white;
            color: #667eea;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-secondary:hover {
            border-color: #667eea;
            background: #f5f7ff;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 30px 0;
            color: #999;
            font-size: 14px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e0e0e0;
        }

        .divider::before {
            margin-right: 12px;
        }

        .divider::after {
            margin-left: 12px;
        }

        .action-link {
            text-align: center;
            font-size: 14px;
            color: #666;
            margin-top: 20px;
        }

        .action-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            display: block;
            margin: 8px 0;
        }

        .action-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .auth-container {
                padding: 40px 30px;
            }

            h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="bg-circle"></div>
    <div class="bg-circle"></div>
    <div class="bg-circle"></div>

    <div class="auth-container">
        <div class="logo-section">
            <div class="logo-icon">
                {!! $icon !!}
            </div>
            <h1>{{ $title }}</h1>
            <p class="subtitle">{{ $subtitle }}</p>
        </div>

        {{ $slot }}
    </div>
</body>
</html>
