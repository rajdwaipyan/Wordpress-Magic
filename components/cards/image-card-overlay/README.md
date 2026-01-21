# Image Card with Overlay

A beautiful, responsive image card component with a smooth overlay effect. Perfect for portfolios, blog posts, team members, or product showcases.

## Preview

This card features:
- Smooth image zoom on hover
- Elegant text overlay with gradient background
- Responsive design that works on all devices
- Pure CSS animations (no JavaScript required)

## Features

- **Hover Animation**: Image smoothly zooms in on hover
- **Gradient Overlay**: Beautiful gradient overlay for better text readability
- **Fully Responsive**: Adapts to all screen sizes
- **Customizable**: Easy to modify colors, sizes, and effects
- **Lightweight**: Pure HTML/CSS, no dependencies

## Usage

### HTML Structure
```html
<div class="image-card">
    <img src="your-image.jpg" alt="Card Image" class="card-image">
    <div class="card-overlay">
        <h3 class="card-title">Card Title</h3>
        <p class="card-description">A brief description of the card content</p>
        <a href="#" class="card-link">Learn More →</a>
    </div>
</div>
```

### CSS Styling
Add the styles from `style.css` to your WordPress theme's stylesheet or use the Customizer's Additional CSS section.

### WordPress Integration

#### Method 1: Custom HTML Block
1. Add a "Custom HTML" block in the WordPress editor
2. Paste the HTML code
3. Add the CSS to Appearance → Customize → Additional CSS

#### Method 2: Theme Template
1. Add the HTML to your template file (e.g., `page.php`, `single.php`)
2. Enqueue the CSS in your theme's `functions.php`:
```php
function enqueue_image_card_styles() {
    wp_enqueue_style('image-card', get_stylesheet_directory_uri() . '/css/image-card.css');
}
add_action('wp_enqueue_scripts', 'enqueue_image_card_styles');
```

## Customization

### Colors
Modify these CSS variables in `style.css`:
- `--overlay-gradient`: The gradient overlay color
- `--text-color`: Text color on the overlay
- `--link-color`: Link color
- `--link-hover-color`: Link hover color

### Sizing
- `.image-card`: Change `max-width` to adjust card width
- `.card-image`: Adjust `height` for different aspect ratios
- `.card-overlay`: Modify padding for spacing

### Animation Speed
- Change `transition: 0.3s` values to adjust animation duration

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

## Dependencies

None! This component is built with pure HTML and CSS.

## Notes

- Use high-quality images (recommended: 800x600px or larger)
- Ensure text contrast meets accessibility standards
- Test on mobile devices for optimal viewing

## Credits

Created for WordPress Magic - Custom WordPress Components
