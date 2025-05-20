<?php
// Dados para conexão com o banco
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "site_defeso"; // Nome do banco de dados


// Conecta ao banco
$conexao = mysqli_connect($host, $usuario, $senha, $banco);

// Verifica se a conexão foi bem-sucedida
if (!$conexao) {
    die("Erro na conexão com o banco de dados: " . mysqli_connect_error());
}

// Recebe os dados do formulário com proteção contra SQL Injection
$nome = isset($_POST['nome']) ? mysqli_real_escape_string($conexao, $_POST['nome']) : NULL;
$local = mysqli_real_escape_string($conexao, $_POST['local']);
$data = mysqli_real_escape_string($conexao, $_POST['data']);
$descricao = mysqli_real_escape_string($conexao, $_POST['descricao']);

// Se houver arquivo enviado, trata o upload
$arquivo_nome = NULL;
if (isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] == 0) {
    $pasta_upload = "uploads/"; // Pasta de destino do upload

    // Gera um nome único para o arquivo
    $arquivo_nome = time() . "_" . basename($_FILES['arquivo']['name']);
    $arquivo_caminho = $pasta_upload . $arquivo_nome;

    // Move o arquivo para a pasta de uploads
    if (!move_uploaded_file($_FILES['arquivo']['tmp_name'], $arquivo_caminho)) {
        echo "Erro ao fazer upload do arquivo.";
        exit;
    }
}

// Prepara a query para inserção dos dados no banco
$sql = "INSERT INTO denuncias (nome, local, data, descricao, arquivo) VALUES (?, ?, ?, ?, ?)";

// Prepara a execução segura (evita SQL Injection)
$stmt = mysqli_prepare($conexao, $sql);
if ($stmt === false) {
    die("Erro na preparação da consulta: " . mysqli_error($conexao));
}

// Liga os valores às interrogações da query ('s' = string)
mysqli_stmt_bind_param($stmt, "sssss", $nome, $local, $data, $descricao, $arquivo_nome);

// Executa o comando no banco
if (mysqli_stmt_execute($stmt)) {
    echo "Denúncia enviada com sucesso!";
} else {
    echo "Erro ao enviar denúncia: " . mysqli_stmt_error($stmt);
}

// Fecha a query e a conexão com o banco
mysqli_stmt_close($stmt);
mysqli_close($conexao);
?>
