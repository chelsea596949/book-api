<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifySlugConstraint extends Migration
{
    public function up()
    {
        $fields = [
            'slug' => [
                'name' => 'slug',
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
        ];

        $this->forge->modifyColumn('books', $fields);

        // 加 unique index
        $this->db->query('CREATE UNIQUE INDEX slug_unique ON books(slug)');
    }

    public function down()
    {
        // 通常不用還原 slug
    }
}
