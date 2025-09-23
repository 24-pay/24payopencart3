<?php
class ControllerExtensionPaymentTwentyfourpay extends Controller {
  private $error = array();

  public function call(){
    if ($this->config->get('payment_twentyfourpay_test'))
      $installUrl = "https://test.24-pay.eu/pay_gate/install";
    else
      $installUrl = "https://admin.24-pay.eu/pay_gate/install";

    $availableGateways = $this->makePostRequest($installUrl, array(
      'ESHOP_ID' => $this->config->get('payment_twentyfourpay_eshopid'),
      'MID' => $this->config->get('payment_twentyfourpay_mid')
    ));

    echo $availableGateways;
  }

  private function makePostRequest($url,$data){
  	$curl = curl_init();

  	$config = array(
  		CURLOPT_URL => $url,
  		CURLOPT_POST => true,
  		CURLOPT_RETURNTRANSFER => true,
  		CURLOPT_POSTFIELDS => http_build_query($data)
  	);

  	curl_setopt_array($curl, $config);
  	$result = curl_exec($curl);
  	curl_close($curl);
  	return $result;
  }



  public function index() {
    $this->load->language('extension/payment/twentyfourpay');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('setting/setting');

    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
      $this->model_setting_setting->editSetting('payment_twentyfourpay', $this->request->post);

      $this->session->data['success'] = $this->language->get('text_success');

      $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
    }


    $data['heading_title'] = $this->language->get('heading_title');
    $data['text_edit'] = $this->language->get('text_edit');

    $data['text_live_mode'] = $this->language->get('text_live_mode');
    $data['text_test_mode'] = $this->language->get('text_test_mode');
    $data['text_enabled'] = $this->language->get('text_enabled');
    $data['text_disabled'] = $this->language->get('text_disabled');
    $data['text_all_zones'] = $this->language->get('text_all_zones');

    $data['entry_email'] = $this->language->get('entry_email');
    $data['entry_order_status'] = $this->language->get('entry_order_status');
    $data['entry_order_status_completed_text'] = $this->language->get('entry_order_status_completed_text');
    $data['entry_order_status_pending'] = $this->language->get('entry_order_status_pending');
    $data['entry_order_status_canceled'] = $this->language->get('entry_order_status_canceled');
    $data['entry_order_status_failed'] = $this->language->get('entry_order_status_failed');
    $data['entry_order_status_failed_text'] = $this->language->get('entry_order_status_failed_text');
    $data['entry_order_status_processing'] = $this->language->get('entry_order_status_processing');
    $data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
    $data['entry_status'] = $this->language->get('entry_status');
    $data['entry_sort_order'] = $this->language->get('entry_sort_order');
    $data['entry_companyid'] = $this->language->get('entry_companyid');
    $data['entry_companyid_help'] = $this->language->get('entry_companyid_help');
    $data['entry_encyptionkey'] = $this->language->get('entry_encyptionkey');
    $data['entry_encyptionkey_help'] = $this->language->get('entry_encyptionkey_help');
    $data['entry_domain_payment_page'] = $this->language->get('entry_domain_payment_page');
    $data['entry_domain_payment_page_help'] = $this->language->get('entry_domain_payment_page_help');
    $data['entry_payment_type'] = $this->language->get('entry_payment_type');
    $data['entry_payment_type_card'] = $this->language->get('entry_payment_type_card');
    $data['entry_payment_type_halva'] = $this->language->get('entry_payment_type_halva');
    $data['entry_payment_type_erip'] = $this->language->get('entry_payment_type_erip');
    $data['button_save'] = $this->language->get('button_save');
    $data['button_cancel'] = $this->language->get('button_cancel');
    $data['tab_general'] = $this->language->get('tab_general');



    if (isset($this->error['warning'])) {
      $data['error_warning'] = $this->error['warning'];
    } else {
      $data['error_warning'] = '';
    }

    if (isset($this->error['mid'])) {
      $data['error_mid'] = $this->error['mid'];
    } else {
      $data['error_mid'] = '';
    }

    if (isset($this->error['eshop'])) {
      $data['error_eshop'] = $this->error['eshop'];
    } else {
      $data['error_eshop'] = '';
    }

