# Quick Start Guide

Welcome to WordPress Magic! This guide will help you get started with using and contributing components.

## 📖 For Users: Using Components

### Step 1: Find a Component
Browse the `components/` folder and find a component you want to use:
```
components/
├── cards/          # Card designs
├── buttons/        # Button styles
├── forms/          # Form elements
├── navigation/     # Navigation menus
└── sliders/        # Sliders and carousels
```

### Step 2: Copy the Code
Each component folder contains:
- `index.html` - The HTML structure
- `style.css` - The styles
- `script.js` - JavaScript (if needed)
- `README.md` - Documentation

### Step 3: Integrate into WordPress

#### Option A: Custom HTML Block (Easiest)
1. Edit your WordPress page/post
2. Add a "Custom HTML" block
3. Paste the HTML from `index.html`
4. Go to **Appearance → Customize → Additional CSS**
5. Paste the CSS from `style.css`

#### Option B: Theme Template (Advanced)
1. Open your child theme folder
2. Add the HTML to your template file (e.g., `page.php`)
3. Enqueue the CSS in `functions.php`:
```php
function my_component_styles() {
    wp_enqueue_style('my-component', get_stylesheet_directory_uri() . '/css/component.css');
}
add_action('wp_enqueue_scripts', 'my_component_styles');
```

#### Option C: Create a Shortcode
1. Add to your theme's `functions.php`:
```php
function my_component_shortcode() {
    ob_start();
    include get_stylesheet_directory() . '/components/my-component.php';
    return ob_get_clean();
}
add_shortcode('my_component', 'my_component_shortcode');
```
2. Use `[my_component]` in posts/pages

## 🎨 For Contributors: Adding Components

### Step 1: Choose a Category
Navigate to the appropriate folder in `components/` or create a new category.

### Step 2: Copy the Template
```bash
cp -r templates/component-template/ components/category/your-component-name/
```

### Step 3: Build Your Component
Edit the template files:
- `index.html` - Your component HTML
- `style.css` - Your component styles
- `script.js` - Your component JavaScript (optional)
- `README.md` - Usage documentation

### Step 4: Test Your Component
- Test on different screen sizes
- Check browser compatibility
- Verify accessibility
- Ensure clean code

### Step 5: Submit
1. Fork the repository
2. Create a branch: `git checkout -b add-your-component`
3. Commit: `git commit -m "Add your-component"`
4. Push: `git push origin add-your-component`
5. Create a Pull Request

## 📚 Example: Image Card Component

See `components/cards/image-card-overlay/` for a complete example:
- Beautiful hover effects
- Responsive design
- Gradient overlay
- Grid layout support

## 🔍 Need Help?

- Check the [main README](README.md) for detailed information
- Read [CONTRIBUTING.md](CONTRIBUTING.md) for contribution guidelines
- Look at existing components for examples
- Open an issue for questions

## 💡 Tips

1. **Keep it Simple**: One component = one purpose
2. **Make it Reusable**: Design for flexibility
3. **Document Well**: Help others use your work
4. **Test Thoroughly**: Check all edge cases
5. **Stay Consistent**: Follow existing patterns

## 🎯 Component Checklist

Before submitting:
- [ ] Component works independently
- [ ] Responsive on all devices
- [ ] Browser tested (Chrome, Firefox, Safari, Edge)
- [ ] Accessibility verified
- [ ] Code is clean and commented
- [ ] README documentation complete
- [ ] WordPress integration tested

---

**Happy coding! ✨**

For more details, see the complete [README](README.md) and [CONTRIBUTING](CONTRIBUTING.md) guides.
