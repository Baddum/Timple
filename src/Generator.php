<?php

namespace Timple;

use Symfony\Component\CssSelector\CssSelectorConverter;

class Generator {

    protected $document;
    protected $startToken = '[[php ';
    protected $startOutputToken = '[[output ';
    protected $endToken = ' php]]';

    public function __construct() {
        $this->document = new \DOMDocument('1.0', 'utf-8');
    }

    public function load($htmlFile) {
        if (! file_exists($htmlFile)) {
            throw new \RuntimeException('HTML file not found');
        }
        $html = file_get_contents($htmlFile);
        $this->document->loadHTML($html);
    }

    public function generate($templatePath) {
        $search = [$this->startToken, $this->startOutputToken, $this->endToken];
        $replace = ['<?php ', '<?= $document->', ' ?>'];
        $template = $this->document->saveHTML($this->document);
        $template = str_replace($search, $replace, $template);
        file_put_contents($templatePath, $template);
    }

    public function addOutput($selector, $attribute) {
        $converter = new CssSelectorConverter();
        $xPath = $converter->toXPath($selector);
        $parser = new \DOMXPath($this->document);
        $entryList = $parser->query($xPath);
        foreach ($entryList as $entry) {
            $entry->nodeValue = $this->startOutputToken . $attribute . $this->endToken;
        }
    }
}