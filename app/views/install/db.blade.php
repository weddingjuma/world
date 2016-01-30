<div class="box">
    <div class="box-title">Database Information</div>
    <form action="" method="post">
        @if($message)
            <div class="alert">{{$message}}</div>
        @endif
    <div class="box-content">

            <label>DB Host:</label>

            <input type="text" value="" placeholder="Localhost" name="val[host]"/>

            <label>DB Name:</label>
            <input type="text" value="" placeholder="crea8Social" name="val[name]"/>

            <label>DB Username:</label>
            <input type="text" value="" name="val[username]"/>

            <label>DB Password:</label>
            <input type="text" value="" name="val[password]"/>

            <label>DB Prefix:</label>
            <input type="text" value="crea8social_" name="val[prefix]" />

    </div>

    <div class="box-footer">
        <button class="btn">Continue</button>
    </div>

    </form>
</div>