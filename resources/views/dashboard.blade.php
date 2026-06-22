<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Jenis Layanan - BengkelConnect</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #fafafa;
            min-height: 100vh;
            color: #1e293b;
            display: flex;
            flex-direction: column;
        }

        /* =========================
            HEADER / BACK BUTTON
        ========================== */
        .header-nav {
            width: 100%;
            padding: 20px 40px;
            display: flex;
            align-items: center;
        }

        .back-button {
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            color: #334155;
            font-size: 15px;
            font-weight: 500;
            transition: opacity 0.2s;
        }

        .back-button:hover {
            opacity: 0.7;
        }

        /* =========================
            MAIN CONTAINER
        ========================== */
        .main-container {
            max-width: 1000px;
            width: 100%;
            margin: auto;
            padding: 20px 40px 60px;
            text-align: center;
        }

        .title-section h1 {
            font-size: 28px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 12px;
        }

        .title-section p {
            font-size: 16px;
            color: #64748b;
            margin-bottom: 45px;
        }

        /* =========================
            SERVICE CARDS
        ========================== */
        .service-wrapper {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        .service-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            text-align: left;
            text-decoration: none;
            color: #334155;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.03);
            border: 1px solid rgba(0, 0, 0, 0.01);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .service-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.06);
        }

        /* Icons */
        .icon-circle {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 25px;
        }

        .icon-red {
            background-color: #fee2e2;
            color: #dc2626;
        }

        .icon-green {
            background-color: #dcfce7;
            color: #16a34a;
        }

        /* Typography inside Card */
        .service-card h2 {
            font-size: 22px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 12px;
        }

        .service-card p {
            font-size: 14px;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        /* Features List */
        .features-list {
            list-style: none;
        }

        .features-list li {
            font-size: 14px;
            color: #334155;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .features-list li::before {
            content: "•";
            font-size: 18px;
            font-weight: bold;
        }

        .list-red li::before {
            color: #dc2626;
        }

        .list-green li::before {
            color: #16a34a;
        }

        /* =========================
            RESPONSIVE
        ========================== */
        @media (max-width: 768px) {
            .service-wrapper {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .header-nav {
                padding: 15px 20px;
            }

            .main-container {
                padding: 10px 20px 40px;
            }

            .title-section h1 {
                font-size: 24px;
            }

            .title-section p {
                font-size: 14px;
                margin-bottom: 30px;
            }
        }
    </style>
</head>

<body>

    <div class="header-nav">
        <a href="#" class="back-button">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="main-container">
        
        <div class="title-section">
            <h1>Pilih Jenis Layanan</h1>
            <p>Apakah Anda ingin datang ke bengkel atau panggil teknisi ke rumah?</p>
        </div>

        <div class="service-wrapper">

            <a href="/kunjungi-bengkel" class="service-card">
                <div class="icon-circle icon-red">
                    <i class="bi bi-building"></i>
                </div>
                <h2>Kunjungi Bengkel</h2>
                <p>Datang langsung ke bengkel kami dengan peralatan lengkap dan teknisi berpengalaman</p>
                <ul class="features-list list-red">
                    <li>Fasilitas lengkap</li>
                    <li>Ruang tunggu nyaman</li>
                    <li>Harga lebih ekonomis</li>
                </ul>
            </a>

            <a href="/home-service" class="service-card">
                <div class="icon-circle icon-green">
                    <i class="bi bi-house-door"></i>
                </div>
                <h2>Home Service</h2>
                <p>Teknisi kami datang ke lokasi Anda dengan peralatan portable dan suku cadang</p>
                <ul class="features-list list-green">
                    <li>Hemat waktu</li>
                    <li>Lebih praktis</li>
                    <li>Layanan di tempat</li>
                </ul>
            </a>

        </div>

    </div>

</body>

</html>