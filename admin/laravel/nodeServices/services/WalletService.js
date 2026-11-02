import { pg } from "../db.js";

class WalletService {
    /**
     * Deposit amount to user's wallet by userId
     */
    async depositByWallet(userId, currency, amount, morph, balanceColumn = 'balance', description = null) {
        return await this.changeBalance(userId, currency, amount, morph, balanceColumn, description, "deposit");
    }

    /**
     * Withdraw amount from user's wallet by userId
     */
    async withdrawByWallet(userId, currency, amount, morph, balanceColumn = 'balance', description = null) {
        return this.changeBalance(userId, currency, amount, morph, balanceColumn, description, "withdrawal");
    }

    /**
     * Internal method to handle deposit/withdrawal
     */
     async changeBalance(userId, currency, amount, morph, balanceColumn, description, type) {
        const client = await pg.connect();

        try {
            await client.query("BEGIN");

            const { rows: assets } = await client.query(
                `SELECT * FROM user_assets WHERE user_id = $1 AND symbol = $2 LIMIT 1`,
                [userId, currency.symbol]
            );

            if (!assets.length) throw new Error(`Asset not found for ${currency.symbol}`);

            const asset = assets[0];

            const sign = type === "deposit" ? 1 : 2;
            await client.query(
                `UPDATE user_assets SET ${balanceColumn} = ${balanceColumn} + $1 WHERE id = $2`,
                [sign * amount, asset.id]
            );

            const message = description || (type === "deposit"
                ? `Deposit to wallet (${asset.symbol})`
                : `Withdrawal from wallet (${asset.symbol})`);

            await client.query(
                `INSERT INTO user_transactions
                    (asset_id, user_id, currency_id, amount, type, section, transactionable_id, transactionable_type, description, status)
                 VALUES ($1,$2,$3,$4,$5,$6,$7,$8,$9,$10)`,
                [
                    asset.id,
                    userId,
                    asset.currency_id,
                    amount,
                    sign,                   // deposit / withdrawal
                    2,
                    morph.getKey(),
                    morph.constructor.name,
                    message,
                   2
                ]
            );

            await client.query("COMMIT");
        } catch (err) {
            await client.query("ROLLBACK");
            console.error(`❌ ${type}ByWallet error:`, err.message);
            throw err;
        } finally {
            client.release();
        }
    }
}

export { WalletService };
