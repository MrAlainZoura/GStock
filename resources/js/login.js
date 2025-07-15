const wrapper = document.querySelector('.wrapper')
const registerLink = document.querySelector('.register-link')
const loginLink = document.querySelector('.login-link')
const form_register = document.querySelector('.form-box.register');

registerLink.onclick = () => {
    wrapper.classList.add('active')
    if (form_register) form_register.style.position = "absolute";
}

loginLink.onclick = () => {
    wrapper.classList.remove('active')
    form_register.style.removeProperty("position");
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