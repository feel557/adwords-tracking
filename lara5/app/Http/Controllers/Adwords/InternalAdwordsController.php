<?php namespace App\Http\Controllers\Adwords;
use App\Http\Controllers\BaseController;
use View;
use Input;
use Redirect;
use DB;
use Auth;

use Google\AdsApi\AdWords\AdWordsServices;
use Google\AdsApi\AdWords\AdWordsSession;
use Google\AdsApi\AdWords\AdWordsSessionBuilder;
use Google\AdsApi\AdWords\v201809\cm\CampaignService;
use Google\AdsApi\AdWords\v201809\cm\CampaignCriterionService;
use Google\AdsApi\AdWords\v201809\cm\CampaignCriterionOperation;
use Google\AdsApi\AdWords\v201809\cm\NegativeCampaignCriterion;
use Google\AdsApi\AdWords\v201809\cm\IpBlock;
use Google\AdsApi\AdWords\v201809\cm\OrderBy;
use Google\AdsApi\AdWords\v201809\cm\Paging;
use Google\AdsApi\AdWords\v201809\cm\Selector;
use Google\AdsApi\Common\OAuth2TokenBuilder;
use Google\AdsApi\AdWords\v201809\cm\Operator;
use Google\AdsApi\AdWords\v201809\mcm\CustomerService;
use Google\AdsApi\AdWords\v201809\mcm\ManagedCustomerService;
use Google\AdsApi\AdWords\v201809\mcm\ManagedCustomerLink;
use Google\AdsApi\AdWords\v201809\mcm\LinkOperation;
use Google\AdsApi\AdWords\v201809\mcm\ServiceLink;
use Google\AdsApi\AdWords\v201809\mcm\ServiceLinkLinkStatus;
use Google\AdsApi\AdWords\v201809\mcm\ServiceLinkOperation;
use Google\AdsApi\AdWords\v201809\mcm\ServiceType;
use Google\AdsApi\AdWords\v201809\cm\SortOrder;


class InternalAdwordsController extends BaseController {

	
	function getTest(){
		
		//$session = $this->getMccSession();
		//$this->runExample($session);
		//$res = $this->sendLink($session);
		//$client_session = $this->setAccountAsClient("4027808056");
			//$result = $this->acceptLink($client_session, $serviceLinkId);
			$this->getCampaignListByUser(104);
		//acceptLink($client_session);
		
		
	}
	
	// MCC ID in database MUST BE without dashes 
	
	/*
	
		Adwords oAuth/Session functions
	
	*/

	function getAdwordsSettings(){

		$queryAdwordsSettings = DB::table('_adwords_settings')
		->take(1)
		->get();

		$arrayAdwordsSettings = (array)$queryAdwordsSettings[0];
		// $clientAuthIniPath = "adwordsUserIniAuthFiles/";
		return $arrayAdwordsSettings;

	}

	function getMccSession(){
		
		$config_ini = config_path() . '/ads/ads_props.ini';
		
		$oAuth2Credential = (new OAuth2TokenBuilder())
			->fromFile($config_ini)
			->build();

		$session = (new AdWordsSessionBuilder())
			->fromFile($config_ini)
			->withOAuth2Credential($oAuth2Credential)
			->build();

		return $session;
		
    }
	
	
	function setAccountAsClient($clientCustomerId){

		$arrayAdwordsSettings = $this->getAdwordsSettings();

		$ini_file_client = public_path() . $arrayAdwordsSettings['client_auth_ini_path'] . uniqid() . time() . "-auth.ini";
		$current = "";
		file_put_contents($ini_file_client, $current);

		$queryClientData = DB::table('_adwords_users')
			->where("adwords_user_id", "=", $clientCustomerId)
			->get();

		if(count($queryClientData) > 0){

			$array = array(
				'ADWORDS' => array(
					'developerToken' => $arrayAdwordsSettings['developerToken'],
					'userAgent' => $arrayAdwordsSettings['userAgent'],
					'clientCustomerId' => "$clientCustomerId"
				),
				'OAUTH2' => array(
					'clientId' => $arrayAdwordsSettings['client_id'],
					'clientSecret' => $arrayAdwordsSettings['client_secret'],
					'refreshToken' => $queryClientData[0]->adwords_refresh_token
				)
			);

			$this->write_php_ini($array, $ini_file_client);

			$oAuth2Credential = (new OAuth2TokenBuilder())
			->fromFile($ini_file_client)
			->build();

			$session = (new AdWordsSessionBuilder())
				->fromFile($ini_file_client)
				->withOAuth2Credential($oAuth2Credential)
				->build();

			return $session;

		}

	}

	
	function setSessionWithIdAndRefreshToken($clientCustomerId, $refresh_token){

		$arrayAdwordsSettings = $this->getAdwordsSettings();

		$ini_file_client = public_path() . $arrayAdwordsSettings['client_auth_ini_path'] . uniqid() . time() . "-auth.ini";
		$current = "";
		file_put_contents($ini_file_client, $current);


			$array = array(
				'ADWORDS' => array(
					'developerToken' => $arrayAdwordsSettings['developerToken'],
					'userAgent' => $arrayAdwordsSettings['userAgent'],
					'clientCustomerId' => strval($clientCustomerId)
				),
				'OAUTH2' => array(
					'clientId' => $arrayAdwordsSettings['client_id'],
					'clientSecret' => $arrayAdwordsSettings['client_secret'],
					'refreshToken' => $refresh_token
				)
			);

			$this->write_php_ini($array, $ini_file_client);

			$oAuth2Credential = (new OAuth2TokenBuilder())
			->fromFile($ini_file_client)
			->build();

			$session = (new AdWordsSessionBuilder())
				->fromFile($ini_file_client)
				->withOAuth2Credential($oAuth2Credential)
				->build();

			return $session;

		

	}
	
	




