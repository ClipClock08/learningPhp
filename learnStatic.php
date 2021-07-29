<?php
//include 'ShopProduct.php';

abstract class DomainObject
{
    private $group;

    public function __construct()
    {
        $this->group = static::getGroup();
    }

    public static function create(): DomainObject
    {
        return new static();
    }

    /**
     * @return string
     */
    public static function getGroup()
    {
        return "default";
    }
}

class User extends DomainObject
{
}

class Document extends DomainObject
{
    public static function getGroup()
    {
        return "document";
    }
}

class SpearSheets extends Document
{

}

print_r(Document::create());
print_r(User::create());
print_r(SpearSheets::create());
