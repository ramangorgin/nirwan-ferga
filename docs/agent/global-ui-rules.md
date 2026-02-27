# Global UI Rules — Nirwan-ferga (Copilot Agent Spec)

You are GitHub Copilot Agent working inside the Nirwan-ferga Laravel project (VSCode).

## Brand / Language
- Public UI language: Persian (RTL)
- Kurdish content: Sorani (for learning content: questions/answers/homework)
- Brand display name shown to users: "نیروان فێرگە"
- Code/repo/domain name: "Nirwan-ferga"
- In Persian UI text, always use the Kurdish brand display name (نیروان فێرگە), not the Persian transliteration.

## Layout & Folders
- Views are role-based:
  - resources/views/admin/*
  - resources/views/teacher/*
  - resources/views/student/*
- Layout structure:
  - resources/views/layouts/app.blade.php            (base)
  - resources/views/layouts/dashboard/admin.blade.php
  - resources/views/layouts/dashboard/teacher.blade.php
  - resources/views/layouts/dashboard/student.blade.php
- Each dashboard layout extends `layouts.app`.

## UI Kit Components
Use Blade components under:
resources/views/components/ui/*

Preferred usage:
- <x-ui.section>, <x-ui.card>, <x-ui.page-header>, <x-ui.breadcrumb>
- Forms: <x-ui.form-layout>, <x-ui.form-group>, <x-ui.input>, <x-ui.select>, <x-ui.textarea>, <x-ui.toggle>, <x-ui.checkbox>, <x-ui.radio>
- Tables: <x-ui.table>, <x-ui.th>, <x-ui.td>, <x-ui.tr>, <x-ui.pagination>, <x-ui.row-actions>, <x-ui.menu-item>, <x-ui.bulk-bar>, <x-ui.table-checkbox>
- Feedback: <x-ui.alert>, toastr usage (see JS rules)
- Overlays: <x-ui.modal>, <x-ui.drawer>, <x-ui.confirm> (UI)
- Icons: <x-ui.icon name="..." />
- NEVER use emojis.

## Digits & Numbers (Critical)
All numbers printed in UI must be Persian digits.
Use the project helper:
- @faNum($value)

Apply to:
- IDs, counts, prices, totals
- table values
- pagination labels/numbers
- badges that contain numbers
- any numeric text shown to users

## Dates & Jalali (Critical)
- Any date shown to users must be Jalali using `verta()` (or the project's chosen Jalali formatter).
Examples:
- {{ verta($model->created_at)->format('Y/m/d') }}
- {{ verta($date)->format('Y/m/d H:i') }}

- Any date input in forms must use Jalali Date Picker:
  - Add attribute: data-jdp
  - Use Persian placeholder examples: "۱۴۰۳/۰۶/۰۴"
  - Store submitted dates according to backend expectations (do NOT transform in Blade; backend handles storage)

## Confirmations (SweetAlert2, Persian)
All destructive or important actions requiring confirmation must use global confirm binding:
- Add `data-confirm` to the clickable element or submit button
- Provide Persian texts via attributes:
  - data-confirm-title
  - data-confirm-text
  - data-confirm-yes
  - data-confirm-no

Example:
<button type="submit"
  data-confirm
  data-confirm-title="حذف"
  data-confirm-text="آیا از حذف این مورد مطمئن هستید؟"
  data-confirm-yes="بله، حذف شود"
  data-confirm-no="انصراف">
  حذف
</button>

Do NOT implement page-specific confirm logic unless explicitly required.

## Toast Notifications (Toastr)
- Use toastr for post-action feedback (success/error/info/warning).
- Prefer server session flashes.
Pattern:
@if(session('success')) <script>toastr.success(@json(session('success')))</script> @endif

## CKEditor
- Use CKEditor only where rich text is needed (announcements, long descriptions, content fields).
- Activate on textarea with:
  - data-ckeditor

## Livewire + Alpine
- Use Livewire for interactive parts when appropriate (filters, modals, dynamic forms).
- Use Alpine for small UI interactions (toggle panels, dropdowns, tabs) if no Livewire needed.
- Avoid mixing heavy JS state with Livewire unless necessary.
- If Livewire DOM updates a section, re-init datepicker/ckeditor/toastr hooks through the global app.js hooks.

## Icon Usage Rules (No Overuse)
- Use icons only for action affordances:
  - Close (x), more menu (more-horizontal), upload, download, trash, edit, send, attachment, inbox empty state
- Avoid decorative icons.

## Code Quality
- Keep Blade clean, modular, readable.
- Use English comments where needed (short and simple).
- Avoid duplicated markup: use partials/components.
- Follow backend naming: do not invent routes/controllers/fields.
- Always read relevant backend files before coding a screen.