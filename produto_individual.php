<?php
include 'conexao.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}
$id_produto = (int) $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM produtos WHERE id = ?");
$stmt->bind_param("i", $id_produto);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows == 0) {
    echo "<h1 style='text-align:center; margin-top:50px;'>Produto não encontrado!</h1>";
    exit;
}
$produto = $result->fetch_assoc();
$stmt->close();

$stmt_imgs = $conn->prepare("SELECT caminho FROM imagens_produtos WHERE produto_id = ? ORDER BY principal DESC");
$stmt_imgs->bind_param("i", $id_produto);
$stmt_imgs->execute();
$result_imgs = $stmt_imgs->get_result();

$imagens = [];
if ($result_imgs && $result_imgs->num_rows > 0) {
    while ($row = $result_imgs->fetch_assoc()) {
        $imagens[] = $row['caminho'];
    }
}
if (empty($imagens)) {
    $imagens[] = "img/sem_foto.png";
}
$stmt_imgs->close();

$preco = isset($produto['preco']) ? (float)$produto['preco'] : 0.0;
$preco_promo = isset($produto['preco_promocional']) ? (float)$produto['preco_promocional'] : 0.0;
$tem_promo = ($preco_promo > 0 && $preco_promo < $preco);
$preco_final = $tem_promo ? $preco_promo : $preco;
$parcela = $preco_final > 0 ? ($preco_final / 5) : 0.0;

$data_cadastro = $produto['data_cadastro'] ?? null;
$badge_novo = false;
if (!empty($data_cadastro)) {
    $ts = strtotime($data_cadastro);
    if ($ts !== false) {
        $badge_novo = ($ts > strtotime('-25 days'));
    }
}

