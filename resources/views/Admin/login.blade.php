<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 400px;
            margin: 100px auto;
            padding: 30px;
            background: white;
            box-shadow: 0px 0px 10px #ccc;
            border-radius: 8px;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-top: 5px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        button {
            width: 100%;
            margin-top: 20px;
            padding: 12px;
            border: none;
            border-radius: 6px;
            background: #007bff;
            color: white;
            font-size: 16px;
        }

        .error {
            color: red;
            margin-top: 10px;
            text-align: center;
        }

        .success {
            color: green;
            margin-top: 10px;
            text-align: center;
        }

    </style>
</head>
<body>

<div class="container">
    <h2>Admin Login</h2>

    @if(session('error'))
        <div class="error">{{ session('error') }}</div>
    @endif

    <form action="{{ route('admin.login.submit') }}" method="POST">
        @csrf

        <label>Email</label>
        <input type="email" name="email" placeholder="Enter email" required>

        <label>Password</label>
        <input type="password" name="password" placeholder="Enter password" required>

        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>
