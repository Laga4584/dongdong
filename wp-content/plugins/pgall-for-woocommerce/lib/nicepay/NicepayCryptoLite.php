<?php
extract($_POST);
extract($_GET);

/*____________________________________________________________
Copyright (C) 2017 NICE IT&T
*
* �ش� ���̺귯���� �����Ͻô°��� ���ι� ���ҿ� ������ �߻��� �� �ֽ��ϴ�.
* ���Ƿ� ������ �ڵ忡 ���� å���� �������� �����ڿ��� ������ �˷��帳�ϴ�.
*
*	@ description		: SSL ���� ������ �����Ѵ�. WEB-API ���� ����
*	@ name				: NicepayCryptoLite.php
*	@ auther			: NICEPAY I&T (tech@nicepay.co.kr)
*	@ date				: 
*	@ modify			
*	
*	2017.07.28			���� �ۼ�
*____________________________________________________________
*/
require_once('NicepayLiteLog.php');
require_once('NicepayLiteCommon.php');

class NicepayCryptoLite
{
	// configuration Parameter
	var $m_NicepayHome;			// �α� ����
	
	// requestPage Parameter
	var $m_EdiDate;				// ó�� �Ͻ�
	var $m_MerchantKey;			// ������ �ο��� ���� Ű
	var $m_Price;				// ���� �ݾ�
	var $m_HashedString;		// �ֿ� ������ hash��
	var $m_VBankExpDate;		// �������� �Ա� ������
	var $m_MerchantServerIp;	// ���� ���� ������
	var $m_UserIp;				// ������ ������
	
	// resultPage Parameter
	var $m_GoodsName;			// ��ǰ��
	var $m_Amt;					// ��ǰ ����
	var $m_Moid;				// ���� �ֹ���ȣ
	var $m_BuyerName;			// ������ �̸�
	var $m_BuyerEmail;			// ������ �̸���
	var $m_BuyerTel;			// ������ ��ȭ��ȣ
	var $m_MallUserID;			// ������ ���� ���̵�
	var $m_MallReserved;		// ���� �����ʵ�
	var $m_GoodsCl;				// ��ǰ ����
	var $m_GoodsCnt;			// ��ǰ ����
	var $m_MID;					// ���� ���̵�
	var $m_MallIP;				// ���� ���� ������ **
	var $m_TrKey;				// ��ȣȭ ������
	var $m_EncryptedData;		// ���� ��ȣȭ ������
	var $m_PayMethod;			// ���� ����
	var $m_TransType;			
	var $m_ActionType;			
	var $m_LicenseKey;
	var $m_EncodeKey;
	
	var $m_ReceiptAmt;			//���ݿ����� �߱� �ݾ� 
	var $m_ReceiptSupplyAmt;	//���ݿ����� ���޾� 
	var $m_ReceiptVAT;			//���ݿ����� �ΰ����� 
	var $m_ReceiptServiceAmt;	//���ݿ����� ���񽺾� 
	var $m_ReceiptType;			//���ݿ����� ����
	var $m_ReceiptTypeNo;		//
	
	// �ΰ���, ������ �� ����
	var $m_ServiceAmt;
	var $m_SupplyAmt;
	var $m_GoodsVat;
	var $m_TaxFreeAmt;
	
	// ARS 
	var $m_ArsAlertShow;
	var $m_ArsReqType;
	
	var $m_CardInterest;
	var $m_ResultCode;			// ���� �ڵ�
	var $m_ResultMsg;			// ���� �޽���
	var $m_ErrorCD;				// ���� �ڵ�
	var $m_ErrorMsg;			// �����޽���
	var $m_AuthDate;			// ���� �ð�
	var $m_AuthCode;			// ���� ��ȣ
	var $m_TID;					// �ŷ� ���̵�
	var $m_CardCode;			// ī�� �ڵ�
	var $m_CardName;			// ���� ī���� �̸�
	var $m_CardNo;				// ī�� ��ȣ
	var $m_CardQuota;			// �Һΰ���	
	var $m_BankCode;			// ���� �ڵ�
	var $m_BankName;			// ���� ���� �̸�
	var $m_Carrier;				// ������ �ڵ�
	var $m_DestAddr;			//
	var $m_VbankBankCode;		// �������� ���� �ڵ�
	var $m_VbankBankName;		// �������� ���� �̸�
	var $m_VbankNum;			// �������� ��ȣ
	
