<?php

require_once '../includes/header.inc.php';

?>
<link href="/system/css/admin.css" rel="stylesheet">

<?php require_once '../includes/nav.inc.php'; ?>

<?php

    if( ! empty($_POST) )
    {
        echo 'got it';

        echo '<pre>';
        print_r($_POST);
        echo '</pre>';
        die;
    }
 
?>


<div class="container">
    <div class="row">
        <?php require __DIR__  .'/navigation.php'; ?>

        <div class="col-sm-10 col-md-10">
            <form method="POST">
                <div class="row">   
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group">
                            <textarea class="form-control" name="list_one" rows="10">
                                text one | text simillar
                                text two | text simillar
                                text three | text simillar
                                text four | text simillar
                            </textarea>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group">
                            <textarea class="form-control" name="list_two" rows="10">
                                text one | text simillar
                                text two | text simillar
                                text three | text simillar
                                text four | text simillar
                            </textarea>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group">
                            <textarea class="form-control" name="list_three" rows="10">
                                text one | text simillar
                                text two | text simillar
                                text three | text simillar
                                text four | text simillar
                            </textarea>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group">
                            <textarea class="form-control" name="list_four" rows="10">
                                text one | text simillar
                                text two | text simillar
                                text three | text simillar
                                text four | text simillar
                            </textarea>
                        </div>
                    </div>
                </div>
                <input type="submit" value="Save" class="btn btn-danger" />
            </from>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.inc.php'; ?>

