<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Chumper\Zipper\Zipper;

class UploadController extends Controller
{
    public function index(){
      return view('upload');
   }
   public function showUploadFile(Request $request){
	$file = $request->file('build');
   
	//Display File Name
	echo 'File Name: '.$file->getClientOriginalName();
	echo '<br>';
   
	//Display File Extension
	echo 'File Extension: '.$file->getClientOriginalExtension();
	echo '<br>';
   
	//Display File Real Path
	echo 'File Real Path: '.$file->getRealPath();
	echo '<br>';
   
	//Display File Size
	echo 'File Size: '.$file->getSize();
	echo '<br>';
   
	//Display File Mime Type
	echo 'File Mime Type: '.$file->getMimeType();
   
      
	// Generating random filename
	$destinationPath = public_path().'/uploads';
	echo "Destination path ".$destinationPath;
	$newFileName = $this->getToken(6);
	$newFullFileName = $newFileName.'.'.$file->getClientOriginalExtension();

	//Move Uploaded File
	$file->move($destinationPath, $newFullFileName);


	// // unzipping the .ipa file to read it
	// //if (File::exists(public_path().$newFileName)) {
	// 	$zipper = new \Chumper\Zipper\Zipper;

	// 	//Zipper::make(public_path().$newFileName)->folder('test')->extractTo('foo');

	// 	$zipper->zip($newFullFileName)->folder($newFileName)->extractTo(public_path() ."/uploads/".$newFileName);
          
 //    //}



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
