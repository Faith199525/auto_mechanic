<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>Your Vehicle Invoice</h2>

        <div>
          <table class="table table-striped">
                <tr>
                    <th colspan="4">Diagnosis</th>
                    <td>{{ $diagnosis }}</td>
                </tr>
                <tr>
                    <th colspan="4">Resolution</th>
                    <td>{{ $resolution }}</td>
                </tr>
                <tr>
                    <th colspan="4">Vehicle Maker</th>
                    <td>{{ $vehicle_Maker }}</td>
                </tr>
                <tr>
                    <th colspan="4">Vehicle Model</th>
                    <td>{{ $vehicle_Model }}</td>
                </tr>
                <tr>
                    <th colspan="4">Vehicle Licence Plate No</th>
                    <td>{{ $vehicle_Licence_Plate_No }}</td>
                </tr>
                <tr>
                    <th colspan="4">Admission Date</th>
                    <td>{{ $admission_date }}</td>
                </tr>
                <tr>
                    <th colspan="4">Release Date</th>
                    <td>{{ $release_date }}</td>
                </tr>
                <tr>
                    <th colspan="4">Service Time</th>
                    <td>{{ $service_time }}</td>
                </tr>
          </table>
          .<br/>

        </div>

    </body>
</html>