$stmt_rel = $conn->prepare("
    SELECT p.id, p.nome, p.preco, p.preco_promocional, i.caminho AS imagem
    FROM produtos p
    INNER JOIN imagens_produtos i ON p.id = i.produto_id
    WHERE i.principal = 1 AND p.id != ?
    ORDER BY RAND() LIMIT 4
");
$stmt_rel->bind_param("i", $id_produto);
$stmt_rel->execute();
$result_relacionados = $stmt_rel->get_result();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($produto['nome'] ?? 'Produto') ?> | ArenaStore</title>

    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/cards.css"> 
    <link rel="stylesheet" href="css/product-page.css">

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
</head>

<body>

<?php include 'header.php'; ?>

<div class="container product-detail-page">

    <div class="breadcrumb">
        <a href="index.php">Início</a>
        <i class="fas fa-chevron-right"></i>
        <span><?= htmlspecialchars($produto['nome'] ?? '') ?></span>
    </div>

    <div class="product-wrapper">

        <div class="product-gallery">
            <div class="main-image-container">
                <img id="mainImg" src="<?= htmlspecialchars($imagens[0]) ?>" alt="Imagem Principal">

                <div class="thumbnails-row">
                    <?php foreach ($imagens as $index => $img): ?>
                        <div class="thumb-box <?= $index == 0 ? 'active' : '' ?>">
                            <img src="<?= htmlspecialchars($img) ?>" alt="thumb">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="product-info">

            <div class="p-badges">
                <?php if ($tem_promo): ?>
                    <span class="p-badge sale">Promo</span>
                <?php endif; ?>
                <?php if ($badge_novo): ?>
                    <span class="p-badge new">Novo</span>
                <?php endif; ?>
            </div>

            <h1 class="p-title"><?= htmlspecialchars($produto['nome']) ?></h1>

            <div class="p-description-moved">
                 Visual oficial com acabamento premium. Veja detalhes completos abaixo.
            </div>

            <div class="p-price-area">
                <?php if ($tem_promo): ?>
                    <span class="p-old-price">R$ <?= number_format($preco, 2, ',', '.') ?></span>
                <?php endif; ?>

                <div class="p-final-price">
                    R$ <?= number_format($preco_final, 2, ',', '.') ?>
                </div>

                <div class="p-installments">
                    em até 5x de R$ <?= number_format($parcela, 2, ',', '.') ?> sem juros
                </div>
            </div>

            <div class="p-selector">
                <div class="selector-header">
                    <h3>Tamanho</h3>
                    <a href="#" class="guide-link">Guia de medidas</a>
                </div>
                
                <div class="size-options-p">
                    <?php 
                    $tamanhos = ["P", "M", "G", "GG", "GGG"];
                    foreach ($tamanhos as $t):
                    ?>
                        <div class="size-option"><?= htmlspecialchars($t) ?></div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="p-actions-container">
                <div class="p-row-cart">
                    <div class="qtd-wrapper">
                        <button type="button" onclick="alterarQtd(-1)">−</button>
                        <input id="qtdInput" type="text" value="1" readonly>
                        <button type="button" onclick="alterarQtd(1)">+</button>
                    </div>

                    <button class="btn-add-cart" onclick="addCarrinho(<?= $id_produto ?>)">
                        <i class="fa fa-cart-plus"></i> Adicionar
                    </button>
                </div>

                <button class="btn-buy-now" onclick="comprarAgora(<?= $id_produto ?>)">
                    Comprar agora
                </button>
            </div>

            <div class="shipping-calc">
                <label><i class="fas fa-truck"></i> Calcule o frete:</label>
                <div class="input-cep-row">
                    <input type="text" id="cepInput" placeholder="00000-000" maxlength="9">
                    <button type="button" onclick="calcularFrete()">OK</button>
                </div>
            </div>

        </div> 
    </div> 
    
    <section class="description-section">
        <h2 class="desc-title">Descrição Detalhada</h2>
        
        <div class="desc-banner">
            <img src="img/bannerdesc.png" alt="Banner do Produto">
        </div>

        <div class="desc-text-content">
            <?php 
            if (!empty($produto['descricao'])) {
                echo nl2br(htmlspecialchars($produto['descricao'])); 
            } else {
                echo "<p>Produto oficial com acabamento premium, desenvolvido para máximo conforto e durabilidade. Ideal para uso casual ou para praticar esportes.</p>";
            }
            ?>
        </div>
    </section>

</div> 

<section class="container related-products-section">
    <div class="section-header-custom">
        <h2>Produtos Relacionados</h2>
    </div>

    <div class="products-grid-wrap">
        <?php
        if ($result_relacionados && $result_relacionados->num_rows > 0):
            while ($rel = $result_relacionados->fetch_assoc()):
                $preco_rel = isset($rel['preco_promocional']) && $rel['preco_promocional'] > 0 ? (float)$rel['preco_promocional'] : (float)$rel['preco'];
                $parcela_rel = $preco_rel > 0 ? ($preco_rel / 5) : 0;
        ?>
                <div class="product-card-clean">
                    <a href="produto_individual.php?id=<?= (int)$rel['id'] ?>">
                        <div class="img-box-clean">
                            <img src="<?= htmlspecialchars($rel['imagem']) ?>" alt="<?= htmlspecialchars($rel['nome']) ?>">
                        </div>
                    </a>
                    <div class="details-clean">
                        <h3 class="product-title"><?= htmlspecialchars($rel['nome']) ?></h3>
                        <div class="price-area">
                            <strong>R$ <?= number_format($preco_rel, 2, ',', '.') ?></strong>
                        </div>
                        <p class="installment-text">
                            5x de R$ <?= number_format($parcela_rel, 2, ',', '.') ?>
                        </p>
                    </div>
                </div>
        <?php
            endwhile;
        else:
            echo "<p style='color:#999;'>Nenhum relacionado.</p>";
        endif;
        $stmt_rel->close();
        ?>
    </div>
</section>

<?php include 'footer.php'; ?>

<script src="js/produto.js" defer></script>

<script>
function comprarAgora(id) {
    addCarrinho(id);
    
}

function calcularFrete() {
    let cep = document.getElementById('cepInput').value;
    if(cep.length < 8) alert("Digite um CEP válido.");
    else alert("Frete simulado para: " + cep + "\nValor: R$ 15,90\nPrazo: 5 dias úteis");
}
</script>

</body>
</html>