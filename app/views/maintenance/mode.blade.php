<html>
    <head>
        <title>{{Config::get('site_title', '')}} - Maintenance mode</title>

        <style>
            body{
                background: #F1A9A0;
                font-size: 15px;
                color: white;
                font-family: 'Segoe UI', Tahoma, Segoe UI, Arial, sans-serif;
            }
            .container{
                width: 40%;
                text-align: center;
                margin: 120px auto;
            }
            .container h2{
                font-family: 'Segoe UI', Tahoma, Segoe UI, Arial, sans-serif;
                font-weight: normal;
                font-size: 30px;
            }
            .container i{
                display: block;
                width:100px;
                height: 100px;
                border-radius:100px;
                background: white;
                margin: auto;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <i></i>
            <h2>Sorry, we are down for maintenance</h2>
            <p>{{Config::get('maintenance-mode-text', '')}}</p>
        </div>
    </body>
</html>