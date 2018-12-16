<?php
namespace Parser;

interface IDbConnection {
    /* Insert users in db */
    public function insert_users($user_ids);
    /* Add group in db */
    public function add_group($group_id, $url, $name, $description);
    /* Update user info */
    public function update_user($user_id, $comment, $groups = null);
    /* first unparsed */
    public function get_unparsed_user();
}