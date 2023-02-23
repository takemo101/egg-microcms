<?php

namespace App\Http\Controller;

use App\Repository\BlogRepository;
use App\Repository\CategoryRepository;
use Cycle\ORM\ORMInterface;
use Takemo101\Egg\Http\Exception\NotFoundHttpException;

class CategoryController
{
    /**
     * カテゴリ記事一覧
     *
     * @param string $id
     * @return string
     */
    public function show(ORMInterface $orm, string $id)
    {
        /** @var BlogRepository */
        $blogRepository = $orm->getRepository('blog');

        /** @var CategoryRepository */
        $categoryRepository = $orm->getRepository('category');

        $category = $categoryRepository->findByPK($id);

        if (is_null($category)) {
            throw new NotFoundHttpException();
        }

        $blogs = $blogRepository->latestListByCategoryId($id);

        return latte('page.category.show', compact('category', 'blogs'));
    }
}
