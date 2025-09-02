<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Seminar_model extends CI_Model
{
    /**
     * Get all documents related to the seminar process for a specific application
     */
    public function get_seminar_documents($application_id)
    {
        $doc_types = [
            'laporan_pkl_draft',
            'laporan_pkl_revised',
            'lembar_persetujuan_seminar',
            'berita_acara_seminar',
            'lembar_pengesahan',
            'form_c1',
            'form_d1',
            'form_d3',
            'form_d4',
            'form_e1'
        ];

        return $this->db->from('pkl_documents')
            ->where('application_id', $application_id)
            ->where_in('doc_type', $doc_types)
            ->order_by('uploaded_at', 'DESC')
            ->get()
            ->result();
    }

    /**
     * Get the latest revision notes for an application
     */
    public function get_revision_notes($application_id)
    {
        return $this->db->from('pkl_workflow')
            ->where('application_id', $application_id)
            ->where('step_name', 'Mulai Revisi')
            ->order_by('action_date', 'DESC')
            ->get()
            ->row();
    }

    /**
     * Get seminar details by application ID
     */
    public function get_seminar_by_application($application_id)
    {
        return $this->db->where('application_id', $application_id)
            ->get('pkl_seminars')
            ->row();
    }
}
