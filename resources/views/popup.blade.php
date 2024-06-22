<html>
<head>
    @vite('resources/js/popup.js')
</head>
<body>
    <div>
        <ul>
            <li class="ip">8.8.8.8</li>
        </ul>
    </div>
    <script>
        new IpPopup({
            selector: '.ip',
            endpoint: 'https://ip.fdev.top/v1/ip',
        })
    </script>
</body>
</html>
