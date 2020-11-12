<?php


class Server{
    protected $clients;
}

class Client{

}


interface IDatabase{

}

interface IPayload{
    public function getDatabase():IDatabase;
}

interface IQuery{}

interface IResult{}

interface IStorage {
    public function write(IPayload $payload):void;
    public function read(IQuery $query):IResult;
}

class ClassCollection implements ArrayAccess, Iterator
{
    private $container = [], $position = 0;
    protected $class;

    public function __construct(string $class)
    {
        $this->class = $class;
    }

    public function offsetSet($offset, $value)
    {
        $suppliedClass = get_class($value); // includes is object check
        if ( $suppliedClass === $this->class){
            is_null( $offset )
                ? $this->container[] = $value
                : $this->container[$offset] = $value
            ;
        } else {
            throw new \Exception("Cannot add " . $suppliedClass . " to collection of type " . $this->class);
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->container[$offset] ?? null;
    }

    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    public function addAll($items)
    {
        foreach ($items as $item) {
            $this->container[] = $item;
        }
    }

    public function current()
    {
        return $this->container[$this->position];
    }

    public function next()
    {
        $this->position++;
    }

    public function key()
    {
        return $this->position;
    }

    public function valid()
    {
        return isset($this->container[$this->position]);
    }

    public function rewind()
    {
        $this->position = 0;
    }
}

interface IEntity{}

interface IDice{
    public function roll():int;

    /**
     * @param int $howMany
     *
     * @return int[]
     */
    public function rollMany( int $howMany ):array;
}

class Dice implements IDice{

    protected $max, $step = 1;

    /**
     * @return int
     * @throws Exception
     */
    public function roll(): int
    {
        return random_int( 1, $this->max );
    }

    /**
     * @param int $howMany
     *
     * @return array
     * @throws Exception
     */
    public function rollMany( int $howMany ): array
    {
        for ( $i = 0; $i < $howMany; $i++ ){
            $rolls[] = $this->roll();
        }
        return $rolls ?? [];
    }
}

class D20 extends Dice{
    public function __construct()
    {
        $this->max = 20;
    }
}

class DiceSet extends ClassCollection {
    public function __construct()
    {
        parent::__construct(Dice::class);
    }
}

class Stat{
    protected $baseValue, $boostFromBase = 0;

    public function __construct(int $baseValue = 8)
    {
        $this->baseValue = $baseValue;
    }

    public function __toString()
    {
        return __CLASS__;
    }

    /**
     * @return int
     */
    public function getBaseValue(): int
    {
        return $this->baseValue;
    }

    /**
     * @return int
     */
    public function getBoostFromBase(): int
    {
        return $this->boostFromBase;
    }

    public function getValue(): int
    {
        return $this->getBaseValue() + $this->getBoostFromBase();
    }

    public function boost(int $by = 1):Stat{
        $this->boostFromBase += $by;
        return $this;
    }

    /**
     * @param int $baseValue
     */
    public function setBaseValue(int $baseValue): void
    {
        $this->baseValue = $baseValue;
    }
}

class Strength extends Stat{}
class Dexterity extends Stat{}
class Intelligence extends Stat{}
class Wisdom extends Stat{}
class Charisma extends Stat{}
class Constitution extends Stat{}

abstract class Effect{
    abstract function affectEntity(Entity $instance);
}

class ModifyStat extends Effect{
    protected $value, $statType;

    public function __construct(int $value, string $statType)
    {
        $this->value = $value;
        $this->statType = ucfirst($statType);
    }

    // __invoke(Entity)??

    /**
     * @return string
     */
    public function getStatType(): string
    {
        return $this->statType;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    function affectEntity(Entity $instance)
    {
        ($instance->getStats()->{"get" . $this->getStatType()}())
            ->boost($this->getValue())
        ;
    }
}

class Heal extends Effect {
    protected $amount;
    public function __construct(int $amount = 0)
    {
        $this->amount = $amount;
    }

    function affectEntity(Entity $instance)
    {
        ($instance->getStats()->{"get" . $this->getStatType()}())
            ->boost($this->getValue())
        ;
    }
}


class Stats { // extend classcoll?
    protected $strength, $dexterity, $intelligence, $wisdom, $charisma, $constitution;