	var $m_charSet;				// ĳ���ͼ�
	
	// ���� ����
	var $m_CancelAmt;			// ���� �ݾ�
	var $m_CancelMsg;			// ���� �޽���
	var $m_CancelPwd;           // ���� �н�����
	var $m_PartialCancelCode; 	// �κ����� �ڵ�

	var $m_ExpDate;				// �Ա� ��������
	var $m_ReqName;				// �Ա���
	var $m_ReqTel;				// �Ա��� ����ó
	
	// ����
	var $m_uri;					// ó�� uri
	var $m_ssl;					// �������� ����
	var $m_queryString = array(); // ���� ��Ʈ��
	var $m_ResultData = array();  // ���� array
	
	// ���� ����
	var $m_BillKey;             // ��Ű
	var $m_ExpYear;             // ī�� ��ȿ�Ⱓ
	var $m_ExpMonth;            // ī�� ��ȿ�Ⱓ
	var $m_IDNo;                // �ֹι�ȣ
	var $m_CardPwd;             // ī�� ���й�ȣ
	var $m_CancelFlg;			// ������û �÷���
	
	var $m_CartType;			// ���ٱ��� ���� �Ǻ� ����
	
	var $m_DeliveryCoNm;		// ���� ��ü
	var $m_InvoiceNum;			// ���� ��ȣ
	var $m_BuyerAddr;			// �������ּ�
	var $m_RegisterName;		// �������̸�
	var $m_BuyerAuthNum;		// �ĺ��� (�ֹι�ȣ)
	var $m_ReqType;				// ��û Ÿ��
	var $m_ConfirmMail;			// �̸��� �߼� ����
	
	var $m_log;					// �α� ���� ����
	var $m_debug;				// �α� Ÿ�� ����
	
	var $m_ReqHost;				// ���� ���� IP
	var $m_ReqPort;				// ���� ���� Port
	var $m_requestPgIp;			// ���μ���IP
	var $m_requestPgPort;		// ���μ���Port
	
	
	
	// �� 4������ ���� �ؾ���.
	// 1. �� �ֿ� �ʵ��� hash ������
	// 2. �������� �Ա��� ���� 
	// 3. ������ IP ����
	// 4. ���� ���� ������ ����
	function requestProcess() {
		// hash ó��
		$this->m_EdiDate = date("YmdHis");
		$str_temp = $this->m_EdiDate.$this->m_MID.$this->m_Price.$this->m_MerchantKey;
		//echo($str_temp);
		$this->m_HashedString = base64_encode( md5($str_temp ));
		
		// �������� �Ա��� ����
		$this->m_VBankExpDate = date("Ymd",strtotime("+3 day",time()));
		
		// ������ IP ����
		$this->m_UserIp = $_SERVER['REMOTE_ADDR'];
		
		// ���� ���������� ����
		$this->m_MerchantServerIp = $_SERVER['SERVER_ADDR'];
	}
	
