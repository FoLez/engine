<?php
/**
 * Created by PhpStorm.
 * User: folez
 * Date: 06.01.2018
 * Time: 21:40
 */

namespace System\Source\Base;


class Model
{
    public $route;

    public function __construct()
    {
    }

    /**
     * Returns the class name to be instantiated, the class file will already be included
     *
     * @param	string 	$filePath		File location of the class (leave an empty string if you've already loaded the main file)
     * @param	string	$className		Name of the class
     * @param	string	$app			Application (defaults to 'core')
     * @param	bool	$supressErrors	If true, will require file with @ operator. Useful for third-party libraries which may throw PHP notices
     * @return	string	Class Name
     */
    public function loadClass( $className, $app='core', $supressErrors=false )
    {
        /* Get the class */
        if( class_exists( $className ) )
        {
            /* Hooks: We have the hook file and the class exists - reset the classname to load */
            $className = $className;
        }

        /* Return Class Name */
        return $className;
    }
}