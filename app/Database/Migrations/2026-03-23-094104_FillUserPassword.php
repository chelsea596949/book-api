<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FillUserPassword extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('users');

        $users = $builder->get()->getResultArray();

        foreach($users as $user) {

            // 產 password
            $password = password_hash($user['uid'], PASSWORD_DEFAULT);

            $builder->where('uid', $user['uid'])
                    ->update(['password' => $password]);
        }
    }

    public function down()
    {
        // 通常不用還原 password
    }
}