	/*
		Creates connection between accounts
	*/	

	function setLinkBetweenAccounts($clientCustomerId){

		try{
				
			$mcc_session = $this->getMccSession();
			$this->sendLink($mcc_session, $clientCustomerId);
			$client_session = $this->setAccountAsClient($clientCustomerId);
			$this->acceptLink($client_session, $clientCustomerId);

		}catch(\Throwable $t){
			
			echo $t->getMessage();
			
		}
		catch(\Exception $e){
			
			echo $e->getMessage();
			
		}

		return 1;

	}


    function sendLink($session, $clientCustomerId){
		
		$arrayAdwordsSettings = $this->getAdwordsSettings();
		$adWordsServices = new AdWordsServices();
		
		try{	
							
			$managedCustomerService = $adWordsServices->get($session, ManagedCustomerService::class);
			$operations = [];
			$linkOp = new LinkOperation();
			$link = new ManagedCustomerLink();
			$link->setManagerCustomerId($arrayAdwordsSettings['managerClientCustomerId']);
			$link->setClientCustomerId($clientCustomerId); 
			$link->setLinkStatus("PENDING");
			$linkOp->setOperand($link);
			$linkOp->setOperator(Operator::ADD);
			$operations[] = $linkOp;
			
			var_dump($operations);

			$result = $managedCustomerService->mutateLink($operations);						
						
		}catch(\Throwable $t){
			
			echo $t->getMessage();
			
		}catch(\Exception $e){
			
			echo $e->getMessage();
			
		}				
		
		return 1;		
				
	}
	
	function acceptLink($session, $clientCustomerId){
			
		$arrayAdwordsSettings = $this->getAdwordsSettings();
		$adWordsServices = new AdWordsServices();
		
		try{
						
			$managedCustomerService = $adWordsServices->get($session, ManagedCustomerService::class);
			$operations = [];
			$linkOp = new LinkOperation();
			$link = new ManagedCustomerLink();
			$link->setManagerCustomerId($arrayAdwordsSettings['managerClientCustomerId']);
			$link->setClientCustomerId($clientCustomerId); 
			$link->setLinkStatus("ACTIVE");
			$linkOp->setOperand($link);
			$linkOp->setOperator(Operator::SET);
			$operations[] = $linkOp;

			$result = $managedCustomerService->mutateLink($operations);
					
		}catch(\Throwable $t){
			
			echo $t->getMessage();
			
		}
		catch(\Exception $e){
			
			echo $e->getMessage();
			
		}
		
		return 1;
		
	}
	
	

	function deleteLinkBetweenAccounts($clientCustomerId){

		$arrayAdwordsSettings = $this->getAdwordsSettings();
		$client_session = $this->setAccountAsClient($clientCustomerId);
		$adWordsServices = new AdWordsServices();
		
		try{
						
			$managedCustomerService = $adWordsServices->get($client_session, ManagedCustomerService::class);
			$operations = [];
			$linkOp = new LinkOperation();
			$link = new ManagedCustomerLink();
			$link->setManagerCustomerId($arrayAdwordsSettings['managerClientCustomerId']);
			$link->setClientCustomerId($clientCustomerId); 
			$link->setLinkStatus("INACTIVE");
			$linkOp->setOperand($link);
			$linkOp->setOperator(Operator::SET);
			$operations[] = $linkOp;

			$result = $managedCustomerService->mutateLink($operations);
					
		}catch(\Throwable $t){
			
			echo $t->getMessage();
			
		}
		catch(\Exception $e){
			
			echo $e->getMessage();
			
		}
		
		return 1;
		

	}



