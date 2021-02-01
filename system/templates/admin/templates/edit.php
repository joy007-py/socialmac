<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if(isset($_POST['delete'])) {

    $release_id=$_POST['release_id'];

        if($release_id !="" ){

        //remove from sitemap
        $doc = new DOMDocument;
        $doc->load('../sitemap/sitemap.xml');

        $thedocument=$doc->documentElement;

        $list = $thedocument->getElementsByTagName('url');

        //figure out which ones you want -- assign it to a variable (ie: $nodeToRemove )
        $nodeToRemove = null;
        foreach ($list as $domElement){

          if (strpos($domElement->textContent, $release_id)) {
            $nodeToRemove = $domElement; //will only remember last one- but this is just an example :)
          }
        }

        //Now remove it.
        if ($nodeToRemove != null)
            $thedocument->removeChild($nodeToRemove);

        $doc->save('../sitemap/sitemap.xml'); 



        $db->query("UPDATE rewrites SET active='0' WHERE release_id='" . $_POST['release_id'] . "'");

        $error='<div class="success">Article Deleted.</div><br/><a href="index.php"><< Home </a>';
    }else{
        $error="ERROR! RELEASE NOT SPECIFIED.";
    }
    
}elseif(isset($_POST['update'])){
    $query="UPDATE rewrites SET title='" . $db->escape($_POST['title']) . "', summary='" . $db->escape($_POST['summary']) . "' WHERE release_id='" . $_POST['release_id'] . "'";
    $db->query($query);
    $success='<div class="success">Article Saved.</div>';
}


$release_id=$_GET['id'];

$release = get_release($release_id);
    
echo $error;
if($error)
    exit();

echo $success;
?>
<h2>Edit Article</h2>
<div>
    <form method="post" action="/admin/edit/<?php echo $release_id?>">
        <input type="hidden" name="release_id" value="<?php echo $release_id?>" />
        <div class="form-group">
            <input type="text" class="form-control" name="title" value="<?=$release['title']?>" />
        </div>
        <div class="form-group">
            <textarea class="form-control" name="summary" rows="10"><?=$release['summary']?></textarea>
        </div>
        
        
        <input type="submit" name="update" value="Save" class="btn btn-default" />&nbsp;&nbsp;
        <input type="submit" name="delete" value="Delete" class="btn btn-danger" />
    </form>
</div>

        
