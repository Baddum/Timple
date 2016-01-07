<?php

namespace Timple;

use Symfony\Component\CssSelector\CssSelectorConverter;

class NodeList
{

    protected $nodeList;
    protected $scopeName = 'document';

    public function __construct($nodeList)
    {
        $this->nodeList = $nodeList;
    }

    /* TEMPLATE IMPLEMENTATION METHODS
    **************************************/

    public function content($attribute, $escape = true)
    {
        if (!$this->nodeList) {
            return $this;
        }
        foreach ($this->nodeList as $node) {
            $output = '$' . $this->scopeName . Generator::CHILD_TOKEN . $attribute;
            if ($escape) {
                $output = '$escape' . Generator::CHILD_TOKEN . 'html(' . $output . ')';
            }
            $node->nodeValue = Generator::START_OUTPUT_TOKEN . $output . Generator::END_TOKEN;
        }

        return $this;
    }

    public function condition($attribute)
    {
        if (!$this->nodeList) {
            return $this;
        }
        foreach ($this->nodeList as $node) {
            $condition = '$' . $this->scopeName . Generator::CHILD_TOKEN . $attribute;
            $condition = 'if (' . $condition . '):';
            $startStatement = Generator::START_TOKEN . $condition . Generator::END_TOKEN;
            $this->insertBefore($this->getDocument()->createTextNode($startStatement), $node);
            $endStatement = Generator::START_TOKEN . 'endif;' . Generator::END_TOKEN;
            $this->insertAfter($this->getDocument()->createTextNode($endStatement), $node);
        }

        return $this;
    }

    public function select($selector)
    {
        $converter = new CssSelectorConverter();
        $xPath = $converter->toXPath($selector);
        $parser = new \DOMXPath($this->getDocument());
        $nodeList = [];
        foreach ($this->nodeList as $node) {
            $nodeList = array_merge($nodeList, iterator_to_array($parser->query($xPath, $node)));
        }

        return new NodeList($nodeList);
    }

    /* DOM MANIPULATION HELPER
    **************************************/

    protected function getDocument()
    {
        return $this->nodeList[0]->ownerDocument;
    }

    protected function insertBefore($newNode, $refNode)
    {
        $refNode->parentNode->insertBefore($newNode, $refNode);
    }

    protected function insertAfter($newNode, $refNode)
    {
        if ($refNode->nextSibling) {
            $this->insertBefore($newNode, $refNode->nextSibling);
        } else {
            $refNode->parentNode->appendChild($newNode);
        }
    }
}
