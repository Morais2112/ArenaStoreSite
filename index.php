<?php
include 'conexao.php';

$id_categoria_lancamentos = 75; 
$id_categoria_camisas = 73; 

$sql = "SELECT p.id, p.nome, p.preco, p.preco_promocional, i.caminho AS imagem
        FROM produtos p
        INNER JOIN imagens_produtos i ON p.id = i.produto_id
        INNER JOIN produto_categorias pc ON p.id = pc.produto_id
        WHERE pc.categoria_id = $id_categoria_lancamentos 
        AND i.principal = 1
        GROUP BY p.id
        LIMIT 8";
$resultado_produtos = $conn->query($sql);

$sql2 = "SELECT p.id, p.nome, p.preco, p.preco_promocional, i.caminho AS imagem
         FROM produtos p
         INNER JOIN imagens_produtos i ON p.id = i.produto_id
         INNER JOIN produto_categorias pc ON p.id = pc.produto_id
         WHERE pc.categoria_id = $id_categoria_camisas
         AND i.principal = 1
         GROUP BY p.id
         LIMIT 8";
$resultado_camisas = $conn->query($sql2);

$id_categoria_infantil = 72; 
$sql3 = "SELECT p.id, p.nome, p.preco, p.preco_promocional, i.caminho AS imagem
         FROM produtos p
         INNER JOIN imagens_produtos i ON p.id = i.produto_id
         INNER JOIN produto_categorias pc ON p.id = pc.produto_id
         WHERE pc.categoria_id = $id_categoria_infantil 
         AND i.principal = 1
         GROUP BY p.id
         LIMIT 8";
$resultado_infantis = $conn->query($sql3);

$id_categoria_diferenciadas = 74; 
$sql4 = "SELECT p.id, p.nome, p.preco, p.preco_promocional, i.caminho AS imagem
         FROM produtos p
         INNER JOIN imagens_produtos i ON p.id = i.produto_id
         INNER JOIN produto_categorias pc ON p.id = pc.produto_id
         WHERE pc.categoria_id = $id_categoria_diferenciadas
         AND i.principal = 1
         GROUP BY p.id
         LIMIT 4"; 
$resultado_diferenciadas = $conn->query($sql4);

$id_categoria_femininas = 69; 
$sql5 = "SELECT p.id, p.nome, p.preco, p.preco_promocional, i.caminho AS imagem
         FROM produtos p
         INNER JOIN imagens_produtos i ON p.id = i.produto_id
         INNER JOIN produto_categorias pc ON p.id = pc.produto_id
         WHERE pc.categoria_id = $id_categoria_femininas 
         AND i.principal = 1
         GROUP BY p.id
         LIMIT 8";
$resultado_femininas = $conn->query($sql5);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ArenaStore | Artigos Esportivos</title>
    
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/cards.css">
    <link rel="stylesheet" href="css/home.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<?php include 'header.php'; ?>

<section class="slider-container">
    <div class="slide fade active">
        <img src="img/Oferta.png" alt="Promoção Leve 3 Pague 2">
    </div>
    <div class="slide fade">
        <img src="img/pague 2 (1).png" alt="Lançamentos 2025">
    </div>

    <a class="prev" onclick="mudarSlide(-1)">&#10094;</a>
    <a class="next" onclick="mudarSlide(1)">&#10095;</a>

    <div class="dots-container">
        <span class="dot active" onclick="slideAtual(1)"></span>
        <span class="dot" onclick="slideAtual(2)"></span>
    </div>
</section>

<section class="categories-section container">
    <div class="section-header-left">
        <h2>Nossas Categorias</h2>
    </div>
    <div class="categories-container">
        <a href="produtos.php?id=68" class="category-item">
            <img src="img/neymar.png" alt="Camisetas Masculinas">
            <span>Camisetas Masculinas</span>
        </a>
        <a href="produtos.php?id=69" class="category-item">
            <img src="img/feminina.png" alt="Camisetas Femininas">
            <span>Camisetas Femininas</span>
        </a>
        <a href="produtos.php?id=70" class="category-item">
            <img src="img/adriano.png" alt="Camisetas Retrô">
            <span>Camisetas Retrô</span>
        </a>
        <a href="produtos.php?id=72" class="category-item">
            <img src="img/crianca.png" alt="Conjuntos Infantis">
            <span>Conjuntos Infantis</span>
        </a>
        <a href="produtos.php?id=71" class="category-item">
            <img src="img/vini.png" alt="Agasalhos">
            <span>Agasalhos</span>
        </a>
    </div>
</section>

