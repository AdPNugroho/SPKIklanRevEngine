<?php

use Illuminate\Database\Seeder;
class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tbl_admin')->delete();
        DB::table('tbl_admin')->insert(array(
            array(
                'username'=>'admin',
                'password'=>bcrypt('admin')
            )
        ));
    }
}
