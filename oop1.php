<?php
class Kocsi
{
    var $rendszam;
    public $szin;
    public $ar;
    public function mutatReszletek()
    {
        echo "A te autód: {$this->rendszam} {$this->szin} {$this->ar} <br>";
    }
    public function __construct($rendszam, $szin, $ar)
    {
        $this->rendszam=$rendszam;
        $this->ar=$ar;
        $this->szin=$szin;
    }
    public function __destruct()
    {
        echo"Objektum terminálva.<br>";
    }
}
$lada=new Kocsi("ASD-000", "fekete", 300000);
$lada->mutatReszletek();
$lada->rendszam="LOL-001";
$lada->szin="Piros";
$lada->ar=1000000;
//unset($lada);
$lada->mutatReszletek();

class SzuloOsztaly
{
    public $a=12;
    private $b=12;
    protected $c=10;
    public function mutat()
    {
        echo "Ez az a: {$this->a} <br>";
    }
    public function __construct($c)
    {
        echo "A ctor paramétere: $c <br>";
    }
}

class Gyerek extends SzuloOsztaly
{
    public $e;
    public function kiir($bemenet)
    {
        echo "Bemenet amit adtál: $bemenet <br>";
    }
    public function __construct()
    {

    }
}

$os=new SzuloOsztaly(15);
$os->mutat();

$utod=new Gyerek(34);
$utod->mutat();

interface Iminta1
{
    public function ember($neve, $var);
    public function no($nev, $var);
}

interface Iminta2
{
    public function kiir();
}
echo "<br>";
class Human implements Iminta1, Iminta2
{
    private $vars=array();
    public $name;

    public function ember($neve, $var)
    {
        $this->vars[$neve]=$var;
        echo $this->vars[$neve]."<br>";
        $this->name=$neve;
    }

    public function no($nev, $var)
    {
        $this->vars[$nev]=$var;
        echo $this->vars[$nev]."<br>";
        $this->name=$nev;
    }

    public function kiir()
    {
        if(isset($this->vars[$this->name]))
        {
            echo $this->vars[$this->name]." éves embert tároltunk utoljára <br>";
        }
        else
        {
            echo "Nincs adat";
        }
    }
}
$eva=new Human();
$eva->no("Éva", "24");
$eva->kiir();

interface A
{
    public function asd();
}
interface B extends A
{
    public function asd2();
}
class C implements B
{

    public function asd()
    {
        // TODO: Implement asd() method.
    }

    public function asd2()
    {
        // TODO: Implement asd2() method.
    }
}

interface Allando
{
    const SZAM="Interface Constant";
}
echo "<br>".Allando::SZAM." az interfész konstans.<br>";

class Osztaly implements Allando
{
    public function EzazErtek()
    {
        return self::SZAM;
    }
}
echo Osztaly::SZAM."<---Ez az interfészből ------------ ez az osztályból--->".Osztaly::EzazErtek()."<br>";
?>
