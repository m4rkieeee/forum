<html>
<head>
    <title>@yield('title', 'ACP')</title>
    <link rel="stylesheet" href={{ asset('css/style.css') }}>
    <script type="text/javascript" src={{ asset("css/jquery.min.js") }}></script>
    <script src="https://kit.fontawesome.com/7f7a28d73a.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
@section('content')
<body>
            @foreach($posts as $post)
                <div id="title" style="border: 1px solid black">
                  <b>  {{ $post->title }} </b> <br>
                    {{ $post->text }}
                </div>
                @endforeach
</body>
