<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@lang('payment.fail_payment')</title>
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
            background: linear-gradient(135deg, #fff5f5, #fed7d7);
            color: #e53e3e;
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
            animation: shake 0.8s;
        }

        @keyframes shake {
            0% {
                transform: translateX(0);
            }
            20% {
                transform: translateX(-10px);
            }
            40% {
                transform: translateX(10px);
            }
            60% {
                transform: translateX(-8px);
            }
            80% {
                transform: translateX(8px);
            }
            100% {
                transform: translateX(0);
            }
        }

        .icon {
            font-size: 80px;
            color: #e53e3e;
            margin-bottom: 20px;
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
            background: linear-gradient(90deg, #e53e3e, #c53030);
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
            background: linear-gradient(90deg, #c53030, #e53e3e);
        }
    </style>
</head>
<body>
<div class="card">
    <div class="icon">⚠️</div>
    <h1>@lang('payment.fail_title')</h1>
    <p>@lang('payment.fail_description')</p>
    <p>@lang('payment.try_again_later')</p>
    <a href="/" class="btn">@lang('payment.back_to_main_page')</a>
</div>
</body>
</html>
