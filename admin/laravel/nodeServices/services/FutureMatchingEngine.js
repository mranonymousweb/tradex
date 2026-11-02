
class FutureMatchingEngine {


    calculateMargin(order) {
        return Number(order.cost).toFixed(4);
    }

    calculatePNL(order, currentPrice) {
        const parseNumeric = (value) => {
            if (typeof value === 'number') return value;
            if (typeof value === 'string') return parseFloat(value.trim());
            if (value == null) return 0;
            return parseFloat(String(value).trim()) || 0;
        };
        const priceDiff = order.side === 1 ? currentPrice - order.enter_price : order.enter_price - currentPrice;
        const  total = priceDiff * (order.quantity * order.leverage)
        const  final = total - order.fee;
        const  pnl = Number(final).toFixed(3);
        return parseNumeric(pnl);
    }

    calculateROI(order, currentPrice) {
        const margin = Number(order.cost);
        const pnl = this.calculatePNL(order, currentPrice);
        if (margin === 0) return 0;
        return Number((pnl / margin) * 100).toFixed(3);
    }

    calculateMarginRatio(order, currentPrice) {
        const margin = Number(order.cost);
        const pnl = this.calculatePNL(order, currentPrice);
        const denominator = Number(order.enter_balance || 0) + Number(pnl);
        if (denominator === 0) return 0;
        return Number((margin / denominator) * 100).toFixed(3);
    }

    calculateBreakEvenPrice(order) {
        if (order.quantity === 0) return 0;
        const total = order.cost / order.quantity;
        return order.side === 1 ? total + order.fee : total - order.fee;
    }


}
export { FutureMatchingEngine };


