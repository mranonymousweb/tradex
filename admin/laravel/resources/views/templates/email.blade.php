<!-- resources/views/emails/custom.blade.php -->
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('app.name') }}</title>
    <style>
        body {
            font-family: 'Tahoma', sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #6366f1, #4338ca);
            color: white;
            text-align: center;
            padding: 30px 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 22px;
        }

        .content {
            padding: 30px 25px;
            text-align: right;
            color: #374151;
            line-height: 1.8;
        }

        .content h2 {
            font-size: 20px;
            margin-bottom: 15px;
            color: #111827;
        }

        .content p {
            font-size: 15px;
            margin: 0 0 20px;
        }

        .btn {
            display: inline-block;
            background: #6366f1;
            color: white !important;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        .btn:hover {
            background: #4338ca;
        }

        .footer {
            background: #f9fafb;
            text-align: center;
            padding: 15px;
            font-size: 12px;
            color: #6b7280;
        }
    </style>
</head>
<body>
<div class="email-container">
    <!-- Header -->
    <div class="header">
        <h1>{{ $title ?? config('app.name') }}</h1>
    </div>

    <!-- Content -->
    <div class="content">
        <h2> @lang('general.hi') {{ $notifiable->getFullName() ?? '' }} 👋</h2>
        <h3>{!! nl2br(e($description ?? '')) !!}</h3>

        <div style="margin-top: 25px; text-align: center;">
            <a href="{{ config('app.front_url') }}" class="btn">@lang('general.back_to_site')</a>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        © {{ date('Y') }} {{ config('app.name') }} @lang('general.all_rights_reserved')
    </div>
</div>
</body>
</html>
