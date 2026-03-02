# Theme & Assets Guide

This guide explains how to use the included theme and design files in the package.

## What's Included

The package includes a complete responsive admin theme with:

### Public Assets
- **CSS Files**: Compiled stylesheets for the admin theme
- **JavaScript Files**: Bootstrap, jQuery, and custom scripts
- **Fonts**: FontAwesome, Material Design Icons, Bootstrap Icons, etc.
- **Images**: Theme images, logos, and UI elements
- **Libraries**: Third-party libraries (DataTables, Chart.js, etc.)

### Source Files (Resources)
- **SCSS Files**: Source styling files with variables and customization options
- **JavaScript**: ES6 modules and custom page scripts
- **Fonts**: Font source files
- **Images**: Design images and assets

## Publishing Assets

### Option 1: Auto-Publish (Recommended)

Assets are automatically published when the package is installed via the service provider.

### Option 2: Manual Publish

```bash
# Publish public assets (CSS, JS, fonts, images)
php artisan vendor:publish --provider="Param\\RBAC\\ProjectSetupServiceProvider" --tag="project-setup-assets"

# Publish source resources (SCSS, fonts, images, JS sources)
php artisan vendor:publish --provider="Param\\RBAC\\ProjectSetupServiceProvider" --tag="project-setup-resources"

# Publish everything
php artisan vendor:publish --provider="Param\\RBAC\\ProjectSetupServiceProvider"
```

## Theme Structure

After publishing, files will be located at:

```
your-project/
├── public/
│   └── assets/
│       ├── css/                 # Compiled CSS files
│       ├── js/                  # Compiled JavaScript files
│       ├── fonts/               # Font files
│       ├── images/              # Theme images
│       └── libs/                # Third-party libraries
│
└── resources/
    ├── scss/
    │   ├── app.scss            # Main stylesheet
    │   ├── bootstrap.scss       # Bootstrap customization
    │   ├── custom/              # Custom team styles
    │   ├── icons.scss           # Icon styles
    │   ├── _variables.scss      # Light theme variables
    │   └── _variables-dark.scss # Dark theme variables
    ├── js/
    │   ├── app.js              # Main JavaScript file
    │   └── pages/              # Page-specific scripts
    ├── fonts/                  # Font source files
    └── images/                 # Design images
```

## Building Assets

To build the CSS and JavaScript:

```bash
# Development build with watch mode
npm run hot

# Production build
npm run production

# Development build (one-time)
npm run dev
```

### webpack.mix.js Configuration

The project uses Laravel Mix for asset compilation. The configuration handles:
- SCSS compilation with PostCSS
- JavaScript transpilation
- Asset versioning
- Source maps for development

## Theme Customization

### SCSS Customization

1. Edit SCSS files in `resources/scss/`
2. Variables are defined in `_variables.scss` and `_variables-dark.scss`
3. Custom styles go in `resources/scss/custom/`
4. Run `npm run dev` or `npm run hot` to compile

### Available Colors

The theme supports customizable colors through SCSS variables:

```scss
// Light theme
$primary-color: #007bff;
$secondary-color: #6c757d;
$success-color: #28a745;
$danger-color: #dc3545;
$warning-color: #ffc107;
$info-color: #17a2b8;

// Dark theme
$dark-primary: #4c7cfa;
$dark-secondary: #868e96;
// ... more dark theme colors
```

### Dark Mode Theme

The theme includes a built-in dark mode variant:

- Light theme: `resources/scss/_variables.scss`
- Dark theme: `resources/scss/_variables-dark.scss`
- Toggle via JavaScript in your app

## JavaScript & Bootstrap

### Bootstrap Version
The theme uses **Bootstrap 5.3.5** with customization:

```html
<!-- Bootstrap CSS (included) -->
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">

<!-- Custom theme CSS -->
<link rel="stylesheet" href="{{ asset('assets/css/app.min.css') }}">

<!-- Third-party libraries -->
<script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('assets/libs/chart-js/chart.min.js') }}"></script>

<!-- Main JavaScript -->
<script src="{{ asset('assets/js/app.min.js') }}"></script>
```

## Included Libraries

The package includes these common UI libraries:

