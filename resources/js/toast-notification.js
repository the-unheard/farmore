document.addEventListener('DOMContentLoaded', function() {
    const toasts = document.querySelectorAll('#toast-warning, #toast-error, #toast-success');

    toasts.forEach(toast => {
        setTimeout(() => {
            toast.classList.add('opacity-0', 'transition-opacity', 'duration-500');
            setTimeout(() => toast.remove(), 500); // Remove after fade out
        }, 3000); // Hide after 3 seconds
    });
});
