<?php

namespace Src\Core\Upload;

use Psr\Http\Message\UploadedFileInterface;

class FileInfo
{
    public function getFileInfos(UploadedFileInterface $uploadedFile): array
    {
        return [
        'filename'        => $uploadedFile->getClientFilename(),
        'storageFileName' => bin2hex(random_bytes(25)),
        'mimeType'        => $uploadedFile->getClientMediaType(),
        'fileSize'        => $uploadedFile->getSize()
        ];
    }

    public function getFileContent(UploadedFileInterface $uploadedFile): string
    {
        return $uploadedFile->getStream()->getContents();
    }
}