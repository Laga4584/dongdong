<?php
require_once dirname(__FILE__).'/../util/KeyUtils.php';
require_once dirname(__FILE__).'/Constants.php';
/**
 * 
 * @author kblee
 *
 */
class HeaderValueSetter {
	
	/**
	 * 
	 */
	function __construct(){
		
	}
	
	/**
	 * 
	 * @param  $paramSet
	 */
	public function fillValue($paramSet){
		
		
		// ���������Ͻ�
		$paramSet->setParameter(EDIT_DATE, date("YmdHis"));
		
		// ��������
		$paramSet->setParameter(LENGTH, "0");
		
		// �ŷ�ID (������������ ���츸 ����, ���Ҽ������� ���� JSP���� ����)
		// [2017.09.07] ������ ��û TID ���뿩�� �߰�
		if ("1" == $paramSet->getParameter(USE_REQUEST_TID)) {
			// ������ ��û TID ����(������ TID ����)
			$logJournal = NicePayLogJournal::getInstance();
			$logJournal->writeAppLog("������ ��û TID: ".$paramSet->getParameter(TID));
		} else if(PAY_SERVICE_CODE == $paramSet->getParameter(SERVICE_MODE)){
			$payMethod = $paramSet->getParameter(PAY_METHOD);
			
			// ���� Ȯ�� �ʿ�!!!! SSG���� �����϶��� TID���� �������� �������� ����
			if($payMethod !== BANK_PAY_METHOD &&  $payMethod !== CELLPHONE_PAY_METHOD){
				$paramSet->setParameter(TID,$this->generateNewTid($paramSet));
				if(LogMode::isAppLogable()){
					$logJournal = NicePayLogJournal::getInstance();
					$logJournal->writeAppLog("���ο� TID ���� : ".$paramSet->getParameter(TID));
				}
			}
		}

		/*if(LogMode::isAppLogable()){
			$logJournal = NicePayLogJournal::getInstance();
			$logJournal->writeAppLog("TID : ".$paramSet->getParameter(TID));
		}*/

		// �����ý��۸�
		$paramSet->setParameter(ERROR_SYSTEM, "MALL");
		
		// �����ڵ�
		$paramSet->setParameter(ERROR_CODE, "00000");
		
		// �����޽���
		// 20180411 [�߿�] ������ ���� ���� �߰��� ���� �ű� ���� �ʵ带 �߰����� �ʰ�, �������������� ErrorMsg �׸��� �̿��ϱ��� ��
		$paramSet->setParameter(ERROR_MSG, $paramSet->getParameter(ERROR_MSG));
	
		return $paramSet;
	}
	
	
	/**
	 * 
	 * @param  $paramSet
	 */
	private function generateNewTid($paramSet){
		$mid = $paramSet->getParameter(MID);
		$payMethod = $paramSet->getParameter(PAY_METHOD);
		$svcCd = "";

		if(CARD_PAY_METHOD == $payMethod){
			$svcCd = SVC_CD_CARD;
		}else if(BANK_PAY_METHOD == $payMethod){
			$svcCd = SVC_CD_BANK;
		}else if(VBANK_PAY_METHOD == $payMethod){
			$svcCd = SVC_CD_VBANK;
		}else if(CELLPHONE_PAY_METHOD == $payMethod){
			$svcCd = SVC_CD_CELLPHONE;
		}else if(CPBILL_PAY_METHOD == $payMethod){
			$svcCd = SVC_CD_CPBILL;
		}else if(VBANK_BULK_PAY_METHOD == $payMethod){
			$svcCd = SVC_CD_VBANK;
		}else if(CASHRCPT_PAY_METHOD == $payMethod){
			$svcCd = SVC_CD_RECEIPT;
		}else if(GIFT_SSG_PAY_METHOD == $payMethod){
			// SSG�Ӵ�
			$svcCd = SVC_CD_GIFT_SSG;
		}else if(BANK_SSG_PAY_METHOD == $payMethod){
			// SSG ��������
			$svcCd = SVC_CD_BANK_SSG;
		}else{
			throw new ServiceException("V005","�������� �ʴ� ���Ҽ����Դϴ�.");
		}
		
		
		return KeyUtils::genTID($mid, $svcCd, SVC_PRDT_CD_ONLINE);
	}
	
}
?>
