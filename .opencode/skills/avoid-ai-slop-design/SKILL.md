---
name: avoid-ai-slop-design
description: Use when designing, reviewing, or generating UI so it does not look like generic AI output. Trigger on words like "make it prettier", "modern design", "redesign", "freshen up", or whenever a screen looks templated, gradient-heavy, or generic. Gives concrete anti-patterns to reject and the project-specific alternatives to use instead.
---

# Avoid "AI Slop" Design

"AI slop" is the generic, instantly-recognizable look most AI generators produce:
samey gradients, floating glassy cards, icon-spam bullets, and vacuous copy.
This skill is a checklist of those tells and the rePaw City replacements.
Run through it before shipping any UI, and push back on any design that hits
more than one or two tells.

## The classic tells (reject these)

1. **Purple/pink/indigo gradients** — `from-violet-500 to-fuchsia-500`,
   `bg-gradient-to-br from-purple-600 via-pink-500 to-orange-400`. This is the
   #1 AI signature. rePaw City uses **warm earth tones**, never violet/pink.
2. **Glassmorphism everywhere** — `backdrop-blur`, semi-transparent white cards
   floating on dark blobs. rePaw City uses solid `bg-white/70` cards on a warm
   cream background, with soft borders, not glass.
3. **Emoji as icons** — 🐶🐱❤️✨ sprinkled through UI copy. rePaw City uses
   Material Symbols (`<span className="mui-icon">`). Never mix the two.
4. **Icon-per-bullet** — every list item preceded by a fresh icon. It reads as
   template-generated. Only icon the items that genuinely need emphasis.
5. **Identical feature-card trios** — three cards, each `icon + heading + blurb`,
   same size, same padding, no focal point. If three cards are truly equal,
   they're probably filler. Give one a stronger treatment or cut to two.
6. **Vague "wow" copy** — "Elevate your experience", "Unlock the power",
   "Seamlessly integrate", "Your journey starts here". Write concrete,
   specific sentences about shelter life instead.
7. **Stock-photo energy** — polished-but-empty hero images that don't match the
   organization. rePaw City uses real pet photos and legacy shelter imagery.
8. **Fake stats & avatars** — invented metrics ("500+ pets", "99% happy") and
   gray silhouette avatars. Only show numbers you can back.
9. **Over-animation** — every element has `transition`, `hover:scale`, and
   `animate-*`. Subtle hover on cards/images is enough; motion should have a
   reason.
10. **Samey centering** — everything centered, equal spacing, no hierarchy.
    Headlines lead, meta follows, one clear primary action.

## What to do instead (rePaw City recipes)

- **Colors**: use only the `repaw-*` tokens from `frontend/src/index.css`
  (`bg`, `text`, `hover`, `dark`, `accent`, `danger`). No hardcoded hex in
  components. Warm + calm beats colorful.
- **Type**: `font-serif` (Montserrat) for headings, `font-sans` (Roboto) for
  body/UI. One display font, one body font — no more.
- **Shape**: `rounded-3xl` cards, `rounded-full` pills/buttons, soft `shadow-sm`
  (never heavy glows).
- **Buttons**: one primary (`bg-repaw-text text-repaw-bg hover:bg-repaw-dark`)
  per view; secondary `bg-repaw-hover`; destructive `repaw-danger`.
- **Hierarchy**: pick ONE primary action. Titles > meta (`text-xs
  text-repaw-text/50`) > actions.
- **Content**: specific and warm. Real facts ("all pets seen by appointment
  only"), real addresses, real team names — the legacy content already does this.

## Review checklist

Before calling a screen done, confirm:

- [ ] No violet/pink/indigo gradients; warm earth tones only.
- [ ] No glassmorphism/backdrop-blur surfaces.
- [ ] No emoji; all icons are `mui-icon`.
- [ ] Not every bullet has its own icon.
- [ ] Cards/layout have a focal point, not three identical equal-weight blocks.
- [ ] Copy is concrete, not "elevate/unlock/seamless" filler.
- [ ] Images are real shelter/pet photos, not generic stock.
- [ ] No invented stats or gray avatar placeholders.
- [ ] Motion is subtle and purposeful (hover transitions only).
- [ ] Only `repaw-*` tokens; no hardcoded hex in components.
- [ ] One primary action per view; buttons use the standard recipes.

## If asked to "make it modern"

"Modern" is not the goal — **warm, trustworthy, and specific** is. When a user
asks to modernize, first audit for these tells, fix them, and only then consider
additive polish (better spacing, real photos, clearer hierarchy). If a proposed
redesign is trending toward any tell above, say so explicitly rather than
silently producing more slop.
