@extends('layouts.app')

@section('content')

    @if(Auth()->check())
        <div class="row mt-2">
            <div class="col-xl-8 mx-auto">
                <button type="button" class="btn btn-primary float-end" style="margin-right:-10px" data-bs-toggle="modal" data-bs-target="#addPostModal">Add Post</button>
            </div>
        </div>
    @endif
    <div id="postRows">
        @foreach ($posts as $post)

                <div class="row mt-2">
                    <div class="col-xl-8 mx-auto border border-dark p-3">
                        <a href="{{ route('post.view', ['id' => $post->id]) }}">{{ $post->title }}</a>
                    </div>
                </div>
        @endforeach
    </div>

    <!-- add post modal -->
    @if(Auth()->check())
        <div class="modal fade" id="addPostModal" tabindex="-1" aria-labelledby="addPostModal" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Post</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="type" value="addPost">
                        <div class="form-group">
                            <label for="postTitle">Post Title</label>
                            <input type="text" class="form-control" id="postTitle" placeholder="">
                        </div>
                        <div class="form-group">
                            <label for="postText">Post Text</label>
                            <textarea type="text" class="form-control" id="postText" placeholder=""></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="addPost" data-bs-dismiss="modal">Add Post</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        $(document).ready(function() {
            $('#addPost').on('click', function () {
                $.ajax({
                    url: "{{ route('post.actions') }}",
                    type: 'POST',
                    data: {
                        type: 'addPost',
                        postTitle: $('#postTitle').val(),
                        postText: $('#postText').val(),
                    },
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function (data) {
                        console.log(data);
                        if (data) {
                            new PNotify({
                                title: "Success!",
                                text: 'Post Added',
                                type: 'success',
                                delay: 7000
                            });

                            $('#postRows').append(
                                ' <div class="row mt-2">\
                                    <div class="col-xl-8 mx-auto border border-dark p-3">\
                                        <a href="/post/' + data + '">' + $('#postTitle').val() + '</a>\
                                    </div>\
                                </div');

                            $('#postTitle').val('');
                            $('#postText').val('');
                        }
                    },
                    error: function (data) {
                        console.log(data);
                        new PNotify({
                            title: "Error!",
                            text: 'Post failed',
                            type: 'error',
                            delay: 7000
                        });
                    }
                });
            });
        });
    </script>
@endsection
