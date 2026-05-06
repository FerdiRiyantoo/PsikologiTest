<!DOCTYPE html>
<html lang="id">
<head>
    <title>Kode Akses Psikotes</title>
</head>
<body>
    <h1>Halo, {{ $accessRequest->name }}!</h1>
    <p>Pendaftaran Anda untuk mengikuti psikotes telah disetujui.</p>
    <p>Gunakan kode akses berikut untuk memulai tes:</p>

    <div style="padding: 15px; background-color: #f2f2f2; border: 1px solid #ddd; text-align: center; font-size: 24px; font-weight: bold; letter-spacing: 5px; margin: 20px 0; border-radius: 8px;">
        {{ $accessRequest->access_code }}
    </div>

    <p>Anda dapat memasukkan kode ini pada halaman <a href="{{ route('enter.code') }}">berikut</a>.</p>
    <p>Terima kasih.</p>
</body>
</html>