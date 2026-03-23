<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLevelToUsers extends Migration
{
    public function up()
    {
        $fields = [
            'level' => [
                'type' => 'INT',
                'constraint' => 3,
                'null' => false,
                'default' => 2,
                'comment' => '使用者等級(1:管理員, 2:一般會員)',//SQLite 不支援 comment，MySQL 才會生效
            ],
        ];

        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'level');
    }
}
