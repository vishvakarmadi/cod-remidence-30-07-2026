<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Google\Client;
use Google\Service\Sheets;
use Google\Auth\CredentialsLoader;

require_once('vendor/google/apiclient/src/Task/Runner.php');
require_once('vendor/google/apiclient/src/Http/REST.php');
require_once('vendor/google/apiclient/src/Client.php');
require_once('vendor/google/apiclient/src/Service.php');

require_once('vendor/google/auth/src/FetchAuthTokenInterface.php');
require_once('vendor/google/auth/src/SignBlobInterface.php');
require_once('vendor/google/auth/src/ProjectIdProviderInterface.php');
require_once('vendor/google/auth/src/UpdateMetadataInterface.php');

require_once('vendor/google/auth/src/MetricsTrait.php');
require_once('vendor/google/auth/src/GetUniverseDomainInterface.php');
require_once('vendor/google/auth/src/GetQuotaProjectInterface.php');

require_once('vendor/google/auth/src/OAuth2.php');
require_once('vendor/google/auth/src/CacheTrait.php');
require_once('vendor/google/auth/src/FetchAuthTokenCache.php');
require_once('vendor/google/auth/src/ServiceAccountSignerTrait.php');



require_once('vendor/google/auth/src/UpdateMetadataTrait.php');
require_once('vendor/google/auth/src/CredentialsLoader.php');
require_once('vendor/google/auth/src/Credentials/ServiceAccountCredentials.php');

require_once('vendor/google/apiclient/src/Service/Resource.php');







require_once('vendor/google/apiclient-services/src/Sheets/Resource/SpreadsheetsSheets.php');
require_once('vendor/google/apiclient-services/src/Sheets/Resource/SpreadsheetsValues.php');
require_once('vendor/google/apiclient-services/src/Sheets/Resource/SpreadsheetsDeveloperMetadata.php');
require_once('vendor/google/apiclient-services/src/Sheets/Resource/Spreadsheets.php');


require_once('vendor/google/auth/src/Middleware/AuthTokenMiddleware.php');
require_once('vendor/google/auth/src/HttpHandler/Guzzle6HttpHandler.php');
require_once('vendor/google/auth/src/HttpHandler/Guzzle7HttpHandler.php');
require_once('vendor/google/auth/src/HttpHandler/HttpHandlerFactory.php');
require_once('vendor/google/apiclient/src/Model.php');
require_once('vendor/google/apiclient/src/Utils/UriTemplate.php');
require_once('vendor/google/apiclient/src/AuthHandler/AuthHandlerFactory.php');
require_once('vendor/google/apiclient/src/AuthHandler/Guzzle6AuthHandler.php');
require_once('vendor/google/apiclient/src/AuthHandler/Guzzle7AuthHandler.php');
require_once('vendor/google/auth/src/Cache/TypedItem.php');
require_once('vendor/google/auth/src/Cache/MemoryCacheItemPool.php');


require_once('vendor/google/apiclient-services/src/Sheets/ClearValuesRequest.php');

require_once('vendor/google/apiclient-services/src/Sheets.php');

require_once('vendor/google/apiclient-services/src/Sheets/ClearValuesResponse.php');
require_once('vendor/google/apiclient-services/src/Sheets/UpdateValuesResponse.php');
require_once('vendor/google/apiclient/src/Collection.php');
require_once('vendor/google/apiclient-services/src/Sheets/ValueRange.php');
use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Sheets;
use Google_Service_Sheets_ValueRange;
use Google_Service_Sheets_ClearValuesRequest;
use Rap2hpoutre\FastExcel\FastExcel;

class GoogleController  extends Controller
{
    public function updateSheet()
    {

        $collections = json_decode($_POST['filename']);
        
        $client = new Client();
        $client->setAuthConfig(storage_path('updatesheettest-422805-cdf9ee7964cf.json'));
        $client->addScope(Sheets::SPREADSHEETS);
        $client->setAccessType('offline');

        $service = new Sheets($client);
        $spreadsheetId = '1m3nnWKrhwfIPwG8tXIQ_KPx16BBe2UmDgerw7_wkhqU';

        // Clear all data from the sheet
        $clearRequest = new Google_Service_Sheets_ClearValuesRequest();
        $service->spreadsheets_values->clear($spreadsheetId, 'A1:ZZ', $clearRequest);

        // $range = 'Hyloship Data!A1:E5';

        // Read data from sheet
        // $filePath = public_path('ratecards.xlsx');
        // try {
        // $collections = (new FastExcel)->import($filePath);
        // } catch (\Exception $exception) {
        //     return back()->with('error','You have uploaded a wrong format file, please upload the right file.');
        // }
        // Determine the number of rows and columns in the data
        $numRows = count($collections);
        $numColumns = count($collections[0]); // Assuming the first row has the same number of columns as the rest
            // echo $numColumns.' '.$numRows;die;
        // Calculate the range dynamically
        $range = 'Hyloship Data!A1:' . $this->getColumnLetter($numColumns) . $numRows;
        
//         echo '<pre>';print_R($collections);
//         echo $range;
//         die;
        $j=0;
        foreach ($collections as $rows) {
            $i=0;
            foreach($rows as $row){
                $values[$j][$i] = $row;
                $i++;
            }
            $j++;
        }
        
//        $values = [
//    ['John', 'Doe', 'johndoe@example.com'],
//    ['Jane', 'Smith', 'janesmith@example.com'],
//    ['Mike', 'Johnson', 'mikejohnson@example.com'],
//];
//        $range = 'Hyloship Data!A1:C' . count($values);
//         echo '<pre>';print_R($values);die;
        $body = new Google_Service_Sheets_ValueRange(['values' => $values]);
        $params = ['valueInputOption' => 'RAW'];
//echo $body;die;
        try {
            $result = $service->spreadsheets_values->update($spreadsheetId, $range, $body, $params);
            return redirect()->route('admin.reports.view')->with('success', 'Sheet updated!');
             echo 'done';die;
        } catch (\Exception $e) {
            return redirect()->route('admin.reports.view')->with('error', 'Failed to update sheet: ' . $e->getMessage());
             echo $e->getMessage();die;
        }
    }

    private function getColumnLetter($columnNumber)
    {
        $columnLetter = '';
        while ($columnNumber > 0) {
            $remainder = ($columnNumber - 1) % 26;
            $columnLetter = chr(65 + $remainder) . $columnLetter;
            $columnNumber = intval(($columnNumber - $remainder) / 26);
        }
        return $columnLetter;
    }
}
