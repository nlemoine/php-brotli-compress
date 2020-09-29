<?php
declare(strict_types=1);

if (!function_exists('brotli_compress') && !function_exists('brotli_uncompress')) {
    /**
     * @param string $data The raw data to compress
     * @param int $quality Compression level (0-11)
     * @return string The compressed data
     * @throws \HelloNico\Brotli\Exception\BrotliException If quality is invalid
     */
    function brotli_compress(string $data, int $quality = 11): string
    {
        return \HelloNico\Brotli\Brotli::compress($data, $quality);
    }

    /**
     * @param string $data The compressed data
     * @return string The uncompressed data
     * @throws \HelloNico\Brotli\Exception\BrotliException If data is not valid Brotli
     */
    function brotli_uncompress(string $data): string
    {
        return \HelloNico\Brotli\Brotli::uncompress($data);
    }
}
