<?php  $page = 'confirmar'; require_once("header.php"); ?>

<script type="text/javascript">
	swal({  title: "Are you sure?",   
			text: "You will not be able to recover this imaginary file!",   
			type: "warning",   
			showCancelButton: true,   
			confirmButtonColor: "#DD6B55",   
			confirmButtonText: "Yes, delete it!",   
			closeOnConfirm: false,
			showLoaderOnConfirm: true
		}, function(isConfirm){
            if(isConfirm){
            	setTimeout(function(){     swal("Ajax request finished!");   }, 2000);
            	//swal("Deleted!", "Your imaginary file has been deleted.", "success"); 
            }
         });
				

</script>
<?php require_once("footer.php"); ?>
