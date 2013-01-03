<?php

/**
 * Шестеренка Панели управления
 *
 * @author		Беляев Дмитрий <admin@cogear.ru>
 * @copyright		Copyright (c) 2011, Беляев Дмитрий
 * @license		http://cogear.ru/license.html
 * @link		http://cogear.ru
 */
class Admin_Gear extends Gear {

    protected $access = array(
        'index' => array(1),
    );
    protected $menu;
    public $bc;

    /**
     * Initializer
     */
    public function init() {
        parent::init();
        if (access('Admin')) {
            $this->menu = new Menu_Auto(array(
                        'name' => 'admin',
                        'template' => 'Admin/templates/menu',
                        'render' => 'before',
                    ));
//            parent::loadAssets();
            css($this->folder . DS . 'css' . DS . 'menu.css', 'head');
        }
    }

    /**
     * Load assets - do not load everytime
     */
    public function loadAssets() {

    }

    /**
     * Получение запроса
     */
    public function request() {
        parent::request();
        title(t('Панель управления'));
        $this->bc = new Breadcrumb_Object(
                        array(
                            'name' => 'admin_breadcrumb',
                            'title' => FALSE,
                            'elements' => array(
                                array(
                                    'link' => l('/admin'),
                                    'label' => icon('home') . ' ' . t('Панель управления'),
                                ),
                            ),
                ));
    }

    /**
     * Add Control Panel to user panel
     */
    public function menu($name, $menu) {
        switch ($name) {
            case 'admin':
                $menu->register(array(
                    'label' => icon('home'),
                    'link' => l('/admin'),
                    'active' => check_route('admin', Router::ENDS) OR check_route('admin/dashboard', Router::STARTS),
                    'order' => 0,
                    'elements' => array(
                        array(
                            'link' => l('/admin'),
                            'label' => icon('home') . ' ' . t('Главная'),
                        ),
                        array(
                            'link' => l('/admin/clear/session'),
                            'label' => icon('remove') . ' ' . t('Сбросить сессию'),
                            'order' => '0.1',
                        ),
                        array(
                            'link' => l('/admin/clear/cache'),
                            'label' => icon('trash') . ' ' . t('Сбросить кеш'),
                            'access' => access('Admin'),
                            'order' => '0.2',
                        )
                    ),
                ));
                $menu->register(array(
                    'link' => l('/admin/site'),
                    'label' => icon('inbox') . ' ' . t('Сайт'),
                    'order' => 1000,
                ));
                break;
        }
    }

    /**
     * Обработка запроса
     */
    public function index_action($gear = NULL) {
        if(!$gear){
            return $this->dashboard_action();
        }
        else {
            $args = $this->router->getArgs();
            $gear = ucfirst($args[1]);
            $args = array_slice($args, 2);
            $callback = new Callback(array($this->gears->$gear, 'admin_action'));
            if($callback->check()){
                $callback->run($args);
            }
            else {
                event('404');
            }
        }
    }
    /**
     * Показывает главную страницу панели управления
     */
    public function dashboard_action(){

    }

    /**
     * Cleaner
     *
     * @param type $action
     */
    public function clear_action($action) {
        switch ($action) {
            case 'session':
                $this->session->remove();
                flash_success(t('Сессия сброшена'));
                break;
            case 'cache':
                flash_success(t('Системный кеш сброшен.'));
                $this->system_cache->clear();
                break;
        }
        back();
    }

    /**
     * Site config
     */
    public function site_action() {
        $form = new Form('Admin/forms/site');
        $form->object(array(
            'name' => config('site.name'),
            'url' => config('site.url'),
            'dev' => config('site.development'),
            'date_format' => config('date.format'),
        ));
        if ($result = $form->result()) {
            $result->name && cogear()->site->set('site.name', $result->name);
            $result->dev && cogear()->site->set('site.development', $result->dev);
            success(t('Настройки успешно сохранены!'));
        }
        $form->show();
    }

}
