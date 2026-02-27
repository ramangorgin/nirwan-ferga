@extends('layouts.dashboard.student')

@section('title', 'داشبورد | نیروان فێرگە')

@section('dashboard_content')
<div class="space-y-6">

  {{-- Header row --}}
  <div class="flex items-start justify-between gap-4">
    <div>
      <h1 class="text-2xl font-extrabold">خوش آمدید</h1>
      <p class="text-ui-muted mt-1">امروز روز خوبی برای یادگیری است.</p>
    </div>
  </div>

  {{-- Stats --}}
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <x-ui.card class="p-5">
      <div class="flex items-center justify-between">
        <div>
          <div class="text-sm text-ui-muted">درس‌های تکمیل‌شده</div>
          <div class="text-2xl font-extrabold mt-1">@faNum(24) / @faNum(30)</div>
        </div>
        <div class="h-10 w-10 rounded-xl bg-brand-primary/10 grid place-items-center text-brand-primary">
          <x-ui.icon name="circle-check" />
        </div>
      </div>
    </x-ui.card>

    <x-ui.card class="p-5">
      <div class="flex items-center justify-between">
        <div>
          <div class="text-sm text-ui-muted">ساعت مطالعه</div>
          <div class="text-2xl font-extrabold mt-1">@faNum(42) ساعت</div>
        </div>
        <div class="h-10 w-10 rounded-xl bg-brand-secondary/10 grid place-items-center text-brand-secondary">
          <x-ui.icon name="clock" />
        </div>
      </div>
    </x-ui.card>

    <x-ui.card class="p-5">
      <div class="flex items-center justify-between">
        <div>
          <div class="text-sm text-ui-muted">امتیاز کل</div>
          <div class="text-2xl font-extrabold mt-1">@faNum(1850)</div>
        </div>
        <div class="h-10 w-10 rounded-xl bg-brand-accent/10 grid place-items-center text-brand-accent">
          <x-ui.icon name="star" />
        </div>
      </div>
    </x-ui.card>
  </div>

  {{-- Main grid --}}
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

    {{-- Left column --}}
    <div class="space-y-4">

      <x-ui.card class="p-5">
        <div class="flex items-center justify-between">
          <div>
            <div class="text-sm text-ui-muted">کلاس آنلاین بعدی</div>
            <div class="font-extrabold mt-1">شروع در @faNum(10) دقیقه</div>
          </div>
          <div class="h-10 w-10 rounded-xl bg-ui-bg grid place-items-center border border-ui-border">
            <x-ui.icon name="video" />
          </div>
        </div>

        <div class="mt-4 grid grid-cols-3 gap-3">
          <div class="rounded-xl bg-ui-bg border border-ui-border p-3 text-center">
            <div class="text-xs text-ui-muted">روز</div>
            <div class="text-lg font-extrabold mt-1">@faNum(2)</div>
          </div>
          <div class="rounded-xl bg-ui-bg border border-ui-border p-3 text-center">
            <div class="text-xs text-ui-muted">ساعت</div>
            <div class="text-lg font-extrabold mt-1">@faNum(4)</div>
          </div>
          <div class="rounded-xl bg-ui-bg border border-ui-border p-3 text-center">
            <div class="text-xs text-ui-muted">دقیقه</div>
            <div class="text-lg font-extrabold mt-1">@faNum(15)</div>
          </div>
        </div>

        <div class="mt-4 flex gap-2">
          <a href="#" class="inline-flex items-center justify-center rounded-xl h-11 px-4 bg-brand-primary text-white font-semibold hover:opacity-90">
            ورود به کلاس
          </a>
          <a href="#" class="inline-flex items-center justify-center rounded-xl h-11 px-4 bg-ui-surface border border-ui-border font-semibold hover:bg-ui-bg">
            جزئیات
          </a>
        </div>
      </x-ui.card>

      <x-ui.card class="p-5">
        <div class="flex items-center justify-between">
          <div class="font-extrabold">تکالیف من</div>
          <a href="{{ route('class-sessions.index') }}" class="text-sm text-brand-primary font-semibold">مشاهده همه</a>
        </div>

        <div class="mt-4 space-y-2">
          <div class="rounded-xl border border-ui-border bg-ui-bg p-3">
            <div class="font-semibold">نوشتن مقاله</div>
            <div class="text-xs text-ui-muted mt-1">مهلت: فردا</div>
          </div>
          <div class="rounded-xl border border-ui-border bg-ui-bg p-3">
            <div class="font-semibold">تمرین شنیداری @faNum(3)</div>
            <div class="text-xs text-ui-muted mt-1">مهلت: دو روز دیگر</div>
          </div>
        </div>
      </x-ui.card>

    </div>

    {{-- Center/Right big card --}}
    <div class="lg:col-span-2 space-y-4">

      <x-ui.card class="p-0 overflow-hidden">
        <div class="h-44 bg-ui-bg border-b border-ui-border flex items-end p-5">
          <div>
            <div class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold bg-brand-primary/10 text-brand-primary">
              در حال یادگیری
            </div>
            <div class="text-2xl font-extrabold mt-3">مسیر یادگیری من</div>
            <div class="text-sm text-ui-muted mt-1">پیشرفت شما در دوره‌ها</div>
          </div>
        </div>

        <div class="p-5">
          <div class="flex items-center justify-between">
            <div class="font-extrabold">پیشرفت دوره</div>
            <div class="text-sm text-ui-muted">@faNum(75)%</div>
          </div>

          <div class="mt-3 h-2 rounded-full bg-ui-bg border border-ui-border overflow-hidden">
            <div class="h-full w-3/4 bg-brand-primary"></div>
          </div>

          <div class="mt-4 flex gap-2">
            <a href="{{ route('courses.index') }}"
               class="inline-flex items-center justify-center rounded-xl h-11 px-5 bg-brand-primary text-white font-semibold hover:opacity-90">
              ادامه یادگیری
            </a>
            <a href="#"
               class="inline-flex items-center justify-center rounded-xl h-11 px-5 bg-ui-surface border border-ui-border font-semibold hover:bg-ui-bg">
              جزئیات دوره
            </a>
          </div>
        </div>
      </x-ui.card>

      <x-ui.card class="p-5">
        <div class="flex items-center justify-between">
          <div class="font-extrabold">نمرات آزمون‌های اخیر</div>
          <a href="{{ route('student.quizzes.index') }}" class="text-sm text-brand-primary font-semibold">مشاهده همه</a>
        </div>

        <div class="mt-4 overflow-x-auto">
          <table class="w-full text-sm">
            <thead class="text-ui-muted">
              <tr class="border-b border-ui-border">
                <th class="text-right py-2">نام آزمون</th>
                <th class="text-right py-2">تاریخ</th>
                <th class="text-right py-2">وضعیت</th>
                <th class="text-right py-2">نمره</th>
              </tr>
            </thead>
            <tbody>
              <tr class="border-b border-ui-border">
                <td class="py-3 font-semibold">آزمونک @faNum(1)</td>
                <td class="py-3 text-ui-muted">{{ verta(now())->format('Y/m/d') }}</td>
                <td class="py-3">
                  <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">قبول</span>
                </td>
                <td class="py-3 font-extrabold">@faNum(95)/@faNum(100)</td>
              </tr>

              <tr class="border-b border-ui-border">
                <td class="py-3 font-semibold">آزمونک @faNum(2)</td>
                <td class="py-3 text-ui-muted">{{ verta(now()->subDays(2))->format('Y/m/d') }}</td>
                <td class="py-3">
                  <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">قبول</span>
                </td>
                <td class="py-3 font-extrabold">@faNum(88)/@faNum(100)</td>
              </tr>

              <tr>
                <td class="py-3 font-semibold">آزمونک @faNum(3)</td>
                <td class="py-3 text-ui-muted">{{ verta(now()->subDays(7))->format('Y/m/d') }}</td>
                <td class="py-3">
                  <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">نیاز به تلاش</span>
                </td>
                <td class="py-3 font-extrabold">@faNum(65)/@faNum(100)</td>
              </tr>
            </tbody>
          </table>
        </div>
      </x-ui.card>

    </div>
  </div>

</div>
@endsection