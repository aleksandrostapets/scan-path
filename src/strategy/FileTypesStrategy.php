<?php


namespace wherw\strategy;


use wherw\entity\MethodStrategyInterface;

class FileTypesStrategy implements MethodStrategyInterface
{

    public function getName($type, string $fileName): ?string
    {
        $mimeType = strtolower(mime_content_type($fileName));
        return explode('/', $mimeType)[0];

    }
}