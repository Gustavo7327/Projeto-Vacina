<?php
session_start();

// Verifica se há uma mensagem de erro armazenada na sessão
$mensagemErro = $_SESSION['erro'] ?? "Ocorreu um erro inesperado. Por favor, tente novamente mais tarde.";

// Limpa a mensagem de erro da sessão após exibi-la
unset($_SESSION['erro']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erro</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            text-align: center;
            padding: 50px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }
        h1 {
            color: #d9534f;
        }
        p {
            font-size: 18px;
            line-height: 1.5;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }
        a:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Ops! Algo deu errado</h1>
        <p><?php echo htmlspecialchars($mensagemErro); ?></p>
        <a href="index.php">Voltar para a página inicial</a>
    </div>
</body>
</html>
