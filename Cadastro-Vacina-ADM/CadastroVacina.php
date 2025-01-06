<?php
session_start();

$host = 'localhost';
$user = 'gustavo';
$password = 'Gustavo7327';
$dbname = 'vacineja';
// Função para redirecionar para a página de erro padrão
function redirecionarErro($mensagem) {
    $_SESSION['erro'] = $mensagem;
    header("Location: ../error.php");
    exit();
}

// Cria a conexão com o banco de dados
$conn = new mysqli($host, $user, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    redirecionarErro("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $data = $_POST['data'];
    $hora = $_POST['hora'];
    $dataHora = $data . ' ' . $hora;
    $id_posto = $_SESSION['id_posto'];
    $idade_recomendada = $_POST['idade'];

    // Inicializa a variável para armazenar a URL da imagem
    $url_imagem = '';
    $uploadSucesso = false;

    // Verifica se o arquivo foi carregado
    if (isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] == 0) {
        // Diretório de destino para o upload
        $diretorioDestino = '../uploads/';

        // Cria o diretório de destino, se necessário
        if (!is_dir($diretorioDestino)) {
            if (!mkdir($diretorioDestino, 0777, true)) {
                redirecionarErro("Erro ao criar o diretório de upload.");
            }
        }

        // Caminho do arquivo
        $nomeArquivo = $_FILES['arquivo']['name'];
        $caminhoDestino = $diretorioDestino . $nomeArquivo;

        // Verifica se o arquivo já existe
        if (file_exists($caminhoDestino)) {
            $url_imagem = $caminhoDestino; // Reutiliza o caminho do arquivo existente
            $uploadSucesso = true;
        } else {
            // Move o arquivo do diretório temporário para o destino final
            if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $caminhoDestino)) {
                $url_imagem = $caminhoDestino;
                $uploadSucesso = true;
            }
        }
    }

    // Verifica se todos os campos foram preenchidos
    if (!empty($nome) && !empty($descricao) && !empty($dataHora) && !empty($id_posto) && !empty($idade_recomendada) && $uploadSucesso) {
        // Prepara a consulta SQL para inserção no banco de dados
        $sql = "INSERT INTO Vacina (nome, descricao, data_aplicacao, id_posto, idade_recomendada, url_imagem) 
                VALUES (?, ?, ?, ?, ?, ?)";

        // Prepara a declaração
        if ($stmt = $conn->prepare($sql)) {
            // Vincula os parâmetros
            $stmt->bind_param("sssiss", $nome, $descricao, $dataHora, $id_posto, $idade_recomendada, $url_imagem);

            // Executa a consulta
            if ($stmt->execute()) {
                $stmt->close();
                $conn->close();
                header("Location: ../Catalogo-Vacinas-ADM/index.php");
                exit();
            } else {
                unlink($url_imagem); // Remove a imagem em caso de erro
                $stmt->close();
                redirecionarErro("Erro ao executar a consulta: " . $conn->error);
            }
        } else {
            unlink($url_imagem); // Remove a imagem em caso de erro
            redirecionarErro("Erro na preparação da consulta: " . $conn->error);
        }
    } else {
        if ($uploadSucesso) {
            unlink($url_imagem); // Remove a imagem se outros campos não forem preenchidos
        }
        redirecionarErro("Por favor, preencha todos os campos corretamente.");
    }
}

// Fecha a conexão
$conn->close();
?>
