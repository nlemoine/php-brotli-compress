<?php
declare(strict_types=1);

namespace HelloNico\Brotli;

use mikehaertl\shellcommand\Command;
use HelloNico\Brotli\Exception\BrotliException;
use HelloNico\Brotli\Exception\CorruptInputException;
use HelloNico\Brotli\Exception\InvalidQualityException;

final class Brotli
{
    /**
     * @var string
     */
    public static $binaryPath = 'brotli';

    /**
     * @param string $binaryPath By default, the "brotli" binary in the OS Path is used. You can change this behavior.
     */
    public static function setBinaryPath(string $binaryPath): void
    {
        self::$binaryPath = $binaryPath;
    }

    /**
     * @param string $data The raw data to compress
     * @param int $quality Compression level (0-11)
     * @return string The compressed data
     * @throws BrotliException If quality is invalid
     * @throws ExceptionInterface In case something went wrong with process
     */
    public static function compress(string $data, int $quality = 11): string
    {
        if ($quality < 0 || $quality > 11) {
            throw InvalidQualityException::create($quality);
        }

        return self::runBinary(['-q' => $quality], $data);
    }

    /**
     * @param string $data The compressed data
     * @return string The uncompressed data
     * @throws BrotliException If data is not valid Brotli
     * @throws ExceptionInterface In case something went wrong with process
     */
    public static function uncompress(string $data): string
    {
        return self::runBinary(['-d' => null], $data);
    }

    private static function runBinary(array $arguments, string $stdin): string
    {
        $command = new Command(self::$binaryPath);

        $command->setStdIn($stdin);

        foreach ($arguments as $arg => $argument) {
            $command->addArg($arg, $argument);
        }

        try {
            if (!$command->execute()) {
                throw new \Exception($command->getError());
            }
        } catch (\Exception $exception) {
            if (strpos($exception->getMessage(), 'corrupt input') === 0) {
                throw CorruptInputException::create($exception);
            }
        }

        return $command->getOutput();
    }
}
