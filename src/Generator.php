<?php

namespace Timple;

use Symfony\Component\CssSelector\CssSelectorConverter;

class Generator {

    protected $document;
    protected $scopeName = 'document';
    protected $startToken = '[[php ';
    protected $endToken = ' php]]';
    protected $startOutputToken = '[[output ';
    protected $childToken = '[[-]]';

    /* MAIN METHODS
    **************************************/

    public function __construct() {
        $this->document = new \DOMDocument('1.0', 'utf-8');
    }

    public function load($htmlFile) {
        if (! file_exists($htmlFile)) {
            throw new \RuntimeException('HTML file not found');
        }
        $html = file_get_contents($htmlFile);
        $this->document->loadHTML($html);

        return $this;
    }

    public function generate($templatePath) {
        $search = [$this->startToken, $this->endToken, $this->startOutputToken, $this->childToken];
        $replace = [PHP_EOL . '<?php ', ' ?>' . PHP_EOL, PHP_EOL . '<?= ', '->'];
        $template = $this->document->saveHTML($this->document);
        $template = str_replace($search, $replace, $template);
        file_put_contents($templatePath, $template);

        return $this;
    }

    /* TEMPLATE IMPLEMENTATION METHODS
    **************************************/

    public function addOutput($selector, $attribute, $escape = true) {
        $converter = new CssSelectorConverter();
        $xPath = $converter->toXPath($selector);
        $parser = new \DOMXPath($this->document);
        $entryList = $parser->query($xPath);
        foreach ($entryList as $entry) {
            $output = '$' . $this->scopeName . $this->childToken . $attribute;
            if ($escape) {
                $output = '$escape' . $this->childToken . 'html(' . $output . ')';
            }
            $entry->nodeValue = $this->startOutputToken . $output . $this->endToken;
        }

        return $this;
    }

    public function addConditionAroundNode($selector, $attribute) {
        $converter = new CssSelectorConverter();
        $xPath = $converter->toXPath($selector);
        $parser = new \DOMXPath($this->document);
        $entryList = $parser->query($xPath);
        foreach ($entryList as $entry) {
            $condition = '$' . $this->scopeName . $this->childToken . $attribute;
            $condition = 'if (' . $condition . '):';
            $startStatement = $this->startToken . $condition . $this->endToken;
            $this->insertBefore($this->document->createTextNode($startStatement), $entry);
            $endStatement = $this->startToken . 'endif;' . $this->endToken;
            $this->insertAfter($this->document->createTextNode($endStatement), $entry);
        }

        return $this;
    }

    /* DOM MANIPULATION HELPER
    **************************************/

    protected function insertBefore($newNode, $refNode) {
        $refNode->parentNode->insertBefore($newNode, $refNode);
    }

    protected function insertAfter($newNode, $refNode) {
        if ($refNode->nextSibling) {
            $this->insertBefore($newNode, $refNode->nextSibling);
        } else {
            $refNode->parentNode->appendChild($newNode);
        }
    }
}