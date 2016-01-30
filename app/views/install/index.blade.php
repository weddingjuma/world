<div class="box">
    <div class="box-title">Welcome to crea8Social Installation</div>
    <form action="{{URL::to('check/purchase-code')}}" method="post">
        <div class="box-content">
            @if($error)
            <div class="alert">Invalid Purchase code</div>
            @endif
            <h3>To Continue please provide your purchase code to continue installation</h3>
            <p><strong>Note:</strong>You can install crea8social on this domain many times but if you want to install
            on another domain please contact me via <strong><a href="http://support.crea8social.com">Our Support Portal</a> </strong> with the following credentials
                <li>Your Envato Username</li>
                <li>Your Purchase code</li>
            </p>

            <input type="text" name="code" placeholder="Provide your purchase code"/>
        </div>

        <div class="box-footer">
            <button class="btn" style="cursor: pointer">Continue Installation</button>
        </div>
    </form>
</div>