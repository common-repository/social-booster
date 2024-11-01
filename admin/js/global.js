(function($){
  function sb_bf_notice_dismiss(event) {
 	 event.preventDefault();
 	 var ajaxurl = wpvr_global_obj.ajaxurl;
 	 var that = $(this);
 	 $.ajax({
 			 type : "post",
 			 dataType : "json",
 			 url : ajaxurl,
 			 data : { action: "sb_black_friday_offer_notice_dismiss" },
 			 success: function(response) {
 					 if(response.success) {
 							 that.fadeOut('slow');
 							 console.log(response)
 					 }
 			 }
 	 })
  }
  $(document).on('click', '.sb-black-friday-offer .notice-dismiss', sb_bf_notice_dismiss);
})(jQuery);
