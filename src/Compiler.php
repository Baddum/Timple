<?php

namespace Timple;

use Aura\Html\HelperLocatorFactory;

class Compiler
{
    protected $templateFile;

    public function __construct()
    {
    }

    public function load($templateFile)
    {
        $this->templateFile = $templateFile;

        return $this;
    }

    public function compile($dataFile, $pageFile)
    {
        $document = $this->getDocument($dataFile);
        $factory = new HelperLocatorFactory();
        $escape = $factory->newInstance()->escape();

        ob_start();
        require "$this->templateFile";
        $html = ob_get_clean();
        file_put_contents($pageFile, $html);

        return $this;
    }

    protected function getDocument($dataFile)
    {
        $data = json_decode(file_get_contents($dataFile));
        $className = '\Timple\Data';

        return unserialize(sprintf(
            'O:%d:"%s"%s',
            strlen($className),
            $className,
            strstr(strstr(serialize($data), '"'), ':')
        ));
    }
}
