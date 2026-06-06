import Alpine from 'alpinejs';
import Sortable from 'sortablejs';
import Chart from 'chart.js/auto';

window.Alpine = Alpine;
window.Sortable = Sortable;
window.Chart = Chart;

/* ════════════════════════════════════════════════════════════
   ALPINE STORES — native reactive state (replaces window.CRM_Theme etc)
   Access in Blade: $store.theme.toggle() / $store.notif.open etc
   ════════════════════════════════════════════════════════════ */
document.addEventListener('alpine:init', () => {

    /* ── Store: Theme ── */
    Alpine.store('theme', {
        mode: 'dark',

        init() {
            // 1. Saved preference  2. OS preference  3. Default dark
            const saved = localStorage.getItem('crm-theme');
            if (saved) {
                this.mode = saved;
            } else {
                this.mode = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }
            this._apply();

            // Sync when OS preference changes (only if user hasn't manually set)
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
                if (!localStorage.getItem('crm-theme')) {
                    this.mode = e.matches ? 'dark' : 'light';
                    this._apply();
                }
            });
        },

        toggle() {
            this.mode = this.mode === 'dark' ? 'light' : 'dark';
            this._apply();
            localStorage.setItem('crm-theme', this.mode);
            CRM_Toast.show(this.mode === 'dark' ? '🌑 Dark mode' : '☀️ Light mode', 'info', 2000);
        },

        _apply() {
            const html = document.documentElement;
            html.classList.remove('dark', 'light');
            html.classList.add(this.mode);
            const icon  = document.getElementById('theme-icon');
            const label = document.getElementById('theme-label');
            if (icon)  icon.textContent  = this.mode === 'dark' ? '☀️' : '🌙';
            if (label) label.textContent = this.mode === 'dark' ? 'Light' : 'Dark';
        },
    });

    /* ── Store: Focus/Presentation Mode ── */
    Alpine.store('focus', {
        active: false,

        toggle() {
            this.active = !this.active;
            const sb = document.getElementById('sidebar');
            if (sb) sb.classList.toggle('collapsed', this.active);
            CRM_Toast.show(
                this.active ? '⛶ Presentation mode ON' : '↩ Normal mode restored',
                'info', 2000
            );
        },
    });

    /* ── Store: Notifications ── */
    Alpine.store('notif', {
        open: false,
        unread: 4,
        items: [
            { icon: '🎉', title: 'Deal Won! PT Gojek',      body: 'Rp 4,8M closed by Sari Dewi',          time: '2m ago',  type: 'won',      url: '/pipeline'    },
            { icon: '⏳', title: '2 Approvals Pending',     body: 'PT Unilever 15% disc. — needs GM sign', time: '15m ago', type: 'approval', url: '/approvals'   },
            { icon: '⚠️', title: 'Deal Aging Alert',        body: 'PT BCA stuck in Proposal for 14 days',  time: '1h ago',  type: 'aging',    url: '/pipeline'    },
            { icon: '🚌', title: 'Fleet Alert',             body: 'Bus BB-0023 maintenance due tomorrow',  time: '2h ago',  type: 'fleet',    url: '/fleet'       },
            { icon: '📅', title: 'Follow-up Due Today',     body: '5 activities scheduled — Andi Pratama', time: '3h ago',  type: 'activity', url: '/activities'  },
            { icon: '💰', title: 'Invoice Overdue',         body: 'INV-240315-0012 PT Astra — 7 days',     time: '5h ago',  type: 'finance',  url: '/finance'     },
        ],

        toggle() {
            this.open = !this.open;
            const drawer = document.getElementById('notif-drawer');
            if (!drawer) { this._mount(); return; }
            drawer.classList.toggle('open', this.open);
            if (this.open) { this.unread = 0; this._updateBadge(); }
        },

        _updateBadge() {
            const badge = document.getElementById('notif-badge');
            if (!badge) return;
            badge.textContent = this.unread > 0 ? this.unread : '';
            badge.style.display = this.unread > 0 ? 'flex' : 'none';
        },

        _mount() {
            const el = document.createElement('div');
            el.id = 'notif-drawer';
            el.className = 'notif-drawer' + (this.open ? ' open' : '');
            el.innerHTML = `
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
                    <div style="font-size:15px;font-weight:700;color:var(--cc-text)">🔔 Notifications</div>
                    <button onclick="Alpine.store('notif').toggle()" style="background:none;border:none;cursor:pointer;font-size:18px;color:var(--cc-text-muted)">✕</button>
                </div>
                ${this.items.map(n => `
                    <div class="notif-item" onclick="window.location='${n.url}';Alpine.store('notif').toggle()">
                        <span class="notif-icon">${n.icon}</span>
                        <div style="flex:1;min-width:0">
                            <div class="notif-title">${n.title}</div>
                            <div class="notif-body">${n.body}</div>
                            <div class="notif-time">${n.time}</div>
                        </div>
                    </div>`).join('')}
                <div style="margin-top:12px;text-align:center">
                    <a href="/notifications" style="font-size:12px;color:var(--cc-accent);text-decoration:none">View all →</a>
                </div>`;
            document.body.appendChild(el);
            document.addEventListener('click', e => {
                if (this.open && !el.contains(e.target) && !e.target.closest('#notif-btn')) {
                    this.open = false;
                    el.classList.remove('open');
                }
            });
            setTimeout(() => { el.classList.add('open'); this.unread = 0; this._updateBadge(); }, 10);
        },
    });

});

