<div class="box">
    <div class="box-title">Provide Site Info And Create Admin Account</div>
    <form action="" method="post">
        @if($message)
        <div class="alert">{{$message}}</div>
        @endif
        <div class="box-content">

            <label>Site Title:</label>
            <input type="text" value="" placeholder="crea8social" name="val[title]"/>

            <label>Site Email:</label>
            <input type="text" value=""  name="val[email]"/>

            <label>Admin Fullname:</label>
            <input type="text" value=""  name="val[fullname]"/>



            <label>Admin Username:</label>
            <input type="text" value="" name="val[username]"/>

            <label>Admin Password:</label>
            <input type="text" value="" name="val[password]"/>


        </div>

        <div class="box-footer">
            <button class="btn">Finish</button>
        </div>

</div>