<main class="container products-section">
    
    <div class="section-header-custom">
        <h2>Lançamentos 2025/26</h2>
        <a href="produtos.php?id=75" class="see-all-link">Ver todos</a>
    </div>

   <div class="product-carousel-container">

    <button class="slider-arrow arrow-left" onclick="paginaAnterior('track-lancamentos')">
        <i class="fas fa-chevron-left"></i>
    </button>

    <div class="product-grid track" id="track-lancamentos">
        <?php if ($resultado_produtos->num_rows > 0): ?>
            <?php while($p = $resultado_produtos->fetch_assoc()): ?>

                <?php 
                    $preco_final = ($p['preco_promocional'] > 0) ? $p['preco_promocional'] : $p['preco'];
                    $parcela = $preco_final / 5;
                ?>

                <div class="product-card-clean">
                    <div class="img-box-clean">
                        <a href="produto_individual.php?id=<?= $p['id'] ?>">
                            <img src="<?= $p['imagem'] ?>" alt="<?= $p['nome'] ?>">
                        </a>
                    </div>

                    <div class="details-clean">
                        <h3 class="product-title"><?= $p['nome'] ?></h3>

                        <div class="price-area">
                            <?php if($p['preco_promocional'] > 0 && $p['preco_promocional'] < $p['preco']): ?>
                                <span class="old-price">R$ <?= number_format($p['preco'], 2, ',', '.') ?></span>
                            <?php endif; ?>

                            <strong>R$ <?= number_format($preco_final, 2, ',', '.') ?></strong>
                        </div>

                        <p class="installment-text">
                            Em até 5x R$ <?= number_format($parcela, 2, ',', '.') ?> sem juros
                        </p>
                    </div>
                </div>

            <?php endwhile; ?>
        <?php endif; ?>
    </div>

    <button class="slider-arrow arrow-right" onclick="proximaPagina('track-lancamentos')">
        <i class="fas fa-chevron-right"></i>
    </button>

    </div>

    <div class="carousel-indicators" id="indicators-lancamentos">
        <span class="indicator active" onclick="irParaPagina('track-lancamentos', 0)"></span>
        <span class="indicator" onclick="irParaPagina('track-lancamentos', 1)"></span>
    </div>

    <div class="section-header-custom">
        <h2>Camisas Mais Vendidas</h2>
        <a href="produtos.php?id=73" class="see-all-link">Ver todos</a>
    </div>

    <div class="product-carousel-container">

    <button class="slider-arrow arrow-left" onclick="paginaAnterior('track-mais-vendidas')">
        <i class="fas fa-chevron-left"></i>
    </button>

    <div class="product-grid track" id="track-mais-vendidas">
        <?php if ($resultado_camisas->num_rows > 0): ?>
            <?php while($c = $resultado_camisas->fetch_assoc()): ?>

                <?php 
                    $preco_final_c = ($c['preco_promocional'] > 0) ? $c['preco_promocional'] : $c['preco'];
                    $parcela_c = $preco_final_c / 5;
                ?>

                <div class="product-card-clean">
                    <div class="img-box-clean">
                        <a href="produto_individual.php?id=<?= $c['id'] ?>">
                            <img src="<?= $c['imagem'] ?>" alt="<?= $c['nome'] ?>">
                        </a>
                    </div>

                    <div class="details-clean">
                        <h3 class="product-title"><?= $c['nome'] ?></h3>

                        <div class="price-area">
                            <?php if($c['preco_promocional'] > 0 && $c['preco_promocional'] < $c['preco']): ?>
                                <span class="old-price">R$ <?= number_format($c['preco'], 2, ',', '.') ?></span>
                            <?php endif; ?>

                            <strong>R$ <?= number_format($preco_final_c, 2, ',', '.') ?></strong>
                        </div>

                        <p class="installment-text">
                            Em até 5x R$ <?= number_format($parcela_c, 2, ',', '.') ?> sem juros
                        </p>
                    </div>
                </div>

            <?php endwhile; ?>
        <?php endif; ?>
    </div>

        <button class="slider-arrow arrow-right" onclick="proximaPagina('track-mais-vendidas')">
            <i class="fas fa-chevron-right"></i>
        </button>

    </div>

    <div class="carousel-indicators" id="indicators-mais-vendidas">
        <span class="indicator active" onclick="irParaPagina('track-mais-vendidas', 0)"></span>
        <span class="indicator" onclick="irParaPagina('track-mais-vendidas', 1)"></span>
    </div>

</main>

<section class="banner-retro container">
    <div class="banner-wrapper">
        <img src="img/pague 2.png" alt="Reviva o seu passado - Camisas Retrô">
        <a href="produtos.php?id=70" class="banner-link-overlay"></a>
    </div>
</section>

