import { MarketConfig } from "@/types/futures";
import { useEffect, useState } from "react";

function parseTime(time?: string): [number, number, number] | null {
    if (!time) return null;

    const parts = time.replaceAll("'", "").split(":").map(Number);
    const [h, m, s = 0] = parts;

    if ([h, m, s].some((n) => Number.isNaN(n))) return null;

    return [h, m, s];
}

export default function Countdown({
    config,
}: {
    config: MarketConfig;
}) {
    const [remaining, setRemaining] = useState("00:00:00");

    const targetTimes = [
        config?.settings?.future_first_period,
        config?.settings?.future_second_period,
        config?.settings?.future_third_period,
    ];

    useEffect(() => {
        const parsedTimes = targetTimes
            .map(parseTime)
            .filter((t): t is [number, number, number] => t !== null);

        if (parsedTimes.length === 0) {
            setRemaining("00:00:00");
            return;
        }

        const calculate = () => {
            const now = new Date();
            let target: Date | null = null;

            for (const [h, m, s] of parsedTimes) {
                const date = new Date();
                date.setHours(h, m, s, 0);

                if (date > now) {
                    target = date;
                    break;
                }
            }

            if (!target) {
                const [h, m, s] = parsedTimes[0];
                target = new Date();
                target.setDate(target.getDate() + 1);
                target.setHours(h, m, s, 0);
            }

            const totalSeconds = Math.max(
                0,
                Math.floor((target.getTime() - now.getTime()) / 1000)
            );

            const hours = Math.floor(totalSeconds / 3600);
            const minutes = Math.floor((totalSeconds % 3600) / 60);
            const seconds = totalSeconds % 60;

            setRemaining(
                `${String(hours).padStart(2, "0")}:${String(minutes).padStart(
                    2,
                    "0"
                )}:${String(seconds).padStart(2, "0")}`
            );
        };

        calculate();
        const interval = setInterval(calculate, 1000);

        return () => clearInterval(interval);
    }, [
        config?.settings?.future_first_period,
        config?.settings?.future_second_period,
        config?.settings?.future_third_period,
    ]);

    return <h2>{remaining}</h2>;
}
