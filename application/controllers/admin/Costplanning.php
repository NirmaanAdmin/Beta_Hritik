<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Costplanning extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('costplanning_model');
    }

    public function setting()
    {
        $data['group'] = $this->input->get('group');
        $data['unit_tab'] = $this->input->get('tab');
        $data['title'] = _l('setting');
        $this->db->where('module_name', 'warehouse');
        $module = $this->db->get(db_prefix() . 'modules')->row();
        $data['tab'][] = 'commodity_group';
        $data['tab'][] = 'sub_group';
        $data['tab'][] = 'master_area';
        $data['tab'][] = 'functionality_area';
        if ($data['group'] == '') {
            $data['group'] = 'commodity_group';
        }
        $data['tabs']['view'] = 'admin/costplanning/includes/' . $data['group'];
        $data['commodity_group_types'] = $this->costplanning_model->get_commodity_group_type();
        $data['sub_groups'] = $this->costplanning_model->get_sub_group();
        $data['area'] = $this->costplanning_model->get_area();
        $data['master_area'] = $this->costplanning_model->get_master_area();
        $data['functionality_area'] = $this->costplanning_model->get_functionality_area();

        $this->load->view('admin/costplanning/manage_setting', $data);
    }

    public function commodity_group_type($id = '')
    {
        if ($this->input->post()) {
            $message = '';
            $data = $this->input->post();

            if (!$this->input->post('id')) {

                $mess = $this->costplanning_model->add_commodity_group_type($data);
                if ($mess) {
                    set_alert('success', _l('added_successfully') . _l('commodity_group_type'));
                } else {
                    set_alert('warning', _l('Add_commodity_group_type_false'));
                }
                redirect(admin_url('costplanning/setting?group=commodity_group'));
            } else {
                $id = $data['id'];
                unset($data['id']);
                $success = $this->costplanning_model->add_commodity_group_type($data, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully') . _l('commodity_group_type'));
                } else {
                    set_alert('warning', _l('updated_commodity_group_type_false'));
                }

                redirect(admin_url('costplanning/setting?group=commodity_group'));
            }
        }
    }

    public function delete_commodity_group_type($id)
    {
        if (!$id) {
            redirect(admin_url('costplanning/setting?group=commodity_group'));
        }
        $response = $this->costplanning_model->delete_commodity_group_type($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('commodity_group_type')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('commodity_group_type')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('commodity_group_type')));
        }
        redirect(admin_url('costplanning/setting?group=commodity_group'));
    }

    public function sub_group($id = '')
    {
        if ($this->input->post()) {
            $message = '';
            $data = $this->input->post();

            if (!$this->input->post('id')) {

                $mess = $this->costplanning_model->add_sub_group($data);
                if ($mess) {
                    set_alert('success', _l('added_successfully') . ' ' . _l('sub_group'));
                } else {
                    set_alert('warning', _l('Add_sub_group_false'));
                }
                redirect(admin_url('costplanning/setting?group=sub_group'));
            } else {
                $id = $data['id'];
                unset($data['id']);
                $success = $this->costplanning_model->add_sub_group($data, $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully') . ' ' . _l('sub_group'));
                } else {
                    set_alert('warning', _l('updated_sub_group_false'));
                }

                redirect(admin_url('costplanning/setting?group=sub_group'));
            }
        }
    }

    public function delete_sub_group($id)
    {
        if (!$id) {
            redirect(admin_url('costplanning/setting?group=sub_group'));
        }
        $response = $this->costplanning_model->delete_sub_group($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('sub_group')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('sub_group')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('sub_group')));
        }
        redirect(admin_url('costplanning/setting?group=sub_group'));
    }

    public function master_area()
    {
        if ($this->input->post()) {
            $message = '';
            $data = $this->input->post();
            if (!$this->input->post('id')) {
                $id = $this->costplanning_model->add_master_area($data);
                if ($id) {
                    $success = true;
                    $message = _l('added_successfully', _l('master_area'));
                    set_alert('success', $message);
                }
                redirect(admin_url('costplanning/setting?group=master_area'));
            } else {
                $id = $data['id'];
                unset($data['id']);
                $success = $this->costplanning_model->update_master_area($data, $id);
                if ($success) {
                    $message = _l('updated_successfully', _l('master_area'));
                    set_alert('success', $message);
                }
                redirect(admin_url('costplanning/setting?group=master_area'));
            }
            die;
        }
    }

    public function delete_master_area($id)
    {
        if (!$id) {
            redirect(admin_url('costplanning/setting?group=master_area'));
        }
        $response = $this->costplanning_model->delete_master_area($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('master_area')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('master_area')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('master_area')));
        }
        redirect(admin_url('costplanning/setting?group=master_area'));
    }

    public function functionality_area()
    {
        if ($this->input->post()) {
            $message = '';
            $data = $this->input->post();
            if (!$this->input->post('id')) {
                $id = $this->costplanning_model->add_functionality_area($data);
                if ($id) {
                    $success = true;
                    $message = _l('added_successfully', _l('functionality_area'));
                    set_alert('success', $message);
                }
                redirect(admin_url('costplanning/setting?group=functionality_area'));
            } else {
                $id = $data['id'];
                unset($data['id']);
                $success = $this->costplanning_model->update_functionality_area($data, $id);
                if ($success) {
                    $message = _l('updated_successfully', _l('functionality_area'));
                    set_alert('success', $message);
                }
                redirect(admin_url('costplanning/setting?group=functionality_area'));
            }
            die;
        }
    }

    public function delete_functionality_area($id)
    {
        if (!$id) {
            redirect(admin_url('costplanning/setting?group=functionality_area'));
        }
        $response = $this->costplanning_model->delete_functionality_area($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('functionality_area')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('functionality_area')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('functionality_area')));
        }
        redirect(admin_url('costplanning/setting?group=functionality_area'));
    }

    public function get_master_area_dropdown()
    {
        $name    = $this->input->post('name');
        $value = $this->input->post('value');
        echo $this->costplanning_model->get_master_area_dropdown($name, $value);
    }

    public function get_functionality_area_dropdown()
    {
        $name    = $this->input->post('name');
        $value = $this->input->post('value');
        echo $this->costplanning_model->get_functionality_area_dropdown($name, $value);
    }

    public function get_area_unit_dropdown()
    {
        $name    = $this->input->post('name');
        $value = $this->input->post('value');
        echo $this->costplanning_model->get_area_unit_dropdown($name, $value);
    }

    public function get_sub_head_dropdown()
    {
        $name    = $this->input->post('name');
        $value = $this->input->post('value');
        echo $this->costplanning_model->get_sub_head_dropdown($name, $value);
    }
}

?>