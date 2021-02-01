
</div>



    <footer id="footer" class="midnight-blue">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    &copy; <?=date('Y')?> socialMac. All Rights Reserved.
                </div>
                <div class="col-sm-6">
                    <ul class="pull-right">
                        <li><a href="/sitemap">Sitemap</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer><!--/#footer-->
    
    <style>
    .gen-button
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
        width: 150px;
        padding: 12px 20px;
        color: #fff;
        border-radius: 4px;
        border: none;
        /*margin-top: 10px;*/
        display:none;
        transition:all linear 0.5s;
    }
    .gen-button:hover,
    .gen-button:focus
    {
        transition:all linear 0.2s;
    }
    .green
    {
        background:#1aa000;
    }
    .green:hover,
    .green:focus
    {
        background:#158200;
        color:white;
    }
    .blue
    {
        background:#0F6CDC;
    }
    
    .blue:hover,
    .blue:focus
    {
        background:#0256bb;
        color:white;
    }
    </style>
    <!-- MESSAGE MODAL -->
    <!--===============================================================-->
    <!-- Modal -->
    <div id="messageModal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Modal Header</h4>
          </div>
          <div class="modal-body">
            <p>Some text in the modal.</p>
          </div>
          <div class="modal-footer">
              <div class="footer-extra"></div>
             <div class="footer-normal">
                <!-- <button type="button" class="gen-button green" onclick="submit_article()">Publish Article</button>
                <button type="button" class="gen-button blue" onclick="spin_article()">Respin Article</button> -->
                <!-- <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button> -->
             </div>
          </div>
        </div>

      </div>
    </div>
    <!--========================END MESSAGE MODAL=======================================-->

    <script src="/system/js/jquery.js"></script>
    <script src="/system/js/bootstrap.min.js"></script>
    <script src="/system/js/jquery.prettyPhoto.js"></script>
    <script src="/system/js/jquery.isotope.min.js"></script>
    <script src="/system/js/main.js"></script>
    <script src="/system/js/wow.min.js"></script>
    <script src="/system/js/jquery.sortable.js"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
    
    <script>
        function myFunction(artical_id) {
          /* Get the text field */
          var copyText = document.getElementById("stop_words_"+ artical_id);
          event.preventDefault();
          /* Select the text field */
          copyText.select();
          copyText.setSelectionRange(0, 99999); /*For mobile devices*/

          /* Copy the text inside the text field */
          document.execCommand("copy");

          /* Alert the copied text */
          alert("Copied text: " + copyText.value);
        }
        function showMessage(title,message,footer,close){

            var modal='#messageModal';
            $(modal + ' .modal-header .modal-title').html(title);

            if(message.match('article-rewrite_id='))
            {
                event.preventDefault();

                $('.blue').hide();
                //$(modal + ' .modal-body').html((document.getElementById()); 

                window.article_id = message.replace('article-rewrite_id=','');
                window.url = '/admin';
                window.formData = 'respin='+$('form#article_id_'+window.article_id+' textarea').text()+'&stop_words='+$('form#article_id_'+window.article_id+' input.respin').val();
                spin_article();
                
            }

            else if ( close != 'yes' )
            {
                $(modal + ' .modal-header .close').addClass('hide');
            }
            else
            {
                $(modal + ' .modal-header .close').removeClass('hide');
            }

            if(footer=='ok')
            {
                $(modal + ' .modal-footer .footer-extra').html('');
                $(modal + ' .modal-footer').removeClass('hide');
                $(modal + ' .modal-footer .footer-normal').removeClass('hide');
                $(modal + ' .modal-footer .btn').html('OK');
            }

            else if(footer=='close'){
                $(modal + ' .modal-footer .footer-extra').html('');
                $(modal + ' .modal-footer').removeClass('hide');
                $(modal + ' .modal-footer .footer-normal').removeClass('hide');
                $(modal + ' .modal-footer .btn').html('Close');
            }

            else if(footer=='cancel')
            {
                $(modal + ' .modal-footer .footer-extra').html('');
                $(modal + ' .modal-footer').removeClass('hide');
                $(modal + ' .modal-footer .footer-normal').removeClass('hide');
                //$(modal + ' .modal-footer .btn').html('CANCEL');
            }

            else if ( footer == '' )
            {
                $(modal + ' .modal-footer .footer-extra').html('');
                $(modal + ' .modal-footer').addClass('hide');

            }

            else
            {
                $(modal + ' .modal-footer').removeClass('hide');
                $(modal + ' .modal-footer .footer-extra').html(footer);
                $(modal + ' .modal-footer .footer-normal').addClass('hide');
            }

            //$('#messageModal').modal('show');
            $("#respinbutton_"+ window.article_id).css('display','block');
            $("#spinbutton_"+ window.article_id).css('display','none');
            

        }

        function submit_article()
        {
            $('form#article_id_'+window.article_id+' textarea').html($('#messageModal .modal-body').html());
            $('#messageModal .btn.btn-primary').click();
            setTimeout(function()
            {
                $('form#article_id_'+window.article_id+' .publish_article').click();
            },500);
        }

        function publishArticle(article_id)
        {
            //$('form#article_id_'+article_id+' textarea').html($('#summary_'+article_id).val());
            //$('#messageModal .btn.btn-primary').click();
           event.preventDefault(); 
           //alert(article_id);
            setTimeout(function()
            {
                $('form#article_id_'+article_id+' .publish_article').click();
            },500);
        }

        function spin_article()
        {


            var modal='#messageModal';
            var article_id = window.article_id;
            $(modal + ' .modal-body').html('Getting your article ready!');
            $('#messageModal').modal('show');
            $('.blue').hide();
            $('.green').hide();

            $.ajax({
                data: window.formData,
                type: "POST",
                url: window.url,
                dataType: "json",
                success: function (data)
                {

                    if (data.success != undefined)
                    {

                        if (data.success == false)
                        {
                            $(modal + ' .modal-body').html(data.error);
                            $('.blue').hide();
                            $('.green').hide();
                        }

                        else
                        {
                            //$(modal + ' .modal-body').html(data.article);
                            $('#summary_'+ article_id).val(data.article)
                            $('.blue').show();
                            $('.green').show();
                            $('#messageModal').modal('hide');
                        }

                    }

                    else
                    {
                        alert('The script was not called properly, because data.error is undefined.');
                    }

                }

            });

        }

        function contact(user_id, title)
        {
            var modal='#contactModal';
            $(modal + ' .modal-header .modal-title').html(title);
            $(modal + ' #contact_user_id').val(user_id);
            $(modal).modal('show');
        }

        $('.contactFormModal').on('submit', function(e)
        {

            e.preventDefault();
            var url = $(this).attr("action");
            var formData = $(this).serializeArray();
            $('#contactFormModal button.close').trigger('click');
            
            $.ajax({
                data: formData,
                url: url,
                type: "POST",
                dataType: "json",
                success: function (data)
                {
                    if (data.error != undefined)
                    {

                        if (data.error !== false)
                        {
                            showMessage('Error!',data.error,'ok','yes');
                        }

                        else
                        {
                            showMessage('Success!',data.result,'ok','yes');
                            $('.contactFormModal')[0].reset();
                        }

                    }

                    else
                    {
                        alert('The script was not called properly, because data.error is undefined.');
                    }

                }

            });

        });
        
        function toggleSidebar()
        {

            $('.side-bar').toggleClass('enabled');
            $('.page-cover').toggleClass('enabled');
            
            if($('.page-cover').hasClass('enabled'))
            {
                $('.page-cover').css('height',$('html').height());
                $('html, body').animate({scrollTop:0}, 1000);
                $('.navbar-collapse').collapse('hide');
            }

            else
            {
                $('.page-cover').css('height','');
            }

        }
        
        $('.page-cover').on('click',function(){
            toggleSidebar();
        });

</script>
<?php require_once dirname(__DIR__) . '/js/prmac-linker.js.php' ?>
