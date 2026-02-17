// js/script.js - Enhanced version

function searchTable(tableId = 'dataTable') {
    const input = document.getElementById('search');
    if (!input) return;

    const filter = input.value.toUpperCase();
    const table = document.getElementById(tableId);
    if (!table) return;

    const tr = table.getElementsByTagName('tr');

    for (let i = 1; i < tr.length; i++) {  // skip header row
        let visible = false;
        const td = tr[i].getElementsByTagName('td');
        for (let j = 0; j < td.length; j++) {
            const cell = td[j];
            if (cell) {
                const txtValue = cell.textContent || cell.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    visible = true;
                    break;
                }
            }
        }
        tr[i].style.display = visible ? '' : 'none';
    }
}

// Optional: live search on keyup (better UX)
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('search');
    if (searchInput) {
        searchInput.addEventListener('keyup', () => {
            searchTable();  // default table id = dataTable
        });
    }

});
document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.querySelector('.theme-toggle');
    const body = document.body;

    // Check saved preference
    if (localStorage.getItem('theme') === 'dark') {
        body.classList.add('dark-mode');
        toggle.textContent = '☀️';
    }

    toggle.addEventListener('click', () => {
        body.classList.toggle('dark-mode');
        const isDark = body.classList.contains('dark-mode');
        toggle.textContent = isDark ? '☀️' : '🌙';
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
    });
});
//// js/script.js – Hamburger menu + table search

console.log("script.js loaded at " + new Date().toLocaleTimeString());

// Hamburger menu toggle
document.addEventListener('DOMContentLoaded', function () {
    const toggle = document.querySelector('.menu-toggle');
    const menu = document.querySelector('.nav-menu');

    if (toggle && menu) {
        console.log("Hamburger elements found – attaching listeners");

        toggle.addEventListener('click', function () {
            menu.classList.toggle('active');
            const isOpen = menu.classList.contains('active');
            toggle.setAttribute('aria-expanded', isOpen);
            toggle.querySelector('.hamburger-icon').textContent = isOpen ? '✕' : '☰';
        });

        // Close on link click
        menu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                menu.classList.remove('active');
                toggle.setAttribute('aria-expanded', 'false');
                toggle.querySelector('.hamburger-icon').textContent = '☰';
            });
        });

        // Close on outside click
        document.addEventListener('click', function (e) {
            if (!menu.contains(e.target) && !toggle.contains(e.target)) {
                menu.classList.remove('active');
                toggle.setAttribute('aria-expanded', 'false');
                toggle.querySelector('.hamburger-icon').textContent = '☰';
            }
        });
    } else {
        console.warn("Hamburger menu elements (.menu-toggle or .nav-menu) NOT found on this page");
    }

    // Your existing searchTable function (if any)
    // ...
});