<?php

namespace wherw;

use wherw\files\FilesManager;

class ScanPath
{

    private $dir = [];
    private $files = [];
    private $mimeType = '';
    private $path;
    private $isSetPath = false;
    private $isSetType = false;
    private $ext = [];
    private $methodName = '';

    /**
     * @param string $path
     * @return ScanPath
     */
    public function setPath(string $path)
    {
        if (!empty($path)) {
            $this->path = $path;
            $this->isSetPath = true;
        }
        return $this;
    }

    /**
     * This method get an array of file extensions
     * @param array $ext
     * @return ScanPath
     */
    public function setExtension(array $ext)
    {
        if (!empty($ext)) {
            $this->ext = $ext;
            $this->methodName = 'Extension';
            $this->isSetType = true;
        }
        return $this;
    }

    /**
     * This method gets a string named type mime type
     *  - application
     *  - audio
     *  - image
     *  - multipart
     *  - text
     *  - video
     * @param string $mimeType
     * @return ScanPath
     */
    public function setMimeType(string $mimeType)
    {
        if (!empty($mimeType)) {
            $this->mimeType = $mimeType;
            $this->methodName = 'MimeType';
            $this->isSetType = true;
        }
        return $this;
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
        $this->run();
        return $this->files;
    }

    private function run()
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
                        $this->files[] = $file;
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