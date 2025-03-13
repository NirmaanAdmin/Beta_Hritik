<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Share extends App_mail_template
{
    protected $for = 'staff';

    protected $drawing_management;

    public $slug = 'share';

    public function __construct($drawing_management)
    {
        parent::__construct();

        $this->drawing_management = $drawing_management;
        // For SMS and merge fields for email
        $this->set_merge_fields('share_merge_fields', $this->drawing_management);
    }

    public function build()
    {
        $this->to($this->drawing_management->email);
    }
    
}
