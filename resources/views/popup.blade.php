<html>
<head>
    @vite('resources/js/popup.js')
</head>
<body>
    <div>
        <span class="ip" >8.8.8.8</span>
        <span class="ip" >1.1.1.1</span>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new IpPopup({
                selector: '.ip',
                endpoint: 'https://ip.fdev.top/v1/ip',
            })
        });

    </script>
</body>
</html>
