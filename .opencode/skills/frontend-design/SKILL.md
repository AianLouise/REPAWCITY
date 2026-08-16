---
name: frontend-design
description: Use when designing or reviewing the overall visual direction, brand, layout, or look-and-feel of rePaw City screens. Covers the repaw color system, typography, spacing, imagery, and page composition. Also use when deciding the look of new pages, when making cross-cutting style changes, or when a UI feels "off" and needs to be brought back in line with the design language.
---

# rePaw City — Frontend Design Direction

This is the visual design language for the rePaw City shelter site. Every
screen should feel warm, friendly, hand-made, and welcoming — like a small
community shelter, not a corporate platform.

## 1. Brand personality

- **Warm & approachable** — soft earth tones, rounded corners, generous padding.
- **Trustworthy** — calm neutrals, clear hierarchy, honest photography of real pets.
- **Playful but not childish** — material icons and slight micro-interactions; no neon, no gradients everywhere, no dark-mode-only.
- Voice: warm but concise. Titles are short and emotionally resonant ("Meet your new best friend").

## 2. Color system (Tailwind `@theme`)

Defined in `frontend/src/index.css` — do not introduce hard-coded hex values in components.

| Token | Value | Usage |
|-------|-------|-------|
| `repaw-bg` | `#f5e6d3` | Main page background (warm cream) |
| `repaw-text` | `#6c4421` | Primary body text (deep brown) |
| `repaw-hover` | `#d6bca8` | Hover fills, borders, muted chips |
| `repaw-dark` | `#4a2c17` | Headings, dark nav/sidebar, primary text emphasis |
| `repaw-accent` | `#fad046` | Highlights: fully-booked days, active markers, unread badges |
| `repaw-danger` | `#c62828` | Errors, delete, cancel, "unavailable" |

Usage rules:
- Cards, tables, and modal surfaces use `bg-white/70` with `border-repaw-hover/40` and a soft `shadow-sm`.
- Primary buttons: `bg-repaw-text text-repaw-bg hover:bg-repaw-dark`.
- Secondary buttons: `bg-repaw-hover text-repaw-dark hover:bg-repaw-hover/70`.
- Focus rings: `focus:ring-2 focus:ring-repaw-text`.
- Status chips use semantic tints (green/amber/red) via `*-100` backgrounds + `*-800`/`*-700` text.

## 3. Typography

Loaded in `index.html` (Montserrat + Roboto + Material Symbols Rounded).

- **Headings / display:** `font-serif` (Montserrat) — page titles, section headings, card titles.
- **Body / UI:** `font-sans` (Roboto) — paragraphs, labels, tables, buttons.
- **Material icons:** `<span className="mui-icon">icon_name</span>` — never raw emoji or unicode arrows for UI icons.
- Sizes: page hero titles `text-4xl/5xl font-bold`; section headers `text-2xl/3xl font-serif font-bold`; card titles `text-xl`; labels `text-sm font-medium`; timestamps/meta `text-xs text-repaw-text/50`.

## 4. Shape & depth

- **Corners:** `rounded-3xl` for cards and modals; `rounded-2xl` for nested panels; `rounded-xl` for inputs/chips; `rounded-full` for pills and buttons.
- **Cards:** white/70 surface, `border border-repaw-hover/40`, `shadow-sm`, `hover:shadow-xl` with a subtle scale on images (`group-hover:scale-105`).
- **No drop shadows that feel heavy**; depth comes from borders + soft shadows.

## 5. Spacing & layout

- Page content constrained to `max-w-7xl` with `px-6 sm:px-8 lg:px-12`.
- Section rhythm: `pt-16 lg:pt-20 pb-20` on content sections; `space-y-8`/`space-y-6` between blocks.
- Generous padding inside cards (`p-8` default, `p-6` compact, `p-4` dense list rows).
- Grids: pet cards `grid-cols-1 sm:grid-cols-2 lg:grid-cols-3` with `gap-6`.

## 6. Imagery

- Real pet photos from `frontend/public/images/` (legacy assets) or uploaded storage images (`image_url`).
- Card grids use `thumb_url` (400px) for speed; profile/hero images use `image_url` (resized 1200px).
- Image containers: `aspect-square` for pet cards, `aspect-video` for news headlines, `object-cover`.
- Auth layout uses a rotating background slideshow (`/images/bg1.jpg` etc.) with a `bg-repaw-dark/40` overlay.

## 7. Page composition

- **Hero banner:** full-width section, warm gradient overlay
  (`from-repaw-bg/90 via-repaw-bg/70 to-repaw-accent/40`), centered `font-serif` headline.
- **Body sections:** white cards on `repaw-bg`; alternating with plain text sections.
- **Forms:** labels above inputs (`text-sm font-medium text-repaw-dark mb-1.5`), inputs `rounded-xl border-repaw-hover bg-repaw-bg`, inline field errors in `text-repaw-danger text-xs`.
- **Modals:** fixed overlay `bg-repaw-dark/40`, centered white `rounded-3xl` panel.

## 8. Tone & copy

- Buttons: UPPERCASE, `text-[15px] font-medium`, `tracking-wide` (e.g., "Book a Visit", "Apply to Adopt").
- Error/empty states: friendly and specific ("No notifications yet.", "This pet has found a forever home!").
- Confirmation flows use `confirm()` in the browser (consistent with the existing admin pattern).

## 9. Consistency checklist

Before shipping a screen:
- [ ] No hard-coded hex colors outside `index.css` — only `repaw-*` tokens.
- [ ] Headings use `font-serif`, body uses `font-sans`.
- [ ] Icons use `mui-icon` material symbols.
- [ ] Cards use the white/70 + border + shadow recipe.
- [ ] Buttons match the primary/secondary recipes.
- [ ] Spacing follows the `max-w-7xl` + section rhythm above.
