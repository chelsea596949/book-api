<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FillBookSlugs extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('books');

        $books = $builder->get()->getResultArray();

        foreach ($books as $book) {

            // 產 slug
            $slug = url_title($book['title'], '-', true);

            $builder->where('id', $book['id'])
                    ->update(['slug' => $slug]);
        }
    }

    public function down()
    {
        // 通常不用還原 slug
    }
}
