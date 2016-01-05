<?php

/*
 *  Created on :Aug 18, 2015, 6:45:17 PM
 *  Author     :Varun Garg <varun.10@live.com>

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class update {

    public $version;
    public $updates = array();

}

class Upgrade extends CI_Controller {

    function index() {
        $update_list = array();

        $u = new update;
        $u->version = 1.1;
        array_push($u->updates, "CREATE TABLE `timetable_admin` (`id` int(11) NOT NULL,`user_id` int(11) NOT NULL);");
        array_push($u->updates, "ALTER TABLE `timetable_admin`ADD PRIMARY KEY (`id`);");
        array_push($u->updates, "ALTER TABLE `timetable_admin` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
        array_push($update_list, $u);
        unset($u);
        //Don't edit after this line
        $this->run_upgrades($update_list);

        redirect('Android_web');
    }

    function run_upgrades($update_list) {
        if (!$this->db->table_exists('timetable_update_info')) {
            $this->db->query("create table timetable_update_info (version FLOAT)");
            $this->db->query("insert into timetable_update_info (version) values ('1.0') ");
        }

        foreach ($update_list as $value) {
            $version_q = $this->db->query(" select * from timetable_update_info");
            $version_r = $version_q->row(0);
            $version = $version_r->version;
            if ($value->version > $version) {
                foreach ($value->updates as $row) {
                    $this->db->query($row);
                }
                $this->db->query("update timetable_update_info set version = '$value->version' ");
            }
        }
    }
}
