<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Ucenter;

class Xml
{
    protected $parser;

    protected $document;

    protected $stack;

    protected $data;

    protected $last_opened_tag;

    protected $isnormal;

    protected $attrs = [];

    protected $failed = false;

    public function __construct($isnormal)
    {
        $this->xml($isnormal);
    }

    protected function xml($isnormal)
    {
        $this->isnormal = $isnormal;
        $this->parser = xml_parser_create('ISO-8859-1');
        xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, false);
        xml_set_object($this->parser, $this);
        xml_set_element_handler($this->parser, 'open', 'close');
        xml_set_character_data_handler($this->parser, 'data');
    }

    protected function destruct()
    {
        xml_parser_free($this->parser);
    }

    protected function parse(&$data)
    {
        $this->document = [];
        $this->stack	= [];
        return xml_parse($this->parser, $data, true) && !$this->failed ? $this->document : '';
    }

    protected function open(&$parser, $tag, $attributes)
    {
        $this->data = '';
        $this->failed = false;
        if (!$this->isnormal) {
            if (isset($attributes['id'])) {
                $this->document  = &$this->document[$attributes['id']];
            } else {
                $this->failed = true;
            }
        } else {
            if (!isset($this->document[$tag]) || !is_string($this->document[$tag])) {
                $this->document  = &$this->document[$tag];
            } else {
                $this->failed = true;
            }
        }
        $this->stack[] = &$this->document;
        $this->last_opened_tag = $tag;
        $this->attrs = $attributes;
    }

    protected function data(&$parser, $data)
    {
        if ($this->last_opened_tag != null) {
            $this->data .= $data;
        }
    }

    protected function close(&$parser, $tag)
    {
        if ($this->last_opened_tag == $tag) {
            $this->document = $this->data;
            $this->last_opened_tag = null;
        }
        array_pop($this->stack);
        if ($this->stack) {
            $this->document = &$this->stack[count($this->stack)-1];
        }
    }

    public static function uc_unserialize(&$xml, $isnormal = false)
    {
        $xml_parser = new static($isnormal);
        $data = $xml_parser->parse($xml);
        $xml_parser->destruct();
        return $data;
    }

    public static function uc_serialize($arr, $htmlon = false, $isnormal = false, $level = 1)
    {
        $s = $level == 1 ? "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\r\n<root>\r\n" : '';
        $space = str_repeat("\t", $level);
        foreach ($arr as $k => $v) {
            if (!is_array($v)) {
                $s .= $space."<item id=\"$k\">".($htmlon ? '<![CDATA[' : '').$v.($htmlon ? ']]>' : '')."</item>\r\n";
            } else {
                $s .= $space."<item id=\"$k\">\r\n".xml_serialize($v, $htmlon, $isnormal, $level + 1).$space."</item>\r\n";
            }
        }
        $s = preg_replace("/([\x01-\x08\x0b-\x0c\x0e-\x1f])+/", ' ', $s);
        return $level == 1 ? $s.'</root>' : $s;
    }
}
