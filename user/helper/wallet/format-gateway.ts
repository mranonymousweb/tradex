import { Gateways } from '@/types/wallet';

export type GatewayValue =
    | string
    | { key: string; value: string }
    | Record<string, string | null>
    | undefined;

export function formatGatewayLabel(gateway: GatewayValue): string {
    if (!gateway) return '------';
    if (typeof gateway === 'string') return gateway;

    if (typeof gateway === 'object' && !Array.isArray(gateway)) {
        if (typeof gateway.key === 'string') return gateway.key;

        if (typeof gateway.value === 'string') {
            const gatewayId = gateway.value;
            const label = (gateway as Record<string, string | null>)[gatewayId];
            return typeof label === 'string' ? label : gatewayId;
        }

        const labels = Object.entries(gateway).filter(
            ([, value]) => typeof value === 'string' && value.length > 0,
        ) as [string, string][];

        if (labels.length === 1) return labels[0][1];
    }

    return '------';
}

export function normalizeGateways(
    gateway: Gateways[] | Record<string, string> | undefined,
): Gateways[] {
    if (!gateway) return [];
    if (Array.isArray(gateway)) return gateway;

    return Object.entries(gateway).map(([key, value]) => ({
        key: value,
        value: key,
    }));
}
