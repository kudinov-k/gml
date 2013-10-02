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

	/*public function onPrepareSave($value, $record, $type, $section)
	{
		$mask = $this->params->get('params.mask', 0);
		if ($mask->mask_type && $this->params->get('params.show_mask', 1))
		{
			if($value == $mask->mask)
			return;
		}
		$filter = JFilterInput::getInstance();
		return $filter->clean($value);
	}*/

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
		//$this->disable_dates = $this->_getDisabledDates($record);
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
		$query->having('COUNT(b.id) >= '.$this->value['amount']);
		$db->setQuery($query);
		return $db->loadColumn();
	}

	public function updateCart()
	{
		$app = JFactory::getApplication();
		$cart = $app->getUserState('booking_cart', array());
		$amount = $app->input->get('amount', array(), 'array');

		foreach ($amount as $type => $array)
		{
			foreach ($array as $id => $am) {
				$cart[$type][$id] = $am;
			}
		}

		$days = $app->input->get('days', array(), 'array');

		foreach ($days as $type => $array)
		{
			foreach ($array as $id => $am) {
				$cart['time_'.$type][$id] = $am;
			}
		}

		$app->setUserState('booking_cart', $cart);

		//var_dump($amount);exit();

		$app->redirect(base64_decode($app->input->getString('return')));

	}

	public function addToCart($post)
	{
		$app = JFactory::getApplication();
		$cart = $app->getUserState('booking_cart', array());
		//$cart[] = array('record_id' => $post['record_id'], 'dates' => $post['dates']);
		if(!isset($cart[$post['type']][$post['record_id']]))
			$cart[$post['type']][$post['record_id']] = 1;
		else
			$cart[$post['type']][$post['record_id']]++;

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
		//$this->updateCart();


		$params = new JRegistry($app->input->getString('mod_params', ''));

		$cart = $app->getUserState('booking_cart', array());

		if(empty($cart))
			AjaxHelper::send('');

		$c_rent = !empty($cart['rent']) ? array_keys($cart['rent']) : array();
		$c_sale = !empty($cart['sale']) ? array_keys($cart['sale']) : array();
		$c_order = !empty($cart['order']) ? array_keys($cart['order']) : array();
		$cart = array_merge($c_rent, $c_sale, $c_order);

		include_once JPATH_ROOT. DIRECTORY_SEPARATOR .'components'. DIRECTORY_SEPARATOR .'com_cobalt'. DIRECTORY_SEPARATOR .'api.php';
		$api = new CobaltApi();
		$result = $api->records(
		$params->get('section_id'),
		'all',
		$params->get('orderby'),
		0,
		null,
		null,
		500,
		$params->get('tmpl'),
		false,
		false,
		0,
		$cart);

		$content = $result['html'];

		AjaxHelper::send($content);
	}

	public function orderCart()
	{
		$app  = JFactory::getApplication();


		$params = new JRegistry($app->input->getString('mod_params', ''));

		$cart = $app->getUserState('booking_cart', array());

		if(empty($cart) || (empty($cart['rent']) || empty($cart['sale']) || empty($cart['order'])))
			AjaxHelper::send('');

		$c_rent = array_keys($cart['rent']);
		$c_sale = array_keys($cart['sale']);
		$c_order = array_keys($cart['order']);
		$cart = array_merge($c_rent, $c_sale, $c_order);

		include_once JPATH_ROOT. DIRECTORY_SEPARATOR .'components'. DIRECTORY_SEPARATOR .'com_cobalt'. DIRECTORY_SEPARATOR .'api.php';
		$api = new CobaltApi();
		$result = $api->records(
				$params->get('section_id'),
				'all',
				$params->get('orderby'),
				0,
				null,
				null,
				500,
				'cart_mail',
				false,
				false,
				0,
				$cart);

		$content = $result['html'];


		$cart = $app->getUserState('booking_cart', array());
		$body = '';

		$config = JFactory::getConfig();
		$mod_params = new JRegistry($app->input->getString('mod_params', ''));

		$mailer = JFactory::getMailer();
		$mailer->setSubject('Order - '.$config->get('sitename'));
		$mailer->SetFrom($config->get('mailfrom'));
		$mailer->IsHTML(true);
		$mailer->AddAddress($this->params->get('params.email'));
		$mailer->AddAddress($app->input->post->getString('email'));

		$mailer->setBody($content);
		$mailer->Send();

		$app->setUserState('booking_cart', array());
		$app->redirect(base64_decode($app->input->post->getString('return')), 'Заказ успешно отправлен');

		return true;
	}
}
