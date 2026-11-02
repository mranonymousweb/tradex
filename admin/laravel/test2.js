import WebSocket  from "ws";
const ws = new WebSocket('ws://127.0.0.1:3001/prices')

ws.on("open",()=>{
    console.log('✅ Connected');
})


ws.on('message',(data)=>{
    const result = JSON.parse(data)
    console.log(result.data)
})
