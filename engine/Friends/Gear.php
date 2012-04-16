<?php
/**
 * Friends gear
 *
 * @author		Dmitriy Belyaev <admin@cogear.ru>
 * @copyright		Copyright (c) 2012, Dmitriy Belyaev
 * @license		http://cogear.ru/license.html
 * @link		http://cogear.ru
 * @package		Core
 * @subpackage          
 * @version		$Id$
 */
class Friends_Gear extends Gear {

    protected $name = 'Friends';
    protected $description = 'Manage friends';
    protected $package = '';
    protected $order = 0;

    /**
     * Init
     */
    public function init() {
        parent::init();
    }

    /**
     * Default dispatcher
     * 
     * @param string $action
     * @param string $subaction 
     */
    public function index($action = '', $subaction = NULL) {
            
    }
    
    /**
     * Custom dispatcher
     * 
     * @param   string  $subaction
     */
    public function action_index($subaction = NULL){
        
    }
}