@php
  // Countries with their cities
  $countriesWithCities = [
    'ایران' => [
      'تهران' => 'تهران',
      'مشهد' => 'مشهد',
      'اصفهان' => 'اصفهان',
      'شیراز' => 'شیراز',
      'تبریز' => 'تبریز',
      'کرج' => 'کرج',
      'قم' => 'قم',
      'اهواز' => 'اهواز',
      'کرمانشاه' => 'کرمانشاه',
      'اردبیل' => 'اردبیل',
      'بندرعباس' => 'بندرعباس',
      'رشت' => 'رشت',
      'ارومیه' => 'ارومیه',
      'تیران' => 'تیران',
      'کاشان' => 'کاشان',
    ],
    'عراق' => [
      'بغداد' => 'بغداد',
      'بصره' => 'بصره',
      'کربلا' => 'کربلا',
      'نجف' => 'نجف',
      'اربیل' => 'اربیل',
      'کرکوک' => 'کرکوک',
      'دیوانیه' => 'دیوانیه',
      'ناصریه' => 'ناصریة',
      'موصل' => 'موصل',
    ],
    'امارات متحده عربی' => [
      'دبی' => 'دبی',
      'ابوظبی' => 'ابوظبی',
      'شارجه' => 'شارجہ',
      'عجمان' => 'عجمان',
      'رأس‌الخیمة' => 'رأس‌الخیمة',
    ],
    'سوریه' => [
      'دمشق' => 'دمشق',
      'حلب' => 'حلب',
      'حمص' => 'حمص',
      'لاذقیه' => 'لاذقیه',
      'طرطوس' => 'طرطوس',
    ],
    'ترکیه' => [
      'استانبول' => 'استانبول',
      'انقره' => 'انقره',
      'ازمیر' => 'ازمیر',
      'بورسا' => 'بورسا',
      'آنتالیا' => 'آنتالیا',
    ],
    'اردن' => [
      'عمّان' => 'عمّان',
      'زرقاء' => 'زرقاء',
      'اربد' => 'اربد',
    ],
    'لبنان' => [
      'بیروت' => 'بیروت',
      'طرابلس' => 'طرابلس',
      'صیدا' => 'صیدا',
    ],
    'عربستان سعودی' => [
      'ریاض' => 'ریاض',
      'جدہ' => 'جدہ',
      'مكة المكرمة' => 'مكة المكرمة',
      'المدینة' => 'المدینة',
      'الدمّام' => 'الدمّام',
    ],
    'آلمان' => [
      'برلین' => 'برلین',
      'مونیخ' => 'مونیخ',
      'کلن' => 'کلن',
      'فرانکفورت' => 'فرانکفورت',
    ],
    'بریتانیا' => [
      'لندن' => 'لندن',
      'منچستر' => 'منچستر',
      'برمنگام' => 'برمنگام',
      'لیورپول' => 'لیورپول',
    ],
    'فرانسه' => [
      'پاریس' => 'پاریس',
      'مارسی' => 'مارسی',
      'لیون' => 'لیون',
      'تولوز' => 'تولوز',
    ],
    'اسپانیا' => [
      'مادرید' => 'مادرید',
      'بارسلونا' => 'بارسلونا',
      'بالنسیا' => 'بالنسیا',
      'لشبونه' => 'لشبونه',
    ],
    'ایتالیا' => [
      'رم' => 'رم',
      'میلان' => 'میلان',
      'ونیز' => 'ونیز',
      'فلورانس' => 'فلورانس',
    ],
    'روسیه' => [
      'موسکو' => 'موسکو',
      'سن‌پترزبورگ' => 'سن‌پترزبورگ',
      'نووسیبیرسک' => 'نووسیبیرسک',
      'کازان' => 'کازان',
    ],
    'آمریکا' => [
      'نیویورک' => 'نیویورک',
      'لس‌آنجلس' => 'لس‌آنجلس',
      'شیکاگو' => 'شیکاگو',
      'ہیوستون' => 'ہیوستون',
      'فیلادلفیا' => 'فیلادلفیا',
    ],
    'کانادا' => [
      'تورنتو' => 'تورنتو',
      'ونکوور' => 'ونکوور',
      'مونترال' => 'مونترال',
      'کالگری' => 'کالگری',
    ],
    'مکزیک' => [
      'مکزیکوسیتی' => 'مکزیکوسیتی',
      'گوادالاخارا' => 'گوادالاخارا',
      'مونتری' => 'مونتری',
    ],
    'برزیل' => [
      'سائوپائولو' => 'سائوپائولو',
      'ریودژانیرو' => 'ریودژانیرو',
      'برازیلیا' => 'برازیلیا',
      'سالوادور' => 'سالوادور',
    ],
    'آرژانتین' => [
      'بوئنوسآیرس' => 'بوئنوسآیرس',
      'کوردوبا' => 'کوردوبا',
      'روزاریو' => 'روزاریو',
    ],
    'استرالیا' => [
      'سیدنی' => 'سیدنی',
      'ملبورن' => 'ملبورن',
      'بریزبن' => 'بریزبن',
      'پرتھ' => 'پرتھ',
    ],
    'ژاپن' => [
      'توکیو' => 'توکیو',
      'اوزاکا' => 'اوزاکا',
      'کیوتو' => 'کیوتو',
      'یوکوهاما' => 'یوکوهاما',
    ],
    'کوریای جنوبی' => [
      'سئول' => 'سئول',
      'بوسان' => 'بوسان',
      'تاگو' => 'تاگو',
      'انچن' => 'انچن',
    ],
    'چین' => [
      'شانگهای' => 'شانگهای',
      'پکن' => 'پکن',
      'شنژن' => 'شنژن',
      'گوانگژو' => 'گوانگژو',
    ],
    'هند' => [
      'دهلی' => 'دهلی',
      'بمبئی' => 'بمبئی',
      'کلکته' => 'کلکته',
      'بنگلور' => 'بنگلور',
    ],
    'تایلند' => [
      'بانکوک' => 'بانکوک',
      'چیانگ‌مای' => 'چیانگ‌مای',
      'پاتایا' => 'پاتایا',
    ],
    'مالزی' => [
      'کوالالامپور' => 'کوالالامپور',
      'جورج‌تاون' => 'جورج‌تاون',
      'پوتراجایا' => 'پوتراجایا',
    ],
    'اندونزی' => [
      'جاکارتا' => 'جاکارتا',
      'سورابایا' => 'سورابایا',
      'بندونگ' => 'بندونگ',
    ],
    'فیلیپین' => [
      'مانیل' => 'مانیل',
      'سبو' => 'سبو',
      'کویزون' => 'کویزون',
    ],
    'سنگاپور' => [
      'سنگاپور' => 'سنگاپور',
    ],
    'پاکستان' => [
      'کراچی' => 'کراچی',
      'لاہور' => 'لاہور',
      'اسلام‌آباد' => 'اسلام‌آباد',
      'فیصل‌آباد' => 'فیصل‌آباد',
    ],
    'بنگلادش' => [
      'ڈھاکا' => 'ڈھاکا',
      'چٹاگانگ' => 'چٹاگانگ',
      'خولنا' => 'خولنا',
    ],
    'هنگ‌کنگ' => [
      'هنگ‌کنگ' => 'هنگ‌کنگ',
    ],
    'تایوان' => [
      'تیپه' => 'تیپه',
      'کائوشیونگ' => 'کائوشیونگ',
      'تایچونگ' => 'تایچونگ',
    ],
    'ویتنام' => [
      'هانوی' => 'هانوی',
      'ہو‌چی‌مین' => 'ہو‌چی‌مین',
      'دانانگ' => 'دانانگ',
    ],
    'ایتالیا' => [
      'رم' => 'رم',
      'میلان' => 'میلان',
      'ونیز' => 'ونیز',
      'فلورانس' => 'فلورانس',
    ],
  ];

  // Timezone translations to Persian
  $timezoneTranslations = [
    'Asia/Tehran' => 'تهران (Iran)',
    'Asia/Baghdad' => 'بغداد (Iraq)',
    'Asia/Dubai' => 'دبی (UAE)',
    'Asia/Istanbul' => 'استانبول (Turkey)',
    'Europe/Berlin' => 'برلین (Germany)',
    'Europe/London' => 'لندن (UK)',
    'Europe/Paris' => 'پاریس (France)',
    'Europe/Madrid' => 'مادرید (Spain)',
    'Europe/Rome' => 'رم (Italy)',
    'Europe/Moscow' => 'موسکو (Russia)',
    'America/New_York' => 'نیویورک (USA)',
    'America/Chicago' => 'شیکاگو (USA)',
    'America/Denver' => 'ڈنور (USA)',
    'America/Los_Angeles' => 'لس‌آنجلس (USA)',
    'America/Toronto' => 'تورنتو (Canada)',
    'America/Vancouver' => 'ونکوور (Canada)',
    'America/Mexico_City' => 'مکزیکوسیتی (Mexico)',
    'America/Sao_Paulo' => 'سائوپائولو (Brazil)',
    'America/Buenos_Aires' => 'بوئنوسآیرس (Argentina)',
    'Africa/Cairo' => 'قاهره (Egypt)',
    'Africa/Johannesburg' => 'جوهانسبرگ (South Africa)',
    'Asia/Tokyo' => 'توکیو (Japan)',
    'Asia/Seoul' => 'سئول (South Korea)',
    'Asia/Hong_Kong' => 'هنگ‌کنگ',
    'Asia/Shanghai' => 'شانگهای (China)',
    'Asia/Bangkok' => 'بانکوک (Thailand)',
    'Asia/Singapore' => 'سنگاپور',
    'Asia/Kolkata' => 'کلکته (India)',
    'Australia/Sydney' => 'سیدنی (Australia)',
    'Australia/Melbourne' => 'ملبورن (Australia)',
    'Pacific/Auckland' => 'آکلند (New Zealand)',
  ];

  // Get all timezones from PHP
  $allTimezones = DateTimeZone::listIdentifiers();
