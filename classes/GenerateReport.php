<?php

session_start();

class GenerateReport {
	
	private $client;
	private $service;
	private $accountId = 'pub-2383900878615808';
	private $siteName;
	private $arrToken;	
	
	//al cargar la clase obtiene el token de acceso a AdSense para traer los reportes
	public function __construct()
	{
				
		//manejo de errores
		include_once 'classes/evErrorHandler.php';
		set_error_handler('customErrorHandler');
		register_shutdown_function('fatal_handler');
		
		set_include_path('../google-api-php-client-master/src' . PATH_SEPARATOR . get_include_path());
		require_once 'Google/autoload.php';
		require_once 'Google/Client.php';
		require_once 'Google/Service/AdSense.php';
		
		//funciones globales
		include_once 'evFunctions.php';
		
		// Set up authentication.
		$this->client = new Google_Client();
		$this->client->addScope('https://www.googleapis.com/auth/adsense.readonly');
		$this->client->setAccessType('offline');
		
		//console developer project credentials
		$this->client->setAuthConfigFile('client_secrets.json');
		$this->service = new Google_Service_AdSense($this->client);

		//set the token
		//al hacer login se carga en una variable de sesion los datos del token configurado en la BD 
		$this->arrToken = array('access_token' => $_SESSION['login']['token_access'], 
								'token_type'   => $_SESSION['login']['token_type'], 
								'expires_in'   => $_SESSION['login']['token_expires_in']
							   );
		$_SESSION['access_token'] = json_encode($this->arrToken);		
		
		$this->client->setAccessToken($_SESSION['access_token']);
				
 		if ($this->client->isAccessTokenExpired()) { 			
 			$this->client->refreshToken($_SESSION['login']['token_refresh']); 			
 			$acceso = $this->client->getAccessToken(); 			
 			$this->client->setAccessToken($acceso);
 			$this->client->authenticate($acceso);
 		}
				
		$this->siteName = $_SESSION['login']['site_name'];
		
	} //function __construct
	
	
	
  /**
   * Retrieves a report for the specified ad client.
   *
   * @param $service Google_Service_AdSense AdSense service object on which to
   *     run the requests.
   * @param $accountId string the ID for the account to be used.
   * @param $adClientId string the ad client ID on which to run the report.
   */
  public static function run($service, $accountId, $adClientId='') {
    $separator = str_repeat('=', 80) . "\n";
    print $separator;
    printf("Running report for ad client %s\n", $adClientId);
    print $separator;
    $startDate = 'today-2007d';
    $endDate = 'today-1d';
    //     $optParams = array(
//       'metric' => array(
//         'PAGE_VIEWS', 'AD_REQUESTS', 'AD_REQUESTS_COVERAGE', 'CLICKS',
//         'AD_REQUESTS_CTR', 'COST_PER_CLICK', 'AD_REQUESTS_RPM', 'EARNINGS'),
//       'dimension' => 'DATE',
//      'dimension' => array('AD_FORMAT_NAME', 'AD_UNIT_NAME', 'PLATFORM_TYPE_NAME', 'AD_UNIT_CODE', 'AD_CLIENT_ID'),
//       'sort' => '+DATE',
//       'filter' => array(
//         'AD_CLIENT_ID==' . $adClientId
//       )
//     );
    $optParams = array(
      'metric' => 'EARNINGS',
      'dimension' => 'AD_UNIT_NAME',
      'sort' => '+AD_UNIT_NAME'
    );
    // Run report.
    $report = $service->accounts_reports->generate($accountId, $startDate, $endDate, $optParams);
    
    if (isset($report) && isset($report['rows'])) {
      // Display headers.
      foreach($report['headers'] as $header) {
        printf('%25s', $header['name']);
      }
      print "\n";
      // Display results.
      foreach($report['rows'] as $row) {
        foreach($row as $column) {
          printf('%25s', $column);
        }
        print "\n";
      }
    } else {
      print "No rows returned.\n";
    }
    print "\n";
  }
  
