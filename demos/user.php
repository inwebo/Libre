<?php
namespace Libre{

    include_once 'header.php';
    use Libre\Models\User;
    use Libre\Database\Driver\MySql;
    use Libre\Models\User\Role;
    use Libre\Models\User\Role\Permission;

    try
    {
        $driver = new MySql('localhost', 'www.inwebo.net', 'root', 'root');

        User::setConfiguration($driver,'id','Users');



        Role::setConfiguration($driver,'id','Roles');
        Permission::setConfiguration($driver,'id','Permissions');
        User::setDefaultRoleId(2);

        //var_dump(User::getConfiguration());

        $user = User::load(1);
        //var_dump($user);
        //var_dump($user->toPublic());
        //echo '<hr>';
        //var_dump($user->isDefault());
        //echo '<hr>';
        //var_dump($user->hasRole(1));
        //echo '<hr>';
        //var_dump($user->can('read'));
        //var_dump($user->is('Root'));
        //var_dump($user->is('d'));
        //echo '<hr>';
        //echo '<hr>';
        //var_dump($user->can('read'));
        //var_dump($user->can('proutie'));
        //echo '<hr>';
        //echo '<hr>';
        //var_dump($user->hasPermission(1));

        $_SESSION['User'] = $user;
        var_dump($_SESSION['User']->is('Root'));

    }
    catch(\Exception $e)
    {
        var_dump($e);
    }
}