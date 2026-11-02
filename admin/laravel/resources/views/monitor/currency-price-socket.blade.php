<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Live Prices with Style</title>
    <style>
        :root {
            --bg: #f1f3f7;
            --card-bg: #fff;
            --text: #333;
            --subtext: #666;
            --positive: green;
            --negative: red;
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
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 20px;
            transition: background 0.3s, color 0.3s;
        }

        h2 {
            text-align: center;
        }

        #controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 900px;
            margin: 0 auto 20px auto;
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
        }

        #prices {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 15px;
        }

        .card {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease, background 0.3s, color 0.3s;
        }

        .symbol {
            font-size: 20px;
            font-weight: bold;
        }

        .price {
            font-size: 24px;
            margin: 10px 0;
            transition: color 0.5s;
        }

        .positive {
            color: var(--positive);
        }

        .negative {
            color: var(--negative);
        }

        .small {
            font-size: 12px;
            color: var(--subtext);
        }

        .flash {
            animation: flash 0.5s;
        }

        @keyframes flash {
            0% { background-color: yellow; }
            100% { background-color: transparent; }
        }
    </style>
</head>
<body>

<h2>📡 Binance Live Prices</h2>

<div id="controls">
    <div id="status">Connecting...</div>
    <button id="toggleDarkMode">🌙 Dark Mode</button>
</div>

<div id="prices"></div>

<audio id="alertSound" src="https://freesound.org/data/previews/341/341695_5260877-lq.mp3" preload="auto"></audio>

<script>
    const pricesContainer = document.getElementById('prices');
    const statusEl = document.getElementById('status');
    const alertSound = document.getElementById('alertSound');
    const toggleDarkModeBtn = document.getElementById('toggleDarkMode');

    let socket;
    let reconnectInterval = 5000;
    let previousPrices = {};

    const priceAlertLevels = {
        BTC: 120000,
        ETH: 4000
    };

    toggleDarkModeBtn.addEventListener('click', () => {
        document.body.classList.toggle('dark');
    });

    function formatNumber(num, decimals = 2) {
        return Number(num).toLocaleString(undefined, {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals
        });
    }

    function updatePrices(data) {
        pricesContainer.innerHTML = '';

        Object.values(data).forEach(item => {
            const symbol = item.symbol;
            const pair = item.pairs;
            const change = item.change ?? item.change_percentage;
            const changePercent = item.changePercent ?? item.change_percentage;
            const price = item.price ?? item.last;
            const isPositive = change >= 0;
            // Detect price change for animation
            const previous = previousPrices[symbol];
            const flash = previous && previous !== price ? '' : '';
            previousPrices[symbol] = price;

            const card = document.createElement('div');
            card.className = `card`;

            card.innerHTML = `
          <div class="symbol">${symbol} / ${pair.split('_')[1]}</div>
          <div class="price ${isPositive ? 'positive' : 'negative'} ${flash}">${formatNumber(price)}</div>
          <div class="small">${isPositive ? '▲' : '▼'} ${formatNumber(change, 3)} (${formatNumber(changePercent, 2)}%)</div>
          <div class="small">High: ${formatNumber(item.high ?? item.high_24h)} | Low: ${formatNumber(item.low ?? item.low_24h)}</div>
          <div class="small">Vol: ${formatNumber(item.volume)} | Q.Vol: ${formatNumber(item.quoteVolume ?? item.quote_volume)}</div>
        `;

            pricesContainer.appendChild(card);
        });
    }

    function connect() {
        const protocol = window.location.protocol === 'https:' ? 'wss' : 'ws';
        const local = '127.0.0.1:3011';
        const socketUrl = `${protocol}://${window.location.hostname}/futures/prices`;
        console.log(socketUrl);

        socket = new WebSocket(socketUrl);

        socket.onopen = () => {
            statusEl.textContent = '✅ Connected';
        };

        socket.onmessage = (event) => {
            try {
                const data = JSON.parse(event.data);
                updatePrices(data.data);
            } catch (e) {
                console.error('❌ Invalid JSON:', e);
            }
        };

        socket.onclose = () => {
            statusEl.textContent = '🔌 Disconnected. Reconnecting...';
            setTimeout(connect, reconnectInterval);
        };

        socket.onerror = (error) => {
            console.error('WebSocket error:', error);
            socket.close();
        };
    }


    connect();
</script>

</body>
</html>
