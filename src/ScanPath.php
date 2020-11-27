<?php

namespace wherw;

use Ds\Vector;
use Exception;
use wherw\files\FilesManager;

class ScanPath
{

    private $dir;
    private $files;
    private $mimeType = '';
    private $path = __DIR__;
    private $ext = [];
    private $methodName;

    /**
     * @param string $path
     * @return ScanPath
     */
    public function setPath(string $path): ScanPath
    {
        if (!empty($path)) {
            $this->path = $path;
        }
        return $this;
    }

    /**
     * This method get an array of file extensions
     * @param array $ext
     * @return ScanPath
     */
    public function setExtension(array $ext): ScanPath
    {
        if (!empty($ext)) {
            $this->ext = $ext;
            $this->methodName = 'Extension';
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
    public function setMimeType(string $mimeType): ScanPath
    {
        if (!empty($mimeType)) {
            $this->mimeType = $mimeType;
            $this->methodName = 'MimeType';
        }
        return $this;
    }

    /**
     * @return $this
     */
    private function setFileTypes(): ScanPath
    {
        $this->methodName = 'FileTypes';
        return $this;
    }

    /**
     * @return string
     */
    public function getMethodName(): string
    {
        return $this->methodName ?? 'Extension';
    }

    /**
     * @return array
     */
    public function fetchExtension(): array
    {
        return $this->ext;
    }

    /**
     * @return string
     */
    public function fetchMimeType(): string
    {
        return $this->mimeType;
    }

    /**
     * @param $name
     * @param $arguments
     * @return string
     */
    public function __call($name, $arguments): string
    {
        return '';
    }

    /**
     * @return Vector
     */
    public function getFiles(): Vector
    {
        $this->dir = new Vector();
        $this->files = new Vector();
        $this->run();
        return $this->files;
    }

    /**
     * this method implements obtaining a list of mimeTypes files
     * @return array
     */
    public function getFileTypes(): array
    {
        $this->dir = new Vector();
        $this->files = new Vector();
        $this->setFileTypes();
        $this->run();
        unset($this->methodName);
        return array_unique($this->files->toArray());
    }

    private function run()
    {
        try {
            $path = $this->path;
            if (!file_exists($path)) {
                throw new Exception('The specified file or directory does not exist');
            }
            $scanDir = [];
            if (is_readable($path) ) {
                $scanDir = scandir($path);
            }
            $scan = new Vector($scanDir);
            $count = $scan->count();
            while ($count) {
                $count--;
                $item = $scan->get($count);
                if ($item == '.' || $item == '..') {
                    continue;
                }
                $file = $path . '/' . $item;
                if (is_dir($file)) {
                    $this->dir->push(realpath($file));
                } else {
                    $file = (new FilesManager($this, $file))->handle();
                    if (!empty($file)) {
                        $this->files->push($file);
                    }
                }
            }
            if ($this->dir->count()) {
                $this->path = $this->dir->pop();
                $this->run();
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        } finally {
            $this->path = __DIR__;
        }
    }
}