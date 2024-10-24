<!DOCTYPE html>
<html>
<head>
    <title>Barcode View</title>
    <meta http-equiv="refresh" content="30">
</head>
<body>
    <h1>Generated QR Code</h1>
    <p>Token: {{ $token }}</p>
    <div>
        {!! $qrCode !!}
    </div>
</body>
</html>
