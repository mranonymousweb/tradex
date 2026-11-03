'use client';

import { useState } from 'react';
import Step1 from './components/Step1';
import Step2 from './components/Step2';
import Step3 from './components/Step3';

export default function Page() {
  const [userForm, setUserForm] = useState({
    step: 1,
    username: '',
    mobile: '',
    email: '',
    userId: '',
  });

  return (
    <>
      {userForm.step === 1 && (
        <Step1 userForm={userForm} setUserForm={setUserForm} />
      )}
      {userForm.step === 2 && (
        <Step2 userForm={userForm} setUserForm={setUserForm} />
      )}
      {userForm.step === 3 && (
        <Step3 userForm={userForm} setUserForm={setUserForm} />
      )}
    </>
  );
}
