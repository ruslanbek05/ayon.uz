<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Bp
 * @author     ruslan qodirov <ruslan9832@mail.ru>
 * @copyright  2017 ruslan qodirov
 * @license    paid
 */
// No direct access
defined('_JEXEC') or die;
/**
 * Content Component Category Tree
 *
 * @since  1.6
 */
class BpCategories extends JCategories
{
    /**
     * Class constructor
     *
     * @param   array  $options  Array of options
     *
     * @since   11.1
     */
    public function __construct($options = array())
    {
        $options['table'] = '#__bp_bizplans';
        $options['extension'] = 'com_bp';

        parent::__construct($options);
    }
}
