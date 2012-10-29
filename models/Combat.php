<?php

/**
 * Combat 
 * 
 * @package Warrior Game
 * @version $id$
 * @copyright 2011 Gavin Corbett
 * @author Gavin Corbett <gav.corbett@gmail.com> 
 * @license PHP Version 3.0 {@link http://www.php.net/license/3_0.txt}
 */
class Combat
{
    const LOG_WINNER = 'The winner of the contest is <strong>%s</strong> with <span>%d</span> points of health left.';
    const LOG_DIED = '<strong>%s</strong> has died.';
    const LOG_START = '<strong>%s</strong> will attack first.';
    const LOG_NEXT_TURN = 'Next warrior is: <strong>%s</strong> and has elected to attack <strong>%s</strong>.'; 
    const LOG_DRAW = 'The contest was a tie after %d turns left and %d warriors still standing are:';
    const LOG_DRAW_WARRIOR = 'Player <strong>%s</strong> with <span>%d</span> points of health left.';

    /**
     * _instance 
     *
     * Singleton instance
     * 
     * @var Combat
     * @static
     * @access private
     */
    private static $_instance = null;

    /**
     * _warriors 
     * 
     * @var array
     * @access private
     */
    private $_warriors = array();

    /**
     * _live_warriors 
     * 
     * @var array
     * @access private
     */
    private $_live_warriors = array();

    /**
     * _combat_log 
     * 
     * @var array
     * @access private
     */
    private $_combat_log = array();

    /**
     * _attacker 
     * 
     * @var CombatInterface
     * @access private
     */
    private $_attacker = null;

    /**
     * _turn 
     * 
     * @var integer
     * @access private
     */
    private $_turn = 0;

    /**
     * __construct 
     *
     * As a singleton users should be able to create a new instance of the object
     * 
     * @access private
     * @return this
     */
    private function __construct()
    {
    }

    /**
     * getInstance 
     * 
     * @static
     * @access public
     * @return this
     */
    public static function getInstance()
    {
        if(is_null(self::$_instance))
        {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * addWarrior 
     * 
     * @param CombatInterface $warrior 
     * @access public
     * @return this
     */
    public function addWarrior(CombatInterface $warrior)
    {
        $this->_live_warriors[] = $warrior;
        $this->_warriors[] = $warrior;
        return $this;
    }

    /**
     * doBattle 
     * 
     * @access public
     * @return this
     */
    public function doBattle()
    {
        while($this->_turn < 30) 
        {
            if(count($this->getLiveWarriors()) == 1)
            {
                $winner = $this->getLiveWarriors();
                $winner = array_shift($winner);
                $this->log(sprintf(self::LOG_WINNER, $winner->getName(), $winner->getStat('health')));
                break;
            }
            
            $next = $this->getNextWarrior();
            $next_warrior = $this->getLiveWarriors($next);

            $attack = new Attack($next_warrior);
            $attack->selectVictim($this->getLiveWarriorsExceptAttacker())
                   ->fight();

            $this->log(sprintf(self::LOG_NEXT_TURN, $attack->getAttacker()->getName(), $attack->getVictim()->getName()));

            foreach($attack->getAttackLog() as $log)
            {
                $this->log($log);
            }

            if($attack->getVictim()->isDead())
            {
                $this->hasDied($attack->getVictim());
            }

            $this->_turn ++;
        }

        if(count($this->getLiveWarriors()) > 1)
        {
            $this->log(sprintf(self::LOG_DRAW, $this->_turn, count($this->getLiveWarriors())));
            foreach($this->getLiveWarriors() as $warrior)
            { 
                $this->log(sprintf(self::LOG_DRAW_WARRIOR, $warrior->getName(), $warrior->getStat('health')));
            }
        }

        return $this;
    }

    /**
     * render 
     * 
     * @param string $html_file 
     * @access public
     * @return this
     */
    public function render($html_file = null)
    {
        require_once('html/_' . $html_file . '.phtml');
        return $this;
    }

    /**
     * getWarriors 
     * 
     * @access public
     * @return array
     */
    public function getWarriors()
    {
        return $this->_warriors;
    }

    /**
     * getLiveWarriors 
     * 
     * @param integer $key 
     * @access private
     * @return array
     */
    private function getLiveWarriors($key = null)
    {
        if(!is_null($key))
        {
            return $this->_live_warriors[$key];
        }

        return $this->_live_warriors;
    }

    /**
     * getLiveWarriorsExceptAttacker 
     * 
     * @access private
     * @return array
     */
    private function getLiveWarriorsExceptAttacker()
    {
        $victims = array();
        foreach($this->getLiveWarriors() as $key => $warrior)
        {
            if($key != $this->getAttacker())
            {
                $victims[] = $warrior;
            }
        }

        return $victims;
    }

    /**
     * setAttacker 
     * 
     * @param integer $key 
     * @access private
     * @return this
     */
    private function setAttacker($key)
    {
        $this->_attacker = $key;

        return $this;
    }

    /**
     * getAttacker 
     * 
     * @access private
     * @return integer
     */
    private function getAttacker()
    {
        return $this->_attacker;
    }

    /**
     * getNextWarrior 
     * 
     * @access private
     * @return integer
     */
    private function getNextWarrior()
    {
        if(is_null($this->getAttacker()))
        {
            return $this->startCombat();
        }

        $next_warrior = false;
        foreach($this->getLiveWarriors() as $key => $warrior)
        {
            if($next_warrior)
            {
                $this->setAttacker($key);
                return $key;
            }

            if($this->getAttacker() == $key)
            {
                $next_warrior = true;
            }
        }

        // If we have got here then was the last warrior in the list, return the first warrior in list
        reset($this->_live_warriors);
        $warriors = array_keys($this->_live_warriors);
        $warrior = array_shift($warriors);
        $this->setAttacker($warrior);
        return $this->getAttacker();
    }

    /**
     * startCombat 
     * 
     * @access private
     * @return integer
     */
    private function startCombat()
    {
        $starting_warrior = null;
        $speed = 1;
        foreach($this->getLiveWarriors() as $key => $warrior)
        {
            if($warrior->getStat('speed') > $speed)
            {
                $start_warrior = $warrior;
                $speed = $warrior->getStat('speed');
                $this->setAttacker($key);
            }
        }

        $this->log(sprintf(self::LOG_START, $start_warrior->getName()));
        return $this->getAttacker();
    }

    /**
     * hasDied 
     * 
     * @param CombatInterface $warrior 
     * @access private
     * @return this
     */
    private function hasDied(CombatInterface $warrior)
    {
        foreach($this->getLiveWarriors() as $key => $live)
        {
            if($warrior === $live)
            {
                $this->log(sprintf(self::LOG_DIED, $warrior->getName()));
                unset($this->_live_warriors[$key]);
            }
        }

        return $this;
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
        $log_pattern = '<span>Turn %d:</span> %s';
        $this->_combat_log[] = sprintf($log_pattern, $this->_turn, $log_message);

        return $this;
    }

    /**
     * getActionLog 
     * 
     * @access public
     * @return array
     */
    public function getActionLog()
    {
        return $this->_combat_log;
    }

    /**
     * __clone 
     *
     * As this is a singleton users should be able to clone this object
     * 
     * @access private
     * @return this
     */
    private function __clone()
    {
        throw Execption('You can not make copies of this class');
    }
}