/* ════════════════════════════════════════════════════════════
   LEGACY COMPATIBILITY — keep window.CRM_* as thin proxies
   so existing Blade templates keep working without changes
   ════════════════════════════════════════════════════════════ */
window.CRM_Theme = {
    toggle() { Alpine.store('theme').toggle(); },
    apply(mode) { Alpine.store('theme').mode = mode; Alpine.store('theme')._apply(); },
    init() { Alpine.store('theme').init(); },
};
window.CRM_Focus = {
    get active() { return Alpine.store('focus').active; },
    toggle() { Alpine.store('focus').toggle(); },
};
window.CRM_Notif = {
    get open()   { return Alpine.store('notif').open; },
    get unread() { return Alpine.store('notif').unread; },
    toggle() { Alpine.store('notif').toggle(); },
    _updateBadge() { Alpine.store('notif')._updateBadge(); },
};

/* ════════════════════════════════════════════════════════════
   3. TOAST NOTIFICATIONS
   ════════════════════════════════════════════════════════════ */
window.CRM_Toast = {
    show(msg, type = 'info', duration = 3200) {
        let el = document.getElementById('crm-toast');
        if (!el) {
            el = document.createElement('div');
            el.id = 'crm-toast';
            el.style.cssText = `
                position:fixed;bottom:28px;left:50%;transform:translateX(-50%);
                padding:11px 20px;border-radius:12px;font-size:13px;font-weight:600;
                z-index:9999;pointer-events:none;max-width:420px;text-align:center;
                backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px);
                transition:opacity 0.2s,transform 0.2s;
                opacity:0;transform:translateX(-50%) translateY(8px);
            `;
            document.body.appendChild(el);
        }
        const colors = {
            info:    'background:rgba(0,229,255,0.15);color:#00e5ff;border:1px solid rgba(0,229,255,0.3)',
            success: 'background:rgba(16,185,129,0.15);color:#10b981;border:1px solid rgba(16,185,129,0.3)',
            error:   'background:rgba(239,68,68,0.15);color:#ef4444;border:1px solid rgba(239,68,68,0.3)',
            warning: 'background:rgba(245,158,11,0.15);color:#f59e0b;border:1px solid rgba(245,158,11,0.3)',
        };
        el.setAttribute('style', el.style.cssText + ';' + (colors[type] || colors.info));
        el.textContent = msg;
        requestAnimationFrame(() => {
            el.style.opacity = '1';
            el.style.transform = 'translateX(-50%) translateY(0)';
        });
        clearTimeout(el._t);
        el._t = setTimeout(() => {
            el.style.opacity = '0';
            el.style.transform = 'translateX(-50%) translateY(8px)';
        }, duration);
    },
};

/* ════════════════════════════════════════════════════════════
   4. COMMAND PALETTE ⌘K
   ════════════════════════════════════════════════════════════ */