<section class="club-logos-section container">
    <div class="logos-grid">
        <div class="logo-item">
            <img src="img/times/Atletico_mineiro_galo.png" alt="Atlético Mineiro">
            <span>Atlético Mineiro</span>
        </div>
        <div class="logo-item">
            <img src="img/times/Botafogo_de_Futebol_e_Regatas_logo.svg.png" alt="Botafogo">
            <span>Botafogo</span>
        </div>
        <div class="logo-item">
            <img src="img/times/Corinthians_simbolo.png" alt="Corinthians">
            <span>Corinthians</span>
        </div>
        <div class="logo-item">
            <img src="img/times/Cruzeiro_Esporte_Clube_(logo).svg.png" alt="Cruzeiro">
            <span>Cruzeiro</span>
        </div>
        <div class="logo-item">
            <img src="img/times/Flamengo_braz_logo.svg.jpg" alt="Flamengo">
            <span>Flamengo</span>
        </div>
        <div class="logo-item">
            <img src="img/times/FFC_crest.svg.png" alt="Fluminense">
            <span>Fluminense</span>
        </div>
        <div class="logo-item">
            <img src="img/times/Gremio_logo.svg.png" alt="Grêmio">
            <span>Grêmio</span>
        </div>
        <div class="logo-item">
            <img src="img/times/SC_Internacional_Brazil_Logo.svg.png" alt="Internacional">
            <span>Internacional</span>
        </div>
        <div class="logo-item">
            <img src="img/times/Palmeiras_logo.svg.png" alt="Palmeiras">
            <span>Palmeiras</span>
        </div>
        <div class="logo-item">
            <img src="img/times/Brasao_do_Sao_Paulo_Futebol_Clube.svg.png" alt="São Paulo">
            <span>São Paulo</span>
        </div>
        <div class="logo-item">
            <img src="img/times/EscudoDoVascoDaGama.svg.png" alt="Vasco da Gama">
            <span>Vasco da Gama</span>
        </div>
    </div>
</section>

<section class="container products-section">
    <div class="section-header-custom">
        <h2>Conjuntos Infantis</h2>
         <a href="produtos.php?id=72" class="see-all-link">Ver todos</a>
    </div>

    <div class="product-carousel-container">

        <button class="slider-arrow arrow-left" onclick="paginaAnterior('track-infantis')">
            <i class="fas fa-chevron-left"></i>
        </button>

        <div class="product-grid track" id="track-infantis">
            <?php if ($resultado_infantis && $resultado_infantis->num_rows > 0): ?>
                <?php while($inf = $resultado_infantis->fetch_assoc()): ?>

                    <?php 
                        $preco_final_inf = ($inf['preco_promocional'] > 0) ? $inf['preco_promocional'] : $inf['preco'];
                        $parcela_inf = $preco_final_inf / 5;
                    ?>

                    <div class="product-card-clean">
                        <div class="img-box-clean">
                            <a href="produto_individual.php?id=<?= $inf['id'] ?>">
                                <img src="<?= $inf['imagem'] ?>" alt="<?= $inf['nome'] ?>">
                            </a>
                        </div>

                        <div class="details-clean">
                            <h3 class="product-title"><?= $inf['nome'] ?></h3>

                            <div class="price-area">
                                <?php if($inf['preco_promocional'] > 0 && $inf['preco_promocional'] < $inf['preco']): ?>
                                    <span class="old-price">R$ <?= number_format($inf['preco'], 2, ',', '.') ?></span>
                                <?php endif; ?>

                                <strong>R$ <?= number_format($preco_final_inf, 2, ',', '.') ?></strong>
                            </div>

                            <p class="installment-text">
                                Em até 5x R$ <?= number_format($parcela_inf, 2, ',', '.') ?> sem juros
                            </p>
                        </div>
                    </div>

                <?php endwhile; ?>
            <?php else: ?>
                <p style="padding: 20px;">Nenhum produto infantil encontrado.</p>
            <?php endif; ?>
        </div>

        <button class="slider-arrow arrow-right" onclick="proximaPagina('track-infantis')">
            <i class="fas fa-chevron-right"></i>
        </button>

    </div>

    <div class="carousel-indicators" id="indicators-infantis">
        <span class="indicator active" onclick="irParaPagina('track-infantis', 0)"></span>
        <span class="indicator" onclick="irParaPagina('track-infantis', 1)"></span>
    </div>
</section>

