<?php
namespace TheCodingOwl\UploadHelpter\Validator;

use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;
use TYPO3\CMS\Core\Utility\StringUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TheCodingOwl\UploadHelper\Model;

class FileValidator extends AbstractValidator {
    const INVALID_TYPE = 1595090575;
    const ERROR_TYPE_NOT_ALLOWED = 1595090576;
    const ERROR_NO_IMAGE = 1595090577;
    const ERROR_SIZE = 1595090578;
    const OPTION_ALLOWED_TYPES = 'allowedTypes';
    const OPTION_MAX_SIZE = 'allowedSize';
    protected $imageFileTypes = [
        'image/bmp',
        'image/gif',
        'image/vnd.microsoft.icon',
        'image/jpeg',
        'image/png',
        'image/svg+xml',
        'image/tiff',
        'image/webp'
    ];
    protected $options = [];
    public function isValid($file) {
        if (!$file instanceof FileUploadInterface) {
            $this->addError(
                'Value to validate not of FileUploadInterface type but of %s!', 
                self::INVALID_TYPE, 
                ['type' => gettype($file)]
            );
            return;
        }
        if (!$this->hasAllowedType($file)) {
            $this->addError(
                'The filetype is not of the accepted file types %s!', 
                self::ERROR_TYPE_NOT_ALLOWED, 
                [self::OPTION_ALLOWED_TYPES => $this->options[self::OPTION_ALLOWED_TYPES]]
            );
            return;
        }
        if ($this->checkImage($file->getType()) && $this->isImage($file)) {
            $this->addError(
                'The uploaded file is not an image!',
                self::ERROR_NO_IMAGE,
                [self::OPTION_ALLOWED_TYPES => $this->options[self::OPTION_ALLOWED_TYPES]]
            );
            return;
        }
        if (!$this->isAllowedSize($file)) {
            $this->addError(
                'The uploaded file is to big, the maximum size is %s!',
                self::ERROR_SIZE,
                [self::OPTION_MAX_SIZE => $this->options[self::OPTION_MAX_SIZE]]
            );
            return;
        }
    }
    
    protected function hasAllowedType(FileUploadInterface $file): bool {
        if (!GeneralUtility::inList($this->options[self::OPTION_ALLOWED_TYPES], $file->getType())) {
            return FALSE;
        }
        $fileInfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $fileInfo->file($file->getTmpName());
        if (!GeneralUtility::inList($this->options[self::OPTION_ALLOWED_TYPES], $mimeType)) {
            return FALSE;
        }
        return TRUE;
    }
    
    protected function checkImage(string $fileType): bool {
        if (!GeneralUtility::inList($this->imageFileTypes, $fileType)) {
            return FALSE;
        }
        
        return TRUE;
    }
    
    protected function isImage(FileUploadInterface $file): bool {
        $image = FALSE;
        $type = $file->getType();
        if ($type === 'image/bmp') {
            $image = \imagecreatefrombmp($file->getTmpName());
        } elseif($type === 'image/jpeg') {
            $image = \imagecreatefromjpeg($file->getTmpName());
        } elseif($type === 'image/png') {
            $image = \imagecreatefrompng($file->getTmpName());
        } elseif($type === 'image/gif') {
            $image = \imagecreatefromgif($file->getTmpName());
        }
        if ($image === FALSE) {
            return FALSE;
        }
        
        \imagedestroy($image);
        return TRUE;
    }
    
    protected function isAllowedSize(FileUploadInterface $file): bool {
        if ($file->getSize() > $this->options[self::OPTION_MAX_SIZE]) {
            return FALSE;
        }
        if (\filesize($file) > $this->options[self::OPTION_MAX_SIZE]) {
            
            return FALSE;
        }
        
        return TRUE;
    }
    
    public function getOptions(): array {
        return $this->options;
    }
    
    public function setOption($name, $value) {
        $this->option[$name] = $value;
    }
}
