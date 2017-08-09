<?php
class word extends adfModelBase {
    public function add($data) {
        try {
            $data['length']     = strlen($data['word']);
            $data['soundex']    = soundex($data['word']);
            $data['a_soundex']  = soundex("a{$data['word']}");
            $data['metaphone']  = metaphone($data['word']);
            
            return parent::add($data);
        } catch (Exception $e) {
            return $this->setError($e->getMessage());
        }
    }
    
    public function getCount() {
        if (false != $row = $this->db->getRow("SELECT COUNT(*) AS total FROM `{$this->_table}`")) {
            return $row['total'];
        } else {
            return 0;
        }
    }
    
    public function isValidDefaultWord($word){
        return false !== $this->getOneWhere("word = '".$this->db->escape($word)."' AND origin = 'default'");
    }
    
    public function getByWord($word){
        return $this->getOneByField('word', $word);
    }
    
    public function updateUsage($word, $usage){
        if (false !== $wordRow = $this->getOneByField('word', $word)){
            $this->updateFieldById($wordRow['id'], 'usage', $usage);
        }
    }
    
    public function getUsage($word){
        if (false !== $wordData = $this->getOneByField('word', $word)){
            return $wordData['usage'];
        } else {
            return false;
        }
    }
    
    public function getSimilarDefaultByMetaphone($word){
        return $this->getAllWhere("metaphone = '".$this->db->escape(metaphone($word))."' AND word <> '".$this->db->escape($word)."' AND origin = 'default'");
    }
    
    public function exists($word) {
        if (false != $row = $this->db->getRow("SELECT * FROM `{$this->_table}` WHERE word = '" . $this->db->escape($word) . "'")) {
            return true;
        } else {
            return false;
        }
    }
    
    public function addCustomWord($word){
        return $this->add(array(
            'word' => $word,
            'origin' => 'custom'
        ));
    }
}