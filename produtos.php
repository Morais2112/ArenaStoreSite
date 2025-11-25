<?php
include 'conexao.php';


$condicoes = [];
$condicoes[] = "i.principal = 1"; 

$nome_categoria = "Todos os Produtos"; 

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $categoria_id = (int)$_GET['id'];
    $condicoes[] = "pc.categoria_id = $categoria_id";
    
    $sql_nome = "SELECT nome FROM categorias WHERE id = $categoria_id";
    $res_nome = $conn->query($sql_nome);
    if ($res_nome && $res_nome->num_rows > 0) {
        $nome_categoria = $res_nome->fetch_assoc()['nome'];
    }
}

if (isset($_GET['busca']) && !empty($_GET['busca'])) {
    $termo_busca = $conn->real_escape_string($_GET['busca']); // Proteção básica
    $condicoes[] = "p.nome LIKE '%$termo_busca%'";
    
    $nome_categoria = "Resultados para: \"" . htmlspecialchars($_GET['busca']) . "\"";
}

if (isset($_GET['genero']) && !empty($_GET['genero'])) {
    
    $mapa_ids = [
        'masculino' => 68,
        'feminina'  => 69,
        'infantil'  => 72,
        'unissex'   => 75
    ];

    $ids_selecionados = [];
    
    foreach ($_GET['genero'] as $g) {
        $valor_limpo = strtolower(trim($g));
        if (isset($mapa_ids[$valor_limpo])) {
            $ids_selecionados[] = $mapa_ids[$valor_limpo];
        }
    }

    if (count($ids_selecionados) > 0) {
        $lista_ids = implode(',', $ids_selecionados);
        $condicoes[] = "p.id IN (
            SELECT produto_id FROM produto_categorias 
            WHERE categoria_id IN ($lista_ids)
        )";
    }
}

if (isset($_GET['preco']) && !empty($_GET['preco'])) {
    $condicoes_preco = [];
    foreach ($_GET['preco'] as $range) {
        if ($range == '100')     $condicoes_preco[] = "p.preco <= 100";
        if ($range == '100-200') $condicoes_preco[] = "p.preco BETWEEN 100 AND 200";
        if ($range == '200-300') $condicoes_preco[] = "p.preco BETWEEN 200 AND 300";
        if ($range == '300+')    $condicoes_preco[] = "p.preco > 300";
    }
    
    if (!empty($condicoes_preco)) {
        $condicoes[] = "(" . implode(' OR ', $condicoes_preco) . ")";
    }
}

$sql_where = implode(' AND ', $condicoes);

if (empty($sql_where)) {
    $sql_where = "1=1"; 
}

$sql = "SELECT p.id, p.nome, p.preco, p.preco_promocional, i.caminho AS imagem
        FROM produtos p
        INNER JOIN imagens_produtos i ON p.id = i.produto_id
        INNER JOIN produto_categorias pc ON p.id = pc.produto_id
        WHERE $sql_where
        GROUP BY p.id"; 

$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title><?= htmlspecialchars($nome_categoria) ?> | ArenaStore</title>
    
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/cards.css">
    <link rel="stylesheet" href="css/catalog.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<?php include 'header.php'; ?> 

