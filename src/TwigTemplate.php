<?php

namespace Timple;

class TwigTemplate extends Template
{

    protected function escapeHTML($text, $escape = true)
    {
        if (!$escape) {
            return $text . '|raw';
        }
        return $text;
    }

    protected function outputStatement($text)
    {
        return '{{ ' . $text . ' }}';
    }

    protected function startConditionStatement($condition)
    {
        return '{% if ' . $condition . ' %}';
    }

    protected function endConditionStatement()
    {
        return '{% endif %}';
    }

    protected function startLoopStatement($loop)
    {
        return '{% for ' . $loop . ' %}';
    }

    protected function endLoopStatement()
    {
        return '{% endfor %}';
    }
}