<?php


$history = [];

system('stty cbreak -echo');
$stdin = fopen('php://stdin', 'r');

pcntl_signal(SIGINT, "signal_handler");
pcntl_signal(SIGTSTP, "signal_handler");

function signal_handler($signal) {}

$dirMap = [
  65 => 'up',
  66 => 'down',
  #68 => 'left',
  #67 => 'right',
];

echo "Welcome to the amazing PHP terminal \n";

$chars = [];
$buildCommand = true;
$direction = null;
$index = 0;
$history = [];
while ( $buildCommand ) {
    if ( !$chars ) { echo "> ";}
    $typed = ord(fgetc($stdin));

    if ( $typed === 10 ) { // enter
      $buildCommand = false;
      echo PHP_EOL;
    } else if ( $typed === 127 ) {
      echo "backspace!!!";
      #unset($chars[count($chars) - 1]);
    } else if ( isset( $dirMap[$typed] ) ) { // direction
      $direction = $dirMap[$typed];
      $buildCommand = false;
    } else { // text
      echo chr($typed);
      $chars[] = chr($typed);
      #echo $chars[count($chars) - 1];
    }

    $play = !$buildCommand;
    while ( $play ) { // execute!
      $fullCommand = implode("", $chars);
      $split = explode(" ", $fullCommand);
      $cmd = array_shift($split);
      $args = $split;

      if  ( $cmd === "char" ) {
          char();
      } else if ( $cmd === "roll") {
          roll($args[0] ?? "");
      } else if ( $cmd === "exit" ) {
          _exit("Bye!");
      } else if ( $cmd === "history" ) {
        foreach ($history as $i => $entry) {
            echo "$i. $entry \n";
        }
      } else {
          echo "Unknown command $cmd";
      }

      $history[$index] = $fullCommand;
      $index++;

      $chars = [];
      $play = false;
      $buildCommand = true;
      echo PHP_EOL;
  }
}

function _exit(string $message){
  die($message);
}

class CharClass{}

class Stats{
    protected int $accounting = 5;
    protected int $anthropology = 1;
    protected int $appraise = 5;
    protected int $archaeology = 1;
    protected int $art = 65;
    protected int $charm = 15;
    protected int $climb = 20;
    protected int $credit = 50;
    protected int $cthulu = 0;
    protected int $disguise = 5;
    protected int $dodge = 30;
    protected int $driveAuto = 20;
    protected int $elecRepair = 10;
    protected int $fastTalk = 5;
    protected int $brawl = 27;
    protected int $handgun = 20;
    protected int $rifle = 25;
    protected int $firstAid = 30;
    protected int $history = 50;
    protected int $intimidate = 15;
    protected int $jump = 20;
    protected int $languageOther = 20;
    protected int $languageNative = 78;
    protected int $law = 5;
    protected int $libraryUse = 40;
    protected int $listen = 20;
    protected int $locksmith = 1;
    protected int $mechanicalRepair = 10;
    protected int $medicine = 1;
    protected int $naturalWorld = 40;
    protected int $navigate = 10;
    protected int $occult = 5;
    protected int $operateHeavyMachinery = 1;
    protected int $persuade = 10;
    protected int $pilot = 1;
    protected int $psychology = 30;
    protected int $psychoanalysis = 1;
    protected int $ride = 5;
    protected int $science = 15;
    protected int $sleightOfHand = 10;
    protected int $spotHidden = 45;
    protected int $stealth = 20;
    protected int $survival = 10;
    protected int $swim = 20;
    protected int $throw = 20;
    protected int $track = 10;
}

class Skills{
    protected int $strength = 65;
    protected int $dexterity = 30;
    protected int $intelligence = 55;
    protected int $constitution = 40;
    protected int $appearance = 65;
    protected int $power = 50;
    protected int $size = 45;
    protected int $education = 78;
}

class Weapon{
    protected string $name;
    protected string $damage;
    protected int $range;
    protected int $attacks;
    protected int $ammo;
}

$knife = new Weapon();

class Character {
  protected string $firstName = "Frank";
  protected string $lastName = "Heidelberg";
  protected int $age;
  protected CharClass $class;
  protected string $occupation;
  protected array $weapons = [];

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return Character
     */
    public function setFirstName(string $firstName): Character
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return Character
     */
    public function setLastName(string $lastName): Character
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return int
     */
    public function getAge(): int
    {
        return $this->age;
    }

