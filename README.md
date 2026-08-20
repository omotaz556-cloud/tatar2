# Arabic RTL fix — edited files (round 2: real "outside the container" fix)

Repo: https://github.com/omotaz556-cloud/tatar.git

## Files in this zip (paths are relative to the repo root)

- `GameEngine/config.php` — shared RTL-stylesheet-loading mechanism (from the
  previous round; unchanged this round).
- `css/rtl.css` — **rewritten this round.** Replaces the earlier float-flip
  approach (which never actually moved anything outside the container —
  that's what the screenshot showed) with a structural fix anchored to the
  real central container.
- `dorf1.php` — unchanged this round (already fixed in the previous pass).
- `changes.diff` — unified diff of all three files vs. the original repo.
  Apply with `git apply changes.diff` from the repo root.

---

## 1. The exact DOM hierarchy found

Every game page (`dorf1.php`, `dorf2.php`, `dorf3.php`, `karte.php`,
`karte2.php`, `build.php`, `berichte.php`, `nachrichten.php`, `allianz.php`,
`spieler.php` — confirmed individually) renders the identical structure:

```
<div class="wrapper">
  ...#header (sibling, outside #mid)...
  <div id="mid">                      <-- THE REAL CENTRAL CONTAINER
    <div id="side_navi">...</div>     <-- Templates/menu.tpl, 1st child
    <div id="content" class="...">    <-- 2nd child (village1/reports/player/...)
      ...village title / map / production / movement / troops / page content...
    </div>
    <div id="side_info">...</div>    <-- 3rd child (hero, quest, village list)
    <div class="clear"></div>
  </div>
  ...#footer (sibling, outside #mid)...
</div>
```

`#side_navi`, `#content`, and `#side_info` are **siblings** — direct
children of `#mid`, in that order. `#content` never wraps the other two.

## 2. Which parent/container was causing the problem

**`#mid` is the real fixed-width central container** — not `#content`.
This comes from `gpack/novaterra_classic/modules/new_layout_ltr.css`
(`@import`-ed by every `lang/en/lang.css`, loaded on every game page before
`css/rtl.css`):

```css
div#mid { float: none; height: auto; margin: 0 auto; width: 980px; }
```

`#mid` is a fixed 980px box, centered on the page. Since `#side_navi` and
`#side_info` are DOM children of `#mid`, no amount of `float` juggling on
them can ever move them outside `#mid`'s box — a float only changes which
edge of its **containing block** an element hugs; it can never place the
element outside that containing block. That's exactly why the previous
float-only pass (float:right on all three) produced no visible change:
`#side_navi`/`#side_info` stayed inside the same 980px box the whole time.

## 3. Which selectors/files were changed

All changes are in `css/rtl.css`, entirely under `html[dir="rtl"]`
selectors (verified — every rule in the file is scoped this way; nothing
touches unscoped/English selectors). Summary of the new rules:

```css
html[dir="rtl"] #mid {
    position: relative;   /* becomes the positioning context below */
    min-height: 453px;    /* reuses #side_navi/#side_info's own existing
                              453px height so #mid still reserves that
                              much vertical space now that they're out
                              of its normal flow */
}

html[dir="rtl"] #side_info {   /* Hero */
    position: absolute;
    top: 0;
    right: 100%;   /* flush against #mid's own LEFT edge */
    float: none;
    /* ...existing RTL text-align/padding rules unchanged... */
}

html[dir="rtl"] #side_navi {   /* Sidebar/nav */
    position: absolute;
    top: 0;
    left: 100%;    /* flush against #mid's own RIGHT edge */
    float: none;
}

html[dir="rtl"] #content {
    float: none;
    margin: 0 auto;   /* centers #content inside #mid's unchanged 980px box */
    text-align: right;
}
```

Nothing else in the file changed (village title offset, map/production
internal float swap, typography, table column alignment all operate
*inside* `#content` and are unaffected by how `#mid` positions its
children).

`GameEngine/config.php` and `dorf1.php` are unchanged from the previous
round — they already ensure `css/rtl.css` loads globally through
`tz_rtl_stylesheet_tag()` on every game page, so this structural fix
automatically applies everywhere too, with zero additional page edits.

## 4. How `#side_info` (Hero) is now positioned outside the central container

`#side_info` is taken out of `#mid`'s normal document flow with
`position: absolute`. Because `html[dir="rtl"] #mid` is now
`position: relative`, `#mid` becomes `#side_info`'s containing block.
`right: 100%` means "start exactly at 100% of the containing block's own
width from its right edge" — i.e. flush against `#mid`'s real left
boundary, wherever that is. This is not a guessed pixel value and not a
negative margin: it's computed from `#mid`'s own declared geometry, so it
stays correct even if `#mid`'s width ever changes. `top: 0` keeps the same
vertical starting position `#side_info` had as a float (no padding exists
on `#mid` itself). The hero image, its quest widget, and the village list
inside `#side_info` are untouched — only the container's own position
changed.

## 5. How `#side_navi` (Sidebar) is now positioned outside the central container

Same technique, mirrored: `position: absolute; left: 100%;` — flush
against `#mid`'s real right boundary. All existing RTL rules for the nav
links inside it (Home, Guide, Profile, Logout, Forum, Discord, Plus,
Support — padding/margin tweaks for RTL reading) are preserved unchanged;
only the container's own position and float were changed.

## 6. Confirmation that `#content` remains inside the central container

`#content` is left as the only element still in `#mid`'s normal document
flow (`float: none` instead of absolute — it never leaves `#mid`). It is
centered inside `#mid`'s box with `margin: 0 auto`, a standard block-
centering rule, not a width or position change to `#mid` itself.
`#content`'s own width is entirely untouched — it's still set per page by
its existing English class (`village1` 537px on dorf1.php, `village2` on
dorf2.php, `village3`/`reports`/`player`/`alliance`/`build`/etc. 502px on
the other pages) exactly as before. Village title, village map,
production, movement, and troops all still render inside `#content`,
inside `#mid`, with zero changes to their own markup, coordinates, or
sprites.

## 7. Confirmation that English/LTR was not touched

Every rule added or changed in `css/rtl.css` is scoped under
`html[dir="rtl"]` — verified programmatically (every selector block in
the file starts with `html[dir="rtl"]`). No English CSS file
(`compact.css`, `new_layout_ltr.css`, `novaterra.css`, etc.) was edited.
Since `<html>` only ever carries `dir="rtl"` when `tz_is_rtl_lang()` is
true (Arabic/Hebrew/Farsi/Urdu), none of these selectors can ever match on
an English page — `#mid`, `#content`, `#side_navi`, `#side_info` keep
their original `float:left` / static-position English behavior completely
unchanged there.

## Why this still doesn't break header/tabs/resource bar/map/sprites/JS

- `#header`, `#mtop`, `#res`, `#resWrap`, `#village_map_wrap`,
  `div#village_map` are explicitly forced back to
  `direction: ltr; unicode-bidi: isolate;` (unchanged from the previous
  round) and are also structurally outside `#mid` entirely (`#header`,
  `#res`) or inside `#content`'s untouched internal float pair
  (`#village_map`/`#map_details`), so none of this round's changes reach
  them.
- Resource-field icons (`img.rf1`..`img.rf18`) are positioned absolutely
  against `div#village_map`, which already has its own `position:relative`
  in the English CSS (`compact.css`) — a closer containing block than
  `#mid` for those icons, so `#mid` becoming `position:relative` has zero
  effect on their coordinates.
- `#side_navi` and `#side_info` themselves already had their own
  `position: relative` in the English CSS before this change, so any of
  *their* internal absolutely-positioned descendants (e.g. `#anm`,
  `#qge`) were never anchored to `#mid` in the first place and are
  unaffected by `#mid` becoming positioned.
- `#footer` sits outside `#mid` (sibling within `.wrapper`, positioned via
  `position:absolute; bottom:0`) and is unaffected.
- No JavaScript reads `#mid`, `#side_navi`, `#content`, or `#side_info`'s
  CSS `float`/`position` values, and no markup/IDs/classes were changed —
  only CSS positioning properties, so existing JS (map interactions,
  popups, timers) continues to work unmodified.
