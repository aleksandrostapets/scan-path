<?php

namespace wherw;

use Exception;
use wherw\files\FilesManager;

class ScanPath
{

    private $dir = [];
    private $files = [];
    private $mimeType = '';
    private $path = __DIR__;
    private $ext = [];
    private $methodName;

    /**
     * @param string $path
     * @return ScanPath
     */
    public function setPath(string $path)
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
    public function setExtension(array $ext)
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
    public function setMimeType(string $mimeType)
    {
        if (!empty($mimeType)) {
            $this->mimeType = $mimeType;
            $this->methodName = 'MimeType';
        }
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
        $this->files = [];
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
            if (!file_exists($path)) {
                throw new Exception('The specified file or directory does not exist');
            }
            $scan = scandir($path);
            if (!is_array($scan)) {
                return;
            }
            $scan = array_values(array_filter($scan, function($file) {
                $skip = ['.', '..'];
                if (!in_array($file, $skip)) {
                    return true;
                }
                return false;
            }));
            $count = count($scan);
            while ($count) {
                $count--;
                $item = $scan[$count];
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
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

}