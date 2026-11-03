import { Dispatch, SetStateAction } from 'react';
import { Upload } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { toast } from 'sonner';
import { Button } from '@/components/ui/button';

interface Props {
  files: File[];
  setFiles: Dispatch<SetStateAction<File[]>>;
}

export const UploadFile: React.FC<Props> = ({ files, setFiles }) => {
  const t = useTranslations('ticket');
  const handleFile = (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    const MAX_SIZE = 100 * 1024 * 1024;

    if (file) {
      if (file.size > MAX_SIZE) {
        toast.error(t('validation.validate_files'));
        return;
      }
      setFiles([...files, file]);
    }
  };

  return (
    <Button className="p-0" variant="ghost" mode="icon">
      <label htmlFor="uploadFile" className="px-4 py-2 flex gap-x-1">
        <input
          type="file"
          onChange={handleFile}
          id="uploadFile"
          className="hidden"
          accept=".png,.jpg,.jpeg,.gif,.bmp,.webp,.svg,.mp4,.mov,.avi,.wmv,.flv,.mkv,.pdf,.rar,.zip,.txt"
        />
        <Upload size={20} />
      </label>
    </Button>
  );
};
