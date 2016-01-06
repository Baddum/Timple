<?php

namespace Timple;

abstract class Template
{
    use DOMUtils;

    /* MAIN METHODS
    **************************************/

    public function fromFile($htmlFile)
    {
        if (!file_exists($htmlFile)) {
            throw new \RuntimeException('HTML file not found');
        }
        $html = file_get_contents($htmlFile);
        $document = new \DOMDocument('1.0', 'utf-8');
        $document->loadHTML($html);

        return $this->fromNode($document->documentElement);
    }

    public function fromNode($node)
    {
        return $this->fromNodeList([$node]);
    }

    public function fromNodeList($nodeList)
    {
        $this->setNodeList($nodeList);

        return $this;
    }

    public function generateFile($templatePath)
    {
        $template = $this->getDocument()->saveHTML($this->getDocument());
        $template = $this->unescapeTemplate($template);
        file_put_contents($templatePath, $template);

        return $this;
    }

    /* TEMPLATE IMPLEMENTATION METHODS
    **************************************/

    public function content($content, $escape = true)
    {
        $content = $this->escapeHTML($content, $escape);
        $statement = $this->outputStatement($content);
        $template = $this->createTemplateNode($statement);
        $this->replaceContent($template);

        return $this;
    }

    public function condition($condition)
    {
        $startTemplate = $this->createTemplateNode($this->startConditionStatement($condition));
        $endTemplate = $this->createTemplateNode($this->endConditionStatement());
        $this->insertWrap($startTemplate, $endTemplate);

        return $this;
    }

    public function conditionalContent($content, $escape = true)
    {
        $this->condition($content);
        $this->content($content, $escape);
    }

    /* TEMPLATE SPECIFIC IMPLEMENTATION METHODS
    **************************************/

    protected function escapeHTML($text, $escape = true)
    {
        throw new \Exception('Method must be overridden');
    }

    protected function outputStatement($text)
    {
        throw new \Exception('Method must be overridden');
    }

    protected function startConditionStatement($condition)
    {
        throw new \Exception('Method must be overridden');
    }

    protected function endConditionStatement()
    {
        throw new \Exception('Method must be overridden');
    }
}