<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>


    <link rel="stylesheet" href="{{asset('css/app.css')}}">
</head>
<body class="bg-white">
    <div id="app" >
        <div >

            <home ></home>

            <div style="background-color:#f8f9fa;" class=" p-3">
                <div class=" container">
                    HOME / 
                </div>

            </div>

            <home_filter></home_filter>
        </div>
    </div>






    <script src="{{asset('js/app.js')}}"></script>
    <script>

        $(function () {
          $('[data-tooltip="tooltip"]').tooltip()
        });
        
        </script>
</body>
</html>