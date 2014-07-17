<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class EntrustInsertData extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Insert Roles        
        $admin = new Role;
        $admin->name = 'Upravnik';
        $admin->save();

        $delegate = new Role;
        $delegate->name = 'Delegat';
        $delegate->save();

        $editor = new Role;
        $editor->name = 'Urednik';
        $editor->save();


        // Insert Permissions
        $access_administrator = new Permission(array(
            'name' => 'access_administrator',
            'display_name' => 'Access Administrator'
        ));
        $manage_news = new Permission(array(
            'name' => 'manage_news',
            'display_name' => 'Manage News'
        ));
        $manage_competitions = new Permission(array(
            'name' => 'manage_competitions',
            'display_name' => 'Manage competitions'
        ));
        $manage_registrations = new Permission(array(
            'name' => 'manage_registrations',
            'display_name' => 'Manage registrations'
        ));
        $manage_notices = new Permission(array(
            'name' => 'manage_notices',
            'display_name' => 'Manage notices'
        ));
        $sudo = new Permission(array(
            'name' => 'sudo',
            'display_name' => 'Manage site'
        ));

        $access_administrator->save();
        $manage_news->save();
        $manage_competitions->save();
        $manage_registrations->save();
        $manage_notices->save();
        $sudo->save();


        // Assign permissions to roles
        $admin->addPermissions(array(
            $access_administrator, 
            $manage_news, 
            $manage_competitions, 
            $manage_registrations, 
            $manage_notices, 
            $sudo,
        ));
        $delegate->addPermissions(array(
            $access_administrator,
            $manage_competitions,
            $manage_registrations,
            $manage_notices,
        ));
        $editor->addPermissions(array(
            $access_administrator,
            $manage_news,
        ));

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::table('assigned_roles')->truncate();
        DB::table('permission_role')->truncate();
        DB::table('permissions')->truncate();
        DB::table('roles')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

}