window.CRM_Palette = {
    open: false,
    query: '',
    selected: 0,
    results: [],

    navItems: [
        { icon: '🏠', label: 'Dashboard',         sub: 'Go to dashboard',       url: '/dashboard' },
        { icon: '🗂️', label: 'Sales Pipeline',    sub: 'Kanban board',          url: '/pipeline' },
        { icon: '🏢', label: 'Clients',            sub: 'Manage clients',        url: '/clients' },
        { icon: '📅', label: 'Activity Log',       sub: 'Sales activities',      url: '/activities' },
        { icon: '✅', label: 'Approval Queue',     sub: 'Pending approvals',     url: '/approvals' },
        { icon: '🚌', label: 'Fleet Armada',       sub: 'Vehicles & drivers',    url: '/fleet' },
        { icon: '🗺️', label: 'Dispatch',           sub: 'Bookings & routes',     url: '/bookings' },
        { icon: '📊', label: 'Analytics',          sub: 'Reports & charts',      url: '/analytics' },
        { icon: '🔄', label: 'Subscriptions',      sub: 'Recurring contracts',   url: '/subscriptions' },
        { icon: '⚙️', label: 'Settings',           sub: 'App settings',          url: '/settings' },
        { icon: '🌙', label: 'Toggle Dark/Light',  sub: 'Switch theme ⌘D',      action: 'theme' },
        { icon: '⛶',  label: 'Focus Mode',         sub: 'Presentation mode ⌘B', action: 'focus' },
        { icon: '➕', label: 'New Opportunity',    sub: 'Create deal (N)',        action: 'new-opp' },
    ],

    show() {
        this.open = true;
        this.query = '';
        this.selected = 0;
        this.results = [...this.navItems];
        this._render();
        setTimeout(() => document.getElementById('cmd-input')?.focus(), 50);
    },

    hide() {
        this.open = false;
        const el = document.getElementById('crm-cmd-palette');
        if (el) el.remove();
    },

    async search(q) {
        this.query = q;
        this.selected = 0;
        if (!q.trim()) {
            this.results = [...this.navItems];
            this._render(); return;
        }
        const ql = q.toLowerCase();
        const nav = this.navItems.filter(i =>
            i.label.toLowerCase().includes(ql) || i.sub.toLowerCase().includes(ql)
        );
        try {
            const res = await fetch(`/search/global?q=${encodeURIComponent(q)}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!res.ok) throw new Error();
            const data = await res.json();
            this.results = [...(data.results || []), ...nav];
        } catch {
            this.results = nav;
        }
        this._render();
    },

    execute(item) {
        this.hide();
        if (item.action === 'theme')   { Alpine.store('theme').toggle(); return; }
        if (item.action === 'focus')   { Alpine.store('focus').toggle(); return; }
        if (item.action === 'new-opp') { document.getElementById('fab-quick-add')?.click(); return; }
        if (item.url) window.location.href = item.url;
    },

    moveDown() { this.selected = Math.min(this.selected + 1, this.results.length - 1); this._highlight(); },
    moveUp()   { this.selected = Math.max(this.selected - 1, 0); this._highlight(); },
    confirm()  { if (this.results[this.selected]) this.execute(this.results[this.selected]); },

    _highlight() {
        document.querySelectorAll('.cmd-result-item').forEach((el, i) => {
            el.classList.toggle('selected', i === this.selected);
        });
    },

    _render() {
        const list = document.getElementById('cmd-list');
        if (!list) return;
        list.innerHTML = this.results.length
            ? this.results.map((r, i) => `
                <div class="cmd-result-item ${i === this.selected ? 'selected' : ''}"
                     onclick="CRM_Palette.execute(CRM_Palette.results[${i}])">
                    <span class="cmd-icon">${r.icon || '📄'}</span>
                    <div style="flex:1;min-width:0">
                        <span class="cmd-label">${r.label}</span>
                        <span class="cmd-sub">${r.sub || ''}</span>
                    </div>
                    ${r.type ? `<span style="font-size:9px;font-weight:700;background:var(--cc-accent-dim);color:var(--cc-accent);padding:1px 6px;border-radius:8px;text-transform:uppercase">${r.type}</span>` : ''}
                </div>`).join('')
            : `<div style="padding:24px;text-align:center;color:var(--cc-text-faint);font-size:13px">No results for "${this.query}"</div>`;
    },

    mount() {
        if (document.getElementById('crm-cmd-palette')) return;
        const el = document.createElement('div');
        el.id = 'crm-cmd-palette';
        el.className = 'cmd-palette-overlay';
        el.innerHTML = `
            <div class="cmd-palette-box">
                <div style="display:flex;align-items:center;padding:0 16px;border-bottom:1px solid var(--cc-border)">
                    <span style="font-size:18px;margin-right:8px;color:var(--cc-text-muted)">🔍</span>
                    <input id="cmd-input" class="cmd-palette-input"
                           placeholder="Search clients, deals, pages... (⌘K)"
                           autocomplete="off" spellcheck="false" />
                    <span class="kbd-hint" style="flex-shrink:0">ESC</span>
                </div>
                <div id="cmd-list" style="max-height:340px;overflow-y:auto;padding:6px;"></div>
                <div style="padding:8px 16px;border-top:1px solid var(--cc-border);display:flex;gap:12px;font-size:11px;color:var(--cc-text-faint)">
                    <span>↑↓ navigate</span><span>↵ open</span><span>ESC close</span>
                </div>
            </div>`;
        document.body.appendChild(el);
        this._render();

        const input = document.getElementById('cmd-input');
        input?.addEventListener('input', e => this.search(e.target.value));
        input?.addEventListener('keydown', e => {
            if (e.key === 'ArrowDown')  { e.preventDefault(); this.moveDown(); }
            if (e.key === 'ArrowUp')    { e.preventDefault(); this.moveUp(); }
            if (e.key === 'Enter')      { e.preventDefault(); this.confirm(); }
            if (e.key === 'Escape')     this.hide();
        });
        el.addEventListener('click', e => { if (e.target === el) this.hide(); });
    },

    toggle() {
        if (this.open) { this.hide(); } else { this.mount(); this.show(); }
    },
};

/* ════════════════════════════════════════════════════════════
   5. WIN CELEBRATION KONFETTI 🎊
   ════════════════════════════════════════════════════════════ */
window.CRM_Confetti = {
    fire() {
        const colors = ['#00e5ff','#3b82f6','#10b981','#f59e0b','#8b5cf6','#ec4899','#ffffff','#f97316'];
        const count = 120;
        const fragment = document.createDocumentFragment();
        for (let i = 0; i < count; i++) {
            const el = document.createElement('div');
            const size = Math.random() * 10 + 6;
            el.style.cssText = `
                position:fixed;
                left:${Math.random() * 100}vw;top:-20px;
                width:${size}px;height:${size * (Math.random() > 0.5 ? 0.4 : 1)}px;
                background:${colors[Math.floor(Math.random() * colors.length)]};
                border-radius:${Math.random() > 0.5 ? '50%' : '2px'};
                z-index:99999;pointer-events:none;
                animation:confetti-fall ${1.5 + Math.random() * 2}s ease-in ${Math.random() * 0.8}s forwards;
            `;
            el.addEventListener('animationend', () => el.remove(), { once: true });
            fragment.appendChild(el);
        }
        document.body.appendChild(fragment);
        CRM_Toast.show('🎊 DEAL WON! Congratulations! 🏆', 'success', 4000);
    },
};

/* ════════════════════════════════════════════════════════════
   6. GLOBAL KEYBOARD SHORTCUTS (12 total)
   ════════════════════════════════════════════════════════════ */
window.CRM_Keys = {
    init() {
        document.addEventListener('keydown', e => {
            const tag    = document.activeElement?.tagName?.toLowerCase();
            const typing = ['input','textarea','select'].includes(tag);

            // ⌘K / Ctrl+K — command palette
            if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
                e.preventDefault(); CRM_Palette.toggle(); return;
            }
            // ⌘B / Ctrl+B — focus mode
            if ((e.metaKey || e.ctrlKey) && e.key === 'b') {
                e.preventDefault(); Alpine.store('focus').toggle(); return;
            }
            // ⌘D / Ctrl+D — dark/light toggle
            if ((e.metaKey || e.ctrlKey) && e.key === 'd') {
                e.preventDefault(); Alpine.store('theme').toggle(); return;
            }
            // Escape — close everything
            if (e.key === 'Escape') {
                CRM_Palette.hide();
                const nd = document.getElementById('notif-drawer');
                if (nd) { Alpine.store('notif').open = false; nd.classList.remove('open'); }
                const shortcuts = document.getElementById('shortcuts-overlay');
                if (shortcuts) shortcuts.style.display = 'none';
                return;
            }

            if (typing || e.metaKey || e.ctrlKey || e.altKey) return;

            switch (e.key) {
                case 'n': case 'N': e.preventDefault(); document.getElementById('fab-quick-add')?.click(); break;
                case 'f': case 'F': e.preventDefault(); document.querySelector('[data-filter-toggle]')?.click(); CRM_Toast.show('🔎 Filter panel', 'info'); break;
                case 'w': case 'W': e.preventDefault(); document.querySelector('[data-mark-won]')?.click(); break;
                case 'e': case 'E': e.preventDefault(); document.querySelector('[data-inline-edit]')?.click(); CRM_Toast.show('✏️ Inline edit mode', 'info'); break;
                case 'a': case 'A': e.preventDefault(); document.querySelector('[data-add-activity]')?.click(); CRM_Toast.show('📅 Add activity', 'info'); break;
                case 'v': case 'V': e.preventDefault(); document.querySelector('[data-view-360]')?.click(); break;
                case '1': window.location.href = '/dashboard';     break;
                case '2': window.location.href = '/pipeline';      break;
                case '3': window.location.href = '/clients';       break;
                case '4': window.location.href = '/bookings';      break;
                case '5': window.location.href = '/analytics';     break;
                case '6': window.location.href = '/fleet';         break;
                case '7': window.location.href = '/approvals';     break;
                case '?': {
                    const overlay = document.getElementById('shortcuts-overlay');
                    if (overlay) overlay.style.display = overlay.style.display === 'none' ? 'flex' : 'none';
                    break;
                }
            }
        });
    },
};

/* ════════════════════════════════════════════════════════════
   7. DASHBOARD SPARKLINES
   ════════════════════════════════════════════════════════════ */
window.CRM_Sparkline = {
    render(canvasId, data, color = '#00e5ff') {
        const canvas = document.getElementById(canvasId);
        if (!canvas) return;
        // Destroy existing instance if any
        const existing = Chart.getChart(canvas);
        if (existing) existing.destroy();
        new Chart(canvas, {
            type: 'line',
            data: {
                labels: data.map((_, i) => i),
                datasets: [{
                    data,
                    borderColor: color,
                    borderWidth: 1.8,
                    fill: true,
                    backgroundColor: color + '14',
                    pointRadius: 0,
                    tension: 0.4,
                }]
            },
            options: {
                plugins: { legend: { display: false }, tooltip: { enabled: false } },
                scales: { x: { display: false }, y: { display: false } },
                animation: { duration: 500, easing: 'easeOutQuart' },
                responsive: true,
                maintainAspectRatio: false,
                resizeDelay: 100,
            }
        });
    },
};

/* ════════════════════════════════════════════════════════════
   8. DEAL HEALTH SCORE
   ════════════════════════════════════════════════════════════ */
window.CRM_Health = {
    score(daysSinceActivity, stageDurationDays) {
        const total = daysSinceActivity + stageDurationDays * 0.5;
        if (total < 7)  return { cls: 'health-green',  emoji: '💚', label: 'Healthy' };
        if (total < 14) return { cls: 'health-yellow', emoji: '💛', label: 'Warming' };
        return              { cls: 'health-red',    emoji: '❤️',  label: 'At Risk' };
    },
};

/* ════════════════════════════════════════════════════════════
   9. KANBAN BOARD DRAG-SCROLL
   ════════════════════════════════════════════════════════════ */
window.initBoardDragScroll = function() {
    const board = document.getElementById('kanban-scroll-x');
    if (!board) return;
    let isDown = false, startX, scrollLeft;
    board.addEventListener('mousedown', e => {
        if (e.target.closest('.kanban-card')) return;
        isDown = true;
        startX = e.pageX - board.offsetLeft;
        scrollLeft = board.scrollLeft;
        board.style.cursor = 'grabbing';
    });
    const stopDrag = () => { isDown = false; board.style.cursor = 'grab'; };
    document.addEventListener('mouseup', stopDrag);
    board.addEventListener('mouseleave', stopDrag);
    board.addEventListener('mousemove', e => {
        if (!isDown) return;
        e.preventDefault();
        board.scrollLeft = scrollLeft - (e.pageX - board.offsetLeft - startX) * 1.5;
    });
};

/* ════════════════════════════════════════════════════════════
   INIT — runs after Alpine stores are registered
   ════════════════════════════════════════════════════════════ */
document.addEventListener('DOMContentLoaded', () => {
    CRM_Keys.init();
    // Badge init via store proxy
    Alpine.store('notif')._updateBadge();
});

Alpine.start();