  //client id es el sitename del cual se quieren traer los datos  
  public function getDetail($adClientId='', $dateType='t', $customStartDate='', $customEndDate='') {
  	  	
  	//si no trae filtro por cliente pone 0 para que salgan todos
  	//$adClientId=$this->siteName;
  	$adClientId=str_replace(".","",$this->siteName);
  	if ($adClientId=='') {$adClientId='0';}
  	  	 
  	//today, startOfMonth, startOfYear, latest
  	try {
  	 
  		//filtro por tipo fecha
  		switch ($dateType) {
  			case 't': //today
  				$startDate = 'today';
  				$endDate = 'today';
  				$tipoFecha = "Today";
  				break;
  			case 'y': //yesterday
  				$startDate = 'today-1d';
  				//$startDate = 'startOfMonth-2m';  //test
  				$endDate = 'today-1d';
  				$tipoFecha="Yesterday";
  				break;
  			case 'cm': //current month
  				$startDate = 'startOfMonth';
  				//$startDate = 'startOfMonth-20m';  //test
  				$endDate = 'today';
  				$tipoFecha="Current Month";
  				break;
  			case 'lm': //last month
  				$startDate = 'startOfMonth-1m';
  				//$startDate = 'startOfMonth-30m';  //test
  				$endDate = 'startOfMonth-1d';
  				$tipoFecha="Last Month";
  				break;
  			case 'cd': //custom date
  				
  				if ($customStartDate=="" or $customEndDate=="") {
  					die('<h4>Enter correct values for Start and End date</h4>');
  				}
  				
  				$startDate = ev_ParseDateMDY($customStartDate);
  				$endDate = ev_ParseDateMDY($customEndDate);
  				$tipoFecha="Custom date from: $startDate to $endDate";
  				break;
  			default:
  				$startDate = 'today';
  				$endDate = 'today';
  				$startDate = 'Today';
  		}

  		$optParams = array(
  				'metric' => array('INDIVIDUAL_AD_IMPRESSIONS', 'CLICKS', 'INDIVIDUAL_AD_IMPRESSIONS_CTR', 'INDIVIDUAL_AD_IMPRESSIONS_RPM', 'EARNINGS'),
  				//'dimension' => array('DOMAIN_NAME', 'AD_UNIT_SIZE_NAME', 'AD_UNIT_NAME'),
  				'dimension' => array('AD_UNIT_SIZE_NAME', 'AD_UNIT_NAME', 'DATE'),
  				'filter' => 'AD_UNIT_NAME=@'.$adClientId, // '=@' es un like
  				'sort' => '+AD_UNIT_NAME'
  		);
  		
  		// Run report.
  		$report = $this->service->accounts_reports->generate($this->accountId, $startDate, $endDate, $optParams);
  		
  		echo '<div class="container">';
  		echo "<h3>Earnings Report ($tipoFecha) </h3>";
  		
  		// Summarize
  		if (isset($report) && isset($report['rows'])) {
  		  			  			
  			echo '<div class="table-responsive">';
  			
  			echo '<table class="table table-hover table-condensed">';
  				
  			$totalEarnings = 0;
  			$totalImpressions = 0;
  			$totalClicks = 0;
  			$totalCPM = 0;
  			$rowCount = 0;
  			foreach($report['rows'] as $row) {

  				$rowCount+=1;
  				if($rowCount==1){  //titulos
  					echo '<tr class="success">';  						
  					//echo '<th class="success">Site</th>';
  					echo '<th>Format</th>';
  					echo '<th>Unit Name</th>';
  					echo '<th>Date</th>';
  					echo '<th class="text-right">Impressions</th>';
  					echo '<th class="text-right">Clicks</th>';
  					echo '<th class="text-right">CTR</th>';
  					echo '<th class="text-right">CPM</th>';
  					echo '<th class="text-right">Earnings</th>';
  					echo '</tr><tr>';
  				}
  				
  				echo '<tr>';  				
  				echo '<td>'.$row[0].'</td>'; //AD_UNIT_SIZE_NAME
  				echo '<td>'.$row[1].'</td>'; //AD_UNIT_NAME
  				echo '<td>'.$row[2].'</td>'; //DATE
  				echo '<td class="text-right">'.$row[3].'</td>'; //INDIVIDUAL_AD_IMPRESSIONS
  				echo '<td class="text-right">'.$row[4].'</td>'; //CLICKS
  				echo '<td class="text-right">'.round($row[5]*100,2).'%</td>'; //AD_REQUESTS_CTR
  				
				//determina si usa CPM que viene del reporte o usa el que esta configurado por usuario (fixed)
				$conExchangeRate=$row[6] * $_SESSION['login']['reports_exchange_rate']; //aplica tasa cambio
  				if (isset($_SESSION['login']['fixed_cpm'])){
  					if ($_SESSION['login']['fixed_cpm']>0) {
  						$clientCPM = $_SESSION['login']['fixed_cpm'];  //fixed_cpm de la tabla de users
  					}else {
  						$clientCPM = ev_DiscountRevenue($conExchangeRate);  //RPM
  					}  						  					
  				} else {
  					$clientCPM = ev_DiscountRevenue($conExchangeRate);  //RPM
  				}  				
  				//$clientCPM = ev_DiscountRevenue($row[6]);  //RPM
  				echo '<td class="text-right">'.$_SESSION['login']['reports_currency_symbol'].' '.number_format(truncate_float($clientCPM,2),2).'</td>'; //AD_REQUESTS_RPM

  				//determina si usa Earning calculado (cpm fixed) o usa el que viene del reporte
  				if (isset($_SESSION['login']['fixed_cpm'])){
  					if ($_SESSION['login']['fixed_cpm']>0) {
						$conExchangeRate=$row[3] * $_SESSION['login']['reports_exchange_rate']; //aplica tasa cambio
  						$clientEarnings = $conExchangeRate * $_SESSION['login']['fixed_cpm'] / 1000; 
  					}else {
						$conExchangeRate=$row[7] * $_SESSION['login']['reports_exchange_rate']; //aplica tasa cambio
  						$clientEarnings = ev_DiscountRevenue($conExchangeRate);  //EARNINGS
  					}  						  					
  				} else {
					$conExchangeRate=$row[7] * $_SESSION['login']['reports_exchange_rate']; //aplica tasa cambio
  					$clientEarnings = ev_DiscountRevenue($conExchangeRate);  //EARNINGS
  				}
  				//$clientEarnings = ev_DiscountRevenue($row[7]);  //EARNINGS 
  				echo '<td class="text-right">'.$_SESSION['login']['reports_currency_symbol'].' '.number_format(truncate_float($clientEarnings,2),2).'</td>';

				//sumando totales
  				$totalEarnings += truncate_float($clientEarnings,2); //para que sume solo con 2 decimales
  				$totalImpressions += $row[3];
  				$totalClicks += $row[4];
  				$totalCPM += truncate_float($clientCPM,2);
  				
  				echo '</tr>';  				
  				
  			}

			//cpm no es suma sino promedio, si es fixed para el usuario no hace ningun promedio, solo lo pone
  			if (isset($_SESSION['login']['fixed_cpm'])){
  				if ($_SESSION['login']['fixed_cpm']>0) {
  					$avgCPM = $_SESSION['login']['fixed_cpm'];
  				}else {
  					$avgCPM = truncate_float($totalCPM/$rowCount,2);
  				}
  			} else {
  				$avgCPM = truncate_float($totalCPM/$rowCount,2);
  			}
  				
  			
  			
  			
  			echo '<tr class="success">';
  			echo '<td><b>Totals:</b></td>'; //AD_UNIT_SIZE_NAME
  			echo '<td></td>'; //AD_UNIT_NAME
  			echo '<td></td>'; //DATE
  			echo '<td class="text-right"><b>'.round($totalImpressions,2).'</b></td>'; //INDIVIDUAL_AD_IMPRESSIONS
  			echo '<td class="text-right"><b>'.$totalClicks.'</b></td>'; //CLICKS
  			echo '<td></td>'; //AD_REQUESTS_CTR
  			echo '<td class="text-right"><b>'.$_SESSION['login']['reports_currency_symbol'].' '.number_format(truncate_float($avgCPM,2),2).'</b></td>'; //AD_REQUESTS_RPM
  			echo '<td class="text-right"><b>'.$_SESSION['login']['reports_currency_symbol'].' '.number_format(truncate_float($totalEarnings,2),2).'</b></td>'; //EARNINGS
  			echo '</tr>';
  				
  			echo '</table>';
  			
  			$sumatotal=$totalEarnings+floatval($_SESSION['login']['revenue_percent']);
  				
  			echo '</div>';
  				
  			return $totalEarnings;
  		} else {  // No rows
  			echo "<span>No Rows</span>";
  		}
  		  	
  	} catch (Exception $e) {
  		
  		//antesError:Error calling GET https://www.googleapis.com/adsense/v1.4/accounts/pub-2383900878615808/reports?startDate=startOfMonth-4m&endDate=today-1d&metric=EARNINGS&dimension=DOMAIN_NAME&filter=AD_UNIT_NAME%3D%400: (400) Data were not available for part of the requested date range. First date with a complete dataset: 2015-04-04No Rows  		
  		if (strpos($e->getMessage(),'Data were not available')) {
  			echo "<span>Data were not available for part the requested date range</span>";
  		}else{
  			echo $e->getMessage();
  			echo "<span>No Rows</span>";
   		}
   	}
   	
   	echo '</div>';
   	
  	 
  }
  

/*  
  //---------------------------------------------
  $file = fopen($out_file_name,"r");
  //$file = fopen("C:\Windows\Temp\Carga.csv","r");  //local
  //$file = fopen("/tmp/Carga.csv","r");  //remoto
  
  echo '<div class="container">';
  
  echo('<h3>Revenue Report</h3>');
  echo('<h5>Date Range: <b>'.$tipoFecha.'</b></h5>');
  
  echo '<div class="table-responsive">';
  echo '<table class="table table-hover table-condensed">';
  
  $row=0;
  while(! feof($file))
  {
  
  	//print_r(fgetcsv($file));
  
  	$row+=1;
  	$arrLinea = fgetcsv($file);
  
  	$numFields = count($arrLinea);
  	//$totalImpressions = 0;
  
  	echo '<tr>';
  	if($row==1){
  		for ($c=0; $c < $numFields; $c++) {
  
  			str_replace("%body%", "black", "<body text='%body%'>");
  			$columnTitle = strtoupper(str_replace('Column.','',str_replace('Dimension.','',$arrLinea[$c])));
  			switch ($columnTitle) {
  				case 'AD_UNIT_ID':
  					$columnTitle = 'ID';
  					break;
  				case 'AD_UNIT_NAME':
  					$columnTitle = 'AD_UNIT';
  					break;
  				case 'TOTAL_INVENTORY_LEVEL_IMPRESSIONS':
  					$columnTitle = 'IMPRESSIONS';
  					break;
  				case 'TOTAL_INVENTORY_LEVEL_CPM_AND_CPC_REVENUE':
  					$columnTitle = 'REVENUE';
  					break;
  			}
  			echo '<th class="success">'.$columnTitle.'</th>';
  				
  		}
  	}else{
  		for ($c=0; $c < $numFields; $c++) {
  				
  			echo '<td>'.$arrLinea[$c].'</td>';
  			if ($c==2) { $totalImpressions = floatval($arrLinea[$c]); }
  			if ($c==3) { $totalRevenue = floatval($arrLinea[$c]); }
  				
  		}
  
  	}
  	echo '</tr>';
  
  }
    
  
  
  echo '</table>';
  echo '<h5>Total Impressions: <b>'.$totalImpressions.'</b></h5>';
  echo '<h5>Total Revenue: <b>'.$totalRevenue.'</b></h5>';
  //echo $totalImpressions;
  
  echo '</div>';
  echo '</div>';
  
  fclose($file);
  //---------------------------------------------
*/    

