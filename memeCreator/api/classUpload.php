<?php


class uploadImage
{
    // error: 0
    // name: "Apple_Rainbow.png"
    // size: 40262
    // tmp_name: "/tmp/phpugJ1ZH"
    // type: "image/png"
    var $error_messages = array(
        1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
        2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
        3 => 'The uploaded file was only partially uploaded',
        4 => 'No file was uploaded',
        6 => 'Missing a temporary folder',
        7 => 'Failed to write file to disk',
        8 => 'A PHP extension stopped the file upload',
        'post_max_size' => 'The uploaded file exceeds the post_max_size directive in php.ini',
        'max_file_size' => 'File is too big',
        'min_file_size' => 'File is too small',
        'accept_file_types' => 'Filetype not allowed',
        'max_number_of_files' => 'Maximum number of files exceeded',
        'max_width' => 'Image exceeds maximum width',
        'min_width' => 'Image requires a minimum width',
        'max_height' => 'Image exceeds maximum height',
        'min_height' => 'Image requires a minimum height',
        'abort' => 'File upload aborted',
        'image_resize' => 'Failed to resize image',
    );

    function __construct($file,$upload_dir=null){

        $this->options['upload_dir'] = dirname($_SERVER['SCRIPT_FILENAME']) . '/uploads/';
        $this->options['fix_filenames'] = true;
        $this->options['replacement_chars'] = [' '=>'_','.'=>'-',"'"=>"",'"'=>""];

        if(!$upload_dir){
            $this->upload_dir = $this->options['upload_dir'];
        }else{
            $this->upload_dir = [dirname($_SERVER['SCRIPT_FILENAME']),$upload_dir];
            $this->upload_dir = implode(DIRECTORY_SEPARATOR,$this->upload_dir);
        }

        $this->error = $file['error'];
        $this->name = $file['name'];
        $this->size = $file['size'];
        $this->tmp_name = $file['tmp_name'];
        $this->type = $file['type'];

    }

    public function doUpload(){

        if($this->error){
            return ['success'=>false,'error'=>$this->error_messages[$this->error]];
        }

        if(!is_dir($this->upload_dir)){
            return ['success'=>false,'error'=>"Dir: '{$this->upload_dir}' is not a directory."];
        }

        $file_size = $this->get_file_size($this->tmp_name);
        $ext = $this->get_file_ext($this->type);
        $name = $this->fix_file_name($this->name,$ext);
        $target = implode(DIRECTORY_SEPARATOR,[$this->upload_dir,$name]);
        
        if(move_uploaded_file($this->tmp_name, $target)){
            if(is_file($target)){
                return ['success'=>true,'file_size'=>$file_size,'ext'=>$ext,'name'=>$name,'type'=>$this->type,'dir'=>$this->upload_dir];
            }

        }
        return ['success'=>false,'file_size'=>$file_size,'ext'=>$ext,'name'=>$name,'type'=>$this->type,'dir'=>$this->upload_dir];
    }

    public function get_file_ext($file_type)
    {
        switch ($file_type) {
            case 'image/jpeg':
            case 'image/jpg':
                return 'jpg';
            case 'image/png':
                return 'png';
            case 'image/gif':
                return 'gif';
            default:
                return '';
        }
    }

    public function get_file_size($file_path, $clear_stat_cache = false)
    {
        if ($clear_stat_cache) {
            if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
                clearstatcache(true, $file_path);
            } else {
                clearstatcache();
            }
        }
        return $this->fix_integer_overflow(filesize($file_path));
    }

    // Fix for overflowing signed 32 bit integers,
    // works for sizes up to 2^32-1 bytes (4 GiB - 1):
    public function fix_integer_overflow($size)
    {
        if ($size < 0) {
            $size += 2.0 * (PHP_INT_MAX + 1);
        }
        return $size;
    }

    public function fix_file_name($name,$ext) {
        $path_parts = pathinfo($name);
        $file_name = $path_parts['filename'];

        // Remove path information and dots around the filename, to prevent uploading
        // into different directories or replacing hidden system files.
        // Also remove control characters and spaces (\x00..\x20) around the filename:
        $file_name = trim(basename(stripslashes($file_name)), ".\x00..\x20");

        $replacement_chars = $this->options['replacement_chars'];
        if (is_array($replacement_chars)) {
        
            foreach($replacement_chars as $old => $new){
                $file_name = str_replace($old,$new,$file_name);
            }
            
            if (!$file_name) {
                $file_name = str_replace('.', '-', microtime(true));
            }

            $name = $file_name.'.'.$ext;
            
        }

        return $name;
    }
}
