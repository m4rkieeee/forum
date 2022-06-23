@extends('layouts.app')

@section('content')

    <div class="row mt-2">
        <div class="col-xl-8 mx-auto border border-dark p-3 rounded">
            <h1 class="mb-0"><i class="fa-regular fa-file fa-lg"></i> {{ $post->title }} @if(Auth()->id() == $post->user_id) <button class="btn p-0 float-end" id="{{ $post->id }}" name="deletePost"><i class="fa-solid fa-trash-can" style="color:red;"></i></button> @endif</h1>
            <small><i>By: {{ $post->user->name }}
                <br>On: {{ $post->created_at->format('D, M Y: H:i:s') }}</i></small>
            <p class="mt-3">{{ $post->text }}</p>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-xl-8 mx-auto border border-dark p-3 rounded">
            <h3><i class="fa-regular fa-comment fa-lg"></i> Comments</h3>
            <hr>
            @if($comments->isEmpty())
                <div id="postComments">
                    <span id="noComments">No comments have been posted yet!</span>
                </div>
            @else
                <div id="postComments">
                    @foreach($comments as $comment)
                        <div class="col-xl-12 border border-dark p-3 mb-3 rounded" id="{{ $comment->id }}">
                            <p class="float-end"><small>{{ $comment->created_at->diffForHumans() }}</small></p>
                            <p class="mb-1"><b>By</b> {{ $post->user->name }} @if(Auth()->id() == $comment->user_id) <button class="btn p-0" id="{{ $comment->id }}" name="deleteComment"><i class="fa-solid fa-trash-can" style="color:red;"></i></button> @endif</p>
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
                        if (data) {
                            new PNotify({
                                title: "Success!",
                                text: 'Comment Added',
                                type: 'success',
                                delay: 7000
                            });

                            if($('#noComments').length){
                                $('#noComments').remove();
                            }

                            $('#postComments').append(
                                '<div class="col-xl-12 border border-dark p-3 mb-3" id="' + data + '">\
                                    <p class="float-end"><small>0 seconds ago</small></p>\
                                    <p class="mb-1"><b>By</b> {{ Auth()->user()->name ?? 'guest'}} <button class="btn p-0" id="' + data + '" name="deleteComment"><i class="fa-solid fa-trash-can" style="color:red;"></i></button></p>\
                                    <p class="mb-0">' + $('#comment').val() + '</p>\
                                </div>');

                            $('#comment').val('');
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

            $(document).on("click", "button[name=deleteComment]", function (e) {
                var id = $(this).attr('id');
                console.log(id);
                $.ajax({
                    url: "{{ route('post.actions') }}",
                    type: 'POST',
                    data: {
                        type: 'deleteComment',
                        commentId: id,
                    },
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function (data) {
                        if (data == 'success') {
                            new PNotify({
                                title: "Success!",
                                text: 'Comment Deleted',
                                type: 'success',
                                delay: 7000
                            });

                            $('div#' + id).remove();
                        }
                    },
                    error: function (data) {
                        console.log(data);
                        new PNotify({
                            title: "Error!",
                            text: 'Comment deletion failed',
                            type: 'error',
                            delay: 7000
                        });
                    }
                });
            });

            $(document).on("click", "button[name=deletePost]", function (e) {
                var id = $(this).attr('id');
                console.log(id);
                $.ajax({
                    url: "{{ route('post.actions') }}",
                    type: 'POST',
                    data: {
                        type: 'deletePost',
                        postId: id,
                    },
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function (data) {
                        if (data == 'success') {
                            new PNotify({
                                title: "Success!",
                                text: 'Post Deleted',
                                type: 'success',
                                delay: 7000
                            });

                            setTimeout(function(){
                                window.location.href = "{{ route('home')}}";
                            }, 3000);
                        }
                    },
                    error: function (data) {
                        console.log(data);
                        new PNotify({
                            title: "Error!",
                            text: 'Comment deletion failed',
                            type: 'error',
                            delay: 7000
                        });
                    }
                });
            });
        });
    </script>
@endsection
