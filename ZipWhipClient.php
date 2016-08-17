<?php
/**
 * Created by PhpStorm.
 * User: brianlogan
 * Date: 8/17/16
 * Time: 9:28 AM
 */

namespace CollingMedia;


class ZipWhipClient {

    private $api_key;

    /**
     * ZipWhipClient constructor.
     * @param $userid
     * @param $password
     * @throws \Exception
     */
    public function __construct($userid, $password) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.zipwhip.com/user/login");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "username={$userid}&password={$password}");
        curl_setopt($ch, CURLOPT_POST, 1);
        $result = json_decode(curl_exec($ch));
        if (curl_errno($ch)) {
            throw new \Exception('Error:' . curl_error($ch));
        }
        curl_close ($ch);
        if($result->success == true) {
            $this->api_key = $result->response;
        } else {
            throw new \Exception("ZIPWHIP: Unable to login, check your user credentials");
        }
    }

    /**
     * @param $contactid
     * @return bool
     */
    public function deleteContact($contactid) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.zipwhip.com/contact/delete");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "contact={$contactid}&session={$this->api_key}");
        curl_setopt($ch, CURLOPT_POST, 1);
        $result = json_decode(curl_exec($ch));
        if (curl_errno($ch)) {
            throw new \Exception('Error:' . curl_error($ch));
        }
        curl_close ($ch);
        if($result->success == true) {
            return $result->response;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function listContacts() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.zipwhip.com/contact/list");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "session=41048e5c-2334-4d99-ac82-c5de15ea15c6:309626613");
        curl_setopt($ch, CURLOPT_POST, 1);
        $result = json_decode(curl_exec($ch));
        if (curl_errno($ch)) {
            throw new \Exception('Error:' . curl_error($ch));
        }
        curl_close ($ch);
        if($result->success == true) {
            return $result->response;
        } else {
            return false;
        }
    }

    /**
     * Refer to https://www.zipwhip.com/api/curl/contact/save
     * @param $contactInformation array
     * @return bool
     * @throws \Exception
     */
    public function saveContact($contactInformation) {
        $query = [];
        foreach($contactInformation AS $k => $v) {
            $query[] = $k."=".$v;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.zipwhip.com/contact/save");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, implode("&", $query)."&session={$this->api_key}");
        curl_setopt($ch, CURLOPT_POST, 1);
        $result = json_decode(curl_exec($ch));
        if (curl_errno($ch)) {
            throw new \Exception('Error:' . curl_error($ch));
        }
        curl_close ($ch);
        if($result->success == true) {
            return $result->response;
        } else {
            return false;
        }
    }

    /**
     * @param $number
     * @param $message
     * @return bool
     */
    public function sendMessage($number, $message) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.zipwhip.com/message/send");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "body={$message}&contacts=ptn:/{$number}&session={$this->api_key}");
        curl_setopt($ch, CURLOPT_POST, 1);
        $result = json_decode(curl_exec($ch));
        if (curl_errno($ch)) {
            throw new \Exception('Error:' . curl_error($ch));
        }
        curl_close ($ch);
        if($result->success == true) {
            return $result->response;
        } else {
            return false;
        }
    }
}