<?php
 function post_captcha($user_response, $s_id) {
        $fields_string = '';
        $fields = array(
            'secret' => $s_id, 
            'response' => $user_response
        );
        foreach($fields as $key=>$value)
        $fields_string .= $key . '=' . $value . '&';
        $fields_string = rtrim($fields_string, '&');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, True);

        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);

    }

        
        
    // Call the function post_captcha
     $user_response = $_POST['recaptcharesponse'];
     $s_id = $_POST['s_key'];
     $res = post_captcha($user_response,  $s_id );



    if (!$res['success']) {
        // What happens when the CAPTCHA wasn't checked
        echo "not success";
    } else {
       
        echo "success";
    }
        


    