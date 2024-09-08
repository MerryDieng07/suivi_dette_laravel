<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f4f9; /* Couleur de fond globale */
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            background: #ffffff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
            background: url('path/to/your/background-image.jpg') no-repeat center center; /* Image d'arrière-plan */
            background-size: cover; /* Couverture totale de l'arrière-plan */
            color: #ffffff; /* Texte blanc pour contraster avec l'arrière-plan */
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            width: 100px;
            height: auto;
            border-radius: 50%;
            border: 3px solid #6c63ff; /* Bordure colorée autour de l'image */
        }
        .client-info {
            text-align: center;
            margin-bottom: 20px;
            color:black;
        }
        .client-info h1 {
            font-size: 28px;
            color: #6c63ff; /* Couleur du titre */
            margin-bottom: 5px;
        }
        .client-info p {
            font-size: 18px;
            color: #ffffff; /* Couleur des informations du client */
            margin-bottom: 10px;
        }
        .qr-code {
            text-align: center;
            margin-top: 30px;
        }
        .qr-code h2 {
            font-size: 22px;
            color: #ff6584; /* Couleur du titre de la section QR */
            margin-bottom: 15px;
        }
        .qr-code img {
            width: 150px;
            height: 150px;
            margin-top: 10px;
            background-color: #ffffff; /* Fond blanc pour le code QR pour assurer la lisibilité */
            padding: 10px;
            border-radius: 10px;
        }
        /* Ajout d'éléments décoratifs en arrière-plan */
        .decorative-elements {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: linear-gradient(135deg, rgba(255,101,132,0.3), rgba(108,99,255,0.3)); /* Dégradé semi-transparent */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="decorative-elements"></div> <!-- Eléments décoratifs colorés -->
        <div class="header">
            <img src="photos/1jAhz4D3QDRFibNBnJFpXsQKy8zrjraCfaZFGR8p.jpg" alt="Photo du client">
        </div>
        <div class="client-info">
            <h1>Nom du client : {{ $client }}</h1>
         
        </div>
        <div class="qr-code">
            <h2>Code QR</h2>
            <img src="{{ $qrCodePath }}" alt="Code QR">
        </div>
    </div>
</body>
</html>