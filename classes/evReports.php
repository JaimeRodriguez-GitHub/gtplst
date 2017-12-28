<?php

$path = dirname(__FILE__) . '/../lib';

//$path = dirname(__FILE__) . '/../../../../lib';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);

require_once 'Google/Api/Ads/Dfp/Lib/DfpUser.php';
require_once 'Google/Api/Ads/Dfp/v201505/ReportService.php';  //<-- yo
require_once 'Google/Api/Ads/Dfp/Util/v201502/ReportUtils.php';  //<-- yo
require_once 'Google/Api/Ads/Dfp/Util/v201505/ReportDownloader.php';
require_once 'Google/Api/Ads/Dfp/Util/v201505/StatementBuilder.php';

//require_once dirname(__FILE__) . '/examples/Common/ExampleUtils.php';

// Clase encargada de gestionar funciones varias
Class evReports{
	
	
	//Obtiene Revenue Today
	function getRevenueTotal ($pDateRangeType='CURRENT_MONTH', $pSite='') {
		
		try {
			
					
			$user = new DfpUser();
			
			// Log SOAP XML request and response.
			$user->LogDefaults();
			
			// Get the ReportService.
			$reportService = $user->GetService('ReportService', 'v201505'); //<--ORIGINAL
			
			// Get the NetworkService.
			$networkService = $user->GetService('NetworkService', 'v201505');
			
			// Get the root ad unit ID to filter on.
			$rootAdUnitId =
			$networkService->getCurrentNetwork()->effectiveRootAdUnitId;
			
			// Create statement to filter on a parent ad unit with the root ad unit ID to
			// include all ad units in the network.
			$statementBuilder = new StatementBuilder();
			//$statementBuilder->Where('PARENT_AD_UNIT_ID = :parentAdUnitId')
			//    ->WithBindVariableValue('parentAdUnitId', intval($rootAdUnitId));
			$statementBuilder->Where("AD_UNIT_NAME LIKE '%:valor%'")->WithBindVariableValue('valor', 'plato');
			
			// Create report query.
			$reportQuery = new ReportQuery();
			$reportQuery->dimensions = array('AD_UNIT_ID');
			$reportQuery->columns = array('TOTAL_INVENTORY_LEVEL_CPM_AND_CPC_REVENUE');
			
			// Set the filter statement.  (filtra por Site)
			//$reportQuery->statement = $statementBuilder->ToStatement();
			
			// Set the ad unit view to hierarchical.
			$reportQuery->adUnitView = 'FLAT';
			
			// Set the start and end dates or choose a dynamic date range type.
			switch ($pDateRangeType) {
				case 'CURRENT_MONTH':
					$reportQuery->dateRangeType = 'CUSTOM_DATE';
					$reportQuery->startDate = new Date(date('Y'), date('m'), 1);
					$reportQuery->endDate = new Date(date('Y'), date('m'), date('t'));
					break;
				default:
					$reportQuery->dateRangeType = $pDateRangeType;
			}
			
			// Create report job.
			$reportJob = new ReportJob();
			$reportJob->reportQuery = $reportQuery;
			
			// Run report job.
			$reportJob = $reportService->runReportJob($reportJob);
			
			// Create report downloader.
			$reportDownloader = new ReportDownloader($reportService, $reportJob->id);
			
			// Wait for the report to be ready.
			$reportDownloader->waitForReportReady();
			
			// Change to your file location.
			$filePath = sprintf('%s.csv.gz', tempnam(sys_get_temp_dir(),'inventory-report-')); //<--ORIGINAL
			
			// Download the report.
			$reportDownloader->downloadReport('CSV_DUMP', $filePath);  //<---ORIGINAL
			
			//------------ Uncompress the downloaded file---------------
			// This input should be from somewhere else, hard-coded in this example
			$file_name = $filePath;
			
			// Raising this value may increase performance
			$buffer_size = 4096; // read 4kb at a time
			$out_file_name = str_replace('.gz', '', $file_name);
			
			// Open our files (in binary mode)
			$file = gzopen($file_name, 'rb');
			$out_file = fopen($out_file_name, 'wb');
			
			// Keep repeating until the end of the input file
			while(!gzeof($file)) {
				// Read buffer-size bytes
				// Both fwrite and gzread and binary-safe
				fwrite($out_file, gzread($file, $buffer_size));
			}
			
			// Files are done, close files
			fclose($out_file);
			gzclose($file);
			//------------ Uncompress the downloaded file---------------
			
			//------------ Read file content and get total -------------
			$file = fopen($out_file_name,"r");
			$row=0;
			while(! feof($file))
			{
			
				$row+=1;
				$arrLinea = fgetcsv($file);
			
				$numFields = count($arrLinea);
			
				if($row>=1){
					for ($c=0; $c < $numFields; $c++) {
							
						if ($c==1) { $totalRevenue = floatval($arrLinea[$c]); }
							
					}
			
				}
			
			}
			
			fclose($file);
			
			return number_format($totalRevenue);			
			//------------ Read file content and get total -------------
			
			
		} catch (Exception $e) {
			return 0; //"ERROR: ".$e->getMessage();
		}
	
	} //function getCountries
	
}
?>