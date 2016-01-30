<div class="box">
    <div class="box-title">Edit User</div>
    <div class="box-content">

        <form class="form-horizontal" action="" method="post">

                            <div class="form-group">
                                <label class="col-sm-4">Fullname</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" value="{{$user->fullname}}" name="val[fullname]"/>
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-sm-4">Username</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" value="{{$user->username}}" name="val[username]"/>
                                </div>
                            </div>

                        <div class="form-group">
                            <label class="col-sm-4">Change Password</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" value="" name="val[password]"/>
                            </div>
                        </div>


                            <div class="form-group">
                                <label class="col-sm-4">Email Address</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control" value="{{$user->email_address}}" name="val[email]"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4">Genre</label>
                                <div class="col-sm-7">
                                    <select class="form-control" name="val[genre]">
                                        <option {{($user->genre == 'male') ? 'selected' : null}} value="male">Male</option>
                                        <option {{($user->genre == 'female') ? 'selected' : null}} value="female">Female</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4">Verified Member</label>
                                <div class="col-sm-7">
                                    <select class="form-control" name="val[verified]">
                                        <option {{($user->verified == 0) ? 'selected' : null}} value="0">No</option>
                                        <option {{($user->verified == 1) ? 'selected' : null}} value="1">Yes</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4">Activated</label>
                                <div class="col-sm-7">
                                    <select class="form-control" name="val[activated]">
                                        <option {{($user->activated == 0) ? 'selected' : null}} value="0">No</option>
                                        <option {{($user->activated == 1) ? 'selected' : null}} value="1">Yes</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4">Make Admin</label>
                                <div class="col-sm-7">
                                    <select class="form-control" name="val[admin]">
                                        <option {{($user->admin == 0) ? 'selected' : null}} value="0">No</option>
                                        <option {{($user->admin == 1) ? 'selected' : null}} value="1">Yes</option>
                                    </select>
                                </div>
                            </div>

            <div class="body-header">
                <input class="btn btn-danger no-radius" type="submit" value="Save"/>
            </div>

        </form>

    </div>
</div>