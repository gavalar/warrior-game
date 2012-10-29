<?php
require_once('models/WarriorFactory.php');

/**
 * Generic 
 * 
 * @abstract
 * @package Warrior Game
 * @version $id$
 * @copyright 2011 Gavin Corbett
 * @author Gavin Corbett <gav.corbett@gmail.com> 
 * @license PHP Version 3.0 {@link http://www.php.net/license/3_0.txt}
 */
abstract class Generic
{
    /**
     * isFormValid 
     * 
     * @param array $data 
     * @static
     * @access public
     * @return this
     */
    public static function isFormValid($data)
    {
        //return true;
        if(empty($data) || !is_array($data))
        {
            return false;
        }

        if(!isset($data['special']) || $data['special'] != self::getXsrf())
        {
            return false;
        }

        foreach($data['name'] as $name)
        {
            if(empty($name))
            {
                return false;
            }
        }

        foreach($data['warrior'] as $warrior)
        {
            if(!in_array($warrior, WarriorFactory::getValidWarriors()))
            {
                return false;
            }
        }

        self::clearXsrf();

        return true;
    }

    /**
     * getXsrf 
     *
     * Generates the Cross Site Request Forgery (XSRF or CSRF) key.  This ensure that only this form can be validated
     * Automatically invalidate the key after the time out period (for production would have a 'grace' period)
     * 
     * @static
     * @access public
     * @return this
     */
    public static function getXsrf()
    {
        if(!isset($_SESSION['xsrf']) || $_SESSION['timeout'] < time(true))
        {
            $_SESSION['xsrf'] = md5(time().'salt');
            $_SESSION['timeout'] = strtotime('5 minutes');
        }

        return $_SESSION['xsrf'];
    }

    /**
     * clearXsrf 
     *
     * Allows the XSRF key to be reset if the form is correct
     * 
     * @static
     * @access private
     * @return this
     */
    private static function clearXsrf()
    {
        unset($_SESSION['xsrf']);
        return true;
    }
}
