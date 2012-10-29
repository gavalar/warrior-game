<?php
require_once('models/Attack.php');

/**
 * Warrior 
 * 
 * @uses CombatInterface
 * @abstract
 * @package Warrior Game
 * @version $id$
 * @copyright 2011 Gavin Corbett
 * @author Gavin Corbett <gav.corbett@gmail.com> 
 * @license PHP Version 3.0 {@link http://www.php.net/license/3_0.txt}
 */
abstract class Warrior implements CombatInterface
{
    /**
     * _type 
     * 
     * @var string
     * @access protected
     */
    protected $_type = null;

    /**
     * _name 
     * 
     * @var string
     * @access protected
     */
    protected $_name = null;

    /**
     * _stats 
     * 
     * @var array
     * @access protected
     */
    protected $_stats = array(
        'health' => '',
        'attack' => '',
        'defence' => '',
        'speed' => '',
        'evade' => '',
    );

    /**
     * _start_stats 
     * 
     * @var array
     * @access protected
     */
    protected $_start_stats = array(
        'health' => '',
        'attack' => '',
        'defence' => '',
        'speed' => '',
        'evade' => '',
    );

    /**
     * _parameters 
     * 
     * @var array
     * @access protected
     */
    protected $_parameters  = array(
        'health'  => array('min' => '', 'max' => ''),
        'attack'  => array('min' => '', 'max' => ''),
        'defence' => array('min' => '', 'max' => ''),
        'speed'   => array('min' => '', 'max' => ''),
        'evade'   => array('min' => '', 'max' => ''),
    );

    /**
     * __construct 
     * 
     * @access public
     * @return this
     */
    public function __construct()
    {
        return $this;
    }

    /**
     * setName 
     * 
     * @param string $name 
     * @access public
     * @return this
     */
    public function setName($name)
    {
        $this->_name = $name;
        $this->generateStats();
        return $this;
    }

    /**
     * getName 
     * 
     * @access public
     * @return this
     */
    public function getName()
    {
        return $this->getType() . ' ' . $this->_name;
    }

    /**
     * getType 
     * 
     * @access public
     * @return this
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * takeDamage 
     * 
     * @param Attack $attack 
     * @access public
     * @return this
     */
    public function takeDamage(Attack $attack)
    {
        if(!$this->evadeAttack($attack))
        {
            $defence = $this->getStat('defence');

            $damage = abs($defence - $attack->getDamage());

            $health = $this->getStat('health');

            $new_health = $health - $damage;

            if($new_health < 0)
            {
                $new_health = 0;
            }

            $attack->log(sprintf(Attack::LOG_ATTACK, $attack->getAttacker()->getName(), $this->getName(), $new_health));
            $this->setStat('health', $new_health);
        }
        return $this;
    }

    /**
     * evadeAttack 
     * 
     * @param Attack $attack 
     * @access protected
     * @return bool
     */
    protected function evadeAttack(Attack $attack)
    {
        if($this->caculateChance($this->getStat('evade')))
        {
            $attack->log(sprintf(Attack::LOG_EVADE, $this->getName(), $attack->getAttacker()->getName()));
            return true;
        }

        return false;
    }

    /**
     * setStat 
     * 
     * @param string $stat_name 
     * @param integer $value 
     * @access protected
     * @return this
     */
    protected function setStat($stat_name, $value)
    {
        if(in_array($stat_name, array_keys($this->_stats)))
        {
            $this->_stats[$stat_name] = $value;
        }

        return $this;
    }

    /**
     * getStat 
     * 
     * @param string $stat_name 
     * @access public
     * @return integer
     */
    public function getStat($stat_name)
    {
        if(in_array($stat_name, array_keys($this->_stats)))
        {
            return $this->_stats[$stat_name];
        }

        return 0;
    }

    /**
     * getStartStat 
     * 
     * @param string $stat_name 
     * @access public
     * @return this
     */
    public function getStartStat($stat_name)
    {
        if(in_array($stat_name, array_keys($this->_start_stats)))
        {
            return $this->_start_stats[$stat_name];
        }

        return 0;
    }

    /**
     * isDead 
     * 
     * @access public
     * @return bool
     */
    public function isDead()
    {
        if($this->getStat('health') > 0)
        {
            return false;
        }

        return true;
    }

    /**
     * generateStats 
     * 
     * @access private
     * @return this
     */
    private function generateStats()
    {
        foreach($this->_parameters as $key => $range)
        {
            $this->setStat($key, rand($range['min'], $range['max']));
        }

        $combat = Combat::getInstance()->log(
            sprintf(
                'Starting Stats for <strong>%s</strong>: H:%d A:%d D:%d S:%d E:%d',
                $this->getName(),
                $this->getStat('health'),
                $this->getStat('attack'), 
                $this->getStat('defence'),
                $this->getStat('speed'),  
                $this->getStat('evade')
            )
        ); 
        $this->_start_stats = $this->_stats;
        
        return $this;
    }

    /**
     * caculateChance 
     *
     * This takes an integer and multiplies by 1000 then selects a random number between 0 and 100,000
     * if the random number is equal to lower than the percentage then returns true.
     * e.g. 30% = 30000
     * 
     * @param int $percentage 
     * @access protected
     * @return bool
     */
    protected function caculateChance($percentage)
    {
        $percentage = $percentage * 1000;
        $chance = rand(0,100000);

        if($percentage >= $chance)
        {
            return true;
        }

        return false;
    }
}
