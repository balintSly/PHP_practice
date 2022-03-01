<?php
abstract class Auto
{
    public $name;
    public function __construct($name)
    {
        $this->name=$name;
    }
    abstract function intro() :string;
}
class Audi extends Auto
{
    function intro(): string
    {
        return "Német gyártmány: $this->name";
    }

}
$audi=new Audi("Audi R8");
echo $audi->intro();


?>