	/*

		IP BLOCK

	*/
	
	
	public function blockIPAdwordsFunction($campaignId, $ipAddress, $trackerUserId){

		$errors = "";
		$adWordsServices = new AdWordsServices();
        //$internalAdwordsController = new InternalAdwordsController();
		$arrayAdwordsSettings = $this->getAdwordsSettings();
		

		if(isset($campaignId)){
			
			// Realization Adwords IP Block
			$error_message;

			// 1. Construct Adwords User Class
			$queryAdwordsUser = DB::table('_adwords_users')
				->where('internal_user_id', '=', $trackerUserId)
				->get();

			if(isset($queryAdwordsUser[0]) && count($queryAdwordsUser[0]) > 0){

				$arrayAdwordsUser = (array)$queryAdwordsUser[0];

				// 2.1. Block on account Level
				if( $campaignId == "account" ){

					$queryCampaign = DB::table('_adwords_campaigns')
						->where('manager_adwords_id', '=', $arrayAdwordsUser['adwords_user_id'])
						->get();

					if(isset($queryCampaign[0]) && count($queryCampaign[0]) > 0){

						$arrayCampaign = (array)$queryCampaign[0];
						$campaignUser = $arrayCampaign['adwords_user_id'];
						$campaignManager = $arrayCampaign['manager_adwords_id'];

						$session = $this->setSessionWithIdAndRefreshToken($campaignUser, $arrayAdwordsUser['adwords_refresh_token']);
						
						$campaignCriterionService = $adWordsServices->get($session, CampaignCriterionService::class);
						
						// Generate operations.
						$operations = array();

						foreach ($queryCampaign as $campaignItem) {

							$operation = $this->adwordsParametersForBlockIp($campaignId, $ipAddress);
							$operations[] = $operation;

						}

						$campaignCriterionService->mutate($operations);

						return 1;

					}
					
				}



				// 2.2. Block IP on campaign Level
				if( $campaignId != 0 && $campaignId != "account" ){
					
					$queryCampaign = DB::table('_adwords_campaigns')
						->where('adwords_campaign_id', '=', $campaignId)
						->get();

					if(isset($queryCampaign[0]) && count($queryCampaign[0]) > 0){
						
						$arrayCampaign = (array)$queryCampaign[0];

						$campaignUser = $arrayCampaign['adwords_user_id'];
						$campaignManager = $arrayCampaign['manager_adwords_id'];

						$session = $this->setSessionWithIdAndRefreshToken($campaignUser, $arrayAdwordsUser['adwords_refresh_token']);
						
						$campaignCriterionService = $adWordsServices->get($session, CampaignCriterionService::class);

						$operation = $this->adwordsParametersForBlockIp($campaignId, $ipAddress);

						// Update campaign criteria.
						$results = $campaignCriterionService->mutate(array($operation));
						//exit();
						return 1;

					}

				}

			}else{

				$errors = "User with AuthId not exist";
				return $errors;

			}
		
		}else{

			$errors = "CampaignId not exist";
			return $errors;

		}
		
	}

	function adwordsParametersForBlockIp($campaignId, $ipAddress){

		$ipBlock = new IpBlock();
		$ipBlock->setIpAddress($ipAddress);
		$negativeCriterion = new NegativeCampaignCriterion();
		$negativeCriterion->setCampaignId($campaignId);
		$negativeCriterion->setCriterion($ipBlock);

		$operation = new CampaignCriterionOperation();
		$operation->setOperator(Operator::ADD);
		$operation->setOperand($negativeCriterion);
		
		return $operation;
		
	}


	
	
	
	
	/*
		Additional functions
	*/	


