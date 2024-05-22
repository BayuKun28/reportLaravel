<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memuat Laporan</title>
    <style>
        body {
            font-family: sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f5f5f5;
        }

        .loading-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .loading-message {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .loading-counter {
            font-size: 30px;
            font-weight: bold;
            color: #333;
        }

        .loading-counter span {
            animation: counter 1s steps(1) infinite;
        }

        @keyframes counter {
            0% {
                content: 0;
            }

            100% {
                content: 5;
            }
        }
    </style>
</head>

<body>
    <div class="loading-container">
        <h2 class="loading-message">SEDANG MEMUAT HARAP TUNGGU...</h2>
        <p class="loading-counter">Memuat <span>0</span> detik</p>
    </div>

    <script>
        let counter = 0;
        const loadingCounter = document.querySelector('.loading-counter span');

        setInterval(() => {
            counter++;
            loadingCounter.textContent = counter;
        }, 1000);
    </script>
</body>

</html>
