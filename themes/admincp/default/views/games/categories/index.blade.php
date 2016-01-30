<div class="box">
    <div class="box-title">Game Categories <a href="{{URL::route('admincp-games-create-category')}}">Add category</a> </div>
    <div class="box-content">
        <div class="alert alert-info">Below contains the list of game categories your member can create different games</div>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th style="width: 40%">Title</th>
                <th style="width: 50%">Description</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($categories as $category)
            <tr>
                <td>{{$category->title}}</td>
                <td>{{$category->description}}</td>
                <td>
                    <a href="{{URL::route('admincp-games-edit-category', ['id' => $category->id])}}">Edit</a> |
                    <a href="{{URL::route('admincp-games-delete-category', ['id' => $category->id])}}">Delete</a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>

        {{$categories->links()}}
    </div>
</div>