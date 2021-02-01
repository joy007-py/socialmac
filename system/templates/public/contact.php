<?php

require_once("../includes/prmac_framework.php");


require_once '../includes/header.inc.php';
?>

<?php
$page="contact";
require_once '../includes/nav.inc.php';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>



<div>
    <div class="section-heading-page">
      <div class="container">
        <div class="row">
          <div class="col-sm-12">
            <h1 class="heading-page title">Contact Us</h1>
          </div>
          
        </div>
      </div>
    </div>

<!-- GRIDS -->
    <!--===============================================================-->
    <div class="container">
      <div class="row">
        <!-- GRID POSTS -->
        <!--===============================================================-->
        <div class="col-sm-8 grid-posts">
          <!-- POST ITEM 1 -->
          <div class="row">
            <div class="col-sm-12">
                <section class="content wrap" >
			
                    
                        <p class="justify">
                        Welcome!  We¹d like to thank you for visiting and hope you enjoy your time
                        while on the site. socialMac is a daily news service for new and updated app
                        information, offered in concise digestible summaries. We only run reviews of
                        hardware and software that we deem interesting to our audience. socialMac
                        leverages industry standard Adsense and Amazon ads.
                        </p>
                    
			
		</section>
                <section class="contact">
			

                    
                      
                        <div class="panel panel-light mt-20">
                            
                            
                            <div class="panel-body">
                                <h3>Drop us a line</h3>
                                <form role="form" action="" method="post" id="newContact">

                                    <div class="form-group">
                                        <input type="text" name="contact[name]" class="form-control" placeholder="Your Name" value=""/>
                                    </div>
                                    <div class="form-group">
                                        <input type="email" name="contact[email]" class="form-control" placeholder="Your email" value=""/>
                                    </div>
                                    <div class="form-group">
                                        <textarea name="contact[message]" class="form-control" placeholder="Message..."></textarea>
                                    </div>
                                    <div class="form-group">
                                        <div class="g-recaptcha" data-sitekey="6LdgXRUTAAAAAOZXhdaAvTMbbB1M3LSYnQN5zSwV"></div>
                                    </div>
                                   
                                    <input type="hidden" name="sendContact" value="1"/>
                                    <div class="clearfix">
                                        <div class="pull-right">
                                          <p class="submit">
                                                <input id="newCommentButton" type="submit" class="btn btn-default" value="submit" />
                                            </p>
                                            <div id="data-result"></div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            
                            
                        </div>
		</section>
		
		
              
            </div>
          </div>
        </div>
        <!-- GRID POSTS END -->
        <!--===============================================================-->
        <div class="col-sm-4">
            <?php include_once('../templates/sidebar.php'); ?>
        </div>
      </div>
    </div>
</div>

    



<?php
require_once '../includes/footer.inc.php';
?>
<script>

            
$("#newContact").submit(function(event){
    // setup some local variables
    var $form = $(this),
        // let's select and cache all the fields
        $inputs = $form.find("input, select, button, textarea"),
        // serialize the data in the form
        serializedData = $form.serialize();
    $('#newCommentButton').prop("disabled",true);
    //var resultDiv=$(this).attr('data-result');
    $('#data-result').html('<img class="loader" src="/system/images/load-indicator.gif" />');
    // let's disable the inputs for the duration of the ajax request
    //$inputs.attr("disabled", "disabled");

    // fire off the request to /form.php
    $.ajax({
        url: "/system/ajax/contact.php",
        type: "post",
        data: serializedData,
        // callback handler that will be called on success
        success:function(data){
            if(data.error != undefined){
                if(data.error !== false){
                    showMessage('Error',data.error,'ok','yes');
                    $('#data-result').html("");
                }
                else{
                    showMessage('Success!',data.success,'ok','yes');
                    $('#data-result').html("Sent.");
                    //$('#data-result').html("");
                    //$('#newComment')[0].reset();
                    //$form.reset();
                    $('#newContact')[0].reset();
                }
            }
        },
        complete:function(){
            $('#newCommentButton').removeAttr('disabled');
        }
    });

    // prevent default posting of form
    event.preventDefault();
    
   
});
</script>
<?php

require_once '../includes/close.inc.php';