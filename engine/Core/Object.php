<?php
/**
 *  Simple object
 *
 * @author		Dmitriy Belyaev <admin@cogear.ru>
 * @copyright		Copyright (c) 2011, Dmitriy Belyaev
 * @license		http://cogear.ru/license.html
 * @link		http://cogear.ru
 * @package		Core
 * @subpackage
 * @version		$Id$
 */
abstract class Object extends Adapter {
    public $object;
    /**
     * Constructor
     *
     * @param array $data
     */
    public function __construct($options = NULL,$place = NULL) {
        $this->detach();
        parent::__construct($options, $place);
    }
    /**
     * Set current object
     *
     * @param array|ArrayObject $data
     */
    public function attach($data){
        $this->object = is_object($data) ? $data : Core_ArrayObject::transform($data);
    }

    /**
     * Detach object
     */
    public function detach(){
        return $this->object = new Core_ArrayObject();
    }
}