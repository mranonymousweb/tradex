import {BotService} from "../services/BotService.js";

class BotProvider {
    constructor() {
        this.botService = new BotService();
        this.bots = []; // will hold bots after DB load
    }

    async init() {
        try {
            this.bots = await this.botService.getActiveBot();
            console.log(`✅ Loaded ${this.bots.length} bots from database`);
        } catch (err) {
            console.error('❌ Error loading bots from DB:', err.message);
        }
    }

    async run() {
        for (const bot of this.bots) {
            try {

               this.botService.deleteOldOrder();
                const order = await  this.botService.generateOrder(bot, 2, false);
                this.botService.createOrder(order);
                console.log(order)
            } catch (err) {
                console.error(`❌ Failed for bot ${bot.id || '[no id]'}:`, err.message);
            }
        }
    }


}

// Startup sequence
const botProvider = new BotProvider();

botProvider.init().then(() => {
    setInterval(() => {
        botProvider.run().catch(err =>
            console.error("❌ Scheduled task error:", err.message)
        );
    }, 1000);
});

export default BotProvider;
