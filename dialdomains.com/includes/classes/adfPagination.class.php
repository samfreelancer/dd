<?php
class adfPagination {
    public function __construct(adfDb $db) {
        $this->db = $db;
    }
    
    public function get($query, $page, $per_page) {
        $this->page     = $page;
        $this->per_page = $per_page;

        if (!$this->db->query($query) || !$this->db->getResult() || $this->db->numRows() < 1) {
            return false;
        }

        $this->computePagination();
        $this->db->dataSeek($this->first_row);
        $rows   = $this->retrieveRows();
        $cnt    = count($rows);

        $return = array(
            'record_start'      => $this->first_record,
            'record_end'        => $this->first_row + $cnt,
            'records_returned'  => $cnt,
            'records_total'     => $this->total_records,
            'total_pages'       => $this->total_pages,
            'rows'              => $rows,
        );
        
        return $return;
    }

    private function retrieveRows() {
        $results = array();
        for ($i = 0; $i < $this->per_page; $i++) {
            if (!$row = $this->db->fetchAssoc()) {
                break;
            }

            $results[] = $row;
        }

        return $results;
    }
    
    private function computePagination($self = false) {
        $this->first_row        = $this->computeFirstRow();
        $this->total_records    = $this->db->numRows();
        $this->first_record     = $this->first_row + 1;
        $this->total_pages      = $this->computePageCount();

        # if the current page is greater than the last page, display the last page
        if (!$self && $this->page > $this->total_pages) {
            $this->page = $this->total_pages;
            $this->computePagination(true);
        }
    }
    
    private function computePageCount() {
    	return ceil($this->total_records / $this->per_page);
    }
    
    private function computeFirstRow() {
    	return max((($this->per_page * $this->page) - $this->per_page), 0);
    }
}
