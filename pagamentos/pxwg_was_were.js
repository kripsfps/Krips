
const burgerMenuBtn = document.getElementById('burgerMenuBtn');
const mobileMenu = document.getElementById('mobileMenu');

const closeBtn = document.getElementById('closeBtn');

function openMobileMenu() {
    mobileMenu.classList.remove('hidden');
    mobileMenu.classList.add('flex');
}

function closeMobileMenu() {
    mobileMenu.classList.add('hidden');
    mobileMenu.classList.remove('flex');
}

burgerMenuBtn.addEventListener('click', openMobileMenu);

closeBtn.addEventListener('click', closeMobileMenu);
