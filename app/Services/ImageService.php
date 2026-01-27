<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageService
{
    protected $manager;

    public function __construct()
    {
        // Try to use Intervention Image if available, otherwise fallback to GD
        if (class_exists(ImageManager::class)) {
            $this->manager = new ImageManager(new Driver());
        }
    }

    /**
     * Validate image resolution
     * 
     * @param UploadedFile $file
     * @param int $minWidth
     * @param int $minHeight
     * @return array ['valid' => bool, 'width' => int, 'height' => int, 'message' => string]
     */
    public function validateResolution(UploadedFile $file, int $minWidth = 1280, int $minHeight = 720): array
    {
        $imageInfo = getimagesize($file->getRealPath());
        if (!$imageInfo) {
            return ['valid' => false, 'width' => 0, 'height' => 0, 'message' => 'Invalid image file'];
        }

        $width = $imageInfo[0];
        $height = $imageInfo[1];

        if ($width < $minWidth || $height < $minHeight) {
            return [
                'valid' => false,
                'width' => $width,
                'height' => $height,
                'message' => "Image resolution too small. Minimum required: {$minWidth}x{$minHeight}, got: {$width}x{$height}"
            ];
        }

        return [
            'valid' => true,
            'width' => $width,
            'height' => $height,
            'message' => 'Resolution OK'
        ];
    }

    /**
     * Remove EXIF data from image
     * 
     * @param string $imagePath
     * @return string Path to cleaned image
     */
    public function removeExifData(string $imagePath): string
    {
        if (!file_exists($imagePath)) {
            throw new \Exception('Image file not found');
        }

        $imageInfo = getimagesize($imagePath);
        if (!$imageInfo) {
            throw new \Exception('Invalid image file');
        }

        $sourceImage = match ($imageInfo[2]) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($imagePath),
            IMAGETYPE_PNG => imagecreatefrompng($imagePath),
            IMAGETYPE_GIF => imagecreatefromgif($imagePath),
            IMAGETYPE_WEBP => imagecreatefromwebp($imagePath),
            default => throw new \Exception('Unsupported image type'),
        };

        $tempPath = storage_path('app/temp_' . uniqid() . '_' . time() . '.jpg');
        
        // Save without EXIF
        imagejpeg($sourceImage, $tempPath, 100);
        imagedestroy($sourceImage);

        return $tempPath;
    }

    /**
     * Generate thumbnails in multiple sizes
     * 
     * @param string $imagePath Original image path
     * @param string $storagePath Base storage path
     * @return array ['150' => path, '300' => path, '600' => path, 'hd' => path]
     */
    public function generateThumbnails(string $imagePath, string $storagePath): array
    {
        $thumbnails = [];
        $sizes = [
            '150' => 150,
            '300' => 300,
            '600' => 600,
            'hd' => 1920
        ];

        $imageInfo = getimagesize($imagePath);
        if (!$imageInfo) {
            return $thumbnails;
        }

        $originalWidth = $imageInfo[0];
        $originalHeight = $imageInfo[1];

        foreach ($sizes as $key => $maxSize) {
            // Calculate dimensions maintaining aspect ratio
            if ($originalWidth > $originalHeight) {
                $newWidth = $maxSize;
                $newHeight = (int)($originalHeight * ($maxSize / $originalWidth));
            } else {
                $newHeight = $maxSize;
                $newWidth = (int)($originalWidth * ($maxSize / $originalHeight));
            }

            // Don't upscale
            if ($newWidth > $originalWidth || $newHeight > $originalHeight) {
                $newWidth = $originalWidth;
                $newHeight = $originalHeight;
            }

            $sourceImage = match ($imageInfo[2]) {
                IMAGETYPE_JPEG => imagecreatefromjpeg($imagePath),
                IMAGETYPE_PNG => imagecreatefrompng($imagePath),
                IMAGETYPE_GIF => imagecreatefromgif($imagePath),
                IMAGETYPE_WEBP => imagecreatefromwebp($imagePath),
                default => null,
            };

            if (!$sourceImage) {
                continue;
            }

            $thumbnail = imagecreatetruecolor($newWidth, $newHeight);
            
            // Preserve transparency
            if ($imageInfo[2] == IMAGETYPE_PNG || $imageInfo[2] == IMAGETYPE_GIF) {
                imagealphablending($thumbnail, false);
                imagesavealpha($thumbnail, true);
                $transparent = imagecolorallocatealpha($thumbnail, 255, 255, 255, 127);
                imagefill($thumbnail, 0, 0, $transparent);
            }

            imagecopyresampled($thumbnail, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

            $thumbPath = str_replace(basename($storagePath), "thumb_{$key}_" . basename($storagePath), $storagePath);
            $thumbPath = preg_replace('/\.(jpg|jpeg|png|gif|webp)$/i', '.webp', $thumbPath);

            $tempPath = storage_path('app/temp_' . uniqid() . '.webp');
            imagewebp($thumbnail, $tempPath, 85);
            
            Storage::disk('public')->put($thumbPath, file_get_contents($tempPath));
            unlink($tempPath);

            $thumbnails[$key] = $thumbPath;
            
            imagedestroy($sourceImage);
            imagedestroy($thumbnail);
        }

        return $thumbnails;
    }

    /**
     * Optimize and store image with all advanced features
     * 
     * @param UploadedFile $file
     * @param string $path Storage path
     * @param array $options ['quality' => int, 'maxWidth' => int, 'removeExif' => bool, 'convertWebP' => bool, 'generateThumbnails' => bool]
     * @return array ['original' => path, 'webp' => path|null, 'thumbnails' => array, 'metadata' => array]
     */
    public function optimizeAndStore(UploadedFile $file, string $path = 'gallery', array $options = []): array
    {
        $options = array_merge([
            'quality' => 80,
            'maxWidth' => 2560,
            'removeExif' => true,
            'convertWebP' => true,
            'generateThumbnails' => true,
            'minWidth' => 1280,
            'minHeight' => 720,
        ], $options);

        // Validate resolution (skip if minWidth/minHeight are 0)
        if ($options['minWidth'] > 0 || $options['minHeight'] > 0) {
            $validation = $this->validateResolution($file, $options['minWidth'], $options['minHeight']);
            if (!$validation['valid']) {
                throw new \Exception($validation['message']);
            }
        }

        $imagePath = $file->getRealPath();
        
        // Remove EXIF if requested
        if ($options['removeExif']) {
            try {
                $imagePath = $this->removeExifData($imagePath);
            } catch (\Exception $e) {
                // Continue with original if EXIF removal fails
            }
        }

        $imageInfo = getimagesize($imagePath);
        $originalWidth = $imageInfo[0];
        $originalHeight = $imageInfo[1];

        // Resize if too large
        if ($originalWidth > $options['maxWidth']) {
            $imagePath = $this->resizeImage($imagePath, $options['maxWidth'], $options['quality']);
        }

        // Store original
        $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
        $originalPath = $path . '/' . $filename;
        Storage::disk('public')->putFileAs($path, new \Illuminate\Http\File($imagePath), $filename);

        $result = [
            'original' => $originalPath,
            'webp' => null,
            'thumbnails' => [],
            'metadata' => [
                'width' => $originalWidth,
                'height' => $originalHeight,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'original_filename' => $file->getClientOriginalName(),
            ]
        ];

        // Convert to WebP
        if ($options['convertWebP'] && function_exists('imagewebp')) {
            $webpPath = $this->storeAsWebP($file, $path, $options['quality']);
            $result['webp'] = $webpPath;
        }

        // Generate thumbnails
        if ($options['generateThumbnails']) {
            $result['thumbnails'] = $this->generateThumbnails($imagePath, $originalPath);
        }

        // Clean up temp file if created
        if ($imagePath !== $file->getRealPath() && file_exists($imagePath)) {
            unlink($imagePath);
        }

        return $result;
    }

    /**
     * Resize image to maximum width
     */
    protected function resizeImage(string $imagePath, int $maxWidth, int $quality = 80): string
    {
        $imageInfo = getimagesize($imagePath);
        $originalWidth = $imageInfo[0];
        $originalHeight = $imageInfo[1];

        if ($originalWidth <= $maxWidth) {
            return $imagePath;
        }

        $newWidth = $maxWidth;
        $newHeight = (int)($originalHeight * ($maxWidth / $originalWidth));

        $sourceImage = match ($imageInfo[2]) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($imagePath),
            IMAGETYPE_PNG => imagecreatefrompng($imagePath),
            IMAGETYPE_GIF => imagecreatefromgif($imagePath),
            IMAGETYPE_WEBP => imagecreatefromwebp($imagePath),
            default => throw new \Exception('Unsupported image type'),
        };

        $resized = imagecreatetruecolor($newWidth, $newHeight);
        
        if ($imageInfo[2] == IMAGETYPE_PNG || $imageInfo[2] == IMAGETYPE_GIF) {
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
        }

        imagecopyresampled($resized, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

        $tempPath = storage_path('app/temp_resized_' . uniqid() . '.webp');
        imagewebp($resized, $tempPath, $quality);
        
        imagedestroy($sourceImage);
        imagedestroy($resized);

        return $tempPath;
    }

    /**
     * Convert and store image as WebP
     * 
     * @param UploadedFile $file
     * @param string $path Storage path (e.g., 'gallery', 'logos', 'destinations')
     * @param int $quality WebP quality (1-100, default 90)
     * @return string Storage path to the WebP image
     */
    public function storeAsWebP(UploadedFile $file, string $path = 'gallery', int $quality = 90): string
    {
        // Check if WebP is supported
        if (!function_exists('imagewebp')) {
            // Fallback: store original format if WebP not supported
            $extension = $file->getClientOriginalExtension();
            $filename = uniqid() . '_' . time() . '.' . $extension;
            $fullPath = $path . '/' . $filename;
            Storage::disk('public')->putFileAs($path, $file, $filename);
            return $fullPath;
        }

        // Generate unique filename
        $filename = uniqid() . '_' . time() . '.webp';
        $fullPath = $path . '/' . $filename;

        // Get image info
        $imageInfo = getimagesize($file->getRealPath());
        if (!$imageInfo) {
            throw new \Exception('Invalid image file');
        }

        // Create image resource based on type
        $sourceImage = match ($imageInfo[2]) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($file->getRealPath()),
            IMAGETYPE_PNG => imagecreatefrompng($file->getRealPath()),
            IMAGETYPE_GIF => imagecreatefromgif($file->getRealPath()),
            IMAGETYPE_WEBP => imagecreatefromwebp($file->getRealPath()),
            default => throw new \Exception('Unsupported image type'),
        };

        // Convert palette images to truecolor (required for WebP)
        if (imageistruecolor($sourceImage) === false) {
            $truecolorImage = imagecreatetruecolor(imagesx($sourceImage), imagesy($sourceImage));
            
            // Preserve transparency for PNG and GIF
            if ($imageInfo[2] == IMAGETYPE_PNG || $imageInfo[2] == IMAGETYPE_GIF) {
                imagealphablending($truecolorImage, false);
                imagesavealpha($truecolorImage, true);
                $transparent = imagecolorallocatealpha($truecolorImage, 255, 255, 255, 127);
                imagefill($truecolorImage, 0, 0, $transparent);
            }
            
            imagecopy($truecolorImage, $sourceImage, 0, 0, 0, 0, imagesx($sourceImage), imagesy($sourceImage));
            imagedestroy($sourceImage);
            $sourceImage = $truecolorImage;
        }

        // Create WebP image
        $tempPath = storage_path('app/temp_' . $filename);
        
        // Ensure directory exists
        if (!is_dir(dirname($tempPath))) {
            mkdir(dirname($tempPath), 0755, true);
        }
        
        // Enable alpha channel for WebP if needed
        if ($imageInfo[2] == IMAGETYPE_PNG || $imageInfo[2] == IMAGETYPE_GIF) {
            imagealphablending($sourceImage, false);
            imagesavealpha($sourceImage, true);
        }
        
        imagewebp($sourceImage, $tempPath, $quality);
        imagedestroy($sourceImage);

        // Store in public disk
        $content = file_get_contents($tempPath);
        Storage::disk('public')->put($fullPath, $content);
        
        // Clean up temp file
        if (file_exists($tempPath)) {
            unlink($tempPath);
        }

        return $fullPath;
    }

    /**
     * Get image URL for display
     * 
     * @param string $storagePath
     * @return string
     */
    public function getUrl(string $storagePath): string
    {
        // Return HTTP/HTTPS URLs as-is
        if (str_starts_with($storagePath, 'http://') || str_starts_with($storagePath, 'https://')) {
            return $storagePath;
        }
        
        // Use asset() for relative paths starting with images/ (public directory)
        if (str_starts_with($storagePath, 'images/')) {
            return asset($storagePath);
        }
        
        // Use Storage::url() for storage paths (gallery, etc.)
        return Storage::url($storagePath);
    }

    /**
     * Delete image from storage
     * 
     * @param string $storagePath
     * @return bool
     */
    public function delete(string $storagePath): bool
    {
        if (str_starts_with($storagePath, 'http')) {
            return false; // External URL, can't delete
        }

        return Storage::disk('public')->delete($storagePath);
    }

    /**
     * Check if WebP is supported
     * 
     * @return bool
     */
    public function isWebPSupported(): bool
    {
        return function_exists('imagewebp');
    }
}
