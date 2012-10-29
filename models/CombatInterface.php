<?php

/**
 * CombatInterface 
 * 
 * @package Warrior Game
 * @version $id$
 * @copyright 2011 Gavin Corbett
 * @author Gavin Corbett <gav.corbett@gmail.com> 
 * @license PHP Version 3.0 {@link http://www.php.net/license/3_0.txt}
 */
interface CombatInterface
{
    /**
     * attack 
     * 
     * @param Attack $attack 
     * @access public
     */
    public function attack(Attack $attack);

    /**
     * getStat 
     * 
     * @param string $stat_name 
     * @access public
     */
    public function getStat($stat_name);

    /**
     * setName 
     * 
     * @param string $name 
     * @access public
     */
    public function setName($name);

    /**
     * isDead 
     * 
     * @access public
     */
    public function isDead();

    /**
     * getName 
     * 
     * @access public
     */
    public function getName();

    /**
     * getType 
     * 
     * @access public
     */
    public function getType();

    /**
     * takeDamage 
     * 
     * @param Attack $attack 
     * @access public
     */
    public function takeDamage(Attack $attack);

    /**
     * selectVictim 
     * 
     * @param array $live_warriors
     * @access public
     */
    public function selectVictim($live_warriors);
}
