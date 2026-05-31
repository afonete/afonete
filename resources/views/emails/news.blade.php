
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Subscribtion</title>
</head>
<body>
@if($status=='activate')
<h1>News and update subscription</h1>
<p>
	thank u for your activating Subscribtion, you will receive our news<br> and update of Fonepo.
</p>
<br>
<p><a style="color: red;" href="https://focoin.eu/deactivate?email={{$email}}">cancel subscription</a></p>
<!-- <p><a href="http://marathon.fonepo.com/f/public/deactivate?email=">cancel subscription</a></p> -->
@endif
@if($status=='deactivate')
<h1>News and update subscription</h1>
<p>
	You have deactivate your Subscribtion for receiving updates and news, <br>
	of Fonepo. will miss to see you
</p>
<br>
<p><a style="color: green;" href="https://focoin.eu/activate?email={{$email}}">Re_activate subscription</a></p>
<!-- <p><a href="http://marathon.fonepo.com/f/public/deactivate?email=">cancel subscription</a></p> -->
@endif
</body>
</html>