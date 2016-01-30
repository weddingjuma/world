<div class="box">
    <div class="box-title">Page Categories <a href="{{URL::route('admincp-pages-create-category')}}">Add New Category</a> </div>


    <div class="box-content">
        <div class="alert alert-info">Below contains the list of page categories your member can create different pages</div>
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
                    <a href="{{URL::route('admincp-pages-edit-category', ['id' => $category->id])}}">Edit</a> |
                    <a href="{{URL::route('admincp-pages-delete-category', ['id' => $category->id])}}">Delete</a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>

        {{$categories->links()}}
    </div>
</div>