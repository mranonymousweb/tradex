'use client';

export const getIP = async () => {
    try {
        const res = await fetch(
            `${process.env.NEXT_PUBLIC_API_BACKEND_URL}/api/v1/my/ip`,
        );

        if (!res.ok) {
            return '127.0.0.1';
        }

        const data = await res.json();
        return data?.data?.ip ?? '127.0.0.1';
    } catch (error) {
        console.log(error);
        return '127.0.0.1';
    }
};
