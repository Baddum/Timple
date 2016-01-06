<?php

namespace Timple;

class PHPTemplate extends Template
{

    protected function escapeHTML($text, $escape = true)
    {
        if ($escape) {
            return '$this->escape()->html(' . $text . ')';
        }
        return $text;
    }

    protected function outputStatement($text)
    {
        return '<?= ' . $text . ' ?>';
    }

    protected function startConditionStatement($condition)
    {
        return '<?php if(' . $condition . '): ?>';
    }

    protected function endConditionStatement()
    {
        return '<?php endif; ?>';
    }
}