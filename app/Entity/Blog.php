<?php

namespace App\Entity;

use App\Repository\BlogRepository;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Relation\BelongsTo;
use DateTimeInterface;

#[Entity(
    role: 'blog',
    repository: BlogRepository::class,
    table: 'blogs',
)]
class Blog
{
    public function __construct(
        #[Column(type: 'string', primary: true)]
        public string $id,
        #[Column(type: 'text', nullable: true)]
        public ?string $eyecatch,
        #[Column(type: 'string')]
        public string $title,
        #[Column(type: 'longText')]
        public string $content,
        #[Column(type: 'timestamp', name: 'created_at')]
        public DateTimeInterface $createdAt,
        #[Column(type: 'timestamp', name: 'updated_at')]
        public DateTimeInterface $updatedAt,
        #[Column(type: 'timestamp', name: 'published_at')]
        public DateTimeInterface $publishedAt,
        #[BelongsTo(target: Category::class, nullable: true)]
        public ?Category $category = null,
    ) {
        //
    }
}
