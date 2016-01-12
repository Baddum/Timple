<?php

namespace Timple;

use Symfony\Component\CssSelector\CssSelectorConverter;

trait DOMUtils
{
    protected $nodeList = [];
    protected static $ltToken = '[[$%$';
    protected static $gtToken = '$%$]]';

    protected function setNodeList($nodeList)
    {
        $this->nodeList = $nodeList;
    }

    protected function dump()
    {
        $html = '';
        foreach ($this->nodeList as $node) {
            $html .= $this->unescapeTemplate($this->getDocument()->saveHTML($node));
        }

        return $html;
    }


    /* DOM QUERY METHODS
    **************************************/

    protected function getDocument()
    {
        if (!$this->nodeList) {
            return new \DOMDocument();
        }

        return $this->nodeList[0]->ownerDocument;
    }

    public function query($selector)
    {
        $selector = trim($selector);
        $converter = new CssSelectorConverter();
        $prefix = '//';
        if ($selector[0] == '>') {
            $selector = trim(substr($selector, 1));
            $prefix = '';
        }
        $xPath = $converter->toXPath($selector, $prefix);
        $parser = new \DOMXPath($this->getDocument());
        $nodeList = [];
        foreach ($this->nodeList as $node) {
            $nodeList = array_merge($nodeList, iterator_to_array($parser->query($xPath, $node)));
        }
        $className = get_class($this);

        return (new $className)->fromNodeList($nodeList);
    }

    /* DOM INSERTION METHODS
    **************************************/

    protected function replaceContent($newNode)
    {
        foreach ($this->nodeList as $node) {
            $node->nodeValue = '';
            $node->appendChild($newNode->cloneNode());
        }
    }

    protected function insertBefore($newNode)
    {
        foreach ($this->nodeList as $node) {
            $node->parentNode->insertBefore($newNode->cloneNode(), $node);
        }
    }

    protected function insertAfter($newNode)
    {
        foreach ($this->nodeList as $node) {
            if ($node->nextSibling) {
                $node->parentNode->insertBefore($newNode->cloneNode(), $node->nextSibling);
            } else {
                $node->parentNode->appendChild($newNode->cloneNode());
            }
        }
    }

    protected function appendChild($newNode, $asFirst = false)
    {
        foreach ($this->nodeList as $node) {
            if ($asFirst && $node->firstChild) {
                $node->insertBefore($newNode->cloneNode(), $node->firstChild);
            } else {
                $node->appendChild($newNode->cloneNode());
            }
        }
    }

    protected function insertOuterWrap($beforeNode, $afterNode)
    {
        $this->insertBefore($beforeNode);
        $this->insertAfter($afterNode);
    }

    protected function insertInnerWrap($beforeNode, $afterNode)
    {
        $this->appendChild($beforeNode, true);
        $this->appendChild($afterNode);
    }


    /* PHP NODE METHODS
    **************************************/

    protected function createTemplateNode($text)
    {
        $text = $this->escapeTemplate($text);
        return $this->getDocument()->createTextNode($text);
    }

    protected function escapeTemplate($text)
    {
        $search = ['<', '>'];
        $replace = [self::$ltToken, self::$gtToken];
        return str_replace($search, $replace, $text);
    }

    protected function unescapeTemplate($text)
    {
        $search = [self::$ltToken, self::$gtToken];
        $replace = ['<', '>'];
        return str_replace($search, $replace, $text);
    }
}