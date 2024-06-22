<html>
<head>
    <title>Ip Popup</title>
    <meta name="description" content="Ip Popup">
    <meta name="keywords" content="Ip Popup">
    <link href="/popup/css" rel="stylesheet">
    <script src="/popup/js"></script>
</head>
<body>
    <h1>Ip Popup</h1>

    <div>

        <span class="ip" >8.8.8.8</span>
        <span class="ip" >1.1.1.1</span>
    </div>
    <h1>Source code</h1>

@php ob_start(); @endphp
<link href="{{asset('/popup/css')}}" rel="stylesheet">
<script src="{{asset('/popup/js')}}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new IpPopup({
            selector: '.ip',
            api: '{{route('api.ip')}}',
        })
    });
</script>
@php
    $content=ob_get_clean();
@endphp
        <textarea style="width: 100%" rows="20">{!! htmlspecialchars($content) !!}</textarea>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new IpPopup({
                selector: '.ip',
                api: 'https://ip.fdev.top/v1/ip',
            })
        });

    </script>
</body>
</html>
