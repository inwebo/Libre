<?php
include('../Libre/database/autoload.php');
include('../Libre/models/user/autoload.php');
include('../modules/authuser/models/authuser/class.authuser.php');


use Libre\Models\User;
use Libre\Database\Driver\MySql;
use Libre\Modules\AuthUser\Models\AuthUser;

?>

<html>
<head></head>

<body>
    <h1>Users</h1>
    <?php
    $driver = new MySql('localhost','UsersRoles','root','root');
    AuthUser::binder($driver,'id','Users');

        //$user = new AuthUser('inwebo','inwebo@gmail.com', 'test','3petitscochonsdanslesbois!');
        $user=AuthUser::load(1);
        
        //var_dump($user->hasRole(1));
        //var_dump($user->hasPermission(1));
        //$user->save();
        var_dump($user);
    ?>
</body>
</html>
