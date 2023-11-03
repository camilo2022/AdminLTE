<!DOCTYPE html>
<html>
<head>
    <title>{{ $data['title'] }}</title>
</head>
<body>
    <h1>{{ $data['title'] }}</h1>
    <p>{{ $data['message'] }}</p>
    <img src="data:image/png;base64,{{ base64_encode($qrCode) }}" alt="Código QR">
</body>
</html>
