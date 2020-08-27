<?php
namespace TheCodingOwl\UploadHelper\Model;

use TYPO3\Extbase\DomainObject\AbstractEntity;

class File extends AbstractEntity implements FileUploadInterface
{
    /**
     * $_FILES['tmp_name']
     *
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $tmpName = '';

    /**
     * $_FILES['name']
     *
     * @var string
     */
    protected $name = '';

    /**
     * $_FILES['size']
     *
     * @var int
     */
    protected $size = 0;

    /**
     * $_FILES['error']
     *
     * @var int
     */
    protected $error = \UPLOAD_ERR_OK;

    /**
     * $_FILES['type']
     *
     * @var string
     */
    protected $type = '';

    protected $imageTypes = [
        ''
    ];
    /**
    * Get the tmp_name of the uploads
    *
    * @var string
    */
    public function getTmpName(): string
    {
        return $this->tmpName;
    }

    /**
     * Get the name of the upload (this is not trustworthy!)
     *
     * @var string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the size of the upload in bytes (this is not trustworthy!)
     *
     * @var int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * Get the error code of the upload
     *
     * @var int
     */
    public function getError(): int
    {
        return $this->error;
    }

    /**
     * Get the mime type of the upload (this is not trustworthy!)
     *
     * @var string
     */
    public function getType(): string
    {
        return $this->type;
    }
    
    /**
     * Check if the uploaded file is an image
     *
     * @return bool
     */
    public function isImage(): bool {
        $fileInfo = new finfo(FILEINFO_MIME, $this->tmpName);
        if (!in_array($fileInfo, $this->imageTypes)) {
            return FALSE;
        }
        $testImage = imagecreatefromstring(file_get_contents($this->tmpName));
        if ($testImage === FALSE) {
            return TRUE;
        }
        imagedestroy($testImage);
        return TRUE;
    }
}
