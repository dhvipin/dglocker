let isLoggedIn = false;

// CTA to show auth
document.getElementById('hero-cta').addEventListener('click', showAuth);
document.getElementById('get-started-nav').addEventListener('click', showAuth);

function showAuth() {
    document.getElementById('hero').style.display = 'none';
    document.querySelectorAll('section').forEach(sec => sec.style.display = 'none');
    document.getElementById('auth-section').style.display = 'block';
}

// OTP Simulation
document.getElementById('send-otp').addEventListener('click', () => {
    const mobile = document.getElementById('mobile').value;
    if (mobile) {
        alert('OTP sent to ' + mobile + ' (Simulated: 123456)');
        document.getElementById('otp').style.display = 'block';
        document.getElementById('verify-otp').style.display = 'block';
    }
});

document.getElementById('verify-otp').addEventListener('click', () => {
    const otp = document.getElementById('otp').value;
    if (otp === '123456') {
        isLoggedIn = true;
        document.getElementById('auth-section').style.display = 'none';
        document.getElementById('main-section').style.display = 'block';
        loadFiles();
    } else {
        document.getElementById('auth-message').textContent = 'Invalid OTP';
    }
});

// Tab Switching
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        e.target.classList.add('active');
        document.getElementById(e.target.dataset.tab + '-section').classList.add('active');
    });
});

// Upload and File Management (Similar to before)
document.getElementById('file-input').addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = () => {
            document.getElementById('preview').innerHTML = file.type.startsWith('image/')
                ? `<img src="${reader.result}" alt="Preview">`
                : `<embed src="${reader.result}" width="100%" height="400px">`;
        };
        reader.readAsDataURL(file);
    }
});

// Modal for auth
document.getElementById('hero-cta').addEventListener('click', () => {
    document.getElementById('auth-modal').style.display = 'block';
});
document.getElementById('get-started-nav').addEventListener('click', () => {
    document.getElementById('auth-modal').style.display = 'block';
});
document.getElementById('close-modal').addEventListener('click', () => {
    document.getElementById('auth-modal').style.display = 'none';
});
document.getElementById('show-register').addEventListener('click', () => {
    document.getElementById('auth-section').style.display = 'none';
    document.getElementById('register-section').style.display = 'block';
});
document.getElementById('show-login').addEventListener('click', () => {
    document.getElementById('register-section').style.display = 'none';
    document.getElementById('auth-section').style.display = 'block';
});

// Tab switching (same as before)
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', (e) => {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        e.target.classList.add('active');
        document.getElementById(e.target.dataset.tab + '-section').classList.add('active');
    });
});

// Share link
document.getElementById('share-btn').addEventListener('click', () => {
    const file = document.getElementById('file-select').value;
    if (file) {
        document.getElementById('share-link').textContent = `Shareable link: ${window.location.origin}/${file}`;
    }
});




