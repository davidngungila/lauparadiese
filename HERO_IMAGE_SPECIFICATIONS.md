# Hero Image Specifications for Lau Paradise Adventures

## Overview
The hero section uses full viewport height (100vh) with background images that cover the entire viewport. Images are displayed using `background-size: cover` and `background-position: center`.

## Recommended Image Specifications

### Primary Dimensions (Standard)
- **Width:** 1920px
- **Height:** 1080px
- **Aspect Ratio:** 16:9
- **File Format:** JPEG (optimized) or WebP
- **File Size:** 200-500 KB (after optimization)
- **Resolution:** 72-96 DPI (web standard)

### High-Resolution Option (Retina Displays)
- **Width:** 3840px
- **Height:** 2160px
- **Aspect Ratio:** 16:9
- **File Format:** JPEG (optimized) or WebP
- **File Size:** 500 KB - 1 MB (after optimization)
- **Resolution:** 72-96 DPI (web standard)

## Why These Dimensions?

### 1920x1080 (Full HD)
- Covers 99% of desktop screens (most common resolution)
- Standard 16:9 aspect ratio works well for all devices
- Good balance between quality and file size
- Works perfectly with `background-size: cover`

### 3840x2160 (4K)
- For retina/high-DPI displays (MacBook Pro, high-end monitors)
- Provides crisp images on 2x and 3x displays
- Can be served conditionally for high-resolution devices

## Aspect Ratio Guidelines

**Recommended: 16:9 (1.78:1)**
- Works best for full-screen hero sections
- Compatible with most modern displays
- Prevents cropping issues on different screen sizes

**Alternative: 21:9 (2.33:1)**
- Ultra-wide format
- Good for cinematic effect
- May crop on standard displays

## File Format Recommendations

### JPEG
- **Best for:** Photographs with many colors
- **Compression:** 80-85% quality
- **Use when:** File size is a concern
- **File extension:** .jpg or .jpeg

### WebP
- **Best for:** Modern browsers (better compression)
- **Compression:** 80-85% quality
- **Use when:** You want smaller file sizes with same quality
- **File extension:** .webp
- **Note:** Provide JPEG fallback for older browsers

### PNG
- **Best for:** Images with transparency (not needed for hero backgrounds)
- **Not recommended** for hero images (larger file size)

## File Size Optimization

### Target File Sizes
- **1920x1080:** 200-400 KB (optimized)
- **3840x2160:** 500 KB - 1 MB (optimized)

### Optimization Tools
1. **TinyPNG / TinyJPG** - Online compression
2. **ImageOptim** - Mac app
3. **Squoosh** - Google's online tool
4. **Photoshop** - Save for Web feature
5. **GIMP** - Free alternative

### Compression Settings
- **JPEG Quality:** 80-85%
- **Progressive JPEG:** Enabled (for better loading experience)
- **Remove EXIF data:** Yes (reduces file size)

## Image Content Guidelines

### Composition Tips
1. **Focal Point:** Important content should be in the center or follow rule of thirds
2. **Text Overlay:** Leave space in center/upper area for text content
3. **Colors:** Ensure good contrast with white text overlay
4. **Subject:** Main subject should be clearly visible and not too dark

### What to Avoid
- ❌ Too much detail in edges (will be cropped on mobile)
- ❌ Important content in corners
- ❌ Very dark images (hard to read text overlay)
- ❌ Images with too much text (conflicts with overlay text)
- ❌ Low resolution images (will look pixelated)

## Responsive Considerations

### Desktop (1920px+)
- Full 1920x1080 image displayed
- May use 4K version for retina displays

### Tablet (768px - 1919px)
- 1920x1080 image will be cropped/zoomed
- Center area remains visible
- Top/bottom may be cropped

### Mobile (< 768px)
- Image will be cropped significantly
- Center portion remains visible
- Vertical orientation may show different crop

## Current Hero Images Location
```
public/images/hero-slider/
├── safari-adventure.jpg
├── kilimanjaro-climbing.jpg
└── zanzibar-beach.jpg
```

## Quick Checklist for New Hero Images

- [ ] Dimensions: 1920x1080 (or 3840x2160 for retina)
- [ ] Aspect Ratio: 16:9
- [ ] File Format: JPEG (optimized) or WebP
- [ ] File Size: Under 500 KB (for 1920x1080)
- [ ] Quality: 80-85% compression
- [ ] Focal Point: Center or rule of thirds
- [ ] Contrast: Good visibility with white text overlay
- [ ] Progressive: Enabled (for JPEG)
- [ ] EXIF Data: Removed

## Example Specifications

### Safari Adventure Image
- **Dimensions:** 1920x1080px
- **Format:** JPEG
- **File Size:** ~350 KB
- **Quality:** 85%
- **Content:** Wildlife scene with good center focus

### Kilimanjaro Climbing Image
- **Dimensions:** 1920x1080px
- **Format:** JPEG
- **File Size:** ~380 KB
- **Quality:** 85%
- **Content:** Mountain scene with clear sky area for text

### Zanzibar Beach Image
- **Dimensions:** 1920x1080px
- **Format:** JPEG
- **File Size:** ~320 KB
- **Quality:** 85%
- **Content:** Beach scene with good lighting

## Testing Recommendations

1. **Desktop Testing:** View on 1920x1080, 2560x1440, and 3840x2160 displays
2. **Mobile Testing:** Test on various mobile devices (portrait orientation)
3. **Tablet Testing:** Test on iPad and Android tablets
4. **Load Time:** Ensure images load within 2-3 seconds
5. **Quality Check:** Verify no pixelation or blurriness

## Tools for Image Preparation

### Free Tools
- **GIMP** - Full-featured image editor
- **Photopea** - Online Photoshop alternative
- **Squoosh** - Google's image compression tool
- **TinyPNG** - Online compression service

### Paid Tools
- **Adobe Photoshop** - Industry standard
- **Adobe Lightroom** - For photo editing
- **Affinity Photo** - Photoshop alternative

## CSS Implementation Notes

Current CSS uses:
```css
.slider-slide {
    background-size: cover;
    background-position: center;
    height: 100vh;
}
```

This means:
- Images will cover the entire viewport
- Center of image remains visible
- Edges may be cropped on different screen sizes
- Image maintains aspect ratio

## Summary

**Optimal Hero Image Specs:**
- **Size:** 1920x1080px (16:9)
- **Format:** JPEG (optimized) or WebP
- **File Size:** 200-500 KB
- **Quality:** 80-85%
- **Focal Point:** Center area
- **Contrast:** Good for text overlay




