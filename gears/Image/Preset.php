<?php

class Image_Preset extends Core_ArrayObject {

    protected $name;
    protected $image;
    const DIR = '.presets';

    /**
     * Конструктор
     *
     * @param string $name
     * @return boolean
     */
    public function __construct($name) {
        $this->name = $name;
    }

    /**
     * Применяем пресет к изображению
     *
     */
    public function process() {
        if ($this->actions) {
            foreach ($this->actions as $action) {
                $callback = $this->parseAction($action);
                $callback->run();
            }
        }
        return $this;
    }
    /**
     * Преобразуем строку действия в реальный callback
     *
     * @param string $action
     * @return  Callback
     */
    public function parseAction($action){
        $callback = new Callback(array($this->image,'action'));
        preg_match('#^(\w+)\((.+)\)$#',$action,$matches);
        $action = $matches[1];
        $params = explode(',',$matches[2]);
        $args = array_merge($action,$params);
        $callback->setArgs($args);
        return $callback;
    }
    /**
     * Build path
     */
    public function buildPath() {
        $file = $this->image->getFile();
        $dir = dirname($file);
        $filename = basename($file);
        $path = $dir . DS . self::DIR . DS . $this->name. DS . $filename;
        return $path;
    }

    /**
     * Set image
     *
     * @param Image_Object $image
     * @return Image_Preset
     */
    public function image($image){
        if($image instanceof Image_Object){
            $this->image = $image;
        }
        elseif(file_exists($image)){
            $this->image = new Image_Object($image);
        }
        return $this;
    }
    /**
     * Load preset
     *
     * @return  boolean
     */
    public function load() {
        if ($config = config('image.presets.' . $this->name)) {
            $config->size OR $config->size = config('image.preset.default_size', '32x32');
            $this->extend($config);
            return TRUE;
        }
        else {
            error(t('Image preset <b>%s</b> doesn\'t exists.',NULL,$this->name));
        }
        return FALSE;
    }

    /**
     * Save preset
     */
    public function save() {
        cogear()->set('image.presets.' . $this->name, $this->toArray());
    }

    /**
     * Render image with current preset
     *
     * @return  string
     */
    public function render(){
        $preset_image = $this->buildPath();
        if(!file_exists($preset_image) OR filemtime($preset_image) < filemtime($this->image->file)){
            $this->process();
            $this->image->save($preset_image);
        }
        return $preset_image;
    }
}