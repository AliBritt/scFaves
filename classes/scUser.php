<?php

//access soundcloud api php wrapper
require('includes/Soundcloud.php');

//access scUcred.php
require 'credScUser.php';
//scUser extends new Services soundcloud()?

class scUser{
	//define variables
	private $scWrap				= "";
	
	private $scAuthUrl			= "";
	private $scAccessToken		= "";
	private $messages			= "";
	private $scUserName			= "";
	
	private $seachTerm			= "";
	private $searchResults		= array();
	private $trackResults		= "";
	private $embed_info			= "";
	private $resultsHtml		= "";
	
	private $pinUrl				= "";
	private $trackUrlsAry		= array();
	
	
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
		
		try{
			$this->scAuthUrl = $this->scWrap->getAuthorizeUrl();
		
			header('Location:' . $this->scAuthUrl ); 
			
		}catch(Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
			exit($e->getMessage());
			
		}
		
		//get user deets
		
		try{
			$userDetails = json_decode($this->scWrap->get('me'), true);
			$this->scUserName = $userDetails['username'];
			$_SESSION['scUserName'] = $this->scUserName;
			
		}catch(Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
			exit($e->getMessage());
		}
	}
	
	//userDeets()
		//me - for sc username
	
	public function accessToken(){
		
		if(!isset($_SESSION['scUserToken'])){
			try{
				$this->scAccessToken = $this->scWrap->accessToken($_GET['code']);
					
				//set SESSION to access token(from accesstoken array)
				$_SESSION['scUserToken'] = $this->scAccessToken['access_token'];
				
				
			} catch (Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
				exit($e->getMessage());
				
			}
		}else{
			
			//set our classes accesstoken property to the session data
				
			try{
				$this->scWrap->setAccessToken($_SESSION['scUserToken']);
				
			} catch (Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
				exit($e->getMessage());
			}
		}
	
	}
	
	
	//scUserRecall()
		//connect to db and return urls of embedded pinned tracks
		//or display 'no tracks pinned please search'
		
	//scUserSearch()
	//search by...?
	public function scSearch(){
		
		$this->searchTerm = $_POST['searchTerm'];

		$tracksSearchQ = json_decode($this->scWrap->get('tracks', array('q' => $this->searchTerm)), true);

		//print_r($tracksSearchQ[0]['permalink_url']);

		//select trackurls(permalink url) from returned array
		//cycle through top level array
		foreach($tracksSearchQ as $trackInfo){
			//cycle through track info. array 
			foreach($trackInfo as $key=>$value){

				//and identify urls
				if ($key == 'permalink_url'){
					//not sure what this does
					$this->scWrap->setCurlOptions(array(CURLOPT_FOLLOWLOCATION => 1));
					
					try{
						
						//set var to array urls
						$track_url = $value;
						//create and array of all track urls for use later?for db?
						//array_push($this->trackUrlsAry, $track_url);
					
						//embed tracks using url and oembed code
						$embed_info = json_decode($this->scWrap->get('oembed', 
							array('url' => $track_url , 'maxheight' => '160', 'maxwidth' => '814')));
							
						// render the html for the player widget. add this to $searchResults array
						array_push($this->searchResults, $embed_info->html);
						
					
					}catch (Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
					    exit($e->getMessage());
							}
				}
				
				else{
					//echo "nope <br> ";
					
				}
		
			// end of for each loop
			}
		 // end of top level for each
		}
		
			//create html for all embed html from searchResults array
	 	 foreach ($this->searchResults as $this->trackResults){
					$this->resultsHtml .=
						'<div>
							<form class="form-pinUrl" action="scUserSearch.php" method="POST" id="pinUrlForm">'
								. $this->trackResults
		          				. '<p><a class="btn btn-default" href="#" role="button">Pin &raquo;</a></p>
		          			</form>
        				</div>';
				}
		// store all the html created by multiple embed htmls and above html in session
		//view can access this. may be a better way to do this
		$_SESSION['searchResults'] = $this->resultsHtml;
		
	
	}
	
		
	//scUserPin()
		// insert new track into db
		//get iframe scr into post?or maybe jquery
		//jquery onclick triggers function maybe?
	/*private function scUserPin(){
		//if post conditional - conflict with search post?
		//$this->pinUrl = $_POST['pinUrl'];
		//print $this->pinUrl;
		print_r($this->trackUrlsAry);
	}*/
		
	//scUserPinDel()
		// delete track in db
		//similar method to above
	
};


?>