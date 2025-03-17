<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Install the Meeting Management module
 */
$CI = &get_instance();

// Create the meeting management table (tblmeeting_management)
if (!$CI->db->table_exists(db_prefix() . 'meeting_management')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . 'meeting_management` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `meeting_title` varchar(255) NOT NULL,
        `project_id` int(11) NOT NULL,
        `agenda` text NOT NULL,
        `minutes` text DEFAULT NULL,
        `meeting_date` datetime NOT NULL,
        `created_by` int(11) NOT NULL,
        `signature_path` varchar(255) DEFAULT NULL,
        `updated_by` int(11) DEFAULT NULL,
        PRIMARY KEY (`id`)
    )');
}


// Create the meetings table (tblagendas)
// Create the meetings table (tblagendas)
if (!$CI->db->table_exists(db_prefix() . 'agendas')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . 'agendas` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `meeting_title` varchar(255) NOT NULL,
        `project_id` int(11) NOT NULL, 
        `agenda` text NOT NULL,
        `minutes` text DEFAULT NULL,
        `meeting_date` datetime NOT NULL,
        `created_by` int(11) NOT NULL,
        PRIMARY KEY (`id`)
       
    )');
}





// Create the meeting tasks table (tblmeeting_tasks)
if (!$CI->db->table_exists(db_prefix() . 'meeting_tasks')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . 'meeting_tasks` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `agenda_id` int(11) NOT NULL,
        `task_title` varchar(255) NOT NULL,
        `assigned_to` int(11) NOT NULL,
        `due_date` date NOT NULL,
        `status` int(11) DEFAULT 0, -- 0 for not completed, 1 for completed
        `reminder_sent` int(1) DEFAULT 0, -- 0 for not sent, 1 for sent
        PRIMARY KEY (`id`),
        FOREIGN KEY (`agenda_id`) REFERENCES `' . db_prefix() . 'meeting_management`(`id`) ON DELETE CASCADE
    )');
}

// Create the meeting participants table (tblagenda_participants)
if (!$CI->db->table_exists(db_prefix() . 'agenda_participants')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . 'agenda_participants` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `agenda_id` int(11) NOT NULL,
        `participant_type` ENUM("client", "staff", "vendor") NOT NULL,
        `participant_id` int(11) NOT NULL,
        PRIMARY KEY (`id`),
        FOREIGN KEY (`agenda_id`) REFERENCES `' . db_prefix() . 'agendas`(`id`) ON DELETE CASCADE
    )');
}

// Optional: Create a table for participant signatures (tblagenda_signatures)
if (!$CI->db->table_exists(db_prefix() . 'agenda_signatures')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . 'agenda_signatures` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `agenda_id` int(11) NOT NULL,
        `participant_id` int(11) NOT NULL,
        `signature` text NOT NULL,
        `signed_at` datetime NOT NULL,
        PRIMARY KEY (`id`),
        FOREIGN KEY (`agenda_id`) REFERENCES `' . db_prefix() . 'agendas`(`id`) ON DELETE CASCADE
    )');
}

// Optional: Create a table for meeting participants (tblmeeting_participants)
if (!$CI->db->table_exists(db_prefix() . 'meeting_participants')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . 'meeting_participants` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `meeting_id` int(11) NOT NULL,
        `participant_id` varchar(255) NOT NULL,
        `email` varchar(255) NOT NULL,
        PRIMARY KEY (`id`),
        FOREIGN KEY (`meeting_id`) REFERENCES `' . db_prefix() . 'meeting_management`(`id`) ON DELETE CASCADE
    )');
}

