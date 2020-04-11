<?php
/**
 * WP Rest API hook for a simple contact form to email a chosen person.
 * Google Invisible reCAPTCHA support.
 */

class DS_EmailAPI {

}

function contact_email($data) {
  if (empty($data['name']) || empty($data['email']) || empty($data['message'])) {
    return new WP_Error('bad_data', 'You did not properly fill out the form.', array('status' => 400));
  }

  $to = KA_Plugin::get_option('contact_email');
  if (empty($to)) {
    return array('success' => false, 'reason' => 'No email address was set to receive email.');
  }
  $to = sanitize_email($to);

  $subject = '<Contact Form> Message from: ' . sanitize_text_field($data['name']);
  $message = sanitize_textarea_field($data['message']);

  wp_mail($to, $subject, $message);

  $return = array('success' => true, 'input' => array('email' => $data['email'], 'to' => $to, 'subject' => $subject));

  return $return;
}

function contact_add_json() {
  register_rest_route('drewswanner-angular/v1', '/contact', array(
    'methods' => 'POST',
    'callback' => 'contact_email'
  ));
}

add_action('init', 'contact_add_json');

 ?>
