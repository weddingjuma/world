<div class="box">
    <div class="box-title">Page Categories <a href="{{URL::route('admincp-pages-create-category')}}">Add New Category</a> </div>


    <div class="box-content">
        <div class="alert alert-info">Below contains the list of page categories your member can create different pages</div>

        <form action="" method="get">
            <input name="term" type="text" class="form-control" placeholder="Search page by its name or slug"/>
            <br/>
            <button class="btn btn-primary btm-sm">Search</button><br/><br/>
        </form>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th style="width: 40%">Title</th>
                <th style="width: 30%">Description</th>
                <th style="">By</th>
                <th>Likes</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($pages as $page)
            <tr>
                <td>{{$page->title}}</td>
                <td>{{$page->description}}</td>
                <td><a href="{{$page->user->present()->url()}}">{{$page->user->fullname}}</a> </td>
                <td>{{$page->countLikes()}}</td>
                <td>
                    <a href="{{URL::route('admincp-pages-edit', ['id' => $page->id])}}">Edit</a> |
                    <a href="{{URL::route('delete-page', ['id' => $page->id])}}">Delete</a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>

        {{$pages->links()}}
    </div>
</div>