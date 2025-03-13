<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Costplanning_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_commodity_group_type($id = false)
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'items_groups')->row();
        }
        if ($id == false) {
            return $this->db->query('select * from tblitems_groups')->result_array();
        }
    }

    public function get_sub_group($id = false)
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'wh_sub_group')->row();
        }
        if ($id == false) {
            return $this->db->query('select * from tblwh_sub_group')->result_array();
        }
    }

    public function get_area($id = false)
    {
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'area')->row();
        }
        if ($id == false) {
            return $this->db->query('select * from tblarea')->result_array();
        }
    }

    public function add_commodity_group_type($data, $id = false)
    {
        $data['commodity_group'] = str_replace(', ', '|/\|', $data['hot_commodity_group_type']);

        $data_commodity_group_type = explode(',', $data['commodity_group']);
        $results = 0;
        $results_update = '';
        $flag_empty = 0;

        foreach ($data_commodity_group_type as $commodity_group_type_key => $commodity_group_type_value) {
            if ($commodity_group_type_value == '') {
                $commodity_group_type_value = 0;
            }
            if (($commodity_group_type_key + 1) % 5 == 0) {

                $arr_temp['note'] = str_replace('|/\|', ', ', $commodity_group_type_value);

                if ($id == false && $flag_empty == 1) {
                    $this->db->insert(db_prefix() . 'items_groups', $arr_temp);
                    $insert_id = $this->db->insert_id();
                    if ($insert_id) {
                        $results++;
                    }
                }
                if (is_numeric($id) && $flag_empty == 1) {
                    $this->db->where('id', $id);
                    $this->db->update(db_prefix() . 'items_groups', $arr_temp);
                    if ($this->db->affected_rows() > 0) {
                        $results_update = true;
                    } else {
                        $results_update = false;
                    }
                }

                $flag_empty = 0;
                $arr_temp = [];
            } else {

                switch (($commodity_group_type_key + 1) % 5) {
                    case 1:
                        if (is_numeric($id)) {
                            //update
                            $arr_temp['commodity_group_code'] = str_replace('|/\|', ', ', $commodity_group_type_value);
                            $flag_empty = 1;
                        } else {
                            //add
                            $arr_temp['commodity_group_code'] = str_replace('|/\|', ', ', $commodity_group_type_value);

                            if ($commodity_group_type_value != '0') {
                                $flag_empty = 1;
                            }
                        }
                        break;
                    case 2:
                        $arr_temp['name'] = str_replace('|/\|', ', ', $commodity_group_type_value);
                        break;
                    case 3:
                        $arr_temp['order'] = $commodity_group_type_value;
                        break;
                    case 4:
                        //display 1: display (yes) , 0: not displayed (no)
                        if ($commodity_group_type_value == 'yes') {
                            $display_value = 1;
                        } else {
                            $display_value = 0;
                        }
                        $arr_temp['display'] = $display_value;
                        break;
                }
            }
        }

        if ($id == false) {
            return $results > 0 ? true : false;
        } else {
            return $results_update;
        }
    }

    public function delete_commodity_group_type($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'items_groups');
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    public function add_sub_group($data, $id = false)
    {
        $commodity_type = str_replace(', ', '|/\|', $data['hot_sub_group']);

        $data_commodity_type = explode(',', $commodity_type);
        $results = 0;
        $results_update = '';
        $flag_empty = 0;

        foreach ($data_commodity_type as $commodity_type_key => $commodity_type_value) {
            if ($commodity_type_value == '') {
                $commodity_type_value = 0;
            }
            if (($commodity_type_key + 1) % 6 == 0) {
                $arr_temp['note'] = str_replace('|/\|', ', ', $commodity_type_value);

                if ($id == false && $flag_empty == 1) {
                    $this->db->insert(db_prefix() . 'wh_sub_group', $arr_temp);
                    $insert_id = $this->db->insert_id();
                    if ($insert_id) {
                        $results++;
                    }
                }
                if (is_numeric($id) && $flag_empty == 1) {
                    $this->db->where('id', $id);
                    $this->db->update(db_prefix() . 'wh_sub_group', $arr_temp);
                    if ($this->db->affected_rows() > 0) {
                        $results_update = true;
                    } else {
                        $results_update = false;
                    }
                }
                $flag_empty = 0;
                $arr_temp = [];
            } else {

                switch (($commodity_type_key + 1) % 6) {
                    case 1:
                        $arr_temp['sub_group_code'] = str_replace('|/\|', ', ', $commodity_type_value);
                        if ($commodity_type_value != '0') {
                            $flag_empty = 1;
                        }
                        break;
                    case 2:
                        $arr_temp['sub_group_name'] = str_replace('|/\|', ', ', $commodity_type_value);
                        break;
                    case 3:
                        $arr_temp['group_id'] = $commodity_type_value;
                        break;
                    case 4:
                        $arr_temp['order'] = $commodity_type_value;
                        break;
                    case 5:
                        //display 1: display (yes) , 0: not displayed (no)
                        if ($commodity_type_value == 'yes') {
                            $display_value = 1;
                        } else {
                            $display_value = 0;
                        }
                        $arr_temp['display'] = $display_value;
                        break;
                }
            }
        }

        if ($id == false) {
            return $results > 0 ? true : false;
        } else {
            return $results_update;
        }
    }

    public function delete_sub_group($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'wh_sub_group');
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }
}

?>