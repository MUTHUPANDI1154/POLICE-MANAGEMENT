<?php
// view_police_xml.php - PHP Transformer for XML - Schema - XSLT (Lab Exercise 7)
header('Content-Type: text/html; charset=UTF-8');

$xmlFile = __DIR__ . '/police.xml';
$xslFile = __DIR__ . '/police.xsl';

if (file_exists($xmlFile) && file_exists($xslFile)) {
    $xml = new DOMDocument();
    $xml->load($xmlFile);

    $xsl = new DOMDocument();
    $xsl->load($xslFile);

    $proc = new XSLTProcessor();
    $proc->importStylesheet($xsl);

    echo $proc->transformToXML($xml);
} else {
    echo "XML or XSL file not found.";
}
?>
