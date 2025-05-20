<?php
// Dados de conexão com o banco de dados
$servidor = "localhost";      // Nome do servidor (localhost para o XAMPP)
$usuario = "root";            // Usuário do banco (padrão no XAMPP é 'root')
$senha = "";                  
$banco = "site_defeso";    // Nome do banco de dados que você criou

// Cria uma nova conexão com o banco
$conn = new mysqli($servidor, $usuario, $senha, $banco);

// Verifica se houve erro na conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error); // Encerra o script se houver erro
}

// Recebe os dados enviados pelo formulário (via método POST)
$nome = $_POST['nome'];
$email = $_POST['email'];
$mensagem = $_POST['mensagem'];

// Comando SQL com placeholders (?) para segurança (evita SQL Injection)
$sql = "INSERT INTO feedbacks (nome, email, mensagem) VALUES (?, ?, ?)";

// Prepara o comando SQL
$stmt = $conn->prepare($sql);

// Associa os valores aos placeholders: s = string
$stmt->bind_param("sss", $nome, $email, $mensagem);

// Executa o comando
if ($stmt->execute()) {
    echo "Obrigado pelo seu feedback!"; // Sucesso
} else {
    echo "Erro ao enviar feedback: " . $stmt->error; // Exibe erro, se houver
}

// Fecha a conexão com o banco
$stmt->close();
$conn->close();
?>
