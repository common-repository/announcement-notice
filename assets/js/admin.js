
function enable_disable_notice(e){
   let isChecked = e.checked ? 1 : 0;
   let postId = e.value;

   if( isChecked ){
      cuteToast({
         type: "success", // or 'info', 'error', 'warning'
         title: "Notice Enabled!",
         message: "",
         timer: 5000
       })
   }else{
      cuteToast({
         type: "success", // or 'info', 'error', 'warning'
         title: "Notice Disabled!",
         message: "",
         timer: 5000,
       })
   }

   let rData = {
      post_id: postId,
      is_active: isChecked,
      nonce: window.api_base_url.nonce
   }

   jQuery.ajax({
      data: rData,
      type: "post",
      url: window.api_base_url.root_url + "announcement-notice/v1/notice-enable",  
      beforeSend: function (e) {
         e.setRequestHeader("X-WP-Nonce", window.api_base_url.nonce);
      },
      success: function (r) {
         console.log('success');
      },
      error: function (e) {
         console.log("Error!!!");
      }
   });
}