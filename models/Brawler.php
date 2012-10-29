<?php

/**
 * Brawler 
 * 
 * @uses Warrior
 * @package Warrior Game
 * @version $id$
 * @copyright 2011 Gavin Corbett
 * @author Gavin Corbett <gav.corbett@gmail.com> 
 * @license PHP Version 3.0 {@link http://www.php.net/license/3_0.txt}
 */
class Brawler extends Warrior
{
    const LOG_SPECIAL = 'Player <strong>%s</strong> has increased defence by 10 points to <span>%d</span>';

    /**
     * _type 
     * 
     * @var string
     * @access protected
     */
    protected $_type = 'Brawler';

    /**
     * _parameters 
     * 
     * @var array
     * @access protected
     */
    protected $_parameters  = array(
        'health'  => array('min' => 90, 'max' => 100),
        'attack'  => array('min' => 65, 'max' => 75),
        'defence' => array('min' => 40, 'max' => 50),
        'speed'   => array('min' => 40, 'max' => 65),
        'evade'   => array('min' => 30, 'max' => 35),
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
        $attack_points = $this->getStat('attack');
        $attack->setDamage($attack_points); 
        return $this;
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
        parent::takeDamage($attack);
        $this->defenceIncrease($attack);
        return $this;
    }

    /**
     * selectVictim 
     *
     * Pick the warrior with the lowest health to finish them off first
     * 
     * @param array $live_warriors 
     * @access public
     * @return CombatInterface
     */
    public function selectVictim($live_warriors)
    {
        $selected_victim = null;
        $victims_health = 1000000;
        foreach($live_warriors as $warrior)
        {
            if($warrior->getStat('health') < $victims_health)
            {
                $victims_health = $warrior->getStat('health'); 
                $selected_victim = $warrior;
            }
        }

        return $selected_victim;        
    }

    /**
     * defenceIncrease 
     * 
     * When the health of the Brawler falls below 20% of original value then defence is increased by 10 points
     *
     * @param Attack $attack 
     * @access private
     * @return this
     */
    private function defenceIncrease(Attack $attack)
    {
        $start_health = $this->getStartStat('health');
        $current_health = $this->getStat('health');

        $twenty_percent = $start_health * .2;

        if($twenty_percent >= $current_health)
        {
            $defence = $this->getStat('defence');
            $defence += 10;
            $this->setStat('defence', $defence);
            $attack->log(sprintf(self::LOG_SPECIAL, $this->getName(), $defence));
        }

        return $this;
    }
}

