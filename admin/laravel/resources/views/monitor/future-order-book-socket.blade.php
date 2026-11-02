<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Futures OrderBook</title>
    <style>
        :root {
            --bg: #f0f2f5;
            --card-bg: #fff;
            --text: #333;
            --subtext: #666;
            --positive: #28a745;
            --negative: #dc3545;
        }
        body.dark {
            --bg: #121212;
            --card-bg: #1e1e1e;
            --text: #f9f9f9;
            --subtext: #aaa;
        }
        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0; padding: 20px;
            transition: background 0.3s, color 0.3s;
        }
        h1 {
            text-align: center;
            margin-bottom: 10px;
        }
        #controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1000px;
            margin: 0 auto 20px;
        }
        #status {
            font-weight: bold;
        }
        #toggleDarkMode {
            padding: 6px 12px;
            border: none;
            border-radius: 5px;
            background: #888;
            color: white;
            cursor: pointer;
            transition: background 0.3s;
        }
        #toggleDarkMode:hover {
            background: #555;
        }
        .orders-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            max-width: 1000px;
            margin: 0 auto;
        }
        table {
            width: 100%;
            background: var(--card-bg);
            border-collapse: collapse;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            font-size: 0.9rem;
        }
        caption {
            background: #0366d6;
            color: white;
            font-size: 1.2em;
            font-weight: bold;
            padding: 10px;
            text-align: left;
        }
        th, td {
            padding: 10px 15px;
            border-bottom: 1px solid #ccc;
            text-align: left;
            word-break: break-word;
        }
        th {
            background: #007bff;
            color: white;
        }
        tbody tr:hover {
            background: #f1f8ff;
            transition: background 0.2s;
        }
        .last-order {
            text-align: center;
            margin-top: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h1>📊 Futures OrderBook: <span id="pairName">BTC_USDT</span></h1>

<div id="controls">
    <div id="status" aria-live="polite" role="status">🔌 Connecting...</div>
    <button id="toggleDarkMode" aria-pressed="false" aria-label="Toggle Dark Mode">🌙 Toggle Dark Mode</button>
</div>

<div class="orders-grid" aria-live="polite">
    <table id="openOrdersTable" aria-label="Open Orders">
        <caption>🟢 Open Orders</caption>
        <thead>
        <tr>
            <th>ID</th>
            <th>Price</th>
            <th>Side</th>
            <th>Created</th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>

    <table id="closeOrdersTable" aria-label="Closed Orders">
        <caption>🔴 Closed Orders</caption>
        <thead>
        <tr>
            <th>ID</th>
            <th>Price</th>
            <th>Side</th>
            <th>Created</th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<div class="last-order" id="lastOrder" aria-live="polite">Last Order: None</div>

<script>
    (() => {
        const pair = 'BTC_USDT';
        const protocol = window.location.protocol === 'https:' ? 'wss' : 'ws';
        const socketUrl = `${protocol}://${window.location.hostname}/futures/order/book/${pair}`;

        const pairNameEl = document.getElementById('pairName');
        const openOrdersTableBody = document.querySelector('#openOrdersTable tbody');
        const closeOrdersTableBody = document.querySelector('#closeOrdersTable tbody');
        const lastOrderDiv = document.getElementById('lastOrder');
        const statusEl = document.getElementById('status');
        const toggleDarkModeBtn = document.getElementById('toggleDarkMode');

        pairNameEl.textContent = pair;

        // Toggle dark mode with state and aria attributes
        toggleDarkModeBtn.addEventListener('click', () => {
            const isDark = document.body.classList.toggle('dark');
            toggleDarkModeBtn.setAttribute('aria-pressed', isDark);
        });

        // WebSocket connection
        const ws = new WebSocket(socketUrl);

        ws.addEventListener('open', () => {
            statusEl.textContent = '✅ Connected';
        });

        ws.addEventListener('error', (err) => {
            statusEl.textContent = '❌ Error';
            console.error('WebSocket error:', err);
        });

        ws.addEventListener('close', () => {
            statusEl.textContent = '🔌 Disconnected';
        });

        ws.addEventListener('message', ({data}) => {
            try {
                const parsed = JSON.parse(data);
                if (parsed.type === 'all_orders' && parsed.pairs === pair) {
                    updateOrdersTable(openOrdersTableBody, parsed.openOrder);
                    updateOrdersTable(closeOrdersTableBody, parsed.closeOrder);
                    updateLastOrder(parsed.lastOrder);
                }
            } catch (err) {
                console.error('Failed to parse message:', err);
            }
        });

        // Helper functions
        function updateOrdersTable(tbody, orders) {
            tbody.innerHTML = '';
            if (!orders || orders.length === 0) {
                const tr = document.createElement('tr');
                const td = document.createElement('td');
                td.colSpan = 5;
                td.style.textAlign = 'center';
                td.textContent = 'No orders found';
                tr.appendChild(td);
                tbody.appendChild(tr);
                return;
            }

            // Use DocumentFragment for better performance when appending many rows
            const fragment = document.createDocumentFragment();

            orders.forEach(({id, status, enter_price, price, side, created_at}) => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                <td>${escapeHtml(id)}</td>
                <td>${escapeHtml(enter_price ?? price ?? '-')}</td>
                <td>${escapeHtml(sideText(side))}</td>
                <td>${escapeHtml(formatDate(created_at))}</td>
            `;
                fragment.appendChild(tr);
            });

            tbody.appendChild(fragment);
        }

        function updateLastOrder(order) {
            if (!order) {
                lastOrderDiv.textContent = 'Last Order: None';
                return;
            }
            lastOrderDiv.textContent = `Last Order → Price: ${order.price}, Side: ${sideText(order.side)}`;
        }


        function sideText(side) {
            if (typeof side === 'string') return side;
            switch (side) {
                case 1: return 'LONG';
                case 2: return 'SHORT';
                default: return `Unknown (${side})`;
            }
        }

        function formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            if (isNaN(date)) return '-';
            return date.toLocaleString();
        }

        // Basic HTML escaping to prevent injection
        function escapeHtml(text) {
            if (typeof text !== 'string') return text;
            return text
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }
    })();
</script>

</body>
</html>
