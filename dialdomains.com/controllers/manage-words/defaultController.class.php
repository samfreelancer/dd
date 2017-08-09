<?php
class defaultController extends adfBaseController {
    public function indexAction() {
        $word = new word();
        $wordCount = $word->getCount();
        
        include $this->tpl->page('manage-words/index.php');
    }
    
    public function importAction() {
        $word = new word();
        
        if (lib_is_post()) {
            ini_set('memory_limit', '1024M');
            
            try {
                if ($_FILES['wordList']['error'] != 0) {
                    echo lib_debug($_FILES);
                    throw new Exception("The file upload failed.  Please try again.");
                }

                if (false == $data = file_get_contents($_FILES['wordList']['tmp_name'])) {
                    throw new Exception("Failed to read file into memory.");
                }
                
                $data       = explode("\n", $data);
                $counter    = 0;

                foreach ($data as $line) {
                    $line = trim($line);

                    if (empty($line)) {
                        continue;
                    }
                    
                    if (!$word->exists($line)) {
                        $word->add(array('word' => $line));
                        $counter++;
                    }
                }

                $this->status->message(number_format($counter) . " words have been added to the database");
                $this->renderNext();
            } catch (Exception $e) {
                $this->status->error($e->getMessage());
            }
        }
        
        include $this->tpl->page('manage-words/import.php');
    }
    
    public function loadUsagesAction(){
        $usagesFile = file_get_contents(SITE_BASE_PATH . '/assets/private/subtlex_us.txt');
        $lines = explode("\n",$usagesFile);
        $lines = array_slice($lines, 1);
        $wordM = new word();
        $linesCount = count($lines);
        for($i = 0; $i < $linesCount; ++$i){
            $line = $lines[$i];
            if (!empty($line)){
                $rows = explode("\t",$line);
                $word = $rows[0];
                $usage = 255 - round(255 * $i / $linesCount); // to have groups with the same number of words
                $wordM->updateUsage($word, $usage);
            }
        }
        exit;
    }
}