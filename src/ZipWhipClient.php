<?php
/**
 * Created by PhpStorm.
 * User: brianlogan
 * Date: 8/17/16
 * Time: 9:28 AM
 */

namespace CollingMedia;


class ZipWhipClient {

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
            $zipwhipClient = new ZipWhipClient($result->response);
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
     * @throws \Exception
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
     * @param array $contactInformation
     * @return bool
     * @throws \Exception
     */
    public function saveContact(array $contactInformation) {
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