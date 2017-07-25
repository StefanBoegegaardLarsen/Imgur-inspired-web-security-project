<?php
/**
 * Created by PhpStorm.
 * User: dany
 * Date: 05-01-2016
 * Time: 03:05
 */

class SMSApi {
    private $sms_username;
    private $sms_password;

    /**
     * Constructor takes the username ans password for the SMS service
     * @param $user
     * @param $pass
     */
    function __construct($user,$pass){
        $this->sms_username=$user;
        $this->sms_password=$pass;
    }

    /**
     * Function takes care of sending SMS message
     * @param $phone : needs to be danish number and exactly  8 digits long
     * @param $message : Should not exceed 130 characters (incl whitespace) and should preferable be in utf8
     * @return : Returns the message id in the queue on success. on failure returns an error desc.
     */
    public function SendSMS($phone,$message){
        $postData = array(
            'token' => $this->sms_password,
            'userid' => $this->sms_username,
            'phone' => $phone,
            'message' => $message
        );

        $ch = curl_init('https://kallasoft.dk/api/sms_send.php');
        curl_setopt_array($ch, array(
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_POSTFIELDS => $postData
        ));

        $response = curl_exec($ch);

        if($response === FALSE){
            die(curl_error($ch));
        }

        return $response;
    }

    /**
     * Function that returns the SMS messages history
     * @param $date : The date to retrieve SMS messages at. format (YYYY-MM-DD)
     * @return : returns the data as an array of JSON objects
     */
    public function GetInfo($date){
        $ch = curl_init("https://kallasoft.dk/api/sms_info.php?userid=$this->sms_username&token=$this->sms_password&date=$date");
        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => TRUE
        ));

// Send the request
        $response = curl_exec($ch);

// Check for errors
        if($response === FALSE){
            die(curl_error($ch));
        }


// Print the date from the response
        return $response;

    }
    public function SendSMSv2($phone,$message){
        $postData = json_encode(array(
            'token' => $this->sms_password,
            'userid' => $this->sms_username,
            'phone' => $phone,
            'message' => $message
        ));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://kallasoft.dk/api/sms_sendv2.php');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,array("Request"=>$postData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response  = curl_exec($ch);
        if($response === FALSE){
            die(curl_error($ch));
        }
        curl_close($ch);

        return json_decode($response);
    }

    /**
     * Function that returns the SMS messages history
     * @param $date : The date to retrieve SMS messages at. format (YYYY-MM-DD)
     * @return : returns the data as an array of JSON objects
     */
    public function GetInfov2($date){
        $getData = urlencode(json_encode(array(
            'token' => $this->sms_password,
            'userid' => $this->sms_username,
            'date' => $date
        )));
        /*
        $ch = curl_init();
        $url="https://kallasoft.dk/api/sms_infov2.php?request=$getData";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);

        $response  = curl_exec($ch);
        if($response === FALSE){
            die(curl_error($ch));
        }
        curl_close($ch);

        echo $url;

        return json_decode($response);
*/

        // create a new cURL resource
        $ch = curl_init();

        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, "https://kallasoft.dk/api/sms_infov2.php?Request=$getData");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);

        // grab URL and pass it to the browser
        $response  = curl_exec($ch);
        if($response === FALSE){
            die(curl_error($ch));
        }

        // close cURL resource, and free up system resources
        curl_close($ch);
 //       var_dump($response);
        return json_decode($response);
    }

    public function SendEmailv2($receiver,$subject,$message){
        $postData = json_encode(array(
            'token' => $this->sms_password,
            'userid' => $this->sms_username,
            'receiver' => $receiver,
            'subject' => $subject,
            'message' => $message
        ));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://kallasoft.dk/api/email_sendv2.php');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,array("Request"=>$postData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response  = curl_exec($ch);
        if($response === FALSE){
            die(curl_error($ch));
        }
        curl_close($ch);
//        var_dump($response);
        return json_decode($response);
    }

}