	// https connection �� �ؼ� ���� ��û�� ��.
	function startAction() {
		if (trim($this->m_ActionType) == "" ) {
			$this->MakeErrorMsg( ERR_WRONG_ACTIONTYPE , "actionType ������ �߸��Ǿ����ϴ�."); 
			return;
		}
		
		// MID�� �����Ѵ�.
		if($this->m_MID == "" || $this->m_MID == NULL) {
			if($this->m_TID == "" || strlen($this->m_TID) != 30) {
				$this->MakeErrorMsg( ERR_MISSING_PARAMETER, "�ʼ� �Ķ�����[MID]�� �����Ǿ����ϴ�.");
				return;
			} else {
				$this->m_MID = substr($this->m_TID, 0,10);
			}
		}
		
		/* 
		 * ������Ű ������ �����̶� ������Ű �ʵ带 �����ϰ� �������ش�.
		 * EncodeKey�� ������ �� ���� ������ ����â���� �ش� �ʵ忡 ���� �����Ͽ� �����ֹǷ�
		 * �������� ��û�� EncodeKey�� �缳�����ִ� ������ �ʿ���.
		 * �ϴ� LicenseKey�θ� ����
		 */
		$this->SetMerchantKey();
		
		$NICELog = new NICELog( $this->m_log, $this->m_debug, $this->m_ActionType );
				
		if(!$NICELog->StartLog($this->m_NicepayHome,$this->m_MID)) 
		{
			$this->MakeErrorMsg( ERR_OPENLOG, "�α������� ������ �����ϴ�."); 
			return;
		}
		
		// ������ ����,
		if (trim($this->m_ActionType) == "CLO" ) {
			// validation
			if(trim($this->m_TID) == "") {
				$this->MakeErrorMsg( ERR_WRONG_PARAMETER, "��û������ �Ķ����Ͱ� �߸��Ǿ����ϴ�. [TID]"); 
				return;
			} else if (trim($this->m_CancelAmt) == "" ) {
				$this->MakeErrorMsg( ERROR_WRONG_PARAMETER, "��û������ �Ķ����Ͱ� �߸��Ǿ����ϴ�. [CancelAmt]"); 
				return;
			} else if (trim($this->m_CancelMsg) == "" ) {
				$this->MakeErrorMsg( ERROR_WRONG_PARAMETER, "��û������ �Ķ����Ͱ� �߸��Ǿ����ϴ�. [CancelMsg]"); 
				return;
			} 
			
			$this->m_uri = "/api/cancelProcessAPI.jsp";
			unset($this->m_queryString);
			
			$this->m_queryString = $_POST;
			$this->m_queryString["MID"]			= $this->m_MID;
			$this->m_queryString["TID"]			= $this->m_TID;
			$this->m_queryString["CancelAmt"]	= $this->m_CancelAmt;
			$this->m_queryString["CancelMsg"]	= $this->m_CancelMsg;
			$this->m_queryString["CancelPwd"]	= $this->m_CancelPwd;
			$this->m_queryString["PartialCancelCode"] = $this->m_PartialCancelCode;
			$this->m_queryString["CartType"]	= $this->m_CartType;
			
			if($this->m_charSet =="UTF8"){
				$this->m_queryString["CancelMsg"] = iconv("UTF-8", "EUC-KR",$this->m_queryString["CancelMsg"]);
			}
		}else {
			// ����
			if(trim($_POST["MID"]) == "") {
				$this->MakeErrorMsg( ERROR_WRONG_PARAMETER, "��û������ �Ķ����Ͱ� �߸��Ǿ����ϴ�. [MID]"); 
				return;
			} else if (trim($_POST["Amt"]) == "" ) {
				$this->MakeErrorMsg( ERROR_WRONG_PARAMETER, "��û������ �Ķ����Ͱ� �߸��Ǿ����ϴ�. [Amt]"); 
				return;
			}
			
			$this->m_uri = "/api/payProcessAPI.jsp";
			unset($this->m_queryString);

			$this->m_queryString = $_POST;
			$this->m_queryString["EncodeKey"] = $this->m_LicenseKey;
			// java lite ����ó�� TID�� �����ϵ��� ����
			$this->m_TID = genTIDNew($this->m_MID, $this->m_PayMethod);
			$this->m_queryString["TID"]  = $this->m_TID;
			
			if($this->m_charSet == "UTF8"){
				$this->m_queryString["BuyerName"] = iconv("UTF-8", "EUC-KR", $this->m_queryString["BuyerName"]);
				$this->m_queryString["GoodsName"] = iconv("UTF-8", "EUC-KR", $this->m_queryString["GoodsName"]);
				$this->m_queryString["BuyerAddr"] = iconv("UTF-8", "EUC-KR", $this->m_queryString["BuyerAddr"]);
				$this->m_queryString["AuthResultMsg"] = iconv("UTF-8", "EUC-KR", $this->m_queryString["AuthResultMsg"]);
			}
		}
		
		// TID �� Ȯ��
		if (isset($this->m_queryString["TID"]) && $this->m_queryString["TID"] != "") {
			$NICELog->WriteLog("TID: ".$this->m_queryString["TID"]);
		} else {
			$NICELog->WriteLog("TID IS EMPTY");
		}
		
		// ���� ������ ����
		if($this->m_ReqHost != "" && $this->m_ReqHost != null) {
			$pos = strpos($this->m_ReqHost, ':');
			if ($pos === true) {
				// ���Ἥ�� �ڿ� Port�� �ٴ� ���� ó��
				list($host, $port) = explode(":", $this->m_ReqHost);
				$this->m_ReqHost = $host;
				$this->m_ReqPort = $port;
			}
			
			$NICELog->WriteLog("ReqHost: ".$this->m_ReqHost.", ReqPort: ".$this->m_ReqPort);
		}
		
		// ���� ���μ��� ����
		if($this->m_requestPgIp != null && $this->m_requestPgIp != "") {
			$this->m_queryString["requestPgIp"]		= $this->m_requestPgIp;
			$this->m_queryString["requestPgPort"]	= $this->m_requestPgPort;
				
			$NICELog->WriteLog("Ư�� IP,Port�� ��û�մϴ�.");
			$NICELog->WriteLog("requestPgIp >> ".$this->m_requestPgIp);
			$NICELog->WriteLog("requestPgIp >> ".$this->m_requestPgPort);
		}
		
		$this->MakeParam($NICELog);
		
		// �̰� �ǹ̰� �����
		$this->m_queryString["LibInfo"]	= getLibInfo();
		
		$httpclient = new HttpClient($this->m_ssl, $this->m_ReqHost, $this->m_ReqPort);
		//connect
		if( !$httpclient->HttpConnect($NICELog) )
		{
			$NICELog->WriteLog('Server Connect Error!!' . $httpclient->getErrorMsg() );
			$resultMsg = $httpclient->getErrorMsg()."���򿬰��� �� ���� �����ϴ�.";
			if( $this->m_ssl == "true" )
			{
				$resultMsg .= "<br>������ ������ SSL������ �������� �ʽ��ϴ�. ����ó�����Ͽ��� m_ssl=false�� �����ϰ� �õ��ϼ���.";
				$this->MakeErrorMsg( ERR_SSLCONN, $resultMsg); 
			}
			else
			{
				$this->MakeErrorMsg( ERR_CONN, $resultMsg); 
			}
			
			$NICELog->CloseNiceLog("");

			return;
		}
		
		//request		
		if( !$httpclient->HttpRequest($this->m_uri, $this->m_queryString, $NICELog) ) {
			// ��û ������ ó��	
			$NICELog->WriteLog('POST Error!!' . $httpclient->getErrorMsg() );
			
			if ($this->doNetCancel($httpclient, $NICELog)) {
				$this->ParseMsg($httpclient->getBody(),$NICELog);
				$NICELog->WriteLog('Net Cancel ResultCode=['.$this->m_ResultData["ResultCode"].'], ResultMsg=['.$this->m_ResultData["ResultMsg"].']');
				$this->MakeErrorMsg(ERR_NO_RESPONSE, "���� ���� ����"); // �� �ڵ尡 ���� ���� ���� �޼����� [2001]���Ҽ��� ���� ������ �� 
			}
			
			$NICELog->CloseNiceLog( $this->m_resultMsg );
			return;
		}
	
		if ( $httpclient->getStatus() == "200" ) {   
		    $this->ParseMsg($httpclient->getBody(),$NICELog);
		    if (isset($this->m_ResultData['TID'])) {
				$NICELog->WriteLog("TID -> "."[".$this->m_ResultData['TID']."]");
		    }
			$NICELog->WriteLog($this->m_ResultData['ResultCode']."[".$this->m_ResultData['ResultMsg']."]");
			$NICELog->CloseNiceLog("");
		}else {
			$NICELog->WriteLog('SERVER CONNECT FAIL:' . $httpclient->getStatus().$httpclient->getErrorMsg().$httpclient->getHeaders() );
			$resultMsg = $httpclient->getStatus()."���򿡷��� �߻��߽��ϴ�.";
			
			//NET CANCEL Start---------------------------------
			if( $httpclient->getStatus() != 200 )
			{
				if ($this->m_PayMethod == "CARD_CAPTURE") {
					// �򵿸����� ���쿡�� ������ ������ ������ ���ҵ��� �ʵ��� ��.
					$this->MakeErrorMsg(ERR_NO_RESPONSE, $resultMsg);
					$NICELog->CloseNiceLog("");
					return;
				}
				
				if ($this->doNetCancel($httpclient, $NICELog)) {
					// ������ ������ ���� body �Ľ� �� ������������ �ڵ��� �����ش�.
					$this->ParseMsg($httpclient->getBody(),$NICELog);
					$NICELog->WriteLog('Net Cancel ResultCode=['.$this->m_ResultData["ResultCode"].'], ResultMsg=['.$this->m_ResultData["ResultMsg"].']');
					$this->MakeErrorMsg( ERR_NO_RESPONSE, $resultMsg); // �� �ڵ尡 ���� ���� ���� �޼����� [2001]���Ҽ��� ���� ������ �� 
				}
			}
			//NET CANCEL End---------------------------------
			$NICELog->CloseNiceLog("");
			return;
		}
	}
	
