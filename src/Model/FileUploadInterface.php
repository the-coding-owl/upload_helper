<?php
namespace TheCodingOwl\UploadHelper\Model;

interface File
{
    /**
     * Get the tmp_name of the uploads
     *
     * @var string
     */
    public function getTmpName(): string;
  
    /**
     * Get the name of the upload (this is not trustworthy!)
     *
     * @var string
     */
    public function getName(): string;
  
    /**
     * Get the size of the upload in bytes (this is not trustworthy!)
     *
     * @var int
     */
    public function getSize(): int;
  
    /**
     * Get the mime type of the upload (this is not trustworthy!)
     *
     * @var string
     */
    public function getType(): string;
  
    /**
     * Get the error code of the upload
     *
     * @var int
     */
    public function getError(): int;
}
