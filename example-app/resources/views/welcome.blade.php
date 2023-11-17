<!DOCTYPE html> <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}" />
<title>
    Laravel
</title>
<style>
    #apiTest{
        width: 100px;
        height:100px;
    }
    #status{
        color:red;
        font-weight:bold;
    }
</style>
</head>

<body class="antialiased">
    <h1 id="status">
        未支付
    </h1>
    <button id="apiTest">
        apiTest
    </button>
    <script>
        document.getElementById('apiTest').addEventListener('click', function () {
            fetch('/AsyncApiTest', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                },
            })
                .then(data => {
                    const statusElement = document.getElementById("status");
                    statusElement.innerHTML = "處理中";
                    statusElement.style.color="green";
                })
        });
    </script>
</body>

</html>