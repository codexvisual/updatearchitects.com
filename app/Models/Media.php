<?php

namespace App\Models;

use Spatie\MediaLibrary\MediaCollections\Models\Media as SpatieMedia;
use Throwable;

/**
 * The application's media model.
 *
 * It exists only to carry the srcset the public views need. Spatie's own
 * Media class is not Macroable - it is an Eloquent model, and models forward
 * unknown calls to their query builder - so the behaviour cannot be added
 * from the outside. Pointing media-library.media_model here puts one method
 * on every media instance the app can reach: Spatie resolves its relations
 * from that config, and the app's own belongsTo() relations are declared
 * against this class.
 *
 * Spatie's `getSrcset()` is deliberately not used. It reports widths the
 * responsive-image generator produced, which runs on a queue connection -
 * unusable on the cPanel target, which has no worker and no cron. The
 * conversions this class advertises are generated inline instead, by
 * HasResponsiveMedia, so the files and the markup always agree.
 */
class Media extends SpatieMedia
{
    /**
     * The first of $conversionNames that exists, falling back to the original.
     *
     * Spatie's version is re-implemented here for one reason: it reads
     * hasGeneratedConversion() from the database but then asks the *registered*
     * conversions for a path, and the two are allowed to disagree - a host with
     * no GD registers nothing (see HasResponsiveMedia) while the row still says
     * the file was generated. On that host parent::getAvailableUrl() would throw
     * InvalidConversion out of every view, admin list and og:image in the app,
     * taking the site down to save a round trip. Catching it and serving the
     * original costs nothing when the two agree and keeps the pages up when
     * they do not.
     *
     * @param  array<int, string>  $conversionNames
     */
    public function getAvailableUrl(array $conversionNames): string
    {
        try {
            return parent::getAvailableUrl($conversionNames);
        } catch (Throwable) {
            return $this->getUrl();
        }
    }

    /**
     * A srcset listing every width of this picture a browser may fetch.
     *
     * Each entry is a URL followed by its pixel width, e.g.
     * `https://…/480.jpg 480w, https://…/800.jpg 800w, https://…/orig.jpg 1440w`.
     * Combined with a `sizes` attribute the browser can then download the
     * smallest file that still looks sharp where it is about to paint, which
     * is what turns a 24 MB homepage into a few hundred kilobytes on a phone.
     *
     * Two rules keep the list honest:
     *
     * - a conversion only appears when Spatie confirms it was generated, so
     *   an entry can never name a file that does not exist;
     * - a conversion at least as wide as the original is dropped, because it
     *   is an upscale of the original - a bigger file with no extra detail.
     *   The original, offered below at its true width, covers that range
     *   instead. Skipping this rule would advertise a 1600w file for every
     *   image in the library, none of which is wider than 1600px.
     *
     * @param  array<int, string>  $conversionNames  Restrict to these
     *                                               conversions. Empty means
     *                                               every width, and only in
     *                                               that case is the original
     *                                               file itself offered.
     * @return string|null The srcset, or null when nothing qualifies - the
     *                     caller should then omit the attribute rather than
     *                     emit an empty one.
     */
    public function responsiveSrcset(array $conversionNames = []): ?string
    {
        $widths = (array) config('media-library.responsive_widths', []);

        if ($widths === []) {
            return null;
        }

        $candidates = [];
        $originalWidth = $this->originalPixelWidth();

        foreach ($widths as $name => $width) {
            $width = (int) $width;

            if ($conversionNames !== [] && ! in_array($name, $conversionNames, true)) {
                continue;
            }

            if (! $this->hasGeneratedConversion($name)) {
                continue;
            }

            if ($originalWidth > 0 && $width >= $originalWidth) {
                continue;
            }

            try {
                $candidates[$width] = $this->getUrl($name);
            } catch (Throwable) {
                // The database says this conversion was generated but the
                // conversion itself is no longer registered - which happens
                // whenever responsive_widths is edited to drop a width after
                // files already exist. hasGeneratedConversion() only reads the
                // database, so without this the very next getUrl() call would
                // throw InvalidConversion and take the whole page with it. The
                // original below still covers the range.
                continue;
            }
        }

        if ($conversionNames === [] && $originalWidth > 0) {
            $candidates[$originalWidth] = $this->getUrl();
        }

        if ($candidates === []) {
            return null;
        }

        ksort($candidates);

        return collect($candidates)
            ->map(fn (string $url, int $width): string => $url.' '.$width.'w')
            ->implode(', ');
    }

    /**
     * Width in pixels of the file as uploaded, or 0 when it cannot be read.
     *
     * The media table has no width column, so the file itself is the only
     * source. getimagesize() reads just the header - a few kilobytes - and is
     * core PHP, so this works on a host with neither GD nor Imagick.
     *
     * A zero return means "unknown", and the callers treat that as "offer
     * every generated width" rather than "offer nothing", because a srcset
     * that is too generous still beats no srcset at all.
     */
    private function originalPixelWidth(): int
    {
        try {
            $size = @getimagesize($this->getPath());
        } catch (Throwable) {
            return 0;
        }

        return is_array($size) ? (int) $size[0] : 0;
    }
}
