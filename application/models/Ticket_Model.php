<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Ticket_Model extends CI_Model
{

    //get all data ticket
    public function Ticket()
    {
        $query = " SELECT T.*, C.CATEGORY, D.DIVISI, S.STATUS, K.TECHNICIAN_NAME,
        to_char(T.DATE_INSERT,'dd-mm-yyy hh24:mi') DATE_INSERT, to_char(T.DATE_SOLVE, 'dd-mm-yy hh24:mi') DATE_SOLVE, to_char(T.UPDATE_TIME, 'dd-mm-yy hh24:mi') UPDATE_TIME
                        FROM TICKET  T
                    LEFT JOIN CATEGORY C ON T.ID_CATEGORY = C.ID_CATEGORY
                    LEFT JOIN DIVISI D ON T.ID_DIVISI = D.ID_DIVISI
                    LEFT JOIN STATUS_PROBLEM S ON T.ID_STATUS = S.ID_STATUS
                    LEFT JOIN TECHNICIAN K ON T.ID_TECHNICIAN = K.ID_TECHNICIAN
                    ORDER BY T.ID_TICKET DESC";
        return $this->db->query($query)->result_array();
    }

    // function data($number, $offset)
    // {
    //     return $query = $this->db->get('TICKET', $number, $offset)->result();
    // }

    //Fitur Search Ticket
    public function get_keyword($keyword)
    {

        $this->db->select('*');
        $this->db->from('TICKET');
        $this->db->like('ID_TICKET', $keyword);
        $this->db->or_like('USER_COMPLAIN', $keyword);
        $this->db->or_like('ID_CATEGORY', $keyword);
        $this->db->or_like('DETAIL', $keyword);
        $this->db->join('DIVISI', 'DIVISI.ID_DIVISI = TICKET.ID_DIVISI');
        $this->db->join('CATEGORY', 'CATEGORY.ID_CATEGORY = TICKET.ID_CATEGORY');
        $this->db->join('STATUS_PROBLEM', 'STATUS_PROBLEM.ID_STATUS = TICKET.ID_STATUS');
        return $this->db->get()->result_array();
    }

    //Details ticket
    public function details($id)
    {
        $query = " SELECT T.*, C.CATEGORY, D.DIVISI, S.STATUS, K.TECHNICIAN_NAME
                        FROM TICKET  T
                    JOIN CATEGORY C ON T.ID_CATEGORY = C.ID_CATEGORY
                    JOIN DIVISI D ON T.ID_DIVISI = D.ID_DIVISI
                    JOIN STATUS_PROBLEM S ON T.ID_STATUS = S.ID_STATUS
                    JOIN TECHNICIAN K ON T.ID_TECHNICIAN = K.ID_TECHNICIAN
                    WHERE T.ID_TICKET = " . "'" . $id . "'";
        return $this->db->query($query)->row_array();
    }

    //insert data ticket to table ticket and status
    public function Add()
    {
    
        //generate custom id
        $year_month = date('ym');
        $count_data = count($this->db->query("SELECT ID_TICKET FROM TICKET WHERE ID_TICKET LIKE " . "'" . $year_month ."%'")->result());
        $plus_data = $count_data + 1;
        $left_id = str_pad($plus_data, 4, STR_PAD_LEFT);
        $co = strrev($left_id);
        $y = date('y');
        $m = date("m") . "-";
        $id_ticket = $y . $m . $co; //2103-00001


        //checkbox solve
        if ($this->input->post('solve') == NULL) {
            $solve = '1';
        } else {
            $solve = $this->input->post('solve');
            $this->db->set('DATE_SOLVE', 'sysdate', false);
        }

        //insert date with time hours, minutes, and seconds
        //sysdate is method from oracle databases
        $this->db->set('DATE_INSERT', 'sysdate', false);

        $this->db->insert('TICKET', [
            'ID_TICKET' => $id_ticket,
            // 'ID_TICKET' => $this->_AddEntry(),
            //get data from user input
            'USER_COMPLAIN' => $this->input->post('user_complain'),
            'CONTACT' => $this->input->post('contact'),
            'ID_DIVISI' => $this->input->post('divisi'),
            'PLACE' => $this->input->post('place'),
            //get data user login
            'ADMIN' => $this->session->userdata('email'),
            'ID_TECHNICIAN' => $this->input->post('technician'),
            'ID_CATEGORY' => $this->input->post('category'),
            'DETAIL' => $this->input->post('detail'),
            // status default sedang dikerjakan
            'ID_STATUS' => $solve,
        ]);
    }

    //edit Ticket
    public function updatetiket($id)
    {
        $this->db->where('ID_TICKET', $id);
        if ($this->input->post('status_problem') == NULL) {
            // $solve = '1';
        } else if ($this->input->post('status_problem') == 1) {
            $solve = 1;
        } else if ($this->input->post('status_problem') == 2) {
            $solve = 2;
        } else if ($this->input->post('status_problem') == 3) {
            $solve = 3;
            $this->db->set('DATE_SOLVE', 'sysdate', false);
        }

        //insert date with time hours, minutes, and seconds
        //sysdate is method from oracle databases
        $this->db->where('ID_TICKET', $id);
        $this->db->set('UPDATE_TIME', 'sysdate', false);
        $this->db->update('TICKET', [
            //    'ID_TICKET' => $ticket_id,
            //get data from user input
            'USER_COMPLAIN' => $this->input->post('user_complain'),
            'CONTACT' => $this->input->post('contact'),
            'ID_DIVISI' => $this->input->post('divisi'),
            'PLACE' => $this->input->post('place'),
            'HOW_TO_SOLVE' => $this->input->post('how_to_solve'),
            'NOTE' => $this->input->post('note'),
            //get data user login
            'ADMIN' => $this->session->userdata('email'),
            'ID_TECHNICIAN' => $this->input->post('technician'),
            'ID_CATEGORY' => $this->input->post('category'),
            'DETAIL' => $this->input->post('detail'),
            // status default sedang dikerjakan
            'ID_STATUS' => $this->input->post('status_problem'),
        ]);
    }

    //Delete Ticket
    public function Delete($id)
    {
        $this->db->delete('TICKET', array('ID_TICKET' => $id));
    }

    //Search Email Technician
    public function EmailTechnician($id)
    {
        $query = "SELECT EMAIL FROM TECHNICIAN WHERE ID_TECHNICIAN = " .  $id;
        return $this->db->query($query)->row_array();
    }

    // ------------------------------ Ticket Log ------------------------

    //get all data ticket
    public function TicketLog()
    {
        $query = " SELECT T.*, C.CATEGORY, D.DIVISI, S.STATUS, K.TECHNICIAN_NAME,
        to_char(T.DATE_INSERT,'dd-mm-yyy hh24:mi') DATE_INSERT, to_char(T.DATE_SOLVE, 'dd-mm-yy hh24:mi') DATE_SOLVE, to_char(T.UPDATE_TIME, 'dd-mm-yy hh24:mi') UPDATE_TIME
                        FROM TICKET_LOG  T
                    LEFT JOIN CATEGORY C ON T.ID_CATEGORY = C.ID_CATEGORY
                    LEFT JOIN DIVISI D ON T.ID_DIVISI = D.ID_DIVISI
                    LEFT JOIN STATUS_PROBLEM S ON T.ID_STATUS = S.ID_STATUS
                    LEFT JOIN TECHNICIAN K ON T.ID_TECHNICIAN = K.ID_TECHNICIAN
                    ORDER BY T.ID_TICKET_LOG DESC";
        return $this->db->query($query)->result_array();
    }

    //get search data ticket
    public function searchLog($keywordlog)
    {
        $this->db->select('*');
        $this->db->from('TICKET_LOG');
        $this->db->like('ID_TICKET_LOG', $keywordlog);
        $this->db->or_like('USER_COMPLAIN', $keywordlog);
        $this->db->or_like('ID_CATEGORY', $keywordlog);
        $this->db->or_like('DETAIL', $keywordlog);
        $this->db->join('DIVISI', 'DIVISI.ID_DIVISI = TICKET_LOG.ID_DIVISI');
        $this->db->join('CATEGORY', 'CATEGORY.ID_CATEGORY = TICKET_LOG.ID_CATEGORY');
        $this->db->join('STATUS_PROBLEM', 'STATUS_PROBLEM.ID_STATUS = TICKET_LOG.ID_STATUS');
        return $this->db->get()->result_array();

    }

    //insert data ticket to table ticket and status
    public function AddLog()
    {
        //generate custom id
        $year_month = date('ym');
        $count_data = count($this->db->query("SELECT ID_TICKET_LOG FROM TICKET_LOG WHERE ID_TICKET_LOG LIKE " . "'" . $year_month ."%'")->result());
        $plus_data = $count_data + 1;
        $left_id = str_pad($plus_data, 4, STR_PAD_LEFT);
        $co = strrev($left_id);
        $y = date('y');
        $m = date("m") . "-";
        $id_ticket = $y . $m . $co; //2103-00001

        //checkbox solve
        if ($this->input->post('solve') == NULL) {
            $solve = '1';
        } else {
            $solve = $this->input->post('solve');
            $this->db->set('DATE_SOLVE', 'sysdate', false);
        }

        //insert date with time hours, minutes, and seconds
        //sysdate is method from oracle databases
        $this->db->set('DATE_INSERT', 'sysdate', false);
        $this->db->set('UPDATE_TIME', 'sysdate', false);

        $this->db->insert('TICKET_LOG', [
            'ID_TICKET_LOG' => $id_ticket,
            //get data from user input
            'USER_COMPLAIN' => $this->input->post('user_complain'),
            'CONTACT' => $this->input->post('contact'),
            'ID_DIVISI' => $this->input->post('divisi'),
            'PLACE' => $this->input->post('place'),
            //get data user login
            'ADMIN' => $this->session->userdata('email'),
            'ID_TECHNICIAN' => $this->input->post('technician'),
            'ID_CATEGORY' => $this->input->post('category'),
            'DETAIL' => $this->input->post('detail'),
            // status default sedang dikerjakan
            'ID_STATUS' => $solve,
        ]);
    }

    //detail ticket Log
    public function detailsLog($id)
    {
        $query = " SELECT T.*, C.CATEGORY, D.DIVISI, S.STATUS, K.TECHNICIAN_NAME
                        FROM TICKET_LOG  T
                    LEFT JOIN CATEGORY C ON T.ID_CATEGORY = C.ID_CATEGORY
                    LEFT JOIN DIVISI D ON T.ID_DIVISI = D.ID_DIVISI
                    LEFT JOIN STATUS_PROBLEM S ON T.ID_STATUS = S.ID_STATUS
                    LEFT JOIN TECHNICIAN K ON T.ID_TECHNICIAN = K.ID_TECHNICIAN
                    WHERE T.ID_TICKET_LOG = " . "'" . $id . "'";
        return $this->db->query($query)->row_array();
    }

    //edit Ticket
    public function updateTiketLOG($id)
    {
        //insert date with time hours, minutes, and seconds
        //sysdate is method from oracle databases
        $this->db->where('ID_TICKET_LOG', $id);
        if ($this->input->post('status_problem') == NULL) {
            // $solve = '1';
        } else if ($this->input->post('status_problem') == 1) {
            $solve = 1;
        } else if ($this->input->post('status_problem') == 2) {
            $solve = 2;
        } else if ($this->input->post('status_problem') == 3) {
            $solve = 3;
            $this->db->set('DATE_SOLVE', 'sysdate', false);
        }
        $this->db->set('UPDATE_TIME', 'sysdate', false);
        $this->db->update('TICKET_LOG', [
            //get data from user input
            'USER_COMPLAIN' => $this->input->post('user_complain'),
            'CONTACT' => $this->input->post('contact'),
            'ID_DIVISI' => $this->input->post('divisi'),
            'PLACE' => $this->input->post('place'),
            //get data user login
            // 'ADMIN' => $this->session->userdata('email'),
            'ID_TECHNICIAN' => $this->input->post('technician'),
            'ID_CATEGORY' => $this->input->post('category'),
            'DETAIL' => $this->input->post('detail'),
            // status default sedang dikerjakan
            // 'ID_STATUS' => $solve,
            'HOW_TO_SOLVE' => $this->input->post('how_to_solve'),
            'NOTE' => $this->input->post('note'),
        ]);
    }

    //Delete Ticket
    public function DeleteLog($id)
    {
        $this->db->delete('TICKET_LOG', array('ID_TICKET_LOG' => $id));
    }


    // ------------------------------ Transaksi ------------------------

    //list of transaksi
    public function Transaksi()
    {
        $query = " SELECT T.*, C.CATEGORY, D.DIVISI, S.STATUS, K.TECHNICIAN_NAME,
        to_char(T.DATE_INSERT,'dd-mm-yyy hh24:mi') DATE_INSERT, to_char(T.DATE_SOLVE, 'dd-mm-yy hh24:mi') DATE_SOLVE, to_char(T.UPDATE_TIME, 'dd-mm-yy hh24:mi') UPDATE_TIME
                        FROM TRANSAKSI  T
                    LEFT JOIN CATEGORY C ON T.ID_CATEGORY = C.ID_CATEGORY
                    LEFT JOIN DIVISI D ON T.ID_DIVISI = D.ID_DIVISI
                    LEFT JOIN STATUS_PROBLEM S ON T.ID_STATUS = S.ID_STATUS
                    LEFT JOIN TECHNICIAN K ON T.ID_TECHNICIAN = K.ID_TECHNICIAN
                    ORDER BY T.ID_TRANSAKSI DESC";
        return $this->db->query($query)->result_array();
    }

    //get search data ticket
    public function searchTransaksi($keywordlog)
    {
        $this->db->select('*');
        $this->db->from('TRANSAKSI');
        $this->db->like('ID_TRANSAKSI', $keywordlog);
        $this->db->or_like('USER_COMPLAIN', $keywordlog);
        $this->db->or_like('ID_CATEGORY', $keywordlog);
        $this->db->or_like('DETAIL', $keywordlog);
        $this->db->join('DIVISI', 'DIVISI.ID_DIVISI = TRANSAKSI.ID_DIVISI');
        $this->db->join('CATEGORY', 'CATEGORY.ID_CATEGORY = TRANSAKSI.ID_CATEGORY');
        $this->db->join('STATUS_PROBLEM', 'STATUS_PROBLEM.ID_STATUS = TRANSAKSI.ID_STATUS');
        return $this->db->get()->result_array();
    }

    //insert data transaksi to table transaksi and status
    public function AddTransaksi()
    {
        //generate custom id
        $year_month = date('ym');
        $count_data = count($this->db->query("SELECT ID_TRANSAKSI FROM TRANSAKSI WHERE ID_TRANSAKSI LIKE " . "'" . $year_month ."%'")->result());
        $plus_data = $count_data + 1;
        $left_id = str_pad($plus_data, 4, STR_PAD_LEFT);
        $co = strrev($left_id);
        $y = date('y');
        $m = date("m") . "-";
        $id_ticket = $y . $m . $co; //2103-00001

        //checkbox solve
        if ($this->input->post('solve') == NULL) {
            $solve = '1';
        } else {
            $solve = $this->input->post('solve');
            $this->db->set('DATE_SOLVE', 'sysdate', false);
        }

        //insert date with time hours, minutes, and seconds
        //sysdate is method from oracle databases
        $this->db->set('DATE_INSERT', 'sysdate', false);
        $this->db->set('UPDATE_TIME', 'sysdate', false);

        $this->db->insert('TRANSAKSI', [
            'ID_TRANSAKSI' => $id_ticket,
            //get data from user input
            'USER_COMPLAIN' => $this->input->post('user_complain'),
            'CONTACT' => $this->input->post('contact'),
            'ID_DIVISI' => $this->input->post('divisi'),
            'PLACE' => $this->input->post('place'),
            //get data user login
            'ADMIN' => $this->session->userdata('email'),
            'ID_TECHNICIAN' => $this->input->post('technician'),
            'ID_CATEGORY' => $this->input->post('category'),
            'DETAIL' => $this->input->post('detail'),
            // status default sedang dikerjakan
            'ID_STATUS' => $solve,
        ]);
    }

    //detail ticket Log
    public function detailsTransaksi($id)
    {
        $query = " SELECT T.*, C.CATEGORY, D.DIVISI, S.STATUS, K.TECHNICIAN_NAME
                        FROM TRANSAKSI  T
                    LEFT JOIN CATEGORY C ON T.ID_CATEGORY = C.ID_CATEGORY
                    LEFT JOIN DIVISI D ON T.ID_DIVISI = D.ID_DIVISI
                    LEFT JOIN STATUS_PROBLEM S ON T.ID_STATUS = S.ID_STATUS
                    LEFT JOIN TECHNICIAN K ON T.ID_TECHNICIAN = K.ID_TECHNICIAN
                    WHERE T.ID_TRANSAKSI = " . "'" . $id . "'";
        return $this->db->query($query)->row_array();
    }

    //edit Transaksi
    public function updateTransaksi($id)
    {
        //insert date with time hours, minutes, and seconds
        //sysdate is method from oracle databases
        $this->db->where('ID_TRANSAKSI', $id);
        if ($this->input->post('status_problem') == NULL) {
            // $solve = '1';
        } else if ($this->input->post('status_problem') == 1) {
            $solve = 1;
        } else if ($this->input->post('status_problem') == 2) {
            $solve = 2;
        } else if ($this->input->post('status_problem') == 3) {
            $solve = 3;
            $this->db->set('DATE_SOLVE', 'sysdate', false);
        }
        $this->db->set('UPDATE_TIME', 'sysdate', false);
        $this->db->update('TRANSAKSI', [
            //get data from user input
            'USER_COMPLAIN' => $this->input->post('user_complain'),
            'CONTACT' => $this->input->post('contact'),
            'ID_DIVISI' => $this->input->post('divisi'),
            'PLACE' => $this->input->post('place'),
            //get data user login
            // 'ADMIN' => $this->session->userdata('email'),
            'ID_TECHNICIAN' => $this->input->post('technician'),
            'ID_CATEGORY' => $this->input->post('category'),
            'DETAIL' => $this->input->post('detail'),
            // status default sedang dikerjakan
            // 'ID_STATUS' => $solve,
            'HOW_TO_SOLVE' => $this->input->post('how_to_solve'),
            'NOTE' => $this->input->post('note'),
        ]);
    }

    //Delete Ticket
    public function DeleteTransaksi($id)
    {
        $this->db->delete('TRANSAKSI', array('ID_TRANSAKSI' => $id));
    }
}
