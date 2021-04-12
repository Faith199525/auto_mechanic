<!DOCTYPE html>
<html>
<head>
    <title>Invitation to Complete User Registration</title>
</head>
<body>
    <h2>{{ $invite['email'] }}</h2>
    <p><a href={{$invite['url']}}>Click Here</a> to complete registration or copy this link to your browser: </p>
    <p>{{$invite['url']}}</p>
    <p>Thank you!</p>
</body>
</html> 