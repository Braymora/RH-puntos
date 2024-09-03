// Agrega un evento de clic al botón de menú desplegable
const dropdownButton = document.querySelector('.dropdown-toggle');
dropdownButton.addEventListener('click', function () {
    const dropdown = this.parentElement; // El elemento .dropdown
    dropdown.classList.toggle('active'); // Agrega o quita la clase 'active'
});
