<?php
/**
 * Copyright (c) 2013 - Aaron Connelly @ MoonBase3.com
 * Author: Aaron Connelly - MoonBase3.com
 * 
 * Will handle the token and cypher generation for JCD Online using tripledes encryption
 * REQUIRES: libmcrypt and mcrypt installed and enabled on your server
 * 
 * 
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * - Redistributions of source code must retain the above copyright notice,
 * this list of conditions and the following disclaimer.
 * - Redistributions in binary form must reproduce the above copyright
 * notice, this list of conditions and the following disclaimer in the
 * documentation and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 * 
 */

Class JCDOnline {

  	public $hostName = 'www.yourdomain.com'; # your hostname (usually your website)
	public $societyID = 0; # supplied by JCD
	public $encryptKey = ''; # supplied by JCD
	public $memberLevel = 'memberaccess'; # this is a default supplied by JCD Online
	
	public $targetURL_test = 'http://tps.elsevier.com:8080/tps/test';
	public $targetURL_live = 'http://www.clinicaldensitometry.com/';
	
	public $targetURL = '';
	
	function __construct($test = false){
		if( $test ){
			$this->targetURL = $this->targetURL_test;
		}else{
			$this->targetURL = $this->targetURL_live;
		}
	}
	
	private function buildToken(){
		# get GMT time
		$time = time();
		$time = $time."000";

		# build string token
		$token = $this->hostName.'|'.$time.'|'.$this->memberLevel;
	
		# build key
		$key = base64_decode($this->encryptKey);
		
		# build encrypted token
		$encryptedToken = $this->encrypt($token, $key) or die("Failed to complete encryption.");
		
		return $encryptedToken;
	}
	
	public function getTPSLink(){
		# generation link - make sure you use urlencode
		return $this->targetURL.'?tpstoken='.$this->societyID.'.'.urlencode(base64_encode($this->buildToken()));
	}

	
	private function encrypt($str, $key)
	{
	    # make sure you pad 8 bytes
	    $block = mcrypt_get_block_size('tripledes', 'ctr');
	    $pad = $block - (strlen($str) % $block);
	    $str .= str_repeat(chr($pad), $pad);
	
	    # return encryption
	    return mcrypt_encrypt('tripledes', $key, $str, MCRYPT_MODE_ECB);
	}
}
?>
