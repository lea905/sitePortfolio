<?php

namespace App\Service;

use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class CloudinaryService
{
    private Cloudinary $cloudinary;

    public function __construct(
        #[Autowire(env: 'CLOUDINARY_URL')] string $cloudinaryUrl
    ) {
        $this->cloudinary = new Cloudinary(Configuration::instance($cloudinaryUrl));
    }

    /**
     * Upload an image to Cloudinary and return the secure URL.
     *
     * @param UploadedFile $file
     * @param string $folder
     * @return string
     */
    public function uploadImage(UploadedFile $file, string $folder = 'portfolio_projects'): string
    {
        $response = $this->cloudinary->uploadApi()->upload($file->getPathname(), [
            'folder' => $folder,
            'use_filename' => true,
            'unique_filename' => true,
            'overwrite' => false,
        ]);

        return $response['secure_url'];
    }
}
