<?php

class Conf
{
    private $xml;
    private $file;
    private $last_match;

    public function __construct(string $file)
    {
        $this->file = $file;
        $this->xml = simplexml_load_file($file);
    }

    public function write()
    {
        file_put_contents($this->file, $this->xml->asXML());
    }

    /**
     * @param string $str
     * @return string|null
     */
    public function get(string $str)
    {
        $matches = $this->xml->xpath("/conf/item[@name=\"$str\"]");
        if (count($matches)) {
            $this->last_match = $matches[0];
            return (string)$matches[0];
        }
        return null;
    }

    public function set(string $key, string $value)
    {
        if (!is_null($this->get($key))) {
            $this->last_match[0] = $value;
            return;
        }
        $conf = $this->xml->conf;
        $this->xml->addChild('item', $value)
            ->addAttribute('name', $key);
    }
}