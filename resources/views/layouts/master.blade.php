<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="css/app.css">        
        <title>Forum</title>

    </head>
    <body>

        <div id="app">
            @include ('layouts.nav')
            
            @include ('layouts.hero')
            {{-- <form action="/threads/php" method="post">
                {{ csrf_field() }}
                <input type="text" name="title">
                <input type="text" name="description">
                <textarea name="body" id="" cols="30" rows="10"></textarea>
                <button type="submit">Submit</button>
            </form>

            <form action="/threads/php/1/replies" method="post">
                {{ csrf_field() }}
                <textarea name="body" id="" cols="30" rows="10"></textarea>
                <button type="submit">Submit</button>
            </form> --}}

            <router-view></router-view>
        </div>

        <script src="js/app.js"></script>
    </body>
</html>
