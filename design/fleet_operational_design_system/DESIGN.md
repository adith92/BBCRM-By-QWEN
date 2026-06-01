---
name: Fleet Operational Design System
colors:
  surface: '#f8f9ff'
  surface-dim: '#cbdbf5'
  surface-bright: '#f8f9ff'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#eff4ff'
  surface-container: '#e5eeff'
  surface-container-high: '#dce9ff'
  surface-container-highest: '#d3e4fe'
  on-surface: '#0b1c30'
  on-surface-variant: '#434652'
  inverse-surface: '#213145'
  inverse-on-surface: '#eaf1ff'
  outline: '#737783'
  outline-variant: '#c3c6d4'
  surface-tint: '#2d5bb4'
  primary: '#003887'
  on-primary: '#ffffff'
  primary-container: '#1e4fa8'
  on-primary-container: '#b2c7ff'
  inverse-primary: '#b0c6ff'
  secondary: '#1960a6'
  on-secondary: '#ffffff'
  secondary-container: '#7ab3ff'
  on-secondary-container: '#00447e'
  tertiary: '#003c73'
  on-tertiary: '#ffffff'
  tertiary-container: '#24548d'
  on-tertiary-container: '#a7c9ff'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#d9e2ff'
  primary-fixed-dim: '#b0c6ff'
  on-primary-fixed: '#001945'
  on-primary-fixed-variant: '#04429b'
  secondary-fixed: '#d4e3ff'
  secondary-fixed-dim: '#a4c9ff'
  on-secondary-fixed: '#001c39'
  on-secondary-fixed-variant: '#004883'
  tertiary-fixed: '#d4e3ff'
  tertiary-fixed-dim: '#a5c8ff'
  on-tertiary-fixed: '#001c3a'
  on-tertiary-fixed-variant: '#124780'
  background: '#f8f9ff'
  on-background: '#0b1c30'
  surface-variant: '#d3e4fe'
typography:
  display-lg:
    fontFamily: Inter
    fontSize: 36px
    fontWeight: '700'
    lineHeight: 44px
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Inter
    fontSize: 28px
    fontWeight: '600'
    lineHeight: 36px
    letterSpacing: -0.01em
  headline-md:
    fontFamily: Inter
    fontSize: 20px
    fontWeight: '600'
    lineHeight: 28px
  title-lg:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '600'
    lineHeight: 24px
  body-md:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 20px
  body-sm:
    fontFamily: Inter
    fontSize: 13px
    fontWeight: '400'
    lineHeight: 18px
  label-md:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '500'
    lineHeight: 16px
    letterSpacing: 0.05em
  headline-lg-mobile:
    fontFamily: Inter
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  base: 4px
  xs: 4px
  sm: 8px
  md: 16px
  lg: 24px
  xl: 32px
  2xl: 48px
  gutter: 24px
  margin-mobile: 16px
  margin-desktop: 32px
---

## Brand & Style
The design system is engineered for high-density B2B fleet management, prioritizing operational efficiency and trust. The visual language follows a **Modern Corporate** aesthetic—blending the reliability of enterprise software with the streamlined usability of modern SaaS.

The interface centers on clarity and data-density without sacrificing legibility. We utilize significant white space to separate complex data modules, paired with a sophisticated blue-dominant palette that reflects the brand's heritage. The emotional response is one of professional control, precision, and architectural stability.

## Colors
The palette is built on a foundation of "Bluebird" blues to establish brand authority. 

- **Primary & Action**: `bluebird-500` is the primary action color. Use `bluebird-600` for hover states and `bluebird-700` for active or pressed states.
- **Surface & Backgrounds**: Use `bluebird-50` for large background areas and sidebar backgrounds to reduce eye strain compared to pure white. `bluebird-100` serves as a subtle border color for UI cards.
- **Status Indicators**: These colors are non-negotiable for fleet safety and logistics. 
  - **Available (Green)**: Signifies operational readiness.
  - **PO (Blue)**: Signifies administrative progression.
  - **Hold (Amber)**: Signifies temporary suspension or caution.
  - **Maintenance (Red)**: Signifies critical downtime or urgent repair.

## Typography
This design system utilizes **Inter** for its exceptional legibility in data-heavy environments. The scale is built on a 14px base to maximize information density while maintaining a professional hierarchy.

- **Headlines**: Use Semi-Bold (600) for section headers to provide strong visual anchoring.
- **Body**: The standard 14px (`body-md`) is used for all primary text and input fields.
- **Labels**: Use 12px Medium (500) with a slight letter spacing for table headers and small metadata.
- **Numeric Data**: Ensure `font-feature-settings: 'tnum'` (tabular figures) is enabled for tables and fleet ID numbers to ensure alignment.

## Layout & Spacing
The design system employs a **Fixed-Fluid Hybrid Grid**. Sidebars and navigation drawers are fixed width, while the main content area utilizes a 12-column fluid grid.

- **Grid System**: 12 columns on desktop (1440px+), 8 columns on tablet (768px+), and 4 columns on mobile.
- **Spacing Rhythm**: All margins and paddings must be multiples of 4px. Use `md` (16px) for standard component padding and `lg` (24px) for page gutters.
- **Container Strategy**: Main dashboard modules should be contained in cards with a `lg` (24px) gap between them to maintain the "Modern SaaS" aesthetic.

## Elevation & Depth
Depth is created through **Tonal Layering** and **Ambient Shadows**. This design system avoids heavy shadows to maintain a clean, enterprise feel.

- **Level 0 (Flat)**: Background surfaces using `bluebird-50`.
- **Level 1 (Default Card)**: White background with a 1px border of `bluebird-100` and a `shadow-sm` (0 1px 2px 0 rgba(0, 0, 0, 0.05)).
- **Level 2 (Hover/Active)**: Used for interactive cards or active menu items. Slightly elevated with a medium shadow (0 4px 6px -1px rgba(0, 0, 0, 0.1)).
- **Level 3 (Modals/Overlays)**: High elevation with a broad, soft shadow and a 20% opacity backdrop blur behind the element.

## Shapes
The design system uses a **Rounded** (12px / 0.75rem) corner strategy to soften the "industrial" nature of fleet management software, making it feel more modern and accessible.

- **Standard Elements**: Buttons, Input fields, and Small cards use 0.5rem (rounded-md).
- **Primary Containers**: Dashboard cards and main content wrappers use 0.75rem (rounded-xl).
- **Status Badges**: Use 1rem (rounded-full) for a pill-shaped appearance to distinguish them from interactive buttons.

## Components

### Buttons
- **Primary**: `bluebird-500` background, white text, 12px rounded corners.
- **Secondary**: Transparent background, `bluebird-500` border and text.
- **Ghost**: No border, `bluebird-600` text for low-priority actions.

### Status Badges
Badges are essential for fleet monitoring. They consist of a light background (10% opacity of the status color) and high-contrast text of the same color. 
- Example: *Available* badge = `#10B981` text on `#10B9811A` background.

### Input Fields
Inputs should have a 1px border of `bluebird-100`. On focus, the border transitions to `bluebird-500` with a 2px outer glow (ring) of `bluebird-100`.

### Data Tables
Tables are the core of this system. Use 14px Inter text. Row height should be fixed at 48px for standard views and 40px for "Compact" views. Headers should be `bluebird-50` background with `label-md` typography.

### Cards
All dashboard modules reside in cards. Cards must include a 16px padding and a 1px border of `bluebird-100` to define the boundary against the `bluebird-50` page background.