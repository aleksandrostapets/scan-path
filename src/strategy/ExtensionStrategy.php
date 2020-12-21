<?php


namespace wherw\strategy;


use RuntimeException;
use wherw\entity\MethodStrategyInterface;

class ExtensionStrategy implements MethodStrategyInterface
{
    /**
     * @param array $type
     * @param string $fileName
     * @return string|null
     * @throws RuntimeException
     */
    public function getName($type, string $fileName): ?string
    {
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        if (is_array($type)) {
            if (in_array($ext, $this->getType($type, $ext), true)) {
                return realpath($fileName);
            }
        } else {
            throw new RuntimeException('The file extension was entered incorrectly');
        }
        return null;
    }

    /**
     * @param array $type
     * @param string $ext
     * @return array
     */
    private function getType(array $type, string $ext): array
    {
        if (!empty($type)) {
            return $type;
        }
        return [$ext];
    }
}