	function getAdwordsClientId($refresh_token){

		$arrayAdwordsSettings = $this->getAdwordsSettings();

		$ini_file_client = public_path() . $arrayAdwordsSettings['client_auth_ini_path'] . uniqid() . time() . "-auth.ini";
		$current = "";
		file_put_contents($ini_file_client, $current);

		$array = array(
			'ADWORDS' => array(
					'developerToken' => $arrayAdwordsSettings['developerToken'],
					'userAgent' => $arrayAdwordsSettings['userAgent'],
					//'clientCustomerId' => "$clientCustomerId"
				),
			'OAUTH2' => array(
				'clientId' => $arrayAdwordsSettings['client_id'],
				'clientSecret' => $arrayAdwordsSettings['client_secret'],
				'refreshToken' => $refresh_token
			)
		);

		$this->write_php_ini($array, $ini_file_client);

			$oAuth2Credential = (new OAuth2TokenBuilder())
			->fromFile($ini_file_client)
			->build();

			$session = (new AdWordsSessionBuilder())
				->fromFile($ini_file_client)
				->withOAuth2Credential($oAuth2Credential)
				->build();

		//
		$adWordsServices = new AdWordsServices();
		
		$customerService = $adWordsServices->get($session, CustomerService::class);
		$result = $customerService->getCustomers();

		$customer = $result[0]->getCustomerId();
		$customerName = $result[0]->getDescriptiveName();
		
		//var_dump($customer);
		//var_dump($customerName);
		//exit();
		
		if(isset($refresh_token) && $refresh_token!=''){

			//check if this use already exist in db
			// add to database

			$myDbCustomerId = trim($customer);

			$query = DB::table('_adwords_users')
				->where('adwords_user_id', '=', $myDbCustomerId)
				->get();

			if( count($query)>0 ){

				$query = DB::table('_adwords_users')
					->where('adwords_user_id', '=', $myDbCustomerId)
					->update(array(
						'adwords_refresh_token' => $refresh_token
						)
					);

			}else{

				$query = DB::table('_adwords_users')
					->insert(array(
						'adwords_name' => $customerName, 
						'adwords_refresh_token' => $refresh_token, 
						'adwords_user_id' => $myDbCustomerId, 
						'internal_user_id' => Auth::user()->id
						)
					);

			}

			return $customer;

		}

	}





	function getCampaignListByUser($internalUserId){

		$arrayAdwordsSettings = $this->getAdwordsSettings();

		$queryAdwordsUser = DB::table('_adwords_users')
			->where('internal_user_id', '=', $internalUserId)
			->get();

		if(count($queryAdwordsUser)>0){

			$session = $this->setAccountAsClient($queryAdwordsUser[0]->adwords_user_id);

			$this->getCampaigns($session, $queryAdwordsUser[0]->adwords_user_id);

		}

	}



