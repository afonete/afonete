<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Fonepo!</title>
    <style>
        /* Add your custom CSS styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0px 3px 5px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
        }
        .message {
            margin-top: 20px;
            padding: 15px;
            background-color: #f0f7f2;
            border-radius: 5px;
        }
        .details {
            margin-top: 20px;
            padding: 15px;
            background-color: #f9f0fa;
            border-radius: 5px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
/*            color: #fff;*/
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Hello!</h1>
        <p>Congratulations! Your registration with <strong>FONEPO</strong> was successful.</p>
        <div class="message">
            <p>Your registration details in our system is:</p>
            <p><strong>Email:</strong> {{$email}}</p>
            <p><strong>Password:</strong> {{$password}}</p>
            <p><strong>username:</strong> {{$username}}</p>
        </div>
        <p>You can access your account from <a class="button" style="color:black" href="https://fonepo.com/login">https://fonepo.com/login</a></p>
        <p>Remember to change password after login.</p>
        <div class="details">
            <p>If you have any questions, feel free to contact our support team via <a href="mailto:admin@fonepo.com"> admin@fonepo.com</a><br> They are always happy to help! </p>
            <p>Good luck!</p>
        </div>
    </div>
</body>
</html>
