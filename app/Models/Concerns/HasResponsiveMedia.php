<?php

namespace App\Models\Concerns;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Throwable;

/**
 * Registers the responsive image conversions shared by every model that
 * carries a picture of its own.
 *
 * The parameter stays typed as Spatie's Media rather than App\Models\Media:
 * HasMedia declares it that way, and a narrower parameter type would make
 * every implementing model fatal on load.
 *
 * The widths are read from config('media-library.responsive_widths'), which
 * is also the list App\Models\Media::responsiveSrcset() advertises in the
 * markup, so the generated files and the srcset cannot drift apart: a width
 * that is listed is always generated, and a generated width is always offered
 * to the browser.
 *
 * Registering a conversion is what makes Spatie *build* it, so registration is
 * gated on an image library: without one the very next line would make the
 * seeder, an admin upload or a regenerate attempt an encode it cannot perform
 * and die mid-request. That leaves the two halves deliberately asymmetric -
 * registration may be empty while the database still marks conversions as
 * generated, which is exactly the state of a deployment that shipped
 * pre-generated files to a host with no GD. Nothing public may assume the two
 * agree: App\Models\Media::getAvailableUrl() catches the InvalidConversion
 * that mismatch produces and serves the original instead, and
 * App\Models\Media::responsiveSrcset() drops the offending width. The result
 * is that a host without an image library renders every page, just without the
 * smaller variants.
 *
 * Conversions are deliberately nonQueued. The production target is a shared
 * cPanel host with no queue worker, so a conversion that waited for a worker
 * would never be created and every visitor would download the original
 * instead - which is exactly the failure this trait exists to prevent.
 *
 * @method void registerMediaConversions(?Media $media = null)
 */
trait HasResponsiveMedia
{
    public function registerMediaConversions(?Media $media = null): void
    {
        if (! extension_loaded('gd') && ! extension_loaded('imagick')) {
            return;
        }

        $originalWidth = self::originalPixelWidth($media);

        foreach (config('media-library.responsive_widths', []) as $conversionName => $width) {
            $width = (int) $width;

            // Never upscale. A 1600px copy of a 1440px original weighs more
            // than the file it came from and resolves no extra detail, so it
            // would make every page that uses it heavier, not lighter. Of the
            // images currently in this library none is wider than 1600px, so
            // this rule is what keeps `large` from being generated at all
            // until somebody uploads something genuinely large.
            if ($originalWidth > 0 && $width >= $originalWidth) {
                continue;
            }

            $this->addMediaConversion($conversionName)->width($width)->nonQueued();
        }
    }

    /**
     * Width in pixels of the file this conversion would be derived from.
     *
     * Returns 0 whenever it cannot be told - no media yet, or the file is not
     * on disk at the moment Spatie asks - which makes the caller register
     * every configured width rather than none of them. Being wrong in that
     * direction costs disk space; being wrong the other way would leave a
     * conversion missing that a view is waiting for.
     */
    private static function originalPixelWidth(?Media $media): int
    {
        if ($media === null || $media->id === null) {
            return 0;
        }

        try {
            $size = @getimagesize($media->getPath());
        } catch (Throwable) {
            return 0;
        }

        return is_array($size) ? (int) $size[0] : 0;
    }
}
