# UI Components Reference

## Layout
- layouts/app.blade.php
- layouts/dashboard.blade.php

## Basics
### Button
<x-ui.button>Primary</x-ui.button>
<x-ui.button variant="secondary">Secondary</x-ui.button>
<x-ui.button variant="ghost">Ghost</x-ui.button>

### Card
<x-ui.card class="p-6">...</x-ui.card>

### Input
<x-ui.input label="عنوان" placeholder="..." />
<x-ui.input :error="$errors->first('title')" />

### Form Group
<x-ui.form-group label="عنوان" hint="..." :error="$errors->first('title')">
  <x-ui.input />
</x-ui.form-group>

### Select
Option-mode:
<x-ui.select label="سطح" :options="['a1'=>'A1']" value="a1" />

Slot-mode:
<x-ui.select label="دسته">
  <option value="x">X</option>
</x-ui.select>

### Textarea
<x-ui.textarea />

### Toggle / Checkbox / Radio
<x-ui.toggle :checked="true" />
<x-ui.checkbox>...</x-ui.checkbox>
<x-ui.radio name="type" value="group">...</x-ui.radio>

## Tables
- table / th / td / tr
- table-checkbox
- row-actions + menu-item
- bulk-bar
- pagination (always use @faNum inside)

## Feedback
- alert
- toast (UI-only)
- skeleton
- empty-state
- progress

## Navigation
- tabs
- breadcrumb
- dropdown (UI-only)

## Overlays
- modal
- confirm
- drawer

## Chat & Files
- chat-layout / chat-inbox / chat-item / chat-thread / chat-bubble
- dropzone / file-item

## Icons

Component:
<x-ui.icon name="icon-name" class="h-5 w-5" />

Examples:
<x-ui.icon name="search" class="h-5 w-5 text-ui-muted" />
<x-ui.icon name="plus" class="h-5 w-5 text-white" />

Common icon names used in UI kit:
- x
- search
- plus
- chevron-left
- chevron-right
- more-horizontal
- upload
- file
- download
- trash-2
- edit-3
- send
- paperclip
- inbox
- phone
- video