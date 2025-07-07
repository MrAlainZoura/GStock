const wrapper = document.querySelector('.wrapper')
const registerLink = document.querySelector('.register-link')
const loginLink = document.querySelector('.login-link')

registerLink.onclick = () => {
    wrapper.classList.add('active')
}

loginLink.onclick = () => {
    wrapper.classList.remove('active')
}



const password = document.getElementById('password')
const confirmation = document.getElementById('confirm')
const createCompte = document.getElementById('createCompte')
const displayInitial = window.getComputedStyle(createCompte).display;

document.addEventListener('DOMContentLoaded', function () {
    password.addEventListener('input', verifierChamps);
    confirmation.addEventListener('input', verifierChamps);
    createCompte.style.display="none";


    function verifierChamps() {
   let message='';
    if (password.value === confirmation.value && password.value.length >= 4) {
        message = 'Les mots de passe correspondent aux critères';
        createCompte.style.display=displayInitial;
    } else{
        message =' Correspondent pas aux critères'
        createCompte.style.display="none";
    }
}
    const alerte = document.getElementById('alerte');
    const alerteLog = document.getElementById('alerteLog');
    if (alerte) {
        setTimeout(() => {
            alerte.style.display = 'none';
        }, 3000);
    }
    if (alerteLog) {
        setTimeout(() => {
            alerteLog.style.display = 'none';
        }, 3000);
    }
});