<?php

declare(strict_types=1);

namespace Migration;

use Cycle\Migrations\Migration;

class OrmDefault451f5b624f1da38bd97fd32e2ef69208 extends Migration
{
    protected const DATABASE = 'default';

    public function up(): void
    {
        $this->table('categories')
            ->addColumn('id', 'string', ['nullable' => false, 'default' => null])
            ->addColumn('name', 'string', ['nullable' => false, 'default' => null])
            ->addColumn('created_at', 'timestamp', ['nullable' => false, 'default' => null])
            ->addColumn('updated_at', 'timestamp', ['nullable' => false, 'default' => null])
            ->setPrimaryKeys(['id'])
            ->create();
        $this->table('blogs')
            ->addColumn('id', 'string', ['nullable' => false, 'default' => null])
            ->addColumn('eyecatch', 'text', ['nullable' => true, 'default' => null])
            ->addColumn('title', 'string', ['nullable' => false, 'default' => null])
            ->addColumn('content', 'longText', ['nullable' => false, 'default' => null])
            ->addColumn('created_at', 'timestamp', ['nullable' => false, 'default' => null])
            ->addColumn('updated_at', 'timestamp', ['nullable' => false, 'default' => null])
            ->addColumn('published_at', 'timestamp', ['nullable' => false, 'default' => null])
            ->addColumn('category_id', 'string', ['nullable' => true, 'default' => null])
            ->addIndex(['category_id'], ['name' => 'blogs_index_category_id_63f4d06e1c33e', 'unique' => false])
            ->addForeignKey(['category_id'], 'categories', ['id'], [
                'name' => 'blogs_category_id_fk',
                'delete' => 'CASCADE',
                'update' => 'CASCADE',
            ])
            ->setPrimaryKeys(['id'])
            ->create();
    }

    public function down(): void
    {
        $this->table('blogs')->drop();
        $this->table('categories')->drop();
    }
}
