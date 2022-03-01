<?php
class Foglalas
{
    public $nev;
    public $lakcim;
    public $kezdet;
    public $veg;
    public $fo;
    public $ara;
    public Szoba $szoba;


    public function __construct($nev, $lakcim, $kezdet, $fo, $gyerek, $ejszaka, Szoba $szoba)
    {
        $this->nev = $nev;
        $this->lakcim = $lakcim;
        $this->kezdet = date_format(date_create($kezdet), "Y-m-d");
        $this->fo = $fo;
        $this->ara=$ejszaka*(($fo-$gyerek)*15000+$gyerek*10000);
        $this->veg = date_format(date_modify(date_create($kezdet), "+$ejszaka days"), "Y-m-d");
        $this->szoba=$szoba;

    }
    public function __toString(): string
    {
        return "Foglaló neve: $this->nev%Foglaló lakcíme: $this->lakcim%Foglalás kezdete: $this->kezdet%Foglás vége: $this->veg%Személyek száma: $this->fo%Foglalás ára: $this->ara%Foglalt szoba száma: $this->szoba";
    }

}
class Szoba
{
 public $szobaszam;
 public $ferohelyek;

    /**
     * @param $szobaszam
     * @param $ferohelyek
     */
    public function __construct($szobaszam, $ferohelyek)
    {
        $this->szobaszam = $szobaszam;
        $this->ferohelyek = $ferohelyek;
    }
    public function __toString(): string
    {
        return "$this->szobaszam";
    }


}
if (!file_exists("szobak.txt") and !file_exists("foglalasok.txt"))
{
    touch("foglalasok.txt");
    $szobak=array(new Szoba(420, 2), new Szoba(299, 4), new Szoba(310, 4), new Szoba(111, 2), new Szoba(99, 1), new Szoba(911, 8));
    if ($fajl=fopen("szobak.txt", "x"));
    {
        chmod("szobak.txt",0777);
        chmod("foglalasok.txt",0777);
        fwrite($fajl, serialize($szobak));
        fclose($fajl);
    }


}
$foglalas=new Foglalas("","","2000-01-01",0,0,0,new Szoba(0,0));
?>
<html>
<head>
    <title>Hotel California</title>
    <script>
        function sikeresfoglalas( szoveg) {
            alert("Sikeres foglalás!\n"+szoveg.replaceAll("%","\n"));
        }
        function sikertelenfoglalas(){
            alert("Sikertelen foglalás!");
        }
    </script>
</head>
<body>
<h1>Szállásfoglalás</h1>
<form action="la03feladat.php" method="post">
    <label>Foglaló neve: </label><input type="text" name="neve" minlength="4"><br>
    <label>Foglaló lakcíme: </label><input type="text" name="lakcim" minlength="4"><br>
    <label>Szállás elfoglalása: </label><input type="date" name="kezdet" value="<?php echo date('Y-m-d')?>"><br>
    <label>Éjszakák száma: </label><input type="number" name="ejszaka" value="1" min="1"><br>
    <label>Személyek száma: </label><input type="number" name="fo" value="1" min="1">
    <label>Ebből gyerek: </label><input type="number" name="gyerek" value="0" min="0"><br>
    <input type="submit" value="Lefoglalom">
</form>
</body>
</html>
<?php
if (!empty($_POST["neve"]) and !empty($_POST["lakcim"]))
{
    $fajl=fopen("szobak.txt", "r");
    $szobak=unserialize(fgets($fajl));
    fclose($fajl);
    $fajl=fopen("foglalasok.txt", "r");
    $foglalasok=unserialize(fgets($fajl));
    if ($foglalasok==false)
    {
        $foglalasok=array();
    }
    fclose($fajl);

    $foglalas=new Foglalas( $_POST["neve"],  $_POST["lakcim"],   $_POST["kezdet"],   $_POST["fo"],  $_POST["gyerek"],  $_POST["ejszaka"], new Szoba(0, 0));

    /*echo "Ezt a foglalást akarod menteni:";
    var_dump($foglalas)."<br>";*/


    function belefernek(Szoba $szoba)
    {
        if ($szoba->ferohelyek>=$_POST["fo"])
           return true;
        else
            return false;
    }
    function foglalte(Szoba $szoba)
    {
        global $foglalasok;
        if (count($foglalasok)==0)
        {
            return false;
        }
        else
        {
            global $foglalas;
            foreach ($foglalasok as $item){
                if ($item->szoba->szobaszam==$szoba->szobaszam
                    and (($item->kezdet>$foglalas->kezdet and $foglalas->veg>$item->kezdet ) //ok
                        or( $item->kezdet>$foglalas->kezdet and $foglalas->veg>$item->veg ) //ok
                        or( $item->kezdet<$foglalas->kezdet and $foglalas->veg<$item->veg ) //ok
                        or( $item->kezdet==$foglalas->kezdet and $foglalas->veg==$item->veg ) //ok
                        or( $item->veg>$foglalas->kezdet and $foglalas->veg>$item->veg ))) //ok
                {
                    return true;
                }
            }
            return false;
        }

    }
    foreach ($szobak as $item)
    {
        if ($foglalas->szoba->szobaszam==0 and belefernek($item) and !foglalte($item))
        {
            $foglalas->szoba=$item;
        }
        elseif ($foglalas->szoba->szobaszam!=0
            and $item->ferohelyek<$foglalas->szoba->ferohelyek
            and belefernek($item) and !foglalte($item))
        {
            $foglalas->szoba=$item;
        }
    }

    if ($foglalas->szoba->ferohelyek!=0)//van foglalhato
    {
        $foglalasok[]=$foglalas;
        if ($fajl=fopen("foglalasok.txt", "w"));
        {
            fwrite($fajl, serialize($foglalasok));
            fclose($fajl);
        }
       /* echo "<br><br>Sikeres foglalás!<br><br>";
        foreach ($foglalasok as $foglalitem)
        {
            echo "Kezdet: ".$foglalitem->kezdet." Vég: ".$foglalitem->veg." Szoba:".$foglalitem->szoba->szobaszam." Fő: .$foglalitem->fo."."<br>";
        }*/
        ?>
        <script>
            sikeresfoglalas("<?php echo $foglalas;?>");
        </script>
        <?php
    }
    else//nincs foglalhato
    {
        /*echo "<br>"."Nincs foglalható szoba!";*/
        ?>
            <script>
                sikertelenfoglalas();
            </script>
        <?php
    }

}
?>