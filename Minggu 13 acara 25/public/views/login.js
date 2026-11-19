// Acara 25 - Langkah 3: login ke API, simpan token & role, lalu masuk ke dashboard
const pesanLogin = document.querySelector('#pesan-login');

// Pengguna yang sudah punya token tidak perlu login lagi
if (localStorage.getItem('token')) {
    window.location.href = 'dashboard.html';
}

document.querySelector('#form-login').addEventListener('submit', async (e) => {
    e.preventDefault();
    pesanLogin.classList.add('hidden');

    try {
        const res = await fetch(`${BASE_URL}/login`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ username: username.value, password: password.value }),
        });
        const hasil = await res.json();

        if (hasil.success) {
            localStorage.setItem('token', hasil.data.token);
            localStorage.setItem('role', hasil.data.role);
            window.location.href = 'dashboard.html';
        } else {
            pesanLogin.textContent = hasil.message;
            pesanLogin.classList.remove('hidden');
        }
    } catch (error) {
        console.error('Gagal login:', error);
        pesanLogin.textContent = 'Tidak dapat terhubung ke server. Periksa BASE_URL dan server.';
        pesanLogin.classList.remove('hidden');
    }
});
