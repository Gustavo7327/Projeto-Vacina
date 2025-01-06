<?php
session_start();

// Dados de conexão com o banco de dados
$host = 'localhost';
$user = 'gustavo';
$password = 'Gustavo7327';
$dbname = 'vacineja';

// Cria a conexão com o banco de dados
$conn = new mysqli($host, $user, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Função para redirecionar com mensagem de erro
function redirecionarErro($mensagem) {
    $_SESSION['erro'] = $mensagem;
    header("Location: ../error.php");
    exit();
}

// Função para fazer upload de uma nova imagem
function fazerUploadImagem($arquivo, $imagemAntiga) {
    $diretorioDestino = '../uploads/';
    
    // Verifica se o diretório existe, caso contrário, cria
    if (!is_dir($diretorioDestino)) {
        if (!mkdir($diretorioDestino, 0777, true)) {
            redirecionarErro("Erro ao criar o diretório de uploads.");
        }
    }

    // Gera um nome único para a nova imagem
    $extensao = pathinfo($arquivo['name'], PATHINFO_EXTENSION);
    $novoNomeImagem = uniqid('vacina_', true) . '.' . $extensao;
    $caminhoDestino = $diretorioDestino . $novoNomeImagem;

    // Move a nova imagem para o diretório de uploads
    if (move_uploaded_file($arquivo['tmp_name'], $caminhoDestino)) {
        // Exclui a imagem antiga, se existir
        if ($imagemAntiga && file_exists($imagemAntiga)) {
            unlink($imagemAntiga);
        }
        return $caminhoDestino;
    } else {
        redirecionarErro("Erro ao fazer upload da imagem.");
    }
}

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $idVacina = $_POST['id'];
    $nome = $_POST['nome'];
    $idade = $_POST['idade'];
    $descricao = $_POST['descricao'];
    $data = $_POST['data'];
    $hora = $_POST['hora'];
    $dataHora = $data . ' ' . $hora;

    // Busca o caminho da imagem atual
    $sql = "SELECT url_imagem FROM Vacina WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $idVacina);
    $stmt->execute();
    $stmt->bind_result($urlImagemAtual);
    $stmt->fetch();
    $stmt->close();

    // Verifica se uma nova imagem foi enviada
    if (isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] == UPLOAD_ERR_OK) {
        $caminhoImagem = fazerUploadImagem($_FILES['arquivo'], $urlImagemAtual);
    } else {
        // Mantém a imagem antiga se nenhuma nova imagem foi enviada
        $caminhoImagem = $urlImagemAtual;
    }

    // Atualiza os dados no banco de dados
    $sql = "UPDATE Vacina SET nome = ?, idade_recomendada = ?, descricao = ?, data_aplicacao = ?, url_imagem = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssssi', $nome, $idade, $descricao, $dataHora, $caminhoImagem, $idVacina);

    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: ../Catalogo-Vacinas-ADM/index.php");
        exit();
    } else {
        redirecionarErro("Erro ao atualizar a vacina: " . $conn->error);
    }
}
?>
