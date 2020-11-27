<?php


namespace wherw\strategy;


use wherw\entity\MethodStrategyInterface;

class MimeTypeStrategy implements MethodStrategyInterface
{

    /**
     * @param $type
     * @param string $fileName
     * @return string|null
     * @throws \Exception
     */
    public function getName($type, string $fileName): ?string
    {
        $mimeType = strtolower(mime_content_type($fileName));
        if (is_string($type)) {
            if (strpos($mimeType, $type) !== false) {
                return realpath($fileName);
            }
        } else {
            throw new \Exception('The mime type was entered incorrectly');
        }
        return null;
    }
}