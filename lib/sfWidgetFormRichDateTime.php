<?php

/**
 * sfWidgetFormRichDateTime is a widget using DynArch's JSCalendar to render a datetime field.
 *
 * @package    nahoWidgets
 * @subpackage widget
 * @author     Nicolas Chambrier <naholyr@yahoo.fr>
 * @version    SVN: $Id$
 */
class sfWidgetFormRichDateTime extends sfWidgetFormDateTime
{
  
  /**
   * Embedded RichDate widget
   * 
   * @var sfWidgetFormRichDate
   */
  protected $date_widget = null;

  /**
   * Options :
   * - jscal_format : defines the way the default widget field and the calender trigger will be displayed.
   * - sf_date_format : format of the date in the input field if using "format = input".
   * - date : options passed to sfWidgetFormRichDate widget
   * @see sfWidgetFormRichDate#configure()
   * 
   * @see lib/vendor/symfony/lib/widget/sfWidgetFormDateTime#configure()
   * @param array $options
   * @param array $attributes
   */
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);
    
    $this->addOption('jscal_format', sfConfig::get('app_jscalendar_format_datetime', '%date% %time% %calendar%'));
    $this->addOption('sf_date_format', sfConfig::get('app_jscalendar_sf_datetime_format', 'dd/MM/yyyy HH:mm:ss'));
  }
  
  /**
   * Returns the date widget.
   *
   * @param  array $attributes  An array of attributes
   * @return sfWidgetFormRichDate A Widget representing the date
   */
  protected function getDateWidget($attributes = array())
  {
    if (is_null($this->date_widget)) {
      $date_options = $this->getOption('date');
      if ($this->getOption('with_time')) {
        if (!isset($date_options['jscal_setup'])) {
          $date_options['jscal_setup'] = array();
        }
        $date_options['jscal_setup']['showsTime'] = true;
      }
      if ($this->getOption('format') == 'input') {
        $date_options['format'] = 'input';
      }
      if (!isset($date_options['sf_date_format'])) {
        $date_options['sf_date_format'] = $this->getOption('sf_date_format');
      }
      $this->setOption('date', $date_options);
      
      $this->date_widget = new sfWidgetFormRichDate($this->getOptionsFor('date'), $this->getAttributesFor('date', $attributes));
    }
    
    return $this->date_widget;
  }
  
  /**
   * @see sfWidgetFormRichDate#getJavascripts()
   * @see lib/vendor/symfony/lib/widget/sfWidget#getJavascripts()
   * @return array
   */
  public function getJavascripts()
  {
    return $this->getDateWidget()->getJavascripts();
  }
  
  /**
   * @see sfWidgetFormRichDate#getStylesheets()
   * @see lib/vendor/symfony/lib/widget/sfWidget#getStylesheets()
   * @return array
   */
  public function getStylesheets()
  {
    return $this->getDateWidget()->getStylesheets();
  }
  
  /**
   * Renders the widget using the embedded date widget, plus the time-specific fields
   * 
   * @see sfWidgetFormRichDate#render()
   * @param string $name
   * @param string $value
   * @param array $attributes
   * @param array $errors
   * @return string
   */
  function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $date_widget = $this->getDateWidget($attributes);
    
    if (!$this->getOption('with_time') || $this->getOption('format') == 'input') {
      return $date_widget->render($name, $value, $attributes, $errors);
    }
    
    return strtr($this->getOption('jscal_format'), array(
      '%date%'     => $date_widget->renderField($name, $value, $attributes, $errors),
      '%time%'     => $this->getTimeWidget($attributes)->render($name, $value),
      '%calendar%' => $date_widget->renderCalendar($name, $value, $attributes, $errors),
    ));
  }
  
}
