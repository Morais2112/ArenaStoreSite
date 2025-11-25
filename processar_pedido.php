<?php
session_start();
include 'conexao.php';

header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['sucesso' => false, 'erro' => 'Usuário não logado']);
    exit;
}

$json = file_get_contents('php://input');
$dados = json_decode($json, true);

if (!$dados || empty($dados['carrinho'])) {
    echo json_encode(['sucesso' => false, 'erro' => 'Carrinho vazio ou dados inválidos']);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$carrinho = $dados['carrinho'];
$cpf = $dados['cpf']; 
$endereco = $dados['endereco'];
$pagamento = $dados['pagamento'];

$endereco_completo = "{$endereco['rua']}, {$endereco['numero']} - {$endereco['cidade']}/{$endereco['estado']} - CEP: {$endereco['cep']}";

$total_pedido = 0;
foreach ($carrinho as $item) {
    $total_pedido += ($item['preco'] * $item['quantidade']);
}

$conn->begin_transaction();

try {
    $stmt = $conn->prepare("INSERT INTO pedidos (usuario_id, cpf, total, endereco, forma_pagamento) VALUES (?, ?, ?, ?, ?)");
    
    $stmt->bind_param("isdss", $usuario_id, $cpf, $total_pedido, $endereco_completo, $pagamento);
    
    $stmt->execute();
    $pedido_id = $conn->insert_id;
    $stmt->close();

    $stmt_item = $conn->prepare("INSERT INTO itens_pedido (pedido_id, produto_id, quantidade, preco_unitario, tamanho) VALUES (?, ?, ?, ?, ?)");
    
    foreach ($carrinho as $item) {
        $stmt_item->bind_param("iiids", $pedido_id, $item['id'], $item['quantidade'], $item['preco'], $item['tamanho']);
        $stmt_item->execute();
    }
    $stmt_item->close();

    $conn->commit();
    
    echo json_encode(['sucesso' => true, 'id_pedido' => $pedido_id]);

} catch (Exception $e) {
    $conn->rollback(); 
    echo json_encode(['sucesso' => false, 'erro' => 'Erro no banco: ' . $e->getMessage()]);
}
?>