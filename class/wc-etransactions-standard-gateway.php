<?php

class WC_Etransactions_Standard_Gateway extends WC_Etransactions_Abstract_Gateway {
	protected $defaultTitle = 'E-Transactions payment';
	protected $defaultDesc = 'xxxx';
	protected $type = 'standard';

	public function __construct() {
		// Some properties
		$this->id = 'etransactions_std';
		$this->method_title = __('E-Transactions', WC_ETRANSACTIONS_PLUGIN);
		$this->has_fields = false;
		//$this->icon = TODO;
		//$this->icon              = apply_filters( 'woocommerce_paypal_icon', WC()->plugin_url() . '/assets/images/icons/paypal.png' );

		parent::__construct();
	}
	private function _showDetailRow($label, $value) {
		return '<strong>'.$label.'</strong> '.__($value, WC_ETRANSACTIONS_PLUGIN);
	}

	public function showDetails($order) {
		$orderId = $order->get_id();
		$payment = $this->_etransactions->getOrderPayments($orderId, 'capture');

		if (!empty($payment)) {
			$data = unserialize($payment->data);
			$rows = array();
			$rows[] = $this->_showDetailRow(__('Reference:', WC_ETRANSACTIONS_PLUGIN), $data['reference']);
			if (isset($data['ip'])) {
				$rows[] = $this->_showDetailRow(__('Country of IP:', WC_ETRANSACTIONS_PLUGIN), $data['ip']);
			}
			$rows[] = $this->_showDetailRow(__('Processing date:', WC_ETRANSACTIONS_PLUGIN), preg_replace('/^([0-9]{2})([0-9]{2})([0-9]{4})$/', '$1/$2/$3', $data['date'])." - ".$data['time']);
			if (isset($data['firstNumbers']) && isset($data['lastNumbers'])) {
				$rows[] = $this->_showDetailRow(__('Card numbers:', WC_ETRANSACTIONS_PLUGIN), $data['firstNumbers'].'...'.$data['lastNumbers']);
			}
			if (isset($data['validity'])) {
				$rows[] = $this->_showDetailRow(__('Validity date:', WC_ETRANSACTIONS_PLUGIN), preg_replace('/^([0-9]{2})([0-9]{2})$/', '$2/$1', $data['validity']));
			}
			$rows[] = $this->_showDetailRow(__('Transaction:', WC_ETRANSACTIONS_PLUGIN), $data['transaction']);
			$rows[] = $this->_showDetailRow(__('Call:', WC_ETRANSACTIONS_PLUGIN), $data['call']);
			$rows[] = $this->_showDetailRow(__('Authorization:', WC_ETRANSACTIONS_PLUGIN), $data['authorization']);

			echo '<h4>'.__('Payment information', WC_ETRANSACTIONS_PLUGIN).'</h4>';
			echo '<p>'.implode('<br/>', $rows).'</p>';
		}
	}

}
