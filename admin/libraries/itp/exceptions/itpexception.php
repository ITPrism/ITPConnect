<?php
/**
 * @package      ITPrism Libraries
 * @subpackage   ITPrism Exceptions
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2010 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * ITPrism Library is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

// no direct access
defined('_JEXEC') or die();

class ItpException extends Exception {
  
    private $trace    = null;
    private $data     = null;
    
  /**
   * 
   * @param  message[optional]
   * @param  code[optional]
   */
    public function __construct( $message, $code, $data = null, $trace = null ) {
    	
	   parent::__construct ( $message, $code );
	   
        if(!empty($data)) {
            
            if(!is_scalar($data)) {
                $data = var_export($data, true);
            }
            $this->data = $data;
        }
        $this->trace = $trace;
        
        $this->log();
       
    }

    public function log() {
        
        $trace = "";
        foreach($this->trace as $v) {
            $trace .="===================================\n";
			$trace .="FILE:"     . $v['file'] . "\n";
			$trace .="LINE:"     . $v['line'] . "\n";
			$trace .="CLASS:"    . $v['class'] . "\n";
			$trace .="FUNCTION:" . $v['function'] . "\n";
//			$trace .="ARGS:"     . var_export($v['args'], true) . "\n";
			$trace .="====================================\n";
		}
		
		$message = "*****************************************\n";
        $message = "\nFILE : " . $this->getFile()  . "\n";
        $message .= "LINE : " . $this->getLine() . "\n";
        $message .= "CODE : " . $this->getCode() . "\n";
        $message .= "MESSAGE : " . $this->getMessage() . "\n";
        if($this->data) {
            $message .= "EXTRA INFO : " . $this->data . "\n";
        }
        if($trace) {
            $message .= "TRACE : " . $trace . "\n";
        }
        $message = "*****************************************\n";
        
        // get an instance of JLog for myerrors log file
        $log = JLog::getInstance();
        $entry = new  JLogEntry($message, JLog::ALERT);
        $log->add($entry);
        
    }
    
	/**
	 * 
	 */
	public function __destruct() {
	
	}
}