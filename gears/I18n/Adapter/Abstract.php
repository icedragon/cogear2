<?php

/**
 * Abstract i18n adapter
 *
 * @author		Беляев Дмитрий <admin@cogear.ru>
 * @copyright		Copyright (c) 2011, Беляев Дмитрий
 * @license		http://cogear.ru/license.html
 * @link		http://cogear.ru
 * @package		Core
 *         I18n

 */
abstract class I18n_Adapter_Abstract extends Options {

    protected $options = array(
        'lang' => 'en',
    );
    protected $update_flag = TRUE;

    const SECTION_PREFIX = '#';

    /**
     * Get text
     *
     * @param   string  $text
     * @param   string  $section
     */
    public function get($text) {
        if ($this->$text) {
            return $this->$text;
        }
        return $text;
    }

    /**
     * Get text
     *
     * @param   string  $text
     * @param   string  $value
     * @param   string  $section
     */
//    public function set($text, $value, $section = NULL) {
//        $args = func_get_args();
//        if ($section) {
//            $section = $this->prepareSection($section);
//            if (!$this->$section) {
//                $this->$section = new Core_ArrayObject();
//            }
//            $this->$section->$text = $value;
//        } else {
//            $this->$text = $value;
//        }
//        $this->update_flag = TRUE;
//    }

    /**
     * Prepare section
     *
     * @param string $section
     */
    protected function prepareSection($section) {
        if ($section[0] != self::SECTION_PREFIX) {
            $section = self::SECTION_PREFIX . $section;
        }
        return $section;
    }

    /**
     * Import text
     *
     * @param   string  $text
     * @param   string  $section
     */
    public function import($data, $section = NULL) {
        if ($section) {
            $this->$section OR $this->$section = new Core_ArrayObject();
            $this->$section->extend($data);
        } else {
            $this->extend($data);
        }
    }

    /**
     * Export text
     *
     * @param   string  $text
     * @param   string  $section
     */
    public function export($section = NULL) {
        return $section ? $this->$section->getArrayCopy() : $this->toArray();
    }

    /**
     * Load data
     */
    abstract public function load();

    /**
     * Save data
     */
    abstract public function save();

    /**
     * Magic __get method
     *
     * @param string $name
     * @return mixed
     */
    public function __get($name) {
        if (!$this->offsetExists($name)) {
            return NULL;
        }
        return $this->offsetGet($name);
    }

}