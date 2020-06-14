<?php


namespace wherw\files;

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
     * @throws \Exception
     */
    public function handle(): ?string
    {
        $strategy = $this->getStrategy();
        $type = 'get' . ucfirst($this->scanDir->getMethodName());
        return $strategy->getName($this->scanDir->$type(), $this->file);
    }

    /**
     * @return \wherw\entity\MethodStrategyInterface
     * @throws \Exception
     */
    private function getStrategy()
    {
        $strategyName = $this->scanDir->getMethodName() . 'Strategy';
        $strategyClass = 'wherw\\strategy\\' . ucwords($strategyName);
        if (!class_exists($strategyClass)) {
            throw new \Exception('The class ' . $strategyName . ' does not exist');
        }
        return new $strategyClass;
    }
}