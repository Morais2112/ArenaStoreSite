<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php?msg=necessario_login");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Finalizar Compra | ArenaStore</title>
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/checkout.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body>

<?php include 'header.php'; ?>

<div class="container">
    <div class="checkout-container">
        
        <div class="checkout-form">
            <form id="form-pedido">
                
                <h2 class="section-title"><i class="fas fa-user"></i> Dados Pessoais</h2>
                <div class="form-group">
                    <label>CPF</label>
                    <input type="text" name="cpf" id="cpf" placeholder="000.000.000-00" maxlength="14" required oninput="mascaraCPF(this)">
                </div>

                <h2 class="section-title" style="margin-top: 25px;"><i class="fas fa-map-marker-alt"></i> Endereço de Entrega</h2>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>CEP</label>
                        <input type="text" name="cep" placeholder="00000-000" required maxlength="9">
                    </div>
                    <div class="form-group">
                        <label>Cidade</label>
                        <input type="text" name="cidade" required>
                    </div>
                    <div class="form-group">
                        <label>Estado</label>
                        <input type="text" name="estado" placeholder="UF" required maxlength="2">
                    </div>
                </div>
                <div class="form-group">
                    <label>Rua / Avenida</label>
                    <input type="text" name="rua" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Número</label>
                        <input type="text" name="numero" required>
                    </div>
                    <div class="form-group">
                        <label>Complemento</label>
                        <input type="text" name="complemento">
                    </div>
                </div>

                <h2 class="section-title" style="margin-top: 30px;"><i class="fas fa-credit-card"></i> Pagamento</h2>
                
                <div class="payment-methods">
                    <div class="payment-option selected" onclick="selectPayment('pix')">
                        <i class="fas fa-qrcode"></i> PIX
                    </div>
                    <div class="payment-option" onclick="selectPayment('cartao')">
                        <i class="far fa-credit-card"></i> Cartão
                    </div>
                    <div class="payment-option" onclick="selectPayment('boleto')">
                        <i class="fas fa-barcode"></i> Boleto
                    </div>
                </div>
                <input type="hidden" name="pagamento" id="input-pagamento" value="pix">

                <div id="cartao-info" style="display:none;">
                    <div class="form-group">
                        <label>Nome no Cartão</label>
                        <input type="text" placeholder="Como no cartão">
                    </div>
                    <div class="form-row">
                        <div class="form-group"><label>Número</label><input type="text" placeholder="0000 0000 0000 0000"></div>
                        <div class="form-group"><label>Validade</label><input type="text" placeholder="MM/AA"></div>
                        <div class="form-group"><label>CVV</label><input type="text" placeholder="123"></div>
                    </div>
                </div>

            </form>
        </div>

        <div class="checkout-summary">
            <h2 class="section-title">Resumo do Pedido</h2>
            
            <div id="lista-itens-checkout">
                </div>

            <div class="summary-item" style="margin-top: 20px; border-top: 1px solid #eee; padding-top: 10px;">
                <span>Subtotal</span>
                <span id="subtotal-val">R$ 0,00</span>
            </div>
            <div class="summary-item">
                <span>Frete</span>
                <span id="frete-val" style="color: #2e7d32;">Grátis</span>
            </div>
            <div class="summary-total">
                <span>Total</span>
                <span id="total-final" style="color: #D32F2F;">R$ 0,00</span>
            </div>

            <button class="btn-finalizar" onclick="finalizarPedido()">CONFIRMAR PEDIDO</button>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        carregarResumo();
    });

    function carregarResumo() {
        const carrinho = JSON.parse(localStorage.getItem('arenaStoreCart')) || [];
        const container = document.getElementById('lista-itens-checkout');
        
        if (carrinho.length === 0) {
            alert("Seu carrinho está vazio!");
            window.location.href = "index.php";
            return;
        }

        let html = '';
        let total = 0;

        carrinho.forEach(item => {
            let sub = item.preco * item.quantidade;
            total += sub;
            html += `
                <div class="summary-item">
                    <div class="summary-product-info">
                        <img src="${item.imagem}" alt="prod">
                        <div>
                            <div style="font-weight:600; font-size:13px;">${item.nome}</div>
                            <div style="font-size:12px; color:#777;">Tam: ${item.tamanho} | Qtd: ${item.quantidade}</div>
                        </div>
                    </div>
                    <div>R$ ${sub.toLocaleString('pt-BR', {minimumFractionDigits: 2})}</div>
                </div>
            `;
        });

        container.innerHTML = html;
        document.getElementById('subtotal-val').innerText = total.toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'});
        document.getElementById('total-final').innerText = total.toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'});
    }

    function selectPayment(tipo) {
        document.querySelectorAll('.payment-option').forEach(el => el.classList.remove('selected'));
        event.currentTarget.classList.add('selected');
        document.getElementById('input-pagamento').value = tipo;
        document.getElementById('cartao-info').style.display = (tipo === 'cartao') ? 'block' : 'none';
    }

    function mascaraCPF(i) {
        var v = i.value;
        if(isNaN(v[v.length-1])){
           i.value = v.substring(0, v.length-1);
           return;
        }
        i.setAttribute("maxlength", "14");
        if (v.length == 3 || v.length == 7) i.value += ".";
        if (v.length == 11) i.value += "-";
    }

    function finalizarPedido() {
        const btn = document.querySelector('.btn-finalizar');
        const carrinho = JSON.parse(localStorage.getItem('arenaStoreCart')) || [];
        const form = document.getElementById('form-pedido');

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        btn.innerText = "Processando...";
        btn.disabled = true;

        const dadosPedido = {
            carrinho: carrinho,
            cpf: document.getElementById('cpf').value, 
            endereco: {
                rua: document.getElementsByName('rua')[0].value,
                numero: document.getElementsByName('numero')[0].value,
                cidade: document.getElementsByName('cidade')[0].value,
                estado: document.getElementsByName('estado')[0].value,
                cep: document.getElementsByName('cep')[0].value
            },
            pagamento: document.getElementById('input-pagamento').value
        };

        fetch('processar_pedido.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(dadosPedido)
        })
        .then(response => response.json())
        .then(data => {
            if (data.sucesso) {
                localStorage.removeItem('arenaStoreCart');
                window.location.href = "sucesso.php?pedido=" + data.id_pedido;
            } else {
                alert("Erro: " + data.erro);
                btn.innerText = "CONFIRMAR PEDIDO";
                btn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert("Ocorreu um erro ao processar.");
            btn.innerText = "CONFIRMAR PEDIDO";
            btn.disabled = false;
        });
    }
</script>

</body>
</html>