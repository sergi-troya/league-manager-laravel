<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') — League Manager</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f8f9fa;
            color: #212529;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            text-align: center;
        }
        .container { max-width: 480px; width: 100%; }
        .error-code { font-size: 5rem; font-weight: 800; line-height: 1; color: #6c757d; margin-bottom: 0.75rem; }
        .error-code.danger { color: #dc3545; }
        .error-title { font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem; }
        .error-desc { font-size: 1rem; color: #6c757d; margin-bottom: 1.75rem; line-height: 1.5; }
        .btn {
            display: inline-block;
            font-weight: 600;
            text-decoration: none;
            background-color: #212529;
            color: #ffffff;
            padding: 0.625rem 1.25rem;
            border-radius: 0.375rem;
        }
        .btn:hover { background-color: #343a40; }
    </style>
</head>
<body>
    <div class="container">
        @yield('content')
    </div>
</body>
</html>