<?php
declare(strict_types=1);

namespace HelloNico\Brotli\Exception;

final class CorruptInputException extends \InvalidArgumentException implements BrotliException
{
    public static function create($previous): self
    {
        return new self('Input data is not valid Brotli.', 0, $previous);
    }
}
