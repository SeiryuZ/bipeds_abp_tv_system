<input type="hidden" value="1" id="page">
  </div>
  


<script type="text/javascript" src="<?php echo asset_url()."js/jquery.js";?>" ></script>
<script type="text/javascript">
  
  $(window).load(function(){
    setTimeout(loadDetail,5000);
    //loadDetail();
    var message ="";
    function loadDetail() {
      

      $.ajax({
        type: 'POST',
        data:{p:$('#page').val()},
        url: "index.php/load/detail",
        complete: function( msg, textStatus ){

          if ( message != msg.responseText )
          {
           
            message = msg.responseText;


            //var $msg = $(msg).hide();
            //$(".comments").prepend($msg);
            //$(".main-content .table:first").slideDown("slow");


            //$('.main-content').hide('slow',function(){

            //});
            
            $('.content').remove();
            $('.main-content').hide();
            $('.main-content').prepend(msg.responseText);
            $('.main-content').show(1000);


          }
          //alert (msg.responseText);
          //console.log(msg);
          setTimeout(loadDetail,20000);
        }

      });
      
    }

  });
  



</script>
  
</body>