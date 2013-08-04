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
		if(!isset($cart[$post['record_id']]))
			$cart[$post['record_id']] = 1;
		else
			$cart[$post['record_id']]++;

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
		$cart = array_keys($cart);

		if(empty($cart))
			AjaxHelper::send('');

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
		$cart = $app->getUserState('booking_cart', array());
		$body = '';

		//var_dump($this->params->get('params.email'));exit();

		$config = JFactory::getConfig();
		$mod_params = new JRegistry($app->input->getString('mod_params', ''));

		$mailer = JFactory::getMailer();
		$mailer->setSubject('Order - '.$config->get('sitename'));
		$mailer->SetFrom($config->get('mailfrom'));
		$mailer->IsHTML(true);
		$mailer->AddAddress($this->params->get('params.email'));
		$mailer->AddAddress($app->input->post->getString('email'));

		$model = JModelLegacy::getInstance('Record', 'CobaltModel');

		$body .= '<h2>Заказ</h2>';
		$body .= '<table>';
		$body .= '<tr>';
		$body .= '<th>Название</th>';
		$body .= '<th>Количество</th>';
		$body .= '<th>Цена</th>';
		$body .= '<th>Сумма</th>';
		$body .= '</tr>';


		foreach ($cart as $row)
		{
			//if(in_array($row['record_id'], $r_ids)) continue;

			$record = ItemsStore::getRecord($row);
			$prep_record = $model->_prepareItem($record, 'list');
			$fields = json_decode($record->fields, true);
			$price = $prep_record->fields_by_key[$mod_params->get('price_id')]->value;
			$body .= '<tr>';
			$body .= '<th>'.$record->title.'</th>';
			$body .= '<th>'.$app->input->post->getString('amount'.$record->id).'</th>';
			$body .= '<th>'.$price.'</th>';
			$body .= '<th>'.$price * $app->input->post->getString('amount'.$record->id).'</th>';
			$body .= '</tr>';

		}

		$body .= '</table>';

		$body .= '<table>
				<tr>
					<td>Date In</td><td>'.$app->input->post->getString('datein').'</td>
				</tr>
				<tr>
					<td>Date Out</td><td>'.$app->input->post->getString('dateout').'</td>
				</tr>
				<tr>
					<td>Общая сумма</td><td>'.$app->input->post->getString('total_summary').'</td>
				</tr>
				<tr>
					<td>Name</td><td>'.$app->input->post->getString('name').'</td>
				</tr>
				<tr>
					<td>Email</td><td>'.$app->input->post->getString('email').'</td>
				</tr>
				<tr>
					<td>Telephone</td><td>'.$app->input->post->getString('telephone').'</td>
				</tr>
				</table>';


		$mailer->setBody($body);
		$mailer->Send();


		/*$order = JTable::getInstance('Booking_order', 'CobaltTable');
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


		}*/
		$app->setUserState('booking_cart', array());
		$app->redirect(base64_decode($app->input->post->getString('return')), 'Заказ успешно отправлен');

		return true;
	}
}
