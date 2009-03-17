<?php

/**
 * sfWidgetFormPlainDate displays plain text date.
 *
 * @package    nahoWidgets
 * @subpackage widget
 * @author     Nicolas Chambrier <naholyr@yahoo.fr>
 * @version    SVN: $Id$
 */
class sfWidgetFormPlainDateTime extends sfWidgetFormPlain
{
  
  /**
   * Adds format_datetime() to the callbacks.
   * 
   * @see sfWidgetFormPlain#configure()
   */
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);
    
    $this->addOption('callbacks', array('format_datetime' => null));
  }
  
}
