<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Column;
use DateTimeInterface;

#[Entity(
    role: 'category',
    repository: CategoryRepository::class,
    table: 'categories',
)]
class Category
{
    public function __construct(
        #[Column(type: 'string', primary: true)]
        public string $id,
        #[Column(type: 'string')]
        public string $name,
        #[Column(type: 'timestamp', name: 'created_at')]
        public DateTimeInterface $createdAt,
        #[Column(type: 'timestamp', name: 'updated_at')]
        public DateTimeInterface $updatedAt,
    ) {
        //
    }
}