@endphp

@extends('layouts.app')

@section('title', 'ثبت‌نام | نیروان فێرگە')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
  <div class="w-full max-w-lg">
    <div class="text-center mb-8">
      <h1 class="text-3xl font-ku font-bold text-brand-primary mb-2">نیروان فێرگە</h1>
      <p class="text-ui-muted text-sm">ایجاد حساب کاربری دانش‌آموز</p>
    </div>

    <x-ui.card class="p-6 sm:p-8">
      <form method="POST" action="{{ route('register.store') }}" class="space-y-5">
        @csrf

        <x-ui.input
          type="text"
          name="name"
          label="نام و نام خانوادگی"
          placeholder="نام خود را وارد کنید"
          value="{{ old('name') }}"
          :error="$errors->first('name')"
          required
          autofocus
        />

        <x-ui.select
          name="gender"
          label="جنسیت"
          :value="old('gender')"
          :error="$errors->first('gender')"
          :options="[
            '' => 'انتخاب کنید',
            'male' => 'مرد',
            'female' => 'زن',
            'other' => 'سایر'
          ]"
        />

        <x-ui.input
          type="text"
          name="birthdate"
          label="تاریخ تولد"
          placeholder="۱۴۰۳/۰۶/۰۴"
          value="{{ old('birthdate') }}"
          :error="$errors->first('birthdate')"
          class="js-jalali"
          data-jdp
        />

        <x-ui.input
          type="email"
          name="email"
          label="ایمیل"
          placeholder="example@email.com"
          value="{{ old('email') }}"
          :error="$errors->first('email')"
          required
        />

        <x-ui.input
          type="text"
          name="phone"
          label="شماره موبایل"
          placeholder="۰۹۱۲۳۴۵۶۷۸۹"
          value="{{ old('phone') }}"
          :error="$errors->first('phone')"
        />

        <x-ui.form-group label="کشور" :error="$errors->first('country')" required>
          <select
            id="country"
            name="country"
            class="w-full h-11 rounded-xl bg-ui-surface border border-ui-border px-3 text-sm font-semibold text-ui-text focus:outline-none focus:ring-2 focus:ring-brand-primary/30 select2-country"
            required
          >
            <option value="">کشور را انتخاب کنید</option>
            @foreach(array_keys($countriesWithCities) as $country)
              <option value="{{ $country }}" @selected(old('country', 'ایران') === $country)>
                {{ $country }}
              </option>
            @endforeach
          </select>
        </x-ui.form-group>

        <x-ui.form-group label="شهر" :error="$errors->first('city')" required>
          <select
            id="city"
            name="city"
            class="w-full h-11 rounded-xl bg-ui-surface border border-ui-border px-3 text-sm font-semibold text-ui-text focus:outline-none focus:ring-2 focus:ring-brand-primary/30 select2-city"
            required
          >
            <option value="">شهر را انتخاب کنید</option>
            @php
              $selectedCountry = old('country', 'ایران');
              $citiesForCountry = $countriesWithCities[$selectedCountry] ?? [];
            @endphp
            @foreach($citiesForCountry as $cityValue => $cityLabel)
              <option value="{{ $cityValue }}" @selected(old('city') === $cityValue)>
                {{ $cityLabel }}
              </option>
            @endforeach
          </select>
        </x-ui.form-group>

        <x-ui.form-group label="منطقه زمانی" :error="$errors->first('timezone')" required>
          <select
            id="timezone"
            name="timezone"
            class="w-full h-11 rounded-xl bg-ui-surface border border-ui-border px-3 text-sm font-semibold text-ui-text focus:outline-none focus:ring-2 focus:ring-brand-primary/30 select2-timezone"
            required
          >
            <option value="">منطقه زمانی را انتخاب کنید</option>
            @foreach($allTimezones as $timezone)
              @php
                $label = $timezoneTranslations[$timezone] ?? $timezone;
              @endphp
              <option value="{{ $timezone }}" @selected(old('timezone', 'Asia/Tehran') === $timezone)>
                {{ $label }}
              </option>
            @endforeach
          </select>
        </x-ui.form-group>

        <x-ui.input
          type="password"
          name="password"
          label="رمز عبور"
          placeholder="حداقل ۶ کاراکتر"
          :error="$errors->first('password')"
          required
        />

        <x-ui.input
          type="password"
          name="password_confirmation"
          label="تکرار رمز عبور"
          placeholder="رمز عبور را دوباره وارد کنید"
          :error="$errors->first('password_confirmation')"
          required
        />

        <x-ui.button type="submit" class="w-full">
          <x-ui.icon name="user-plus" class="h-5 w-5" />
          <span>ثبت‌نام</span>
        </x-ui.button>
      </form>

      <div class="mt-6 text-center">
        <p class="text-sm text-ui-muted">
          قبلاً ثبت‌نام کرده‌اید؟
          <a href="{{ route('login') }}" class="text-brand-secondary font-semibold hover:underline">ورود به حساب</a>
        </p>
      </div>
    </x-ui.card>
  </div>
