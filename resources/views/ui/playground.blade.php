@extends('layouts.dashboard')

@section('page_title', 'UI Playground')
@section('page_subtitle', 'کیت رابط کاربری نیروان فێرگە — نسخه اولیه')

@section('dashboard_content')
<div class="space-y-8">

  {{-- PAGE HEADER + BREADCRUMB --}}
  <x-ui.section title="Header + Breadcrumb" subtitle="الگوی بالای صفحات CRUD">
    <x-ui.breadcrumb :items="[
      ['label' => 'داشبورد', 'href' => '#'],
      ['label' => 'مدیریت دوره‌ها', 'href' => '#'],
      ['label' => 'لیست دوره‌ها'],
    ]" />

    <div class="mt-4">
      <x-ui.page-header title="مدیریت دوره‌ها" subtitle="لیست دوره‌ها و عملیات‌ها">
        <x-slot:actions>
          <x-ui.button variant="ghost">اعمال فیلتر</x-ui.button>
          <x-ui.button variant="secondary">افزودن دوره</x-ui.button>
        </x-slot:actions>
      </x-ui.page-header>
    </div>
  </x-ui.section>

  {{-- BUTTONS --}}
  <x-ui.section title="Buttons" subtitle="primary / secondary / ghost">
    <div class="flex flex-wrap gap-3">
      <x-ui.button>دکمه اصلی</x-ui.button>
      <x-ui.button variant="secondary">دکمه ثانویه</x-ui.button>
      <x-ui.button variant="ghost">دکمه خنثی</x-ui.button>
    </div>
  </x-ui.section>

  {{-- BADGES + CHIPS + DOT --}}
  <x-ui.section title="Badges / Chips / Dot" subtitle="وضعیت‌ها و تگ‌ها">
    <div class="flex flex-wrap items-center gap-3">
      <x-ui.badge>اطلاعات</x-ui.badge>
      <x-ui.badge tone="success">موفق</x-ui.badge>
      <x-ui.badge tone="warning">هشدار</x-ui.badge>
      <x-ui.badge tone="danger">خطا</x-ui.badge>

      <span class="mx-2 h-6 w-px bg-ui-border"></span>

      <x-ui.chip>Neutral</x-ui.chip>
      <x-ui.chip tone="info"><x-ui.dot /> Info</x-ui.chip>
      <x-ui.chip tone="success"><x-ui.dot tone="success" /> Success</x-ui.chip>
      <x-ui.chip tone="warning"><x-ui.dot tone="warning" /> Warning</x-ui.chip>
      <x-ui.chip tone="danger"><x-ui.dot tone="danger" /> Danger</x-ui.chip>
    </div>
  </x-ui.section>

  {{-- ALERT + TOAST --}}
  <x-ui.section title="Alerts / Toast" subtitle="UI-only">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <div class="space-y-3">
        <x-ui.alert tone="info" title="اطلاعات">این یک پیام اطلاعاتی است.</x-ui.alert>
        <x-ui.alert tone="success" title="موفق">عملیات با موفقیت انجام شد.</x-ui.alert>
        <x-ui.alert tone="warning" title="هشدار">لطفاً فیلدهای ضروری را بررسی کنید.</x-ui.alert>
        <x-ui.alert tone="danger" title="خطا">مشکلی رخ داده است.</x-ui.alert>
      </div>

      <div class="space-y-3">
        <x-ui.toast tone="success" title="ذخیره شد" text="اطلاعات دوره با موفقیت ذخیره شد." />
        <x-ui.toast tone="info" title="اطلاع‌رسانی" text="یک اعلان جدید برای شما ثبت شد." />
        <x-ui.preloader text="در حال بارگذاری..." />
      </div>
    </div>
  </x-ui.section>

  {{-- TABS --}}
  <x-ui.section title="Tabs" subtitle="الگوی تب‌ها">
    <x-ui.tabs :items="['overview' => 'بررسی اجمالی', 'students' => 'دانش‌آموزان', 'homework' => 'تکالیف', 'files' => 'فایل‌ها']" active="overview" />
  </x-ui.section>

  {{-- FORM SYSTEM --}}
  <x-ui.section title="Forms" subtitle="Form Group + Input + Select + Textarea + Toggle + Checkbox + Radio">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

      <x-ui.card class="p-6 space-y-5">
        <x-ui.form-group label="عنوان دوره" hint="یک نام واضح و کوتاه انتخاب کنید." required>
          <x-ui.input placeholder="مثال: مکالمه سطح ۱" />
        </x-ui.form-group>

        <x-ui.select label="سطح" :options="['a1' => 'مبتدی', 'a2' => 'متوسط', 'b1' => 'پیشرفته']" value="a1" />

        <x-ui.form-group label="توضیحات" hint="برای معرفی دوره به دانشجو.">
          <x-ui.textarea placeholder="توضیحات دوره..." />
        </x-ui.form-group>

        <div class="flex items-center justify-between">
          <div class="text-sm font-bold">نمایش در سایت</div>
          <x-ui.toggle :checked="true" />
        </div>
      </x-ui.card>

      <x-ui.card class="p-6 space-y-5">
        <div class="space-y-2">
          <div class="text-sm font-bold">گزینه‌ها</div>
          <div class="flex flex-col gap-2">
            <x-ui.checkbox :checked="true">ارسال اعلان</x-ui.checkbox>
            <x-ui.checkbox>ثبت در تقویم</x-ui.checkbox>
          </div>
        </div>

        <div class="space-y-2">
          <div class="text-sm font-bold">نوع دوره</div>
          <div class="flex flex-col gap-2">
            <x-ui.radio name="type" value="group" :checked="true">گروهی</x-ui.radio>
            <x-ui.radio name="type" value="private">خصوصی</x-ui.radio>
          </div>
        </div>

        <x-ui.form-group label="نمونه خطا" error="این فیلد الزامی است.">
          <x-ui.input placeholder="فیلدی با خطا" />
        </x-ui.form-group>

        <x-ui.progress :value="68" />
      </x-ui.card>

    </div>
  </x-ui.section>

  {{-- STAT CARDS --}}
  <x-ui.section title="Dashboard Stats" subtitle="KPI cards">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <x-ui.stat-card title="درآمد ماهانه" value="@faNum('45200000')" hint="نسبت به ماه قبل +@faNum(8)%"
                      tone="success" />
      <x-ui.stat-card title="دوره‌های فعال" value="@faNum(124)" hint="نسبت به ماه قبل +@faNum(5)%"
                      tone="warning" />
      <x-ui.stat-card title="دانشجویان" value="@faNum('2450')" hint="نسبت به ماه قبل +@faNum(12)%"
                      tone="info" />
    </div>
  </x-ui.section>

  {{-- FILTERS + TABLE (professional) --}}
  <x-ui.section title="Filters + Table" subtitle="Selectable rows + bulk actions + actions menu">
    <x-ui.bulk-bar :count="3" class="mb-4">
      <x-ui.button variant="ghost">غیرفعال کردن</x-ui.button>
      <x-ui.button variant="ghost">خروجی گرفتن</x-ui.button>
      <x-ui.button>حذف</x-ui.button>
    </x-ui.bulk-bar>

    <x-ui.filters class="mb-4">
      <x-ui.input class="w-72" placeholder="جستجو..." />
      <x-ui.select class="w-44">
        <option>همه سطح‌ها</option>
        <option>مبتدی</option>
        <option>متوسط</option>
        <option>پیشرفته</option>
      </x-ui.select>
      <x-ui.select class="w-44">
        <option>همه دسته‌ها</option>
        <option>مکالمه</option>
        <option>گرامر</option>
      </x-ui.select>
      <x-ui.button variant="ghost">اعمال فیلترها</x-ui.button>
      <div class="flex-1"></div>
      <x-ui.button variant="secondary">افزودن دوره</x-ui.button>
    </x-ui.filters>

    <x-ui.table>
      <thead>
        <tr>
          <x-ui.th class="w-12"><x-ui.table-checkbox /></x-ui.th>
          <x-ui.th>عنوان دوره</x-ui.th>
          <x-ui.th>مدرس</x-ui.th>
          <x-ui.th>قیمت</x-ui.th>
          <x-ui.th>وضعیت</x-ui.th>
          <x-ui.th class="w-16">عملیات</x-ui.th>
        </tr>
      </thead>

      <tbody>
        <x-ui.tr :selected="true">
          <x-ui.td><x-ui.table-checkbox :checked="true" /></x-ui.td>
          <x-ui.td class="font-bold">مکالمه مقدماتی</x-ui.td>
          <x-ui.td>علی رضایی</x-ui.td>
          <x-ui.td>@faNum('200000')</x-ui.td>
          <x-ui.td><x-ui.badge tone="success">فعال</x-ui.badge></x-ui.td>
          <x-ui.td>
            <x-ui.row-actions :open="true">
              <x-ui.menu-item>ویرایش</x-ui.menu-item>
              <x-ui.menu-item>پیش‌نمایش</x-ui.menu-item>
              <x-ui.menu-item danger>حذف</x-ui.menu-item>
            </x-ui.row-actions>
          </x-ui.td>
        </x-ui.tr>

        <x-ui.tr>
          <x-ui.td><x-ui.table-checkbox /></x-ui.td>
          <x-ui.td class="font-bold">گرامر پیشرفته</x-ui.td>
          <x-ui.td>سارا احمدی</x-ui.td>
          <x-ui.td>@faNum('500000')</x-ui.td>
          <x-ui.td><x-ui.badge tone="warning">پیش‌نویس</x-ui.badge></x-ui.td>
          <x-ui.td>
            <x-ui.row-actions :open="true">
              <x-ui.menu-item>ویرایش</x-ui.menu-item>
              <x-ui.menu-item danger>حذف</x-ui.menu-item>
            </x-ui.row-actions>
          </x-ui.td>
        </x-ui.tr>
      </tbody>
    </x-ui.table>

    <div class="mt-4">
      <x-ui.pagination :current="1" :total="5" label="صفحه" />
    </div>
  </x-ui.section>

  {{-- CHAT --}}
  <x-ui.section title="Chat" subtitle="Inbox + Thread">
    <x-ui.chat-layout>
      <x-ui.chat-inbox>
        <x-ui.chat-item name="سارا محمدی" snippet="فایل تمرین جلسه سوم پیوست شد" time="۱۰:۳۰" :active="true" :unread="true" />
        <x-ui.chat-item name="علی رضایی" snippet="ممنون از راهنمایی شما استاد" time="۵ دقیقه پیش" />
        <x-ui.chat-item name="مریم کاظمی" snippet="لطفاً فایل صوتی را بررسی کنید" time="دیروز" />
      </x-ui.chat-inbox>

      <x-ui.chat-thread title="سارا محمدی" status="آنلاین">
        <x-ui.chat-bubble time="۱۰:۲۵">سلام استاد، تمرین‌های جلسه سوم رو انجام دادم و براتون ارسال می‌کنم.</x-ui.chat-bubble>
        <x-ui.chat-bubble me time="۱۰:۲۸">سلام سارا جان. حتماً، منتظرم فایل‌هات رو ببینم.</x-ui.chat-bubble>

        <div class="mt-4">
          <x-ui.file-item name="تمرین_جلسه_سوم.pdf" meta="PDF • @faNum('2.4') MB" />
        </div>
      </x-ui.chat-thread>
    </x-ui.chat-layout>
  </x-ui.section>

  {{-- FILE UPLOAD --}}
  <x-ui.section title="File Upload" subtitle="Dropzone + File list">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <x-ui.dropzone title="فایل را اینجا رها کنید" hint="PNG, JPG, PDF" />
      <div class="space-y-3">
        <x-ui.file-item name="course-cover.jpg" meta="JPG • @faNum('2.4') MB" />
        <x-ui.file-item name="تمرین_جلسه_سوم.pdf" meta="PDF • @faNum('1.8') MB" />
      </div>
    </div>
  </x-ui.section>

  {{-- STEPPER / WIZARD --}}
  <x-ui.section title="Stepper / Wizard" subtitle="الگوی ساخت آزمون">
    <x-ui.stepper :steps="['مشخصات آزمون', 'سوالات', 'تنظیمات نهایی']" :active="2" />
  </x-ui.section>

  {{-- MODAL + CONFIRM + DRAWER --}}
  <x-ui.section title="Modal / Confirm / Drawer" subtitle="UI-only">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <x-ui.modal :open="true" title="برنامه‌ریزی جلسه جدید">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <x-ui.input label="عنوان کلاس" placeholder="مثال: مکالمه سطح ۱" />
          <x-ui.select label="نوع کلاس" :options="['group' => 'گروهی - مبتدی', 'private' => 'خصوصی']" value="group" />
          <x-ui.input label="تاریخ" placeholder="۱۴۰۳/۰۶/۰۴" />
          <x-ui.input label="ساعت شروع" placeholder="۰۴:۰۰ PM" />
        </div>

        <x-slot:footer>
          <div class="flex items-center gap-3">
            <x-ui.button>ذخیره جلسه</x-ui.button>
            <x-ui.button variant="ghost">انصراف</x-ui.button>
          </div>
        </x-slot:footer>
      </x-ui.modal>

      <div class="space-y-4">
        <x-ui.confirm :open="true" title="حذف دوره" text="آیا از حذف این دوره مطمئن هستید؟ این عملیات قابل بازگشت نیست." />
        <x-ui.drawer :open="true" title="جزئیات دوره">
          <x-ui.datalist-item label="عنوان" value="مکالمه مقدماتی" />
          <x-ui.datalist-item label="سطح" value="مبتدی" />
          <x-ui.datalist-item label="دانشجویان" value="@faNum(24)" />

          <x-slot:footer>
            <div class="flex items-center gap-3">
              <x-ui.button variant="secondary">ویرایش</x-ui.button>
              <x-ui.button variant="ghost">بستن</x-ui.button>
            </div>
          </x-slot:footer>
        </x-ui.drawer>
      </div>
    </div>
  </x-ui.section>

  {{-- EMPTY + SKELETON --}}
  <x-ui.section title="Empty / Skeleton" subtitle="حالت‌های خالی و لودینگ">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <x-ui.card class="p-6 space-y-3">
        <x-ui.skeleton class="h-6 w-40" />
        <x-ui.skeleton class="h-4 w-full" />
        <x-ui.skeleton class="h-4 w-5/6" />
        <x-ui.skeleton class="h-10 w-44" />
      </x-ui.card>

      <x-ui.empty-state
        title="موردی یافت نشد"
        text="هنوز هیچ داده‌ای وجود ندارد. می‌توانید اولین مورد را ایجاد کنید."
        actionText="ایجاد مورد جدید"
      />
    </div>
  </x-ui.section>

</div>
@endsection