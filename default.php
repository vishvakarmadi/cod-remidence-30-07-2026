<?php
$launchDate = "2025-12-07 00:00:00";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Coming Soon</title>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
    }

    body {
        height: 100vh;
        background: linear-gradient(135deg, #1a1a2e, #16213e, #0f3460);
        display: flex;
        justify-content: center;
        align-items: center;
        overflow: hidden;
        color: #fff;
    }

    .container {
        text-align: center;
        padding: 40px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 20px;
        backdrop-filter: blur(10px);
        box-shadow: 0 0 30px rgba(255, 255, 255, 0.1);
        width: 80%;
        max-width: 600px;
        animation: fadein 1.5s ease-in-out;
    }

    @keyframes fadein {
        from { opacity: 0; transform: translateY(40px); }
        to { opacity: 1; transform: translateY(0); }
    }

    h1 {
        font-size: 3rem;
        margin-bottom: 10px;
        font-weight: 600;
        letter-spacing: 2px;
        text-shadow: 0px 0px 10px #00eaff;
    }

    p {
        font-size: 1.2rem;
        opacity: 0.9;
    }

    .timer {
        margin-top: 30px;
        font-size: 2rem;
        font-weight: bold;
        letter-spacing: 2px;
        color: #00eaff;
        text-shadow: 0 0 10px #00eaff;
    }

    /* Floating Shapes */
    .shape {
        position: absolute;
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        animation: float 10s infinite ease-in-out;
    }

    @keyframes float {
        0% { transform: translateY(0) rotate(0); }
        50% { transform: translateY(-80px) rotate(180deg); }
        100% { transform: translateY(0) rotate(360deg); }
    }

    .shape:nth-child(1) {
        top: 10%;
        left: 5%;
        animation-duration: 8s;
    }

    .shape:nth-child(2) {
        bottom: 10%;
        right: 15%;
        animation-duration: 12s;
    }

    .shape:nth-child(3) {
        top: 50%;
        right: 5%;
        animation-duration: 9s;
    }

    .shape:nth-child(4) {
        bottom: 20%;
        left: 20%;
        animation-duration: 11s;
    }
</style>

<script>
    var launchDate = new Date("<?php echo $launchDate; ?>").getTime();

    var timer = setInterval(function () {
        var now = new Date().getTime();
        var distance = launchDate - now;

        if (distance < 0) {
            document.getElementById("timer").innerHTML = "WE ARE LIVE!";
            clearInterval(timer);
            return;
        }

        var d = Math.floor(distance / (1000 * 60 * 60 * 24));
        var h = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var m = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var s = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById("timer").innerHTML =
            d + "d : " + h + "h : " + m + "m : " + s + "s";
    }, 1000);
</script>

</head>

<body>

<div class="shape"></div>
<div class="shape"></div>
<div class="shape"></div>
<div class="shape"></div>

<div class="container">
    <h1>🚀 Coming Soon</h1>
    <p>We are working on something exciting! Stay tuned.</p>
    <div id="timer" class="timer">Loading...</div>
</div>

</body>
</html>
