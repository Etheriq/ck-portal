<?php

namespace Geekhub\FileBundle;

use Geekhub\FileBundle\Entity\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    /**
     * Process the upload.
     * @param $request Request
     * @param $fileEntity File
     */
    public function handleUpload(Request $request, File $fileEntity)
    {
        /* @var $uploadedFile UploadedFile */
        $uploadedFile = $request->files->get('qqfile');

        if (!file_exists($fileEntity->getUploadDir())) {
            mkdir($fileEntity->getUploadDir());
        }

        if ($this->isValid($fileEntity, $uploadedFile)) {
            $ext = explode('.', $uploadedFile->getClientOriginalName());
            $uniqueName = md5(microtime(1).$uploadedFile->getClientOriginalName()) .'.'. $ext[count($ext)-1];

            $fileEntity->setPath($fileEntity->getUploadDir() . $uniqueName);
            $fileEntity->setOriginalName($uploadedFile->getClientOriginalName());
            $fileEntity->setSize($uploadedFile->getSize());
            $fileEntity->setMimeType($uploadedFile->getClientMimeType());

            $uploadedFile->move($fileEntity->getUploadDir(), $uniqueName);
        }

        return true;
    }

    protected function isValid(File $file, UploadedFile $uploadedFile)
    {
        if (!isset($_SERVER['CONTENT_TYPE'])) {
            $file->setError("No files were uploaded.");
            return false;
        }
        else if (strpos(strtolower($_SERVER['CONTENT_TYPE']), 'multipart/') !== 0) {
            $file->setError("Server error. Not a multipart request. Please set forceMultipart to default value (true).");
            return false;
        }

        if (!is_writable($file->getUploadDir()) || !is_executable($file->getUploadDir())) {
            $file->setError("Server error. Uploads directory isn't writable or executable.");
            return false;
        }

        if (null === $uploadedFile) {
            $file->setError("Don't find uploaded file, or uploaded file is too large.");
            return false;
        }

        // Validate file mimeType
        if (!in_array($uploadedFile->getClientMimeType(), $file->getAllowedMimeType())) {
            $file->setError('File has an invalid mime type, it should be one of ' . (implode(', ', $file->getAllowedMimeType())) . '.');
            return false;
        }

        // Check that the max upload size specified in class configuration does not
        // exceed size allowed by server config
        if ($this->toBytes(ini_get('post_max_size')) < $file->getSizeLimit() ||
            $this->toBytes(ini_get('upload_max_filesize')) < $file->getSizeLimit()
        ) {
            $size = max(1, $file->getSizeLimit() / 1024 / 1024) . 'M';
            $file->setError("Server error. Increase post_max_size and upload_max_filesize to " . $size);
            return false;
        }

        // Validate name
        if ($uploadedFile->getClientOriginalName() === null || $uploadedFile->getClientOriginalName() === '') {
            $file->setError('File name empty.');
            return false;
        }

        if ($uploadedFile->getSize() == 0) {
            $file->setError('File is empty.');
            return false;
        }

        if ($uploadedFile->getSize() > $file->getSizeLimit()) {
            $file->setError('File is too large.');
            return false;
        }

        return true;
    }

    /**
     * Converts a given size with units to bytes.
     * @param string $str
     */
    protected function toBytes($str)
    {
        $val = trim($str);
        $last = strtolower($str[strlen($str) - 1]);
        switch ($last) {
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }
        return $val;
    }
}