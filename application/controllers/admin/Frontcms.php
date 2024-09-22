<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Frontcms extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('frontcms_setting_model');
        $this->load->config('ci-blog');
        $this->front_themes = $this->config->item('ci_front_themes');
    }

    function index() {
        if (!$this->rbac->hasPrivilege('front_cms_setting', 'can_view')) {
            access_denied();
        }
        $data['front_themes'] = $this->front_themes;

        $frontcmslist = $this->frontcms_setting_model->get();
        $data['title'] = 'Add Front CMS Setting';
        $data['title_list'] = 'Front CMS Settings';
        $this->session->set_userdata('top_menu', 'System Settings');
        $this->session->set_userdata('sub_menu', 'admin/frontcms/index');
        $data['front_themes'] = $this->front_themes;
        $this->form_validation->set_rules('logo', 'Image', 'callback_handle_upload');
        if ($this->form_validation->run() == TRUE) {
            $frontcms = $this->input->post('is_active_front_cms');
            $sidebar_options = $this->input->post('sidebar_options');
            if (isset($sidebar_options)) {
                $sidebar_options = json_encode($sidebar_options);
            } else {
                $sidebar_options = json_encode(array());
            }
            if (isset($frontcms)) {
                $is_active_front_cms = $frontcms;
            } else {
                $is_active_front_cms = 0;
            }
            $data = array(
                'id' => $this->input->post('id'),
                'contact_us_email' => $this->input->post('contact_us_email'),
                'is_active_front_cms' => $this->input->post('is_active_front_cms'),
                'is_active_rtl' => $this->input->post('is_active_rtl'),
                'is_active_sidebar' => $this->input->post('is_active_sidebar'),
                'theme' => $this->input->post('theme'),
                'complain_form_email' => $this->input->post('complain_form_email'),
                'sidebar_options' => $sidebar_options,
                'google_analytics' => $this->input->post('google_analytics'),
                'footer_text' => $this->input->post('footer_text'),
                'whatsapp_url' => $this->input->post('whatsapp_url'),
                'fb_url' => $this->input->post('fb_url'),
                'twitter_url' => $this->input->post('twitter_url'),
                'youtube_url' => $this->input->post('youtube_url'),
                'google_plus' => $this->input->post('google_plus'),
                'instagram_url' => $this->input->post('instagram_url'),
                'pinterest_url' => $this->input->post('pinterest_url'),
                'linkedin_url' => $this->input->post('linkedin_url'),
                'cookie_consent' => $this->input->post('cookie_consent'),
            );


            if (isset($_FILES["logo"]) && !empty($_FILES["logo"]['name'])) {
                $newLogoName = $this->customlib->uniqueFileName('front_logo-', $_FILES["logo"]['name']);
                $file_info = pathinfo($_FILES["logo"]['name']);
                $file_path = $_FILES["logo"]["tmp_name"];
                $upload_result_logo = upload_to_s3($file_path, $file_info, $newLogoName, 'uploads/school_content/logo/');

                if ($upload_result_logo['success']) {
                    $logoUploaded = true;  // Mark logo as successfully uploaded
                } else {
                    // Add logo error to the errors array
                    $uploadErrors[] = 'Logo upload failed: ' . $upload_result_logo['error'];
                }
                // if ($upload_result_logo['success']) {
                //     $logoUploaded = true;  // Mark logo as successfully uploaded
                // } else {
                //     // Handle logo upload failure
                //     $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Logo upload failed: ' . $upload_result_logo['error'] . '</div>');
                // }
            }

            if (isset($_FILES["fav_icon"]) && !empty($_FILES["fav_icon"]['name'])) {
                $newFavName = uniqid('front_fav_icon-', true) . '.' . strtolower(pathinfo($_FILES["fav_icon"]['name'], PATHINFO_EXTENSION));
                $file_info = pathinfo($_FILES["fav_icon"]['name']);
                $file_path = $_FILES["fav_icon"]["tmp_name"];
                $upload_result_favicon = upload_to_s3($file_path, $file_info, $newFavName, '/uploads/school_content/logo/');

                if ($upload_result_favicon['success']) {
                    $favIconUploaded = true;  // Mark favicon as successfully uploaded
                } else {
                    // Add favicon error to the errors array
                    $uploadErrors[] = 'Favicon upload failed: ' . $upload_result_favicon['error'];
                }
                // if ($upload_result_favicon['success']) {
                //     $favIconUploaded = true;  // Mark favicon as successfully uploaded
                // } else {
                //     // Handle favicon upload failure
                //     $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Favicon upload failed: ' . $upload_result_favicon['error'] . '</div>');
                // }
            }

            // If both uploads failed
            if (!$logoUploaded && !$favIconUploaded) {
                // Show error message for both uploads
                $errorMsg = implode('<br>', $uploadErrors);
                $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">' . $errorMsg . '</div>');
            }
            // If at least one upload was successful
            else if ($logoUploaded || $favIconUploaded) {
                // If there are errors, show the error message but do not update the database
                if (!empty($uploadErrors)) {
                    $errorMsg = implode('<br>', $uploadErrors);
                    $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">' . $errorMsg . '</div>');
                }
                // If no errors, save data and show success message
                else {
                    $this->frontcms_setting_model->add($data);
                    $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
                }
            }

            // if ($upload_result['success']) {
            //
            //   $this->frontcms_setting_model->add($data);
            //   $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            // } else {
            //     $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">' . $upload_result['error'] . '</div>');
            //     // $array = array('success' => false, 'error' => $upload_result['error'], 'message' => 'Upload failed.');
            // }
            redirect('admin/frontcms');
        }


        if (!$frontcmslist) {
            $frontcmslist = new stdClass();
            $frontcmslist->id = 0;
            $frontcmslist->is_active_front_cms = 0;
            $frontcmslist->contact_us_email = '';
            $frontcmslist->is_active_sidebar = '';
            $is_active_front_cms = 0;
            $frontcmslist->google_analytics = '';
            $frontcmslist->logo = '';
            $frontcmslist->fav_icon = '';
            $frontcmslist->sidebar_options = json_encode(array());
            $frontcmslist->is_active_rtl = '';
            $frontcmslist->theme = '';
            $frontcmslist->complain_form_email = '';
            $frontcmslist->footer_text = '';
            $frontcmslist->whatsapp_url = '';
            $frontcmslist->fb_url = '';
            $frontcmslist->twitter_url = '';
            $frontcmslist->youtube_url = '';
            $frontcmslist->google_plus = '';
            $frontcmslist->instagram_url = '';
            $frontcmslist->pinterest_url = '';
            $frontcmslist->linkedin_url = '';
            $frontcmslist->cookie_consent = '';
        }
        $data['frontcmslist'] = $frontcmslist;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/frontcms/index', $data);
        $this->load->view('layout/footer', $data);
    }

    function handle_upload() {
        if (isset($_FILES["logo"]) && !empty($_FILES["logo"]['name'])) {
            $allowedExts = array('jpg', 'jpeg', 'png');
            $temp = explode(".", $_FILES["logo"]["name"]);
            $extension = end($temp);
            if ($_FILES["logo"]["error"] > 0) {
                $error .= "Error opening the file<br />";
            }
            if ($_FILES["logo"]["type"] != 'image/gif' &&
                    $_FILES["logo"]["type"] != 'image/jpeg' &&
                    $_FILES["logo"]["type"] != 'image/png') {
                $this->form_validation->set_message('handle_upload', $this->lang->line('invalid_file_type'));
                return false;
            }
            if (!in_array($extension, $allowedExts)) {
                $this->form_validation->set_message('handle_upload', $this->lang->line('extension_not_allowed'));
                return false;
            }
            if ($_FILES["logo"]["size"] > 204800) {
                $this->form_validation->set_message('handle_upload', $this->lang->line('file_size_shoud_be_less_than'));
                return false;
            }
            return true;
        } else {
            return true;
        }
    }

}