<div class="container category-page-container">
    
    <div class="breadcrumb">
        <a href="index.php">Home</a>
        <i class="fas fa-chevron-right"></i>
        <span><?= htmlspecialchars($nome_categoria) ?></span>
    </div>

    <div class="catalog-layout">
        
        <aside class="filter-sidebar">
            <form id="form-filtros" action="produtos.php" method="GET">
                
                <?php if(isset($_GET['id'])): ?>
                    <input type="hidden" name="id" value="<?= htmlspecialchars($_GET['id']) ?>">
                <?php endif; ?>

                <?php if(isset($_GET['busca'])): ?>
                    <input type="hidden" name="busca" value="<?= htmlspecialchars($_GET['busca']) ?>">
                <?php endif; ?>

                <div class="filter-group">
                    <h3>Preço</h3>
                    <label>
                        <input type="checkbox" name="preco[]" value="100" 
                        <?= (isset($_GET['preco']) && in_array('100', $_GET['preco'])) ? 'checked' : '' ?>> 
                        Até R$ 100,00
                    </label>
                    <label>
                        <input type="checkbox" name="preco[]" value="100-200"
                        <?= (isset($_GET['preco']) && in_array('100-200', $_GET['preco'])) ? 'checked' : '' ?>> 
                        R$ 100 a R$ 200
                    </label>
                    <label>
                        <input type="checkbox" name="preco[]" value="200-300"
                        <?= (isset($_GET['preco']) && in_array('200-300', $_GET['preco'])) ? 'checked' : '' ?>> 
                        R$ 200 a R$ 300
                    </label>
                    <label>
                        <input type="checkbox" name="preco[]" value="300+"
                        <?= (isset($_GET['preco']) && in_array('300+', $_GET['preco'])) ? 'checked' : '' ?>> 
                        Acima de R$ 300
                    </label>
                </div>

                <div class="filter-group">
                    <h3>Tamanho</h3>
                    <div class="size-options">
                        <?php 
                        $tamanhos = ['P', 'M', 'G', 'GG', 'GGG'];
                        foreach($tamanhos as $t): 
                        ?>
                        <label class="size-box-label">
                            <input type="checkbox" name="tamanho[]" value="<?= $t ?>"
                            <?= (isset($_GET['tamanho']) && in_array($t, $_GET['tamanho'])) ? 'checked' : '' ?>>
                            <span><?= $t ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="filter-group">
                    <h3>Gênero</h3>
                    <label>
                        <input type="checkbox" name="genero[]" value="masculino"
                        <?= (isset($_GET['genero']) && in_array('masculino', $_GET['genero'])) ? 'checked' : '' ?>> 
                        Masculino
                    </label>
                    <label>
                        <input type="checkbox" name="genero[]" value="feminina"
                        <?= (isset($_GET['genero']) && in_array('feminina', $_GET['genero'])) ? 'checked' : '' ?>> 
                        Feminina
                    </label>
                    <label>
                        <input type="checkbox" name="genero[]" value="infantil"
                        <?= (isset($_GET['genero']) && in_array('infantil', $_GET['genero'])) ? 'checked' : '' ?>> 
                        Infantil
                    </label>
                    <label>
                        <input type="checkbox" name="genero[]" value="unissex"
                        <?= (isset($_GET['genero']) && in_array('unissex', $_GET['genero'])) ? 'checked' : '' ?>> 
                        Unissex
                    </label>
                </div>

                <button type="button" class="btn-clear-filters" onclick="limparFiltros()">
                    <i class="fas fa-trash-alt"></i> Limpar Filtros
                </button>
            </form>
        </aside>

        <main class="catalog-content">
            
            <div class="catalog-header">
                <h1><?= htmlspecialchars($nome_categoria) ?></h1>
                
                <div class="sort-box">
                    <span>Ordenar por:</span>
                    <select>
                        <option>Mais Relevantes</option>
                        <option>Menor Preço</option>
                        <option>Maior Preço</option>
                    </select>
                </div>
            </div>

            <div class="products-grid-wrap">
                
                <?php if ($resultado && $resultado->num_rows > 0): ?>
                    <?php while($p = $resultado->fetch_assoc()): ?>

                        <?php 
                            $preco_final = ($p['preco_promocional'] > 0) ? $p['preco_promocional'] : $p['preco'];
                            $parcela = $preco_final / 5;
                        ?>

                        <div class="product-card-clean catalog-card">
                            <div class="img-box-clean">
                                <a href="produto_individual.php?id=<?= $p['id'] ?>">
                                    <img src="<?= $p['imagem'] ?>" alt="<?= htmlspecialchars($p['nome']) ?>">
                                </a>
                            </div>

                            <div class="details-clean">
                                <h3 class="product-title"><?= htmlspecialchars($p['nome']) ?></h3>

                                <div class="price-area">
                                    <?php if($p['preco_promocional'] > 0 && $p['preco_promocional'] < $p['preco']): ?>
                                        <span class="old-price">R$ <?= number_format($p['preco'], 2, ',', '.') ?></span>
                                    <?php endif; ?>
                                    <strong>R$ <?= number_format($preco_final, 2, ',', '.') ?></strong>
                                </div>

                                <p class="installment-text">
                                    Em até 5x R$ <?= number_format($parcela, 2, ',', '.') ?>
                                </p>
                            </div>
                        </div>

                    <?php endwhile; ?>
                <?php else: ?>
                    <div style="padding: 40px; text-align: center; width: 100%;">
                        <h3 style="color: #666;">Nenhum produto encontrado.</h3>
                        <p>Tente buscar por outro termo ou limpe os filtros.</p>
                    </div>
                <?php endif; ?>

            </div>

            <div class="pagination">
                <a href="#" class="page-btn active">1</a>
                <a href="#" class="page-btn">2</a>
                <a href="#" class="page-btn">3</a>
                <a href="#" class="page-btn"><i class="fas fa-chevron-right"></i></a>
            </div>

        </main>
    </div>
</div>

<?php include 'footer.php'; ?>
<script>
function limparFiltros() {
    const urlParams = new URLSearchParams(window.location.search);
    let novaUrl = 'produtos.php';
    
    if (urlParams.has('id')) {
        novaUrl += '?id=' + urlParams.get('id');
    } else if (urlParams.has('busca')) {
        novaUrl += '?busca=' + urlParams.get('busca');
    }
    
    window.location.href = novaUrl;
}
</script>
</body>
</html>