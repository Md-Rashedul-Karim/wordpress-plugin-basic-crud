<?php
add_action('wp_ajax_wqnew_entry', 'wqnew_entry_callback_function');
add_action('wp_ajax_nopriv_wqnew_entry', 'wqnew_entry_callback_function');

function get_the_user_ip() {
  if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {  
  //check ip from share internet  
  $ip = $_SERVER['HTTP_CLIENT_IP'];  
  } elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {  
  //to check ip is pass from proxy  
  $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];  
  } else {  
  $ip = $_SERVER['REMOTE_ADDR'];  
  }  
  return $ip;
  
  }

function wqnew_entry_callback_function() {
  global $wpdb;
  $wpdb->get_row( "SELECT * FROM `wp_crud` WHERE `title` = '".$_POST['wqtitle']."' AND `description` = '".$_POST['wqdescription']."' ORDER BY `id` DESC" );
  if($wpdb->num_rows < 1) {
    $wpdb->insert("wp_crud", array(
      "title" => $_POST['wqtitle'],
      "mobile" => $_POST['wqmobile'],
      "ip_address" => get_the_user_ip(),    // $_SERVER['REMOTE_ADDR'],  
      "description" => $_POST['wqdescription'],
      "created_at" => time(),
      "updated_at" => time()
    ));

    $response = array('message'=>'Data Has Inserted Successfully', 'rescode'=>200);
  } else {
    $response = array('message'=>'Data Has Already Exist', 'rescode'=>404);
  }
  echo json_encode($response);
  exit();
  wp_die();
}



add_action('wp_ajax_wqedit_entry', 'wqedit_entry_callback_function');
add_action('wp_ajax_nopriv_wqedit_entry', 'wqedit_entry_callback_function');

function wqedit_entry_callback_function() {
  global $wpdb;
  $wpdb->get_row( "SELECT * FROM `wp_crud` WHERE `title` = '".$_POST['wqtitle']."' AND `description` = '".$_POST['wqdescription']."' AND `id`!='".$_POST['wqentryid']."' ORDER BY `id` DESC" );
  if($wpdb->num_rows < 1) {
    $wpdb->update( "wp_crud", array(
      "title" => $_POST['wqtitle'],
      "mobile" => $_POST['wqmobile'],
      "description" => $_POST['wqdescription'],
      "updated_at" => time()
    ), array('id' => $_POST['wqentryid']) );

    $response = array('message'=>'Data Has Updated Successfully', 'rescode'=>200);
  } else {
    $response = array('message'=>'Data Has Already Exist', 'rescode'=>404);
  }
  echo json_encode($response);
  exit();
  wp_die();
}
