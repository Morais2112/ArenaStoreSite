<?php
session_start();
include 'conexao.php';

if (isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

$erro = "";
$sucesso = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $acao = $_POST['acao'];

    if ($acao == 'login') {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $senha = $_POST['senha'];

        $stmt = $conn->prepare("SELECT id, nome, senha FROM usuarios WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $usuario = $result->fetch_assoc();
            
            if (password_verify($senha, $usuario['senha'])) {
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nome'] = $usuario['nome'];
                header("Location: index.php");
                exit;
            } else {
                $erro = "Senha incorreta.";
            }
        } else {
            $erro = "E-mail não encontrado.";
        }
        $stmt->close();
    } 
    
    elseif ($acao == 'cadastro') {
        $nome = htmlspecialchars($_POST['nome']);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $senha = $_POST['senha'];
        $confirmar = $_POST['confirmar_senha'];

        if ($senha !== $confirmar) {
            $erro = "As senhas não coincidem.";
        } else {
            $check = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
            $check->bind_param("s", $email);
            $check->execute();
            $check->store_result();

            if ($check->num_rows > 0) {
                $erro = "Este e-mail já está cadastrado.";
            } else {
                $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
                
                $insert = $conn->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
                $insert->bind_param("sss", $nome, $email, $senhaHash);
                
                if ($insert->execute()) {
                    $sucesso = "Conta criada com sucesso! Faça login agora.";
                } else {
                    $erro = "Erro ao criar conta. Tente novamente.";
                }
                $insert->close();
            }
            $check->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Conta | ArenaStore</title>
    
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/login.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="login-page-container">
    <div class="auth-container">
        
        <?php if($erro): ?>
            <div class="msg-box msg-error"><i class="fas fa-exclamation-circle"></i> <?= $erro ?></div>
        <?php endif; ?>

        <?php if($sucesso): ?>
            <div class="msg-box msg-success"><i class="fas fa-check-circle"></i> <?= $sucesso ?></div>
        <?php endif; ?>

        <div class="auth-tabs">
            <div class="auth-tab active" onclick="switchTab('login')">Entrar</div>
            <div class="auth-tab" onclick="switchTab('cadastro')">Criar Conta</div>
        </div>

        <form method="POST" class="auth-form active" id="form-login">
            <input type="hidden" name="acao" value="login">
            
            <div class="form-group">
                <label>E-mail</label>
                <input type="email" name="email" required placeholder="seu@email.com">
            </div>
            <div class="form-group">
                <label>Senha</label>
                <input type="password" name="senha" required placeholder="********">
            </div>
            
            <button type="submit" class="btn-submit">Acessar Conta</button>
        </form>

        <form method="POST" class="auth-form" id="form-cadastro">
            <input type="hidden" name="acao" value="cadastro">
            
            <div class="form-group">
                <label>Nome Completo</label>
                <input type="text" name="nome" required placeholder="Ex: João da Silva">
            </div>
            <div class="form-group">
                <label>E-mail</label>
                <input type="email" name="email" required placeholder="seu@email.com">
            </div>
            <div class="form-group">
                <label>Senha</label>
                <input type="password" name="senha" required placeholder="Crie uma senha segura" minlength="6">
            </div>
            <div class="form-group">
                <label>Confirmar Senha</label>
                <input type="password" name="confirmar_senha" required placeholder="Repita a senha">
            </div>

            <button type="submit" class="btn-submit">Cadastrar</button>
        </form>

    </div>
</div>

<?php include 'footer.php'; ?>

<script>
    function switchTab(tab) {
        const formLogin = document.getElementById('form-login');
        const formCadastro = document.getElementById('form-cadastro');
        const tabs = document.querySelectorAll('.auth-tab');

        const msgBox = document.querySelector('.msg-box');
        if(msgBox) msgBox.style.display = 'none';

        if (tab === 'login') {
            formLogin.classList.add('active');
            formCadastro.classList.remove('active');
            tabs[0].classList.add('active');
            tabs[1].classList.remove('active');
        } else {
            formLogin.classList.remove('active');
            formCadastro.classList.add('active');
            tabs[0].classList.remove('active');
            tabs[1].classList.add('active');
        }
    }
</script>

</body>
</html>