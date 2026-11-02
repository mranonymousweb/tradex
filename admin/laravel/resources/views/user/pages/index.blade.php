@extends('user.layout.master')
@section('title','Dashboard')
@section('contents')
    <main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-background-dark">
        <!-- header page -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-dark dark:text-light">سلام مریم عزیز <span class="text-2xl">👋</span></h2>
                <p class="text-gray-600 dark:text-gray-400">وضعیت امروز حساب کاربری شما</p>
            </div>
            <div class="mt-4 md:mt-0">
                <button class="flex items-center px-4 py-2 text-sm font-medium rounded-lg bg-primary text-white hover:bg-primary/90 shadow-sm dark:bg-primary-dark dark:hover:bg-primary/80">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                    </svg>
                    افزودن موجودی
                </button>
            </div>
        </div>

        <!-- Summary of cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">
            <div class="bg-white dark:bg-card-dark rounded-xl shadow-soft dark:shadow-soft-dark p-6 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">موجودی کیف پول</p>
                        <p class="text-2xl font-bold text-dark dark:text-light">12,850,000 <span class="text-sm">تومان</span></p>
                    </div>
                    <div class="p-3 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-primary dark:text-primary-dark">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center">
                    <span class="text-xs text-gray-500 dark:text-gray-400">افزایش 5% نسبت به ماه قبل</span>
                    <span class="text-xs px-2 py-1 rounded-full bg-success/10 dark:bg-success/20 text-success dark:text-success-dark">
                                <i class="fas fa-arrow-up ml-1"></i> 5%
                            </span>
                </div>
            </div>

            <div class="bg-white dark:bg-card-dark rounded-xl shadow-soft dark:shadow-soft-dark p-6 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">سفارشات فعال</p>
                        <p class="text-2xl font-bold text-dark dark:text-light">3 <span class="text-sm">سفارش</span></p>
                    </div>
                    <div class="p-3 rounded-xl bg-green-50 dark:bg-green-900/30 text-success dark:text-success-dark">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center">
                    <span class="text-xs text-gray-500 dark:text-gray-400">2 سفارش در حال ارسال</span>
                    <a href="#" class="text-xs text-primary hover:text-primary/80 dark:text-primary-dark dark:hover:text-primary/70">مشاهده همه</a>
                </div>
            </div>

            <div class="bg-white dark:bg-card-dark rounded-xl shadow-soft dark:shadow-soft-dark p-6 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">تعداد علاقه‌مندی‌ها</p>
                        <p class="text-2xl font-bold text-dark dark:text-light">17 <span class="text-sm">محصول</span></p>
                    </div>
                    <div class="p-3 rounded-xl bg-red-50 dark:bg-red-900/30 text-danger dark:text-danger-dark">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center">
                    <span class="text-xs text-gray-500 dark:text-gray-400">5 محصول جدید اضافه شد</span>
                    <a href="#" class="text-xs text-primary hover:text-primary/80 dark:text-primary-dark dark:hover:text-primary/70">مشاهده همه</a>
                </div>
            </div>

            <div class="bg-white dark:bg-card-dark rounded-xl shadow-soft dark:shadow-soft-dark p-6 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">امتیاز شما</p>
                        <p class="text-2xl font-bold text-dark dark:text-light">92 <span class="text-sm">از 100</span></p>
                    </div>
                    <div class="p-3 rounded-xl bg-yellow-50 dark:bg-yellow-900/30 text-warning dark:text-warning-dark">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-1.5">
                        <div class="bg-warning dark:bg-warning-dark h-1.5 rounded-full" style="width: 92%"></div>
                    </div>
                    <div class="flex justify-between items-center mt-1">
                        <span class="text-xs text-gray-500 dark:text-gray-400">سطح طلایی</span>
                        <span class="text-xs text-warning dark:text-warning-dark">92%</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and orders -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-6">
            <!-- Activity chart -->
            <div class="xl:col-span-2 bg-white dark:bg-card-dark rounded-xl shadow-soft dark:shadow-soft-dark p-6 border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-bold text-lg mb-4 relative pb-4 before:absolute before:right-0 before:bottom-0 before:size-2 before:rounded-full before:bg-primary after:absolute after:w-40 after:h-2 after:bottom-0 after:right-4 after:bg-primary after:rounded-lg">نمودار فعالیت‌های اخیر</h3>
                    <div class="flex items-center space-x-2 space-x-reverse">
                        <button role="presentation" role="tab" data-tab="tabOne" class="px-3 tab-button py-1 text-xs font-medium rounded-lg !bg-transparent">هفته</button>
                        <button role="presentation" role="tab" data-tab="tabTwo" class="px-3 tab-button py-1 text-xs font-medium rounded-lg !bg-transparent">ماه</button>
                        <button role="presentation" role="tab" data-tab="tabThree" class="px-3 tab-button py-1 text-xs font-medium rounded-lg !bg-transparent">سال</button>
                    </div>
                </div>
                <div id="tabOne" class="tab-content bg-white hidden dark:bg-card-dark p-6 rounded-lg">
                    <div class="relative h-80">
                        <canvas id="userActivityChart"></canvas>
                    </div>
                    <div class="mt-4 text-sm text-gray-600">
                        <p class="dark:text-white">آخرین به‌روزرسانی: <span id="currentDate"></span></p>
                    </div>
                </div>
                <div id="tabTwo" class="tab-content bg-gray-50 border border-gray-200 hidden tab-content dark:bg-card-dark p-6 rounded-lg">
                    <div class="relative h-80">
                        <h3>فعالیتی ثبت نشده در ماه</h3>
                    </div>
                </div>
                <div id="tabThree" class="tab-content bg-gray-50 border border-gray-200 hidden dark:bg-card-dark p-6 rounded-lg">
                    <div class="relative h-80">
                        <h3>فعالیتی ثبت نشده در سال</h3>
                    </div>
                </div>
            </div>

            <!-- Latest transactions -->
            <div class="bg-white dark:bg-card-dark rounded-xl shadow-soft dark:shadow-soft-dark overflow-hidden border border-gray-100 dark:border-gray-700">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="font-bold text-lg mb-4 relative pb-4 before:absolute before:right-0 before:bottom-0 before:size-2 before:rounded-full before:bg-primary after:absolute after:w-40 after:h-2 after:bottom-0 after:right-4 after:bg-primary after:rounded-lg">آخرین تراکنش‌ها</h3>
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    <div class="p-4 flex items-center hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <div class="p-3 rounded-xl bg-green-50 dark:bg-green-900/30 text-success dark:text-success-dark ml-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-dark dark:text-light">واریز کیف پول</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">1402/05/20 - 12:45</p>
                        </div>
                        <div class="text-sm font-medium text-success dark:text-success-dark">+450,000 تومان</div>
                    </div>
                    <div class="p-4 flex items-center hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <div class="p-3 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-primary dark:text-primary-dark ml-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-dark dark:text-light">خرید از فروشگاه</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">1402/05/18 - 09:23</p>
                        </div>
                        <div class="text-sm font-medium text-danger dark:text-danger-dark">-1,250,000 تومان</div>
                    </div>
                    <div class="p-4 flex items-center hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <div class="p-3 rounded-xl bg-green-50 dark:bg-green-900/30 text-success dark:text-success-dark ml-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-dark dark:text-light">واریز کیف پول</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">1402/05/15 - 16:12</p>
                        </div>
                        <div class="text-sm font-medium text-success dark:text-success-dark">+750,000 تومان</div>
                    </div>
                    <div class="p-4 flex items-center hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <div class="p-3 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-primary dark:text-primary-dark ml-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-dark dark:text-light">خرید از فروشگاه</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">1402/05/10 - 14:30</p>
                        </div>
                        <div class="text-sm font-medium text-danger dark:text-danger-dark">-850,000 تومان</div>
                    </div>
                </div>
                <div class="p-4 bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 text-center">
                    <a href="#" class="text-sm font-medium text-primary hover:text-primary/90 dark:text-primary-dark dark:hover:text-primary/80">مشاهده همه تراکنش‌ها</a>
                </div>
            </div>
        </div>

        <!-- Recent orders -->
        <div class="bg-white dark:bg-card-dark rounded-xl shadow-soft dark:shadow-soft-dark overflow-hidden mb-6 border border-gray-100 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h3 class="font-bold text-lg mb-4 relative pb-4 before:absolute before:right-0 before:bottom-0 before:size-2 before:rounded-full before:bg-primary after:absolute after:w-40 after:h-2 after:bottom-0 after:right-4 after:bg-primary after:rounded-lg">سفارشات اخیر</h3>
                <a href="#" class="text-sm font-medium text-primary hover:text-primary/90 dark:text-primary-dark dark:hover:text-primary/80 flex items-center">
                    مشاهده همه
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                    </svg>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">شماره سفارش</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">تاریخ</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">محصولات</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">مبلغ</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">وضعیت</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">عملیات</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-card-dark divide-y divide-gray-200 dark:divide-gray-700">
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-dark dark:text-light">#ORD-12345</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">1402/05/15</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">3 محصول</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">1,250,000 تومان</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5  rounded-full bg-success/10 dark:bg-success/20 text-success dark:text-success-dark">
                                            تحویل شده
                                        </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            <a href="#" class="text-primary hover:text-primary/80 dark:text-primary-dark dark:hover:text-primary/70 flex items-center">
                                جزئیات
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"></path>
                                </svg>
                            </a>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-dark dark:text-light">#ORD-12344</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">1402/05/10</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">2 محصول</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">850,000 تومان</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5  rounded-full bg-primary/10 dark:bg-primary/20 text-primary dark:text-primary-dark">
                                            در حال ارسال
                                        </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            <a href="#" class="text-primary hover:text-primary/80 dark:text-primary-dark dark:hover:text-primary/70 flex items-center">
                                جزئیات
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"></path>
                                </svg>
                            </a>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-dark dark:text-light">#ORD-12343</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">1402/05/05</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">5 محصول</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">2,150,000 تومان</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5  rounded-full bg-danger/10 dark:bg-danger/20 text-danger dark:text-danger-dark">
                                            لغو شده
                                        </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            <a href="#" class="text-primary hover:text-primary/80 dark:text-primary-dark dark:hover:text-primary/70 flex items-center">
                                جزئیات
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"></path>
                                </svg>
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Suggested products -->
        <div class="bg-white dark:bg-card-dark rounded-xl shadow-soft dark:shadow-soft-dark overflow-hidden border border-gray-100 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h3 class="font-bold text-lg mb-4 relative pb-4 before:absolute before:right-0 before:bottom-0 before:size-2 before:rounded-full before:bg-primary after:absolute after:w-40 after:h-2 after:bottom-0 after:right-4 after:bg-primary after:rounded-lg">محصولات پیشنهادی برای شما</h3>
                <a href="#" class="text-sm font-medium text-primary hover:text-primary/90 dark:text-primary-dark dark:hover:text-primary/80 flex items-center">
                    مشاهده همه
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                    </svg>
                </a>
            </div>
            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
                <article class="bg-white product-box-item drop-shadow-md rounded-xl border border-gray-300 p-4 dark:bg-card-dark dark:border-white dark:border-1" itemscope="" itemtype="http://schema.org/Product">
                    <header class="flex items-center relative justify-between">
                        <div class="flex flex-col bg-white absolute top-1 start-0 p-1 rounded space-y-3  dark:bg-transparent">
                            <button class="hover:text-gray-400 relative group cursor-pointer" aria-label="افزودن به علاقه‌مندی‌ها">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 dark:text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"></path>
                                </svg>
                                <span class="absolute text-nowrap right-full mr-2 top-1/2 -translate-y-1/2 hidden group-hover:block bg-gray-900 text-white text-sm py-1 px-2 rounded-md shadow-lg">
                                    <span class="absolute left-full top-1/2 -translate-y-1/2 w-0 h-0 border-y-4 border-y-transparent border-l-4 border-l-gray-900"></span>
                                    افزودن به علاقه‌مندی‌ها
                                    </span>
                            </button>
                            <button class="hover:text-gray-400 cursor-pointer relative group" aria-label="مشاهده جزئیات">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 dark:text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"></path>
                                </svg>
                                <span class="absolute text-nowrap right-full mr-2 top-1/2 -translate-y-1/2 hidden group-hover:block bg-gray-900 text-white text-sm py-1 px-2 rounded-md shadow-lg">
                                    <span class="absolute left-full top-1/2 -translate-y-1/2 w-0 h-0 border-y-4 border-y-transparent border-l-4 border-l-gray-900"></span>
                                    مشاهده جزئیات
                                    </span>
                            </button>
                            <button class="hover:text-gray-400 cursor-pointer relative group" aria-label="مقایسه محصول">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 dark:text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"></path>
                                </svg>
                                <span class="absolute text-nowrap right-full mr-2 top-1/2 -translate-y-1/2 hidden group-hover:block bg-gray-900 text-white text-sm py-1 px-2 rounded-md shadow-lg">
                                    <span class="absolute left-full top-1/2 -translate-y-1/2 w-0 h-0 border-y-4 border-y-transparent border-l-4 border-l-gray-900"></span>
                                    مقایسه محصول
                                    </span>
                            </button>
                        </div>
                        <div class="flex flex-col bg-white absolute top-1 end-0 p-1 rounded dark:bg-transparent" itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
                            <meta itemprop="color" content="خاکستری">
                            <meta itemprop="color" content="زرد">
                            <meta itemprop="color" content="سبز">
                            <span class="size-4 rounded-full bg-gray-300" title="خاکستری"></span>
                            <span class="size-4 rounded-full bg-amber-300" title="زرد"></span>
                            <span class="size-4 rounded-full bg-green-300" title="سبز"></span>
                        </div>
                    </header>
                    <figure class="flex image justify-center my-4">
                        <a href="/product/iphone-13-pro-max" itemprop="url">
                            <img class="one-image" src="{{asset('assets/images/product/mobile-3.png')}}" loading="lazy" alt="گوشی موبایل اپل آیفون 13 پرو مکس" itemprop="image">
                            <img class="two-image" src="{{asset('assets/images/product/mobile-4.png')}}" loading="lazy" alt="نمای دیگر گوشی اپل آیفون 13 پرو مکس" itemprop="image">
                        </a>
                    </figure>
                    <h3 class="text-base leading-8  line-clamp-2 mb-2">
                        <a href="/product/iphone-13-pro-max" class="text-gray-800 dark:text-white" itemprop="name">گوشی موبایل
                            اپل مدل iPhone 13 Pro Max دو سیم کارت</a>
                    </h3>
                    <footer itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
                        <link itemprop="availability" href="http://schema.org/InStock">
                        <meta itemprop="priceCurrency" content="IRR">
                        <div class="flex items-center justify-between">
                            <button class="bg-primary-grad text-white p-3 rounded-lg transition" aria-label="افزودن به سبد خرید">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 dark:text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"></path>
                                </svg>
                            </button>
                            <div class="text-gray-700 flex flex-col items-center">
                                <div class="flex justify-between items-center">
                                    <del class="text-zinc-400" itemprop="priceSpecification" itemscope="" itemtype="http://schema.org/UnitPriceSpecification">
                                        <meta itemprop="priceCurrency" content="IRR">
                                        <span itemprop="price">100000000</span>
                                    </del>
                                    <span class="bg-secondary px-3 rounded-bl-none text-sm rounded-xl text-white ms-3" itemprop="discount">10%</span>
                                </div>
                                <span class="text-xl font-bold dark:text-white" itemprop="price" content="90000000">90,000,000 <span class="text-xs">تومان</span></span>
                            </div>
                        </div>
                    </footer>
                </article>

                <article class="bg-white product-box-item drop-shadow-md rounded-xl border border-gray-300 p-4 dark:bg-card-dark dark:border-white dark:border-1" itemscope="" itemtype="http://schema.org/Product">
                    <header class="flex items-center relative justify-between">
                        <div class="flex flex-col bg-white absolute top-1 start-0 p-1 rounded space-y-3  dark:bg-transparent">
                            <button class="hover:text-gray-400 relative group cursor-pointer" aria-label="افزودن به علاقه‌مندی‌ها">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 dark:text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"></path>
                                </svg>
                                <span class="absolute text-nowrap right-full mr-2 top-1/2 -translate-y-1/2 hidden group-hover:block bg-gray-900 text-white text-sm py-1 px-2 rounded-md shadow-lg">
                                    <span class="absolute left-full top-1/2 -translate-y-1/2 w-0 h-0 border-y-4 border-y-transparent border-l-4 border-l-gray-900"></span>
                                    افزودن به علاقه‌مندی‌ها
                                    </span>
                            </button>
                            <button class="hover:text-gray-400 cursor-pointer relative group" aria-label="مشاهده جزئیات">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 dark:text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"></path>
                                </svg>
                                <span class="absolute text-nowrap right-full mr-2 top-1/2 -translate-y-1/2 hidden group-hover:block bg-gray-900 text-white text-sm py-1 px-2 rounded-md shadow-lg">
                                    <span class="absolute left-full top-1/2 -translate-y-1/2 w-0 h-0 border-y-4 border-y-transparent border-l-4 border-l-gray-900"></span>
                                    مشاهده جزئیات
                                    </span>
                            </button>
                            <button class="hover:text-gray-400 cursor-pointer relative group" aria-label="مقایسه محصول">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 dark:text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"></path>
                                </svg>
                                <span class="absolute text-nowrap right-full mr-2 top-1/2 -translate-y-1/2 hidden group-hover:block bg-gray-900 text-white text-sm py-1 px-2 rounded-md shadow-lg">
                                    <span class="absolute left-full top-1/2 -translate-y-1/2 w-0 h-0 border-y-4 border-y-transparent border-l-4 border-l-gray-900"></span>
                                    مقایسه محصول
                                    </span>
                            </button>
                        </div>
                        <div class="flex flex-col bg-white absolute top-1 end-0 p-1 rounded dark:bg-transparent" itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
                            <meta itemprop="color" content="خاکستری">
                            <meta itemprop="color" content="زرد">
                            <meta itemprop="color" content="سبز">
                            <span class="size-4 rounded-full bg-gray-300" title="خاکستری"></span>
                            <span class="size-4 rounded-full bg-amber-300" title="زرد"></span>
                            <span class="size-4 rounded-full bg-green-300" title="سبز"></span>
                        </div>
                    </header>
                    <figure class="flex image justify-center my-4">
                        <a href="/product/iphone-13-pro-max" itemprop="url">
                            <img class="one-image" src="{{asset('assets/images/product/laptop-1.png')}}" loading="lazy" alt="گوشی موبایل اپل آیفون 13 پرو مکس" itemprop="image">
                            <img class="two-image" src="{{asset('assets/images/product/laptop-2.png')}}" loading="lazy" alt="نمای دیگر گوشی اپل آیفون 13 پرو مکس" itemprop="image">
                        </a>
                    </figure>
                    <h3 class="text-base leading-8  line-clamp-2 mb-2">
                        <a href="/product/iphone-13-pro-max" class="text-gray-800 dark:text-white" itemprop="name">گوشی موبایل
                            اپل مدل iPhone 13 Pro Max دو سیم کارت</a>
                    </h3>
                    <footer itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
                        <link itemprop="availability" href="http://schema.org/InStock">
                        <meta itemprop="priceCurrency" content="IRR">
                        <div class="flex items-center justify-between">
                            <button class="bg-primary-grad text-white p-3 rounded-lg transition" aria-label="افزودن به سبد خرید">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 dark:text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"></path>
                                </svg>
                            </button>
                            <div class="text-gray-700 flex flex-col items-center">
                                <div class="flex justify-between items-center">
                                    <del class="text-zinc-400" itemprop="priceSpecification" itemscope="" itemtype="http://schema.org/UnitPriceSpecification">
                                        <meta itemprop="priceCurrency" content="IRR">
                                        <span itemprop="price">100000000</span>
                                    </del>
                                    <span class="bg-secondary px-3 rounded-bl-none text-sm rounded-xl text-white ms-3" itemprop="discount">10%</span>
                                </div>
                                <span class="text-xl font-bold dark:text-white" itemprop="price" content="90000000">90,000,000 <span class="text-xs">تومان</span></span>
                            </div>
                        </div>
                    </footer>
                </article>

                <article class="bg-white product-box-item drop-shadow-md rounded-xl border border-gray-300 p-4 dark:bg-card-dark dark:border-white dark:border-1" itemscope="" itemtype="http://schema.org/Product">
                    <header class="flex items-center relative justify-between">
                        <div class="flex flex-col bg-white absolute top-1 start-0 p-1 rounded space-y-3  dark:bg-transparent">
                            <button class="hover:text-gray-400 relative group cursor-pointer" aria-label="افزودن به علاقه‌مندی‌ها">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 dark:text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"></path>
                                </svg>
                                <span class="absolute text-nowrap right-full mr-2 top-1/2 -translate-y-1/2 hidden group-hover:block bg-gray-900 text-white text-sm py-1 px-2 rounded-md shadow-lg">
                                    <span class="absolute left-full top-1/2 -translate-y-1/2 w-0 h-0 border-y-4 border-y-transparent border-l-4 border-l-gray-900"></span>
                                    افزودن به علاقه‌مندی‌ها
                                    </span>
                            </button>
                            <button class="hover:text-gray-400 cursor-pointer relative group" aria-label="مشاهده جزئیات">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 dark:text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"></path>
                                </svg>
                                <span class="absolute text-nowrap right-full mr-2 top-1/2 -translate-y-1/2 hidden group-hover:block bg-gray-900 text-white text-sm py-1 px-2 rounded-md shadow-lg">
                                    <span class="absolute left-full top-1/2 -translate-y-1/2 w-0 h-0 border-y-4 border-y-transparent border-l-4 border-l-gray-900"></span>
                                    مشاهده جزئیات
                                    </span>
                            </button>
                            <button class="hover:text-gray-400 cursor-pointer relative group" aria-label="مقایسه محصول">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 dark:text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"></path>
                                </svg>
                                <span class="absolute text-nowrap right-full mr-2 top-1/2 -translate-y-1/2 hidden group-hover:block bg-gray-900 text-white text-sm py-1 px-2 rounded-md shadow-lg">
                                    <span class="absolute left-full top-1/2 -translate-y-1/2 w-0 h-0 border-y-4 border-y-transparent border-l-4 border-l-gray-900"></span>
                                    مقایسه محصول
                                    </span>
                            </button>
                        </div>
                        <div class="flex flex-col bg-white absolute top-1 end-0 p-1 rounded dark:bg-transparent" itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
                            <meta itemprop="color" content="خاکستری">
                            <meta itemprop="color" content="زرد">
                            <meta itemprop="color" content="سبز">
                            <span class="size-4 rounded-full bg-gray-300" title="خاکستری"></span>
                            <span class="size-4 rounded-full bg-amber-300" title="زرد"></span>
                            <span class="size-4 rounded-full bg-green-300" title="سبز"></span>
                        </div>
                    </header>
                    <figure class="flex image justify-center my-4">
                        <a href="/product/iphone-13-pro-max" itemprop="url">
                            <img class="one-image" src="{{asset('assets/images/product/laptop-3.png')}}" loading="lazy" alt="گوشی موبایل اپل آیفون 13 پرو مکس" itemprop="image">
                            <img class="two-image" src="{{asset('assets/images/product/laptop-4.png')}}" loading="lazy" alt="نمای دیگر گوشی اپل آیفون 13 پرو مکس" itemprop="image">
                        </a>
                    </figure>
                    <h3 class="text-base leading-8  line-clamp-2 mb-2">
                        <a href="/product/iphone-13-pro-max" class="text-gray-800 dark:text-white" itemprop="name">گوشی موبایل
                            اپل مدل iPhone 13 Pro Max دو سیم کارت</a>
                    </h3>
                    <footer itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
                        <link itemprop="availability" href="http://schema.org/InStock">
                        <meta itemprop="priceCurrency" content="IRR">
                        <div class="flex items-center justify-between">
                            <button class="bg-primary-grad text-white p-3 rounded-lg transition" aria-label="افزودن به سبد خرید">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 dark:text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"></path>
                                </svg>
                            </button>
                            <div class="text-gray-700 flex flex-col items-center">
                                <div class="flex justify-between items-center">
                                    <del class="text-zinc-400" itemprop="priceSpecification" itemscope="" itemtype="http://schema.org/UnitPriceSpecification">
                                        <meta itemprop="priceCurrency" content="IRR">
                                        <span itemprop="price">100000000</span>
                                    </del>
                                    <span class="bg-secondary px-3 rounded-bl-none text-sm rounded-xl text-white ms-3" itemprop="discount">10%</span>
                                </div>
                                <span class="text-xl font-bold dark:text-white" itemprop="price" content="90000000">90,000,000 <span class="text-xs">تومان</span></span>
                            </div>
                        </div>
                    </footer>
                </article>

                <article class="bg-white product-box-item drop-shadow-md rounded-xl border border-gray-300 p-4 dark:bg-card-dark dark:border-white dark:border-1" itemscope="" itemtype="http://schema.org/Product">
                    <header class="flex items-center relative justify-between">
                        <div class="flex flex-col bg-white absolute top-1 start-0 p-1 rounded space-y-3  dark:bg-transparent">
                            <button class="hover:text-gray-400 relative group cursor-pointer" aria-label="افزودن به علاقه‌مندی‌ها">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 dark:text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"></path>
                                </svg>
                                <span class="absolute text-nowrap right-full mr-2 top-1/2 -translate-y-1/2 hidden group-hover:block bg-gray-900 text-white text-sm py-1 px-2 rounded-md shadow-lg">
                                    <span class="absolute left-full top-1/2 -translate-y-1/2 w-0 h-0 border-y-4 border-y-transparent border-l-4 border-l-gray-900"></span>
                                    افزودن به علاقه‌مندی‌ها
                                    </span>
                            </button>
                            <button class="hover:text-gray-400 cursor-pointer relative group" aria-label="مشاهده جزئیات">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 dark:text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"></path>
                                </svg>
                                <span class="absolute text-nowrap right-full mr-2 top-1/2 -translate-y-1/2 hidden group-hover:block bg-gray-900 text-white text-sm py-1 px-2 rounded-md shadow-lg">
                                    <span class="absolute left-full top-1/2 -translate-y-1/2 w-0 h-0 border-y-4 border-y-transparent border-l-4 border-l-gray-900"></span>
                                    مشاهده جزئیات
                                    </span>
                            </button>
                            <button class="hover:text-gray-400 cursor-pointer relative group" aria-label="مقایسه محصول">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 dark:text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21 3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"></path>
                                </svg>
                                <span class="absolute text-nowrap right-full mr-2 top-1/2 -translate-y-1/2 hidden group-hover:block bg-gray-900 text-white text-sm py-1 px-2 rounded-md shadow-lg">
                                    <span class="absolute left-full top-1/2 -translate-y-1/2 w-0 h-0 border-y-4 border-y-transparent border-l-4 border-l-gray-900"></span>
                                    مقایسه محصول
                                    </span>
                            </button>
                        </div>
                        <div class="flex flex-col bg-white absolute top-1 end-0 p-1 rounded dark:bg-transparent" itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
                            <meta itemprop="color" content="خاکستری">
                            <meta itemprop="color" content="زرد">
                            <meta itemprop="color" content="سبز">
                            <span class="size-4 rounded-full bg-gray-300" title="خاکستری"></span>
                            <span class="size-4 rounded-full bg-amber-300" title="زرد"></span>
                            <span class="size-4 rounded-full bg-green-300" title="سبز"></span>
                        </div>
                    </header>
                    <figure class="flex image justify-center my-4">
                        <a href="/product/iphone-13-pro-max" itemprop="url">
                            <img class="one-image" src="{{asset('assets/images/product/wach-1.png')}}" loading="lazy" alt="گوشی موبایل اپل آیفون 13 پرو مکس" itemprop="image">
                            <img class="two-image" src="{{asset('assets/images/product/wach-2.png')}}" loading="lazy" alt="نمای دیگر گوشی اپل آیفون 13 پرو مکس" itemprop="image">
                        </a>
                    </figure>
                    <h3 class="text-base leading-8  line-clamp-2 mb-2">
                        <a href="/product/iphone-13-pro-max" class="text-gray-800 dark:text-white" itemprop="name">گوشی موبایل
                            اپل مدل iPhone 13 Pro Max دو سیم کارت</a>
                    </h3>
                    <footer itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
                        <link itemprop="availability" href="http://schema.org/InStock">
                        <meta itemprop="priceCurrency" content="IRR">
                        <div class="flex items-center justify-between">
                            <button class="bg-primary-grad text-white p-3 rounded-lg transition" aria-label="افزودن به سبد خرید">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 dark:text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"></path>
                                </svg>
                            </button>
                            <div class="text-gray-700 flex flex-col items-center">
                                <div class="flex justify-between items-center">
                                    <del class="text-zinc-400" itemprop="priceSpecification" itemscope="" itemtype="http://schema.org/UnitPriceSpecification">
                                        <meta itemprop="priceCurrency" content="IRR">
                                        <span itemprop="price">100000000</span>
                                    </del>
                                    <span class="bg-secondary px-3 rounded-bl-none text-sm rounded-xl text-white ms-3" itemprop="discount">10%</span>
                                </div>
                                <span class="text-xl font-bold dark:text-white" itemprop="price" content="90000000">90,000,000 <span class="text-xs">تومان</span></span>
                            </div>
                        </div>
                    </footer>
                </article>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script src="{{asset('assets/js/plugin/chart-js/chart.js')}}"></script>
    <!-- chart -->
    <script>
        // Display the current date in Persian format
        document.getElementById('currentDate').textContent = new Date().toLocaleDateString('fa-IR');

        // General settings for Chart.js to support RTL
        Chart.defaults.font.family = 'payda, sans-serif';
        Chart.defaults.layout.padding = {
            right: 20
        };

        // Create the chart
        const ctx = document.getElementById('userActivityChart').getContext('2d');
        const userActivityChart = new Chart(ctx, {
            type: 'bar', // Chart type (bar, line, pie, etc.)
            data: {
                labels: ['دیدن محصول', 'اضافه کردن به سبد خرید', 'خرید', 'افزودن به علاقه مندی', 'نظر'],
                datasets: [{
                    label: 'فعالیت های یک هفته گذشته شما',
                    data: [12, 8, 5, 7, 3],
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.7)',
                        'rgba(16, 185, 129, 0.7)',
                        'rgba(245, 158, 11, 0.7)',
                        'rgba(239, 68, 68, 0.7)',
                        'rgba(139, 92, 246, 0.7)'
                    ],
                    borderColor: [
                        'rgba(59, 130, 246, 1)',
                        'rgba(16, 185, 129, 1)',
                        'rgba(245, 158, 11, 1)',
                        'rgba(239, 68, 68, 1)',
                        'rgba(139, 92, 246, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            boxWidth: 12,
                            padding: 20
                        }
                    },
                    title: {
                        display: true,
                        text: 'فعالیت های شما',
                        font: {
                            size: 16
                        }
                    },
                    tooltip: {
                        callbacks: {
                            title: function(context) {
                                return context[0].label;
                            },
                            label: function(context) {
                                return 'تعداد: ' + context.raw;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Function to change the chart type dynamically
        function changeChartType(type) {
            userActivityChart.config.type = type;
            userActivityChart.update();
        }

    </script>
@endpush
