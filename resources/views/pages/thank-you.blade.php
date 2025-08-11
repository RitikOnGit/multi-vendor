<!-- resources/views/thank-you.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You for Your Order</title>
    <script>
        // Redirect after 5 seconds
        setTimeout(function() {
            window.location.href = '{{ route("myOrders") }}';
        }, 5500);
    </script>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f8f9fa;
        }
        .thank-you-container {
            text-align: center;
            padding: 30px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .thank-you-container h1 {
            color: #4CAF50;
        }
        .thank-you-container p {
            font-size: 18px;
        }
        .countdown {
            font-size: 20px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="thank-you-container">
        <h1>Thank you for your order!</h1>
        <p>Your order has been successfully placed. We are processing it and will notify you soon.</p>
        <div class="countdown">
            <p>You will be redirected to the home page in <span id="timer">5</span> seconds...</p>
        </div>
    </div>

    <script>
        let countdown = 5;
        const timerElement = document.getElementById('timer');

        // Update the timer countdown every second
        const timerInterval = setInterval(function() {
            countdown--;
            timerElement.innerText = countdown;

            // Once countdown reaches 0, stop the interval
            if (countdown === 0) {
                clearInterval(timerInterval);
            }
        }, 1000);
    </script>
</body>
</html>
