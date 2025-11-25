document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('search-input');
    const resultsBox = document.getElementById('search-results');
    let timeout = null;

    if (searchInput && resultsBox) {
        
        searchInput.addEventListener('input', function() {
            const termo = this.value.trim();
            
            clearTimeout(timeout);

            if (termo.length < 2) {
                resultsBox.classList.remove('active');
                resultsBox.innerHTML = '';
                return;
            }

            timeout = setTimeout(() => {
                fetch(`busca_ajax.php?q=${termo}`)
                    .then(response => response.json())
                    .then(data => {
                        resultsBox.innerHTML = '';
                        
                        if (data.length > 0) {
                            data.forEach(prod => {
                                const item = `
                                    <a href="produto_individual.php?id=${prod.id}" class="search-item">
                                        <img src="${prod.imagem}" alt="${prod.nome}">
                                        <div class="search-item-info">
                                            <h4>${prod.nome}</h4>
                                            <span>R$ ${prod.preco_formatado}</span>
                                        </div>
                                    </a>
                                `;
                                resultsBox.innerHTML += item;
                            });
                            resultsBox.classList.add('active');
                        } else {
                            resultsBox.innerHTML = '<div style="padding:10px; color:#666; font-size:13px;">Nenhum produto encontrado.</div>';
                            resultsBox.classList.add('active');
                        }
                    })
                    .catch(err => console.error('Erro na busca:', err));
            }, 300);
        });

        document.addEventListener('click', (e) => {
            if (!searchInput.contains(e.target) && !resultsBox.contains(e.target)) {
                resultsBox.classList.remove('active');
            }
        });
    }
});