	function MakeParam($NICELog)
	{
		// 4�� �ʵ� backup
		$mid = $this->m_queryString["MID"];
		$moid = $this->m_queryString["Moid"];
		$ediDate = $this->m_queryString["EdiDate"];
		$encodeType = getIfEmptyDefault($this->m_charSet, "EUC-KR");
		
		if ($encodeType == "UTF8") {
			$encodeType = "UTF-8";
		}
		
		$post_array = array();
		foreach($_POST as $key=>$value)
		{
			if ($encodeType == "EUC-KR") {
				if (has_hangul($value)) {
					$post_array[$key] = iconv($encodeType, "UTF-8", $value);
				} else {
					$post_array[$key] = $value;
				}
			} else {
				$post_array[$key] = $value;
			}
		}
		
		// jsonObj�� ��ȯ (UTF-8)�� ����
		$jsonStr = json_encode($post_array);
		
		// ��ȣȭ
		if (version_compare(phpversion(), '7.1.0', '<')) {
			$data = aesEncrypt($jsonStr, $this->m_LicenseKey);
		} else {
			$data = aesEncryptSSL($jsonStr, $this->m_LicenseKey);
		} 
		
		// WEB-API�� ������ �ʿ��� �� �ʵ� ����
		unset($m_queryString);
		$this->m_queryString["MID"] = $mid;
		$this->m_queryString["Moid"] = $moid;
		$this->m_queryString["EdiDate"] = $ediDate;
		$this->m_queryString["EncodeType"] = $encodeType;
		$this->m_queryString["Data"] = $data;
		
		// �α�
		$NICELog->WriteLog("MakeParam.src MID=".$this->m_queryString["MID"].", Moid=".$this->m_queryString["Moid"].", EdiDate=".$this->m_queryString["EdiDate"].", EncodeType=".$this->m_queryString["EncodeType"].", Data=".$this->m_queryString["Data"]);
	}
	
