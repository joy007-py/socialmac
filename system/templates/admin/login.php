<?php


    if(isset($_POST['submit'])){
        if($_POST['username']=='admin' && $_POST['password']=='socialauthorize2018'){
            setcookie('admin', '4sk1psk', time() + (86400 * 30), "/"); // 86400 = 1 day
        }
        header('Location: /admin/');
    }
    
    
    
    ?>
<div class="col-sm-4 col-sm-offset-4">
    <form class="login-form" action="/admin/login" method="post">
        <div class="form-group">
            <input type="text" class="form-control" name="username" placeholder="Username"/>
        </div>
        <div class="form-group">
            <input type="password" class="form-control" name="password" placeholder="Password" />
        </div>
        <input type="submit" name="submit" class="btn btn-default" value="login" />
    </form>
</div>