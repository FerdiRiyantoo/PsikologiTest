<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; background:#f3f4f6; margin:0; padding:0; }
        .wrapper { max-width:580px; margin:40px auto; background:#fff; border-radius:12px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,0.08); }
        .header { background:linear-gradient(135deg,#0d6efd,#06b6d4); padding:32px 40px; text-align:center; }
        .header h1 { color:#fff; margin:0; font-size:22px; font-weight:700; }
        .header p { color:rgba(255,255,255,0.85); margin:6px 0 0; font-size:13px; }
        .body { padding:36px 40px; }
        .greeting { font-size:16px; color:#374151; margin-bottom:16px; }
        .info-box { background:#f0f9ff; border:1px solid #bae6fd; border-radius:8px; padding:16px 20px; margin:20px 0; }
        .info-row { display:flex; justify-content:space-between; margin-bottom:8px; font-size:13px; }
        .info-label { color:#6b7280; }
        .info-value { color:#111827; font-weight:600; }
        .info-row:last-child { margin-bottom:0; }

        /* Magic Link Button */
        .btn-magic {
            display:block;
            background:linear-gradient(135deg,#0d6efd,#06b6d4);
            color:#fff !important;
            padding:16px 32px;
            border-radius:12px;
            text-decoration:none;
            font-weight:700;
            font-size:16px;
            text-align:center;
            margin:24px 0 12px;
            box-shadow:0 4px 14px rgba(13,110,253,0.35);
        }

        /* Kode manual */
        .code-box {
            background:#f8faff;
            border:2px dashed #c7d2fe;
            border-radius:10px;
            padding:16px 24px;
            text-align:center;
            margin:20px 0;
        }
        .code-label { color:#6b7280; font-size:11px; text-transform:uppercase; letter-spacing:2px; margin-bottom:8px; }
        .code-value { color:#1e3a8a; font-size:2rem; font-weight:900; letter-spacing:6px; font-family:monospace; }

        .divider { border:none; border-top:1px solid #f3f4f6; margin:20px 0; }
        .note { font-size:12px; color:#9ca3af; line-height:1.8; }
        .note strong { color:#6b7280; }
        .badge-type {
            display:inline-block;
            background:#e0f2fe;
            color:#0369a1;
            padding:3px 10px;
            border-radius:20px;
            font-size:12px;
            font-weight:600;
        }
        .footer-mail { background:#f9fafb; padding:20px 40px; text-align:center; font-size:11px; color:#9ca3af; border-top:1px solid #f3f4f6; }
        .expires { font-size:11px; color:#9ca3af; text-align:center; margin-top:4px; }
    </style>
</head>
<body>
<div class="wrapper">

    {{-- Header --}}
    <div class="header">
        <h1>DIGITAMA Consulting</h1>
        <p>Psikotes Online &nbsp;·&nbsp; {{ strtoupper($accessRequest->jenis_tes ?? 'PAPI') }}</p>
    </div>

    <div class="body">
        <p class="greeting">
            Halo, <strong>{{ $accessRequest->name }}</strong>!
        </p>
        <p style="color:#6b7280; font-size:14px; line-height:1.7; margin-bottom:20px">
            Permintaan akses psikotes Anda telah
            <strong style="color:#16a34a">disetujui</strong>.
            Anda dapat langsung memulai tes dengan mengklik tombol di bawah ini.
        </p>

        {{-- Info peserta --}}
        <div class="info-box">
            <div class="info-row">
                <span class="info-label">Nama</span>
                <span class="info-value">{{ $accessRequest->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Jenis Tes</span>
                <span class="info-value">
                    <span class="badge-type">{{ strtoupper($accessRequest->jenis_tes ?? 'PAPI') }}</span>
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Posisi Dilamar</span>
                <span class="info-value">
                    {{ $accessRequest->posisi_yang_dilamar ?? $accessRequest->posisi_jabatan_terakhir ?? '-' }}
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal Disetujui</span>
                <span class="info-value">{{ now()->format('d F Y, H:i') }} WIB</span>
            </div>
        </div>

        {{-- Magic Link Button --}}
        <a href="{{ route('magic.login', $accessRequest->magic_token) }}"
           class="btn-magic">
            ▶&nbsp; Mulai Tes Sekarang
        </a>
        <p class="expires">
            Link berlaku hingga
            <strong>{{ $accessRequest->magic_token_expires_at?->format('d F Y, H:i') ?? '-' }} WIB</strong>
        </p>

        <hr class="divider">

        {{-- Kode manual (backup) --}}
        <p style="text-align:center; color:#6b7280; font-size:13px; margin-bottom:8px">
            Atau gunakan kode akses ini jika link di atas tidak berfungsi:
        </p>
        <div class="code-box">
            <div class="code-label">Kode Akses Manual</div>
            <div class="code-value">{{ $accessRequest->access_code }}</div>
        </div>
        <p style="text-align:center; font-size:12px; color:#9ca3af">
            Masukkan kode di:
            <a href="{{ route('enter.code') }}" style="color:#0d6efd">
                {{ route('enter.code') }}
            </a>
        </p>

        <hr class="divider">

        {{-- Catatan --}}
        <div class="note">
            <strong>Petunjuk penting:</strong><br>
            &bull; Kerjakan tes di tempat yang tenang tanpa gangguan<br>
            &bull; Pastikan koneksi internet stabil selama tes berlangsung<br>
            &bull; Tes hanya dapat dikerjakan <strong>satu kali</strong><br>
            &bull; Link otomatis kadaluarsa dalam <strong>7 hari</strong><br>
            &bull; Jangan bagikan link atau kode ini kepada orang lain
        </div>
    </div>

    <div class="footer-mail">
        &copy; {{ date('Y') }} Digitama Consulting &nbsp;·&nbsp; info@digitama.consulting<br>
        Email ini dikirim otomatis, mohon tidak membalas email ini.
    </div>
</div>
</body>
</html>