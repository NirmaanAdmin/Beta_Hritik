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
/**
 * Takes a comma separated string of staff IDs and returns a comma separated string of the
 * corresponding staff full names.
 * 
 * @param string $staff_ids_csv A comma separated string of staff IDs.
 * @return string A comma separated string of the corresponding staff full names.
 */
function getStaffNamesFromCSV($staff_ids_csv)
{
    // Retrieve the full staff list as an array with staffid and fullname.
    $staff_list = getstafflist();

    // Convert the comma separated input string to an array and trim any whitespace.
    $staff_ids_array = array_map('trim', explode(',', $staff_ids_csv));

    // Build a lookup array mapping each staff id to its fullname.
    $staff_map = [];
    foreach ($staff_list as $staff) {
        $staff_map[$staff['staffid']] = $staff['fullname'];
    }

    // Prepare an array to hold the full names corresponding to the provided staff IDs.
    $names = [];
    foreach ($staff_ids_array as $id) {
        if (isset($staff_map[$id])) {
            $names[] = $staff_map[$id];
        }
    }

    // Return the names as a comma separated string.
    return implode(', ', $names);
}
function getvendorlist()
{
    $CI = &get_instance();
    $CI->load->model('purchase/purchase_model');
    return $get_vendor = $CI->purchase_model->get_vendor();
}

function getdeptmom()
{
    $CI = &get_instance();

    try {
        if (!isset($CI->Departments_model)) {
            $CI->load->model('Departments_model');
        }

        $departments = $CI->Departments_model->get();

        if (empty($departments)) {
            log_message('info', 'No departments found in getdeptmom()');
            return [];
        }

        // Return the original array of department objects
        return $departments;
    } catch (Exception $e) {
        log_message('error', 'Error in getdeptmom(): ' . $e->getMessage());
        return [];
    }
}
