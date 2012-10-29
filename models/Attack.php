<?php

/**
 * Attack 
 * 
 * Allows one warrior to attack another warrior
 *
 * @package Warrior Game 
 * @version $id$
 * @copyright 2011 Gavin Corbett
 * @author Gavin Corbett <gav.corbett@gmail.com> 
 * @license PHP Version 3.0 {@link http://www.php.net/license/3_0.txt}
 */
class Attack
{
    const LOG_ATTACK = '<strong>%s</strong> has attacked <strong>%s</strong> and now has <span>%d</span> of health left.';
    const LOG_EVADE = '<strong>%s</strong> has evaded the attack from <strong>%s</strong>.';

    /**
     * _attacker 
     * 
     * @var CombatInterface
     * @access private
     */
    private $_attacker = null;

    /**
     * _victim 
     * 
     * @var CombatInterface
     * @access private
     */
    private $_victim = null;

    /**
     * _damage 
     * 
     * @var integer
     * @access private
     */
    private $_damage = 0;
    
    /**
     * _attack_log 
     * 
     * @var array
     * @access private
     */
    private $_attack_log = array();

    /**
     * __construct 
     * 
     * @param CombatInterface $warrior 
     * @access public
     * @return this
     */
    public function __construct(CombatInterface $warrior)
    {
        $this->_attacker = $warrior;
        return $this;
    }

    /**
     * setVictim 
     * 
     * @param CombatInterface $warrior 
     * @access public
     * @return this
     */
    public function setVictim(CombatInterface $warrior)
    {
        $this->_victim = $warrior;
        return $this;
    }

    /**
     * getVictim 
     * 
     * @access public
     * @return CombatInterface
     */
    public function getVictim()
    {
        return $this->_victim;
    }

    /**
     * getAttacker 
     * 
     * @access public
     * @return CombatInterface
     */
    public function getAttacker()
    {
        return $this->_attacker;
    }

    /**
     * setDamage 
     * 
     * @param integer $damage 
     * @access public
     * @return this
     */
    public function setDamage($damage)
    {
        $this->_damage = (int)$damage;
        return $this;
    }

    /**
     * getDamage 
     * 
     * @access public
     * @return integer
     */
    public function getDamage()
    {
        return $this->_damage;
    }

    /**
     * fight 
     * 
     * @access public
     * @return this
     */
    public function fight()
    {
        $this->getAttacker()->attack($this);
        $this->getVictim()->takeDamage($this);
        return $this;
    }

    /**
     * selectVictim 
     * 
     * @param array $live_warriors 
     * @access public
     * @return this
     */
    public function selectVictim($live_warriors)
    {
        $victim = $this->getAttacker()->selectVictim($live_warriors);
        $this->setVictim($victim);

        return $this;
    }

    /**
     * getAttackLog 
     * 
     * @access public
     * @return array
     */
    public function getAttackLog()
    {
        return $this->_attack_log;
    }

    /**
     * log 
     * 
     * @param string $log_message 
     * @access public
     * @return this
     */
    public function log($log_message)
    {
        $this->_attack_log[] = $log_message;
        return $this;
    }
}
