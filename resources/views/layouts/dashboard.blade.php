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
<body>
    <div id="app">
        <dashboard_nav :current_user="{{json_encode(array('name' => Auth::user()->name,'img' => Auth::user()->Img))}}"></dashboard_nav>
        <div class="container-fluid">
            <div class="row">
        <dashboard_sidebar></dashboard_sidebar>
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4" style=" padding-top: 20px;">
            
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
             
                <div class="container-fluid">
                        @yield("content")
                </div>
             
            </div>
           
            </div>
        </div>
    </div>





    <script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-core.js"></script>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-service.js"></script>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-ui.js"></script>
    <script type="text/javascript" src="https://js.api.here.com/v3/3.1/mapsjs-mapevents.js"></script>
    <script src="{{asset('js/app.js')}}"></script>
    <script>

        $(function () {
          $('[data-tooltip="tooltip"]').tooltip()
        });
        
        </script>
</body>
</html>