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

    public function get_master_area($id = '')
    {
        if ($id != '') {
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'master_area')->row();
        } else {
            return $this->db->get(db_prefix() . 'master_area')->result_array();
        }
    }

    public function add_master_area($data)
    {
        $this->db->insert(db_prefix() . 'master_area', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            return $insert_id;
        }
        return false;
    }

    public function update_master_area($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'master_area', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    public function delete_master_area($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'master_area');
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    public function get_functionality_area($id = '')
    {
        if ($id != '') {
            $this->db->where('id', $id);
            return $this->db->get(db_prefix() . 'functionality_area')->row();
        } else {
            return $this->db->get(db_prefix() . 'functionality_area')->result_array();
        }
    }

    public function add_functionality_area($data)
    {
        $this->db->insert(db_prefix() . 'functionality_area', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            return $insert_id;
        }
        return false;
    }

    public function update_functionality_area($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'functionality_area', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    public function delete_functionality_area($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'functionality_area');
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }

    public function get_master_area_dropdown($name, $value)
    {
        $select = '';
        $select = '<select class="selectpicker display-block tax main-tax" data-width="100%" name="'.$name.'" data-none-selected-text="' . _l('master_area') . '">';
        $select .= '<option value=""></option>';
        $master_area = $this->get_master_area();
        foreach ($master_area as $area) {
            $selected = ($area['id'] == $value) ? ' selected' : '';
            $select .= '<option value="' . $area['id'] . '"' . $selected . '>' . $area['category_name'] . '</option>';

        }
        $select .= '</select>';
        return $select;
    }

    public function get_functionality_area_dropdown($name, $value)
    {
        $select = '';
        $select = '<select class="selectpicker display-block tax main-tax" data-width="100%" name="'.$name.'" data-none-selected-text="' . _l('functionality_area') . '">';
        $select .= '<option value=""></option>';
        $functionality_area = $this->get_functionality_area();
        foreach ($functionality_area as $area) {
            $selected = ($area['id'] == $value) ? ' selected' : '';
            $select .= '<option value="' . $area['id'] . '"' . $selected . '>' . $area['category_name'] . '</option>';

        }
        $select .= '</select>';
        return $select;
    }

    public function get_units($id = '')
    {
        if ($id != '') {
            $this->db->where('unit_type_id', $id);
            return $this->db->get(db_prefix() . 'ware_unit_type')->row();
        } else {
            return $this->db->get(db_prefix() . 'ware_unit_type')->result_array();
        }
    }

    public function get_area_unit_dropdown($name, $value)
    {
        $select = '';
        $select = '<select class="selectpicker display-block tax main-tax" data-width="100%" name="'.$name.'" data-none-selected-text="' . _l('unit') . '">';
        $select .= '<option value=""></option>';
        $units = $this->get_units();
        foreach ($units as $unit) {
            $selected = ($unit['unit_type_id'] == $value) ? ' selected' : '';
            $select .= '<option value="' . $unit['unit_type_id'] . '"' . $selected . '>' . $unit['unit_name'] . '</option>';

        }
        $select .= '</select>';
        return $select;
    }

    public function get_area_statement_tabs($id)
    {
        $this->db->where('estimate_id', $id);
        return $this->db->get(db_prefix() . 'area_statement_tabs')->result_array();
    }

    public function get_area_summary_tabs()
    {
        return $this->db->get(db_prefix() . 'area_summary_tabs')->result_array();
    }
}

?>