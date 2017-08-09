<?php

class domainReadingPart extends adfModelBase{
    protected $_table = 'domainreadingpart';
    public function addNewReading($newDomainId, $readingOrder, $parsedReading){
        $wordM = new word();
        
        foreach($parsedReading as $parsedReadingOrder => $parsedReadingPart){
            if (false !== $wordData = $wordM->getByWord($parsedReadingPart['notParsed'])){
                $wordId = $wordData['id'];
            } else {
                if (false == $wordId = $wordM->addCustomWord($parsedReadingPart['notParsed'])){
                    throw new Exception($wordM->getError());
                } 
            }
            
            $domainReadingPartData = array(
                'domain_id' => $newDomainId,
                'phrase_id' => $readingOrder,
                'phrase_order' => $parsedReadingOrder,
                'word_id' => $wordId,
                'separator' => (empty($parsedReadingPart['separator']) ? '' : $parsedReadingPart['separator'])
            );
            if (false === $this->add($domainReadingPartData)){
                return false;
            }
        }
        
        return true;
    }
    
    public static function checkReadingsAvailabilities($readings){
        foreach($readings as $key => $reading){
            if ($this->isReadingUsed($reading['value'])){
                $readings[$key]['availability'] = 'used';
            } elseif ($this->isSimilarReadingUsedMetaphone($reading['value'])){
                $readings[$key]['availability'] = 'similarUsed';
            } else {
                $readings[$key]['availability'] = 'available';
            }
        }
        return $readings;
    }
    
    public static function mergeCustomReadingsForDisplay($readings, $selectedReadings){
        $readingsValuesInGenerated = array_map(function($reading) {return $reading['value'];}, $readings);
        foreach($selectedReadings as $selectedReading){
            if (!in_array($selectedReading, $readingsValuesInGenerated)){
                $readings[] = array(
                    'value' => $selectedReading,
                    'display' => $selectedReading
                );
            }
        }
        return $readings;
    }
    
    public function getReadingsByDomain($domainId){
        $results = array();
        $readingsQuery = "SELECT GROUP_CONCAT(CONCAT(word.word, drp.separator) ORDER BY drp.phrase_order SEPARATOR '') reading
            FROM domainreadingpart as drp
            JOIN word on (word.id = drp.word_id)
            WHERE domain_id = '".$this->db->escape($domainId)."'
            GROUP BY phrase_id";
        if (false !== $readings = $this->db->getRecords($readingsQuery)){
            foreach($readings as $reading){
                $results[] = $reading['reading'];
            }
        }
        return $results;
    }
    
    public function getReadingsForGrammarByDomain($domainId){
        $readings = $this->getReadingsByDomain($domainId);
        $results = array();
        foreach($readings as $reading){
            $results[] = $this->prepareReadingForGrammar($reading);
        }
        return $results;
    }
    
    
    private function prepareReadingForGrammar($reading){
        $readingInParts = ReadingsGenerator::getPartsFromReading($reading);
        $result = array();
        foreach($readingInParts as $readingPartIndex => $partReading){
            $parsedPartData = array(
                'part' => $partReading['notParsed']
            );
            
            // if it's last part and it's short, then it can be omitted ... I guess ;)
            if ($readingPartIndex == count($readingInParts) - 1 && strlen($parsedPartData['part']) <= 4){
                $parsedPartData['optional'] = true;
            }
            
            if (!empty($partReading['separator']) && $partReading['separator'] != '-'){
                $parsedPartData['separator'] = $partReading['separator'];
            }
            
            $result[] = $parsedPartData;
        }
        return $result;
    }
    
    public function isReadingUsed($reading){
        $testQuery = "SELECT GROUP_CONCAT(CONCAT(word.word, drp.separator) ORDER BY drp.phrase_order SEPARATOR '') reading
            FROM domainreadingpart as drp
            JOIN word on (word.id = drp.word_id)
            GROUP BY domain_id, phrase_id
            HAVING reading = '".$this->db->escape($reading['value'])."'
            LIMIT 1";
        return false !== $this->db->getRow($testQuery);
    }
    
    public function isSimilarReadingUsedMetaphone($reading){
        $readingsMetaphoneReading = '';
        $readingParts = ReadingsGenerator::getPartsFromReading($reading);
        foreach($readingParts as $readingPart){
            $readingsMetaphoneReading .= metaphone($readingPart['notParsed']) . (!empty($readingPart['separator']) ? $readingPart['separator'] : '');
        }
        $testQuery = "SELECT GROUP_CONCAT(CONCAT(word.metaphone, drp.separator) ORDER BY drp.phrase_order SEPARATOR '') metaphone_reading
            FROM domainreadingpart as drp
            JOIN word on (word.id = drp.word_id)
            GROUP BY domain_id, phrase_id
            HAVING metaphone_reading = '".$this->db->escape($readingsMetaphoneReading)."'
            LIMIT 1";
        
        return false !== $this->db->getRow($testQuery);
    }
    
}

