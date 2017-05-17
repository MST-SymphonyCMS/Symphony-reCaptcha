//Includes 
 //<script src='https://www.google.com/recaptcha/api.js'></script>

//reCaptcha callback function

	  function recaptcha_callback(){

		  	var g_recaptcha_key = $("#g-recaptcha-response").val();
		  	var g_recaptcha_s_key = $("#s-id").val();
		  	var url = location.hostname; 
				$.post( 
					//Set the path if it's different
                  "/extensions/recaptcha/content/reCaptcha.php",
                  { 
					recaptcharesponse: g_recaptcha_key,
					s_key: g_recaptcha_s_key
				  },
                  function(data) {
                    if(data ==="success"){
						//Do validation,form submit
						$("#g-recaptcha-data").val(1);
						$('#g-re-cp-error').hide();	
						
					}else{
						alert('Somthing went wrong');
					}
                  }
               );



      }

//reCaptcha form validations (optionals)
$( document ).ready(function() {
	
//Register form 	
$('#g-re-cp-error').hide();	
$("#register").submit(function(e) {

if(1) {	
       	var value = $('#g-recaptcha-data').val();	

		if(value == "1"){		
		//alert("submit");
		}else{
		e.preventDefault();
		//alert('test');
		$('#g-re-cp-error').css("display", "block");
		$('#g-re-cp-error').removeClass("hide");
		$('#g-re-cp-error').text("reCAPTCHA field is required.");
			
		}

		  } else {
       //do some error handling
    }

    
});
	
});






// Add this div set  to form ..........................................

// <div class="g-recaptcha" data-callback="recaptcha_callback" data-sitekey="{$recaptcha-sitekey}"></div>
// <input id="s-id" type="hidden" value="{$recaptcha-secret-id}" />
// <input id="g-recaptcha-data" name="fields[recaptcha]" type="hidden" value="" />
// <label id="g-re-cp-error" class="error_re hide">This field is required.</label>