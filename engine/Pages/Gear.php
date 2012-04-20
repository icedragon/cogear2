<?php

/**
 * Pages gear
 *
 * @author		Dmitriy Belyaev <admin@cogear.ru>
 * @copyright		Copyright (c) 2012, Dmitriy Belyaev
 * @license		http://cogear.ru/license.html
 * @link		http://cogear.ru
 * @package		Core
 * @subpackage
 * @version		$Id$
 */
class Pages_Gear extends Gear {

    protected $name = 'Pages';
    protected $description = 'Pagess manager';
    protected $order = 0;
    protected $routes = array(
        'page/:digit/?' => 'showPage',
    );

    /**
     * Init
     */
    public function init() {
        parent::init();
    }

    /**
     * Hook menu
     *
     * @param string $name
     * @param object $menu
     */
    public function menu($name, $menu) {
        switch ($name) {
            case 'admin':
                $menu->register(array(
                    'label' => icon('list') . ' ' . t('Pages', 'Pages'),
                    'link' => l('/admin/pages'),
                    'order' => 150
                ));
                break;
        }
    }

    /**
     * Admin dispatcher
     *
     * @param type $action
     * @param type $subaction
     */
    public function admin($action = NULL, $subaction = NULL) {
        $menu = new Menu_Tabs(array(
                    'name' => 'pages.admin',
                    'elements' => array(
                        'list' => array(
                            'label' => t('List'),
                            'link' => l('/admin/pages'),
                            'active' => !check_route('admin/pages/create') && !check_route('admin/pages/edit',Router::STARTS),
                        ),
                        'edit' => array(
                            'label' => icon('edit') . ' ' . t('Edit'),
                            'link' => l('/admin/pages/edit'),
                            'class' => 'fl_r',
                            'access' => check_route('admin/pages/edit',  Router::STARTS),
                        ),
                        'create' => array(
                            'label' => icon('pencil') . ' ' . t('Create'),
                            'link' => l('/admin/pages/create'),
                            'class' => 'fl_r',
                        ),
                    ),
                    'render' => 'info',
                ));
        switch ($action) {
            case 'create':
                $this->admin_create();
                break;
            case 'edit':
                $this->admin_edit($subaction);
                break;
            default:
                $this->admin_index();
        }
    }

    /**
     * Show list of pages
     */
    public function admin_index() {
        $pages = new Pages();
        $tpl = new Template('Pages.list');
        $tpl->pages = $pages->findAll();
        $tpl->show();
    }

    /**
     * Create page
     */
    public function admin_create() {
        $form = new Form('Pages.page');
        if ($result = $form->result()) {
            $page = new Pages();
            $page->attach($result);
            if ($result->preview) {
                $page->created_date = time();
                $page->preview = TRUE;
                $page->show();
            } else {
                if ($result->draft) {
                    $page->published = 0;
                } elseif ($result->publish) {
                    $page->published = 1;
                }
                if ($page->save()) {
                    flash_success(t($page->published ? 'Page published!' : 'Page saved to drafts!') . ' <a class="btn btn-primary btn-mini" href="' . $page->getLink() . '">' . t('View') . '</a>');
                    redirect($page->getEditLink());
                }
            }
        }
        // Remove 'delete' button from create post form
        $form->elements->offsetUnset('delete');
        $form->show();
    }

    /**
     * Edit action
     */
    public function admin_edit($id = NULL) {
        if (!$id) {
            return event('404');
        }
        $page = new Pages();
        $page->id = $id;
        if (!$page->find()) {
            return event('404');
        }
        $form = new Form('Pages.page');
        $form->attach($page);
        $form->elements->title->options->label = t('Edit page');
        if ($result = $form->result()) {
            $page->object->mix($result);
            if ($result->delete && (access('page.delete.all') OR access('page.delete') && $this->user->id == $page->aid)) {
                if ($page->delete()) {
                    flash_success(t('Page has been deleted!'));
                    redirect(l('/admin/pages'));
                }
            }
            if ($result->preview) {
                $page->created_date = time();
                $page->aid = $this->user->id;
                $page->preview = TRUE;
                $page->show();
            } else {
                if ($result->draft) {
                    $page->published = 0;
                } elseif ($result->publish) {
                    $page->published = 1;
                }
                if ($page->save()) {
                    if ($page->published) {
                        $link = l($page->getLink());
                        info(t('Page is published! %s', 'Pages', '<a class="btn btn-primary btn-mini" href="' . $link . '">' . t('View') . '</a>'));
                    } else {
                        $link = l($page->getLink());
                        success(t('Page is saved to drafts! %s', 'Pages', '<a class="btn btn-primary btn-mini" href="' . $link . '">' . t('View') . '</a>'));
                    }
                }
            }
        }
        $form->show();
    }

    /**
     * Show page by id
     *
     * @param string $id
     */
    public function showPage($id) {
        $page = new Pages();
        $page->id = $id;
        if ($page->find()) {
            $page->show();
        } else {
            return FALSE;
        }
    }

    /**
     * Get values for form select
     */
    public function getFormSelect($Form){
       $data[] = '';
       $pages = new Pages();
       if($Form->object->count()){
           $this->db->not_like('path',$Form->object->path,'after');
       }
       if($result = $pages->findAll()){
           foreach($result as $page){
               $data[$page->id] = str_repeat('--',$page->level).$page->name;
           }
       }
       return $data;
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
    public function some_action($subaction = NULL) {

    }

}