	function getCampaigns($session, $managerId) {

		$adWordsServices = new AdWordsServices();
		
		$managedCustomerService = $adWordsServices->get($session, ManagedCustomerService::class);

		//$managedCustomerService = $user->GetService('ManagedCustomerService');

		// Create selector.
		$selector = new Selector();
		$selector->setFields(['CustomerId', 'Name', 'CanManageClients']);
		//$selector->setOrdering([new OrderBy('CustomerId', SortOrder::ASCENDING)]);
		//$selector->setPaging(new Paging(0, 500));

		// Make the get request.
		$graph = $managedCustomerService->get($selector);
		
		
		
		$i=0;
		$workingAdwordsUser = array();
		foreach($graph->getEntries() as $adwordsUser){
			
			//var_dump($adwordsUser->);
			//echo "<br>";
			
			if($adwordsUser->getCanManageClients() == false){
				$workingAdwordsUser[$i]["id"] = $adwordsUser->getCustomerId();
				$workingAdwordsUser[$i]["name"] = $adwordsUser->getName();
				$i++;
			}
		}
		
		
	//	var_dump($workingAdwordsUser);
//exit();

		foreach($workingAdwordsUser as $customerAdwordsId){

			//$user->SetClientCustomerId($customerAdwordsId['id']);
			
			//$session = $this->setAccountAsClient($customerAdwordsId['id']);
			
			//---
			
			
		$arrayAdwordsSettings = $this->getAdwordsSettings();

		$ini_file_client = public_path() . $arrayAdwordsSettings['client_auth_ini_path'] . uniqid() . time() . "-auth.ini";
		$current = "";
		file_put_contents($ini_file_client, $current);

		$queryClientData = DB::table('_adwords_users')
			->where("adwords_user_id", "=", $managerId)
			->get();

			$array = array(
				'ADWORDS' => array(
					'developerToken' => $arrayAdwordsSettings['developerToken'],
					'userAgent' => $arrayAdwordsSettings['userAgent'],
					'clientCustomerId' => strval($customerAdwordsId['id'])
				),
				'OAUTH2' => array(
					'clientId' => $arrayAdwordsSettings['client_id'],
					'clientSecret' => $arrayAdwordsSettings['client_secret'],
					'refreshToken' => $queryClientData[0]->adwords_refresh_token
				)
			);

			$this->write_php_ini($array, $ini_file_client);

			$oAuth2Credential = (new OAuth2TokenBuilder())
			->fromFile($ini_file_client)
			->build();

			$session = (new AdWordsSessionBuilder())
				->fromFile($ini_file_client)
				->withOAuth2Credential($oAuth2Credential)
				->build();

			
			
			//-----
			
			
			$campaignService = $adWordsServices->get($session, CampaignService::class);
			
			
			// Create selector.
			$selector = new Selector();
			$selector->setFields(['Id', 'Name', 'Status', 'ServingStatus']);
			$selector->setOrdering([new OrderBy('Name', SortOrder::ASCENDING)]);
			$selector->setPaging(new Paging(0, 500));
			
			do {
				// Make the get request.
				$page = $campaignService->get($selector);
				// Display results.
				if ($page->getEntries() !== null){

					//insert child customers into database

					$adwId = trim($customerAdwordsId['id']);
					$adwName = trim($customerAdwordsId['name']); 

					$queryCampaign = DB::table('_adwords_users_nonmanager')
						->where('adwords_user_id', '=', $adwId)
						->get();


					if(isset($queryCampaign[0]) && count($queryCampaign[0]) > 0){
					
						//$arrayCampaign = (array)$queryCampaign[0];

					}else{

						$query_up = DB::table('_adwords_users_nonmanager')
							->insert(array(
								'manager_adwords_id' => $managerId, 
								'adwords_user_id' => $adwId, 
								'adwords_name' => $adwName
							)
						);

					}

					foreach ($page->getEntries() as $campaign) {

						//var_dump($campaign);
						//printf("Campaign with name '%s' and ID '%s' was found.\n",
						//$campaign->name, trim($campaign->id));
						//DB

						$queryCampaign = DB::table('_adwords_campaigns')
							->where('adwords_campaign_id', '=', trim($campaign->getId()))
							->get();
						
						if(isset($queryCampaign[0]) && count($queryCampaign[0]) > 0){

							$query_up = DB::table('_adwords_campaigns')
								->where('adwords_campaign_id', '=', trim($campaign->getId()))
								->update(array(
									'manager_adwords_id' => $managerId,
									'adwords_user_id' => $adwId,
									'name' => trim( $campaign->getName() )
								));

						}else{

							$query_up = DB::table('_adwords_campaigns')
								->insert(array(
									'manager_adwords_id' => $managerId,
									'adwords_user_id' => $adwId,
									'name' => trim( $campaign->getName() ),
									'adwords_campaign_id' => trim( $campaign->getId() )
								));

						}



					}
						
				} else {
				//print "No campaigns were found.\n";
				}
			// Advance the paging index.
			$selector->getPaging()->setStartIndex(
                $selector->getPaging()->getStartIndex() + 500
            );
		
		} while ($page->getTotalNumEntries() > $selector->getPaging()->getStartIndex());


		}


	}




	/*
	
		Service php functions
	
	*/


	function write_php_ini($array, $file){
		
		$res = array();
		foreach($array as $key => $val)
		{
			if(is_array($val))
			{
				$res[] = "[$key]";
				foreach($val as $skey => $sval) $res[] = "$skey = ".(is_numeric($sval) ? $sval : '"'.$sval.'"');
			}else{
				$res[] = "$key = ".(is_numeric($val) ? $val : '"'.$val.'"');
			}
		}
			$this->safefilerewrite($file, implode("\r\n", $res));
		}

	function safefilerewrite($fileName, $dataToSave){
		
		if ($fp = fopen($fileName, 'w')){
			
			$startTime = microtime(TRUE);
			
			do
			{
				$canWrite = flock($fp, LOCK_EX);
				// If lock not obtained sleep for 0 - 100 milliseconds, to avoid collision and CPU load
				if(!$canWrite) usleep(round(rand(0, 100)*1000));
			} while ((!$canWrite)and((microtime(TRUE)-$startTime) < 5));

			//file was locked so now we can store information
			if ($canWrite)
			{
				fwrite($fp, $dataToSave);
				flock($fp, LOCK_UN);
			}
			
			fclose($fp);
			
		}

	}


