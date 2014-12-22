<?php 
class ControllerTotalCodFee extends Controller { 
	private $error = array(); 
	 
	public function index() { 
		$this->load->language('total/cod_fee');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('cod_fee_total', $this->request->post);
			
			$vqmod_xml_dir = substr_replace(DIR_SYSTEM, '/vqmod/xml/', -8);
			if($this->request->post['cod_fee_status']==0) {
				if(file_exists($vqmod_xml_dir . 'cod_fee_total.xml')) rename($vqmod_xml_dir . 'cod_fee_total.xml', $vqmod_xml_dir . 'cod_fee_total.xml_');
			} else {
				if(file_exists($vqmod_xml_dir . 'cod_fee_total.xml_')) rename($vqmod_xml_dir . 'cod_fee_total.xml_', $vqmod_xml_dir . 'cod_fee_total.xml');
			}
			
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_fixed'] = $this->language->get('text_fixed');
		$this->data['text_perc'] = $this->language->get('text_perc');
		
		$this->data['entry_total'] = $this->language->get('entry_total');
		$this->data['entry_fee'] = $this->language->get('entry_fee');
		$this->data['entry_fee_min'] = $this->language->get('entry_fee_min');
		$this->data['entry_fee_max'] = $this->language->get('entry_fee_max');
		$this->data['entry_fee_type'] = $this->language->get('entry_fee_type');
		$this->data['entry_tax_class'] = $this->language->get('entry_tax_class');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
					
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['fee'])) {
			$this->data['error_fee'] = $this->error['fee'];
		} else {
			$this->data['error_fee'] = '';
		}

   		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),      		
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_total'),
			'href'      => $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('total/cod_fee_total', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('total/cod_fee', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['cod_fee_total'])) {
			$this->data['cod_fee_total'] = $this->request->post['cod_fee_total'];
		} else {
			$this->data['cod_fee_total'] = $this->config->get('cod_fee_total');
		}
		
		if (isset($this->request->post['cod_fee_fee'])) {
			$this->data['cod_fee_fee'] = $this->request->post['cod_fee_fee'];
		} else {
			$this->data['cod_fee_fee'] = $this->config->get('cod_fee_fee');
		}

		if (isset($this->request->post['cod_fee_fee_type'])) {
			$this->data['cod_fee_fee_type'] = $this->request->post['cod_fee_fee_type'];
		} else {
			$this->data['cod_fee_fee_type'] = $this->config->get('cod_fee_fee_type');
		}

		if (isset($this->request->post['cod_fee_fee_min'])) {
			$this->data['cod_fee_fee_min'] = $this->request->post['cod_fee_fee_min'];
		} else {
			$this->data['cod_fee_fee_min'] = $this->config->get('cod_fee_fee_min');
		}

		if (isset($this->request->post['cod_fee_fee_max'])) {
			$this->data['cod_fee_fee_max'] = $this->request->post['cod_fee_fee_max'];
		} else {
			$this->data['cod_fee_fee_max'] = $this->config->get('cod_fee_fee_max');
		}

		if (isset($this->request->post['cod_fee_tax_class_id'])) {
			$this->data['cod_fee_tax_class_id'] = $this->request->post['cod_fee_tax_class_id'];
		} else {
			$this->data['cod_fee_tax_class_id'] = $this->config->get('cod_fee_tax_class_id');
		}
		
		if (isset($this->request->post['cod_fee_status'])) {
			$this->data['cod_fee_status'] = $this->request->post['cod_fee_status'];
		} else {
			$this->data['cod_fee_status'] = $this->config->get('cod_fee_status');
		}

		if (isset($this->request->post['cod_fee_sort_order'])) {
			$this->data['cod_fee_sort_order'] = $this->request->post['cod_fee_sort_order'];
		} else {
			$this->data['cod_fee_sort_order'] = $this->config->get('cod_fee_sort_order');
		}
		
		$this->load->model('localisation/tax_class');
		
		$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		$this->template = 'total/cod_fee.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'total/cod_fee')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['cod_fee_fee']) {
			$this->error['fee'] = $this->language->get('error_fee');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
	
	public function install() {
		$this->db->query("INSERT INTO ". DB_PREFIX . "setting VALUES (NULL,'". (int)$this->config->get('store_admin') ."','cod_fee_total','cod_fee_total','','0')");
		$this->db->query("INSERT INTO ". DB_PREFIX . "setting VALUES (NULL,'". (int)$this->config->get('store_admin') ."','cod_fee_total','cod_fee_fee_type','f','0')");
		$this->db->query("INSERT INTO ". DB_PREFIX . "setting VALUES (NULL,'". (int)$this->config->get('store_admin') ."','cod_fee_total','cod_fee_fee','','0')");
	}

	public function uninstall() {
		$vqmod_xml_dir = substr_replace(DIR_SYSTEM, '/vqmod/xml/', -8);
		rename($vqmod_xml_dir . 'cod_fee_total.xml', $vqmod_xml_dir . 'cod_fee_total.xml_');
		$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `key` = 'cod_fee_total'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `key` = 'cod_fee_fee_type'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE `key` = 'cod_fee_fee'");
	}
}
?>