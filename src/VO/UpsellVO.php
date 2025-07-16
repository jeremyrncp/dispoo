<?php

namespace App\VO;
use App\Entity\Category;
use Symfony\Component\HttpFoundation\File\File;

class UpsellVO
{
    public ?File $image = null;
    public ?string $name = null;
    public ?int $duration = null;
    public ?float $price = null;
    public ?int $position = null;
    public ?string $description = null;
    public ?array $services = [];
}
