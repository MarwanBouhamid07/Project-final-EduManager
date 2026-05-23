document.addEventListener('DOMContentLoaded', function() {
    // Add loading class to body
    document.body.classList.add('loading');
});

window.addEventListener('load', function() {
    const loader = document.getElementById('loader-wrapper');
    
    if (loader) {
        // Smooth fade out
        setTimeout(() => {
            loader.classList.add('fade-out');
            document.body.classList.remove('loading');
            
            // Remove from DOM after transition
            setTimeout(() => {
                loader.remove();
            }, 500);
        }, 300); // Small delay for visual impact
    }
});
