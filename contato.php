<?php
session_start();
include 'conexao.php';

$msg_sucesso = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $msg_sucesso = "Sua mensagem foi enviada com sucesso! Entraremos em contato em breve.";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fale Conosco | ArenaStore</title>
    
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/contato.css"> <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="container contact-page">
    
    <div class="breadcrumb">
        <a href="index.php">Home</a>
        <i class="fas fa-chevron-right" style="font-size: 10px; margin: 0 5px;"></i>
        <span>Contato</span>
    </div>

    <div class="section-header-left" style="margin-top: 20px;">
        <h2>Fale Conosco</h2>
    </div>

    <?php if($msg_sucesso): ?>
        <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
            <i class="fas fa-check-circle"></i> <?= $msg_sucesso ?>
        </div>
    <?php endif; ?>

    <div class="contact-wrapper">
        
        <div class="contact-info">
            <div class="info-item">
                <div class="info-icon"><i class="fas fa-phone-alt"></i></div>
                <div class="info-content">
                    <h4>Telefone</h4>
                    <p>(31) 1313-1313</p>
                    <p>(31) 1010-1010</p>
                </div>
            </div>

            <div class="info-item">
                <div class="info-icon"><i class="fab fa-whatsapp"></i></div>
                <div class="info-content">
                    <h4>WhatsApp</h4>
                    <p>(31) 97777-7777</p>
                    <p>Atendimento rápido</p>
                </div>
            </div>

            <div class="info-item">
                <div class="info-icon"><i class="far fa-envelope"></i></div>
                <div class="info-content">
                    <h4>E-mail</h4>
                    <p>suporte@arenastore.com.br</p>
                    <p>vendas@arenastore.com.br</p>
                </div>
            </div>

            <div class="info-item">
                <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
                <div class="info-content">
                    <h4>Endereço</h4>
                    <p>Rua Guaicurus, 660 - Centro</p>
                    <p>Belo Horizonte - MG, 30111-060</p>
                </div>
            </div>

            <div class="map-container">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3750.966787473789!2d-43.94145862509495!3d-19.925806038182915!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xa699e8631456c7%3A0x8372c4c38b306427!2sCentro%2C%20Belo%20Horizonte%20-%20MG!5e0!3m2!1spt-BR!2sbr!4v1709234567890!5m2!1spt-BR!2sbr" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>

        <div class="contact-form-container">
            <form method="POST" action="contato.php">
                <div class="form-group">
                    <label>Nome Completo</label>
                    <input type="text" name="nome" required placeholder="Digite seu nome">
                </div>

                <div class="form-group">
                    <label>E-mail</label>
                    <input type="email" name="email" required placeholder="Digite seu e-mail">
                </div>

                <div class="form-group">
                    <label>Assunto</label>
                    <input type="text" name="assunto" required placeholder="Ex: Dúvida sobre pedido">
                </div>

                <div class="form-group">
                    <label>Mensagem</label>
                    <textarea name="mensagem" required placeholder="Como podemos te ajudar?"></textarea>
                </div>

                <button type="submit" class="btn-send">ENVIAR MENSAGEM</button>
            </form>
        </div>

    </div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>