	// ���� �޽��� ó��
	function MakeErrorMsg($err_code, $err_msg)
	{
		$this->m_ResultCode = $err_code;
		$this->m_ResultMsg = "[".$err_code."][".$err_msg."]";
		$this->m_ResultData["ResultCode"] = $err_code;
		$this->m_ResultData["ResultMsg"] =  $err_msg;
	}
	
	// �����޽��� �Ľ�
	function ParseMsg($result_string,$NICELog) {
		$encodeType = getIfEmptyDefault($this->m_charSet, "EUC-KR");
		
		if ($encodeType == "UTF8") {
			$encodeType = "UTF-8";
		}

		// json_decode�� UTF-8�� �ν��Ѵ�. �ӽ÷� ��ü�� UTF-8�� ������.
		$result_string_utf = iconv("EUC-KR", "UTF-8", $result_string);

		$jsonObj = json_decode($result_string_utf); // ��ü ���� ������ JSON Object�� ��ȯ

		if ($jsonObj->ResultCode == "3001"  // �ſ�ī�� ���� ����
			|| $jsonObj->ResultCode == "4000" // ������ü ���� ����
			|| $jsonObj->ResultCode == "A000" // �޴��� ���� ����
			|| $jsonObj->ResultCode == "4100" // �������� ���� ����
			|| $jsonObj->ResultCode == "2001" // ���Ҽ���
			|| $jsonObj->ResultCode == "0000" // �� �� ���� (����)
			) {

			if (version_compare(phpversion(), '7.1.0', '<')) {
				$jsonDataStr = aesDecrypt($jsonObj->Data, $this->m_LicenseKey);
			} else {
				$jsonDataStr = aesDecryptSSL($jsonObj->Data, $this->m_LicenseKey);
			}

			$jsonDataStr = iconv("EUC-KR", "UTF-8", $jsonDataStr);

			$jsonDataObj = json_decode($jsonDataStr); // ��ȣȭ�� Data �׸��� JSON Object ��ȯ
			
			foreach($jsonDataObj as $key=>$value) {
				if ($encodeType == "EUC-KR") {
					if (has_hangul($value)) {
						$this->m_ResultData[$key] = iconv("UTF-8", $encodeType, $value);
					} else {
						$this->m_ResultData[$key] = $value;
					}
				} else {
					$this->m_ResultData[$key] = $value;
				}
			}
		} else {
			//echo "<BR> ===== ���� ====== <BR>";
		}
		
		$this->m_ResultData["ResultCode"] = $jsonObj->ResultCode;
		$this->m_ResultData["ResultMsg"] = iconv("UTF-8", $encodeType, $jsonObj->ResultMsg);
	}
	
