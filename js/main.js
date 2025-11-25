document.addEventListener('DOMContentLoaded', () => {

    const menuBtn = document.querySelector('.mobile-menu-btn');
    const navMenu = document.querySelector('.nav-menu'); 

    if (menuBtn) {
        menuBtn.addEventListener('click', () => {
            if(navMenu) navMenu.classList.toggle('active'); 
        });
    }
});