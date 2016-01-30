<html>
<head>
    <title>{{Config::get('site_title', '')}} - Ooops!!</title>

    <style>
        body{
            background: #F62459;
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
        a {
            color: #ffffff;
        }
    </style>
</head>

<body>
<div class="container">
    <i></i>
    <h2>Ooop!!, We're sorry....</h2>
    <p>The page you are looking for cannot be found</p>
    <p><a href="{{URL::to('/')}}">Return to Homepage</a> </p>
</div>
</body>
</html>