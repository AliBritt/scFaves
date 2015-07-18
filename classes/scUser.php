<?php
//echo "scUser.php accessed. ";
//access soundcloud api php wrapper
require('includes/Soundcloud.php');

//access scUcred.php
require 'credScUser.php';
//scUser extends new Services soundcloud()?

class scUser{
	//define variables
	//do I need to store services soundcloud in here?
	private $scWrap				= "";
	
	private $scAuthUrl			= "";
	private $scAccessToken		= "";
	private $messages			= "";
	
	
	// call new services soundcloud
	public function __construct(){
		
		try{
			$this->scWrap = new Services_Soundcloud(CLIENT_ID , CLIENT_SECRET , REDIRECT_URI) ;
		
		}catch(Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
			exit($e->getMessage());
		}

	}
	
	//loginToSc()
	// get redirect url from sc and go there	
	public function loginToSc(){
		//echo "called funtion.";
		try{
			$this->scAuthUrl = $this->scWrap->getAuthorizeUrl();
		
			header('Location:' . $this->scAuthUrl ); 
			
		}catch(Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
			exit($e->getMessage());
			//$this->messages = $e->getMessages();
			//echo "services. ";
			//print_r($this->messages);
			
			}
	}
	
	
	public function accessToken(){
		echo "accessToken function accessed";
	//accessToken allows us to ?? why do this?fuckitdoitanywayy
		if(!isset($_SESSION['scUserToken'])){
			try{
				$this->scAccessToken = $this->scWrap->accessToken($_GET['code']);
					print_r($this->scAccessToken);
				//set SESSION to access token(from accesstoken array)
				$_SESSION['scUserToken'] = $this->scAccessToken['access_token'];
				//print_r($_SESSION['scUserToken']);
				
				echo " 1 ";
				
			} catch (Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
				exit($e->getMessage());
				
			}
		}else{
			//set our classes accesstoken property to the session data
				
			try{
				
				$this->scWrap->setAccessToken($_SESSION['scUserToken']);
				echo " 2 ";
			} catch (Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
				exit($e->getMessage());
			}
		}
	
	}
	
	//userDeets()
		//me - for sc username
	//scUserRecall()
		//connect to db and return urls of embedded pinned tracks
		//or display 'no tracks pinned please search
		
	//scUserSearch()
	public function scSearch(){
		echo "scSearch function accessed";
		
		$username = 'ali britt';

		$tracksSearchQ = json_decode($this->scWrap->get('tracks', array('q' => $username)), true);
		//echo "<pre>";
		//print_r($tracksSearchQ);
		//print_r($tracksSearchQ[0]['permalink_url']);
		
		//select trackurls(permalink url) from returned array
		//cycle through top level array
		foreach($tracksSearchQ as $trackInfo){
			//cycle through track info. array 
			foreach($trackInfo as $key=>$value){
			 // echo "$key . 'is' . $value . '<br>'";
			 
				//and identify urls
				if ($key == 'permalink_url'){
					//not sure what this does
					$this->scWrap->setCurlOptions(array(CURLOPT_FOLLOWLOCATION => 1));
					
					//echo "$key . 'is' . $value . '<br>'";
					try{
						
						//set var to array urls
						$track_url = $value;
						//print_r($track_url) ;
						echo "<br>";
						//$embed_info = json_decode($scUser->get('oembed', array('url' => $track_url)));
						
						//embed tracks using url and oembed code
						$embed_info = json_decode($this->scWrap->get('oembed', 
							array('url' => $track_url , 'maxheight' => '150', 'maxwidth' => '400')));
						 
					
						// render the html for the player widget
						print $embed_info->html;
						echo "<br>";
			
					//print_r($embed_info);
					
					}catch (Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
					    exit($e->getMessage());
							}
				}
				
				else{
					//echo "nope <br> ";
					
				}
		
			
			}
		 
		}
		
	}
		// Search for tracks. Embed results
		
	//scUserPin()
		// insert new track into db
		
	//scUserPinDel()
		// delete track in db
	
};


?>