<?php
use Parser\DbConnection;
use Parser\Parser;

class Core {
    public $parser;
    public $dbConnection;
    const MAX_REQUEST_PER_SECOND = 3;
    public function __construct()
    {
        $token = '0d7a81b5b0430219f6bce6ab55f7ef0097cfe48f1d2fe64458c1be7cf7f6df66f98704a19ebf09bdf4388';
        $this->dbConnection = new DbConnection('localhost', 'root', '123456789p');
        $this->parser = new Parser($token, 'pgpuspb');
    }

    public function parse_users() {
        $total = PHP_INT_MAX;
        $parsed = 0;
        /* Max value by api */
        $count = 1000;
        $request_counter = 0;
        while ($parsed < $total) {
            $response = $this->parser->get_users($parsed , $count);
            $total = $response['count'];
            $this->dbConnection->insert_users(array_values($response['items']));
            /* preventing errors with requests limits */
            $request_counter += 1;
            $parsed += $count;
            if($request_counter === CORE::MAX_REQUEST_PER_SECOND) {
                sleep(1);
                $request_counter = 0;
            }
        }
    }

    public function parse_user_groups() {
      $request_counter = 0;

      $user_id = $this->dbConnection->get_unparsed_user();
      while (!empty($user_id)) {
          $response = $this->parser->get_user_groups($user_id);
          $request_counter += 1;
          if($request_counter === CORE::MAX_REQUEST_PER_SECOND) {
              sleep(1);
              $request_counter = 0;
          }
          if($response['parsed']) {
              $group_ids = array_column($response['groups'], 'id');
              $this->dbConnection->update_user($user_id, null, $group_ids);
              foreach ($response['groups'] as $group) {
                  $url = "https://vk.com/{$group['screen_name']}";
                  $this->dbConnection->add_group($group['id'], $url, $group['name'], $group['description']);
              }
          } else {
              $this->dbConnection->update_user($user_id, $response['message']);
          }
          $user_id = $this->dbConnection->get_unparsed_user();
      }
    }

    public function import() {
        $this->dbConnection->import_to_csv();
    }
}