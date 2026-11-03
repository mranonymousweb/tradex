/* eslint-disable @typescript-eslint/no-explicit-any */
'use client';

import { useState } from 'react';
import { useQuery } from '@tanstack/react-query';
import { AlertCircle, CircleAlert, CircleX, FileText } from 'lucide-react';
import { useTranslations } from 'next-intl';
import { Department } from '@/types/Ticket';
import { getDepartments } from '@/services/ticket.services';
import { Alert, AlertIcon, AlertTitle } from '@/components/ui/alert';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input, InputWrapper } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectRemove,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import {
  Tooltip,
  TooltipContent,
  TooltipProvider,
  TooltipTrigger,
} from '@/components/ui/tooltip';
import { TicketActions } from './ticket-action';
import { UploadFile } from './upload-file';

const FormAction = () => {
  const t = useTranslations('ticket');
  const [title, setTitle] = useState('');
  const [description, setDescription] = useState('');
  const [selectedDepartment, setSelectedDepartment] = useState('');
  const [files, setFiles] = useState<File[]>([]);
  const [errors, setErrors] = useState<any>(null);

  const { data: departments } = useQuery<Department[]>({
    queryKey: ['departments'],
    queryFn: () => getDepartments(),
  });

  const handleRemoveFile = (id: number) => {
    const file = files.filter((file, i) => i !== id);
    setFiles(file);
  };

  return (
    <Card className="pb-2.5">
      <CardHeader id="webhooks">
        <CardTitle>{t('register_ticket')}</CardTitle>
      </CardHeader>
      {errors && (
        <Alert variant="destructive" appearance="light" className="m-5 w-auto">
          <AlertIcon>
            <AlertCircle />
          </AlertIcon>
          <div>
            {errors.map((err: string, i: number) => (
              <AlertTitle key={i}>{err}</AlertTitle>
            ))}
          </div>
        </Alert>
      )}
      <CardContent className="grid gap-5">
        <div className="flex items-center flex-wrap lg:flex-nowrap gap-2.5">
          <Label className="flex w-full max-w-56">{t('title_ticket')}</Label>
          <div className="grow">
            <InputWrapper>
              <Input
                type="text"
                value={title}
                onChange={(e) => setTitle(e.target.value)}
                placeholder={t('placeholder_title')}
                maxLength={250}
              />
            </InputWrapper>
          </div>
        </div>
        <div className="flex items-start md:items-center flex-col md:flex-row flex-wrap lg:flex-nowrap gap-2.5">
          <Label className="flex w-full max-w-56">
            {t('choose_department')}
          </Label>
          <div className="grow relative w-full">
            <SelectRemove
              selected={selectedDepartment}
              onValueChange={() => setSelectedDepartment('')}
            />
            <Select
              value={selectedDepartment}
              onValueChange={(value) => setSelectedDepartment(value)}
            >
              <SelectTrigger>
                <div className="flex gap-x-2 items-center">
                  <SelectValue placeholder={t('choose')} />
                </div>
              </SelectTrigger>
              <SelectContent>
                {departments ? (
                  departments.map((department: Department) => (
                    <SelectItem key={department.id} value={`${department.id}`}>
                      {department.name}
                    </SelectItem>
                  ))
                ) : (
                  <span className="text-xs">{t('no_result')}</span>
                )}
              </SelectContent>
            </Select>
          </div>
        </div>
        <div className="flex flex-wrap lg:flex-nowrap gap-2.5">
          <Label className="flex w-full max-w-56">
            {t('description_ticket')}
          </Label>
          <div className="grow">
            <Textarea
              placeholder={t('placeholder_description')}
              className="text-sm text-secondary-foreground font-normal"
              rows={5}
              value={description}
              onChange={(e) => setDescription(e.target.value)}
            />
          </div>
        </div>
        <div className="flex flex-wrap lg:flex-nowrap gap-2.5">
          <div className="flex items-center w-full gap-2 max-w-56">
            <Label>{t('upload_file')}</Label>
            <TooltipProvider>
              <Tooltip delayDuration={50}>
                <TooltipTrigger className="cursor-pointer">
                  <CircleAlert size={20} fill="none" color="#9a9c9f" />
                </TooltipTrigger>
                <TooltipContent className="flex flex-col text-xs w-full p-2">
                  <span>{t('guide_upload')}</span>
                  <small className="text-sm">
                    (png,jpg,jpeg,gif,bmp,webp,svg,mp4,mov,avi,wmv,flv,mkw,pdf,rar,zip,txt)
                  </small>
                </TooltipContent>
              </Tooltip>
            </TooltipProvider>
          </div>

          <div className="grow ">
            <UploadFile files={files} setFiles={setFiles} />
          </div>
        </div>

        <ul className="w-full flex items-center gap-1 flex-wrap">
          {files.map((file, i) => (
            <li
              key={i}
              className="flex items-center gap-x-1 border p-2 rounded-sm"
            >
              <FileText size={16} className="text-base text-green-500" />
              <span className="text-sm text-mono text-nowrap">{file.name}</span>
              <button type="button" onClick={() => handleRemoveFile(i)}>
                <CircleX size={15} className="text-base text-red-500 ms-2.5" />
              </button>
            </li>
          ))}
        </ul>
        <div className="flex flex-col items-end">
          <TicketActions
            setErrors={setErrors}
            title={title}
            description={description}
            departmentId={selectedDepartment}
            files={files}
          />
        </div>
      </CardContent>
    </Card>
  );
};

export { FormAction };
