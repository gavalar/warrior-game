<?php

/**
 * Samurai 
 * 
 * @uses Warrior
 * @package Warrior Game
 * @version $id$
 * @copyright 2011 Gavin Corbett
 * @author Gavin Corbett <gav.corbett@gmail.com> 
 * @license PHP Version 3.0 {@link http://www.php.net/license/3_0.txt}
 */
class Samurai extends Warrior
{
    const LOG_SPECIAL = 'Player %s has increased health by 10 point to %d';

    /**
     * _type 
     * 
     * @var string
     * @access protected
     */
    protected $_type = 'Samurai';

    /**
     * _parameters 
     * 
     * @var array
     * @access protected
     */
    protected $_parameters  = array(
        'health'  => array('min' => 60, 'max' => 100),
        'attack'  => array('min' => 75, 'max' => 80),
        'defence' => array('min' => 35, 'max' => 40),
        'speed'   => array('min' => 60, 'max' => 80),
        'evade'   => array('min' => 30, 'max' => 40),
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
     * @return parent::takeDamage
     */
    public function takeDamage(Attack $attack)
    {
        return parent::takeDamage($attack);
    }

    /**
     * selectVictim 
     *
     * Pick the first Samurai Warrior in the list otherwise the first Warrior
     * 
     * @param array $live_warriors 
     * @access public
     * @return CombatInterface
     */
    public function selectVictim($live_warriors)
    {
        $selected_victim = null;
        foreach($live_warriors as $warrior)
        {
            if($warrior instanceof Samurai)
            {
                return $warrior;
            }
        }

        return array_shift($live_warriors);
    }

    /**
     * evadeAttack 
     *
     * On evading an attack there is a 10% chance that health is increased by 10 points
     * 
     * @param Attack $attack 
     * @access protected
     * @return bool
     */
    protected function evadeAttack(Attack $attack)
    {
        if($this->caculateChance($this->getStat('evade')))
        {
            if($this->caculateChance(1))
            {
                $health = $this->getStat('health');
                $health += 10;
                $attack->log(sprintf(self::LOG_SPECIAL, $this->getName(), $health));
                $this->setStat('health', $health);
            }

            $attack->log(sprintf(Attack::LOG_EVADE, $this->getName(), $attack->getAttacker()->getName()));
            return true;
        }

        return false;
    }


}

