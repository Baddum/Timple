<?php

namespace Timple;

use Symfony\Component\CssSelector\CssSelectorConverter;

class Generator
{

    protected $document;
    const START_TOKEN = '[[php ';
    const END_TOKEN = ' php]]';
    const START_OUTPUT_TOKEN = '[[output ';
    const CHILD_TOKEN = '[[-]]';

    /* MAIN METHODS
    **************************************/

    public function __construct()
    {
        $this->document = new \DOMDocument('1.0', 'utf-8');
    }

    public function load($htmlFile)
    {
        if (!file_exists($htmlFile)) {
            throw new \RuntimeException('HTML file not found');
        }
        $html = file_get_contents($htmlFile);
        $this->document->loadHTML($html);

        return $this;
    }

    public function generate($templatePath)
    {
        $search = [self::START_TOKEN, self::END_TOKEN, self::START_OUTPUT_TOKEN, self::CHILD_TOKEN];
        $replace = [PHP_EOL . '<?php ', ' ?>' . PHP_EOL, PHP_EOL . '<?= ', '->'];
        $template = $this->document->saveHTML($this->document);
        $template = str_replace($search, $replace, $template);
        file_put_contents($templatePath, $template);

        return $this;
    }

    public function select($selector)
    {
        $converter = new CssSelectorConverter();
        $xPath = $converter->toXPath($selector);
        $parser = new \DOMXPath($this->document);
        $nodeList = iterator_to_array($parser->query($xPath));

        return new NodeList($nodeList);
    }
}
