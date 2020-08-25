<?php
require_once dirname(__FILE__).'/BodyMessageValidator.php';
require_once dirname(__FILE__).'/../exception/ServiceException.php';
require_once dirname(__FILE__).'/../log/LogMode.php';

/**
 * 
 * @author kblee
 *
 */
class CellPhoneRegItemBodyValidator implements BodyMessageValidator{
	
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
		if($mdto->getParameter(MID) == null || $mdto->getParameter(MID) == ""){
			if(LogMode::isAppLogable()){
				$logJournal = NicePayLogJournal::getInstance();
				$logJournal->errorAppLog("����ID �̼��� �����Դϴ�.");
			}
			throw new ServiceException("V201","����ID �̼��� �����Դϴ�.");
		}
		
		if($mdto->getParameter(GOODS_NAME) == null || $mdto->getParameter(GOODS_NAME) == ""){
			if(LogMode::isAppLogable()){
				$logJournal = NicePayLogJournal::getInstance();
				$logJournal->errorAppLog("��ǰ�� �̼��� �����Դϴ�.");
			}
			throw new ServiceException("V401","��ǰ�� �̼��� �����Դϴ�.");
		}
		
		if($mdto->getParameter(GOODS_AMT) == null || $mdto->getParameter(GOODS_AMT) == ""){
			if(LogMode::isAppLogable()){
				$logJournal = NicePayLogJournal::getInstance();
				$logJournal->errorAppLog("��ǰ�ݾ� �̼��� �����Դϴ�.");
			}
			throw new ServiceException("V402","��ǰ�ݾ� �̼��� �����Դϴ�.");
		}
		
	}
}

?>