  //sumatoria de Earnings por fecha (today, yesterday, current month, etc.)
  //client id es el sitename del cual se quieren traer los datos  
  //public static function getSummary($service, $adClientId='', $accountId, $dateType='t', $customStartDate='', $customEndDate='') {
  //public static function getSummary($adClientId='', $accountId, $dateType='t', $customStartDate='', $customEndDate='') {
  public function getSummary($adClientId='', $dateType='t', $customStartDate='', $customEndDate='') {

  	//si no trae filtro por cliente pone 0 para que salgan todos
  	//if ($adClientId=='') {$adClientId='0';}
  	//$adClientId=$this->siteName;
  	$adClientId=str_replace(".","",$this->siteName);
  	if ($adClientId=='') {$adClientId='0';}
    	

  	//today, startOfMonth, startOfYear, latest
  	try {
  		  		
	  	//filtro por tipo fecha
	  	switch ($dateType) {  		 
		  	case 't': //today
		  		$startDate = 'today';
		  		$endDate = 'today';
		  		break;
		  	case 'y': //yesterday
		  		$startDate = 'today-1d';
		  		//$startDate = 'startOfMonth-10m';  //test		  		
		  		$endDate = 'today-1d';
		  		break;
		  	case 'cm': //current month
		  		$startDate = 'startOfMonth';
		  		//$startDate = 'startOfMonth-20m';  //test
		  		$endDate = 'today';
		  		break;
		  	case 'lm': //last month
		  		$startDate = 'startOfMonth-1m';
		  		//$startDate = 'startOfMonth-30m';  //test  		
		  		$endDate = 'startOfMonth-1d';
		  		break;
		  	case 'cd': //custom date
		  		$startDate = customStartDate;
		  		$endDate = customEndDate;
		  		break;
		  	default:
		  		$startDate = 'today';
		  		$endDate = 'today';	  		 
	  	}
	  	  	
	  	$optParams = array(
	  			'metric' => array('EARNINGS', 'INDIVIDUAL_AD_IMPRESSIONS'),
  				'dimension' => array('AD_UNIT_SIZE_NAME', 'AD_UNIT_NAME', 'DATE'),  //se debe usar el mismo parametro que el reporte Detail para que cuadren las sumatorias
	  			'filter' => 'AD_UNIT_NAME=@'.$adClientId, // '=@' es un like
	  			'sort' => '+AD_UNIT_NAME'
	  	);
	  	
	  	// Run report.
	  	$report = $this->service->accounts_reports->generate($this->accountId, $startDate, $endDate, $optParams);
	  	
	  	// Summarize
	  	if (isset($report) && isset($report['rows'])) {
			$suma = 0;
	   		foreach($report['rows'] as $row) {	   	

	   			//determina si usa Earning calculado (cpm fixed) o usa el que viene del reporte
	   			if (isset($_SESSION['login']['fixed_cpm'])){
	   				if ($_SESSION['login']['fixed_cpm']>0) {
	   					$clientEarnings = $row[4] * $_SESSION['login']['fixed_cpm'] / 1000;
	   				}else {
	   					$clientEarnings = ev_DiscountRevenue($row[3]);  //descuenta nuestra participacion antes de sumar
	   				}
	   			} else {
	   				$clientEarnings = ev_DiscountRevenue($row[3]);  //descuenta nuestra participacion antes de sumar
	   			}
	   			//$clientEarnings = ev_DiscountRevenue($row[3]);  //descuenta nuestra participacion antes de sumar
	   				   	   			
	   			$suma += truncate_float($clientEarnings,2); //para que sume solo con 2 decimales
	   				   
	   		}
	   		
	   		/*TEMPORAL SOLO TESTING*/
	   		/*
	   		// Display headers.
	   		foreach($report['headers'] as $header) {
	   			printf('%25s', $header['name']);
	   		}
	   		print "\n";
	   		// Display results.
	   		foreach($report['rows'] as $row) {
	   			foreach($row as $column) {
	   				printf('%25s', $column);
	   			}
	   			print "\n";
	   		}
	   		*/
	   		/*TEMPORAL SOLO TESTING*/	   		
	   		
	   		
	   		return $suma * $_SESSION['login']['reports_exchange_rate'];  		
	  	} else {  // No rows  		
	  		return "0";
	  	}
  	
	} catch (Exception $e) {
  		//echo $e->getMessage();
  		return "0";  		
  	}
  	
  }  //getSummary
  
  
} //class GenerateReport

?>
