import { Button } from '@/components/ui/button';
import { useState } from 'react';

interface Props {
    buttomSheet: boolean;
    setButtomSheet: (val: boolean) => void;
}
export const FooterMobile = () => {
    const [bottomSheet, setBottomSheet] = useState(false);
    return (
        <>
            <div className="fixed bottom-0 w-full space-x-3 z-10 border bg-background p-5 flex justify-between md:hidden">
                <Button
                    onClick={() => setBottomSheet(true)}
                    className="bg-destructive/70 text-background w-full hover:opacity-80 hover:bg-destructive/70 dark:text-white"
                >
                    Sell/Short
                </Button>
                <Button
                    onClick={() => setBottomSheet(true)}
                    className="bg-green text-background w-full hover:opacity-80 hover:bg-green"
                >
                    Buy/Long
                </Button>
            </div>
        </>
    );
};
