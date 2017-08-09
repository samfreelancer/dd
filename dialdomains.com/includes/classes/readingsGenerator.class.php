<?php
class ReadingsGenerator {
    private $state;
    private $domain;
    private $domainLength;
    private $domainOriginalParts;
    private $wordM;
    private $maxWordLength = 24;
    private $wordLengthsOrderedByUsage = array(9, 10, 8, 11, 7, 12, 6, 13, 5, 14, 15, 4, 16, 17, 3, 18, 19, 20, 2, 21, 22, 1, 23, 24);
    private $passedUrlParts = array('www', 'http', 'com', 'org', 'eu', 'me'/* ... */);
    
    public function __construct($domain, $partialState = array()) {
        $this->domain = $domain;
        $this->domainLength = strlen($domain);
        $this->state = $partialState;
        $this->wordM = new word();
    }
    
    public static function getPartsFromReading($reading){
        $explodePatterns = '~([.]+)|([-]+)|([0-9]+)~';
        if (is_array($reading)) {
            $splitParts = preg_split($explodePatterns, $reading['value'], -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY );
        } else {
            $splitParts = preg_split($explodePatterns, $reading, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY );    
        }
        $parts = array();
        for($i = 0; $i < count($splitParts); $i += 2){
            if (isset($splitParts[$i+1])){
                $separator = $splitParts[$i+1];
            } else {
                $separator = null;
            }
            
            $parts[] = array(
                'parsed' => '',
                'notParsed' => $splitParts[$i],
                'separator' => $separator,
                'method' => 'getReadingFromPartFromWords',
                'bannedMethods' => array() // list of heuristics we've already tried, so no use to do it again on this part
            );
        }
        return $parts;
    }
    
    public function getReadings(){
        
        if (empty($this->state)){
            $domain = $this->preprocessDomain($this->domain);
            // todo, separate by numbers as well
            if (empty($domain)) {
                return array();
            }

            $parts = self::getPartsFromReading($domain);

            $this->domainOriginalParts = $parts;
            
            $this->state = array(
                array(
                    'parts' => $parts,
                    'finished' => false,
                    'score' => '0'
                )
            );
        }
        
        $this->processState(100);
                
        return $this->getBestFromState(100);
    }
    
    private function preprocessDomain($domain){
        if (strpos($domain, 'http://') !== 0 && strpos($domain, 'https://') !== 0) {
            $domain = 'http://' . $domain;
        }

        $parsedUrl = parse_url($domain);
        return $parsedUrl['host'];
    }

    private function processState($readingsToGenerate){
        for($processedItems = count($this->getProcessedInState()); $processedItems < $readingsToGenerate && $this->notProcessedExistsInState(); $processedItems = count($this->getProcessedInState())){
            $index = $this->getBestNotProcessedIndexInState();
            $this->processStateItem($index);
        }
    }

    private function getProcessedInState(){
        return array_filter(
                $this->state,
                function($stateItem) { return $stateItem['finished']; } 
        );
    }
    
    private function notProcessedExistsInState(){
        foreach($this->state as $stateItem){
            if (!$stateItem['finished']){
                return true;
            }
        }
        return false;
    }
    
    private function getBestNotProcessedIndexInState(){
        $index = null;
        foreach($this->state as $i => $stateItem){
            if (!$stateItem['finished'] && ($index === null || $this->state[$index]['score'] < $stateItem['score'])){
                $index = $i;
            }
        }
        return $index;
    }
    
    private function getBestFromState($returnedOptionsCount){
        $finishedInState = array_filter($this->state, function($stateItem){return $stateItem['finished'];});
        usort($finishedInState, function($stateItemA, $stateItemB) {return ($stateItemA['score'] > $stateItemB['score']) ? -1 : 1;});
        $finishedInStateWithoutDuplicates = $this->removeDuplicatesFromStateItemArray($finishedInState);
        $selectedFinishedInState = array_slice($finishedInStateWithoutDuplicates, 0, $returnedOptionsCount);
        return array_map(function($itemState){
                $reading = $this->getReadingFromParts($itemState['parts']);
                return array(
                    'value' => $reading, 
                    'display' => $reading
                );

            },
            $selectedFinishedInState
        );
    }
    
    private function removeDuplicatesFromStateItemArray($finishedInState){
        $alreadyAvailableReadings = array();
        $results = array();
        foreach($finishedInState as $stateItem){
            $reading = $this->getReadingFromParts($stateItem['parts']);
            if (!in_array($reading, $alreadyAvailableReadings)){
                $results[] = $stateItem;
                $alreadyAvailableReadings[] = $reading;
            }
        }
        return $results;
    }
    
