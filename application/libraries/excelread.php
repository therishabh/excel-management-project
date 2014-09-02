<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH."/third_party/IOFactory.php"; 
 
class Excelread extends PHPExcel { 
    public function __construct() { 
        parent::__construct(); 
    } 
}