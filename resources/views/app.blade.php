<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title>asdgasdg</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1">

      {!! $head !!}
</head>
<body>
    <div id="modal"></div>
    <div id="app" class="App">
        <div id="app-navigation" class="App-navigation"></div>
        <div id="drawer" class="App-drawer">

            <header id="header" class="App-header">
                <div id="header-navigation" class="Header-navigation"></div>
                <div class="container">
                    <h1 class="Header-title">
                        <a href="{{ $forum['set_site']['site_url'] }}" id="home-link">
                            @if ($forum['set_site']['site_logo'])
                                <img src="{{ $forum['set_site']['site_logo'] }}" alt="{{ $forum['set_site']['site_name'] }}" class="Header-logo">
                            @else
                                {{ $forum['set_site']['site_name'] }}
                            @endif
                        </a>
                    </h1>
                    <div id="header-primary" class="Header-primary"></div>
                    <div id="header-secondary" class="Header-secondary"></div>
                </div>
            </header>

        </div>
        <main class="App-content">
            <div id="content"></div>
        </main>
    </div>
    <script type="text/javascript">
        var discuz = {};
    </script>
    <script type="text/javascript" src="/assets/js/app.js"></script>
    <script type="text/javascript">
        discuz.core.app.load(@json($payload));
        discuz.core.app.boot();
    </script>
</body>
</html>
