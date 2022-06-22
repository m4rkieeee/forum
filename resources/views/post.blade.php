@extends('layouts.app')

@section('content')

    <div class="row mt-2">
        <div class="col-xl-8 mx-auto border border-dark p-3">
            <h1 class="mb-0">{{ $post->title }}</h1>
            <small><i>By: {{ $post->user->name }}
                <br>On: {{ $post->created_at->format('D, M Y: H:i:s') }}</i></small>
            <p class="mt-3">{{ $post->text }}</p>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-xl-8 mx-auto border border-dark p-3">
            <h3>Comments</h3>
            <hr>
            @if($comments->isEmpty())
                <div id="postComments">
                    No comments have been posted yet!
                </div>
            @else
                <div id="postComments">
                    @foreach($comments as $comment)
                        <div class="col-xl-12 border border-dark p-3 mb-3">
                            <p class="float-end"><small>{{ $comment->created_at->diffForHumans() }}</small></p>
                            <p class="mb-1"><b>By</b> {{ $post->user->name }}</p>
                            <p class="mb-0">{{ $comment->reply }}</p>

                        </div>
                    @endforeach
                </div>
            @endif

            <hr>
            @auth()
                <textarea class="form-control" id="comment"></textarea>
                <button class="btn btn-primary float-end mt-2" id="addComment">Comment</button>
            @else()
                <p>Please login to comment!</p>
            @endauth
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#addComment').on('click', function () {
                $.ajax({
                    url: "{{ route('post.actions') }}",
                    type: 'POST',
                    data: {
                        type: 'addComment',
                        comment: $('#comment').val(),
                        postId: window.location.pathname.substring(window.location.pathname.lastIndexOf('/') + 1),
                    },
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function (data) {
                        if (data == 'success') {
                            new PNotify({
                                title: "Success!",
                                text: 'Comment Added',
                                type: 'success',
                                delay: 7000
                            });

                            $('#postComments').append(
                                '<div class="col-xl-12 border border-dark p-3 mb-3">\
                                    <p class="float-end"><small>0 seconds ago</small></p>\
                                    <p class="mb-1"><b>By</b> {{ Auth()->user()->name ?? 'guest'}}</p>\
                                    <p class="mb-0">' + $('#comment').val() + '</p>\
                                </div>');

                            $('#postComment').val('');
                        }
                    },
                    error: function (data) {
                        console.log(data);
                        new PNotify({
                            title: "Error!",
                            text: 'Comment failed',
                            type: 'error',
                            delay: 7000
                        });
                    }
                });
            });
        });
    </script>
@endsection
