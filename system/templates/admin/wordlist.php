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
<style>
.splClassAdd {
    background: #F0F0F0;
    padding: 20px;
    margin-top: 20px;
    border-radius: 5px;
    -webkit-box-shadow: 0px 0px 30px -13px rgba(0,0,0,0.75);
    -moz-box-shadow: 0px 0px 30px -13px rgba(0,0,0,0.75);
    
    box-shadow: 0px 0px 30px -13px rgba(0,0,0,0.75);
}
.splClassAdd textarea {
  margin-top: 25px;
  -moz-border-bottom-colors: none;
  -moz-border-left-colors: none;
  -moz-border-right-colors: none;
  -moz-border-top-colors: none;
  /*background: none repeat scroll 0 0 rgba(0, 0, 0, 0.07);*/
  border-color: -moz-use-text-color #FFFFFF #FFFFFF -moz-use-text-color;
  border-image: none;
  border-radius: 6px 6px 6px 6px;
  border-style: none solid solid none;
  border-width: medium 1px 1px medium;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.12) inset;
  color: #555555;
  font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
  font-size: 1em;
  line-height: 1.4em;
  padding: 5px 8px;
  transition: background-color 0.2s ease 0s;
  resize: none;
  text-align:center;
}
.splClassAdd textarea:focus {
    background: none repeat scroll 0 0 #FFFFFF;
    outline-width: 0;
}
.columnBox{
    padding:0 20px;
}
.colHeadingText{
    color: #000;
    text-align:center;
    font-family: "Open Sans", Helvetica, sans-serif;
}
</style>
<div class="columnBox">
    <div class="row">
        <?php require __DIR__  .'/navigation.php'; ?>

        <div class="col-sm-10 col-md-10 splClassAdd">
            <form method="POST">
                <div class="row">
                    <?php $i = 1 ?>
                    <?php foreach( $wordlist->getCurrentWordList() as $key => $value ) : ?>
                        <div class="col-sm-3 col-md-3">
                            <h3 class="colHeadingText">Column_<?php echo $i ?></h3>
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