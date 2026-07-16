<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<style>

body{

font-family:Arial,sans-serif;

background:#f4f4f4;

padding:30px;

}

.card{

background:white;

padding:30px;

border-radius:10px;

box-shadow:0 0 15px rgba(0,0,0,.15);

max-width:600px;

margin:auto;

}

h2{

color:#198754;

}

.otp{

font-size:42px;

font-weight:bold;

letter-spacing:10px;

color:#198754;

text-align:center;

margin:30px 0;

}

.footer{

font-size:13px;

color:#888;

margin-top:30px;

}

</style>

</head>

<body>

<div class="card">

<h2>

ANI-CARE

</h2>

<p>

Hello,

</p>

<p>

Someone requested to reset your password.

</p>

<p>

Use the verification code below.

</p>

<div class="otp">

{{ $otp }}

</div>

<p>

The code is valid for

<strong>

10 minutes

</strong>.

</p>

<p>

If you did not request this,

please ignore this email.

</p>

<div class="footer">

© {{ date('Y') }}

ANI-CARE

</div>

</div>

</body>

</html>