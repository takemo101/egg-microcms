<?php

namespace App\Repository;

use App\Entity\Blog;
use Cycle\ORM\Select\Repository;

/**
 * @extends Repository<Blog>
 */
class BlogRepository extends Repository
{
    /**
     * カテゴリIdを指定して新着順の記事リストを取得する
     *
     * @param string $categoryId
     * @return array<integer,Blog>
     */
    public function latestListByCategoryId(string $categoryId): array
    {
        return $this->select()
            ->where('category_id', $categoryId)
            ->orderBy('published_at', 'DESC')
            ->fetchAll();
    }

    /**
     * 新着順の記事リストを取得する
     *
     * @return array<integer,Blog>
     */
    public function latestList(): array
    {
        return $this->select()
            ->orderBy('published_at', 'DESC')
            ->fetchAll();
    }
}
