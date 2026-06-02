// ============================================================
//  SMART PARKIR - Main JavaScript
// ============================================================

document.addEventListener('DOMContentLoaded', function () {

    // ── Clock ──────────────────────────────────────────────
    const clockEl = document.getElementById('topbarClock');
    function updateClock() {
        if (!clockEl) return;
        const now = new Date();
        const opts = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
        clockEl.textContent = now.toLocaleTimeString('id-ID', opts) + ' WIB';
    }
    updateClock();
    setInterval(updateClock, 1000);

    // ── Sidebar Toggle ─────────────────────────────────────
    const sidebar = document.getElementById('sidebar');
    const mainWrapper = document.getElementById('mainWrapper');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const mobileToggle = document.getElementById('mobileToggle');

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function () {
            sidebar.classList.toggle('collapsed');
            mainWrapper.classList.toggle('expanded');
            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
        });
    }

    // Restore sidebar state
    if (localStorage.getItem('sidebarCollapsed') === 'true') {
        sidebar?.classList.add('collapsed');
        mainWrapper?.classList.add('expanded');
    }

    if (mobileToggle) {
        mobileToggle.addEventListener('click', function () {
            sidebar.classList.toggle('mobile-open');
        });
        // Close on outside click
        document.addEventListener('click', function (e) {
            if (window.innerWidth <= 768 &&
                sidebar.classList.contains('mobile-open') &&
                !sidebar.contains(e.target) &&
                e.target !== mobileToggle) {
                sidebar.classList.remove('mobile-open');
            }
        });
    }

    // ── Tabs ───────────────────────────────────────────────
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const tabId = this.dataset.tab;
            const container = this.closest('.tabs-container');
            if (!container) return;

            container.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            container.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));

            this.classList.add('active');
            const target = container.querySelector('#' + tabId);
            if (target) target.classList.add('active');
        });
    });

    // ── Modal ──────────────────────────────────────────────
    document.querySelectorAll('[data-modal]').forEach(btn => {
        btn.addEventListener('click', function () {
            const modalId = this.dataset.modal;
            const modal = document.getElementById(modalId);
            if (modal) modal.classList.add('show');
        });
    });
    document.querySelectorAll('.modal-close, .modal-overlay').forEach(el => {
        el.addEventListener('click', function (e) {
            if (e.target === this) {
                this.closest('.modal-overlay')?.classList.remove('show');
            }
        });
    });

    // ── Auto-dismiss alerts ────────────────────────────────
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => alert.remove(), 5000);
    });

    // ── Format currency inputs ─────────────────────────────
    window.formatCurrency = function (val) {
        return 'Rp ' + parseInt(val || 0).toLocaleString('id-ID');
    };

    // ── Confirm delete ─────────────────────────────────────
    document.querySelectorAll('[data-confirm]').forEach(el => {
        el.addEventListener('click', function (e) {
            if (!confirm(this.dataset.confirm || 'Yakin ingin menghapus data ini?')) {
                e.preventDefault();
            }
        });
    });

    // ── Live search table ──────────────────────────────────
    document.querySelectorAll('[data-search-table]').forEach(input => {
        input.addEventListener('input', function () {
            const q = this.value.toLowerCase();
            const tableId = this.dataset.searchTable;
            const table = document.getElementById(tableId);
            if (!table) return;
            table.querySelectorAll('tbody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        });
    });

    // ── Progress bars animate on load ─────────────────────
    document.querySelectorAll('.progress-fill').forEach(bar => {
        const target = bar.dataset.width || bar.style.width;
        bar.style.width = '0';
        requestAnimationFrame(() => {
            setTimeout(() => { bar.style.width = target; }, 100);
        });
    });
});

// ── Chart helper (uses Chart.js from CDN if available) ────
window.createBarChart = function (canvasId, labels, data, label) {
    const canvas = document.getElementById(canvasId);
    if (!canvas || typeof Chart === 'undefined') return;
    new Chart(canvas, {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label,
                data,
                backgroundColor: 'rgba(37,99,235,0.15)',
                borderColor: '#2563EB',
                borderWidth: 2,
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, ticks: { font: { family: 'Plus Jakarta Sans', size: 11 } } },
                y: {
                    grid: { color: '#F1F5F9' },
                    ticks: {
                        font: { family: 'Plus Jakarta Sans', size: 11 },
                        callback: v => 'Rp ' + (v / 1000) + 'k'
                    }
                }
            }
        }
    });
};

window.createLineChart = function (canvasId, labels, data, label) {
    const canvas = document.getElementById(canvasId);
    if (!canvas || typeof Chart === 'undefined') return;
    new Chart(canvas, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label,
                data,
                borderColor: '#2563EB',
                backgroundColor: 'rgba(37,99,235,0.08)',
                borderWidth: 2.5,
                pointRadius: 4,
                pointBackgroundColor: '#2563EB',
                fill: true,
                tension: 0.4,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, ticks: { font: { family: 'Plus Jakarta Sans', size: 11 } } },
                y: {
                    grid: { color: '#F1F5F9' },
                    ticks: {
                        font: { family: 'Plus Jakarta Sans', size: 11 },
                        callback: v => 'Rp ' + (v / 1000) + 'k'
                    }
                }
            }
        }
    });
};