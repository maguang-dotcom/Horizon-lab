(() => {
    const searchInputs = document.querySelectorAll('[data-dashboard-search]');

    searchInputs.forEach((input) => {
        const regions = [...document.querySelectorAll('[data-dashboard-search-region]')];

        const filterDashboard = () => {
            const query = input.value.trim().toLocaleLowerCase();

            regions.forEach((region) => {
                const rows = [...region.querySelectorAll('[data-dashboard-searchable]')];
                let visibleRows = 0;

                rows.forEach((row) => {
                    const matches = !query || row.textContent.toLocaleLowerCase().includes(query);
                    row.hidden = !matches;
                    visibleRows += matches ? 1 : 0;
                });

                const emptyState = region.querySelector('[data-dashboard-search-empty]');
                if (emptyState) {
                    emptyState.hidden = !query || rows.length === 0 || visibleRows > 0;
                }
            });
        };

        input.addEventListener('input', filterDashboard);
        filterDashboard();
    });

    const notificationRoot = document.querySelector('[data-dashboard-notifications]');
    if (!notificationRoot) return;

    const toggle = notificationRoot.querySelector('[data-notification-toggle]');
    const panel = notificationRoot.querySelector('[data-notification-panel]');
    const list = notificationRoot.querySelector('[data-notification-list]');
    if (!toggle || !panel || !list) return;

    const userId = document.body.dataset.dashboardUserId || 'unknown';
    const role = document.body.dataset.dashboardRole || 'user';
    const storageKey = `horizon-${role}-read-notifications-${userId}`;
    const countNodes = document.querySelectorAll('[data-notification-count]');
    const summary = notificationRoot.querySelector('[data-notification-summary]');
    const emptyState = notificationRoot.querySelector('[data-notification-empty]');
    const markAll = notificationRoot.querySelector('[data-notification-mark-all]');
    const sidebarBadges = document.querySelectorAll('[data-sidebar-notification]');

    let readIds;
    try {
        readIds = new Set(JSON.parse(localStorage.getItem(storageKey) || '[]'));
    } catch {
        readIds = new Set();
    }

    const notificationRows = () => [...list.querySelectorAll('[data-notification-id]')];
    const saveReadIds = () => {
        try {
            localStorage.setItem(storageKey, JSON.stringify([...readIds]));
        } catch {
            return;
        }
    };

    const updateNotifications = () => {
        notificationRows().forEach((row) => {
            if (readIds.has(row.dataset.notificationId)) row.remove();
        });

        const rows = notificationRows();
        const unreadCount = rows.length;
        countNodes.forEach((node) => {
            node.textContent = unreadCount;
            node.classList.toggle('hidden', unreadCount === 0);
        });
        toggle.setAttribute('aria-label', unreadCount ? `Notifications, ${unreadCount} unread` : 'Notifications');
        if (summary) summary.textContent = `${unreadCount} unread`;
        if (markAll) markAll.classList.toggle('hidden', unreadCount === 0);
        if (emptyState) emptyState.classList.toggle('hidden', unreadCount !== 0);

        sidebarBadges.forEach((badge) => {
            const categoryCount = rows.filter((row) => row.dataset.notificationCategory === badge.dataset.sidebarNotification).length;
            badge.textContent = categoryCount;
            badge.classList.toggle('hidden', categoryCount === 0);
        });
    };

    notificationRows().forEach((row) => {
        const link = row.querySelector('[data-notification-link]');
        const dismiss = row.querySelector('[data-notification-dismiss]');

        link?.addEventListener('click', () => {
            readIds.add(row.dataset.notificationId);
            saveReadIds();
            window.setTimeout(updateNotifications, 0);
        });

        dismiss?.addEventListener('click', () => {
            readIds.add(row.dataset.notificationId);
            saveReadIds();
            updateNotifications();
        });
    });

    markAll?.addEventListener('click', () => {
        notificationRows().forEach((row) => readIds.add(row.dataset.notificationId));
        saveReadIds();
        updateNotifications();
    });

    toggle.addEventListener('click', () => {
        panel.hidden = !panel.hidden;
        toggle.setAttribute('aria-expanded', String(!panel.hidden));
    });

    document.addEventListener('click', (event) => {
        if (!event.target.closest('[data-dashboard-notifications]')) {
            panel.hidden = true;
            toggle.setAttribute('aria-expanded', 'false');
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            panel.hidden = true;
            toggle.setAttribute('aria-expanded', 'false');
            toggle.focus();
        }
    });

    updateNotifications();
})();
