// BitycleSpotPrice.js


//this socket ib for to receive spot prices in usdt from bitycle

class BitycleSpotPrice {
    constructor(symbols = []) {
        this.symbols = symbols.map(s => `${s.toUpperCase()}_USDT`);
        this.ws = null;
        this.reconnectDelay = 5000;
        this.requestId = 1;
    }

    //url
    getStreamUrl() {
        return 'wss://streamer.bitycle.io/ws/market_data';
    }

    async connect() {
        this.ws = new WebSocket(this.getStreamUrl());

        this.ws.on('open',()=>{
            console.log('✅ Connected to bitycle Spot WebSocket');
        })
    }
}
