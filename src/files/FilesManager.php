<?php


namespace wherw\files;

use RuntimeException;
use wherw\entity\MethodStrategyInterface;
use wherw\ScanPath;

class FilesManager
{
    /**
     * @var ScanPath
     */
    private $scanDir;
    /**
     * @var string
     */
    private $file;

    /**
     * FilesManager constructor.
     * @param ScanPath $scanDir
     * @param string $file
     */
    public function __construct(ScanPath $scanDir, string $file)
    {
        $this->scanDir = $scanDir;
        $this->file = $file;
    }

    /**
     * @return string | null
     * @throws RuntimeException
     */
    public function handle(): ?string
    {
        $strategy = $this->getStrategy();
        $type = 'fetch' . ucfirst($this->scanDir->getMethodName());
        return $strategy->getName($this->scanDir->$type(), $this->file);
    }

    /**
     * @return MethodStrategyInterface
     * @throws RuntimeException
     */
    private function getStrategy(): MethodStrategyInterface
    {
        $strategyName = $this->scanDir->getMethodName() . 'Strategy';
        $strategyClass = 'wherw\\strategy\\' . ucwords($strategyName);
        if (!class_exists($strategyClass)) {
            throw new RuntimeException('The class ' . $strategyName . ' does not exist');
        }
        return new $strategyClass;
    }
}