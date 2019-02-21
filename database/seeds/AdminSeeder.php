<?php

use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $admin = new Admin();
      $admin->name = "Jobi Admin";
      $admin->type = 1;
      $admin->email = "jishad@codebreaze.com";
      $admin->username = "admin";
      $admin->password = Hash::make('admin@123');
      $admin->secret_pin = Hash::make('9633');
      $admin->save();

      $admin = new Admin();
      $admin->name = "HR Executive";
      $admin->type = 3;
      $admin->email = "info@codebreaze.com";
      $admin->username = "hrexecutive";
      $admin->password = Hash::make('hr@nursesgroup565');
      $admin->secret_pin = Hash::make('7664');
      $admin->save();
    }
}
