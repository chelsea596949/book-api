<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddImageToBooks extends Migration
{
    public function up()
    {
        $fields = [
            'image_url' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
        ];

        $this->forge->addColumn('books', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('books', 'image_url');
    }
}
