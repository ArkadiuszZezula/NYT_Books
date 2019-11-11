<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    /**
     * @var
     */
    private $targetDirectory;

    /**
     * FileUploader constructor.
     * @param $targetDirectory
     */
    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    /**
     * Upload file
     * @param UploadedFile $file
     * @return string
     */
    public function upload(UploadedFile $file)
    {
        $originalFilename = pathinfo(
            $file->getClientOriginalName(),
            PATHINFO_FILENAME
        );
        $safeFilename = preg_replace(
            '/[^a-z0-9]+/',
            '-',
            strtolower($originalFilename)
        );
        $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            return false;
        }
        return $fileName;
    }

    /**
     * @return mixed
     */
    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}
