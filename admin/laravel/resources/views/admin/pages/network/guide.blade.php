@extends('admin.layout.master')

@section('title', trans('general.networks_management'))

@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-slate-50 dark:bg-slate-900" dir="rtl">
        <!-- Header -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                    @lang('general.networks_management')
                </h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    راهنمای  فعال‌سازی و مدیریت شبکه‌ها و کیف پول‌ها
                </p>
            </div>
            <div class="flex items-center">
                <a href="{{ route('admin.setting.currency.management') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold rounded-xl bg-white text-slate-700 hover:bg-slate-100 hover:text-slate-900 border border-slate-200 shadow-sm transition-all duration-200 dark:bg-slate-800 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-700 dark:hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                    </svg>
                    @lang('master.back')
                </a>
            </div>
        </div>

        <!-- Content -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
            <div class="p-8 lg:p-12">
                <article class="prose prose-slate dark:prose-invert max-w-4xl mx-auto prose-headings:font-bold prose-a:text-indigo-600 dark:prose-a:text-indigo-400 prose-img:rounded-xl">

                    <h1 class="text-start text-4xl mb-10 text-indigo-600 dark:text-indigo-400">
                        راهنمای  فعال‌سازی و مدیریت شبکه‌ها و کیف پول‌ها
                    </h1>


                    <hr class="my-8 border-slate-200 dark:border-slate-700" />

                    <h2>مسیر دسترسی</h2>
                    <p>از پنل ادمین وارد مسیر زیر شوید:</p>
                    <div class="bg-slate-100 dark:bg-slate-700/50 rounded-lg p-4 font-mono text-sm text-center font-bold text-slate-800 dark:text-slate-200 my-4">
                        تنظیمات &larr; مدیریت شبکه‌ها و ارزها
                    </div>
                    <p>در این صفحه چهار بخش وجود دارد:</p>
                    <ol>
                        <li>مدیریت ارزها</li>
                        <li>مدیریت شبکه‌ها</li>
                        <li>مدیریت کیف پول‌ها</li>
                        <li>مدیریت تأمین‌کننده</li>
                    </ol>

                    <h2>شرط‌های اصلی فعال بودن یک شبکه</h2>
                    <p>برای اینکه شبکه‌ای در بخش واریز و برداشت کاربران نمایش داده شود، همه موارد زیر باید برقرار باشند:</p>
                    <ul>
                        <li>ارز از نوع <strong>«کریپتو»</strong> و وضعیت آن <strong>«فعال»</strong> باشد.</li>
                        <li>خود شبکه وضعیت <strong>«فعال»</strong> داشته باشد.</li>
                        <li>اتصال ارز به شبکه وضعیت <strong>«فعال»</strong> داشته باشد.</li>
                        <li>روی اتصال ارز به شبکه، گزینه‌های <strong>«قابل واریز»</strong>  روشن باشند.</li>
                        <li>برای دریافت آدرس واریز، مخزن کیف پول آن شبکه حداقل یک آدرس فعال و قابل استفاده داشته باشد.</li>
                        <li>تأمین‌کننده انتخاب‌شده به‌درستی تنظیم و همگام‌سازی شده باشد.</li>
                    </ul>

                    <div class="my-6 border-r-4 border-amber-500 bg-amber-50 dark:bg-amber-900/20 p-4 rounded-l-xl flex gap-3">
                        <svg class="w-6 h-6 text-amber-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="m-0 text-amber-800 dark:text-amber-200 text-sm font-medium">
                            <strong>نکته مهم:</strong> در وضعیت فعلی سیستم، اگر فقط یکی از گزینه‌های «قابل واریز» یا «قابل برداشت» روی اتصال ارز و شبکه روشن باشد، شبکه در فهرست شبکه‌های کاربر نمایش داده نمی‌شود.
                        </p>
                    </div>

                    <h2>ترتیب پیشنهادی فعال‌سازی</h2>

                    <h3>۱. تنظیم تأمین‌کننده</h3>
                    <p>وارد بخش <strong>مدیریت تأمین‌کننده</strong> شوید.</p>

                    <h4>ثبت اطلاعات اتصال</h4>
                    <p>برای تأمین‌کننده موردنظر مانند Coinex، Gate.io، Nobitex،   Cregis:</p>
                    <ol>
                        <li>API Key را وارد کنید.</li>
                        <li>Secret Key را وارد کنید.</li>
                        <li>فیلدهای اختصاصی تأمین‌کننده را، در صورت وجود، تکمیل کنید.</li>
                        <li>دکمه «ذخیره» را بزنید.</li>
                    </ol>


                    <h4>انتخاب تأمین‌کننده کیف پول</h4>
                    <p>در مرحله دوم تنظیمات:</p>
                    <ol>
                        <li>«تأمین‌کننده کیف پول» را انتخاب کنید.</li>
                        <li>«انجام‌دهنده مبادلات و تراکنش‌ها» را انتخاب کنید.</li>
                        <li>تنظیمات را ذخیره کنید.</li>
                    </ol>
                    <p>با ذخیره این مرحله، سیستم معمولاً عملیات زیر را اجرا می‌کند:</p>
                    <ul>
                        <li>دریافت و همگام‌سازی ارزها</li>
                        <li>دریافت و همگام‌سازی شبکه‌ها</li>
                        <li>ایجاد یا به‌روزرسانی اتصال ارزها به شبکه‌ها</li>
                        <li>دریافت آدرس‌های کیف پول (ممکن است تا ۲۰ دقیقه زمان ببرد)</li>
                    </ul>

                    <h3>۲. بررسی و فعال‌سازی شبکه</h3>
                    <p>وارد بخش <strong>مدیریت شبکه‌ها</strong> شوید. شبکه موردنظر را پیدا کنید و روی «تنظیمات» بزنید. سپس موارد زیر را بررسی کنید:</p>
                    <ul class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-4">
                        <li class="bg-slate-50 dark:bg-slate-700/50 p-3 rounded-lg"><strong>وضعیت:</strong> فعال</li>
                        <li class="bg-slate-50 dark:bg-slate-700/50 p-3 rounded-lg"><strong>قابل برداشت:</strong> بله</li>
                        <li class="bg-slate-50 dark:bg-slate-700/50 p-3 rounded-lg"><strong>قابل واریز:</strong> بله</li>
                        <li class="bg-slate-50 dark:bg-slate-700/50 p-3 rounded-lg"><strong>تگ‌دار:</strong> بر اساس ویژگی واقعی شبکه</li>
                    </ul>
                    <p class="mt-4 text-sm text-slate-500">شبکه‌هایی مانند XRP و XLM ممکن است به Tag یا Memo نیاز داشته باشند.</p>

                    <h3>۳. فعال‌سازی ارز</h3>
                    <p>وارد بخش <strong>مدیریت ارزها</strong> شوید و ارز موردنظر را ویرایش کنید:</p>
                    <ul>
                        <li><strong>نوع:</strong> کریپتو</li>
                        <li><strong>وضعیت:</strong> فعال</li>
                    </ul>

                    <h3>۴. اتصال ارز به شبکه و تعیین کارمزد</h3>
                    <p>در صفحه ویرایش ارز، بخش <strong>اطلاعات ارز بر روی هر شبکه</strong> را باز کنید و موارد زیر را تنظیم کنید:</p>
                    <ul>
                        <li><strong>کارمزد:</strong> در واحد همان ارز</li>
                        <li><strong>حداقل واریز و برداشت</strong></li>
                        <li><strong>وضعیت:</strong> فعال</li>
                        <li><strong>قابل برداشت و واریز:</strong> روشن</li>
                    </ul>

                    <div class="my-6 border-r-4 border-red-500 bg-red-50 dark:bg-red-900/20 p-4 rounded-l-xl flex gap-3">
                        <svg class="w-6 h-6 text-red-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <p class="m-0 text-red-800 dark:text-red-200 text-sm font-medium">
                            <strong>هشدار:</strong> همگام‌سازی مجدد تأمین‌کننده ممکن است کارمزد، حداقل‌ها، وضعیت و گزینه‌های واریز/برداشت ثبت‌شده به‌صورت دستی را بازنویسی کند.
                        </p>
                    </div>

                    <h3>۵. تنظیم مخزن کیف پول شبکه</h3>
                    <p>وارد بخش <strong>مدیریت کیف پول‌ها</strong> شوید. برای شبکه موردنظر موارد زیر را تنظیم کنید:</p>
                    <ul>
                        <li>آدرس کیف پول</li>
                        <li>Tag یا Memo در صورت نیاز</li>
                        <li>تأمین‌کننده و نوع کیف پول</li>
                    </ul>

                    <hr class="my-8 border-slate-200 dark:border-slate-700" />

                    <h2>تفاوت کیف پول اشتراکی و اختصاصی</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div class="bg-indigo-50 dark:bg-indigo-900/20 p-6 rounded-2xl border border-indigo-100 dark:border-indigo-800/50">
                            <h4 class="text-indigo-800 dark:text-indigo-300 mt-0">کیف پول اشتراکی</h4>
                            <p class="text-sm mb-0">یک آدرس بین چند کاربر استفاده می‌شود و تشخیص کاربر ممکن است با Tag، Memo یا اطلاعات تراکنش انجام شود.</p>
                        </div>
                        <div class="bg-emerald-50 dark:bg-emerald-900/20 p-6 rounded-2xl border border-emerald-100 dark:border-emerald-800/50">
                            <h4 class="text-emerald-800 dark:text-emerald-300 mt-0">کیف پول اختصاصی</h4>
                            <p class="text-sm mb-0">به هر کاربر آدرس مجزا اختصاص داده می‌شود (مانند HD Wallet، Bitycle و Cregis).</p>
                        </div>
                    </div>

                    <h2 class="mt-12">چک‌لیست کوتاه فعال‌سازی</h2>
                    <ul class="space-y-3 list-none pl-0">
                        <li class="flex items-center gap-3"><input type="checkbox" class="w-5 h-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500" disabled checked> اطلاعات API تأمین‌کننده ثبت شده است.</li>
                        <li class="flex items-center gap-3"><input type="checkbox" class="w-5 h-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500" disabled checked> تأمین‌کننده کیف پول انتخاب شده است.</li>
                        <li class="flex items-center gap-3"><input type="checkbox" class="w-5 h-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500" disabled checked> کارمزد اضافه و سقف آن تنظیم شده است.</li>
                        <li class="flex items-center gap-3"><input type="checkbox" class="w-5 h-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500" disabled checked> ارز از نوع کریپتو و فعال است.</li>
                        <li class="flex items-center gap-3"><input type="checkbox" class="w-5 h-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500" disabled checked> شبکه و اتصال‌ها فعال هستند.</li>
                        <li class="flex items-center gap-3"><input type="checkbox" class="w-5 h-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500" disabled checked> آدرس فعال در مخزن کیف پول وجود دارد.</li>
                        <li class="flex items-center gap-3"><input type="checkbox" class="w-5 h-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500" disabled checked> نمایش شبکه و دریافت آدرس آزمایش شده است.</li>
                    </ul>
                </article>
            </div>
        </div>
    </main>
@endsection
