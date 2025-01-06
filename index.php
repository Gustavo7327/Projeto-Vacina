<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Links</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            text-align: center;
            background: #ffffff;
            padding: 20px 40px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        h1 {
            margin-bottom: 20px;
            color: #333;
        }
        a {
            display: block;
            text-decoration: none;
            font-size: 18px;
            color: #007BFF;
            margin: 10px 0;
            padding: 10px;
            border: 2px solid transparent;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        a:hover {
            background-color: #007BFF;
            color: #ffffff;
            border-color: #007BFF;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Menu de Navegação</h1>
        <a href="Login/index.php">Login</a>
        <a href="Catalogo/index.php">Catálogo do Usuário</a>
        <a href="Catalogo-Vacinas-ADM/index.php">Catálogo do ADM (login necessário)</a>
        <a href="Cadastro/index.php">Cadastro de ADM (admin_prefeitura necessário)</a>
    </div>
</body>
</html>
