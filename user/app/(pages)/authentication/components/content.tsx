'use client';

import { useEffect, useState } from 'react';
import { AuthenticationSettings, StatusAuth } from '@/types/auth';
import { getIP } from '@/services/ip-address.services';
import FormVerify from './form-verify';
import { Approval } from './step3';
import VideoVerify from './video-verify';
import { DocumentVerify } from './document-verify';

export const ContentAuth = ({
    config,
}: {
    config: AuthenticationSettings;
}) => {
    const [step, setStep] = useState<number>(config?.current_step ?? 1);
    const [status, setStatus] = useState<StatusAuth>(config?.status);
    const [ip, setIp] = useState('');

    useEffect(() => {
        const fetchData = async () => {
            const ip = await getIP();
            setIp(ip ?? '127.0.0.1');
        };
        fetchData();
    }, []);

    useEffect(() => {
        setStep(config?.current_step ?? 1);
        setStatus(config?.status);
    }, [config]);


    return (
        <>
            {step === 1 && (
                <FormVerify
                    ip={ip}
                    config={config}
                    status={status}
                />
            )}
            {step === 2 && config.step_2_mode === 'manual' && (
                <VideoVerify
                    ip={ip}
                    config={config}
                    setStep={setStep}
                    status={status}
                    setStatus={setStatus}
                    description={config.description}
                />
            )}
            {step === 2 && config.step_2_mode === 'id_analyzer' && (
                <DocumentVerify
                    config={config}
                />
            )}
            {step === 3 && <Approval status={config.status} />}
        </>
    );
};
