<?php
class ControllerExtensionDashboardSettings extends Controller {
            private $error = array();

            public function index() {

            $this->load->language('extension/dashboard/settings');
            $this->document->setTitle($this->language->get('heading_title'));

            $this->load->model('setting/setting');
            if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
                $this->model_setting_setting->editSetting('dashboard_settings', $this->request->post);
                $this->session->data['success'] = $this->language->get('text_success');
                $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=dashboard', true));
            }

            if (isset($this->error['warning'])) {
                $data['error_warning'] = $this->error['warning'];
            } else {
                $data['error_warning'] = '';
            }

            if (isset($this->request->post['dashboard_settings_width'])) {
                $data['dashboard_settings_width'] = $this->request->post['dashboard_settings_width'];
            } else {
                $data['dashboard_settings_width'] = $this->config->get('dashboard_settings_width');
            }
        
            $data['columns'] = array();
            
            for ($i = 3; $i <= 12; $i++) {
                $data['columns'][] = $i;
            }
                    
            if (isset($this->request->post['dashboard_settings_status'])) {
                $data['dashboard_settings_status'] = $this->request->post['dashboard_settings_status'];
            } else {
                $data['dashboard_settings_status'] = $this->config->get('dashboard_settings_status');
            }
    
            if (isset($this->request->post['dashboard_settings_sort_order'])) {
                $data['dashboard_settings_sort_order'] = $this->request->post['dashboard_settings_sort_order'];
            } else {
                $data['dashboard_settings_sort_order'] = $this->config->get('dashboard_settings_sort_order');
            }
            
            
            $data['heading_title'] = $this->language->get('heading_title');

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
                'href' => $this->url->link('extension/dashboard/settings', 'user_token=' . $this->session->data['user_token'], true)
            );

            $data['action'] = $this->url->link('extension/dashboard/settings', 'user_token=' . $this->session->data['user_token'], true);

            $data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=dashboard', true);

            $data['header'] = $this->load->controller('common/header');
            $data['column_left'] = $this->load->controller('common/column_left');
            $data['footer'] = $this->load->controller('common/footer');
            $this->response->setOutput($this->load->view('extension/dashboard/settings_form', $data));

        }

        protected function validate() {
            /**
             * Check whether the current user has the permissions to modify the settings of the module
             * The permissions are set in System->Users->User groups
             */
            if (!$this->user->hasPermission('modify', 'extension/dashboard/settings')) {
                $this->error['warning'] = $this->language->get('error_permission');
            }

            return !$this->error;
        }

        public function dashboard() {
            $this->load->language('extension/dashboard/settings');
            $data['heading_title'] = $this->language->get('heading_title');
            $data['user_token'] = $this->session->data['user_token'];

            return $this->load->view('extension/dashboard/settings_info', $data);
        }
}
