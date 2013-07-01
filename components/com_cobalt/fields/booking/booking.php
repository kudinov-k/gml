<?php
/**
 * Cobalt by MintJoomla
 * a component for Joomla! 1.7 - 2.5 CMS (http://www.joomla.org)
 * Author Website: http://www.mintjoomla.com/
 * @copyright Copyright (C) 2012 MintJoomla (http://www.mintjoomla.com). All rights reserved.
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 */

defined('_JEXEC') or die();
require_once JPATH_ROOT. DIRECTORY_SEPARATOR .'components/com_cobalt/library/php/fields/cobaltfield.php';

class JFormFieldCBooking extends CFormField
{

	public function getInput()
	{
		return $this->_display_input();
	}

	public function onJSValidate()
	{
		$js = "\n\t\tvar txt{$this->id} = jQuery('[name^=\"jform\\\\[fields\\\\]\\\\[$this->id\\\\]\"]').val();";
		if($this->required)
		{
			$js .= "\n\t\tif(!txt{$this->id}){hfid.push({$this->id}); isValid = false; errorText.push('".addslashes(JText::sprintf("CFIELDREQUIRED", $this->label))."');}";
		}
		return $js;
	}

	public function onPrepareSave($value, $record, $type, $section)
	{
		$mask = $this->params->get('params.mask', 0);
		if ($mask->mask_type && $this->params->get('params.show_mask', 1))
		{
			if($value == $mask->mask)
			return;
		}
		$filter = JFilterInput::getInstance();
		return $filter->clean($value);
	}

	public function onRenderFull($record, $type, $section)
	{
		return $this->_render('full', $record, $type, $section);
	}

	public function onRenderList($record, $type, $section)
	{
		return $this->_render('list', $record, $type, $section);
	}

	private function _render($view, $record, $type, $section)
	{
		$this->disable_dates = $this->_getDisabledDates($record);
		return $this->_display_output($view, $record, $type, $section);
	}

	private function _getDisabledDates($record)
	{
		if(!$this->value) return array();

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('b.date');
		$query->from('#__js_res_booking AS b');
		$query->where('b.date > NOW()');
		$query->where('b.field_id = '.$this->id);
		$query->where('b.record_id = '.$record->id);
		$query->group('b.date');
		$query->having('COUNT(b.id) >= '.$this->value);
		$db->setQuery($query);
		return $db->loadColumn();
	}

	public function addToCart($post)
	{
		$app = JFactory::getApplication();
		$cart = $app->getUserState('booking_cart', array());
		//$cart[] = array('record_id' => $post['record_id'], 'dates' => $post['dates']);
		$cart[] = $post['record_id'];

		$app->setUserState('booking_cart', $cart);

		AjaxHelper::send(1);
	}

	public function removeFromCart($post)
	{
		$index = $post['index'];
		if(is_null($index)) AjaxHelper::send(0);

		$app = JFactory::getApplication();
		$cart = $app->getUserState('booking_cart', array());
		unset($cart[$index]);
		$app->setUserState('booking_cart', $cart);

		AjaxHelper::send(1);
	}

	public function getCart()
	{
		$app	= JFactory::getApplication();
		$rows	= array();
		$r_ids	= array();
		$total_fields = array();

		$params = new JRegistry($app->input->getString('mod_params', ''));

		$cart = $app->getUserState('booking_cart', array());
		$cart = array_unique($cart);

		//var_dump($cart);exit;

		$model = JModelLegacy::getInstance('Record', 'CobaltModel');
		foreach ($cart as $row)
		{
			//if(in_array($row['record_id'], $r_ids)) continue;

			$record = ItemsStore::getRecord($row);
			$rows[$row] = $model->_prepareItem($record, 'list');
			//$r_ids[] = $row['record_id'];

			foreach($rows[$row]->fields_by_id as $field)
			{
				$key = $field->key;
				$total_fields[$key] = $field;
			}
		}

		ob_start();
		include (__DIR__).'/tmpl/cart/default.php';
		$content = ob_get_clean();

		AjaxHelper::send($content);
	}

	public function orderCart()
	{
		$app  = JFactory::getApplication();
		$cart = $app->getUserState('booking_cart', array());

		var_dump($app->input->post);exit();

		$form = $app->input->getString('allFields');
		$form = json_decode($form);

		$order = JTable::getInstance('Booking_order', 'CobaltTable');
		//$order->bind();
		$order->check();
		if($order->store())
		{
			$booking = JTable::getInstance('Booking', 'CobaltTable');

			foreach ($cart as $row)
			{
				$dates = explode(',', $row['dates']);
				foreach ($dates as $date)
				{
					$data['date'] = $date;
					$data['order_id'] = $order->id;
					$data['record_id'] = $row['record_id'];
					$data['field_id'] = $this->id;

					$booking->bind($data);
					$booking->check();
					$booking->store();
					$booking->reset();
					$booking->id = null;
				}
			}
			$app->setUserState('booking_cart', array());


		}
		AjaxHelper::send(1);
	}
}
