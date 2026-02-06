@if(session('show_login_loader'))
<div id="login-loader" class="fixed inset-0 z-50 flex items-center justify-center bg-white">
    <div class="text-center">
        <div class="spinner"></div>
        <p class="mt-4 text-gray-600 text-lg font-semibold">
            Cargando tu perfil...
        </p>
    </div>
</div>

<style>
.spinner {
    width: 60px;
    height: 60px;
    border: 6px solid #e5e7eb;
    border-top-color: #2563eb;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}
@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>

<script>
    window.addEventListener('load', () => {
        setTimeout(() => {
            const loader = document.getElementById('login-loader');
            if (loader) {
                loader.style.opacity = '0';
                loader.style.transition = 'opacity .4s ease';
                setTimeout(() => loader.remove(), 400);
            }
        }, 1200); // ⏱️ duración visible
    });
</script>
@endif