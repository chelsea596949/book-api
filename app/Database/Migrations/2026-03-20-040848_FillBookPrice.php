<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FillBookPrice extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('books');

        $books = $builder->get()->getResultArray();

        helper('random');
        
        foreach($books as $book) {
            // 產 price
            $price = RandomFloat(100, 500, 2);

            $builder->where('id', $book['id'])
                    ->update(['price' => $price]);
        }
    }

    public function down()
    {
        // 通常不用還原 price
    }
}
