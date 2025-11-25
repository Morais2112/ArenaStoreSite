<?php
include 'conexao.php';

if (isset($_GET['q'])) {
    $busca = $conn->real_escape_string($_GET['q']);
    
    $sql = "SELECT p.id, p.nome, p.preco, p.preco_promocional, i.caminho as imagem 
            FROM produtos p
            LEFT JOIN imagens_produtos i ON p.id = i.produto_id AND i.principal = 1
            WHERE p.nome LIKE '%$busca%' 
            LIMIT 5";
            
    $result = $conn->query($sql);
    
    $produtos = [];
    
    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $preco = ($row['preco_promocional'] > 0) ? $row['preco_promocional'] : $row['preco'];
            $row['preco_formatado'] = number_format($preco, 2, ',', '.');
            
            if(empty($row['imagem'])) $row['imagem'] = 'img/sem_foto.png';
            
            $produtos[] = $row;
        }
    }
    
    echo json_encode($produtos);
}
?>