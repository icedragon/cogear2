<?php

/**
 * Абстрактный класс кеша
 *
 * @author		Беляев Дмитрий <admin@cogear.ru>
 * @copyright		Copyright (c) 2010, Беляев Дмитрий
 * @license		http://cogear.ru/license.html
 * @link		http://cogear.ru
 */
abstract class Cache_Driver_Abstract extends Object {

    abstract public function read($name);

    abstract public function write($name, $value, $tags=NULL, $ttl=NULL);

    abstract public function remove($name);

    abstract public function clear();
    /**
     * Remove cached tags
     *
     * @param string|array $name
     */
    public function removeTags($name) {
        if (is_array($name)) {
            foreach ($name as $tag) {
                $this->remove('tags/' . $tag);
            }
        } else {
            $this->remove('tags/' . $name);
        }
    }

    /**
     *  Prepare filaname for cache
     * @param string $name
     * @return string
     */
    protected function prepareKey($name) {
        $name = str_replace('/', DS, $name . EXT);
        return $name;
    }

}