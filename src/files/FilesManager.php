<?php


namespace wherw\files;

use wherw\ScanDir;

class FilesManager
{
    /**
     * @var ScanDir
     */
    private $scanDir;
    /**
     * @var string
     */
    private $file;

    /**
     * FilesManager constructor.
     * @param ScanDir $scanDir
     * @param string $file
     */
    public function __construct(ScanDir $scanDir, string $file)
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