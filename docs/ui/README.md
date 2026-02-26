# Nirwan-ferga UI Kit (RTL)

This UI Kit is a Blade Components design system for the Nirwan-ferga project.
The public-facing UI language is Persian (RTL), while Kurdish Sorani is used in learning content.
Brand display name in UI must be Kurdish: **نێروان فێرگە**
Code/repo/domain name: **Nirwan-ferga**

## Goals
- Fast, consistent UI development for admin/teacher/student panels
- Reusable Blade components (`x-ui.*`)
- No CDN dependencies
- RTL-first spacing and layout
- Dark-mode ready (via `html.dark` class)
- Persian digits everywhere in UI (via `@faNum`)

## CSS Build (No Vite)
We compile Tailwind using Tailwind CLI:
- Source: `resources/css/input.css`
- Output: `public/assets/app.css`

Commands:
- Dev watch:
  npx tailwindcss -i ./resources/css/input.css -o ./public/assets/app.css --watch
- Build:
  npx tailwindcss -i ./resources/css/input.css -o ./public/assets/app.css --minify

Layout must include:
<link rel="stylesheet" href="{{ asset('assets/app.css') }}">

## Public Assets Structure
public/
  assets/app.css
  images/brand/logo.png
  fonts/fa-peyda/*.ttf
  fonts/ku-72_Sarchia_Qaisy/*.woff|ttf
  icons/lucide/*.svg   (local icon set)

## Design Tokens
Colors are defined as CSS variables in `resources/css/input.css`:
- Primary: #F59E0B
- Secondary: #2563EB
- Success: #22C55E
- Light background: #F8FAFC
- Dark background: #0F172A

Tailwind colors are mapped to variables:
- brand.primary / brand.secondary / brand.success
- ui.bg / ui.surface / ui.border / ui.text / ui.muted

## Typography
- Persian UI: Peyda (all weights)
- Kurdish UI content: 72_Sarchia_Qaisy

Default:
- body uses PersianUI
- Kurdish content uses `font-ku`

Tailwind font families (tailwind.config.js):
- font-fa -> PersianUI
- font-ku -> KurdishUI

## Persian Digits (Global Rule)
All UI numbers must be printed using the Blade directive:
@faNum($value)

Pagination views must also be converted.

## Icon System (No Emoji)
No emoji must be used in UI.
Use local SVG icons via:
<x-ui.icon name="settings" />
<x-ui.icon name="trash-2" class="h-5 w-5" />

## Icon System (Local, No CDN)

All icons must be local SVGs (no CDN) and used via the Blade component:
<x-ui.icon name="..." class="..." />

### Storage location
Icons are stored in:
public/icons/lucide/

Example:
public/icons/lucide/search.svg
public/icons/lucide/trash-2.svg

### Usage
<x-ui.icon name="search" class="h-5 w-5 text-ui-muted" />
<x-ui.icon name="x" class="h-5 w-5" />

### Rules
- Do NOT use emoji anywhere in UI.
- Use icons only where meaning or affordance is improved (buttons, actions, empty-state, upload, close, menu).
- Avoid decorative/extra icons.

Lucide icons are stored locally in:
public/icons/lucide/

## Component Naming
All components live under:
resources/views/components/ui/

Usage:
<x-ui.button>...</x-ui.button>
<x-ui.table>...</x-ui.table>

## Where to test
UI Playground:
resources/views/ui/playground.blade.php
Route:
GET /ui