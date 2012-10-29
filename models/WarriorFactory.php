<?php
require_once('models/CombatInterface.php');
require_once('models/Warrior.php');
require_once('models/Ninja.php');
require_once('models/Samurai.php');
require_once('models/Brawler.php');

/**
 * WarriorFactory 
 * 
 * @abstract
 * @package Warrior Game
 * @version $id$
 * @copyright 2011 Gavin Corbett
 * @author Gavin Corbett <gav.corbett@gmail.com> 
 * @license PHP Version 3.0 {@link http://www.php.net/license/3_0.txt}
 */
abstract class WarriorFactory
{
    const WARRIOR_RANDOM = 'x';
    const WARRIOR_NINJA = 1;
    const WARRIOR_SAMURAI = 2;
    const WARRIOR_BRAWLER = 3;

    /**
     * _warriors 
     * 
     * @var array
     * @static
     * @access private
     */
    private static $_warriors = array(
        self::WARRIOR_RANDOM,
        self::WARRIOR_NINJA,
        self::WARRIOR_SAMURAI,
        self::WARRIOR_BRAWLER,
    );

    /**
     * getWarrior 
     * 
     * @param integer $type 
     * @static
     * @access public
     * @return Warrior
     */
    public static function getWarrior($type)
    {
        switch($type)
        {
            case self::WARRIOR_NINJA:
                return new Ninja();
            case self::WARRIOR_SAMURAI:
                return new Samurai();
            case self::WARRIOR_BRAWLER:
                return new Brawler();
            case self::WARRIOR_RANDOM:
                return self::getWarrior(rand(self::WARRIOR_NINJA, self::WARRIOR_BRAWLER));
            default:
                throw new Exception('Unable to create new concrete Warrior Class');
        }
    }

    /**
     * getValidWarriors 
     * 
     * @static
     * @access public
     * @return array
     */
    public static function getValidWarriors()
    {
        return self::$_warriors;
    }
}
