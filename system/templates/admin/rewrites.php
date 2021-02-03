<?php
    require_once 'functions.php';
    require_once("../includes/prmac_framework.php");
    require_once '../includes/header.inc.php';
?>
<link href="/system/css/admin.css" rel="stylesheet">
<?php

    require_once '../includes/nav.inc.php';

    /* 
     * To change this license header, choose License Headers in Project Properties.
     * To change this template file, choose Tools | Templates
     * and open the template in the editor.
     */

    $linkNames = array('company' => 'Company', 'product' => 'Product', 'download' => 'Download', 'image' => 'Image');
    
    if ( ! $isAdmin )
    {
        require_once '../templates/admin/login.php';
        
    }

    elseif( isset($_REQUEST['publishart']) )
    {
        // echo '<pre>';
        // print_r($_REQUEST);
        // echo '</pre>';

        $error = false;
        $post = $_POST['rewrite'];

        $rewrite_id = intval($_POST['rewrite_id']);

        $post['summary'] = nl2br($post['summary']);
        $post['active']='1';
        $post['publish_date']=time();
        
        $text = $_POST['text'];
        $links = $_POST['link'];
        
        foreach ( array_keys ( $linkNames ) as $idx => $linkName )
        {
            $post[$linkName . '_text'] = $text[$idx];
            $post[$linkName . '_url'] = $links[$idx];
        }

        $post['summary'] = release_rewrite($post);
        
        if (!$error)
        {
            //Update
            $query = 'UPDATE rewrites SET ';
            $updateParts = array();
            foreach ($post as $field => $value) {
                $updateParts[] = "{$field} = \"" . $db->escape($value) . '"';
            }
            $query .= implode(', ', $updateParts);
            $query .= ' WHERE rewrite_id = ' . $rewrite_id;
            $db->query($query);
            
            $success = "<div class=\"success\">Post successfully updated.</div>";
            $row = $db->fetch_array($db->query('SELECT release_id, trackback FROM rewrites WHERE rewrite_id = ' . $rewrite_id));

            if ( ! empty ( $row ) && ! empty ( $row [ 'trackback' ] ) )
            {
                stalone_submit_trackback($row['release_id'], $post['title'], $row['trackback']);
            }

        }
    }

    elseif ( isset ( $_REQUEST [ 'delete' ] ) )
    {
        $db->query("DELETE from rewrites WHERE rewrite_id='" . $_POST['rewrite_id']. "'");
        $success = "<div class=\"success\">Post successfully DELETED.</div>";
    }
    
    if (isset($_REQUEST['start']))
    {
        $start = intval ( $_REQUEST [ 'start' ] ) ;
    }

    else
    {
        $start = 0;
    }

    $limit = 50;

    $prev = $start - $limit;

    if (isset($success))
    {
        echo $success;
    }

    if (isset($error))
    {
        echo $error;
    }

    require 'navigation.php';
?>
<style>
    input.btn-generate
    {
        display: inline-block;
        padding: 6px 12px;
        margin-bottom: 0;
        font-size: 14px;
        font-weight: normal;
        line-height: 1.428571429;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        cursor: pointer;
        background-image: none;
        border: 1px solid transparent;
        border-radius: 4px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        -o-user-select: none;
        user-select: none;
        background:#0F6CDC;
        color:white;
        font-weight:bold;
        transition:all linear 0.5s;
    }
    input.btn-generate:hover
    {
        transition:all linear 0.2s;
        background:#0256bb;
        color:white;
    }
    input.btn-publish
    {
        display: inline-block;
        padding: 6px 12px;
        margin-bottom: 0;
        font-size: 14px;
        font-weight: normal;
        line-height: 1.428571429;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        cursor: pointer;
        background-image: none;
        border: 1px solid transparent;
        border-radius: 4px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        -o-user-select: none;
        user-select: none;
        background:green;
        color:white;
        font-weight:bold;
        transition:all linear 0.5s;
    }
    input.btn-publish:hover
    {
        transition:all linear 0.2s;
        background:green;
        color:white;
    }
    .btn
    {
        width:150px;
        
    }

    div.form-group.action-buttons
    {
        text-align:left;
        width:100%;
        margin:0 auto 14px;;
        box-sizing:border-box;
    }

    div.form-group.action-buttons input.respin
    {
        border:1px solid #ccc;
        display:inline-block;
        width: 50%;
        min-width: 100px;
        margin: 0;
        padding:7px 10px;
        border-radius:5px;
        line-height:30px;
        box-sizing:border-box;
        margin-top: -1px;
        float:left;
    }

    div.form-group.action-buttons .btn
    {
        /*margin-right:20px;*/
        line-height:30px;
        float: left;
    }

    .action-buttons .btn, .action-buttons .gen-button {
        margin-right: 5px;
    }


    div.form-group textarea {
        height: 277px;
    }

    div.form-group textarea,
    div.form-group input
    {
        /*border:1px solid #ccc !important;*/
    }

    div.form-group:nth-child(3)
    {
        margin-bottom:0;
        padding-bottom:0;
    }

    div.form-group a
    {
        margin:10px 0 14px 4px;
        position:relative;
        display:inline-block;
        clear:both;
    }
    .hide
    {
        height:0;
        width:0;
        background:transparent;
        color:transparent;
        overflow:hidden;
    }
    .respinbutton {
        float: left;
    }

    .copy{
        display: inline-block;
        padding: 11px 12px;
        margin-left: 5px;
        font-size: 14px;
        font-weight: normal;
        line-height: 1.428571429;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        cursor: pointer;
        background-image: none;
        border: 1px solid transparent;
        border-radius: 4px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        -o-user-select: none;
        user-select: none;
    }

