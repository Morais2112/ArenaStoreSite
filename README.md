# ⚽ Arena Store - E-commerce de Artigos Esportivos

O **Arena Store** é um sistema completo de comércio eletrônico focado na venda de camisas de futebol nacionais e internacionais. O projeto foi desenvolvido para simular uma experiência real de compra, desde a navegação por categorias até o processo de checkout.

## 🚀 Funcionalidades

* **Catálogo Dinâmico**: Visualização de produtos divididos por categorias (Nacionais, Internacionais, Femininas e Infantis).
* **Busca em Tempo Real**: Sistema de busca inteligente utilizando PHP e AJAX para filtragem de produtos sem refresh da página.
* **Carrinho de Compras**: Gestão de itens selecionados, permitindo adicionar, remover e atualizar quantidades antes da finalização.
* **Área do Cliente**: Sistema de login, cadastro e gerenciamento de conta ("Minha Conta").
* **Fluxo de Checkout**: Processamento de pedidos com integração de dados entre o frontend e o banco de dados via PHP.
* **Design Responsivo**: Interface adaptável para diferentes dispositivos com foco em uma experiência visual limpa.

## 🛠️ Tecnologias Utilizadas

* **Backend**: PHP (Lógica de servidor e gestão de sessões).
* **Frontend**: HTML5, CSS3 (Modularizado com arquivos específicos para cada seção) e JavaScript.
* **Banco de Dados**: MySQL/PostgreSQL para armazenamento de produtos, usuários e pedidos.
* **Comunicação**: AJAX para requisições assíncronas no sistema de busca.

## 📂 Estrutura do Projeto

* `index.php`: Página inicial com destaques e promoções.
* `produtos.php`: Listagem geral com filtros por categoria.
* `produto_individual.php`: Detalhamento técnico e visual de cada item.
* `js/busca.js` e `busca_ajax.php`: Implementação da busca dinâmica.
* `checkout.php` e `processar_pedido.php`: Lógica final do funil de vendas.
* `conexao.php`: Configuração centralizada de acesso ao banco de dados.

## 🧠 Destaques Técnicos

Este projeto demonstra a aplicação de conceitos importantes de desenvolvimento Web:
* **Persistência de Dados**: Uso de sessões PHP para manter o estado do carrinho de compras durante a navegação.
* **Modularização de Componentes**: Fragmentação do código em componentes reutilizáveis (`header`, `footer`) para facilitar a manutenção.
* **Lógica de Negócio**: Cálculo de totais, validação de estoque e fluxo de autenticação de usuários.

## 👨‍💻 Como rodar
1. Clone este repositório.
2. Configure um servidor local (XAMPP, WAMP ou Docker).
3. Importe o banco de dados (se disponível) e ajuste as credenciais no arquivo `conexao.php`.
4. Acesse o projeto via `localhost`.

---
*Arena Store - Vista a camisa do seu time com estilo.*
