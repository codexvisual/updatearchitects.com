<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media as SpatieMedia;
use Spatie\MediaLibrary\Support\PathGenerator\PathGeneratorFactory;
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
     * Conversion file names found on disk for this row, keyed by conversion
     * name. Null until first asked, then reused for every conversion on it.
     *
     * @var array<string, string>|null
     */
    private ?array $conversionFileIndex = null;

    /**
     * The first of $conversionNames that exists, falling back to the original.
     *
     * Spatie's version is re-implemented here because its two halves are
     * allowed to disagree. It reads hasGeneratedConversion() from the database
     * but then asks the *registered* conversions for a path, and registration
     * is gated on an image library (see HasResponsiveMedia): on a host with no
     * GD and no Imagick, ConversionCollection comes back empty, so
     * parent::getAvailableUrl() throws InvalidConversion out of every view,
     * admin list and og:image in the app.
     *
     * Serving the original in that case kept the pages up but quietly made
     * every image full-size - the thumbnail and medium copies were on disk the
     * whole time, and the row said so. publishedConversionUrl() now answers
     * from the files themselves, so a shipped conversion is served whether or
     * not this host could have encoded it.
     *
     * @param  array<int, string>  $conversionNames
     */
    public function getAvailableUrl(array $conversionNames): string
    {
        foreach ($conversionNames as $conversionName) {
            if (! $this->hasGeneratedConversion($conversionName)) {
                continue;
            }

            $url = $this->publishedConversionUrl($conversionName);

            if ($url !== null) {
                return $url;
            }
        }

        return $this->getUrl();
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

            $url = $this->publishedConversionUrl($name);

            if ($url === null) {
                // Either the row never claimed this conversion, or the file it
                // promised is not on disk. Advertising it anyway would hand the
                // browser a 404 where the original would have rendered, so the
                // original below still covers the range.
                continue;
            }

            $candidates[$width] = $url;
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
     * URL of a generated conversion, or null when there is nothing to serve.
     *
     * Spatie is asked first. When it has the conversion registered - a host
     * with an image library - its answer is authoritative and already correct
     * for this host's URL configuration, so nothing here changes.
     *
     * What this adds is the answer for the other case. Spatie builds a
     * conversion's path from the Conversion object, and with no image library
     * loaded there is no Conversion to build it from, so it throws even though
     * the bytes are sitting on disk. Rather than reconstruct Spatie's naming
     * rules, the conversions directory is listed once per media instance and
     * matched against the upload's own stem - `{upload}-{conversion}.{ext}` -
     * which is what Spatie wrote when the file was made. Only a file that is
     * actually present is ever returned, so an entry can no longer name a 404.
     *
     * Listing the directory also keeps this honest about extensions: the copy
     * of a `.jpeg` is written as `.jpg`, and several inputs would have needed
     * a rule this class would rather not own.
     *
     * @return array{url: string, path: string}|null
     */
    private function publishedConversionUrl(string $conversionName): ?string
    {
        try {
            return $this->getUrl($conversionName);
        } catch (Throwable) {
            // No image library registered it - fall through to the files.
        }

        $fileName = $this->conversionFileIndex()[$conversionName] ?? null;

        if ($fileName === null) {
            return null;
        }

        $diskName = $this->conversions_disk ?: $this->disk;

        try {
            $disk = Storage::disk($diskName);
            $relative = $this->conversionRelativeDirectory().$fileName;
        } catch (Throwable) {
            return null;
        }

        try {
            return $disk->url($relative);
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * Conversion file names in this media's conversions directory, keyed by
     * conversion name: `medium` => `Upload-medium.jpg`.
     *
     * Resolved once per media instance and reused by every conversion this
     * row asks for, so a page of fifty pictures opens fifty directories rather
     * than a hundred, and an unreadable directory simply yields no conversions.
     *
     * @return array<string, string>
     */
    private function conversionFileIndex(): array
    {
        if ($this->conversionFileIndex !== null) {
            return $this->conversionFileIndex;
        }

        $this->conversionFileIndex = [];

        $directory = $this->conversionAbsoluteDirectory();

        if ($directory === null || ! is_dir($directory)) {
            return $this->conversionFileIndex;
        }

        try {
            $entries = scandir($directory) ?: [];
        } catch (Throwable) {
            return $this->conversionFileIndex;
        }

        $stem = pathinfo((string) $this->file_name, PATHINFO_FILENAME);
        $pattern = '/^'.preg_quote($stem, '/').'-([^.]+)\.[^.]+$/';

        foreach ($entries as $entry) {
            if (preg_match($pattern, $entry, $matches) !== 1) {
                continue;
            }

            if (! is_file($directory.DIRECTORY_SEPARATOR.$entry)) {
                continue;
            }

            $this->conversionFileIndex[$matches[1]] = $entry;
        }

        return $this->conversionFileIndex;
    }

    /**
     * This media's conversions directory relative to its disk root, e.g.
     * `167/conversions/`, as Spatie's path generator reports it.
     *
     * Asked of the generator rather than assembled here so a custom path
     * generator keeps working without this class needing to know about it.
     */
    private function conversionRelativeDirectory(): string
    {
        try {
            return PathGeneratorFactory::create($this)->getPathForConversions($this);
        } catch (Throwable) {
            return $this->id.'/'.config('media-library.conversions_dir_name', 'conversions').'/';
        }
    }

    /**
     * Absolute filesystem path of that directory, or null when the disk is
     * not reachable from this host - a missing key, a misconfigured disk.
     *
     * Only used to decide whether a file exists; the URL is always built from
     * the relative path so it goes through the same configuration as any other
     * media link.
     */
    private function conversionAbsoluteDirectory(): ?string
    {
        $diskName = $this->conversions_disk ?: $this->disk;

        try {
            return Storage::disk($diskName)->path($this->conversionRelativeDirectory());
        } catch (Throwable) {
            return null;
        }
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
