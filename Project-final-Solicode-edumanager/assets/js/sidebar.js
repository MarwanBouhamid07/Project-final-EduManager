document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('.sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    const wrapper = document.querySelector('.wrapper');

    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    if (toggleBtn && sidebar && wrapper) {
        toggleBtn.addEventListener('click', function(e) {
            sidebar.classList.toggle('collapsed');
            wrapper.classList.toggle('sidebar-collapsed');
            console.log("good");
            
        });
    }


    // Mobile menu toggle
    if (mobileMenuBtn && sidebar && sidebarOverlay) {
        mobileMenuBtn.addEventListener('click', function() {
            sidebar.classList.add('mobile-active');
            sidebarOverlay.classList.add('active');
            document.body.style.overflow = 'hidden'; // Prevent scrolling
        });

        sidebarOverlay.addEventListener('click', function() {
            closeMobileSidebar();
        });
    }

    function closeMobileSidebar() {
        sidebar.classList.remove('mobile-active');
        sidebarOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    // Close mobile sidebar on link click
    const navLinks = sidebar.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth < 768) {
                closeMobileSidebar();
            }
        });
    });

    function handleResize() {
        if (window.innerWidth <= 991) {
            sidebar.classList.add('collapsed');
            wrapper.classList.add('sidebar-collapsed');
        } else {
            sidebar.classList.remove('collapsed');
            wrapper.classList.remove('sidebar-collapsed');
            closeMobileSidebar();
        }
    }

    window.addEventListener('resize', handleResize);
    handleResize(); // Initial check
});