    private function getReadingFromParts($parts){
        $reading = '';
        foreach($parts as $part){
            $reading .= $part['parsed'] . $part['separator'];
        }
        return $reading;
    }
    
    private function processStateItem($index){
        $item = $this->state[$index];

        $partIndex = $this->getFirstNotProcessedPartFromStateItem($item);
        
        $partsFromUpdate = $this->getUpdatedStateItemPart($this->state[$index]['parts'][$partIndex]);
        
        if (empty($partsFromUpdate)){
            // we are unable to parse one of the parts, so the whole reading goes to trash
            $this->removeIndexFromState($index);
        }
        
        for($i = 0; $i < count($partsFromUpdate); ++$i){
            if ($i + 1 == count($partsFromUpdate)){
                // last generated part overrides original
                $this->state[$index]['parts'][$partIndex] = $partsFromUpdate[$i];
                $newStateItemIndex = $index;
            } else {
                $newStateItemIndex = $this->cloneStateItemByIndex($index);
                $this->state[$newStateItemIndex]['parts'][$partIndex] = $partsFromUpdate[$i];
            }
            
            if (false === $this->getFirstNotProcessedPartFromStateItem($this->state[$newStateItemIndex])){
                $this->state[$newStateItemIndex]['finished'] = true;
            }
            
            $this->state[$newStateItemIndex]['score'] = $this->scoreStateItem($this->state[$newStateItemIndex]);
        }
        
        ++$this->loops;
        if ($this->loops > 1500){   
            $this->removeNotProcessedStateItems();
        }
    }
    
    private $loops = 0;
    
    private function removeNotProcessedStateItems(){
        $newState = array();
        foreach($this->state as $stateItem){
            if ($stateItem['finished']){
                $newState[] = $stateItem;
            }
        }
        $this->state = $newState;
    }
    
    private function getFirstNotProcessedPartFromStateItem($item){
        foreach($item['parts'] as $i => $part){
            if (!empty($part['notParsed'])){
                return $i;
            }
        }
        
        return false; // all processed
    }
    
    private function cloneStateItemByIndex($index){
        $item = $this->state[$index];
        $this->state[] = $item;
        // return inserted index
        end($this->state);
        return key($this->state);
    }
    
    private function removeIndexFromState($index){
        unset($this->state[$index]);
    }
    
    private function scoreStateItem($stateItem){

        // wages for different types of score elements.
        // this drives the whole algorithm towards this or that solution
        $maxScoreBasedOnFinishedStatus = 150;
        $maxScoreBasedOnASoundex = 100;
        $maxScoreBasedOnMetaphone = 75;
        $maxScoreBasedOnSimilarity = 250;
        $maxScoreBasedOnMethod = 200;
        $maxScoreBasedOnPartsNumber = 150;
        
        // not started parsings are scored only based on method, others are scored based on everything and start at 0
        
        $parsingStarted = $this->parsingOfStateItemStarted($stateItem['parts']);
        
        if ($parsingStarted){
            $finalScore = 0;
        } else {
            $finalScore = 500;
        }
        
        // some heuristics produce better results than others, so readings using those methods have higher scores
        $methodsScores = array(
            'getReadingFromPartFromWords' => 0,
            'getReadingFromPartFromWordsMatchingHeuristics' => 0,
            'getReadingFromPartFromWordsLongestWordsHeuristic' => 50,
            'getReadingFromPartFromWordsCommonLengthWordsHeuristic' => 50,
            'getReadingFromPartFromWordsLongestWordsMetaphoneHeuristic' => 50,
            'getReadingFromPartFromWordsCommonLengthWordsMetaphoneHeuristic' => 50,
            'getReadingFromPartFromWordsUsageHeuristic' => 100,
            'getReadingFromPartFromWordsUsageMetaphoneHeuristic' => 0, // generates too many wild options
            'getReadingFromPartFromWordsPrefixPostfixHeuristic' => 50,
            'getReadingFromPartFromWordsAllCutsHeuristic' => 50
        );
        
        $methodScore = 0;
        foreach($stateItem['parts'] as $partIndex => $part){
            if ($methodScore < $methodsScores[$part['method']]){
                $methodScore = $methodsScores[$part['method']];
            }
            if (!empty($part['bannedMethods'])){
                $methodScore = 0;
                break;
            }
        }
        $finalScore += ($methodScore / 100) * $maxScoreBasedOnMethod;
        
        if (!$parsingStarted){
            return $finalScore; // other methods do not apply
        }
        
        // the more is finished the better. To get some parsed results
        $finishedLength = 0;
        foreach($stateItem['parts'] as $part){
            if (!empty($part['parsed'])){
                $finishedLength += strlen($part['parsed']);
            }
        }
        $finalScore += ($finishedLength / $this->domainLength) * $maxScoreBasedOnFinishedStatus;
        
        // the more similar to original the better
        $distanceBetweenNamesScore = $this->getSimilarityScore($stateItem['parts'], function($text) { return $text; });
        $finalScore += $distanceBetweenNamesScore * $maxScoreBasedOnSimilarity;
        
        // the more similar metaphone the better
        $distanceBetweenMetaphonesScore = $this->getSimilarityScore($stateItem['parts'], function($text) { return metaphone($text); });
        $finalScore += $distanceBetweenMetaphonesScore * $maxScoreBasedOnMetaphone; 
        
        $commonSoundexNumbers = 0;
        foreach($stateItem['parts'] as $partIndex => $part){
            $partText = str_replace('-', '', $part['parsed'] . $part['notParsed']);
            $originalPartText = $this->domainOriginalParts[$partIndex]['notParsed'];
            $parsedSoundex = soundex('A'.$partText);
            $originalSoundex = soundex('A' . $originalPartText);
            for($i = 1; $i < 4; ++$i){
                if ($parsedSoundex[$i] == $originalSoundex[$i]){
                    ++$commonSoundexNumbers;
                }
            }
        }
        $finalScore += ($commonSoundexNumbers / (count($stateItem) * 3)) * $maxScoreBasedOnASoundex;
        
        $partsNumber = 0;
        foreach($stateItem['parts'] as $part){
            $partsNumber += count(explode('-', $part['parsed']));
        }
        
        $finalScore += (1 + count($this->domainOriginalParts) - $partsNumber / count($this->domainOriginalParts)) * $maxScoreBasedOnPartsNumber; 
        
        
        return $finalScore;
    }
    
