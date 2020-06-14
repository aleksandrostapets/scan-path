<?php

namespace wherw;

use wherw\files\FilesManager;

class ScanDir
{

    private $dir = [];
    private $file = [];
    private $mimeType = '';
    private $path;
    private $isSetPath = false;
    private $isSetType = false;
    private $ext = [];
    private $methodName = '';

    /**
     * @param string $path
     */
    public function setPath(string $path)
    {
        if (!empty($path)) {
            $this->path = $path;
            $this->isSetPath = true;
        }
    }

    /**
     * @param array $ext
     */
    public function setExtension(array $ext)
    {
        if (!empty($ext)) {
            $this->ext = $ext;
            $this->methodName = 'Extension';
            $this->isSetType = true;
        }
    }

    /**
     * @param string $mimeType
     */
    public function setMimeType(string $mimeType)
    {
        if (!empty($mimeType)) {
            $this->mimeType = $mimeType;
            $this->methodName = 'MimeType';
            $this->isSetType = true;
        }
    }

    /**
     * @return string
     */
    public function getMethodName(): string
    {
        return $this->methodName;
    }

    /**
     * @return array
     */
    public function getExtension(): array
    {
        return $this->ext;
    }

    /**
     * @return string
     */
    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    /**
     * @return array
     */
    public function getFiles(): array
    {
        return $this->file;
    }

    public function run()
    {
        try {
            $path = $this->path;
            if (empty($path)) {
                return;
            }
            $scan = scandir($path);
            $count = count($scan);
            while ($count) {
                $count--;
                $item = $scan[$count];
                if (is_dir($item)) {
                    break;
                }
                $file = $path . '/' . $item;
                if (is_dir($file)) {
                    $this->dir[] = realpath($file);
                } else {
                    $file = (new FilesManager($this, $file))->handle();
                    if (!empty($file)) {
                        $this->file[] = $file;
                    }
                }

            }
            $this->path = array_pop($this->dir);
            $this->run();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

}