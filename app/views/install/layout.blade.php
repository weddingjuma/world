<!DOCTYPE html>
    <html lang="en">
         <head>
             <title>crea8Social - Installation</title>

             <style>
                 body{
                     background: #E7E7E7;
                 }

                 .box{
                     width: 50%;
                     margin: 50px auto;
                     background: white;
                     -moz-box-shadow: 0 0 1px 0px #8EC6EC;
                     -webkit-box-shadow: 0 0 1px 0px #8EC6EC;
                     box-shadow: 0 0 1px 0px lightgrey;
                     min-height: 100px;
                 }

                 .box .box-title{
                     padding: 10px;
                     margin-bottom: 10px;
                     font-weight: bold;
                     border-bottom: solid 1px #CACACA;
                     color: #5F676C;
                 }
                 .box .box-content{
                     padding: 5px 10px;
                     padding-bottom: 20px !important;
                 }
                 .box .divider{
                     border-top: solid 1px #E2E2E2;
                     margin: 15px 0;
                 }

                 .box .box-footer{
                     width: 100%;
                     background: #D9D9D9;
                     display: block;
                 }
                 .box .box-footer .btn{
                     background: white;
                     border:solid 2px #F89406;
                     color: #000000;
                     padding: 5px 20px;
                     margin: 10px ;
                     display: inline-block;
                     text-decoration: none !important;
                 }
                 .box label{
                     color: #808080;
                     width: 100%;
                     display: block;
                     margin-bottom: 3px;
                 }
                 .alert{
                     padding: 10px;
                     color: white;
                     background: #ff0000;
                     margin: 10px 0;
                 }
                 .box input[type=text]{
                     width: 90%;
                     border: solid 1px lightgray;
                     padding: 10px;
                     margin-bottom: 15px;
                 }
             </style>
         </head>
        <body>
            {{$content}}
        </body>
    </html>
