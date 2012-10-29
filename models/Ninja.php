<?php

/**
 * Ninja 
 * 
 * @uses Warrior
 * @package Warrior Game
 * @version $id$
 * @copyright 2011 Gavin Corbett
 * @author Gavin Corbett <gav.corbett@gmail.com> 
 * @license PHP Version 3.0 {@link http://www.php.net/license/3_0.txt}
 */
class Ninja extends Warrior
{
    const LOG_SPECIAL = 'Player <strong>%s</strong> has doubled attack point this round to %d';

    /**
     * _type 
     * 
     * @var string
     * @access protected
     */
    protected $_type = 'Ninja';

    /**
     * _parameters 
     * 
     * @var array
     * @access protected
     */
    protected $_parameters  = array(
        'health'  => array('min' => 40, 'max' => 60),
        'attack'  => array('min' => 60, 'max' => 70),
        'defence' => array('min' => 20, 'max' => 30),
        'speed'   => array('min' => 90, 'max' => 100),
        'evade'   => array('min' => 30, 'max' => 50),
    );

    /**
     * attack 
     * 
     * @param Attack $attack 
     * @access public
     * @return this
     */
    public function attack(Attack $attack)
    {
        $attack_points = $this->doubleAttack($attack);
        $attack->setDamage($attack_points); 
        return $this;
    }

    /**
     * takeDamage 
     * 
     * @param Attack $attack 
     * @access public
     * @return parent::takeDamage
     */
    public function takeDamage(Attack $attack)
    {
        return parent::takeDamage($attack);
    }

    /**
     * selectVictim 
     *
     * Pick the warrior with the lowest evade probability
     * 
     * @param array $live_warriors 
     * @access public
     * @return CombatInterface
     */
    public function selectVictim($live_warriors)
    {
        $selected_victim = null;
        $victims_evade = 100;
        foreach($live_warriors as $warrior)
        {
            if($warrior->getStat('evade') < $victims_evade)
            {
                $victims_evade = $warrior->getStat('evade'); 
                $selected_victim = $warrior;
            }
        }

        return $selected_victim;        
    }

    /**
     * doubleAttack 
     *
     * For every attack there is a 5% chance that the attack points are doubled
     * 
     * @param Attack $attack 
     * @access private
     * @return integer
     */
    private function doubleAttack(Attack $attack)
    {
        $attack_points = $this->getStat('attack');

        if($this->caculateChance(5))
        {
            $attack_points = $attack_points * 2;
            $attack->log(sprintf(self::LOG_SPECIAL, $this->getName(), $attack_points));
        }

       return $attack_points; 
    }
}

