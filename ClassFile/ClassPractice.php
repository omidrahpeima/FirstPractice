<?php
namespace ClassPractice;

trait TraitEven {

  function Wow(){
    echo "<br><b>Wow!!!</b>";
  }
}

class EvenNumber
{
  use TraitEven;

  protected $number;
  private $result;

  public function GetNumber($n)
  {
    $this->number = $n;
  }

  public function DetermineEven()
  {
    $this->CalculateEven($this->number);

    if ($this->result == "even")
    {
      echo "<br>" . $this->number . " Is an even!";

      $this->Wow();

    } else {
      echo "<br>" . $this->number . " Is not an even?";
    }

    /*$this->obbjectdetermine = new EvenNumber();
    $this->obbjectdetermine->CalculateEven($this->number);

    // Notice:
    // $this->number is value of the first called class
    // But $this->obbjectdetermine->result is value of the new called class
    // $this->obbjectdetermine->number and $this->result, both of them are empty right now !!!

    if ($this->obbjectdetermine->result == "even")
    {
      echo $this->number . " Is an even!";

      $this->MakeWow = new EvenNumber();
      $this->MakeWow->Wow();

    } else {
      echo $this->number . " Is not an even?";
    }*/

  }

  private function CalculateEven($n)
  {
    if (($n % 2) == 0)
    {
      $this->result = "even";
      } else {
      $this->result = "odd";
    }

  }

}
?>
