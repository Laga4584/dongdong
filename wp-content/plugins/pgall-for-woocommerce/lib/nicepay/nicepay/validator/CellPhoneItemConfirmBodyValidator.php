<?php
require_once dirname(__FILE__).'/BodyMessageValidator.php';
require_once dirname(__FILE__).'/../exception/ServiceException.php';
require_once dirname(__FILE__).'/../log/LogMode.php';

/**
 * 
 * @author kblee
 *
 */
class CellPhoneItemConfirmBodyValidator implements BodyMessageValidator{
	
	/**
	 * Default constructor
	 */
	public function __construct(){
		
	}
	
	/**
	 * 
	 * @see BodyMessageValidator::validate()
	 */
	public function validate($mdto){

		if($mdto->getParameter(SERVER_INFO) == null || $mdto->getParameter(SERVER_INFO) == ""){
			if(LogMode::isAppLogable()){
				$logJournal = NicePayLogJournal::getInstance();
				$logJournal->writeAppLog("�ŷ�KEY �̼��� �����Դϴ�.");
			}
			throw new ServiceException("VA01","�ŷ�KEY �̼��� �����Դϴ�.");
		}
		
		if($mdto->getParameter(ENCODED_TID) == null || $mdto->getParameter(ENCODED_TID) == ""){
			if(LogMode::isAppLogable()){
				$logJournal = NicePayLogJournal::getInstance();
                                $logJournal->writeAppLog("ENCODE ��üTID �̼��� �����Դϴ�.");
			}
			throw new ServiceException("VA10","ENCODE ��üTID �̼��� �����Դϴ�.");
		}
	}
}
?>
