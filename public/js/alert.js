function showAlert(message, type = "info", duration = 3000) {
    let container = document.getElementById('alert-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'alert-container';
        container.style.position = 'fixed';
        container.style.top = '20px';
        container.style.right = '20px';
        container.style.zIndex = '9999';
        container.style.display = 'flex';
        container.style.flexDirection = 'column';
        container.style.gap = '10px';
        document.body.appendChild(container);
    }

    const alert = document.createElement('div');
    alert.textContent = message;
    alert.style.padding = '10px 20px';
    alert.style.borderRadius = '8px';
    alert.style.color = 'white';
    alert.style.minWidth = '200px';
    alert.style.boxShadow = '0 2px 10px rgba(0,0,0,0.2)';
    alert.style.opacity = '0';
    alert.style.transition = 'opacity 0.3s';

    switch (type) {
        case 'success': alert.style.background = 'green'; break;
        case 'error': alert.style.background = 'red'; break;
        case 'warning': alert.style.background = 'orange'; break;
        default: alert.style.background = 'blue'; break;
    }

    container.appendChild(alert);

    requestAnimationFrame(() => {
        alert.style.opacity = '1';
    });

    setTimeout(() => {
        alert.style.opacity = '0';
        alert.addEventListener('transitionend', () => alert.remove());
    }, duration);
}
