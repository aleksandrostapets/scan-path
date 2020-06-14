<?php


namespace wherw\strategy;


use wherw\entity\MethodStrategyInterface;

class ExtensionStrategy implements MethodStrategyInterface
{
    /**
     * @param array $type
     * @param string $fileName
     * @return string|null
     * @throws \Exception
     */
    public function getName($type, string $fileName): ?string
    {
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        if (is_array($type)) {
            if (in_array($ext, $type)) {
                return $fileName;
            }
        } else {
            throw new \Exception('The file extension was entered incorrectly');
        }
        return null;
    }
}