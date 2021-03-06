<?php

/**
 * Menu
 *
 * @author		Беляев Дмитрий <admin@cogear.ru>
 * @copyright		Copyright (c) 2011, Беляев Дмитрий
 * @license		http://cogear.ru/license.html
 * @link		http://cogear.ru
 * @package		Core
 * @subpackage

 */
class Menu_Object extends Observer {

    protected $pointer = 0;
    protected $options = array(
        'name' => 'primary',
        'template' => 'Menu/templates/simple',
        'show_empty' => TRUE,
        'render' => 'content',
        'multiple' => FALSE,
        'title' => TRUE,
        'titleActiveOnly' => TRUE,
        'autoactive' => TRUE,
    );
    protected $is_activated;

    /**
     * Конструктор
     *
     * @param array $options
     */
    public function __construct($options) {
        // Принимаем настройки
        parent::__construct($options);
        // Подкрепляем шестеренку Мета в качестве слушателя Listener (паттерн ООП Observer)
        $this->attach(cogear()->Meta);
        // Определяем базовый uri, от которого работает меню
        $this->options->base = rtrim(parse_url($this->options->base ? $this->options->base : Url::link(), PHP_URL_PATH), '/') . '/';
        // Если определен вывод, вешаем хук
        $this->options->render && hook($this->options->render, array($this, 'output'));
        // Регистрируем элементы меню из конфига
        if ($this->options->elements) {
            foreach ($this->options->elements as $item) {
                $this->add($item->toArray());
            }
        }
    }

    /**
     * Регистрация элемента в меню
     *
     * @param string $path
     * @param Menu_Item $item
     */
    public function add($item) {
        if (is_array($item)) {
            isset($item['order']) OR $item['order'] = $this->pointer++;
            $item['level'] = count(explode('.', $item['order']));
            $item = new Menu_Item($item);
        }
        if ($item->access !== FALSE) {
            $this->append($item);
        }
        return $this;
    }

    /**
     * Активируем пункт меню
     */
    public function setActive() {
        // Если уже активирован
        if ($this->is_activated) {
            return;
        }
        // Нужно знать номер последнего активированного
        $last_active = NULL;
        foreach ($this as $key => $item) {
            // Если в его настройках указано, что он активный, запоминаем
            if ($item->options->active) {
                $last_active = $key;
                event('menu.active', $item, $this);
            }
            // Если же в настройках пусто, то проверяем через роутер совпадение uri
            else if (FALSE !== $item->options->active && cogear()->router->check(trim($item->link, ' /'))) {
                $item->options->active = TRUE;
                $last_active = $key;
                event('menu.active', $item, $this);
            }
        }
        // Если активных нет или же могут быть несколько элементов — выходим
        if ($last_active && !$this->multiple) {

            // Отменяем все, кроме последнего активного
            foreach ($this as $key => $item) {
                if ($item->options->active) {
                    if ($key !== $last_active) {
                        $item->options->active = FALSE;
                    } else {
                        event('menu.active', $item, $this);
                    }
                }
            }
        }
        // Оповещаем наблюдатель (в данном случае шестеренка Meta) о событии
        $this->notify();
        // Ставим флажок, что уже выбрали активный
        $this->is_activated = TRUE;
    }

    /**
     * Ренденр меню
     *
     * @param string $glue
     * @return string
     */
    public function render() {
        // Событие
        event('menu', $this->options->name, $this);
        event('menu.' . $this->options->name, $this);
        // Если не пустой
        if ($this->count()) {
            // Сортируем
            $this->uasort('Core_ArrayObject::sortByOrder');
            // Активируем один из пунктов, если задана соотетствующая настрйока
            $this->autoactive && $this->setActive();
        }
        // Если не пустой или сказано, что может быть показан пустым
        if ($this->count() OR $this->options->show_empty) {
            // Создаем шаблон
            $tpl = new Template($this->options->template);
            // Заводим в него переменную
            $tpl->menu = $this;
            // Возвращаем рендер шаблона
            return $tpl->render();
        }
        return NULL;
    }

    /**
     * Показываем меню
     */
    public function output() {
        echo $this->render();
    }

    /**
     * Приведение к строке
     *
     * @return type
     */
    public function __toString() {
        return $this->render();
    }

}
