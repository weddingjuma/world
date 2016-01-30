<div class="box-title">API Documentation</div>

<div class="box-content">
    <table class="table table-striped table-bordered" style="font-size: 15px">
        <thead>
            <tr>
                <th style="width: 20%">Name</th>
                <th style="width: 20%">Values</th>
                <th style="width: 60%">Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>type</td>
                <td>
                    <strong>user</strong> or <strong>page</strong>
                </td>
                <td>
                    This parameter tell us what type of query you are requesting for, users, pages e.t.c
                </td>
            </tr>

            <tr>
                <td>ID</td>
                <td>
                    <strong>Type ID e.g 1,2 e.t.c</strong>
                </td>
                <td>
                    This parameter tell us which item of the query type you are requesting for
                </td>
            </tr>

            <tr>
                <td>get</td>
                <td>
                    <strong>profile</strong> or <strong>posts</strong>
                </td>
                <td>
                    This parameter tell us which information you want from the item. see below

                    <strong>Profile</strong> : the following field are return for user type
                    <ol>
                        <li><strong>fullname</strong>: User fullname</li>
                        <li><strong>username</strong>: User username</li>
                        <li><strong>genre</strong>: User genre [Female|Male]</li>
                        <li><strong>bio</strong>: User bio info</li>
                        <li><strong>Country</strong>: User country</li>
                        <li><strong>avatar</strong>: User profile photo</li>
                        <li><strong>verified</strong>: If user is verified or not</li>
                        <li><strong>cover</strong>: User profile cover photo</li>
                    </ol>

                    Profile result for page
                    <ol>
                        <li><strong>title</strong>: Page title</li>
                        <li><strong>slug</strong>: Page slug</li>
                        <li><strong>category</strong>:Page category</li>
                        <li><strong>description</strong>: Page Description</li>
                        <li><strong>website</strong>: Page website</li>
                        <li><strong>logo</strong>: Page logo</li>
                        <li><strong>verified</strong>: If page is verified or not</li>
                        <li><strong>cover</strong>: Page profile cover photo</li>
                    </ol>

                    <strong>Posts</strong>: The following field are return for both type
                    <ol>
                        <li><strong>text</strong>: Post text</li>
                        <li><strong>user_id</strong>: Post user_id</li>
                        e.t.c
                    </ol>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="divider"></div>
    <strong>EXAMPLE OF REQUEST:</strong>

    <p>
        For profile information from a user<br/>
        <strong>{{URL::to('')}}/api/?type=user&id=[userid]&get=profile</strong>
    </p>

    <p>
        For posts from a user<br/>
        <strong>{{URL::to('')}}/api/?type=user&id=[userid]&get=posts</strong>
    </p>

    <div class="divider"></div>
    <p>
        For profile information from a page<br/>
        <strong>{{URL::to('')}}/api/?type=page&id=[userid]&get=profile</strong>
    </p>

    <p>
        For posts from a page<br/>
        <strong>{{URL::to('')}}/api/?type=page&id=[userid]&get=posts</strong>
    </p>
</div>