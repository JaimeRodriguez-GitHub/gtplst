<?php

//JR
// require_once 'constants.php';
// require_once 'functions.php';
//JR

/*
 * jQuery File Upload Plugin PHP Example
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

error_reporting(E_ALL | E_STRICT);
require('UploadHandler.php');
class CustomUploadHandler extends UploadHandler {
    protected function trim_file_name($file_path, $name, $size, $type, $error, $index, $content_range) {
    		$extension = pathinfo($name , PATHINFO_EXTENSION);
            $name = 'soporte_'.$_GET['pn'].'.' . $extension;
            return $name;
        }

}

$upload_handler = new CustomUploadHandler;
//echo($upload_handler);


    // protected function handle_file_upload($uploaded_file, $name, $size, $type, $error,
    //         $index = null, $content_range = null) {
    //     $file = new \stdClass();
    //     $file->name = $this->get_file_name($uploaded_file, $name, $size, $type, $error,
    //         $index, $content_range);
    //     //$file->name = "44.pdf"
    //     $file->size = $this->fix_integer_overflow((int)$size);
    //     $file->type = $type;
    //     if ($this->validate($uploaded_file, $file, $error, $index)) {
    //         $this->handle_form_data($file, $index);
    //         $upload_dir = $this->get_upload_path();
    //         if (!is_dir($upload_dir)) {
    //             mkdir($upload_dir, $this->options['mkdir_mode'], true);
    //         }
    //         $file_path = $this->get_upload_path($file->name);
    //         $append_file = $content_range && is_file($file_path) &&
    //             $file->size > $this->get_file_size($file_path);
//JR
     // require_once (C_ROOT_DIR.'classes/csPedidos.php');
     // $csDatos = new csPedidos();
     // $rsResult = $csDatos->setDocumentacionSoporte('40',$file->name);
//JR                
    //         if ($uploaded_file && is_uploaded_file($uploaded_file)) {
    //             // multipart/formdata uploads (POST method uploads)
    //             if ($append_file) {
    //                 file_put_contents(
    //                     $file_path,
    //                     fopen($uploaded_file, 'r'),
    //                     FILE_APPEND
    //                 );
    //             } else {
    //                 move_uploaded_file($uploaded_file, $file_path);
    //             }
    //         } else {
    //             // Non-multipart uploads (PUT method support)
    //             file_put_contents(
    //                 $file_path,
    //                 fopen($this->options['input_stream'], 'r'),
    //                 $append_file ? FILE_APPEND : 0
    //             );
    //         }
    //         $file_size = $this->get_file_size($file_path, $append_file);
    //         if ($file_size === $file->size) {
    //             $file->url = $this->get_download_url($file->name);
    //             if ($this->is_valid_image_file($file_path)) {
    //                 $this->handle_image_file($file_path, $file);
    //             }
    //         } else {
    //             $file->size = $file_size;
    //             if (!$content_range && $this->options['discard_aborted_uploads']) {
    //                 unlink($file_path);
    //                 $file->error = $this->get_error_message('abort');
    //             }
    //         }
    //         $this->set_additional_file_properties($file);
    //     }
    //     return $file;
    // }


?>
