<?php

/**
 * Элемент файла для формы
 *
 * @author		Беляев Дмитрий <admin@cogear.ru>
 * @copyright		Copyright (c) 2012, Беляев Дмитрий
 * @license		http://cogear.ru/license.html
 * @link		http://cogear.ru
 */
class File_Element extends Form_Element_Abstract {

    protected $template = 'File/templates/element';

    /**
     * Конструктор
     *
     * @param array $options
     */
    public function __construct($options) {
        parent::__construct($options);
    }

    /**
     * Process elements value from request
     *
     * @return
     */
    public function result() {
        $file = new File_Upload($this->options);
        if ($value = $file->upload()) {
            $this->is_fetched = TRUE;
            $this->value = $value;
        } else {
            $this->errors = $file->getErrors();
            $this->value = $this->options->value;
        }
        return $this->value;
    }

    /**
     * Render
     */
    public function render() {
        $this->prepareOptions();
        if ($this->options->allowed_types) {
            $this->notice(t('Следующие типы файлов разрешены к загрузке: <b>%s</b>.', $this->options->allowed_types->toString('</b>, <b>')));
        }
        if ($this->options->maxsize) {
            $this->notice(t('Макстмальный размер файла <b>%s</b>.', File::fromBytes(File::toBytes($this->options->maxsize), NULL, 2)));
        }
        if ($this->notices->count()) {
            $this->options->description .= '<ul class="file-notice"><li>' . $this->notices->toString('</li><li>') . '</li></ul>';
        }
        $tpl = new Template($this->template);
        $tpl->assign($this->options);
        $this->code = $tpl->render();
        $this->decorate();
        return $this->code;
    }

}