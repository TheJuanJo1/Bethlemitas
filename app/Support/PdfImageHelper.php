<?php

namespace App\Support;

class PdfImageHelper
{
    /** @var array<string, string> */
    private static array $tempCache = [];

    /**
     * Ruta JPEG para DomPDF. Nunca devuelve PNG (DomPDF requiere GD para PNG).
     */
    public static function pathForDompdf(?string $absolutePath): ?string
    {
        if (! $absolutePath || ! is_file($absolutePath)) {
            return null;
        }

        $absolutePath = self::normalizePath($absolutePath);
        $ext = strtolower(pathinfo($absolutePath, PATHINFO_EXTENSION));

        if (in_array($ext, ['jpg', 'jpeg'], true)) {
            return $absolutePath;
        }

        if ($ext !== 'png') {
            return null;
        }

        $jpgSidecar = self::pngSidecarJpegPath($absolutePath);
        if (is_file($jpgSidecar)) {
            return $jpgSidecar;
        }

        if (! function_exists('imagecreatefrompng')) {
            return null;
        }

        return self::pngToJpegCached($absolutePath, $jpgSidecar);
    }

    /**
     * Data URI JPEG para DomPDF (sin PNG).
     */
    public static function dataUriForDompdf(?string $absolutePath): ?string
    {
        $jpegPath = self::pathForDompdf($absolutePath);

        if (! $jpegPath || ! is_readable($jpegPath)) {
            return null;
        }

        $contents = @file_get_contents($jpegPath);

        if ($contents === false) {
            return null;
        }

        return 'data:image/jpeg;base64,'.base64_encode($contents);
    }

    public static function institutionalLogoDataUri(): ?string
    {
        $jpg = public_path('img/credencialesGo.jpg');
        if (is_file($jpg)) {
            return self::dataUriForDompdf($jpg);
        }

        return self::dataUriForDompdf(public_path('img/credencialesGo.png'));
    }

  /** @deprecated Usar institutionalLogoDataUri() en PDFs */
    public static function institutionalLogoPath(): ?string
    {
        return self::pathForDompdf(public_path('img/credencialesGo.jpg'))
            ?? self::pathForDompdf(public_path('img/credencialesGo.png'));
    }

    private static function pngSidecarJpegPath(string $pngPath): string
    {
        return preg_replace('/\.png$/i', '.jpg', $pngPath);
    }

    private static function normalizePath(string $path): string
    {
        return str_replace('\\', '/', $path);
    }

    private static function pngToJpegCached(string $pngPath, string $targetJpg): ?string
    {
        $pngPath = self::normalizePath($pngPath);

        if (isset(self::$tempCache[$pngPath]) && is_file(self::$tempCache[$pngPath])) {
            return self::$tempCache[$pngPath];
        }

        if (is_file($targetJpg)) {
            self::$tempCache[$pngPath] = self::normalizePath($targetJpg);

            return self::$tempCache[$pngPath];
        }

        $cacheDir = storage_path('app/pdf-cache');
        if (! is_dir($cacheDir)) {
            @mkdir($cacheDir, 0755, true);
        }

        $cached = $cacheDir.DIRECTORY_SEPARATOR.'img_'.md5($pngPath).'.jpg';

        if (is_file($cached)) {
            self::$tempCache[$pngPath] = self::normalizePath($cached);

            return self::$tempCache[$pngPath];
        }

        if (! self::convertPngFileToJpeg($pngPath, $cached)) {
            return null;
        }

        self::$tempCache[$pngPath] = self::normalizePath($cached);

        if ($targetJpg !== $cached && is_writable(dirname($targetJpg))) {
            @copy($cached, $targetJpg);
        }

        return self::$tempCache[$pngPath];
    }

    private static function convertPngFileToJpeg(string $pngPath, string $jpegPath): bool
    {
        $img = @imagecreatefrompng($pngPath);
        if (! $img) {
            return false;
        }

        $width = imagesx($img);
        $height = imagesy($img);
        $canvas = imagecreatetruecolor($width, $height);
        $white = imagecolorallocate($canvas, 255, 255, 255);
        imagefill($canvas, 0, 0, $white);
        imagecopy($canvas, $img, 0, 0, 0, 0, $width, $height);
        imagedestroy($img);

        $ok = imagejpeg($canvas, $jpegPath, 90);
        imagedestroy($canvas);

        return $ok;
    }

    /**
     * Convierte PNG a JPG junto al archivo (útil para firmas en public/Imagenes_Firma).
     */
    public static function ensureJpegSidecar(string $pngPath): ?string
    {
        if (! is_file($pngPath)) {
            return null;
        }

        return self::pathForDompdf($pngPath);
    }
}
