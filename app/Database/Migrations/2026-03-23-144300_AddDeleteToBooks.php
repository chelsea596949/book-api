<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDeleteToBooks extends Migration
{
    public function up()
    {
        $fields = [
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ];

        $this->forge->addColumn('books', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('books', 'deleted_at');
    }
}
