<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <title>Apliecinājums</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
            padding: 10px;
            background: #e0f7fa;
        }
        .apliecinajums {
            background: #ffffff;
            border: 4px solid #26a69a;
            border-radius: 15px;
            padding: 40px;
            max-width: 700px;
            margin: 0 auto;
            text-align: center;
        }
        .header {
            font-size: 14px;
            margin-bottom: 10px;
            color: #444;
        }
        .logo {
            margin: 30px 0;
        }
        h1 {
            font-size: 36px;
            color: #00796b;
            margin-bottom: 10px;
            font-weight: bold;
        }
        h2 {
            font-size: 24px;
            color: #00796b;
            margin-bottom: 20px;
        }
        .vards {
            font-size: 28px;
            font-weight: bold;
            color: #8e24aa;
            margin: 20px 0;
        }
        .info {
            font-size: 16px;
            color: #333;
            margin: 10px 0;
        }
        .datumi {
            margin: 20px 0;
            font-size: 16px;
            color: #555;
        }
        .stundas {
            font-size: 18px;
            font-weight: bold;
            color: #00796b;
            margin: 20px 0;
        }
        .pateiciba {
            font-size: 16px;
            margin-top: 30px;
            color: #333;
        }
        .signature {
            margin-top: 50px;
            text-align: right;
            font-size: 14px;
            color: #333;
        }
        .bottom {
            margin-top: 30px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="apliecinajums">
        <div class="header">
            Biedrība "Mums pieder pasaule", reģ.Nr. 40008183872<br>
            Adrese: Gaismas, Svitenes pag., Bauskas novads LV-3917 Latvija
        </div>

        <div class="logo">
            <img src="{{ public_path('images/mpp.png') }}" alt="MPP Logo" width="150" height="150">
        </div>



        <h1>APLIECINĀJUMS</h1>
        <h2>par brīvprātīgo darbu</h2>

        <p class="info">Ar prieku apliecinām, ka</p>
        <div class="vards">{{ $vards }} {{ $uzvards }}</div>

        <p class="info">ir bijis vērtīgs brīvprātīgais mūsu komandā laikā:</p>

        <div class="datumi">
            no {{ \Carbon\Carbon::parse($no)->format('d.m.Y') }} līdz {{ \Carbon\Carbon::parse($lidz)->format('d.m.Y') }}
        </div>

        <div class="stundas">
            Kopā paveiktas {{ $stundas }} stundas brīvprātīgā darba!
        </div>

        <p class="pateiciba">
            Paldies par Tavu enerģiju un ieguldījumu! Mēs augsti vērtējam Tavu palīdzību.
        </p>

        <div class="signature">
            Svitenē, {{ \Carbon\Carbon::now()->format('d.m.Y') }}<br><br>
            ___________________________<br>
            Vita Reinfelde<br>
            Projekta vadītāja
        </div>

        <div class="bottom">
            www.mumspiederpasaule.com<br>
            instagram.com/mppasaule
        </div>
    </div>
</body>
</html>