	public function getAccessToken($refreshToken){

		$arrayAdwordsSettings = $this->getAdwordsSettings();

		$refreshTokenArray = array(
			  'client_id' => $arrayAdwordsSettings['client_id'],
			  'client_secret' => $arrayAdwordsSettings['client_secret'],
			  'refresh_token' => $refreshToken,
			  'grant_type' => 'refresh_token'
			);
	  

		$curl = curl_init();
		$userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
		curl_setopt($curl,CURLOPT_URL, "https://accounts.google.com/o/oauth2/token");	//The URL to fetch. This can also be set when initializing a session with curl_init().
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,TRUE);	//TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
		curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,5);	//The number of seconds to wait while trying to connect.
		curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);	//The contents of the "User-Agent: " header to be used in a HTTP request.
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, FALSE);	//To follow any "Location: " header that the server sends as part of the HTTP header.
		curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE);	//To automatically set the Referer: field in requests where it follows a Location: redirect.
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);	//The maximum number of seconds to allow cURL functions to execute.
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);	//To stop cURL from verifying the peer's certificate.
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $refreshTokenArray);
		$contents = curl_exec($curl);
		curl_close($curl);
		return $contents;

	}


		/*
		
	public static function runExample(AdWordsSession $session
		) {
			$adWordsServices = new AdWordsServices();
			$managedCustomerService = $adWordsServices->get(
				$session,
				ManagedCustomerService::class
			);
			// Create selector.
			$selector = new Selector();
			$selector->setFields(['CustomerId', 'Name']);
			$selector->setOrdering([new OrderBy('CustomerId', SortOrder::ASCENDING)]);
			$selector->setPaging(new Paging(0, 500));
			// Maps from customer IDs to accounts and links.
			$customerIdsToAccounts = [];
			$customerIdsToChildLinks = [];
			$customerIdsToParentLinks = [];
			$totalNumEntries = 0;
			do {
				// Make the get request.
				$page = $managedCustomerService->get($selector);
				// Create links between manager and clients.
				if ($page->getEntries() !== null) {
					$totalNumEntries = $page->getTotalNumEntries();
					if ($page->getLinks() !== null) {
						foreach ($page->getLinks() as $link) {
							// Cast the indexes to string to avoid the issue when 32-bit PHP
							// automatically changes the IDs that are larger than the 32-bit max
							// integer value to negative numbers.
							$managerCustomerId = strval($link->getManagerCustomerId());
							$customerIdsToChildLinks[$managerCustomerId][] = $link;
							$clientCustomerId = strval($link->getClientCustomerId());
							$customerIdsToParentLinks[$clientCustomerId] = $link;
						}
					}
					foreach ($page->getEntries() as $account) {
						$customerIdsToAccounts[strval($account->getCustomerId())] = $account;
					}
				}
				// Advance the paging index.
				$selector->getPaging()->setStartIndex(
					$selector->getPaging()->getStartIndex() + 500
				);
			} while ($selector->getPaging()->getStartIndex() < $totalNumEntries);
			// Find the root account.
			$rootAccount = null;
			foreach ($customerIdsToAccounts as $account) {
				if (!array_key_exists(
					$account->getCustomerId(),
					$customerIdsToParentLinks
				)) {
					$rootAccount = $account;
					break;
				}
			}
			if ($rootAccount !== null) {
				// Display results.
				self::printAccountHierarchy(
					$rootAccount,
					$customerIdsToAccounts,
					$customerIdsToChildLinks
				);
			} else {
				printf("No accounts were found.\n");
			}
		}
		
		
		private static function printAccountHierarchy(
			$account,
			$customerIdsToAccounts,
			$customerIdsToChildLinks,
			$depth = null
		) {
			if ($depth === null) {
				print "(Customer ID, Account Name)\n";
				self::printAccountHierarchy(
					$account,
					$customerIdsToAccounts,
					$customerIdsToChildLinks,
					0
				);
				return;
			}
			print str_repeat('-', $depth * 2);
			$customerId = $account->getCustomerId();
			printf("%s, %s\n", $customerId, $account->getName());
			if (array_key_exists($customerId, $customerIdsToChildLinks)) {
				foreach ($customerIdsToChildLinks[strval($customerId)] as $childLink) {
					$childAccount = $customerIdsToAccounts[strval($childLink->getClientCustomerId())];
					self::printAccountHierarchy(
						$childAccount,
						$customerIdsToAccounts,
						$customerIdsToChildLinks,
						$depth + 1
					);
				}
			}
		}


*/


}