| Library | Version | Purpose |
|---------|---------|---------|
| Bootstrap | 5.3.5 | CSS framework |
| jQuery | Latest | DOM manipulation |
| DataTables | 1.13.6 | Data tables |
| Chart.js | 4.4.5 | Charts |
| ApexCharts | 3.44.2 | Advanced charts |
| CKEditor | 5 | Rich text editor |
| Bootstrap DatePicker | 1.10.0 | Date selection |
| Tempusdominus | Latest | DateTime picker |
| Sweet Alert | Latest | Alerts/modals |
| Animate.css | Latest | CSS animations |

## Icon Fonts

The theme includes multiple icon libraries:

1. **FontAwesome 6** (Free version)
   - Usage: `<i class="fa fa-icon-name"></i>`
   - 1000+ icons available

2. **Material Design Icons**
   - Usage: `<span class="mdi mdi-icon-name"></span>`
   - 4000+ icons available

3. **Bootstrap Icons**
   - Usage: `<i class="bi bi-icon-name"></i>`
   - 1500+ icons available

4. **Dripicons**
   - Usage: `<i class="dripicon-icon-name"></i>`

5. **BoxIcons**
   - Usage: `<i class="bx bx-icon-name"></i>`

## Fonts

The theme includes multiple web-safe fonts:

```css
/* Available fonts */
$font-family-sans-serif: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
$font-family-monospace: SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', 'Courier New', monospace;

/* Imported fonts */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
```

## Responsive Design

The theme is fully responsive:

- **Mobile**: 320px and up
- **Tablet**: 768px and up
- **Desktop**: 1024px and up
- **Large Desktop**: 1200px and up
- **Extra Large**: 1400px and up

Breakpoints can be customized in `_variables.scss`:

```scss
$breakpoints: (
  'xs': 0,
  'sm': 576px,
  'md': 768px,
  'lg': 992px,
  'xl': 1200px,
  'xxl': 1400px
);
```

## CSS Utilities

The theme provides custom CSS utility classes:

```html
<!-- Colors -->
<div class="text-primary"></div>
<div class="bg-secondary"></div>

<!-- Spacing -->
<div class="p-4 m-2"></div>

<!-- Display -->
<div class="d-flex justify-content-between"></div>

<!-- Sizing -->
<div class="w-100 h-100"></div>
```

## Layout Components

Pre-built components included:

- Navbar (header)
- Sidebar navigation
- Cards
- Tables
- Forms
- Buttons
- Badges
- Breadcrumbs
- Pagination
- Alerts
- Modals
- Dropdowns

## RTL Support

The theme includes RTL (Right-to-Left) support for Arabic, Hebrew, Persian, etc.

Enable RTL:

```php
// In your controller or config
app()->setLocale('ar'); // Sets RTL direction
```

## Best Practices

1. **Keep source files organized**
   - Use custom folders in `resources/scss/custom/`
   - Create separate files for components

2. **Use CSS variables**
   - Define colors and spacing in `_variables.scss`
   - Avoid hardcoding values

3. **Optimize images**
   - Compress images before adding to `resources/images/`
   - Use web-friendly formats (PNG, WebP, SVG)

4. **Version assets**
   - Laravel Mix automatically versions assets
   - Reference via `asset()` helper

5. **Cache busting**
   - Always use `asset()` function
   - Never hardcode `/assets/` paths

## Troubleshooting

### Assets not loading?

1. Check file paths are correct
2. Run `npm run dev` to rebuild assets
3. Clear browser cache
4. Check `public_path` in `config/app.php`

### CSS not compiling?

```bash
# Clear and rebuild
rm -rf node_modules package-lock.json
npm install
npm run dev
```

### Dark mode not working?

1. Ensure `_variables-dark.scss` is imported
2. Check JavaScript dark mode toggle
3. Verify CSS is being applied

## References

- [Laravel Mix Documentation](https://laravel-mix.com/)
- [Bootstrap 5 Documentation](https://getbootstrap.com/docs/5.3/)
- [SCSS Documentation](https://sass-lang.com/documentation)
- [FontAwesome Icons](https://fontawesome.com/icons)
- [DataTables Documentation](https://datatables.net/)

---

All theme files are automatically included when installing the package. Customize as needed for your project! 🎨
