<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$nome_usuario = isset($_SESSION['usuario_nome']) ? $_SESSION['usuario_nome'] : null;
$qtd_carrinho = isset($_SESSION['carrinho_qtd']) ? $_SESSION['carrinho_qtd'] : 0;

$pagina_atual = basename($_SERVER['PHP_SELF']);
$id_atual = isset($_GET['id']) ? $_GET['id'] : '';

function active($page, $id_req = null) {
    global $pagina_atual, $id_atual;
    if ($pagina_atual == $page) {
        if ($id_req !== null) {
            return ($id_atual == $id_req) ? 'active' : '';
        }
        return 'active';
    }
    return '';
}

function getPrimeiroNome($nome) {
    $partes = explode(' ', trim($nome));
    return $partes[0];
}
?>

<header>
    <div class="top-bar">
        <div class="container top-bar-content">
            
            <div class="social-icons">
                <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#"><i class="fa-brands fa-instagram"></i></a>
                <a href="#"><i class="fa-brands fa-whatsapp"></i></a>
            </div>

            <div class="user-actions">
                
                <div class="action-item">
                    <i class="fa-regular fa-circle-user icon-large"></i> 
                    <div class="text-group">
                        <?php if($nome_usuario): ?>
                            <span class="user-greeting">Olá, <?php echo htmlspecialchars(getPrimeiroNome($nome_usuario)); ?></span>
                            <a href="logout.php" class="user-logout">(Sair)</a>
                        <?php else: ?>
                            <a href="login.php" class="text-small">Entrar / Cadastrar</a>
                            <a href="login.php" class="text-large">Minha conta</a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="action-item">
                    <i class="fa-solid fa-location-dot icon-large"></i>
                    <div class="text-group">
                        <span class="text-small">Onde está meu produto?</span>
                        <a href="#" class="text-large">Rastrear pedido</a>
                    </div>
                </div>

                <a href="javascript:void(0)" id="open-cart-btn" class="cart-btn">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span class="badge cart-badge" style="display: none;">0</span>
                </a>

            </div>
        </div>
    </div>

    <div class="main-header">
        <div class="container main-header-content">
            <button class="mobile-menu-btn"><i class="fa-solid fa-bars"></i></button>

            <div class="logo">
                <a href="index.php"><img src="img/logo_vermelha.png" alt="Arena Store"></a>
            </div>

            <nav class="nav-menu">
                <ul>
                    <li><a href="index.php" class="<?php echo active('index.php'); ?>">Home</a></li>
                    <li><a href="produtos.php?id=68" class="<?php echo active('produtos.php', '68'); ?>">Masculinas</a></li>
                    <li><a href="produtos.php?id=69" class="<?php echo active('produtos.php', '69'); ?>">Femininas</a></li>
                    <li><a href="produtos.php?id=72" class="<?php echo active('produtos.php', '72'); ?>">Infantis</a></li>
                    
                    <li class="dropdown">
                        <a href="#">Campeonatos <i class="fa-solid fa-chevron-down"></i></a>
                        <div class="dropdown-content mega-menu">
                            <div class="mega-column">
                                <h3 class="menu-title">Campeonato Brasileiro</h3>
                                <div class="sub-columns-wrapper">
                                    <div class="sub-col">
                                        <h4>Minas Gerais</h4>
                                        <a href="produtos.php?id=4">Atlético Mineiro</a>
                                        <a href="produtos.php?id=5">Cruzeiro</a>
                                        <h4>Rio de Janeiro</h4>
                                        <a href="produtos.php?id=7">Botafogo</a>
                                        <a href="produtos.php?id=8">Flamengo</a>
                                        <a href="produtos.php?id=9">Fluminense</a>
                                        <a href="produtos.php?id=10">Vasco da Gama</a>
                                    </div>
                                    <div class="sub-col">
                                        <h4>São Paulo</h4>
                                        <a href="produtos.php?id=12">Corinthians</a>
                                        <a href="produtos.php?id=13">Palmeiras</a>
                                        <a href="produtos.php?id=14">São Paulo FC</a>
                                        <a href="produtos.php?id=15">Santos</a>
                                        <h4>Nordeste</h4>
                                        <a href="produtos.php?id=17">Bahia</a>
                                        <a href="produtos.php?id=18">Ceará</a>
                                        <a href="produtos.php?id=19">E.C. Vitória</a>
                                        <a href="produtos.php?id=20">Fortaleza</a>
                                        <a href="produtos.php?id=21">Sport Recife</a>
                                        <h4>Sul</h4>
                                        <a href="produtos.php?id=23">Grêmio</a>
                                        <a href="produtos.php?id=24">Internacional</a>
                                    </div>
                                </div>
                            </div>
                            <div class="mega-column">
                                <h3 class="menu-title">Internacionais</h3>
                                <div class="sub-columns-wrapper">
                                    <div class="sub-col">
                                        <h4>Bundesliga</h4>
                                        <a href="produtos.php?id=27">Bayern de Munique</a>
                                        <a href="produtos.php?id=28">Borussia Dortmund</a>
                                        <h4>La Liga</h4>
                                        <a href="produtos.php?id=30">Atlético de Madrid</a>
                                        <a href="produtos.php?id=31">Barcelona</a>
                                        <a href="produtos.php?id=32">Real Madrid</a>
                                        <h4>Série A</h4>
                                        <a href="produtos.php?id=34">Inter</a>
                                        <a href="produtos.php?id=35">Juventus</a>
                                        <a href="produtos.php?id=36">Milan</a>
                                        <a href="produtos.php?id=37">Napoli</a>
                                    </div>
                                    <div class="sub-col">
                                        <h4>Ligue 1</h4>
                                        <a href="produtos.php?id=39">Olympique de Marseille</a>
                                        <a href="produtos.php?id=40">Paris Saint-Germain</a>
                                        <h4>Premier League</h4>
                                        <a href="produtos.php?id=42">Arsenal</a>
                                        <a href="produtos.php?id=43">Chelsea</a>
                                        <a href="produtos.php?id=44">Manchester City</a>
                                        <a href="produtos.php?id=45">Manchester United</a>
                                        <a href="produtos.php?id=46">Liverpool</a>
                                        <a href="produtos.php?id=47">Tottenham</a>
                                        <h4>Outras Ligas</h4>
                                        <a href="produtos.php?id=48">Outras</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="dropdown">
                        <a href="#">Seleções <i class="fa-solid fa-chevron-down"></i></a>
                        <div class="dropdown-content simple-menu">
                            <h4>África</h4>
                            <a href="produtos.php?id=51">Egito</a>
                            <a href="produtos.php?id=52">Marrocos</a>
                            <a href="produtos.php?id=53">Nigéria</a>
                            <h4>América</h4>
                            <a href="produtos.php?id=55">Argentina</a>
                            <a href="produtos.php?id=56">Brasil</a>
                            <a href="produtos.php?id=57">Uruguai</a>
                            <h4>Ásia</h4>
                            <a href="produtos.php?id=59">Coreia</a>
                            <a href="produtos.php?id=60">Japão</a>
                            <h4>Europa</h4>
                            <a href="produtos.php?id=62">Alemanha</a>
                            <a href="produtos.php?id=63">Espanha</a>
                            <a href="produtos.php?id=64">França</a>
                            <a href="produtos.php?id=65">Holanda</a>
                            <a href="produtos.php?id=66">Inglaterra</a>
                            <a href="produtos.php?id=67">Itália</a>
                        </div>
                    </li>

                    <li><a href="produtos.php?id=70" class="<?php echo active('produtos.php', '70'); ?>">Retrô</a></li>
                    <li><a href="produtos.php?id=71" class="<?php echo active('produtos.php', '71'); ?>">Agasalhos</a></li>
                    <li><a href="produtos.php?id=1" class="<?php echo active('produtos.php', '1'); ?>">Todos</a></li>
                    <li><a href="contato.php" class="<?php echo active('contato.php'); ?>">Contato</a></li>
                </ul>
            </nav>

            <div class="search-box">
                <form action="busca.php" method="GET">
                    <input type="text" name="q" id="search-input" placeholder="O que você procura..." autocomplete="off">
                    <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                </form>
                <div id="search-results" class="results-container"></div>
            </div>
        </div>
    </div>
</header>

<div class="cart-overlay" id="cartOverlay"></div>
<div class="cart-sidebar" id="cartSidebar">
    <div class="cart-header">
        <h2>Seu Carrinho</h2>
        <button id="close-cart-btn">&times;</button>
    </div>
    <div class="cart-items-container" id="cartItemsContainer"></div>
    <div class="cart-footer">
        <div class="cart-total-row">
            <span>Total:</span>
            <span id="cartTotalValue">R$ 0,00</span>
        </div>
        <button class="btn-checkout">FINALIZAR COMPRA</button>
    </div>
</div>

<script src="js/menu.js"></script>
