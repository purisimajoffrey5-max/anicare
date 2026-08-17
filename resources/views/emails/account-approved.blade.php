<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            padding: 30px;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,.15);
            max-width: 600px;
            margin: auto;
        }

        h2 {
            color: #198754;
        }

        .button {
            display: inline-block;
            background: #198754;
            color: white !important;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 8px;
            margin-top: 20px;
            font-weight: bold;
        }

        .footer {
            font-size: 13px;
            color: #888;
            margin-top: 30px;
        }
    </style>
</head>

<body>

    <div class="card">

        <h2>ANI-CARE</h2>

        <p>
            Hello {{ $userName }},
        </p>

        <p>
            Your account has been approved by the admin.
        </p>

        <p>
            You can now log in and access your account.
        </p>

        <a
            href="https://embellish-mannish-vigorous.ngrok-free.dev/login"
            class="button"
            target="_blank"
        >
            Login Now
        </a>

        <div class="footer">
            © {{ date('Y') }} ANI-CARE
        </div>

    </div>

</body>
</html>