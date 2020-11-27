<?php


namespace wherw\strategy;


class FileTypesStrategy implements \wherw\entity\MethodStrategyInterface
{

    public function getName($type, string $fileName): ?string
    {
        $mimeType = strtolower(mime_content_type($fileName));
        return explode('/', $mimeType)[0];

    }
}