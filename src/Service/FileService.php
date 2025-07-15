<?php

namespace App\Service;

use App\Entity\Service;
use Symfony\Component\HttpFoundation\File\File;

class FileService
{
    public function save(File $file, string $directory, string $name)
    {
        move_uploaded_file($file->getRealPath(), $directory . "/" . $name . "." . $file->getClientOriginalExtension());

        return $name . "." . $file->getClientOriginalExtension();
    }

    public function createName(Service $service) :string
    {
        $uniqid = uniqid();

        return $uniqid . "_" . $service->getId();
    }

    public function naming(Service $service): string
    {
        return uniqid();
    }
}