</div>

@push('head')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
  <style>
    .select2-container--default .select2-selection--single {
      height: 44px !important;
      border-radius: 12px;
      border: 1px solid #ddd;
      background-color: #f5f5f5;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
      line-height: 44px;
      font-size: 14px;
      padding-left: 12px;
      padding-right: 40px;
      color: #333;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
      height: 42px;
      right: 6px;
      top: 1px;
    }
    .select2-dropdown {
      border-radius: 12px;
      border: 1px solid #ddd;
    }
    .select2-results__option {
      padding: 10px 12px;
    }
    .select2-search__field {
      font-size: 14px;
    }
  </style>
@endpush

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
  @php
    // Convert PHP array to JavaScript
    $countriesData = json_encode($countriesWithCities);
  @endphp
  
  const countriesCities = @json($countriesWithCities);

  // Initialize Select2 for country
  $('#country').select2({
    placeholder: 'کشور را انتخاب کنید',
    language: 'fa',
    allowClear: false,
    width: '100%',
    templateResult: formatCountry,
    templateSelection: formatCountry,
  });

  function formatCountry(state) {
    return state.text;
  }

  // Initialize Select2 for city
  $('#city').select2({
    placeholder: 'شهر را انتخاب کنید',
    language: 'fa',
    allowClear: false,
    width: '100%',
  });

  // Initialize Select2 for timezone
  $('#timezone').select2({
    placeholder: 'منطقه زمانی را انتخاب کنید',
    language: 'fa',
    allowClear: false,
    width: '100%',
  });

  // Update city options when country changes
  $('#country').on('change', function() {
    const selectedCountry = $(this).val();
    const citySelect = $('#city');
    
    // Clear current options
    citySelect.empty().append('<option value="">شهر را انتخاب کنید</option>');
    
    // Add cities for selected country
    if (countriesCities[selectedCountry]) {
      Object.entries(countriesCities[selectedCountry]).forEach(([value, label]) => {
        citySelect.append(`<option value="${value}">${label}</option>`);
      });
    }
    
    // Refresh Select2
    citySelect.val(null).trigger('change');
  });
</script>

@endsection