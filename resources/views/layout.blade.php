<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Server - Check Hosting - Check IP</title>



    <link href="https://cdn.jsdelivr.net/npm/beercss@3.5.1/dist/cdn/beer.min.css" rel="stylesheet">
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.0.0/css/flag-icons.min.css"
    />
    <script type="module" src="https://cdn.jsdelivr.net/npm/beercss@3.5.1/dist/cdn/beer.min.js"></script>

    <script type="module" src="https://cdn.jsdelivr.net/npm/material-dynamic-colors@1.1.0/dist/cdn/material-dynamic-colors.min.js"></script>
    <style>

        @media screen and (min-width: 800px) {
            main.responsive{
                max-inline-size: 80%;
            }
        }
        @media screen and (min-width: 1000px) {
            main.responsive{
                max-inline-size: 60%;
            }
        }
        .break-word{
            word-break: break-word;
        }
    </style>
</head>
<body>
<main class="responsive">
    <nav>
        <a href="/" class="red-text text-accent-1"><img height="60px" src="/tn.png" alt="TinyInstaller IP tool">
            <h3 style="display: inline-block">Tra cứu IP</h3></a>
    </nav>
    @yield('content')
</main>
</body>
</html>