    if (isset($this->error['key'])) {
      $data['error_key'] = $this->error['key'];
    } else {
      $data['error_key'] = '';
    }

    if (isset($this->error['email'])) {
      $data['error_email'] = $this->error['email'];
    } else {
      $data['error_email'] = '';
    }

    $data['breadcrumbs'] = array();

    $data['breadcrumbs'][] = array(
      'text'      => $this->language->get('text_home'),
      'href'      =>  $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
      'separator' => false
    );

    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_extension'),
      'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true)
    );

    $data['breadcrumbs'][] = array(
      'text'      => $this->language->get('heading_title'),
      'href'      => $this->url->link('extension/payment/twentyfourpay', 'user_token=' . $this->session->data['user_token'], true),
      'separator' => ' :: '
    );

    $data['action'] = $this->url->link('extension/payment/twentyfourpay', 'user_token=' . $this->session->data['user_token'], true);

    $data['action_call'] = $this->url->link('extension/payment/twentyfourpay/call', 'user_token=' . $this->session->data['user_token'], true);

    $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token']  . '&type=payment', true);


    if (isset($this->request->post['payment_twentyfourpay_mid'])) {
      $data['payment_twentyfourpay_mid'] = $this->request->post['payment_twentyfourpay_mid'];
    } else {
      $data['payment_twentyfourpay_mid'] = $this->config->get('payment_twentyfourpay_mid');
    }

    if (isset($this->request->post['payment_twentyfourpay_eshopid'])) {
      $data['payment_twentyfourpay_eshopid'] = $this->request->post['payment_twentyfourpay_eshopid'];
    } else {
      $data['payment_twentyfourpay_eshopid'] = $this->config->get('payment_twentyfourpay_eshopid');
    }


    if (isset($this->request->post['payment_twentyfourpay_key'])) {
      $data['payment_twentyfourpay_key'] = $this->request->post['payment_twentyfourpay_key'];
    } else {
      $data['payment_twentyfourpay_key'] = $this->config->get('payment_twentyfourpay_key');
    }

		if (isset($this->request->post['payment_twentyfourpay_test'])) {
			$data['payment_twentyfourpay_test'] = $this->request->post['payment_twentyfourpay_test'];
		} else {
			$data['payment_twentyfourpay_test'] = $this->config->get('payment_twentyfourpay_test');
		}

    if (isset($this->request->post['payment_twentyfourpay_debug'])) {
			$data['payment_twentyfourpay_debug'] = $this->request->post['payment_twentyfourpay_debug'];
		} else {
			$data['payment_twentyfourpay_debug'] = $this->config->get('payment_twentyfourpay_debug');
		}

    if (isset($this->request->post['payment_twentyfourpay_notify_client'])) {
			$data['payment_twentyfourpay_notify_client'] = $this->request->post['payment_twentyfourpay_notify_client'];
		} else {
			$data['payment_twentyfourpay_notify_client'] = $this->config->get('payment_twentyfourpay_notify_client');
		}

    if (isset($this->request->post['payment_twentyfourpay_save_transaction_email'])) {
        $data['payment_twentyfourpay_save_transaction_email'] = $this->request->post['payment_twentyfourpay_save_transaction_email'];
    } else {
        $data['payment_twentyfourpay_save_transaction_email'] = $this->config->get('payment_twentyfourpay_save_transaction_email');
    }

    if (isset($this->request->post['payment_twentyfourpay_cart'])) {
        $data['payment_twentyfourpay_cart'] = $this->request->post['payment_twentyfourpay_cart'];
    } else {
        $data['payment_twentyfourpay_cart'] = $this->config->get('payment_twentyfourpay_cart');
    }

    if (isset($this->request->post['payment_twentyfourpay_direct_apple_pay'])) {
      $data['payment_twentyfourpay_direct_apple_pay'] = $this->request->post['payment_twentyfourpay_direct_apple_pay'];
    } else {
      $data['payment_twentyfourpay_direct_apple_pay'] = $this->config->get('payment_twentyfourpay_direct_apple_pay');
    }

    if (isset($this->request->post['payment_twentyfourpay_direct_google_pay'])) {
      $data['payment_twentyfourpay_direct_google_pay'] = $this->request->post['payment_twentyfourpay_direct_google_pay'];
    } else {
      $data['payment_twentyfourpay_direct_google_pay'] = $this->config->get('payment_twentyfourpay_direct_google_pay');
    }

    if (isset($this->request->post['payment_twentyfourpay_notify'])) {
      $data['payment_twentyfourpay_notify'] = $this->request->post['payment_twentyfourpay_notify'];
    } else {
      $data['payment_twentyfourpay_notify'] = $this->config->get('payment_twentyfourpay_notify');
    }

    if (isset($this->request->post['payment_twentyfourpay_email'])) {
      $data['payment_twentyfourpay_email'] = $this->request->post['payment_twentyfourpay_email'];
    } else {
      $data['payment_twentyfourpay_email'] = $this->config->get('payment_twentyfourpay_email');
    }

    if (isset($this->request->post['payment_twentyfourpay_completed_status_id'])) {
      $data['payment_twentyfourpay_completed_status_id'] = $this->request->post['payment_twentyfourpay_completed_status_id'];
    } else {
      $data['payment_twentyfourpay_completed_status_id'] = $this->config->get('payment_twentyfourpay_completed_status_id');
    }

    if (isset($this->request->post['payment_twentyfourpay_failed_status_id'])) {
      $data['payment_twentyfourpay_failed_status_id'] = $this->request->post['payment_twentyfourpay_failed_status_id'];
    } else {
      $data['payment_twentyfourpay_failed_status_id'] = $this->config->get('payment_twentyfourpay_failed_status_id');
    }

    if (isset($this->request->post['payment_twentyfourpay_pending_status_id'])) {
      $data['payment_twentyfourpay_pending_status_id'] = $this->request->post['payment_twentyfourpay_pending_status_id'];
    } else {
      $data['payment_twentyfourpay_pending_status_id'] = $this->config->get('payment_twentyfourpay_pending_status_id');
    }

    $this->load->model('localisation/order_status');

    $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

    if (isset($this->request->post['payment_twentyfourpay_status'])) {
      $data['payment_twentyfourpay_status'] = $this->request->post['payment_twentyfourpay_status'];
    } else {
      $data['payment_twentyfourpay_status'] = $this->config->get('payment_twentyfourpay_status');
    }

    if (isset($this->request->post['payment_begateway_geo_zone_id'])) {
      $data['payment_begateway_geo_zone_id'] = $this->request->post['payment_begateway_geo_zone_id'];
    } else {
      $data['payment_begateway_geo_zone_id'] = $this->config->get('payment_begateway_geo_zone_id');
    }

    $this->load->model('localisation/geo_zone');

    $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

    if (isset($this->request->post['payment_begateway_sort_order'])) {
      $data['payment_begateway_sort_order'] = $this->request->post['payment_begateway_sort_order'];
    } else {
      $data['payment_begateway_sort_order'] = $this->config->get('payment_begateway_sort_order');
    }

    $data['user_token'] = $this->session->data['user_token'];

    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');

    $this->response->setOutput($this->load->view('extension/payment/twentyfourpay', $data));
  }

  private function validate() {
    if (!$this->user->hasPermission('modify', 'extension/payment/twentyfourpay')) {
      $this->error['warning'] = $this->language->get('error_permission');
    }

    if (!$this->request->post['payment_twentyfourpay_mid']) {
      $this->error['mid'] = $this->language->get('error_mid');
    }

    if (!$this->request->post['payment_twentyfourpay_eshopid']) {
      $this->error['eshop'] = $this->language->get('error_eshopid');
    }

    if (!$this->request->post['payment_twentyfourpay_key']) {
      $this->error['key'] = $this->language->get('error_key');
    }

    if ($this->request->post['payment_twentyfourpay_notify']==1) {
      if (empty($this->request->post['payment_twentyfourpay_email'])) {
        $this->error['email'] = $this->language->get('error_email');
      }
      else{
          $pattern = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i";
          if (!preg_match($pattern, $this->request->post['payment_twentyfourpay_email']))
            $this->error['email'] = $this->language->get('error_email_format');
      }
    }

    return !$this->error;
  }
}
