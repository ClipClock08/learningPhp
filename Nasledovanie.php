<?php


class CdProduct
{
    public $playLength;
    private $_title;
    public $producerMainName;
    public $producerFirstName;
    public $price = 0;

    public function __construct(
        string $title,
        string $firstName,
        string $mainName,
        float $price,
        int $playLength = 0
    )
    {
        $this->_title = $title;
        $this->producerFirstName = $firstName;
        $this->producerMainName = $mainName;
        $this->price = $price;
        $this->playLength = $playLength;
    }

    public function setTitle($title)
    {
        $this->_title = $title;
    }

    public function getTitle()
    {
        return $this->_title;
    }

    public function getPlayLength()
    {
        return $this->playLength;
    }

    public function getSummaryLine()
    {
        $base = "{$this->_title} ( {$this->producerMainName},";
        $base .= "{$this->producerFirstName} )";
        $base .= ": Время звучания - {$this->playLength}";
        return $base;
    }

    public function getProducer()
    {
        return $this->producerFirstName . " " .
            $this->producerMainName;
    }
}

class BookProduct
{
    public $numPages;
    private $_title;
    public $producerMainName;
    public $producerFirstName;
    public $price = 0;

    public function __construct(
        string $title,
        string $firstName,
        string $mainName,
        float $price,
        int $numPages = 0
    )
    {
        $this->_title = $title;
        $this->producerFirstName = $firstName;
        $this->producerMainName = $mainName;
        $this->price = $price;
        $this->numPages = $numPages;
    }

    public function setTitle($title)
    {
        $this->_title = $title;
    }

    public function getTitle()
    {
        return $this->_title;
    }

    public function getSummaryLine()
    {
        $base = "{$this->_title} ( {$this->producerMainName},";
        $base .= "{$this->producerFirstName} )";
        $base .= ": Количество страниц - {$this->numPages}";
        return $base;
    }

    public function getProducer()
    {
        return $this->producerFirstName . " " .
            $this->producerMainName;
    }
}

class ShopProductWriter
{
    public function write($shopProduct)
    {

        print_r($shopProduct->getSummaryLine().PHP_EOL);
    }
}

class Wrong
{

}

$wrong = new Wrong();
$writer = new ShopProductWriter();
$product1 = new BookProduct('Собачье сердце', 'Михаил', 'Булгаков', 13.23, 300);
$product2 = new CdProduct('Классическая музыка. Лучшее', 'Антонио', 'Вивальди', 15.99, 65);
$writer->write($product1);
$writer->write($product2);
