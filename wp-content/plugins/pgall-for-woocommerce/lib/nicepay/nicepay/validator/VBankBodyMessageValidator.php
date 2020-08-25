<?php
require_once dirname(__FILE__).'/BodyMessageValidator.php';
require_once dirname(__FILE__).'/../exception/ServiceException.php';
require_once dirname(__FILE__).'/../log/LogMode.php';

/**
 * 
 * @author kblee
 *
 */
class VBankBodyMessageValidator implements BodyMessageValidator{
	
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
		// ���������Աݸ�����
		if($mdto->getParameter(VBANK_EXPIRE_DATE) == null || $mdto->getParameter(VBANK_EXPIRE_DATE) == ""){
			if(LogMode::isAppLogable()){
				$logJournal = NicePayLogJournal::getInstance();
				$logJournal->errorAppLog("���������Աݸ����� �̼��� �����Դϴ�.");
			}
			throw new ServiceException("V701","���������Աݸ����� �̼��� �����Դϴ�.");
		}
	}	
}

?>
