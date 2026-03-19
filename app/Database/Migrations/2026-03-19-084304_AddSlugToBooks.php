<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSlugToBooks extends Migration
{
    public function up()
    {
        $fields = [
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'title',
            ],
        ];

        $this->forge->addColumn('books', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('books', 'slug');
    }
}
