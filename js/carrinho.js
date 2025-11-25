let carrinho = JSON.parse(localStorage.getItem('arenaStoreCart')) || [];

document.addEventListener('DOMContentLoaded', () => {
    setupSizeSelectors();
    inicializarCarrinho();

    const openCartBtn = document.getElementById('open-cart-btn');
    const closeCartBtn = document.getElementById('close-cart-btn');
    const overlay = document.getElementById('cartOverlay');
    
    if(openCartBtn) openCartBtn.addEventListener('click', () => toggleCart());
    if(closeCartBtn) closeCartBtn.addEventListener('click', () => toggleCart());
    if(overlay) overlay.addEventListener('click', () => toggleCart());

    const btnCheckout = document.querySelector('.btn-checkout');
    if (btnCheckout) {
        btnCheckout.addEventListener('click', () => {
            if (carrinho.length === 0) {
                alert("Seu carrinho está vazio!");
                toggleCart(false);
            } else {
                window.location.href = 'checkout.php';
            }
        });
    }
});

function setupSizeSelectors() {
    const sizes = document.querySelectorAll('.size-option, .btn-size, .tamanho-item'); 
    sizes.forEach(size => {
        size.addEventListener('click', () => {
            sizes.forEach(s => s.classList.remove('selected'));
            size.classList.add('selected');
        });
    });
}

window.addCarrinho = function(idProduto) {
    const tituloEl = document.querySelector('.p-title');
    const precoEl = document.querySelector('.p-final-price');
    const imgEl = document.querySelector('#mainImg');
    const qtdInput = document.querySelector('#qtdInput');
    const tamanhoEl = document.querySelector('.size-option.selected, .size-option.active');

    if (!tamanhoEl) {
        alert("Por favor, selecione um tamanho antes de adicionar!");
        return; 
    }
    
    const tamanhoTexto = tamanhoEl.innerText.trim();

    if (!tituloEl || !precoEl) {
        alert("Erro interno ao adicionar produto.");
        return;
    }

    const nome = tituloEl.innerText;
    const precoTexto = precoEl.innerText.replace('R$', '').replace('.', '').replace(',', '.').trim();
    const preco = parseFloat(precoTexto);
    const imagem = imgEl ? imgEl.src : 'img/sem_foto.png';
    const quantidade = qtdInput ? parseInt(qtdInput.value) : 1;

    const produto = {
        id: idProduto,
        nome: nome,
        tamanho: tamanhoTexto,
        preco: preco,
        imagem: imagem,
        quantidade: quantidade
    };

    const indexExistente = carrinho.findIndex(item => item.id === idProduto && item.tamanho === tamanhoTexto);
    
    if (indexExistente > -1) {
        carrinho[indexExistente].quantidade += quantidade;
    } else {
        carrinho.push(produto);
    }

    salvarCarrinho();
    atualizarInterfaceCarrinho();
    toggleCart(true);
};

window.removerItem = function(idProduto, tamanhoItem) {
    carrinho = carrinho.filter(item => !(item.id === idProduto && item.tamanho === tamanhoItem));
    salvarCarrinho();
    atualizarInterfaceCarrinho();
};

function salvarCarrinho() {
    localStorage.setItem('arenaStoreCart', JSON.stringify(carrinho));
}

function inicializarCarrinho() {
    atualizarInterfaceCarrinho();
}

function atualizarInterfaceCarrinho() {
    const container = document.getElementById('cartItemsContainer');
    const totalEl = document.getElementById('cartTotalValue');
    const badge = document.querySelector('.cart-badge');

    if (!container) return;

    const totalItens = carrinho.reduce((acc, item) => acc + item.quantidade, 0);
    if(badge) {
        badge.innerText = totalItens;
        badge.style.display = totalItens > 0 ? 'flex' : 'none';
    }

    container.innerHTML = '';
    let valorTotal = 0;

    if (carrinho.length === 0) {
        container.innerHTML = '<p class="empty-msg">Seu carrinho está vazio.</p>';
    } else {
        carrinho.forEach(item => {
            valorTotal += item.preco * item.quantidade;
            
            const htmlItem = `
                <div class="cart-item">
                    <img src="${item.imagem}" alt="${item.nome}">
                    <div class="cart-item-details">
                        <span class="cart-item-title">${item.nome}</span>
                        <span class="cart-item-size">Tamanho: <strong>${item.tamanho}</strong></span>
                        <div class="cart-item-price">
                            ${item.quantidade}x R$ ${item.preco.toLocaleString('pt-BR', {minimumFractionDigits: 2})}
                        </div>
                        <span class="cart-item-remove" onclick="removerItem(${item.id}, '${item.tamanho}')">Remover</span>
                    </div>
                </div>
            `;
            container.innerHTML += htmlItem;
        });
    }

    if(totalEl) {
        totalEl.innerText = valorTotal.toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'});
    }
}

window.toggleCart = function(forceOpen = false) {
    const sidebar = document.getElementById('cartSidebar');
    const overlay = document.getElementById('cartOverlay');
    const floatBtns = document.querySelector('.floating-buttons'); 
    
    if (!sidebar || !overlay) return;

    const isOpening = forceOpen || !sidebar.classList.contains('open');

    if (isOpening) {
        sidebar.classList.add('open');
        overlay.classList.add('open');
        if(floatBtns) floatBtns.style.display = 'none';
    } else {
        sidebar.classList.remove('open');
        overlay.classList.remove('open');
        if(floatBtns) floatBtns.style.display = 'flex';
    }
};

window.alterarQtd = function(valor) {
    const input = document.getElementById('qtdInput');
    if (input) {
        let novaQtd = parseInt(input.value) + valor;
        if (novaQtd < 1) novaQtd = 1;
        input.value = novaQtd;
    }
};