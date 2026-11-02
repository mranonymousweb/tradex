import { useEffect, useRef, useState } from 'react';
import { CircleX, FileText } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { SendMessageAction } from './send-message-action';
import { UploadFile } from './upload-file';

interface Props {
  ticketId: string;
}

const SendMessageBox: React.FC<Props> = ({ ticketId }) => {
  const t = useTranslations('ticket');
  const [message, setMessage] = useState<string>('');
  const [files, setFiles] = useState<File[]>([]);
  const textareaRef = useRef<HTMLTextAreaElement | null>(null);
  const containerRef = useRef<HTMLDivElement | null>(null);

  const handleRemoveFile = (id: number) => {
    const file = files.filter((_, i) => i !== id);
    setFiles(file);
  };

  useEffect(() => {
    if (textareaRef.current) {
      const textarea = textareaRef.current;
      textarea.style.height = 'auto';
      textarea.style.height = textarea.scrollHeight + 'px';
    }

    if (containerRef.current) {
      const container = containerRef.current;
      if (container.scrollHeight > 250) {
        container.style.maxHeight = '250px';
        container.style.overflowY = 'auto';
      } else {
        container.style.maxHeight = 'none';
        container.style.overflowY = 'hidden';
      }
    }
  }, [message, files]);

  useEffect(() => {
    if (containerRef.current) {
      const container = containerRef.current;
      container.scrollTop = container.scrollHeight;
    }
  }, [files]);

  return (
    <div className="w-full px-2 pb-3 relative">
      <div
        ref={containerRef}
        className="container w-full shadow-xs  bg-background text-foreground rounded-md p-3 flex flex-col gap-3 border border-input overflow-hidden"
      >
        <div className="px-2 pe-22">
          <textarea
            ref={textareaRef}
            className="border-none outline-none w-full resize-none overflow-hidden py-1"
            placeholder={t('placeholder_description')}
            value={message}
            rows={1}
            onChange={(e) => setMessage(e.target.value)}
          />
        </div>

        <div className="flex gap-2 items-center absolute bottom-6 end-5">
          <UploadFile files={files} setFiles={setFiles} />
          <SendMessageAction
            ticketId={ticketId}
            message={message}
            files={files}
            clearForm={() => {
              setMessage('');
              setFiles([]);
            }}
          />
        </div>

        {files.length ? (
          <ul className="w-full flex items-start gap-1 pe-22 flex-wrap">
            {files.map((file, i) => (
              <li
                key={i}
                className="flex items-center gap-x-1 border p-2 rounded-sm max-w-full"
              >
                <FileText size={16} className="text-base text-green-500" />
                <span className="text-sm text-mono truncate max-w-[150px]">
                  {file.name}
                </span>
                <button type="button" onClick={() => handleRemoveFile(i)}>
                  <CircleX
                    size={15}
                    className="text-base text-red-500 ms-2.5"
                  />
                </button>
              </li>
            ))}
          </ul>
        ) : null}
      </div>
    </div>
  );
};

export default SendMessageBox;
