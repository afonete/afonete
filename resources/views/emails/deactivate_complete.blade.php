<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Deactivation Complete</title>
</head>
<body>
    <h1>{{ $message }}</h1>
    <p>you are going to be redirected in 3 seconds</p>
    <!-- JavaScript code to redirect after 5 seconds -->
    <script>
        setTimeout(function() {
            window.location.href = "{{route('front')}}"; 
        }, 3000); // 3000 milliseconds = 5 seconds
    </script>
</body>
</html>
