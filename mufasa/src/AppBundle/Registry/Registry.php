<?php

namespace AppBundle\Registry;

class Registry {    
     
    /**
     * @var Singleton The reference to *Singleton* instance of this class
     */
    private static $instance;
    
    /**
     * Returns the *Singleton* instance of this class.
     *
     * @return Singleton The *Singleton* instance.
     */

    public static function setInstance($c) { 
        if (null === static::$instance) {
            static::$instance = $c;
        }        
        
    }

   /**
     * Returns the *Singleton* instance of this class.
     *
     * @return Singleton The *Singleton* instance.
     */
   public static function getInstance() {
       return static::$instance;
   }

   /**
     * Returns the *Singleton* instance of this class.
     *
     * @return Singleton The *Singleton* EM instance.
     */
   public static function getEm() {
       return static::$instance->get('em');
   }

   /**
     * Returns the *Singleton* instance of this class.
     *
     * @return Singleton The *Singleton* Logger instance.
     */
   public static function getLogger() {
       return static::$instance->get('logger');
   }

   

}