    public function __construct(Strength $str, Dexterity $dex, Intelligence $int, Wisdom $wis, Charisma $chr, Constitution $con)
    {
        $this->strength = $str;
        $this->dexterity = $dex;
        $this->intelligence = $int;
        $this->wisdom = $wis;
        $this->charisma = $chr;
        $this->constitution = $con;
    }

    /**
     * @return Strength
     */
    public function getStrength(): Strength
    {
        return $this->strength;
    }

    /**
     * @return Dexterity
     */
    public function getDexterity(): Dexterity
    {
        return $this->dexterity;
    }

    /**
     * @return Intelligence
     */
    public function getIntelligence(): Intelligence
    {
        return $this->intelligence;
    }

    /**
     * @return Wisdom
     */
    public function getWisdom(): Wisdom
    {
        return $this->wisdom;
    }

    /**
     * @return Charisma
     */
    public function getCharisma(): Charisma
    {
        return $this->charisma;
    }

    /**
     * @return Constitution
     */
    public function getConstitution(): Constitution
    {
        return $this->constitution;
    }

    /**
     * @param Strength $strength
     * @return Stats
     */
    public function setStrength(Strength $strength): Stats
    {
        $this->strength = $strength;
        return $this;
    }

    /**
     * @param Dexterity $dexterity
     * @return Stats
     */
    public function setDexterity(Dexterity $dexterity): Stats
    {
        $this->dexterity = $dexterity;
        return $this;
    }

    /**
     * @param Intelligence $intelligence
     * @return Stats
     */
    public function setIntelligence(Intelligence $intelligence): Stats
    {
        $this->intelligence = $intelligence;
        return $this;
    }

    /**
     * @param Wisdom $wisdom
     * @return Stats
     */
    public function setWisdom(Wisdom $wisdom): Stats
    {
        $this->wisdom = $wisdom;
        return $this;
    }

    /**
     * @param Charisma $charisma
     * @return Stats
     */
    public function setCharisma(Charisma $charisma): Stats
    {
        $this->charisma = $charisma;
        return $this;
    }

    /**
     * @param Constitution $constitution
     * @return Stats
     */
    public function setConstitution(Constitution $constitution): Stats
    {
        $this->constitution = $constitution;
        return $this;
    }
}

class Progression{
    protected $level;
}

class Status{
    protected $health, $stamina;

    public function __construct(int $health = 20, $stamina = 10)
    {
        $this->health = $health;
        $this->stamina = $stamina;
    }
}

class EntityRace {
    protected $name, $statModifiers;

    public function __construct()
    {
        $this->name = get_called_class();
        $this->statModifiers = new ClassCollection(ModifyStat::class);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return ClassCollection|ModifyStat[]
     */
    public function getStatModifiers(): ClassCollection
    {
        return $this->statModifiers;
    }
}

class Human extends EntityRace{
    public function __construct()
    {
        parent::__construct();
        $this->statModifiers->addAll([
            new ModifyStat( 1, Strength::class),
            new ModifyStat( 1, Dexterity::class),
            new ModifyStat( 1, Intelligence::class),
            new ModifyStat( 1, Wisdom::class),
            new ModifyStat( 1, Charisma::class),
            new ModifyStat( 1, Constitution::class),
        ]);
    }
}

class Orc extends EntityRace{
    public function __construct()
    {
        parent::__construct();
        $this->statModifiers->addAll([
            new ModifyStat( 111, Strength::class),
            new ModifyStat( 111, Dexterity::class),
            new ModifyStat( 111, Intelligence::class),
            new ModifyStat( 111, Wisdom::class),
            new ModifyStat( 111, Charisma::class),
            new ModifyStat( 111, Constitution::class),
        ]);
    }
}

class CharacterClass{
    protected $name, $bonuses;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}

class CombinedCharacterClass{}

interface Consumable{}

class Item{
    protected $description, $value = 0, $effects;

    public function __construct()
    {
        $this->effects = new ClassCollection(Effect::class);
    }
}

class Potion extends Item implements Consumable{}

class HealthPotion extends Potion{
    public function __construct(){
        parent::__construct();
        $this->effects[] = new Heal();
    }
}

class Inventory {}

class Entity implements IEntity {
    protected $id, $name, $status, $race, $class, $stats, $skills, $inventory;