<div class="special-offer-container">
    
    <div class="special-info">
        <h3>Camisas Únicas e<br>Diferenciadas</h3>
        <p>Recomendadas pela nossa loja</p>
        <a href="produtos.php?id=74" class="btn-white-pill">Veja Todas</a>
    </div>

    <div class="special-grid">
        <?php if ($resultado_diferenciadas && $resultado_diferenciadas->num_rows > 0): ?>
            <?php while($dif = $resultado_diferenciadas->fetch_assoc()): ?>
        
                <div class="card-special">
                    
                    <div class="img-special">
                         <a href="produto_individual.php?id=<?= $dif['id'] ?>">
                             <img src="<?= $dif['imagem'] ?>" alt="<?= $dif['nome'] ?>">
                         </a>
                    </div>
                    
                    <div class="info-special">
                        <h4><?= $dif['nome'] ?></h4>
                        
                        <div class="price-special">
                            <?php if($dif['preco_promocional'] > 0 && $dif['preco_promocional'] < $dif['preco']): ?>
                                <span class="old">R$<?= number_format($dif['preco'], 2, ',', '.') ?></span>
                                <span class="new">R$ <?= number_format($dif['preco_promocional'], 2, ',', '.') ?></span>
                            <?php else: ?>
                                <span class="new">R$ <?= number_format($dif['preco'], 2, ',', '.') ?></span>
                            <?php endif; ?>
                        </div>

                        <?php 
                            $preco_final_dif = ($dif['preco_promocional'] > 0) ? $dif['preco_promocional'] : $dif['preco'];
                            $parcela_dif = $preco_final_dif / 5;
                        ?>
                        <p class="installment-special-text">
                            Em até 5x R$ <?= number_format($parcela_dif, 2, ',', '.') ?> sem juros
                        </p>
                    </div>
                </div>

            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</div>

<section class="container products-section">
    <div class="section-header-custom">
        <h2>Camisas Femininas</h2>
        <a href="produtos.php?id=69" class="see-all-link">Ver todos</a>
    </div>

    <div class="product-carousel-container">

        <button class="slider-arrow arrow-left" onclick="paginaAnterior('track-femininas')">
            <i class="fas fa-chevron-left"></i>
        </button>

        <div class="product-grid track" id="track-femininas">
            <?php if ($resultado_femininas && $resultado_femininas->num_rows > 0): ?>
                <?php while($fem = $resultado_femininas->fetch_assoc()): ?>

                    <?php 
                        $preco_final_fem = ($fem['preco_promocional'] > 0) ? $fem['preco_promocional'] : $fem['preco'];
                        $parcela_fem = $preco_final_fem / 5;
                    ?>

                    <div class="product-card-clean">
                        <div class="img-box-clean">
                            <a href="produto_individual.php?id=<?= $fem['id'] ?>">
                                <img src="<?= $fem['imagem'] ?>" alt="<?= $fem['nome'] ?>">
                            </a>
                        </div>

                        <div class="details-clean">
                            <h3 class="product-title"><?= $fem['nome'] ?></h3>

                            <div class="price-area">
                                <?php if($fem['preco_promocional'] > 0 && $fem['preco_promocional'] < $fem['preco']): ?>
                                    <span class="old-price">R$ <?= number_format($fem['preco'], 2, ',', '.') ?></span>
                                <?php endif; ?>

                                <strong>R$ <?= number_format($preco_final_fem, 2, ',', '.') ?></strong>
                            </div>

                            <p class="installment-text">
                                Em até 5x R$ <?= number_format($parcela_fem, 2, ',', '.') ?> sem juros
                            </p>
                        </div>
                    </div>

                <?php endwhile; ?>
            <?php else: ?>
                <p style="padding: 20px;">Nenhum produto feminino encontrado.</p>
            <?php endif; ?>
        </div>

        <button class="slider-arrow arrow-right" onclick="proximaPagina('track-femininas')">
            <i class="fas fa-chevron-right"></i>
        </button>

    </div>

    <div class="carousel-indicators" id="indicators-femininas">
        <span class="indicator active" onclick="irParaPagina('track-femininas', 0)"></span>
        <span class="indicator" onclick="irParaPagina('track-femininas', 1)"></span>
    </div>
</section>

<section class="about-section" id="sobre">
    <div class="container">
        <div class="about-wrapper">
            
            <div class="about-text">
                <h3>Sobre a ArenaStore</h3>
                <p>
                    Somos um projeto acadêmico fictício criado por alunos para demonstrar nossos conhecimentos na matéria de Programação Web. A ArenaStore não é uma loja real com CNPJ registrado, mas sim um ambiente de simulação com propósitos estritamente educativos.
                </p>
                <p>
                    Nosso objetivo é utilizar este projeto para aplicar e exibir as habilidades e técnicas aprendidas, focando em simular uma experiência de usuário completa na venda de camisas de time. Trabalhamos para demonstrar a melhor qualidade de código, design e funcionalidade, provando que somos capazes de desenvolver um site de Alto Valor do zero. A experiência de "vestir a camisa" aqui é a de aplicar o conhecimento!
                </p>
                
                <a href="index.php" class="btn-red">Veja Mais</a>
            </div>

            <div class="about-image">
                <img src="img/logo site.png" alt="Logo ArenaStore Grande">
            </div>

        </div>
    </div>
</section>
     
<?php include 'footer.php'; ?>

<script src="js/sliders.js" defer></script>

</body>
</html>