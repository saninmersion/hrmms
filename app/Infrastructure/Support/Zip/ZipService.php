<?php

namespace App\Infrastructure\Support\Zip;

use ZipArchive;

/**
 * Class ZipService
 * @package App\Infrastructure\Support\Zip
 */
class ZipService
{
    /**
     * @var ZipArchive
     */
    protected ZipArchive $zip;

    public function __construct()
    {
        $this->zip = new ZipArchive;
    }

    /**
     * @param array  $files
     * @param string $zipPath
     *
     * @return string|null
     */
    public function convertToZip(array $files, string $zipPath)
    {
        if ( $this->zip->open(($zipPath), ZipArchive::CREATE) === true ) {
            foreach ($files as $filename => $file) {
                $this->zip->addFile($file, (string) $filename);
            }

            $this->zip->close();

            if ( $this->zip->open($zipPath) === true ) {
                return $zipPath;
            } else {
                return null;
            }
        }

        return null;
    }
}
