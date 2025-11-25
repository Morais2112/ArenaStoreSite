<?php 
session_start(); 
if(!isset($_GET['pedido'])) header("Location: index.php");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Pedido Confirmado! | ArenaStore</title>
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        .success-container {
            text-align: center;
            padding: 80px 20px;
            min-height: 60vh;
        }
        .icon-success {
            font-size: 80px;
            color: #2e7d32;
            margin-bottom: 20px;
        }
        .btn-home {
            display: inline-block;
            margin-top: 30px;
            padding: 12px 30px;
            background: #D32F2F;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="container success-container">
    <i class="far fa-check-circle icon-success"></i>
    <h1>Obrigado pela sua compra!</h1>
    <p>Seu pedido <strong>#<?= htmlspecialchars($_GET['pedido']) ?></strong> foi confirmado com sucesso.</p>
    <p>Enviamos um e-mail com os detalhes da entrega.</p>

    <a href="index.php" class="btn-home">VOLTAR PARA A LOJA</a>
</div>

<?php include 'footer.php'; ?>
</body>
</html>