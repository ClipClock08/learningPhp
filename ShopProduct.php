<?php


class ShopProduct
{
    private $_title;
    public $producerMainName;
    public $producerFirstName;
    public $price = 0;

    public function __construct(
        string $title,
        string $firstName,
        string $mainName,
        float $price
    )
    {
        $this->_title = $title;
        $this->producerFirstName = $firstName;
        $this->producerMainName = $mainName;
        $this->price = $price;
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
        return $base;
    }

    public function getProducer()
    {
        return $this->producerFirstName . " " .
            $this->producerMainName;
    }
}

class CdProduct extends ShopProduct
{
    public $playLength;

    public function __construct(string $title, string $firstName, string $mainName, float $price, int $playLength)
    {
        parent::__construct($title, $firstName, $mainName, $price);
        $this->playLength = $playLength;
    }

    public function getPlayLength()
    {
        return $this->playLength;
    }

    public function getSummaryLine()
    {
        $base = parent::getSummaryLine();
        $base .= ": Время прослушивания - {$this->playLength}";
        return $base;
    }
}

class BookProduct extends ShopProduct
{

    public $numPages;

    public function __construct(string $title, string $firstName, string $mainName, float $price, int $numPages)
    {
        parent::__construct($title, $firstName, $mainName, $price);
        $this->numPages = $numPages;
    }

    public function getNumPages()
    {
        return $this->numPages;
    }

    public function getSummaryLine()
    {
        $base = parent::getSummaryLine();
        $base .= ": Количество страниц - {$this->numPages}";
        return $base;
    }
}

class ShopProductWriter
{
    public function write(ShopProduct $shopProduct)
    {
        print $shopProduct->getSummaryLine();
    }
}

class Wrong
{

}

$wrong = new Wrong();
$writer = new ShopProductWriter();
$product1 = new BookProduct('Собачье сердце', 'Михаил', 'Булгаков', 13.23, 132);
$product2 = new CdProduct('Классическая музыка. Лучшее', 'Антонио', 'Вивальди', 15.99, 66);

$writer->write($product1);
$writer->write($product2);
