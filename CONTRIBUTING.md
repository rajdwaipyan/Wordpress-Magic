# Contributing to WordPress Magic ✨

Thank you for your interest in contributing to WordPress Magic! This guide will help you add your custom WordPress components to the repository.

## 🎯 What We're Looking For

We welcome contributions of:
- **UI Components**: Cards, buttons, forms, navigation, sliders, etc.
- **Interactive Elements**: Accordions, tabs, modals, tooltips
- **Layout Sections**: Headers, footers, hero sections, testimonials
- **Animation Effects**: Scroll effects, hover animations, transitions
- **Utility Components**: Loaders, progress bars, badges

## 📋 Component Requirements

Each component should:
1. ✅ Work independently (self-contained)
2. ✅ Be responsive and mobile-friendly
3. ✅ Follow accessibility best practices (WCAG)
4. ✅ Use clean, well-commented code
5. ✅ Include comprehensive documentation
6. ✅ Avoid heavy dependencies when possible
7. ✅ Be compatible with modern browsers

## 🚀 How to Contribute

### Step 1: Choose a Category

Navigate to the appropriate folder in `components/`:
- `cards/` - Card components
- `buttons/` - Button designs
- `forms/` - Form elements
- `navigation/` - Navigation components
- `sliders/` - Sliders and carousels

Don't see a category? Create a new one!

### Step 2: Create Your Component Folder

Create a new folder with a descriptive kebab-case name:
```
components/cards/your-component-name/
```

**Naming Guidelines:**
- Use lowercase with hyphens (kebab-case)
- Be descriptive: `animated-gradient-button` ✅ not `button1` ❌
- Include variant: `card-image-overlay-dark` ✅

### Step 3: Use the Component Template

Copy the template from `templates/component-template/`:
```bash
cp -r templates/component-template/* components/category/your-component-name/
```

### Step 4: Create Your Component Files

Your component folder should contain:

#### 📄 index.html
The HTML structure of your component.
```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Component - WordPress Magic</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Your component code -->
    <div class="your-component">
        <!-- Component structure -->
    </div>
    
    <script src="script.js"></script>
</body>
</html>
```

#### 🎨 style.css
All styles for your component.
```css
/* Use CSS variables for easy customization */
:root {
    --primary-color: #007bff;
    --secondary-color: #6c757d;
}

.your-component {
    /* Component styles */
}

/* Include responsive styles */
@media (max-width: 768px) {
    /* Mobile styles */
}
```

#### ⚡ script.js (if needed)
JavaScript for interactive functionality.
```javascript
document.addEventListener('DOMContentLoaded', function() {
    // Your component logic
});
```

#### 📖 README.md
Comprehensive documentation for your component.

**Required sections:**
- Brief description
- Features list
- Usage instructions
- Customization guide
- WordPress integration methods
- Browser support
- Dependencies (if any)

### Step 5: Test Your Component

Before submitting:
- ✅ Test on different screen sizes (mobile, tablet, desktop)
- ✅ Check in multiple browsers (Chrome, Firefox, Safari, Edge)
- ✅ Verify accessibility (keyboard navigation, screen readers)
- ✅ Ensure no console errors
- ✅ Test WordPress integration methods

### Step 6: Document Your Component

Create a detailed README.md with:
1. Component name and description
2. Preview/screenshot (if possible)
3. Feature list
4. Complete usage instructions
5. Customization options
6. WordPress integration examples
7. Browser compatibility
8. Any dependencies

See `components/cards/image-card-overlay/README.md` for a complete example.

### Step 7: Submit Your Contribution

1. Fork the repository
2. Create a new branch: `git checkout -b add-your-component-name`
3. Add your files: `git add components/category/your-component-name/`
4. Commit: `git commit -m "Add your-component-name"`
5. Push: `git push origin add-your-component-name`
6. Create a Pull Request

## 📝 Code Style Guidelines

### HTML
- Use semantic HTML5 elements
- Include proper ARIA attributes for accessibility
- Use meaningful class names
- Indent with 4 spaces

### CSS
- Use CSS custom properties (variables) for easy customization
- Follow BEM or similar naming convention
- Include responsive breakpoints
- Add vendor prefixes when necessary
- Group related properties

### JavaScript
- Use vanilla JavaScript when possible
- Add comments for complex logic
- Use modern ES6+ syntax
- Handle errors gracefully
- Add event listeners properly

## 🎨 Design Guidelines

- **Responsive First**: Design for mobile, then scale up
- **Accessibility**: Ensure WCAG 2.1 Level AA compliance
- **Performance**: Keep code lightweight and optimized
- **Browser Support**: Support modern browsers (last 2 versions)
- **WordPress Friendly**: Easy to integrate into WordPress themes

## 🔍 Component Checklist

Before submitting, ensure your component has:
- [ ] Clear, descriptive folder name
- [ ] Complete HTML structure in `index.html`
- [ ] All styles in `style.css`
- [ ] JavaScript (if needed) in `script.js`
- [ ] Comprehensive `README.md`
- [ ] Responsive design (mobile, tablet, desktop)
- [ ] Cross-browser compatibility
- [ ] Accessibility features
- [ ] Clean, commented code
- [ ] No external dependencies (or clearly documented)
- [ ] WordPress integration instructions

## 💡 Tips for Great Components

1. **Keep it Simple**: Focus on one thing and do it well
2. **Make it Reusable**: Design for various use cases
3. **Document Everything**: Clear docs = happy users
4. **Think WordPress**: Consider how it integrates with WordPress
5. **Test Thoroughly**: Check all edge cases
6. **Stay Consistent**: Follow existing patterns in the repo

## 🐛 Reporting Issues

Found a bug or have a suggestion?
1. Check existing issues first
2. Create a new issue with:
   - Clear title
   - Detailed description
   - Steps to reproduce (for bugs)
   - Expected vs actual behavior
   - Screenshots (if applicable)

## ❓ Questions?

- Open an issue for general questions
- Check existing components for examples
- Review the component template

## 📜 License

By contributing, you agree that your contributions will be licensed under the MIT License.

---

**Thank you for helping make WordPress Magic better!** 🙏✨
