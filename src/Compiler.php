<?php

namespace Timple;

class Compiler {

    protected $templateFile;

    public function __construct() {
    }

    public function load($templateFile) {
        $this->templateFile = $templateFile;
    }

    public function compile($dataFile, $pageFile) {
        $document = json_decode(file_get_contents($dataFile));

        ob_start();
        require "$this->templateFile";
        $html = ob_get_clean();
        file_put_contents($pageFile, $html);
    }
}