    /**
     * @param int $age
     * @return Character
     */
    public function setAge(int $age): Character
    {
        $this->age = $age;
        return $this;
    }

    /**
     * @return CharClass
     */
    public function getClass(): CharClass
    {
        return $this->class;
    }

    /**
     * @param CharClass $class
     * @return Character
     */
    public function setClass(CharClass $class): Character
    {
        $this->class = $class;
        return $this;
    }

    /**
     * @return string
     */
    public function getOccupation(): string
    {
        return $this->occupation;
    }

    /**
     * @param string $occupation
     * @return Character
     */
    public function setOccupation(string $occupation): Character
    {
        $this->occupation = $occupation;
        return $this;
    }

    /**
     * @return array
     */
    public function getWeapons(): array
    {
        return $this->weapons;
    }

    /**
     * @param array $weapons
     * @return Character
     */
    public function setWeapons(array $weapons): Character
    {
        $this->weapons = $weapons;
        return $this;
    }

}

function char(){
  $character = [
   "name" => "Frank Heidelberg",
   "player" => "Kyrro",
   "occupation" => "painter/artist",
   "age" => 41,
   "sex" => "male",
  ];

  $stats = [
   "health" => 7,
   "magic" => 10,
   "movement" => 8,
];

  echo $character['name'] . ", " . $character['age'] . ", " . $character['sex'] . PHP_EOL;
  echo "Occupation: " . $character['occupation'] . PHP_EOL;
  echo "HP:" . $stats['health'];
}


function updateStat(string $stat, int $modifier){

}


function roll(string $stat){
  $skills = [
    "str" => 65,
    "con" => 40,
    "siz" => 45,
    "dex" => 30,
    "app" => 65,
    "edu" => 78,
    "int" => 55,
    "pow" => 50,
    "sanity" => 50,
    "luck" => 32,
    "accounting" => 5,
    "anthropology" => 1,
    "appraise" => 5,
    "archaeology" => 1,
    "art/craft" => 65,
    "charm" => 15,
    "climb" => 20,
    "credit" => 50,
    "cthulu" => 0,
    "dodge" => 30,
    "drive" => 20,
    "elecrepair" => 10,
    "fasttalk" => 5,
    "brawl" => 27,
    "handgun" => 20,
    "rifle" => 25,
    "firstaid" => 30,
    "history" => 5,
    "intimidate" => 15,
    "jump" => 20,
    "german" => 20,
    "own_lang" => 78,
    "law" => 5,
    "library" => 40,
    "listen" => 20,
    "locksmith" => 1,
    "mechrepair" => 10,
    "medicine" => 1,
    "naturalworld" => 40,
    "navigate" => 10,
    "occult" => 5,
    "op.hv.machine" => 1,
    "psychology" => 10,
    "psychoanalysis" => 1,
    "ride" => 5,
    "science" => 1,
    "chemistry" => 15,
    "sleightofhand" => 10,
    "spothidden" => 25,
    "stealth" => 20,
    "survival" => 10,
    "swim" => 20,
    "throw" => 20,
    "track" => 10,
  ];

  if ( !$stat ) {
    echo "Gotta have a stat!!" . PHP_EOL;
    return false;
  }

  $stat = strtolower($stat);

  echo "Rolling " . ucfirst($stat) . "..\n";

  $myStat = $skills[$stat] ?? null;

  if ( is_null($myStat) ) { echo "No such stat!!"; return false;}

  $greatSuccess = (int)floor($myStat / 2);
  $extremeSuccess = (int)floor($myStat / 5);

  $roll = rand(0,100);

  echo "Rolled $roll against $stat ($myStat) \n";

  if ( $roll <= 1 ) {
    echo "Critial success!! :O";
  } else if ( $roll <= $extremeSuccess ) {
    echo "Extreme success!";
  } else if ( $roll <= $greatSuccess ) {
    echo "Great success! ";
  } else if ( $roll <= $myStat ) {
    echo "Normal success!";
  } else if ( $roll >= 96 ) {
    echo "Big fail ;___;";
  } else {
    echo "Normal fail..";
  }
  return true;
}

