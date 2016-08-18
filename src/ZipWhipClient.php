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
     * @param array $contactInformation
     * @return bool
     * @throws \Exception
     */
    public function saveContact(array $contactInformation) {
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
     * @param $fingerprint
     * @return bool
     * @throws \Exception
     */
    public function deleteConversation($fingerprint) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.zipwhip.com/conversation/delete");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "session={$this->api_key}&fingerprint={$fingerprint}");
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
     * @param $fingerprint
     * @param int $limit
     * @param int $start
     * @return bool
     * @throws \Exception
     */
    public function getConversation($fingerprint, $limit = 30, $start = 0) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.zipwhip.com/conversation/get");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "fingerprint={$fingerprint}&session={$this->api_key}&limit={$limit}&start={$start}");
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
     * @param int $limit
     * @param int $start
     * @return bool
     * @throws \Exception
     */
    public function listConversations($limit = 30, $start = 0) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.zipwhip.com/conversation/list");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "session={$this->api_key}&limit={$limit}&start={$start}");
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
     * @param array $userInfo
     * @return bool
     * @throws \Exception
     */
    public function addMember(array $userInfo) {
        $query = [];
        foreach($userInfo AS $k => $v) {
            $query[] = $k."=".$v;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.zipwhip.com/group/addMember");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, implode("&", $query)."session={$this->api_key}");
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
     * @param int $address
     * @return bool
     * @throws \Exception
     */
    public function deleteGroup($address) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.zipwhip.com/group/delete");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "session={$this->api_key}&address={$address}");
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
     * This one isn't documented right on their site, going with a best guess right now.
     * @param int $address
     * @return bool
     * @throws \Exception
     */
    public function getGroup($address) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.zipwhip.com/group/get");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "session={$this->api_key}&address={$address}");
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
     * @param array $groupInfo
     * @return bool
     * @throws \Exception
     */
    public function saveGroup(array $groupInfo) {
        $query = [];
        foreach($groupInfo AS $k => $v) {
            $query[] = $k."=".$v;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.zipwhip.com/group/save");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, implode("&", $query)."session={$this->api_key}");
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
     * @param $group
     * @param $member
     * @return bool
     * @throws \Exception
     */
    public function removeMember($group, $member) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.zipwhip.com/group/removeMember");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "session={$this->api_key}&group={$group}&member={$member}");
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
     * @param int $message
     * @return bool
     * @throws \Exception
     */
    public function deleteMessage($message) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.zipwhip.com/message/delete");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "session={$this->api_key}&messages={$message}");
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
     * @param int $limit
     * @param int $start
     * @return bool
     * @throws \Exception
     */
    public function listMessages($limit = 30, $start = 0) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.zipwhip.com/message/list");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "session={$this->api_key}&limit={$limit}&start={$start}");
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
     * @param int $limit
     * @param int $start
     * @return bool
     * @throws \Exception
     */
    public function readMessage($message) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.zipwhip.com/message/read");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "session={$this->api_key}&messages={$message}");
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

    /**
     * @return bool
     * @throws \Exception
     */
    public function getUser() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.zipwhip.com/user/get");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "session={$this->api_key}");
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
     * @param array $userInfo
     * @return bool
     * @throws \Exception
     */
    public function saveUser(array $userInfo) {
        $query = [];
        foreach($userInfo AS $k => $v) {
            $query[] = $k."=".$v;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.zipwhip.com/user/save");
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
}