    /**
     * Calculates how similar parts are to original after transformation (like metaphone etc)
     * @param type $parts
     * @param type $transformationFunction
     */
    private function getSimilarityScore($parts, $transformationFunction){
        $transformedLength = 0;
        $distance = 0;
        foreach($parts as $partIndex => $part){
            $partText = str_replace('-', '', $part['parsed'] . $part['notParsed']);
            $originalPartText = $this->domainOriginalParts[$partIndex]['notParsed'];
            $parsedTransformedText = $transformationFunction($partText);
            $originalTransformedText = $transformationFunction($originalPartText);
            $distance += levenshtein($parsedTransformedText, $originalTransformedText);
            $transformedLength += strlen($originalTransformedText);
        }
        
        return (($transformedLength - $distance) / $transformedLength);
    }
    
    private function parsingOfStateItemStarted($stateItemParts){
        foreach($stateItemParts as $part){
            if (!empty($part['parsed'])){
                return true;
            }
        }
        
        return false;
    }
    
    private function getUpdatedStateItemPart($stateItemPart){
        $textToUpdate = $stateItemPart['notParsed'];
        $bannedMethods = $stateItemPart['bannedMethods'];
           
        switch($stateItemPart['method']){
            case 'getReadingFromPartFromWords':
                $parsedFragments = $this->getReadingFromPartFromWords($textToUpdate);
                break;
            case 'getReadingFromPartFromWordsMatchingHeuristics':
                $parsedFragments = $this->getReadingFromPartFromWordsMatchingHeuristics($textToUpdate, $bannedMethods);
                break;
            case 'getReadingFromPartFromWordsLongestWordsHeuristic':
                $parsedFragments = $this->getReadingFromPartFromWordsLongestWordsHeuristic($textToUpdate);
                break;
            case 'getReadingFromPartFromWordsCommonLengthWordsHeuristic':
                $parsedFragments = $this->getReadingFromPartFromWordsCommonLengthWordsHeuristic($textToUpdate);
                break;
            case 'getReadingFromPartFromWordsLongestWordsMetaphoneHeuristic':
                $parsedFragments = $this->getReadingFromPartFromWordsLongestWordsMetaphoneHeuristic($textToUpdate);
                break;
            case 'getReadingFromPartFromWordsCommonLengthWordsMetaphoneHeuristic':
                $parsedFragments = $this->getReadingFromPartFromWordsCommonLengthWordsMetaphoneHeuristic($textToUpdate);
                break;
            case 'getReadingFromPartFromWordsUsageHeuristic':
                $parsedFragments = $this->getReadingFromPartFromWordsUsageHeuristic($textToUpdate);
                break;
            case 'getReadingFromPartFromWordsUsageMetaphoneHeuristic':
                $parsedFragments = $this->getReadingFromPartFromWordsUsageMetaphoneHeuristic($textToUpdate);
                break;
            case 'getReadingFromPartFromWordsPrefixPostfixHeuristic':
                $parsedFragments = $this->getReadingFromPartFromWordsPrefixPostfixHeuristic($textToUpdate);
                break;
            case 'getReadingFromPartFromWordsAllCutsHeuristic':
                $parsedFragments = $this->getReadingFromPartFromWordsAllCutsHeuristic($textToUpdate);
                break;
            default:
                $parsedFragments = array();
                break;
            // etc
        }
        
        $updateResults = array();
        foreach($parsedFragments as $parsedFragment){
            $newStateItemPart = $stateItemPart;
            
            if (!empty($parsedFragment['parsed'])){
                
                if (empty($newStateItemPart['parsed'])){
                    $newStateItemPart['parsed'] = $parsedFragment['parsed'];
                } else {
                    $newStateItemPart['parsed'] .= '-' . $parsedFragment['parsed'];
                }
                
                if (!empty($parsedFragment['subNotParsed'])){
                    $newStateItemPart['notParsed'] = ltrim($newStateItemPart['notParsed'], $parsedFragment['subNotParsed']);
                } else {
                    $newStateItemPart['notParsed'] = ltrim($newStateItemPart['notParsed'], $parsedFragment['parsed']);
                }
                
            }
            
            if (!empty($parsedFragment['newMethod'])){
                $newStateItemPart['method'] = $parsedFragment['newMethod'];
            }
            
            if (!empty($parsedFragment['bannedMethod'])){
                $newStateItemPart['bannedMethods'][] = $parsedFragment['bannedMethod'];
            }
            
            $updateResults[] = $newStateItemPart;
        }
        
        return $updateResults;
    }
    
