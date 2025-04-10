<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Example helper function (You can define any reusable function here)
function example_meeting_helper_function()
{
    return "This is a helper function for the Meeting Management module.";
}

function getstafflist()
{
    $CI = &get_instance();
    $CI->load->model('staff_model');
    
    // Retrieve active staff members.
    $get_staff = $CI->staff_model->get('', ['active' => 1]);
    
    // Build an array with 'staffid' and concatenated 'fullname' for each staff member.
    $staff_list = [];
    foreach ($get_staff as $staff) {
        $fullname = $staff['firstname'] . ' ' . $staff['lastname'];
        $staff_list[] = [
            'staffid'  => $staff['staffid'],
            'fullname' => $fullname,
        ];
    }
    
    return $staff_list;
}

function getvendorlist(){
    $CI = &get_instance();
    $CI->load->model('purchase/purchase_model');
    return $get_vendor = $CI->purchase_model->get_vendor();
}