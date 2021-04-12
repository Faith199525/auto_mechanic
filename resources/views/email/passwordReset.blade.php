<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>Verify Your Email Address</h2>

        <div>
            Please click on the link below to reset your password
            {{ URL::to('/password/reset/' . $confirmationToken) }}.<br/>

        </div>

    </body>
</html>