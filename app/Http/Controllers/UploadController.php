<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Chumper\Zipper\Zipper;
use Storage;
use File;

class UploadController extends Controller
{
    public function index(){
      return view('upload');
   }
   public function showUploadFile(Request $request){

	$file = $request->file('build');
   
	// //Display File Name
	// echo 'File Name: '.$file->getClientOriginalName();
	// echo '<br>';
   
	// //Display File Extension
	// echo 'File Extension: '.$file->getClientOriginalExtension();
	// echo '<br>';
   
	// //Display File Real Path
	// echo 'File Real Path: '.$file->getRealPath();
	// echo '<br>';
   
	// //Display File Size
	// echo 'File Size: '.$file->getSize();
	// echo '<br>';
   
	// //Display File Mime Type
	// echo 'File Mime Type: '.$file->getMimeType();
   
      
	// Generating random filename
 
	$uniqueId = $this->getToken(6);
	$buildFileName = $uniqueId.'.'.$file->getClientOriginalExtension();

	// todo: will implement some file verification and security checks
	//Move Uploaded File 

	$request->file('build')->storeAs('', $buildFileName, 'uploads');

	// getting full file path
	$buildUploadFolderPath = Storage::disk('uploads')->getDriver()->getAdapter()->getPathPrefix();
	$buildFullPath = $buildUploadFolderPath.$buildFileName;

	//echo "Build Uploaded here - ".$buildFullPath;
	// unzipping the uploaded ipa file
	// todo: need to do some security checks here 
	$zipper = new \Chumper\Zipper\Zipper;
	$zipper->zip($buildFullPath)->extractTo($buildUploadFolderPath.$uniqueId);

     
	// reading info.plist file
	// todo: need to implement some security checks
 
	$infoPlistContents = File::get(File::directories($buildUploadFolderPath.$uniqueId."/Payload")[0]."/info.plist");
 
	$xml = simplexml_load_string($infoPlistContents, "SimpleXMLElement", LIBXML_NOCDATA);
	$json = json_encode($xml);
	$infoPlistArr = json_decode($json,TRUE);

	dd($infoPlistArr);


}

	function crypto_rand_secure($min, $max)
	{
	    $range = $max - $min;
	    if ($range < 1) return $min; // not so random...
	    $log = ceil(log($range, 2));
	    $bytes = (int) ($log / 8) + 1; // length in bytes
	    $bits = (int) $log + 1; // length in bits
	    $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
	    do {
	        $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
	        $rnd = $rnd & $filter; // discard irrelevant bits
	    } while ($rnd > $range);
	    return $min + $rnd;
	}

	function getToken($length)
	{
	    $token = "";
	    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	    $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
	    $codeAlphabet.= "0123456789";
	    $max = strlen($codeAlphabet); // edited

	    for ($i=0; $i < $length; $i++) {
	        $token .= $codeAlphabet[$this->crypto_rand_secure(0, $max-1)];
	    }

	    return $token;
	}



}
