const loginForm = document.getElementById('loginForm');
const registerForm = document.getElementById('registerForm');

function showLogin() {
  loginForm.classList.remove('hidden');
  registerForm.classList.add('hidden');
}

function showRegister() {
  registerForm.classList.remove('hidden');
  loginForm.classList.add('hidden');
}
