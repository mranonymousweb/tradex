/* eslint-disable @typescript-eslint/no-explicit-any */

'use client';

import { useCallback, useEffect, useRef, useState } from 'react';
import { Video } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { Button } from '@/components/ui/button';

const RECORDING_DURATION = 30;
const MAX_FILE_BYTES = 5000 * 1024; // 5 MB
const RECORDING_AUDIO_GAIN = 0.12;
const PLAYBACK_VOLUME = 0.2;
const AUDIO_BITRATE = 64_000;
/** Headroom for encoder overshoot so a full recording stays under MAX_FILE_BYTES */
const BITRATE_SAFETY_FACTOR = 0.85;
const MAX_TOTAL_BITRATE = (MAX_FILE_BYTES * 8) / RECORDING_DURATION;
const VIDEO_BITRATE = Math.floor(
    (MAX_TOTAL_BITRATE - AUDIO_BITRATE) * BITRATE_SAFETY_FACTOR,
);

const MIME_CANDIDATES = [
    'video/webm;codecs=vp9,opus',
    'video/webm;codecs=vp8,opus',
    'video/webm',
    'video/mp4;codecs=h264,aac',
    'video/mp4',
];

function getSupportedMimeType(): string | undefined {
    if (typeof MediaRecorder === 'undefined') return undefined;
    return MIME_CANDIDATES.find((type) => MediaRecorder.isTypeSupported(type));
}

function getRecorderOptions(mimeType?: string): MediaRecorderOptions {
    const options: MediaRecorderOptions = {
        videoBitsPerSecond: VIDEO_BITRATE,
        audioBitsPerSecond: AUDIO_BITRATE,
    };

    if (mimeType) {
        options.mimeType = mimeType;
    }

    return options;
}

