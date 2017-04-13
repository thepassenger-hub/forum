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
            <ul class="breadcrumb" v-if="path">
                <router-link v-for="route in path.breadcrumbs" tag="li" :to="route.path" :key="route"><a>@{{route.name}}</a></router-link>
            </ul>
            <router-view></router-view>
        </div>

        <script src="js/app.js"></script>
    </body>
</html>
