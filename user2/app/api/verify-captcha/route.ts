
export async function POST(req: Request) {
    if (req.method !== "POST") {
        return new Response(
            JSON.stringify({ message: "Only POST requests allowed" }),
            { status: 405 },
        );
    }

    const { gReCaptchaToken } = await req.json();
    const secretKey: string | undefined = process.env.RECAPTCHA_SECRET_KEY;

    if (!gReCaptchaToken) {
        return new Response(JSON.stringify({ message: "Token not found" }), {
            status: 405,
        });
    }

    try {
        const verifyRes = await fetch(
            "https://www.google.com/recaptcha/api/siteverify",
            {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `secret=${secretKey}&response=${gReCaptchaToken}`,
            }
        );
        const result = await verifyRes.json();
        if (result.success) {
            return new Response(JSON.stringify({ status: "success" }), {
                status: 200,
            });
        } else {
            return new Response(JSON.stringify({ message: "Failed to verify" }), {
                status: 405,
            });
        }
    } catch (error) {
        console.log(error)
        return new Response(JSON.stringify({ message: "Internal Server Error" }), {
            status: 500,
        });
    }
}