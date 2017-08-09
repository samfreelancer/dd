<?php
chdir(dirname(__FILE__));
include '../config.php';

$voiceDomainsGrammarRulesFile = SITE_BASE_PATH . '/assets/private/domains_grammar.xml';

try {
    $domainM = new domain();
    
    $domainsWithReadings = $domainM->getDomainsWithReadingsForGrammar();
    echo "<pre>";
    print_r($domainsWithReadings);
    exit();
    $theme = adfTheme::getInstance();
    $theme->setTemplate('xml');
    $adfBuffer = new adfBuffer();
    $adfBuffer->start();
    include $theme->page('domains_grammar.php');
    $domainsGrammarFileContent = $adfBuffer->stop();
    
    file_put_contents($voiceDomainsGrammarRulesFile, $domainsGrammarFileContent);
    
} catch (Exception $ex) {
    echo $ex->getMessage();
}