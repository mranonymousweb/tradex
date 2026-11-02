<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Live Prices with Style</title>
</head>
<body>

<script>
    let socket = null;
    let pingTimeout = null;

    function connect() {
        const protocol = window.location.protocol === 'https:' ? 'wss' : 'ws';
        const socketUrl = `${protocol}://${window.location.host}/market/prices`;

        socket = new WebSocket(socketUrl);

        socket.onopen = () => {
            console.log(`✅ Connected to WebSocket : ${socketUrl}`);
            if (pingTimeout) clearTimeout(pingTimeout);
        };

        socket.onmessage = (event) => {
            try {
                const data = JSON.parse(event.data);
                console.log('📨 Received:', data.data);
                if (pingTimeout) clearTimeout(pingTimeout);
                pingTimeout = setTimeout(() => {
                    console.warn('⚠️ No ping from server, closing socket');
                    socket.close();
                }, 20000);
            } catch (e) {
                console.error('❌ Invalid JSON:', e);
            }
        };

        socket.onclose = () => {
            console.log('🔌 Disconnected. Reconnecting in 5 seconds...');
            if (pingTimeout) clearTimeout(pingTimeout);
            setTimeout(connect, 5000);
        };

        socket.onerror = (error) => {
            console.error('❌ WebSocket error:', error);
            socket.close();
        };
    }

    connect();
</script>

</body>
</html>