const RecorderVideo = ({ setVideo }: { setVideo: any }) => {
    const t = useTranslations('authentication');
    const [recordedChunks, setRecordedChunks] = useState<Blob[]>([]);
    const videoRef = useRef<HTMLVideoElement | null>(null);
    const previewRef = useRef<HTMLVideoElement>(null);
    const mediaRecorderRef = useRef<MediaRecorder | null>(null);
    const mimeTypeRef = useRef<string>('video/webm');
    const chunksRef = useRef<Blob[]>([]);
    const [previewUrl, setPreviewUrl] = useState<string | null>(null);
    const [time, setTime] = useState<number>(RECORDING_DURATION);
    const [status, setStatus] = useState('unable');
    const timerRef = useRef<ReturnType<typeof setInterval> | null>(null);
    const streamCleanupRef = useRef<(() => void) | null>(null);

    const stopStream = useCallback(() => {
        streamCleanupRef.current?.();
        streamCleanupRef.current = null;
        if (videoRef.current) {
            videoRef.current.srcObject = null;
        }
    }, []);

    const stopTimer = useCallback(() => {
        if (timerRef.current) {
            clearInterval(timerRef.current);
            timerRef.current = null;
        }
    }, []);

    const stopRecording = useCallback(() => {
        stopTimer();
        if (mediaRecorderRef.current?.state === 'recording') {
            // Status switches to 'stop' in onstop after the new blob is ready
            mediaRecorderRef.current.stop();
        }
    }, [stopTimer]);

    useEffect(() => {
        if (time <= 0) {
            stopRecording();
        }
    }, [time, stopRecording]);

    const saveRecording = useCallback(() => {
        if (recordedChunks.length === 0) return;
        const type = mimeTypeRef.current.split(';')[0] || 'video/webm';
        const blob = new Blob(recordedChunks, { type });
        const url = URL.createObjectURL(blob);
        setPreviewUrl((prev) => {
            if (prev) URL.revokeObjectURL(prev);
            return url;
        });
    }, [recordedChunks]);

    useEffect(() => {
        saveRecording();
    }, [recordedChunks, saveRecording]);

    const setupVideoStream = useCallback(async () => {
        stopStream();

        const rawStream = await navigator.mediaDevices.getUserMedia({
            video: {
                facingMode: 'user',
                width: { ideal: 640, max: 854 },
                height: { ideal: 480, max: 480 },
                frameRate: { ideal: 24, max: 30 },
            },
            audio: {
                echoCancellation: true,
                noiseSuppression: true,
                autoGainControl: true,
            },
        });

        const audioTracks = rawStream.getAudioTracks();
        const videoTracks = rawStream.getVideoTracks();

        let processedStream: MediaStream = rawStream;

        if (audioTracks.length > 0) {
            const audioContext = new AudioContext();
            const source = audioContext.createMediaStreamSource(
                new MediaStream(audioTracks),
            );
            const gainNode = audioContext.createGain();
            gainNode.gain.value = RECORDING_AUDIO_GAIN;
            const destination = audioContext.createMediaStreamDestination();

            source.connect(gainNode);
            gainNode.connect(destination);

            processedStream = new MediaStream([
                ...videoTracks,
                ...destination.stream.getAudioTracks(),
            ]);

            streamCleanupRef.current = () => {
                rawStream.getTracks().forEach((track) => track.stop());
                destination.stream.getTracks().forEach((track) => track.stop());
                audioContext.close().catch(() => undefined);
            };
        } else {
            streamCleanupRef.current = () => {
                rawStream.getTracks().forEach((track) => track.stop());
            };
        }

        if (videoRef.current) {
            videoRef.current.srcObject = processedStream;
        }

        return processedStream;
    }, [stopStream]);

    useEffect(() => {
        return () => {
            stopTimer();
            stopStream();
            setPreviewUrl((prev) => {
                if (prev) URL.revokeObjectURL(prev);
                return null;
            });
        };
    }, [stopStream, stopTimer]);

    useEffect(() => {
        if (previewRef.current) {
            previewRef.current.volume = PLAYBACK_VOLUME;
        }
    }, [previewUrl]);

    const handleTimer = () => {
        if (timerRef.current) return;
        timerRef.current = setInterval(() => {
            setTime((prev) => prev - 1);
        }, 1000);
    };

    const startRecording = async () => {
        stopTimer();
        setTime(RECORDING_DURATION);
        setRecordedChunks([]);
        chunksRef.current = [];
        // Clear parent upload state so submit cannot send a previous take
        setVideo(null);
        setPreviewUrl((prev) => {
            if (prev) URL.revokeObjectURL(prev);
            return null;
        });
        setStatus('startRecording');

        try {
            const stream = await setupVideoStream();
            const mimeType = getSupportedMimeType();
            mimeTypeRef.current = mimeType ?? 'video/webm';

            let mediaRecorder: MediaRecorder;
            try {
                mediaRecorder = mimeType
                    ? new MediaRecorder(stream, getRecorderOptions(mimeType))
                    : new MediaRecorder(stream, getRecorderOptions());
            } catch {
                try {
                    mediaRecorder = new MediaRecorder(stream, getRecorderOptions());
                } catch {
                    mediaRecorder = new MediaRecorder(stream);
                }
            }

            mimeTypeRef.current = mediaRecorder.mimeType || mimeTypeRef.current;
            mediaRecorderRef.current = mediaRecorder;

            mediaRecorder.ondataavailable = (event) => {
                if (event.data.size > 0) {
                    chunksRef.current.push(event.data);
                }
            };

            mediaRecorder.onstop = () => {
                stopStream();
                const type = mimeTypeRef.current.split(';')[0] || 'video/webm';
                const ext = type.includes('mp4') ? 'mp4' : 'webm';
                // Fresh File each take so upload never reuses a previous blob/name
                const file = new File(
                    chunksRef.current,
                    `agreement-${Date.now()}.${ext}`,
                    { type },
                );
                setRecordedChunks([file]);
                setStatus('stop');
            };

            // timeslice helps some mobile browsers emit chunks reliably
            mediaRecorder.start(1000);
            handleTimer();
            setStatus('stopRecording');
        } catch (error) {
            console.error('Error accessing camera:', error);
            stopStream();
            setStatus('unable');
        }
    };

    const handleConfirm = () => {
        if (recordedChunks.length === 0) return;
        setVideo(recordedChunks);
        setStatus('confirm');
    };

    return (
        <div className="flex flex-col">
            {status === 'unable' && (
                <div className="border border-mono border-dashed w-full h-44 bg-secondary/50 flex flex-col items-center justify-center">
                    <Video size={40} color="#bdbfc1" />
                </div>
            )}

            <video
                ref={videoRef}
                autoPlay
                playsInline
                muted
                className={`h-44 ${(status === 'unable' || status === 'stop' || status === 'confirm') && 'hidden'}`}
            />

            {status !== 'unable' && status !== 'stop' && (
                <span
                    className={`flex justify-center mt-2 text-destructive ${status === 'confirm' && '!hidden'}`}
                >
                    {time}s
                </span>
            )}

            {previewUrl && (
                <div>
                    <h2 className="text-sm mb-2 text-muted-foreground">{t('preview')}</h2>
                    <video
                        ref={previewRef}
                        src={previewUrl}
                        controls
                        playsInline
                        onLoadedMetadata={(event) => {
                            event.currentTarget.volume = PLAYBACK_VOLUME;
                        }}
                        style={{ maxWidth: '100%' }}
                    />
                </div>
            )}

            {(status === 'unable' || status === 'confirm') && (
                <Button
                    variant="primary"
                    size="sm"
                    className="w-full mt-3"
                    onClick={startRecording}
                >
                    {t('active_camera')}
                </Button>
            )}
            {status === 'startRecording' && (
                <Button
                    variant="success"
                    size="sm"
                    className="w-full mt-3"
                    onClick={startRecording}
                >
                    {t('start_record')}
                </Button>
            )}
            {status === 'stopRecording' && (
                <Button
                    variant="destructive"
                    className="w-full mt-3"
                    size="sm"
                    onClick={stopRecording}
                >
                    {t('end_record')}
                </Button>
            )}
            {status === 'stop' && (
                <>
                    <Button
                        variant="success"
                        className="w-full mt-3"
                        onClick={startRecording}
                        size="sm"
                    >
                        {t('new_video')}
                    </Button>
                    <Button
                        variant="primary"
                        className="w-full mt-3"
                        onClick={handleConfirm}
                        size="sm"
                        disabled={recordedChunks.length === 0}
                    >
                        {t('register_confirm')}
                    </Button>
                </>
            )}
        </div>
    );
};

export default RecorderVideo;