    private function getReadingFromPartFromWords($string) {
        
        if (in_array($string, $this->passedUrlParts)){
            return array(
                array(
                    'parsed' => $string
                )
            );
        }
        
        return array(
            array(
                'parsed' => '',
                'newMethod' => 'getReadingFromPartFromWordsMatchingHeuristics'
            ),
            array(
                'parsed' => '',
                'newMethod' => 'getReadingFromPartFromWordsPrefixPostfixHeuristic'
            ),
            array(
                'parsed' => '',
                'newMethod' => 'getReadingFromPartFromWordsAllCutsHeuristic'
            )
        );
    }

    private function getReadingFromPartFromWordsMatchingHeuristics($string, $bannedMethods) {
        $results = array();

        $availableMethods = array(
            'getReadingFromPartFromWordsUsageHeuristic',
            'getReadingFromPartFromWordsLongestWordsHeuristic',
            'getReadingFromPartFromWordsCommonLengthWordsHeuristic',
            'getReadingFromPartFromWordsLongestWordsMetaphoneHeuristic',
            'getReadingFromPartFromWordsCommonLengthWordsMetaphoneHeuristic',
            'getReadingFromPartFromWordsUsageMetaphoneHeuristic'
        );
        
        foreach($availableMethods as $availableMethod){
            if (!in_array($availableMethod, $bannedMethods)){
                $results[] = array(
                    'parsed' => '',
                    'newMethod' => $availableMethod
                );
            }
        }
        
        return $results;
    }
    
    private function isValidWord($word) {
        return $this->wordM->isValidDefaultWord($word);
    }

    private function getWordsByText($word){
        if ($this->isValidWord($word)){
            return array($word);
        } else {
            return array();
        }
    }
    
    private function getWordUsage($word) {
        return $this->wordM->getUsage($word);
    }
    
    private function getWordsByMetaphone($word){
        if (false !== $wordsList = $this->wordM->getSimilarDefaultByMetaphone($word)){

            $results = array();
            foreach($wordsList as $word){
                $results[] = $word['word'];
            }
            return $results;
        } else {
            return false;
        }
    }

    /**
     * Try to match the longest possible words
     * @param type $string
     * @return string
     */
    private function getReadingFromPartFromWordsLongestWordsHeuristic($string) {
        return $this->getReadingFromPartFromWordsCustomLengthHeuristic($string, range($this->maxWordLength, 1), 'getReadingFromPartFromWordsLongestWordsHeuristic');
    }

    /**
     * Try to match words starting with most common lengths
     * @param type $string
     * @return string
     */
    private function getReadingFromPartFromWordsCommonLengthWordsHeuristic($string) {
        return $this->getReadingFromPartFromWordsCustomLengthHeuristic($string, $this->wordLengthsOrderedByUsage, 'getReadingFromPartFromWordsCommonLengthWordsHeuristic');
    }
    