	function SetMerchantKey() {
		if($this->m_MerchantKey != "") {
			$this->m_LicenseKey = $this->m_MerchantKey;
			$this->m_EncodeKey = $this->m_EncodeKey;
		} else if($this->m_LicenseKey != "") {
			$this->m_MerchantKey = $this->m_LicenseKey;
			$this->m_EncodeKey = $this->m_LicenseKey;
		} else if($this->m_EncodeKey != "") {
			$this->m_MerchantKey = $this->m_EncodeKey;
			$this->m_LicenseKey = $this->m_EncodeKey;
			
		}
	}
	
	function doNetCancel($httpclient, $NICELog) {
		if (empty($this->m_TID)) {
			$this->MakeErrorMsg(ERR_WRONG_PARAMETER, "�ʼ���[TID]�� ���� �����Ұ� �Ұ��� �մϴ�. �������� ���� �ٶ��ϴ�.");
			return false;
		}
		
		//NET CANCEL Start---------------------------------
		$NICELog->WriteLog("Net Cancel Start by TID=[".$this->m_TID."]");
	
		// unset �ϱ� ���� ���� �� �����ߴ� �ݾ� backup
		$amt = $this->m_queryString["Amt"];
		
		//Set Field
		$this->m_uri = "/api/cancelProcessAPI.jsp";
		unset($this->m_queryString);
		$this->m_queryString["MID"] = substr($this->m_TID, 0, 10);
		$this->m_queryString["TID"] = $this->m_TID;
		// �������ұݾ��� ���� ����, ���� �ݾ����� ����
		$this->m_queryString["CancelAmt"] = empty($this->m_NetCancelAmt) ? $amt : $this->m_NetCancelAmt;
		$this->m_queryString["CancelMsg"] = "NICE_NET_CANCEL";
		$this->m_queryString["CancelPwd"] = $this->m_NetCancelPW;
		$this->m_queryString["NetCancelCode"] = "1";
		$this->m_queryString["LibInfo"]	= getLibInfo();

		if(!$httpclient->HttpConnect($NICELog))
		{
			$NICELog->WriteLog('Net Cancel Server Connect Error!!' . $httpclient->getErrorMsg() );
			$resultMsg = $httpclient->getErrorMsg()."���򿬰��� �� ���� �����ϴ�.";
			$this->MakeErrorMsg( ERR_CONN, $resultMsg); 
			
			return false;
		}
		if( !$httpclient->HttpRequest($this->m_uri, $this->m_queryString, $NICELog) )
		{
			$NICELog->WriteLog("Net Cancel FAIL" );
			if( $this->m_ActionType == "PYO")
				$this->MakeErrorMsg( ERR_NO_RESPONSE, "���ο��� Ȯ�ο���"); 
			else if( $this->m_ActionType == "CLO")
				$this->MakeErrorMsg( ERR_NO_RESPONSE, "���ҿ��� Ȯ�ο���"); 
			
			return false;
		}
		else
		{
			$NICELog->WriteLog("Net Cancel Request-Response SUCESS" );
		}
		
		return true;
	}
}

?>