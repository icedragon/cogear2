<?php

/**
 * Code gear
 *
 * @author		Dmitriy Belyaev <admin@cogear.ru>
 * @copyright		Copyright (c) 2012, Dmitriy Belyaev
 * @license		http://cogear.ru/license.html
 * @link		http://cogear.ru
 * @package		Core
 * @subpackage
 * @version		$Id$
 */
class Code_Gear extends Gear {

    protected $name = 'Code';
    protected $description = 'Helps to deal with programming code';
    protected $version = '1.2';
    /**
     * Construct
     */
    public function __construct() {
        parent::__construct();
        Form::$types['code_editor'] = 'Code_Editor';
    }

    public function editor_action(){
        define('LAYOUT','splash');
        $form = new Form('Code/forms/editor');
        if($result = $form->result()){
            Redactor_Editor::insert($result->editor);
        }
        $form->show();
    }
}