</style>
<div class="col-sm-10">
    <div class="admin-container">
        <h2>Stand Alone</h2>
    <br />
    <div class="row no-margins" id="article_list">
        <?php
        $result = $db->query('SELECT * FROM rewrites WHERE active = 0 AND publish_date < ' . time() . ' ORDER BY publish_date DESC');
        while ($draft = $db->fetch_assoc($result)) : ?>
            <div class="rewrite-container clearfix">
                <form action="/admin/rewrites.php" method="post" id="article_id_<?= $draft [ 'rewrite_id' ] ?>">
                    <div class="col-sm-5">
                        <div class="form-group">
                            <input class="form-control" type="text" name="rewrite[title]" value="<?= $draft['title'] ?>" />
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" rows="12" name="rewrite[summary]" id="summary_<?= $draft [ 'rewrite_id' ] ?>" class="form-control"><?= strip_tags($draft['summary']) ?></textarea>
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="text" name="rewrite[price]" value="<?= $draft['price'] ?>" placeholder="Price" />
                        </div>
                    </div>
                    <div class="col-sm-1 all-links no-pad">
                        <ul class="sortable-titles">
                        <?php foreach ($linkNames as $title): ?>
                            <li><?= $title ?> &gt;</li>
                        <?php endforeach; ?>
                        </ul>
                    </div>
                    
                    <div class="col-sm-6 all-links">
                        <ul class="sortable list">
                        <?php foreach ($linkNames as $link => $title) { ?>
                            
                            <li id="<?= $link ?>">
                                <input type="text" class="form-control text" name="text[]" value="<?= $draft[$link . '_text'] ?>" />
                                <input id="url<?=$draft['rewrite_id']?>_<?=$link ?>" type="text" class="form-control link" name="link[]" value="<?= $draft[$link . '_url'] ?>" /> <a href="javascript:{}" onclick="javascript: window.open($('#url<?=$draft['rewrite_id']?>_<?=$link ?>').val())">&crarr;</a>
                            </li>
                        <?php }
                        ?>
                        </ul>
                    </div>
                    <div class="form-group action-buttons">
                        <a href="https://<?=PRMAC_URI?>/release-id-<?=$draft['release_id']?>.htm" target="_blank">View article on prMac</a>
                        <input type="hidden" name="rewrite_id" value="<?= $draft [ 'rewrite_id' ] ?>" />
                        <div style="clear:both"></div>
                        <input class="btn btn-default hide publish_article" type="submit" name="update" value="Publish"/>
                        <input class="btn btn-publish" type="submit" name="publishart"  value="Publish Article"/>
                        <button type="submit" class="btn btn-default" style="float:left;" data-rewrite_id=<?= $draft [ 'rewrite_id' ] ?>>Spin Article</button>
                        <input class="btn btn-red" type="submit" name="delete" value="Delete Article" />
                        <input type="text" value="" id="stop_words_<?=$draft [ 'rewrite_id' ] ?>" class="respin" placeholder="Ex: MacPlus Software, macOS, Apple Dock">
                        <button onclick="myFunction('<?=$draft [ 'rewrite_id' ] ?>')" class="copy">Copy</button>
                    </div>
                </form>
            </div>
            <hr/>
        <?php endwhile ?>
    </div>
    <!-- Pagination -->
    <div align="right"><?php if ($prev >= 0) { ?><a href="?start=<?php echo $prev; ?>&order=<?php echo $order; ?><?php echo (isset($_REQUEST['uid']) ? '&uid=' . intval($_REQUEST['uid']) : ''); ?>">&laquo; Previous</a> <?php } ?><?php if ($prev >= ($limit - 1)) { ?><a href="?start=<?php echo $start + $limit; ?>&order=<?php echo $order; ?><?php echo (isset($_REQUEST['uid']) ? '&uid=' . intval($_REQUEST['uid']) : ''); ?>">Next &raquo;</a><?php } ?>

  </div>
  </div>
  </div>
  
<?php require_once '../includes/footer.inc.php'; ?>

<script type="text/javascript" defer="defer">
    function allowDrop(ev) {
        ev.preventDefault();
    }

    function drag(ev) {
        ev.dataTransfer.setData("text", ev.target.id);
    }

    function drop(ev) {
        ev.preventDefault();
        var data = ev.dataTransfer.getData("text");
        ev.target.appendChild(document.getElementById(data));
    }

    $(document).ready(function () {
        $('form').on('submit', function (e) {
            return true;
        });

        $('.sortable').sortable({items: '.sortable > li'});
        
        // Fix stupid Firefox bug
        $('input').mouseenter(function(){
            $('.sortable').sortable('disable');
        }).mouseleave(function()
        {
            $('.sortable').sortable('enable');
        });
    });

    
document.getElementById("article_list").addEventListener("click", (e) => {
    
    if ( e.target.tagName == 'BUTTON' && ( e.target.textContent == 'Spin Article' || e.target.textContent == 'Respin Article' ) )
    {
        e.preventDefault();

        const id = e.target.dataset.rewrite_id;
        const article_ref = document.getElementById("summary_" + id);

        let article_val = article_ref.value;
        const stop_word = document.getElementById("stop_words_" + id).value;

        const url = 'http://socialmac.test/spintext';

        fetch(url, {
            method: "post",
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                "article": article_val,
                "stop_word": stop_word
            })
        })
        .then( (response) => {
            return response.json();
        })
        .then( (data) => {

            if(data.status)
            {
                article_ref.value = data.article;
                e.target.innerText = 'Respin Article';
            }
            else 
            {
                console.log(data);
            }
        });
    }
});
</script>