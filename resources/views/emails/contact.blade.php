<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Tailwind styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #007bff;
            padding: 10px;
            text-align: left;
            color: white;
            border-radius: 8px 8px 0 0;
        }

        .footer {
            background-color: #f4f4f4;
            padding: 10px;
            text-align: center;
            font-size: 12px;
            color: #777;
            border-radius: 0 0 8px 8px;
        }

        .content {
            padding: 20px;
            text-align: left;
        }

        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border-radius: 4px;
            text-decoration: none;
            margin-top: 20px;
        }

        .button:hover {
            background-color: #0056b3;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        p {
            font-size: 16px;
            margin-bottom: 20px;
        }

        strong {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1 style="margin-left: 20px;">Hi {{ config('app.name') }}</h1>
        </div>

        <div class="content">
            <p>You have received a new message from:</p>
            <p><strong>{{ $request->name }}</strong> ({{ $request->email }})</p>

            <hr style="border-color: #ddd; margin: 20px 0;">

            <p><strong>Message:</strong></p>
            <p>{{ $request->message }}</p>

        </div>

        <div class="footer">
            <p>Best regards,<br>
                {{ $request->name }}
            </p>
        </div>
    </div>
</body>

</html>