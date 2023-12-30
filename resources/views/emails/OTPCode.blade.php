<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .container {
            max-width: 100%;
            width: 500px;
            background: #efefef;
            border-radius: 10px;
            box-shadow: 2px 2px 5px 0px rgba(0, 0, 0, 0.5);
            -webkit-box-shadow: 2px 2px 5px 0px rgba(0, 0, 0, 0.5);
            -moz-box-shadow: 2px 2px 5px 0px rgba(0, 0, 0, 0.5);
            margin: auto;
            min-height: 400px;
            padding: 10px;
            box-sizing: border-box;
            direction: rtl;
            font-family: "iransans", "b homa", "b nazanin"
        }

        .container a {
            text-decoration: none
        }

        .logo {
            height: 100px;
            display: block;
            margin: auto
        }

        .otp-box {
            width: 100%;
            border: 1px solid rgb(171 171 246);
            background: lightblue;
            font-size: 18px;
            text-align: center;
            box-sizing: border-box;
            padding: 5px;
            line-height: 30px;
            margin-bottom: 50px;
            margin-top: 50px;
        }

        .code {
            font-weight: bold;
            font-size: 20px;
            display: block
        }
    </style>
</head>

<body>
    <div class="container">
        <img class="logo" src="{{ asset('images/logo.png') }}" alt="">


        <div class="otp-box">

            کد ورود شما :
            <span class="code">{{ $code }}</span>
        </div>

        <div class="description">

            از ایمیل شما درخواستی برای ورود به حساب کاربری ارسال شده است. در صورتی که درخواست از جانب شما نبوده است این
            ایمیل را نادیده بگیرید.

            <br>
            با احترام.
            <br>
            <a href="{{ env('SPA_URL') }}">فروشگاه {{ env('APP_NAME') }}</a>
        </div>


    </div>

</body>

</html>
