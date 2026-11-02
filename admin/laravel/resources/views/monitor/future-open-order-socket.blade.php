<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>Futures Open Orders Client</title>
    <style>
        body {
            font-family: sans-serif;
            background: #0d1117;
            color: #e6edf3;
            padding: 20px;
        }
        h2 {
            color: #58a6ff;
        }
        pre {
            background: #161b22;
            padding: 10px;
            border-radius: 6px;
            overflow-x: auto;
        }
        input, button {
            padding: 8px;
            margin: 5px;
            border-radius: 4px;
            border: 1px solid #30363d;
            background: #21262d;
            color: #e6edf3;
        }
        button {
            cursor: pointer;
        }
        button:hover {
            background: #2d333b;
        }
    </style>
</head>
<body>

<h2>📊 Futures Open Orders (WebSocket Client)</h2>

<label>User ID:</label>
<input type="number" id="userId" value="1264">
<label>Pairs (BTC_USDT):</label>
<input type="text" id="pairs" value="BTC_USDT">
<button onclick="connectWS()">Connect</button>

<h3>🔌 Connection Status:</h3>
<pre id="status">Disconnected</pre>

<h3>📦 Received Data:</h3>
<pre id="data"></pre>

<script>
    let socket = null;

    function connectWS() {
        const userId = document.getElementById('userId').value.trim();
        const pairs = document.getElementById('pairs').value.trim().toUpperCase();

        if (!userId || !pairs) {
            alert("Enter The UserId And Pairs ");
            return;
        }

        const protocol = window.location.protocol === 'https:' ? 'wss' : 'ws';
        const socketUrl = `${protocol}://${window.location.hostname}/futures/open/order/${userId}/${pairs}`;
        document.getElementById('status').textContent = "Connecting...";

        socket = new WebSocket(socketUrl);

        socket.onopen = () => {
            document.getElementById('status').textContent = "✅ Connected to " + wsUrl;
        };

        socket.onmessage = (event) => {
            try {
                const msg = JSON.parse(event.data);
                document.getElementById('data').textContent = JSON.stringify(msg, null, 2);
            } catch (err) {
                console.error("Invalid JSON:", err);
            }
        };

        socket.onerror = (err) => {
            document.getElementById('status').textContent = "❌ Error: " + err.message;
        };

        socket.onclose = () => {
            document.getElementById('status').textContent = "🔴 Disconnected";
        };
    }
</script>

</body>
</html>
