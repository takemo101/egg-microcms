<?php

namespace App\Http\Controller;

use App\Repository\BlogRepository;
use Cycle\ORM\ORMInterface;
use Takemo101\Egg\Http\Exception\NotFoundHttpException;

class BlogController
{
    /**
     * 新着記事一覧
     *
     * @return string
     */
    public function index(ORMInterface $orm)
    {
        /** @var BlogRepository */
        $repository = $orm->getRepository('blog');

        $blogs = $repository->latestList();

        return latte('page.blog.index', compact('blogs'));
    }

    /**
     * 新着詳細
     *
     * @param string $id
     * @return string
     */
    public function show(ORMInterface $orm, string $id)
    {
        /** @var BlogRepository */
        $repository = $orm->getRepository('blog');

        $blog = $repository->findByPK($id);

        if (is_null($blog)) {
            throw new NotFoundHttpException();
        }

        return latte('page.blog.show', compact('blog'));
    }
}
