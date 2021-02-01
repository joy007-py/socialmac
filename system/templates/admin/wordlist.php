<?php

require_once 'functions.php';
require_once '../includes/header.inc.php';
require '../includes/wordlist.class.php';

$wordlist = new WordList();
?>
<link href="/system/css/admin.css" rel="stylesheet">

<?php require_once '../includes/nav.inc.php'; ?>

<?php
    if ( ! $isAdmin )
    {
        require_once '../templates/admin/login.php';
        die;
    }
?>
<?php
    if( ! empty($_POST) )
    {
        try {
            $wordlist->saveData( $_POST );
            echo '<div class="success">Word List Updated.</div>';
        } catch( \Exception $e ) {
            echo '<div class="error">'. $e->getMessage() .'</div>';
        }
    }
?>
<div class="container">
    <div class="row">
        <?php require __DIR__  .'/navigation.php'; ?>

        <div class="col-sm-10 col-md-10">
            <form method="POST">
                <div class="row">
                    <?php $i = 1 ?>
                    <?php foreach( $wordlist->getCurrentWordList() as $key => $value ) : ?>
                        <div class="col-sm-3 col-md-3">
                            <h1 style="color: black">Column_<?php echo $i ?></h1>
                            <div class="form-group">
                                <textarea class="form-control" name="<?php echo $key ?>" rows="20"><?php echo $value ?></textarea>
                            </div>
                        </div>
                        <?php $i++ ?>
                    <?php endforeach ?>
                </div>
                <input type="submit" value="Save" class="btn btn-danger" />
            </from>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.inc.php'; ?>