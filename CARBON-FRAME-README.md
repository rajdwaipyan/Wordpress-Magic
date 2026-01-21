# Carbon Frame Style - Advanced 3D WordPress Styling

## Overview
The Carbon Frame style is an advanced CSS styling system designed for WordPress Elementor widgets. It features animated gradient borders, 3D transforms, hover effects, and futuristic grid overlays.

## Features

### 1. The Carbon Frame (Original Style + 10px Radius)
- Dark obsidian background (#0a0a0a)
- 10px border radius for smooth edges
- Perspective-based 3D container
- Smooth 1.5s cubic-bezier transitions

### 2. The Molten Heat Core
- Animated conic gradient border effect
- Rotating magma flow animation (8s duration)
- Primary color: #ff6b00 (orange)
- Hot accent color: #ffca00 (yellow)
- 8px blur for smooth glow effect

### 3. The "Dark Obsidian" Shield
- Inner gradient overlay (160deg linear gradient)
- 8px border radius (slightly smaller than outer frame)
- Creates depth and layering effect

### 4. The Internal HUD Grid
- Radial gradient center glow
- Repeating grid pattern overlay
- 3D transform support for child elements

### 5. Hover Effects (Supreme Ascension)
- Scale up to 1.03x
- Lift effect with -15px translateY
- 4deg rotateX for 3D tilt
- Enhanced shadow and glow
- Accelerated animation (4s duration)
- Image box 3D pop-forward effect
- Text and icon depth sync

### 6. Animation Engine
- `magmaFlow` keyframe animation
- Smooth 360deg rotation
- Infinite loop

## Installation

### Method 1: WordPress Customizer (Custom CSS)
1. Go to WordPress Admin → Appearance → Customize
2. Navigate to "Additional CSS"
3. Copy and paste the contents of `carbon-frame-style.css`
4. Click "Publish"

### Method 2: Elementor Custom CSS
1. Edit your page with Elementor
2. Click the Settings icon (bottom left)
3. Go to "Custom CSS" tab
4. Paste the CSS code
5. Update the page

### Method 3: Child Theme
1. Copy `carbon-frame-style.css` to your child theme folder
2. Add to your child theme's `functions.php`:
```php
function enqueue_carbon_frame_style() {
    wp_enqueue_style(
        'carbon-frame-style',
        get_stylesheet_directory_uri() . '/carbon-frame-style.css',
        array(),
        '1.0.0'
    );
}
add_action('wp_enqueue_scripts', 'enqueue_carbon_frame_style');
```

### Method 4: Plugin
1. Create a custom plugin or use a CSS plugin
2. Enqueue the stylesheet through the plugin

## Usage

### Apply to Elementor Widgets
1. Select any Elementor widget (Image Box, Icon Box, etc.)
2. Go to "Advanced" → "CSS Classes"
3. Add the class: `carbon-frame`
4. Update the page

### Apply to Custom Elements
Simply add the `carbon-frame` class to any HTML element:
```html
<div class="carbon-frame">
    <!-- Your content here -->
</div>
```

## CSS Variables
You can customize the colors by modifying these CSS variables:

```css
.carbon-frame {
    --magma-primary: #ff6b00;  /* Primary magma color */
    --magma-hot: #ffca00;      /* Hot accent color */
    --frame-bg: #0a0a0a;       /* Frame background */
}
```

## Customization Examples

### Change Colors
```css
.carbon-frame {
    --magma-primary: #00ff6b;  /* Green magma */
    --magma-hot: #00ffca;      /* Cyan accent */
}
```

### Adjust Animation Speed
```css
.carbon-frame::before {
    animation: magmaFlow 12s linear infinite !important; /* Slower */
}

.carbon-frame:hover::before {
    animation-duration: 6s !important; /* Slower hover */
}
```

### Modify Border Radius
```css
.carbon-frame {
    border-radius: 20px !important; /* More rounded */
}

.carbon-frame::after {
    border-radius: 18px !important; /* Adjust inner shield */
}
```

## Browser Compatibility
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

**Note**: 3D transforms and perspective effects work best on modern browsers with hardware acceleration enabled.

## Performance Tips
1. Use sparingly on pages with many elements
2. Consider disabling on mobile devices for better performance
3. Test on lower-end devices to ensure smooth animations
4. Use browser DevTools to monitor performance

## Troubleshooting

### Effects not showing
- Ensure the CSS is properly loaded (check browser DevTools → Network)
- Verify the `carbon-frame` class is applied to the element
- Check for CSS conflicts with other stylesheets

### Animation stuttering
- Enable hardware acceleration in browser settings
- Reduce the number of elements with the effect
- Consider simplifying the animation for mobile devices

### 3D effects not working
- Ensure the parent container has enough space
- Check that `overflow: hidden` on parent containers isn't clipping the effect
- Verify browser support for CSS 3D transforms

## Credits
Created for WordPress Magic - Custom Code for Wordpress Magical Development

## License
See LICENSE file in the repository root.