    public function __construct(string $name, EntityRace $race,
                                CharacterClass $class, Stats $stats, Status $status){
        $this->name = $name;
        $this->id = uniqid($this->name);
        $this->race = $race;
        $this->class = $class; // todo: nullable things
        $this->stats = $stats;
        $this->status = $status;

        $this->inventory = new ClassCollection(Item::class);
    }

    /**
     * @return mixed
     */
    public function getStats()
    {
        return $this->stats;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }
}

class Character extends Entity {}

class StatGenerator{
    protected $race, $class, $stats;

    public function __construct(EntityRace $race, CharacterClass $class)
    {
        $this->race = $race;
        $this->class = $class;
        $this->generate();
    }

    protected function generate()
    {
        // php 7.4 lambda
        $this->stats =
            new Stats(
                new Strength(),
                new Dexterity(),
                new Intelligence(),
                new Wisdom(),
                new Charisma(),
                new Constitution()
            )
        ;

        foreach ($this->race->getStatModifiers() as $statModifier) {
            ($this->stats->{"get" . $statModifier->getStatType()}())
                ->boost($statModifier->getValue())
            ;
        }
    }

    /**
     * @return Stats
     */
    public function getStats(): Stats
    {
        return $this->stats;
    }
}

abstract class Instance{
    abstract function invoke();
}

class Encounter extends Instance{
    function invoke()
    {
        echo "You encounter a ";
    }
}

class MapTile{
    protected $sprite = "=";

    /**
     * @return string
     */
    public function getSprite(): string
    {
        return $this->sprite;
    }

    /**
     * @param string $sprite
     */
    public function setSprite(string $sprite): void
    {
        $this->sprite = $sprite;
    }
}

abstract class Gridded{

}

class Map {

    protected $gridHeight, $sprite, $gridWidth, $tiles;

    public function __construct(array $config) // todo: interface
    {

        $this->gridHeight = $config['height'];
        $this->gridWidth = $config['width'];
        $this->sprite = $config['sprite'];

        $this->generateMap();
    }

    protected function generateSeed():string{}

    protected function generateMap(int $seed):void{
        $top = function (int $width){

        };
        for ( $y = 0; $y < $this->gridHeight; $y++){
            $this->tiles[$y] = new ClassCollection(MapTile::class);
            for ( $x = 0; $x < $this->gridWidth; $x++ ){
                $tile = new MapTile();
                if ( $y === 0 ){
                    if ( $x === (int)round($this->gridWidth / 2)){
                        $tile->setSprite("O");
                    }
                }
                $this->tiles[$y][$x] = $tile;
            }
        }
    }

    /**
     * @return array
     */
    public function getTiles(): array
    {
        return $this->tiles;
    }
}

class MapDrawer{
    protected $map;
    public function __construct(Map $map)
    {
        $this->map = $map;
    }

    /**
     * @var int $row
     * @var MapTile $tile
     */
    public function __invoke()
    {
        foreach ($this->map->getTiles() as $column => $tiles) {
            foreach ($tiles as $row => $tile) {
                echo " " . $tile->getSprite() . " ";
            }
            echo PHP_EOL;
        }
    }
}

class Player{
    protected $name;
}

class Game{}

interface Rule{}

class PlayerRule{}

class DiceRollTranslator{
    protected $min, $max, $times;
}

class CharacterRule{}

$party = new ClassCollection(Character::class);
$class = new CharacterClass("Haxx0rz");

$name = "Johnny";
$race = new Orc();
$stats = ( new StatGenerator( $race, $class ) )->getStats();

$character = new Character("Johnny", $race, $class, $stats, new Status());

$mypos = ["x" => 0, "y" => 0];
$mapConfig = [
    'type' => 'city_street',
    'exits' => 4,
    'width' => 20,
    'height' => 10,
];

$map = new Map($mapConfig);

$continue=true;
while ( $continue ){
    (new MapDrawer($map))();
    $action = mb_strtoupper(readline(">:"));
}



#var_dump($character->getStatus());

#var_dump($newStats->getStrength()->getValue());


#$character = new Character( "Johhny", $class  $stats)

