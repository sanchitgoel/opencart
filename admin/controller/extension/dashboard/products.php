<?php
class ControllerExtensionDashboardProducts extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/dashboard/products');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('dashboard_products', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=dashboard', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=dashboard', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/dashboard/products', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/dashboard/products', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=dashboard', true);

		if (isset($this->request->post['dashboard_products_width'])) {
			$data['dashboard_products_width'] = $this->request->post['dashboard_products_width'];
		} else {
			$data['dashboard_products_width'] = $this->config->get('dashboard_products_width');
		}

		$data['columns'] = array();
		
		for ($i = 3; $i <= 12; $i++) {
			$data['columns'][] = $i;
		}
				
		if (isset($this->request->post['dashboard_products_status'])) {
			$data['dashboard_products_status'] = $this->request->post['dashboard_products_status'];
		} else {
			$data['dashboard_products_status'] = $this->config->get('dashboard_products_status');
		}

		if (isset($this->request->post['dashboard_products_sort_order'])) {
			$data['dashboard_products_sort_order'] = $this->request->post['dashboard_products_sort_order'];
		} else {
			$data['dashboard_products_sort_order'] = $this->config->get('dashboard_products_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/dashboard/products_form', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/dashboard/products')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
		
	public function dashboard() {
		$this->load->language('extension/dashboard/products');
		$data['user_token'] = $this->session->data['user_token'];
        $data['products'] = $this->url->link('catalog/product', 'user_token=' . $this->session->data['user_token'], true);

		return $this->load->view('extension/dashboard/products_info', $data);
	}
}
