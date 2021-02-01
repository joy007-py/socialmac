
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
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
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
    function showMessage(title,message,footer,close){
                var modal='#messageModal';
                $(modal + ' .modal-header .modal-title').html(title);
                $(modal + ' .modal-body').html(message);

                if(close!='yes'){
                    $(modal + ' .modal-header .close').addClass('hide');
                }else{
                    $(modal + ' .modal-header .close').removeClass('hide');
                }

                if(footer=='ok'){
                    $(modal + ' .modal-footer .footer-extra').html('');
                    $(modal + ' .modal-footer').removeClass('hide');
                    $(modal + ' .modal-footer .footer-normal').removeClass('hide');
                    $(modal + ' .modal-footer .btn').html('OK');
                }else if(footer=='close'){
                    $(modal + ' .modal-footer .footer-extra').html('');
                    $(modal + ' .modal-footer').removeClass('hide');
                    $(modal + ' .modal-footer .footer-normal').removeClass('hide');
                    $(modal + ' .modal-footer .btn').html('Close');
                }else if(footer==''){
                    $(modal + ' .modal-footer .footer-extra').html('');
                    $(modal + ' .modal-footer').addClass('hide');

                }else{
                    $(modal + ' .modal-footer').removeClass('hide');
                    $(modal + ' .modal-footer .footer-extra').html(footer);
                    $(modal + ' .modal-footer .footer-normal').addClass('hide');
                }

                $('#messageModal').modal('show');

            }


        function contact(user_id, title){
                var modal='#contactModal';
                $(modal + ' .modal-header .modal-title').html(title);
                $(modal + ' #contact_user_id').val(user_id);
                $(modal).modal('show');
        }

        $('.contactFormModal').on('submit', function(e){
            e.preventDefault();
            var url = $(this).attr("action");
            var formData = $(this).serializeArray();
            $('#contactFormModal button.close').trigger('click');
            
                $.ajax({
                    data: formData,
                    url: url,
                    type: "POST",
                    dataType: "json",
                    success: function (data) {
                        if (data.error != undefined) {
                            if (data.error !== false) {
                                showMessage('Error!',data.error,'ok','yes');
                            }
                            else {
                                showMessage('Success!',data.result,'ok','yes');
                                $('.contactFormModal')[0].reset();
                            }
                        } else {
                            alert('The script was not called properly, because data.error is undefined.');
                        }
                    }

                });
        });
        
        function toggleSidebar(){
            $('.side-bar').toggleClass('enabled');
            $('.page-cover').toggleClass('enabled');
            
            if($('.page-cover').hasClass('enabled')){
                $('.page-cover').css('height',$('html').height());
                $('html, body').animate({scrollTop:0}, 1000);
                $('.navbar-collapse').collapse('hide');
            }else{
                $('.page-cover').css('height','');
            }
        }
        
        $('.page-cover').on('click',function(){
            toggleSidebar();
        });
</script>
<script type="text/javascript" src="/system/js/prmac-linker.js"></script>