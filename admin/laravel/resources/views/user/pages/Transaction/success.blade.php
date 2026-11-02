<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('payment.success_payment')</title>
    <style>
        @font-face {
            font-family: 'yekan';
            src: url('../font/yekan/YekanBakh-Medium.eot');
            src: url('../font/yekan/YekanBakh-Medium.eot?#iefix') format('embedded-opentype'),
            url('../font/yekan/YekanBakh-Medium.woff') format('woff');
            font-weight: 500;
            font-style: medium;
            font-display: swap;
        }

        body {
            background: linear-gradient(135deg, #e6fffa, #c6f6d5);
            color: #2f855a;
            font-family: 'yekan', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            text-align: center;
            overflow: hidden;
        }

        .card {
            background: white;
            border-radius: 20px;
            padding: 30px 20px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            width: 90%;
            max-width: 400px;
            animation: popUp 0.8s ease;
        }

        @keyframes popUp {
            0% {
                transform: scale(0.7);
                opacity: 0;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .icon {
            font-size: 80px;
            color: #38a169;
            margin-bottom: 20px;
            animation: bounce 1.5s infinite;
        }

        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-8px);
            }
        }

        h1 {
            font-size: 26px;
            margin-bottom: 10px;
        }

        p {
            font-size: 18px;
            margin-top: 5px;
        }

        .btn {
            margin-top: 30px;
            background: linear-gradient(90deg, #38a169, #2f855a);
            color: white;
            padding: 14px 35px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: bold;
            font-size: 18px;
            transition: background 0.3s;
            display: inline-block;
        }

        .btn:hover {
            background: linear-gradient(90deg, #2f855a, #38a169);
        }
    </style>
</head>
<body>
<div class="card">
    <div class="icon">🎉</div>
    <h1>@lang('payment.success_title')</h1>
    <p>@lang('payment.success_description')</p>
    <p> : @lang('orders.tracking_code')<strong>{{ $payment->tracking_code }}</strong></p>
    <a href="/" class="btn">@lang('payment.back_to_main_page')</a>
</div>
</body>
</html>
