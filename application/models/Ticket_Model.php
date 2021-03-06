<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Ticket_Model extends CI_Model
{
    function data($limit, $start)
    {
        $this->db->select('TICKET.*');
        $this->db->select("to_char(TICKET.DATE_INSERT,'dd-mm-yyy hh24:mi') DATE_INSERTTICKET");
        $this->db->select("to_char(TICKET.DATE_SOLVE,'dd-mm-yyy hh24:mi') DATE_SOLVETICKET");
        $this->db->select("to_char(TICKET.UPDATE_TIME,'dd-mm-yyy hh24:mi') UPDATE_TIMETICKET");
        $this->db->select('DIVISI.DIVISI');
        $this->db->select('CATEGORY.CATEGORY');
        $this->db->select('STATUS_PROBLEM.STATUS');
        $this->db->from('TICKET');
        $this->db->join('CATEGORY', 'CATEGORY.ID_CATEGORY = TICKET.ID_CATEGORY');
        $this->db->join('STATUS_PROBLEM', 'STATUS_PROBLEM.ID_STATUS = TICKET.ID_STATUS');
        $this->db->join('DIVISI', 'DIVISI.ID_DIVISI = TICKET.ID_DIVISI');
        $this->db->order_by('TICKET.ID_TICKET', 'DESC');
        $this->db->limit($limit, $start);
        return $this->db->get()->result_array();
    }

    //Fitur Search Ticket
    public function result_get_keyword($keyword)
    {
        $this->db->select('TICKET.*');
        $this->db->select('DIVISI.DIVISI');
        $this->db->select('CATEGORY.CATEGORY');
        $this->db->select('STATUS_PROBLEM.STATUS');
        $this->db->from('TICKET');
        $this->db->like('ID_TICKET', $keyword);
        $this->db->or_like('USER_COMPLAIN', $keyword);
        $this->db->or_like('CONTACT', $keyword);
        $this->db->or_like('DIVISI', $keyword);
        $this->db->or_like('PLACE', $keyword);
        $this->db->or_like('DATE_INSERT', $keyword);
        $this->db->or_like('CATEGORY', $keyword);
        $this->db->or_like('DETAIL', $keyword);
        $this->db->or_like('STATUS', $keyword);
        $this->db->or_like('DATE_SOLVE', $keyword);
        $this->db->join('DIVISI', 'DIVISI.ID_DIVISI = TICKET.ID_DIVISI');
        $this->db->join('CATEGORY', 'CATEGORY.ID_CATEGORY = TICKET.ID_CATEGORY');
        $this->db->join('STATUS_PROBLEM', 'STATUS_PROBLEM.ID_STATUS = TICKET.ID_STATUS');
        return $this->db->get()->result_array();
    }

    //Fitur Search Ticket
    public function get_keyword($keyword, $limit, $start)
    {
        $this->db->select('TICKET.*');
        $this->db->select("to_char(TICKET.DATE_INSERT,'dd-mm-yyy hh24:mi') DATE_INSERTTICKET");
        $this->db->select("to_char(TICKET.DATE_SOLVE,'dd-mm-yyy hh24:mi') DATE_SOLVETICKET");
        $this->db->select("to_char(TICKET.UPDATE_TIME,'dd-mm-yyy hh24:mi') UPDATE_TIMETICKET");
        $this->db->select('DIVISI.DIVISI');
        $this->db->select('CATEGORY.CATEGORY');
        $this->db->select('STATUS_PROBLEM.STATUS');
        $this->db->from('TICKET');
        $this->db->like('TICKET.ID_TICKET', $keyword);
        $this->db->or_like('TICKET.USER_COMPLAIN', $keyword);
        $this->db->or_like('TICKET.CONTACT', $keyword);
        $this->db->or_like('TICKET.PLACE', $keyword);
        $this->db->or_like('DIVISI.DIVISI', $keyword);
        $this->db->or_like('TICKET.DATE_INSERT', $keyword);
        $this->db->or_like('CATEGORY.CATEGORY', $keyword);
        $this->db->or_like('TICKET.DETAIL', $keyword);
        $this->db->or_like('STATUS_PROBLEM.STATUS', $keyword);
        $this->db->or_like('TICKET.DATE_SOLVE', $keyword);
        $this->db->join('CATEGORY', 'CATEGORY.ID_CATEGORY = TICKET.ID_CATEGORY');
        $this->db->join('STATUS_PROBLEM', 'STATUS_PROBLEM.ID_STATUS = TICKET.ID_STATUS');
        $this->db->join('DIVISI', 'DIVISI.ID_DIVISI = TICKET.ID_DIVISI');
        $this->db->order_by('TICKET.ID_TICKET', 'DESC');
        $this->db->limit($limit, $start);
        return $this->db->get()->result_array();
    }

    //Details ticket
    public function details($id)
    {
        $query = " SELECT T.*, C.CATEGORY, D.DIVISI, S.STATUS, K.TECHNICIAN_NAME,
         to_char(T.DATE_INSERT,'dd-mm-yyy hh24:mi') DATE_INSERT, to_char(T.DATE_SOLVE, 'dd-mm-yy hh24:mi') DATE_SOLVE, to_char(T.UPDATE_TIME, 'dd-mm-yy hh24:mi') UPDATE_TIME
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
        $count_data = count($this->db->query("SELECT ID_TICKET FROM TICKET WHERE ID_TICKET LIKE " . "'" . $year_month . "%'")->result());
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

    //Search Email Technician
    public function EmailTechnician($id)
    {
        $query = "SELECT EMAIL FROM TECHNICIAN WHERE ID_TECHNICIAN = " .  $id;
        return $this->db->query($query)->row_array();
    }

    // ------------------------------ Ticket Log ------------------------

    //get all data ticket
    function dataLog($limit, $start)
    {
        $this->db->select('TICKET_LOG.*');
        $this->db->select("to_char(TICKET_LOG.DATE_INSERT,'dd-mm-yyy hh24:mi') DATE_INSERTLOG");
        $this->db->select("to_char(TICKET_LOG.DATE_SOLVE,'dd-mm-yyy hh24:mi') DATE_SOLVELOG");
        $this->db->select("to_char(TICKET_LOG.UPDATE_TIME,'dd-mm-yyy hh24:mi') UPDATE_TIMELOG");
        $this->db->select('DIVISI.DIVISI');
        $this->db->select('CATEGORY.CATEGORY');
        $this->db->select('STATUS_PROBLEM.STATUS');
        $this->db->from('TICKET_LOG');
        $this->db->join('CATEGORY', 'CATEGORY.ID_CATEGORY = TICKET_LOG.ID_CATEGORY');
        $this->db->join('STATUS_PROBLEM', 'STATUS_PROBLEM.ID_STATUS = TICKET_LOG.ID_STATUS');
        $this->db->join('DIVISI', 'DIVISI.ID_DIVISI = TICKET_LOG.ID_DIVISI');
        $this->db->order_by('TICKET_LOG.ID_TICKET_LOG', 'DESC');
        $this->db->order_by('TICKET_LOG.ID_STATUS', 'ASC');
        $this->db->limit($limit, $start);
        return $this->db->get()->result_array();
    }

    //get search data ticket
    public function result_searchLog($keywordlog)
    {
        $this->db->select('TICKET_LOG.*');
        $this->db->select('DIVISI.DIVISI');
        $this->db->select('CATEGORY.CATEGORY');
        $this->db->select('STATUS_PROBLEM.STATUS');
        $this->db->from('TICKET_LOG');
        $this->db->like('ID_TICKET_LOG', $keywordlog);
        $this->db->or_like('USER_COMPLAIN', $keywordlog);
        $this->db->or_like('CONTACT', $keywordlog);
        $this->db->or_like('DIVISI', $keywordlog);
        $this->db->or_like('PLACE', $keywordlog);
        $this->db->or_like('DATE_INSERT', $keywordlog);
        $this->db->or_like('CATEGORY', $keywordlog);
        $this->db->or_like('DETAIL', $keywordlog);
        $this->db->or_like('STATUS', $keywordlog);
        $this->db->or_like('DATE_SOLVE', $keywordlog);
        $this->db->join('DIVISI', 'DIVISI.ID_DIVISI = TICKET_LOG.ID_DIVISI');
        $this->db->join('CATEGORY', 'CATEGORY.ID_CATEGORY = TICKET_LOG.ID_CATEGORY');
        $this->db->join('STATUS_PROBLEM', 'STATUS_PROBLEM.ID_STATUS = TICKET_LOG.ID_STATUS');
        return $this->db->get()->result_array();
    }

    public function searchLog($keywordlog, $limit, $start)
    {
        $this->db->select('TICKET_LOG.*');
        $this->db->select("to_char(TICKET_LOG.DATE_INSERT,'dd-mm-yyy hh24:mi') DATE_INSERTLOG");
        $this->db->select("to_char(TICKET_LOG.DATE_SOLVE,'dd-mm-yyy hh24:mi') DATE_SOLVELOG");
        $this->db->select("to_char(TICKET_LOG.UPDATE_TIME,'dd-mm-yyy hh24:mi') UPDATE_TIMELOG");
        $this->db->select('DIVISI.DIVISI');
        $this->db->select('CATEGORY.CATEGORY');
        $this->db->select('STATUS_PROBLEM.STATUS');
        $this->db->from('TICKET_LOG');
        $this->db->like('TICKET_LOG.ID_TICKET_LOG', $keywordlog);
        $this->db->or_like('TICKET_LOG.USER_COMPLAIN', $keywordlog);
        $this->db->or_like('TICKET_LOG.CONTACT', $keywordlog);
        $this->db->or_like('DIVISI.DIVISI', $keywordlog);
        $this->db->or_like('TICKET_LOG.PLACE', $keywordlog);
        $this->db->or_like('TICKET_LOG.DATE_INSERT', $keywordlog);
        $this->db->or_like('CATEGORY.CATEGORY', $keywordlog);
        $this->db->or_like('TICKET_LOG.DETAIL', $keywordlog);
        $this->db->or_like('STATUS_PROBLEM.STATUS', $keywordlog);
        $this->db->or_like('TICKET_LOG.DATE_SOLVE', $keywordlog);
        $this->db->join('DIVISI', 'DIVISI.ID_DIVISI = TICKET_LOG.ID_DIVISI');
        $this->db->join('CATEGORY', 'CATEGORY.ID_CATEGORY = TICKET_LOG.ID_CATEGORY');
        $this->db->join('STATUS_PROBLEM', 'STATUS_PROBLEM.ID_STATUS = TICKET_LOG.ID_STATUS');
        $this->db->order_by('TICKET_LOG.ID_TICKET_LOG', 'DESC');
        $this->db->order_by('TICKET_LOG.ID_STATUS', 'ASC');
        $this->db->limit($limit, $start);
        return $this->db->get()->result_array();
    }

    //insert data ticket to table ticket and status
    public function AddLog()
    {
        //generate custom id
        $year_month = date('ym');
        $count_data = count($this->db->query("SELECT ID_TICKET FROM TICKET WHERE ID_TICKET LIKE " . "'" . $year_month . "%'")->result());
        $plus_data = $count_data;
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
        $query = " SELECT T.*, C.CATEGORY, D.DIVISI, S.STATUS, K.TECHNICIAN_NAME,
         to_char(T.DATE_INSERT,'dd-mm-yyy hh24:mi') DATE_INSERT, to_char(T.DATE_SOLVE, 'dd-mm-yy hh24:mi') DATE_SOLVE, to_char(T.UPDATE_TIME, 'dd-mm-yy hh24:mi') UPDATE_TIME
                        FROM TICKET_LOG  T
                    LEFT JOIN CATEGORY C ON T.ID_CATEGORY = C.ID_CATEGORY
                    LEFT JOIN DIVISI D ON T.ID_DIVISI = D.ID_DIVISI
                    LEFT JOIN STATUS_PROBLEM S ON T.ID_STATUS = S.ID_STATUS
                    LEFT JOIN TECHNICIAN K ON T.ID_TECHNICIAN = K.ID_TECHNICIAN
                    WHERE T.ID_TICKET_LOG = " . "'" . $id . "'";
        return $this->db->query($query)->row_array();
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

    // ------------------------------ Transaksi ------------------------

    //list of transaksi
    function dataTransaksi($limit, $start)
    {
        $this->db->select('TRANSAKSI.*');
        $this->db->select("to_char(TRANSAKSI.DATE_INSERT,'dd-mm-yyy hh24:mi') DATE_INSERTTRANSAKSI");
        $this->db->select("to_char(TRANSAKSI.DATE_SOLVE,'dd-mm-yyy hh24:mi') DATE_SOLVETRANSAKSI");
        $this->db->select("to_char(TRANSAKSI.UPDATE_TIME,'dd-mm-yyy hh24:mi') UPDATE_TIMETRANSAKSI");
        $this->db->select('DIVISI.DIVISI');
        $this->db->select('CATEGORY.CATEGORY');
        $this->db->select('STATUS_PROBLEM.STATUS');
        $this->db->from('TRANSAKSI');
        $this->db->join('CATEGORY', 'CATEGORY.ID_CATEGORY = TRANSAKSI.ID_CATEGORY');
        $this->db->join('STATUS_PROBLEM', 'STATUS_PROBLEM.ID_STATUS = TRANSAKSI.ID_STATUS');
        $this->db->join('DIVISI', 'DIVISI.ID_DIVISI = TRANSAKSI.ID_DIVISI');
        $this->db->order_by('TRANSAKSI.ID_TRANSAKSI', 'DESC');
        $this->db->limit($limit, $start);
        return $this->db->get()->result_array();
    }

    //get search data ticket
    public function result_searchTransaksi($keywordlog)
    {
        $this->db->select('TRANSAKSI.*');
        $this->db->select("to_char(TRANSAKSI.DATE_INSERT,'dd-mm-yyy hh24:mi') DATE_INSERT");
        $this->db->select("to_char(TRANSAKSI.DATE_SOLVE,'dd-mm-yyy hh24:mi') DATE_SOLVE");
        $this->db->select("to_char(TRANSAKSI.UPDATE_TIME,'dd-mm-yyy hh24:mi') UPDATE_TIME");
        $this->db->select('DIVISI.DIVISI');
        $this->db->select('CATEGORY.CATEGORY');
        $this->db->select('STATUS_PROBLEM.STATUS');
        $this->db->from('TRANSAKSI');
        $this->db->like('ID_TRANSAKSI', $keywordlog);
        $this->db->or_like('USER_COMPLAIN', $keywordlog);
        $this->db->or_like('CONTACT', $keywordlog);
        $this->db->or_like('PLACE', $keywordlog);
        $this->db->or_like('ID_TECHNICIAN', $keywordlog);
        $this->db->or_like('ID_CATEGORY', $keywordlog);
        $this->db->or_like('DETAIL', $keywordlog);
        $this->db->or_like('ID_STATUS', $keywordlog);
        $this->db->or_like('HOW_TO_SOLVE', $keywordlog);
        $this->db->or_like('DATE_INSERT', $keywordlog);
        $this->db->or_like('DATE_SOLVE', $keywordlog);
        $this->db->or_like('NOTE', $keywordlog);
        $this->db->or_like('UPDATE_TIME', $keywordlog);
        $this->db->join('DIVISI', 'DIVISI.ID_DIVISI = TRANSAKSI.ID_DIVISI');
        $this->db->join('CATEGORY', 'CATEGORY.ID_CATEGORY = TRANSAKSI.ID_CATEGORY');
        $this->db->join('STATUS_PROBLEM', 'STATUS_PROBLEM.ID_STATUS = TRANSAKSI.ID_STATUS');
        return $this->db->get()->result_array();
    }

    public function searchTransaksi($keywordlog, $limit, $start)
    {
        $this->db->select('TRANSAKSI.*');
        $this->db->select("to_char(TRANSAKSI.DATE_INSERT,'dd-mm-yyy hh24:mi') DATE_INSERTTRANSAKSI");
        $this->db->select("to_char(TRANSAKSI.DATE_SOLVE,'dd-mm-yyy hh24:mi') DATE_SOLVETRANSAKSI");
        $this->db->select("to_char(TRANSAKSI.UPDATE_TIME,'dd-mm-yyy hh24:mi') UPDATE_TIMETRANSAKSI");
        $this->db->select('DIVISI.DIVISI');
        $this->db->select('CATEGORY.CATEGORY');
        $this->db->select('STATUS_PROBLEM.STATUS');
        $this->db->from('TRANSAKSI');
        $this->db->like('TRANSAKSI.ID_TRANSAKSI', $keywordlog);
        $this->db->or_like('TRANSAKSI.USER_COMPLAIN', $keywordlog);
        $this->db->or_like('TRANSAKSI.CONTACT', $keywordlog);
        $this->db->or_like('TRANSAKSI.PLACE', $keywordlog);
        $this->db->or_like('CATEGORY.CATEGORY', $keywordlog);
        $this->db->or_like('TRANSAKSI.DETAIL', $keywordlog);
        $this->db->or_like('STATUS_PROBLEM.STATUS', $keywordlog);
        $this->db->or_like('TRANSAKSI.HOW_TO_SOLVE', $keywordlog);
        $this->db->or_like('TRANSAKSI.DATE_INSERT', $keywordlog);
        $this->db->or_like('TRANSAKSI.DATE_SOLVE', $keywordlog);
        $this->db->or_like('TRANSAKSI.NOTE', $keywordlog);
        $this->db->or_like('TRANSAKSI.UPDATE_TIME', $keywordlog);
        $this->db->join('DIVISI', 'DIVISI.ID_DIVISI = TRANSAKSI.ID_DIVISI');
        $this->db->join('CATEGORY', 'CATEGORY.ID_CATEGORY = TRANSAKSI.ID_CATEGORY');
        $this->db->join('STATUS_PROBLEM', 'STATUS_PROBLEM.ID_STATUS = TRANSAKSI.ID_STATUS');
        $this->db->order_by('TRANSAKSI.ID_TRANSAKSI', 'DESC');
        $this->db->limit($limit, $start);
        return $this->db->get()->result_array();
    }

    //insert data transaksi to table transaksi and status
    public function AddTransaksi()
    {
        //generate custom id
        $year_month = date('ym');
        $count_data = count($this->db->query("SELECT ID_TICKET FROM TICKET WHERE ID_TICKET LIKE " . "'" . $year_month . "%'")->result());
        $plus_data = $count_data;
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
        $query = " SELECT T.*, C.CATEGORY, D.DIVISI, S.STATUS, K.TECHNICIAN_NAME,
         to_char(T.DATE_INSERT,'dd-mm-yyy hh24:mi') DATE_INSERT, to_char(T.DATE_SOLVE, 'dd-mm-yy hh24:mi') DATE_SOLVE, to_char(T.UPDATE_TIME, 'dd-mm-yy hh24:mi') UPDATE_TIME
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
        // var_dump($this->db->get_where('TICKET', array('ID_TICKET' => $id))->row()->USER_COMPLAIN);die();
        //insert date with time hours, minutes, and seconds
        //sysdate is method from oracle databases
        if ($this->input->post('status_problem') == NULL) {
            // $solve = '1';
        } else if ($this->input->post('status_problem') == 1) {
            $solve = 1;
        } else {
            if ($this->input->post('status_problem') == 2) {
                $solve = 2;
                // update status on ticket
                $this->_updateStatusTicket($id, $solve);
                // update status on ticketLog
                $this->_updateStatusLog($id, $solve);
            } else if ($this->input->post('status_problem') == 3) {
                $solve = 3;
                // update status on ticket
                $this->_updateStatusTicket($id, $solve);
                // update status on ticketLog
                $this->_updateStatusLog($id, $solve);
            }
        }
        $this->db->where('ID_TRANSAKSI', $id);
        if ($solve == 3) {
            // update date solve to now
            $this->db->set('DATE_SOLVE', 'sysdate', false);
        }
        $this->db->set('UPDATE_TIME', 'sysdate', false);
        $this->db->update('TRANSAKSI', [
            // status default sedang dikerjakan
            'ID_STATUS' => $solve,
        ]);
    }

    //Delete Ticket
    public function DeleteTransaksi($id)
    {
        $this->db->delete('TRANSAKSI', array('ID_TRANSAKSI' => $id));
    }

    private function _updateStatusLog($id, $solve)
    {

        if ($solve == 3) {
            $this->db->set('DATE_SOLVE', 'sysdate', false);
        }
        //insert date with time hours, minutes, and seconds
        //sysdate is method from oracle databases
        $this->db->set('DATE_INSERT', 'sysdate', false);
        $this->db->set('UPDATE_TIME', 'sysdate', false);

        $this->db->insert('TICKET_LOG', [
            'ID_TICKET_LOG' => $id,
            //get data from user input
            'USER_COMPLAIN' => $this->db->get_where('TICKET', array('ID_TICKET' => $id))->row()->USER_COMPLAIN,
            'CONTACT' => $this->db->get_where('TICKET', array('ID_TICKET' => $id))->row()->CONTACT,
            'ID_DIVISI' => $this->db->get_where('TICKET', array('ID_TICKET' => $id))->row()->ID_DIVISI,
            'PLACE' => $this->db->get_where('TICKET', array('ID_TICKET' => $id))->row()->PLACE,
            //get data user login
            'ADMIN' => $this->session->userdata('email'),
            'ID_TECHNICIAN' => $this->db->get_where('TICKET', array('ID_TICKET' => $id))->row()->ID_TECHNICIAN,
            'ID_CATEGORY' => $this->db->get_where('TICKET', array('ID_TICKET' => $id))->row()->ID_CATEGORY,
            'DETAIL' => $this->db->get_where('TICKET', array('ID_TICKET' => $id))->row()->DETAIL,
            // status default sedang dikerjakan
            'ID_STATUS' => $solve,
            'HOW_TO_SOLVE' => $this->db->get_where('TICKET', array('ID_TICKET' => $id))->row()->HOW_TO_SOLVE,
            'NOTE' => $this->db->get_where('TICKET', array('ID_TICKET' => $id))->row()->NOTE,
        ]);
    }

    private function _updateStatusTicket($id, $solve)
    {
        $this->db->where('ID_TICKET', $id);
        if ($solve == 3) {
            $this->db->set('DATE_SOLVE', 'sysdate', false);
        }
        //sysdate is method from oracle databases
        $this->db->set('UPDATE_TIME', 'sysdate', false);
        $this->db->update('TICKET', [
            'ID_STATUS' => $solve,
            'HOW_TO_SOLVE' => $this->input->post('how_to_solve'),
            'NOTE' => $this->input->post('note'),
        ]);
    }
}
