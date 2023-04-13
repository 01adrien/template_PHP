<?php

namespace Src\Core\Upload;

use Psr\Http\Message\UploadedFileInterface;


class FileInfo
{
  public function getFileInfos(UploadedFileInterface $uploadedFile)
  {
    return [
      'filename'        => $uploadedFile->getClientFilename(),
      'storageFileName' => bin2hex(random_bytes(25)),
      'mimeType'        => $uploadedFile->getClientMediaType(),
      'fileSize'        => $uploadedFile->getSize()
    ];
  }

  public function getFileContent(UploadedFileInterface $uploadedFile)
  {
    return $uploadedFile->getStream()->getContents();
  }
}
