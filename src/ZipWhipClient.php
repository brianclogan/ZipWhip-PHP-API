<?php
/**
 * Created by PhpStorm.
 * User: brianlogan
 * Date: 8/17/16
 * Time: 9:28 AM
 */

namespace CollingMedia;


class ZipWhipClient {
    const BASE_URL = "https://api.zipwhip.com/";
    /**
     * @var string
     */
    private $api_key;

    /**
     * ZipWhipClient constructor.
     * @param $apiKey
     */
    public function __construct($apiKey) {
        $this->api_key = $apiKey;
    }

    /**
     * @param $userid
     * @param $password
     * @return ZipWhipClient
     * @throws \Exception
     */
    static function authenticate($userid, $password) {
        $result = static::post("user/login", array("username" => $userid, "password" => $password));

        if ($result['success'] == true) {
            $zipwhipClient = new ZipWhipClient($result['response']);
            return $zipwhipClient;
        } else {
            throw new \Exception("ZIPWHIP: Unable to login, check your user credentials");
        }
    }

    /**
     * @param $contactid
     * @return bool
     * @throws \Exception
     */
    public function deleteContact($contactid) {
        $result = $this->post("contact/delete", array("contact" => $contactid, "session" => $this->api_key));

        if (!array_key_exists('success', $result)) {
            return false;
        }
        return $result['success'];
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function listContacts() {
        $result = $this->post("contact/list", array("session" => $this->api_key));

        if ($result['success'] == true) {
            return json_encode($result['response']);
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
        foreach ($contactInformation AS $k => $v) {
            $query[] = $k . "=" . $v;
        }
        $query['session'] = $this->api_key;
        $result = $this->post("contact/save", $query);

        if (!array_key_exists('success', $result)) {
            return false;
        }
        return $result['success'];
    }

    /**
     * @param $number
     * @param $message
     * @return bool
     * @throws \Exception
     */
    public function sendMessage($number, $message) {
        $result = $this->post("message/send",
            array('body' => $message, 'contacts' => $number, 'session' => $this->api_key)
        );

        // TODO: For tracking the message sending progress you will want to capture the messageID.
        // In the case of a 1:1 message it would be return $result['response']['root'];
        if (!array_key_exists('success', $result)) {
            return false;
        }
        return $result['success'];
    }

    private static function post($method, $parameters) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => sprintf("%s%s", static::BASE_URL, ltrim($method, "/")),
            CURLOPT_POSTFIELDS => http_build_query($parameters),
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_ENCODING => "",
            CURLOPT_HEADER => 0,
            CURLOPT_POST => 1,
//            CURLOPT_VERBOSE => 1,
        ));

        $response = json_decode(curl_exec($curl), true);
        if (curl_errno($curl)) {
            $curl_error = curl_error($curl);
            curl_close($curl);
            throw new \Exception(sprintf("Error: %s", $curl_error));
        }
        curl_close($curl);

        return $response;
    }
}