<?php
require_once dirname(__FILE__).'/WebParamGather.php';

/**
 * 
 * @author kblee
 *
 */
class CashReceiptWebParamGather implements WebParamGather{
	
	/**
	 * Default Constructor
	 */
	public function __construct(){
		
	}
	
	/**
	 * 
	 * @see WebParamGather::gather()
	 */
	public function gather($request) {
		$webParam = new WebMessageDTO();

		// �ֹ� ��ȣ,�޴��� ��ȣ �ĺ� ��
		$receiptTypeNo = isset($request["ReceiptTypeNo"]) ? $request["ReceiptTypeNo"] : "";
		$webParam->setParameter(RECEIPT_TYPE_NO,$receiptTypeNo);

		// �ҵ����� ����
		$receiptType = isset($request["ReceiptType"]) ? $request["ReceiptType"] : "";
		$webParam->setParameter(RECEIPT_TYPE, $receiptType);

		// ������
		$receiptServiceAmt = isset($request["ReceiptServiceAmt"]) ? $request["ReceiptServiceAmt"] : "0";
		$webParam->setParameter(RECEIT_SERVICE_AMT, $receiptServiceAmt);

		//�ΰ���ġ��
		$receiptVAT = isset($request["ReceiptVAT"]) ? $request["ReceiptVAT"] : "0";
		$webParam->setParameter(RECEIT_VAT, $receiptVAT);
		
		//�ΰ���ġ��
		$receiptSupplyAmt = isset($request["ReceiptSupplyAmt"]) ? $request["ReceiptSupplyAmt"] : "0";
		$webParam->setParameter(RECEIT_SUPPLY_AMT, $receiptSupplyAmt);

		//���� ������ ��û �ݾ�
		$receiptAmt = isset($request["ReceiptAmt"]) ? $request["ReceiptAmt"] : "0";
		$webParam->setParameter(RECEIPT_AMT, $receiptAmt);
				
		//���� ������ ������ �����ڹ�ȣ
		$receiptSubNum = isset($request["ReceiptSubNum"]) ? $request["ReceiptSubNum"] : "";
		$webParam->setParameter(RECEIT_SUB_NUM, $receiptSubNum);

		//���� ������ ������ ������ ��ȣ
		$receiptSubCoNm = isset($request["ReceiptSubCoNm"]) ? $request["ReceiptSubCoNm"] : "";
		$webParam->setParameter("ReceiptSubCoNm", $receiptSubCoNm);
		
		//���� ������ ������ �����ڸ�
		$receiptSubBossNm = isset($request["ReceiptSubBossNm"]) ? $request["ReceiptSubBossNm"] : "";
		$webParam->setParameter("ReceiptSubBossNm", $receiptSubBossNm);
		
		//���� ������ ������ ������ ��ȭ��ȣ
		$receiptSubTel = isset($request["ReceiptSubTel"]) ? $request["ReceiptSubTel"] : "";
		$webParam->setParameter("ReceiptSubTel", $receiptSubTel);
				
		// �鼼
		$TaxFreeAmt = isset($request["ReceiptTaxFreeAmt"]) ? $request["ReceiptTaxFreeAmt"] : "0";
		$webParam->setParameter(RECEIT_TAXFREE_AMT,$TaxFreeAmt);

		return $webParam;
	}
	
}
?>
