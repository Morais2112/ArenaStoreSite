<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION['usuario_id'];

$stmt = $conn->prepare("SELECT nome, email, data_cadastro FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$res_user = $stmt->get_result();
$usuario = $res_user->fetch_assoc();
$stmt->close();

$stmt_pedidos = $conn->prepare("SELECT id, total, status, data_pedido FROM pedidos WHERE usuario_id = ? ORDER BY id DESC LIMIT 5");
$stmt_pedidos->bind_param("i", $id_usuario);
$stmt_pedidos->execute();
$res_pedidos = $stmt_pedidos->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Minha Conta | ArenaStore</title>
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        .account-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: 1fr 2fr; 
            gap: 30px;
            min-height: 60vh;
        }

        .profile-card {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            text-align: center;
            height: fit-content;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            background-color: #eee;
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            color: #aaa;
        }

        .profile-name {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 5px;
            color: #333;
        }

        .profile-email {
            color: #666;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .info-group {
            text-align: left;
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .info-label {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            font-weight: bold;
        }
        .info-value {
            font-size: 15px;
            color: #333;
            font-weight: 500;
        }

        .btn-logout {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: #fff;
            border: 1px solid #D32F2F;
            color: #D32F2F;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.2s;
            text-decoration: none;
            margin-top: 20px;
        }
        .btn-logout:hover {
            background-color: #D32F2F;
            color: #fff;
        }

        .orders-section {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
        }
        
        .section-title {
            font-size: 20px;
            border-bottom: 2px solid #f4f4f4;
            padding-bottom: 15px;
            margin-bottom: 20px;
            color: #333;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }
        .order-item:last-child { border-bottom: none; }

        .order-info h4 { margin: 0 0 5px; color: #333; }
        .order-info span { font-size: 13px; color: #777; }
        
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-pendente { background: #fff3cd; color: #856404; }
        .status-pago { background: #d4edda; color: #155724; }

        @media (max-width: 768px) {
            .account-container { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="account-container">
    
    <div class="profile-card">
        <div class="profile-avatar">
            <i class="fas fa-user"></i>
        </div>
        
        <div class="profile-name"><?= htmlspecialchars($usuario['nome']) ?></div>
        <div class="profile-email"><?= htmlspecialchars($usuario['email']) ?></div>

        <div class="info-group">
            <div class="info-label">Data de Cadastro</div>
            <div class="info-value">
                <?= date('d/m/Y', strtotime($usuario['data_cadastro'])) ?>
            </div>
        </div>
        
        <div class="info-group">
            <div class="info-label">ID do Cliente</div>
            <div class="info-value">#<?= $id_usuario ?></div>
        </div>

        <a href="logout.php" class="btn-logout">
            <i class="fas fa-sign-out-alt"></i> Sair da Conta
        </a>
    </div>

    <div class="orders-section">
        <h2 class="section-title">Meus Últimos Pedidos</h2>
        
        <?php if ($res_pedidos && $res_pedidos->num_rows > 0): ?>
            <?php while($pedido = $res_pedidos->fetch_assoc()): ?>
                <div class="order-item">
                    <div class="order-info">
                        <h4>Pedido #<?= $pedido['id'] ?></h4>
                        <span><?= date('d/m/Y \à\s H:i', strtotime($pedido['data_pedido'])) ?></span>
                    </div>
                    <div class="order-values">
                        <div style="font-weight: bold; margin-bottom: 5px;">
                            R$ <?= number_format($pedido['total'], 2, ',', '.') ?>
                        </div>
                        <span class="status-badge status-<?= strtolower($pedido['status']) ?>">
                            <?= $pedido['status'] ?>
                        </span>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="color: #777; text-align: center; padding: 20px;">
                Você ainda não fez nenhum pedido. <br>
                <a href="index.php" style="color: #D32F2F; font-weight: bold;">Ir para a loja</a>
            </p>
        <?php endif; ?>
        
    </div>

</div>

<?php include 'footer.php'; ?>

</body>
</html>