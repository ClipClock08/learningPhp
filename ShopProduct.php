<?php

interface Chargeable
{
    public function getPrice(): float;
}

interface IdentityObject
{
    public function generateId(): string;
}

trait PriceUtilities
{

    public function calculateTax(float $price): float
    {
        return ($this->getTaxRate() / 100) * $price;
    }

    abstract function getTaxRate(): float;
}

trait TaxTools
{

    public function calculateTax(float $price): float
    {
        return 222;
    }
}

trait IdentityTrait
{
    public function generateId(): string
    {
        return uniqid();
    }
}

abstract class Service
{

}

class UtilityService extends Service
{
    use PriceUtilities {
        PriceUtilities::calculateTax as protected;
    }

    private $price;

    public function __construct(float $price)
    {
        $this->price = $price;
    }

    public function getTaxRate(): float
    {
        return 17;
    }

    public function getFinalPrice(): float
    {
        return ($this->price + $this->calculateTax($this->price));
    }
}

class ShopProduct implements Chargeable, IdentityObject
{
    use PriceUtilities, IdentityTrait;

    const AVAILABLE = 0;
    const OUT_OF_STOCK = 1;
    private $id = 0;
    private $_title;
    private $producerMainName;
    private $producerFirstName;
    protected $price = 0;
    private $discount;

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

    public function getTaxRate(): float
    {
        return 20;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getProducerFirstName(): string
    {
        return $this->producerFirstName;
    }

    /**
     * @return string
     */
    public function getProducerMainName(): string
    {
        return $this->producerMainName;
    }

    public function setTitle($title)
    {
        $this->_title = $title;
    }

    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * @return mixed
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * @param float|int $price
     */
    public function setPrice($price): void
    {
        $this->price = $price;
    }

    public function getSummaryLine()
    {
        $base = "{$this->_title} ( {$this->producerMainName},";
        $base .= "{$this->producerFirstName} )";
        $base .= " - {$this->getPrice()} $)";
        return $base;
    }

    public function getProducer()
    {
        return $this->producerFirstName . " " .
            $this->producerMainName;
    }

    public function setDiscount($percent)
    {
        $this->discount = $percent;
    }

    public function getPrice(): float
    {
        return $this->price - $this->price * $this->discount / 100;
    }

    /**
     * @param int $id
     * @param PDO $pdo
     * @return ShopProduct
     */
    public static function getInstance(int $id, \PDO $pdo): ShopProduct
    {
        $stmt = $pdo->prepare('select * from products where id=?');
        $result = $stmt->execute([$id]);
        $row = $stmt->fetch();

        if (empty($row)) return null;

        if ($row ['type'] == 'book') {
            $product = new BookProduct(
                $row['title'],
                $row['firstname'],
                $row['mainname'],
                (float)$row['price'],
                (int)$row['numpages']
            );
        } elseif ($row['type'] == "cd") {
            $product = new CdProduct(
                $row['title'],
                $row['firstname'],
                $row['mainname'],
                (float)$row['price'],
                (int)$row['playlength']
            );
        } else {

            $firstname = (is_null($row['firstname'])) ? "" : $row ['firstname'];
            $product = new ShopProduct(
                $row['title'],
                $firstname,
                $row['mainname'],
                (float)$row['price']
            );
        }
        $product->setId((int)$row['id']);
        $product->setDiscount((int)$row['discount']);
        return $product;

    }
}

class CdProduct extends ShopProduct
{
    private $playLength;

    public function __construct(string $title, string $firstName, string $mainName, float $price, int $playLength)
    {
        parent::__construct($title, $firstName, $mainName, $price);
        $this->playLength = $playLength;
    }

    /**
     * @param int $playLength
     */
    public function setPlayLength(int $playLength): void
    {
        $this->playLength = $playLength;
    }

    public function getPlayLength()
    {
        return $this->playLength;
    }

    public function getSummaryLine()
    {
        $base = parent::getSummaryLine();
        $base .= ": Время прослушивания - {$this->playLength} минут";
        return $base;
    }
}

class BookProduct extends ShopProduct
{

    private $numPages;

    public function __construct(string $title, string $firstName, string $mainName, float $price, int $numPages)
    {
        parent::__construct($title, $firstName, $mainName, $price);
        $this->numPages = $numPages;
    }

    public function getNumPages()
    {
        return $this->numPages;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    public function getSummaryLine()
    {
        $base = parent::getSummaryLine();
        $base .= ": Количество страниц - {$this->numPages}";
        return $base;
    }
}

abstract class ShopProductWriter
{
    protected $products = [];

    public function addProduct(ShopProduct $shopProduct)
    {
        $this->products[] = $shopProduct;
    }

    abstract public function write();
}

class TextProductWriter extends ShopProductWriter
{
    public function write()
    {
        $str = "Товары:" . PHP_EOL;
        foreach ($this->products as $product) {
            $str .= $product->getSummaryLine() . PHP_EOL;
        }
        print $str;
    }
}

class XmlProductWriter extends ShopProductWriter
{
    public function write()
    {
        $writer = new XMLWriter();
        $writer->openMemory();
        $writer->startDocument('1.0', 'UTF-8');
        $writer->startElement('products');
        foreach ($this->products as $product) {
            $writer->startElement('product');
            $writer->writeAttribute("title", $product->getTitle());
            $writer->startElement('summary');
            $writer->text($product->getSummaryLine());
            $writer->endElement();
            $writer->endElement();
        }
        $writer->endElement();
        $writer->endDocument();
        print $writer->flush();
    }
}


/*$wrong = new Error();
$writer = new XmlProductWriter();
$product1 = new BookProduct('Собачье сердце', 'Михаил', 'Булгаков', 13.23, 132);
$product2 = new CdProduct('Классическая музыка. Лучшее', 'Антонио', 'Вивальди', 15.99, 66);

$writer->addProduct($product1);
$writer->addProduct($product2);
$writer->write();


$pdo = new \PDO('mysql:host=localhost;dbname=test', 'q', 'q');
$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
$obj = ShopProduct::getInstance(1, $pdo);

echo $obj->getSummaryLine();*/

$u = new UtilityService(200);
print $u->getFinalPrice();
