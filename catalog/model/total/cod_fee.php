<?php
class ModelTotalCodFee extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		$cft = $this->config->get('cod_fee_total');
		
		if ($this->cart->getTotal() && (empty($cft) || ($this->cart->getTotal() < $cft))) {
			$this->load->language('total/cod_fee');
		 	
			if((isset($this->session->data['payment_method']) && ($this->session->data['payment_method']['code'] == 'cod')) || (isset($this->request->post['payment']) && ($this->request->post['payment']=='cod'))) {
				if($this->config->get('cod_fee_fee_type') == 'f') {
					$fee = $this->config->get('cod_fee_fee');
				} else {
					$min = $this->config->get('cod_fee_fee_min');
					$max = $this->config->get('cod_fee_fee_max');
					$fee = ($this->cart->getTotal() * $this->config->get('cod_fee_fee')) / 100;
					if(!empty($min) && ($fee < $min)) $fee = $min;
					if(!empty($max) && ($fee > $max)) $fee = $max;
				}
				
				$total_data[] = array( 
					'code'       => 'cod_fee',
					'title'      => $this->language->get('text_cod_fee'),
					'text'       => $this->currency->format($fee),
					'value'      => $fee,
					'sort_order' => $this->config->get('cod_fee_sort_order')
				);
				
				if ($this->config->get('cod_fee_tax_class_id')) {
					$tax_rates = $this->tax->getRates($fee, $this->config->get('cod_fee_tax_class_id'));
					
					foreach ($tax_rates as $tax_rate) {
						if (!isset($taxes[$tax_rate['tax_rate_id']])) {
							$taxes[$tax_rate['tax_rate_id']] = $tax_rate['amount'];
						} else {
							$taxes[$tax_rate['tax_rate_id']] += $tax_rate['amount'];
						}
					}
				}
				
				$total += $fee;
			}
		}
	}
}
?>