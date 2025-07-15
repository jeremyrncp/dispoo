<?php

namespace App\VO;
use App\Entity\Category;
use Symfony\Component\HttpFoundation\File\File;

class ServiceVO
{
    public ?File $image = null;
    public ?string $name = null;
    public ?int $duration = null;
    public ?float $price = null;
    public ?string $description = null;
    public ?Category $category = null;
}
