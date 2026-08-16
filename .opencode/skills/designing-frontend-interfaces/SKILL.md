---
name: designing-frontend-interfaces
description: Use when building or refining UI components and screens in React + Tailwind for rePaw City and needing stronger interaction, layout, and visual craft. Covers state handling (loading/empty/error), hierarchy, accessibility, responsive behavior, micro-interactions, and code-level patterns that make interfaces feel polished.
---

# Designing Frontend Interfaces — UI Craft

Practical craft rules for React + Tailwind v4 interfaces. Apply these on top of
the visual direction in the `frontend-design` skill (colors, type, spacing).

## 1. Always design the states

Every async surface needs four states — never ship a screen that only handles the happy path:

- **Loading** — use the shared `<Loading />` spinner (`frontend/src/components/Shared.tsx`) on fetch pages; inline "Loading..." for tables/lists.
- **Empty** — friendly message + a next-step action ("No applications yet → Browse Pets"). Use the `Empty` component or an equivalent centered block.
- **Error** — inline red alert (`border-repaw-danger/40 bg-red-50 text-repaw-danger`) with a retry or back action. Never let a failed fetch render a blank screen.
- **Success / loaded** — the real content, with a success toast/banner where a mutation happened.

Pattern: `if (isLoading) return <Loading />` before any branch that dereferences data; guard `data` with `data && data.length > 0 ? ... : <empty>`.

## 2. Guard against null/undefined

- API responses are async: treat `data` as possibly undefined until a guard runs.
- Use optional chaining (`app.user?.name`) and fallbacks (`?? '—'`) for nested optional fields.
- For query params: `Number(id)` then `Number.isFinite(id) && id > 0` before enabling the query.
- In tests and components, never index into a possibly-empty array without a guard.

## 3. Hierarchy before decoration

- One clear primary action per card/page; style it `bg-repaw-text text-repaw-bg`.
- Secondary actions are muted `bg-repaw-hover`; destructive actions are `repaw-danger`.
- Don't stack two equal-weight buttons — if there are many actions, use a dropdown/menu.
- Titles lead, meta follows (smaller, `text-repaw-text/50`), actions last.

## 4. Interactions & feedback

- Buttons: disabled state is `disabled:opacity-60`; submitting shows text swap ("Submitting..."/"Saving...") — never leave a button inert without feedback.
- Destructive actions confirm first (`confirm('...')`) and show a busy state during the mutation.
- Links that open the booking wizard use `target="_blank"` (existing pattern).
- Favorites/heart toggles: `e.preventDefault(); e.stopPropagation()` when nested inside a `<Link>`/card so the click doesn't navigate.
- Hover affordances: `hover:bg-repaw-hover`, images `group-hover:scale-105 transition-transform`, cards `hover:shadow-xl`.
- Use `transition-colors duration-300` (buttons) / `transition-shadow duration-300` (cards) — keep it subtle.

## 5. Accessibility

- Every icon button gets an `aria-label` and `title` (e.g., FavoriteButton, delete buttons).
- Labels are associated with inputs via `htmlFor`/`id` (also makes `getByLabelText` work in tests).
- Form validation errors are linked next to the field and announced inline; mark required fields with `*`.
- Color is never the only signal — combine status color with a text label or icon.
- Focus visible: `focus:outline-none focus:ring-2 focus:ring-repaw-text`.
- Semantic elements: `<button>` for actions, `<Link>`/`<a>` for navigation, `<table>` for tabular data, `<details>`/`<summary>` for expandable content.

## 6. Responsive behavior

- Mobile-first: `grid-cols-1` → `sm:grid-cols-2` → `lg:grid-cols-3`.
- Navbars hide desktop links at `lg:hidden` and provide a mobile menu; admin sidebars collapse to a top bar.
- Tables wrap in `overflow-x-auto` with a `max-h-[...] overflow-y-auto` and a `sticky top-0` header when long.
- Modals: `fixed inset-0 ... p-4`, panel `w-full max-w-md`.
- Test on narrow widths by keeping padding generous (`px-6`) and letting grids reflow.

## 7. Code-level patterns

- **Hooks:** feature data flows through `src/hooks/` using TanStack Query (`useQuery`/`useMutation`) + `src/api/` modules. Mutations invalidate their query keys on success.
- **Forms:** React Hook Form + Zod for validated forms; manual state is fine for small forms.
- **Auth store:** Zustand (`useAuthStore`) with `persist`. Read `user.user_type` to branch admin vs regular.
- **Routing:** admin vs client are separate apps — never add a client page that assumes admin auth and vice versa.
- **Testing:** Vitest + RTL. Mock hooks via `vi.mock(...)`; prefer `getByRole`/`getByLabelText`; use `waitFor`/`findBy*` for async outcomes; `fireEvent` for simple changes.

## 8. Polish checklist

- [ ] All four states (loading/empty/error/loaded) handled.
- [ ] No null-deref risks; optional chains on nested data.
- [ ] One obvious primary action per view.
- [ ] Icon buttons have `aria-label` + `title`.
- [ ] Buttons show a busy state while submitting.
- [ ] Responsive at `sm` and `lg` breakpoints.
- [ ] New strings are consistent with existing friendly, UPPERCASE-button copy.
