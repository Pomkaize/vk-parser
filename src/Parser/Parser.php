<?php

namespace Parser;

use Throwable;
use VK\Actions\Groups;
use VK\Actions\Users;
use VK\Client\VKApiRequest;

class Parser {

    const REQUEST_PER_SECOND = 6;

    protected $token;
    protected $group_id;
    protected $request;
    protected $groups;
    protected $users;

    public function __construct($token, $group_id)
    {
        $this->token = $token;
        $this->group_id = $group_id;
        $this->request = new VKApiRequest(5.92, 'ru', 'https://api.vk.com/method');
        $this->groups = new Groups($this->request);
        $this->users = new Users($this->request);
    }

    public function get_users($from, $count = 1000) {
        try {
            $params = array(
                "sort"=>"id_asc",
                "group_id"=>$this->group_id,
                "count" => $count,
                "offset" => $from
            );
           $response = $this->groups->getMembers($this->token, $params);
           return $response;
        } catch (Throwable $throw) {
            /* 125 */
            echo $throw->getMessage();
        }
    }
    public function get_user_groups($user_id) {
        try {
            $params = array(
                "user_id"=>$user_id,
                "extended" => 1,
                "fields" =>'description',
                "offset" => 0,
                "count" => 3
            );
            $response = $this->groups->get($this->token,$params);
            return array('parsed' => true, 'groups' => $response['items']);
        } catch (Throwable $throw) {
            /* 30, 260 */
            $message = $throw->getMessage();
            return array('parsed' => false, 'message' => $message);
        }
    }
}