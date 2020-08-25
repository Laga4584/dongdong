<?php
require_once dirname(__FILE__).'/BodyMessageValidator.php';
require_once dirname(__FILE__).'/../exception/ServiceException.php';
require_once dirname(__FILE__).'/../log/LogMode.php';

/**
 * 
 * @author kblee
 *
 */
class CellPhoneBodyMessageValidator implements BodyMessageValidator{
	
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
		/*
		if($mdto->getParameter(SERVER_INFO) == null || $mdto->getParameter(SERVER_INFO) == ""){
			if(LogMode::isAppLogable()){
				$logJournal = NicePayLogJournal::getInstance();
				$logJournal->writeAppLog("�ŷ�KEY �̼��� �����Դϴ�.");
			}
			throw new ServiceException("VA01","�ŷ�KEY �̼��� �����Դϴ�.");
		}
		*/
		
		if($mdto->getParameter(CARRIER) == null || $mdto->getParameter(CARRIER) == ""){
			if(LogMode::isAppLogable()){
				$logJournal = NicePayLogJournal::getInstance();
				$logJournal->writeAppLog("�����籸�� �̼��� �����Դϴ�.");
			}
			throw new ServiceException("VA02","�����籸�� �̼��� �����Դϴ�.");
		}
		
		if($mdto->getParameter(SMS_OTP) == null || $mdto->getParameter(SMS_OTP) == ""){
			if(LogMode::isAppLogable()){
				$logJournal = NicePayLogJournal::getInstance();
				$logJournal->writeAppLog("SMS���ι�ȣ �̼��� �����Դϴ�.");
			}
			throw new ServiceException("VA03","SMS���ι�ȣ �̼��� �����Դϴ�.");
		}
		
		
		if($mdto->getParameter(DST_ADDR) == null || $mdto->getParameter(DST_ADDR) == ""){
			if(LogMode::isAppLogable()){
				$logJournal = NicePayLogJournal::getInstance();
				$logJournal->writeAppLog("�޴�����ȣ �̼��� �����Դϴ�.");
			}
			throw new ServiceException("VA05","�޴�����ȣ �̼��� �����Դϴ�.");
		}
		
		if($mdto->getParameter(GOODS_CL) == null || $mdto->getParameter(GOODS_CL) == ""){
			if(LogMode::isAppLogable()){
				$logJournal = NicePayLogJournal::getInstance();
				$logJournal->writeAppLog("��ǰ�����ڵ� �̼��� �����Դϴ�.");
			}
			throw new ServiceException("VA11","��ǰ�����ڵ� �̼��� �����Դϴ�.");
		}
		
		if($mdto->getParameter(PHONE_ID) == null || $mdto->getParameter(PHONE_ID) == ""){
			if(LogMode::isAppLogable()){
				$logJournal = NicePayLogJournal::getInstance();
				$logJournal->writeAppLog("����PhoneID �̼��� �����Դϴ�.");
			}
			throw new ServiceException("VA12","����PhoneID �̼��� �����Դϴ�.");
		}
		
		if($mdto->getParameter(REC_KEY) == null || $mdto->getParameter(REC_KEY) == ""){
			if(LogMode::isAppLogable()){
				$logJournal = NicePayLogJournal::getInstance();
				$logJournal->writeAppLog("����RecKey �̼��� �����Դϴ�.");
			}
			throw new ServiceException("VA13","����RecKey �̼��� �����Դϴ�.");
		}
	}
}

?>
