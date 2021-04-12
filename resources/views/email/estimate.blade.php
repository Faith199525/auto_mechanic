<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>Vehicle Estimate</h2>
        <b>Vehicle maker: {{ $vehicle_Maker }}</b><br />
        <b>Vehicle model: {{ $vehicle_Model }}</b><br />
        <b>Vehicle no: {{ $vehicle_Licence_Plate_No }}</b>
        <div>
            Please click on the link below to view your estimates<br>
            {{ URL::to('/view-estimate/' . $token) }}.<br/>

        </div>

    </body>
</html>