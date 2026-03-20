<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPriceToBooks extends Migration
{
    public function up()
    {
        $fields = [
            'price' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
                'default' => 0.00,
            ],
        ];

        $this->forge->addColumn('books', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('books', 'price');
    }
}
