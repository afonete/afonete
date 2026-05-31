<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account reminder</title>
    <style>
        /* Add your custom CSS styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 10px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
/*            background-color: #007BFF;*/
            color: #000;
         
        }
        .content {
            padding: 5px;
        }
        .footer {
            text-align: center;
            background-color: #000050;
          
            padding: 10px 0;
        }
        .best {
            
            color: #777;
            font-size: 14px;
       
        }
    </style>
</head>
<body>
    <div class="container">
      <!--   <div class="header">
            <h1>Account Reminder</h1>
        </div> -->
        <div class="content">
            <p>Hello Leader {{$username}},</p>
            <p>Just quick reminder you as team leader  to complete your account to qualified bonus today please because soon as possible your account change standard  or freezing.</p>
            <p>If you require any assistance, please don't hesitate to contact us at <a href="mailto:admin@fonepo.com">admin@fonepo.com</a>.</p>
           <div class="best"> <p>Best regards,</p>
            <p>The Fonepo Team</p><div>
        </div>
        <div class="footer">
            <p>&copy; <?php echo date("Y"); ?> Fonepo. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
