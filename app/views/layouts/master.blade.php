<!DOCTYPE html>
<html lang="en">
    <head>
        <title>@yield('title') - Hejmo</title>
        <meta name="rating" content="general">
        <meta name="robots" content="noindex,nofollow">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="/favicon.ico">
        <link rel="stylesheet" href="/assets/pure/pure-min.css" type="text/css" media="screen,projection">
        <link rel="stylesheet" href="/assets/pure/grids-responsive-min.css" type="text/css" media="screen,projection">
        <link rel="stylesheet" href="/css/hejmo.css" type="text/css" media="screen,projection" />
        <link rel="stylesheet" href="/assets/pikaday/css/pikaday.css" type="text/css" media="screen,projection" />
        <link rel="stylesheet" href="/assets/font-awesome/css/font-awesome.min.css" type="text/css" media="screen,projection" />
        <!--[if lt IE 9]>
            <script src="/assets/html5shiv/dist/html5shiv.js"></script>
        <![endif]-->
        <script type="text/javascript" src="/assets/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="/assets/jqueryui/jqueryui-built.js"></script>
        <script type="text/javascript" src="/assets/jquery-placeholder/jquery.placeholder.js"></script>
        <script type="text/javascript" src="/assets/pikaday/pikaday.js"></script>
        <script type="text/javascript" src="/assets/pikaday/plugins/pikaday.jquery.js"></script>
        <script type="text/javascript" src="/assets/jQueryDndPageScroll/jquery.dnd_page_scroll.js"></script>
        <script type="text/javascript" src="/js/hejmo.js"></script>
    </head>
    <body>
        @if ($errors->has())
            <div class="pure-g">
                <div class="pure-u-1-1">
                    @foreach ($errors->all() as $error)
                        <div class="error">
                            <strong>Error!</strong> {{ $error }}
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
        <div class="tasks">
            @yield('content')
        </div>
        <div class="pure-g">
            <div class="pure-u-1-1 footer">
                Hejmo Task Management<br>Copyright Tom Pitcher 2014
            </div>
        </div>
    </body>
</html>
