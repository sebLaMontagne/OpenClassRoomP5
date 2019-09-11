<?php
class prout
{
    public $number;
    public $sound;

    public function __construct($a,$b)
    {
        $this->number = $a;
        $this->sound = $b;
    }
}

$a = new prout(2, 'snif');
var_dump($a);

?>

<script type="text/javascript">
    var x = <?php echo json_encode($a); ?>;
    console.log(x);
</script>