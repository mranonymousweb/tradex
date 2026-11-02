/** @type {import('next').NextConfig} */
import createNextIntlPlugin from 'next-intl/plugin';

const securityHeaders = [
    { key: 'X-Frame-Options', value: 'DENY' },
    { key: 'X-Content-Type-Options', value: 'nosniff' },
    { key: 'Referrer-Policy', value: 'strict-origin-when-cross-origin' },
    {
        key: 'Permissions-Policy',
        value: 'camera=(self), microphone=(self), geolocation=()',
    },
];

const nextConfig = {
    // For local development, basePath is '/'
    // This file will be overwritten during deployment with the appropriate basePath
    // allowedDevOrigins: ['192.168.10.199'],
    experimental: {
        // proxy.ts buffers the request body (default 10MB). Video KYC uploads
        // exceed that and get truncated → "Unexpected end of form".
        proxyClientMaxBodySize: '100mb',
        serverActions: {
            bodySizeLimit: '100mb',
        },
    },
    images: {
        remotePatterns: [
            {
                protocol: 'https',
                hostname: '**',
                port: '',
            },
            {
                protocol: 'http',
                hostname: '**',
                port: '',
            },
        ],
    },
    async headers() {
        return [
            {
                source: '/(.*)',
                headers: securityHeaders,
            },
        ];
    },
};
const withNextIntl = createNextIntlPlugin();
export default withNextIntl(nextConfig);
