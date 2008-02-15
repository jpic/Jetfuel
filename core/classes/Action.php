<?php
/**
 * @package TrevorCore
 */

/**
 * @package TrevorCore
 */
interface Action 
{
    public function __construct();

    public function run();

    public function getTemplate();

    public function getTemplateVars();
}

?>