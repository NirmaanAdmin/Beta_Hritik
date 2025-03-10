<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Payment_certificate_to_approver_merge_fields extends App_merge_fields
{
    public function build()
    {
        return [
            [
                'name'      => 'Contact firstname',
                'key'       => '{contact_firstname}',
                'available' => [
                    
                ],
                'templates' => [
                    'payment-certificate-to-approver',
                ],
            ],
            [
                'name'      => 'Contact lastname',
                'key'       => '{contact_lastname}',
                'available' => [
                    
                ],
                'templates' => [
                    'payment-certificate-to-approver',
                ],
            ],
            [
                'name'      => 'Payment certificate link',
                'key'       => '{payment_certificate_link}',
                'available' => [
                    
                ],
                'templates' => [
                    'payment-certificate-to-approver',
                ],
            ],
            [
                'name'      => 'Payment certificate title',
                'key'       => '{payment_certificate_title}',
                'available' => [
                    
                ],
                'templates' => [
                    'payment-certificate-to-approver',
                ],
            ],
        ];
    }

    /**
     * Merge field for appointments
     * @param  mixed $teampassword 
     * @return array
     */
    public function format($data)
    {
        $id = $data->id;
        $this->ci->load->model('purchase/purchase_model');


        $fields = [];

        $this->ci->db->where('id', $id);
        $pc = $this->ci->db->get(db_prefix() . 'payment_certificate')->row();


        if (!$pc) {
            return $fields;
        }

        $fields['{contact_firstname}'] =  $data->contact_firstname;
        $fields['{contact_lastname}'] =  $data->contact_lastname;
        if(!empty($pc->wo_id)) {
            $fields['{payment_certificate_title}'] = site_url('purchase/payment_certificate/' . $pc->wo_id.'/'.$pc->id.'/1');
            $fields['{payment_certificate_link}'] = site_url('purchase/payment_certificate/' . $pc->wo_id.'/'.$pc->id.'/1');
        } else {
            $fields['{payment_certificate_title}'] = site_url('purchase/payment_certificate/' . $pc->po_id.'/'.$pc->id.'/1');
            $fields['{payment_certificate_link}'] = site_url('purchase/payment_certificate/' . $pc->po_id.'/'.$pc->id.'/1');
        }

        return $fields;
    }
}
