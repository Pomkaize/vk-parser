<?php
namespace Parser;

use Error;
use mysqli;

class DbConnection implements IDbConnection {
    public $connection;
    public function __construct($servername, $username, $password) {
        $this->connection = new mysqli($servername, $username, $password, 'parser');
        if ($this->connection->connect_error) {
            throw new Error("Connection failed: " . $this->connection->connect_error);
        }
    }
    public function insert_users($user_ids) {
        $users = '(\''. implode('\'), (\'', $user_ids) . '\')';
        $sql = "INSERT INTO vk_users (user_id) 
                VALUES ${users} ON DUPLICATE KEY UPDATE user_id=user_id";
        $this->connection->query($sql);
    }
    
    public function get_unparsed_user() {
        $sql = "SELECT user_id FROM vk_users WHERE parsed=0 ORDER BY user_id LIMIT 1";
        return $this->connection->query($sql)->fetch_assoc()['user_id'];
    }

    public function add_group($group_id, $url, $name, $description) {
        $sql = "INSERT INTO vk_groups (group_id, url, name, count, description) VALUES ({$group_id}, '{$url}', '{$name}', 1, '${description}') ON DUPLICATE KEY UPDATE count=count+1";
        $this->connection->query($sql);
    }

    public function update_user($user_id, $comment, $groups = null) {
        $group_updates = array();
        foreach ($groups as $k =>$groupId) {
            $index = $k + 1;
            $group_updates[] = "group{$index}=\"{$groupId}\"";
        }

        if(count($group_updates)) {
            $sql_part_groups = "," . implode(', ', $group_updates);
        } else {
            $sql_part_groups = '';
        }

        $sql = "UPDATE vk_users SET parsed=1, comment='{$comment}' {$sql_part_groups} WHERE user_id={$user_id}";
        $this->connection->query($sql);
    }
    public function get_all_groups($minCount = 10) {
        $sql = "SELECT * FROM vk_groups WHERE count>={$minCount}";
        return $this->connection->query($sql)->fetch_all();
    }

    public function import_to_csv() {
        $headers = array(

        );
        $data = $this->get_all_groups();
        $filename = "vk_groups-".date('d.m.Y').".csv";
        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Description: File Transfer');
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename='.$filename.';');
        header('Content-Transfer-Encoding: binary');
        //open file pointer to standard output
        $fp = fopen('php://output', 'w');

//add BOM to fix UTF-8 in Excel
        fputs($fp, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
        if ($fp)
        {
/*            fputcsv($fp, $this->getMapFields(), ";");*/
            foreach ($data as $item)
            {
                fputcsv($fp, $item, ";");
            }
        }
        fclose($fp);
    }
}