<div class="box">
    <div class="box-title">NewsLetter <a href="{{URL::route('admincp-newsletter-add')}}">Add New</a> </div>
    <div class="box-content">
        <div class="alert alert-info">Below contains the list of newsletter for you to delete or resend to the members</div>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th style="width: 40%">Subject</th>
                <th style="width: 50%">To</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($newsletters as $newsletter)
            <tr>
                <td>{{$newsletter->subject}}</td>
                <td>{{$newsletter->content}}</td>

                <td>

                    <a href="{{URL::route('admincp-newsletter-resend', ['id' => $newsletter->id])}}">Resend</a>
                    <a href="{{URL::route('admincp-newsletter-delete', ['id' => $newsletter->id])}}">Delete</a>

                </td>
            </tr>
            @endforeach
            </tbody>
        </table>

        {{$newsletters->links()}}
    </div>
</div>