    private function getReadingFromPartFromWordsCustomLengthHeuristic($string, $lengths, $methodName) {
        return $this->getReadingFromPartFromWordsCustomLengthHeuristicCustomWordsFetcher(
                $string, 
                $lengths, 
                function($word) {return $this->getWordsByText($word);},
                $methodName
        );
    }

    private function getReadingFromPartFromWordsCustomLengthHeuristicMetaphone($string, $lengths, $methodName) {
        return $this->getReadingFromPartFromWordsCustomLengthHeuristicCustomWordsFetcher(
                $string,
                $lengths,
                function($word) {return $this->getWordsByMetaphone($word);},
                $methodName
        );       
    }
    
    private function getReadingFromPartFromWordsUsageHeuristic($string) {
        return $this->getReadingFromPartFromWordsUsageCustomWordsFetcher(
                $string, 
                function($word) { return $this->getWordsByText($word); },
                'getReadingFromPartFromWordsUsageHeuristic'
        );
    }

    private function getReadingFromPartFromWordsPrefixPostfixHeuristic($string) {
        return array();
    }

    private function getReadingFromPartFromWordsAllCutsHeuristic($string) {
        return array();
    }

    private function getReadingFromPartFromWordsLongestWordsMetaphoneHeuristic($string) {
        return $this->getReadingFromPartFromWordsCustomLengthHeuristicMetaphone(
                $string, 
                range($this->maxWordLength, 1), 
                'getReadingFromPartFromWordsLongestWordsMetaphoneHeuristic'
        );
    }

    private function getReadingFromPartFromWordsCommonLengthWordsMetaphoneHeuristic($string) {
        return $this->getReadingFromPartFromWordsCustomLengthHeuristicMetaphone(
                $string, 
                $this->wordLengthsOrderedByUsage, 
                'getReadingFromPartFromWordsCommonLengthWordsMetaphoneHeuristic'
        );
    }

    private function getReadingFromPartFromWordsUsageMetaphoneHeuristic($string) {
        return $this->getReadingFromPartFromWordsUsageCustomWordsFetcher(
                $string, 
                function($word){ return $this->getWordsByMetaphone($word); },
                'getReadingFromPartFromWordsUsageMetaphoneHeuristic'
        );
    }

    private function getReadingFromPartFromWordsUsageCustomWordsFetcher($string, $fetcher, $methodName){
        // starting by 2, because individual letters have too high usage
        $usages = array();
        $originalWords = array();
        for ($i = 2; $i <= $this->maxWordLength; ++$i) {
            $word = substr($string, 0, $i);
            if (strlen($word) < 2) {
                continue;
            }
            if (false !== $possibleWords = $fetcher($word)){
                foreach($possibleWords as $possibleWord){
                    $usages[$possibleWord] = $this->getWordUsage($possibleWord);
                    $originalWords[$possibleWord] = $word;
                }
            }
        }
        if (!empty($usages)) {
            arsort($usages);
            $keys = array_keys($usages);
            $words = array($keys[0]);
            if ($usages[$words[0]] > 125) {
                for ($i = 1; $i < count($keys) &&  $usages[$keys[$i]] >= $usages[$keys[0]] - 30; ++$i) {
                    $words[] = $keys[$i];
                }
            }
            $results = array();
            foreach ($words as $similarWord) {
                $word = $originalWords[$similarWord];
                $results[] = array(
                    'parsed' => $similarWord,
                    'subNotParsed' => $word
                );
            }
            return $results;
        } else {
            // nothing found in usage functions
            if (strlen($string) == 1) {
                return array(
                    array(
                        'parsed' => $string
                    )
                ); // it was one letter, we are only passing that in this method
            } else {
                return array(
                    array(
                        'newMethod' => 'getReadingFromPartFromWordsMatchingHeuristics',
                        'bannedMethod' => $methodName
                    )
                );
            }
        }
    }
    
    private function getReadingFromPartFromWordsCustomLengthHeuristicCustomWordsFetcher($string, $lengths, $fetcher, $methodName){
        foreach ($lengths as $i) {
            $substring = substr($string, 0, $i);
            if (strlen($substring) != $i) {
                continue; // there arent $i characters in the $string
            }
            if (false !== $possibleWords = $fetcher($substring)){
                $results = array();
                foreach($possibleWords as $possibleWord){
                    $results[] = array(
                        'parsed' => $possibleWord,
                        'subNotParsed' => $substring
                    );
                }
                if (!empty($results)){
                    return $results;
                }
            }
        }
        
        return array(
            array(
                'newMethod' => 'getReadingFromPartFromWordsMatchingHeuristics',
                'bannedMethod' => $methodName
            )
        ); // nothing found
    }
    
}