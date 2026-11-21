<?php

declare(strict_types=1);

namespace app\services;

use Yii;
use yii\web\UploadedFile;

/**
 * Validates uploaded images and converts them to optimized WebP files.
 */
class MediaUploadService
{
    private const ALLOWED_MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'image/webp',
    ];

    private const MAX_SIZE_BYTES = 10 * 1024 * 1024; // 10 MB

    public function uploadAsWebp(UploadedFile $file, string $subDir = 'uploads'): ?string
    {
        if ($file->size > self::MAX_SIZE_BYTES) {
            return null;
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = $finfo ? finfo_file($finfo, $file->tempName) : $file->type;
        if ($finfo) {
            finfo_close($finfo);
        }

        if (!in_array($mime, self::ALLOWED_MIME_TYPES, true)) {
            return null;
        }

        $targetDir = Yii::getAlias('@webroot/media/' . trim($subDir, '/'));
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0775, true);
        }

        $filename = uniqid('nj_', true) . '.webp';
        $targetPath = $targetDir . DIRECTORY_SEPARATOR . $filename;

        if ($mime === 'image/webp') {
            if ($file->saveAs($targetPath)) {
                return '/media/' . trim($subDir, '/') . '/' . $filename;
            }
            return null;
        }

        $image = match ($mime) {
            'image/jpeg' => @imagecreatefromjpeg($file->tempName),
            'image/png' => @imagecreatefrompng($file->tempName),
            default => false,
        };

        if (!$image) {
            return null;
        }

        imagepalettetotruecolor($image);
        imagealphablending($image, true);
        imagesavealpha($image, true);

        $saved = imagewebp($image, $targetPath, 86);
        imagedestroy($image);

        return $saved ? '/media/' . trim($subDir, '/') . '/' . $filename : null;
    }
}
