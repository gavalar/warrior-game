<?php
session_start();
require_once('html/template-head.phtml');
require_once('models/Generic.php');

if(!Generic::isFormValid($_POST))
{    
    require_once('html/form.phtml');
}
else
{
    require_once('battle.php');
}

require_once('html/template-footer.phtml');
