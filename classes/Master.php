<?php
require_once('../config.php');
Class Master extends DBConnection {
	private $settings;
	public function __construct(){
		global $_settings;
		$this->settings = $_settings;
		parent::__construct();
	}
	public function __destruct(){
		parent::__destruct();
	}
	function capture_err(){
		if(!$this->conn->error)
			return false;
		else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
			return json_encode($resp);
			exit;
		}
	}
	function save_department(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `department_list` set {$data} ";
		}else{
			$sql = "UPDATE `department_list` set {$data} where id = '{$id}' ";
		}
		$check = $this->conn->query("SELECT * FROM `department_list` where `name`='{$name}' ".($id > 0 ? " and id != '{$id}'" : ""))->num_rows;
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Department Name Already Exists.";
		}else{
			$save = $this->conn->query($sql);
			if($save){
				$rid = !empty($id) ? $id : $this->conn->insert_id;
				$resp['status'] = 'success';
				if(empty($id))
					$resp['msg'] = "Department details successfully added.";
				else
					$resp['msg'] = "Department details has been updated successfully.";
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = "An error occured.";
				$resp['err'] = $this->conn->error."[{$sql}]";
			}
		}
		if($resp['status'] =='success')
		$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}
	function delete_department(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `department_list` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Department has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function save_curriculum(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k =>$v){
			if(!in_array($k,array('id'))){
				if(!is_numeric($v))
					$v = $this->conn->real_escape_string($v);
				if(!empty($data)) $data .=",";
				$data .= " `{$k}`='{$v}' ";
			}
		}
		if(empty($id)){
			$sql = "INSERT INTO `curriculum_list` set {$data} ";
		}else{
			$sql = "UPDATE `curriculum_list` set {$data} where id = '{$id}' ";
		}
		$check = $this->conn->query("SELECT * FROM `curriculum_list` where `name`='{$name}' and `department_id` = '{department_id}' ".($id > 0 ? " and id != '{$id}'" : ""))->num_rows;
		if($check > 0){
			$resp['status'] = 'failed';
			$resp['msg'] = "Curriculum Name Already Exists.";
		}else{
			$save = $this->conn->query($sql);
			if($save){
				$rid = !empty($id) ? $id : $this->conn->insert_id;
				$resp['status'] = 'success';
				if(empty($id))
					$resp['msg'] = "Curriculum details successfully added.";
				else
					$resp['msg'] = "Curriculum details has been updated successfully.";
			}else{
				$resp['status'] = 'failed';
				$resp['msg'] = "An error occured.";
				$resp['err'] = $this->conn->error."[{$sql}]";
			}
		}
		if($resp['status'] =='success')
		$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}
	function delete_curriculum(){
		extract($_POST);
		$del = $this->conn->query("DELETE FROM `curriculum_list` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Curriculum has been deleted successfully.");
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);

	}
	function save_archive() {
		error_log("save_archive function started");
		
		if (isset($_POST['members'])) {
			$_POST['members'] = htmlentities($_POST['members']);
		}
	
		$_POST['submitted_by'] = $this->settings->userdata('firstname') . " " . $this->settings->userdata('lastname');
		
		extract($_POST);
		$data = "";
	
		// Validate abstract PDF file
		if (isset($_FILES['abstract_pdf']) && !empty($_FILES['abstract_pdf']['tmp_name'])) {
			error_log("Abstract file found");
			$type = mime_content_type($_FILES['abstract_pdf']['tmp_name']);
			if ($type != "application/pdf") {
				$resp['status'] = "failed";
				$resp['msg'] = "Invalid Abstract PDF File Type.";
				return json_encode($resp);
			}
		}
	
		// Validate document PDF file
		if (isset($_FILES['document_pdf']) && !empty($_FILES['document_pdf']['tmp_name'])) {
			$type = mime_content_type($_FILES['document_pdf']['tmp_name']);
			if ($type != "application/pdf") {
				$resp['status'] = "failed";
				$resp['msg'] = "Invalid Document PDF File Type.";
				return json_encode($resp);
			}
		}
	
		// Process data
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id')) && !is_array($_POST[$k])) {
				if (!is_numeric($v)) {
					$v = $this->conn->real_escape_string($v);
				}
				if (!empty($data)) {
					$data .= ",";
				}
				$data .= " `{$k}`='{$v}' ";
			}
		}
	
		if (isset($_POST['curriculum_id']) && !is_numeric($_POST['curriculum_id'])) {
			$resp['status'] = "failed";
			$resp['msg'] = "Invalid Curriculum ID.";
			return json_encode($resp);
		}
	
		// Insert or update data
		if (empty($id)) {
			$sql = "INSERT INTO `archive_list` SET {$data} ";
		} else {
			$sql = "UPDATE `archive_list` SET {$data} WHERE id = '{$id}' ";
		}
	
		error_log("Executing SQL: " . $sql);  // Debug SQL Query
	
		$save = $this->conn->query($sql);
		if ($save) {
			$aid = !empty($id) ? $id : $this->conn->insert_id;
			$resp['status'] = 'success';
			$resp['id'] = $aid;
			$resp['msg'] = empty($id) ? "Archive was successfully submitted" : "Archive details were updated successfully.";
	
			// Process abstract PDF file
			if (isset($_FILES['abstract_pdf']) && $_FILES['abstract_pdf']['tmp_name'] != '') {
				error_log("Processing abstract PDF upload");
				$fname = 'uploads/abstracts/abstract-' . $aid . '.pdf';
				$dir_path = base_app . $fname;
				$upload = $_FILES['abstract_pdf']['tmp_name'];
				$type = mime_content_type($upload);
				$allowed = array('application/pdf');
				if (!in_array($type, $allowed)) {
					$resp['msg'] .= " But Abstract PDF failed to upload due to invalid file type.";
				} else {
					$uploaded = move_uploaded_file($upload, $dir_path);
					if ($uploaded) {
						error_log("Abstract PDF uploaded successfully");
						$this->conn->query("UPDATE archive_list SET `abstract` = CONCAT('{$fname}', '?v=', unix_timestamp(CURRENT_TIMESTAMP)) WHERE id = '{$aid}' ");
					} else {
						error_log("Failed to upload abstract PDF");
					}
				}
			}
	
			// Process document PDF file
			if (isset($_FILES['document_pdf']) && $_FILES['document_pdf']['tmp_name'] != '') {
				$fname = 'uploads/pdf/document-' . $aid . '.pdf';
				$dir_path = base_app . $fname;
				$upload = $_FILES['document_pdf']['tmp_name'];
				$type = mime_content_type($upload);
				$allowed = array('application/pdf');
				if (!in_array($type, $allowed)) {
					$resp['msg'] .= " But Document PDF failed to upload due to invalid file type.";
				} else {
					$uploaded = move_uploaded_file($upload, $dir_path);
					if ($uploaded) {
						$this->conn->query("UPDATE archive_list SET `document_path` = CONCAT('{$fname}', '?v=', unix_timestamp(CURRENT_TIMESTAMP)) WHERE id = '{$aid}' ");
					}
				}
			}
	
		} else {
			$resp['status'] = 'failed'; 
			$resp['msg'] = "An error occurred.";
			$resp['err'] = $this->conn->error . "[{$sql}]";
			error_log("SQL Error: " . $this->conn->error . "[{$sql}]");  // Log SQL error for better debugging
		}
	
		if ($resp['status'] == 'success') {
			$this->settings->set_flashdata('success', $resp['msg']);
		}
	
		error_log("save_archive function ended");
	
		return json_encode($resp);
	}
	
	
	
	function delete_archive(){
		extract($_POST);
		$get = $this->conn->query("SELECT * FROM `archive_list` where id = '{$id}'");
		$del = $this->conn->query("DELETE FROM `archive_list` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"archive Records has deleted successfully.");
			if($get->num_rows > 0){
				$res = $get->fetch_array();
				$banner_path = explode("?",$res['banner_path'])[0];
				$document_path = explode("?",$res['document_path'])[0];
				if(is_file(base_app.$banner_path))
					unlink(base_app.$banner_path);
				if(is_file(base_app.$document_path))
					unlink(base_app.$document_path);
			}
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}

	function update_status(){
		extract($_POST);
		$update = $this->conn->query("UPDATE `archive_list` set status  = '{$status}' where id = '{$id}'");
		if($update){
			$resp['status'] = 'success';
			$resp['msg'] = "Archive status has successfully updated.";
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = "An error occurred. Error: " .$this->conn->error;
		}
		if($resp['status'] =='success')
		$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}

	function save_form() {
	
		$_POST['submitted_by'] = $this->settings->userdata('firstname') . " " . $this->settings->userdata('lastname');
	
		extract($_POST);
		$data = "";
	
		// Validate abstract PDF file
		if (isset($_FILES['forms_pdf']) && !empty($_FILES['forms_pdf']['tmp_name'])) {
			error_log("Abstract file found"); // Debug line
			$type = mime_content_type($_FILES['forms_pdf']['tmp_name']);
			if ($type != "application/pdf") {
				$resp['status'] = "failed";
				$resp['msg'] = "Invalid Abstract PDF File Type.";
				return json_encode($resp);
			}
		} else {
			error_log("Abstract file not found"); // Debug line
		}
	
		// Process data
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id')) && !is_array($_POST[$k])) {
				if (!is_numeric($v)) {
					$v = $this->conn->real_escape_string($v);
				}
				if (!empty($data)) {
					$data .= ",";
				}
				$data .= " `{$k}`='{$v}' ";
			}
		}
	
		// Insert or update data
		if (empty($id)) {
			$sql = "INSERT INTO `forms_list` SET {$data} ";
		} else {
			$sql = "UPDATE `forms_list` SET {$data} WHERE id = '{$id}' ";
		}
	
		$save = $this->conn->query($sql);
		if ($save) {
			$aid = !empty($id) ? $id : $this->conn->insert_id;
			$resp['status'] = 'success';
			$resp['id'] = $aid;
			$resp['msg'] = empty($id) ? "Archive was successfully submitted" : "Archive details were updated successfully.";
	
			// Handle abstract PDF upload
			if (isset($_FILES['forms_pdf']) && $_FILES['forms_pdf']['tmp_name'] != '') {
				error_log("Processing abstract PDF upload"); // Debug line
				$fname = 'uploads/forms/form-'.$aid.'.pdf';
				$dir_path = base_app . $fname;
				$upload = $_FILES['forms_pdf']['tmp_name'];
				$type = mime_content_type($upload);
				$allowed = array('application/pdf');
				if (!in_array($type, $allowed)) {
					$resp['msg'] .= " But Abstract PDF failed to upload due to invalid file type.";
				} else {
					$uploaded = move_uploaded_file($upload, $dir_path);
					if ($uploaded) {
						error_log("Abstract PDF uploaded successfully"); // Debug line
						$this->conn->query("UPDATE forms_list SET `document_path` = CONCAT('{$fname}', '?v=', unix_timestamp(CURRENT_TIMESTAMP)) WHERE id = '{$aid}' ");
					} else {
						error_log("Failed to upload abstract PDF"); // Debug line
					}
				}
			}
	
		} else {
			$resp['status'] = 'failed';
			$resp['msg'] = "An error occurred.";
			$resp['err'] = $this->conn->error . "[{$sql}]";
		}
	
		if ($resp['status'] == 'success') {
			$this->settings->set_flashdata('success', $resp['msg']);
		}
	
		error_log("save_archive function ended"); // Debug line
	
		return json_encode($resp);
	}
		
	function delete_form(){
		extract($_POST);
		$get = $this->conn->query("SELECT * FROM `forms_list` where id = '{$id}'");
		$del = $this->conn->query("DELETE FROM `forms_list` where id = '{$id}'");
		if($del){
			$resp['status'] = 'success';
			$this->settings->set_flashdata('success',"Form Records has deleted successfully.");
			if($get->num_rows > 0){
				$res = $get->fetch_array();
				$document_path = explode("?",$res['document_path'])[0];
				if(is_file(base_app.$document_path))
					unlink(base_app.$document_path);
			}
		}else{
			$resp['status'] = 'failed';
			$resp['error'] = $this->conn->error;
		}
		return json_encode($resp);
	}

	function update_form_status(){
		extract($_POST);
		$update = $this->conn->query("UPDATE `forms_list` set status  = '{$status}' where id = '{$id}'");
		if($update){
			$resp['status'] = 'success';
			$resp['msg'] = "Form status has successfully updated.";
		}else{
			$resp['status'] = 'failed';
			$resp['msg'] = "An error occurred. Error: " .$this->conn->error;
		}
		if($resp['status'] =='success')
		$this->settings->set_flashdata('success',$resp['msg']);
		return json_encode($resp);
	}
}

$Master = new Master();
$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$sysset = new SystemSettings();
switch ($action) {
	case 'save_department':
		echo $Master->save_department();
	break;
	case 'delete_department':
		echo $Master->delete_department();
	break;
	case 'save_curriculum':
		echo $Master->save_curriculum();
	break;
	case 'delete_curriculum':
		echo $Master->delete_curriculum();
	break;
	case 'save_archive':
		echo $Master->save_archive();
	break;
	case 'delete_archive':
		echo $Master->delete_archive();
	break;
	case 'save_form':
		echo $Master->save_form();
	break;
	case 'delete_form':
		echo $Master->delete_form();
	break;
	case 'update_status':
		echo $Master->update_status();
	break;
	case 'update_form_status':
		echo $Master->update_form_status();
	break;
	case 'save_payment':
		echo $Master->save_payment();
	break;
	case 'delete_payment':
		echo $Master->delete_payment();
	break;
	default:
		// echo $sysset->index();
		break;
}