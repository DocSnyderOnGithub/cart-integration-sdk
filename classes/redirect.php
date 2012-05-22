<?php
/**
 *
 * Enter description here ...
 * @author Shopgate GmbH, 35510 Butzbach, DE
 *
 */
class ShopgateMobileRedirect extends ShopgateObject {
	const SHOPGATE_STATIC = 'http://static.shopgate.com';
	const SHOPGATE_STATIC_SSL = 'https://static-ssl.shopgate.com';
	
	/**
	 * @var string the URL that is appended to the end of a shop alias (aka subdomain)
	 */
	const SHOPGATE_ALIAS = '.shopgate.com';
	
	/**
	 * @var string name of the cookie to set in case a customer turns of mobile redirect
	 */
	const COOKIE_NAME = 'SHOPGATE_MOBILE_WEBPAGE';
	
	/**
	 * @var int (hours) the minimum time that can be set for updating of the cache
	 */
	const MIN_CACHE_TIME = 1;
	
	/**
	 * @var int (hours) the default time to be set for updating the cache
	 */
	const DEFAULT_CACHE_TIME = 24;
	
	
	/**
	 * @var string alias name of shop at Shopgate, e.g. 'yourshop' to redirect to 'https://yourshop.shopgate.com'
	 */
	protected $alias = '';
	
	/**
	 * @var string your shops cname entry to redirect to
	 */
	protected $cname = '';
	
	/**
	 * @var string[] list of strings that cause redirection if they occur in the client's user agent
	 */
	protected $redirectKeywords = array('iPhone', 'iPod', 'iPad', 'Android', 'Windows Phone OS 7.0', 'Bada');
	
	/**
	 * @var string[] list of strings that deny redirection if they occur in the client's user agent; overrides $this->redirectKeywords
	 */
	protected $skipRedirectKeywords = array();
	
	/**
	 * @var string
	 */
	protected $cacheFilePath;
	
	/**
	 * @var bool
	 */
	protected $updateRedirectKeywords;
	
	/**
	 * @var int (hours)
	 */
	protected $redirectKeywordCacheTime;
	
	/**
	 * @var bool true in case the website is delivered via HTTPS (this will load the Shopgate javascript via HTTPS as well to avoid browser warnings)
	 */
	protected $useSecureConnection;
	
	
	/**
	 * @var string
	 */
	protected $redirectScriptFilePath;
	
	/**
	 * @var string name of the cookie that indicates deactivation of the redirect
	 */
	protected $jsCookieName;
	
	/**
	 * @var int time (in days) after which the cookie expires
	 */
	protected $jsCookieLife;
	
	/**
	 * @var string 'true' <==> mobile header is displayed below page content; 'false' <==> mobile header is displayed above page content
	 */
	protected $jsDisplayBelowContent;
	
	/**
	 * @var string html id of the mobile header container
	 */
	protected $jsMobileHeaderId;
	
	/**
	 * @var string html id of the button container
	 */
	protected $jsButtonWrapperId;
	
	/**
	 * @var string url to the image for the "switched on" button
	 */
	protected $jsButtonOnImageSource;
	
	/**
	 * @var string url to the image for the "switched off" button
	 */
	protected $jsButtonOffImageSource;
	
	/**
	 * @var string name of the class for the "switched on" button
	 */
	protected $jsButtonOnCssClass;
	
	/**
	* @var string name of the class for the "switched off" button
	*/
	protected $jsButtonOffCssClass;
	
	/**
	 * @var a JQuery style selector for the parent element the header is attached to
	 */
	protected $jsHeaderParentSelector;
	
	/**
	 * @var string description to be displayed to the left of the button
	 */
	protected $jsButtonDescription;
	
	public function initLibrary() {
		$this->updateRedirectKeywords = false;
		$this->redirectKeywordCacheTime = self::DEFAULT_CACHE_TIME;
		$this->cacheFile = dirname(__FILE__).'/../temp/cache/redirect_keywords.txt';
		$this->useSecureConnection = isset($_SERVER["HTTPS"]) && ($_SERVER["HTTPS"] === "on" || $_SERVER["HTTPS"] == "1");
		
		// javascript mobile header options
		$this->redirectScriptFilePath = dirname(__FILE__).'/../assets/mobile_header_min.js';
		$this->jsCookieName = self::COOKIE_NAME;
		$this->jsCookieLife = 7;
		$this->jsDisplayBelowContent = 'false';
		$this->jsMobileHeaderId = 'shopgate_mobile_header';
		$this->jsButtonWrapperId = 'shopgate_mobile_button';
		$this->jsButtonOnImageSource = (($this->useSecureConnection) ? self::SHOPGATE_STATIC_SSL : self::SHOPGATE_STATIC).'/api/mobile_header/button_on.png';
		$this->jsButtonOffImageSource = (($this->useSecureConnection) ? self::SHOPGATE_STATIC_SSL : self::SHOPGATE_STATIC).'/api/mobile_header/button_off.png';
		$this->jsButtonOnCssClass = 'sg_mobile_button_on';
		$this->jsButtonOffCssClass = 'sg_mobile_button_off';
		$this->jsHeaderParentSelector = 'body';
		$this->jsButtonDescription = 'Mobile Webseite aktivieren';
	}
	
	
	####################
	# general settings #
	####################
	
	/**
	 * Sets the alias of the Shopgate shop
	 *
	 * @param string $alias
	 */
	public function setAlias($alias){
		$this->alias = $alias;
	}
	
	/**
	 * Sets the cname of the shop
	 */
	public function setCustomMobileUrl($cname){
		$this->cname = $cname;
	}
	
	/**
	 * Enables updating of the keywords that identify mobile devices from Shopgate Merchant API.
	 *
	 * @param int $cacheTime Time the keywords are cached in hours. Will be set to at least self::MIN_CACHE_TIME.
	 */
	public function enableKeywordUpdate($cacheTime = self::DEFAULT_CACHE_TIME) {
		$this->updateKeywords = true;
		$this->keywordCacheTime = ($cacheTime >= self::MIN_CACHE_TIME) ? $cacheTime : self::MIN_CACHE_TIME;
	}
	
	/**
	 * Disables updating of the keywords that identify mobile devices from Shopgate Merchant API.
	 */
	public function disableKeywordUpdate() {
		$this->updateKeywords = false;
	}
	
	/**
	 * Appends a new keyword to the redirect keywords list.
	 *
	 * @param string $keyword The redirect keyword to append.
	 */
	public function addRedirectKeyword($keyword){
		if(is_array($keyword)){
			$this->redirectKeywords = array_merge($this->redirectKeywords, $keyword);
		} else {
			$this->redirectKeywords[] = $keyword;
		}
	}
	
	/**
	 * Removes a keyword or an array of redirect keywords from the keywords list.
	 *
	 * @param string|string[] $keyword The redirect keyword or keywords to remove.
	 */
	public function removeRedirectKeyword($keyword){
		if(is_array($keyword)){
			foreach($keyword as $word){
				foreach($this->redirectKeywords as $key => $mobileKeyword){
					if(mb_strtolower($word) == mb_strtolower($mobileKeyword)){
						unset($this->redirectKeywords[$key]);
					}
				}
			}
		} else {
			foreach($this->redirectKeywords as $key => $mobileKeyword){
				if(mb_strtolower($keyword) == mb_strtolower($mobileKeyword)){
					unset($this->redirectKeywords[$key]);
				}
			}
		}
	}
	
	/**
	 * Replaces the current list of redirect keywords with a given list.
	 *
	 * @param string[] $redirectKeywords The new list of redirect keywords.
	 */
	public function setRedirectKeywords(array $redirectKeywords){
		$this->redirectKeywords = $redirectKeywords;
	}
	
	/**
	 * Replaces the current list of skiüp redirect keywords with a given list.
	 *
	 * @param string[] $skipRedirectKeywords The new list of skip redirect keywords.
	 */
	public function setSkipRedirectKeywords(array $skipRedirectKeywords){
		$this->skipRedirectKeywords = $skipRedirectKeywords;
	}
	
	/**
	 * Detects by redirect keywords (and skip redirect keywords) if a request was sent by a mobile device.
	 *
	 * @return bool true if a mobile device could be detected, false otherwise.
	 */
	public function isMobileRequest(){
		// find user agent
		$userAgent = '';
		if(!empty($_SERVER['HTTP_USER_AGENT'])){
			$userAgent = $_SERVER['HTTP_USER_AGENT'];
		} else {
			return false;
		}
		
		// update keywords if enabled
		$this->updateRedirectKeywords();
		
		// check user agent for redirection keywords and skip redirection keywords and return the result
		return
			(!empty($this->redirectKeywords)     ?  preg_match('/'.implode('|', $this->redirectKeywords).'/', $userAgent)     : false) &&
			(!empty($this->skipRedirectKeywords) ? !preg_match('/'.implode('|', $this->skipRedirectKeywords).'/', $userAgent) : true);
	}
	
	/**
	 * Detects whether the customer wants to be redirected.
	 *
	 * @return bool true if the customer wants to be redirected, false otherwise.
	 */
	public function isRedirectAllowed() {
		// if GET parameter is set create cookie and do not redirect
		if (!empty($_GET['shopgate_redirect'])) {
			setcookie(self::COOKIE_NAME, 1);
			return false;
		}
		
		
		return empty($_COOKIE[self::COOKIE_NAME]) ? true : false;
	}
	
	/**
	 * Redirects to a given (valid) URL.
	 *
	 * If the $url parameter is no valid URL the method will simply return false and do nothing else.
	 * Otherwise it will output the necessary redirection headers and stop script execution.
	 *
	 * @param string $url the URL to redirect to
	 * @param bool $setCookie true to set the redirection cookie and activate redirection
	 * @return false if the passed $url parameter is no valid URL
	 */
	public function redirect($url) {
		// validate url
		if (!preg_match('#^(http|https)\://#', $url)) {
			return false;
		}
		
		// perform redirect
		header("Location: ". $url, true, 302);
		exit;
	}
	
	/**
	 * Fetches contents of the java script file, replaces the variable strings with $this->js* settings and returns the script
	 * inside script tags.
	 *
	 * @return string
	 */
	public function getJavascript() {
		if (!file_exists($this->redirectScriptFilePath)) {
			return '';
		}
		
		$script = @file_get_contents($this->redirectScriptFilePath);
		if (empty($script)) {
			return '';
		}
		
		// set parameters
		foreach (get_object_vars($this) as $attribute => $value) {
			$script = str_replace('{$'.$attribute.'}', $value, $script);
		}
		
		return '<script type="text/javascript">'.$script.'</script>';
	}
	
	
	#######################
	# javascript settings #
	#######################
	
	/**
	 * Call to display mobile header below page content.
	 */
	public function setDisplayBelowContent() {
		$this->jsDisplayBelowContent = 'true';
	}
	
	/**
	 * Sets the parent element for the mobile header (default: 'body').
	 *
	 * @param string $selector a JQuery style selector to identify the desired parent element
	 */
	public function setHeaderParentSelector($selector) {
		$this->jsHeaderParentSelector = $selector;
	}
	
	/**
	 * Sets the description to be displayed to the left of the button.
	 *
	 * @param string $description
	 */
	public function setButtonDescription($description) {
		$this->jsButtonDescription = description;
	}
	
	
	###############
	### helpers ###
	###############
	
	/**
	 * Generates the root mobile Url for the redirect
	 */
	private function _getMobileUrl(){
		if(!empty($this->cname)){
			return $this->cname;
		} elseif(!empty($this->alias)){
			return 'https://'.$this->alias.self::SHOPGATE_ALIAS;
		}
	}
	
	/**
	 * Updates the keywords array from cache file or Shopgate Merchant API if enabled.
	 */
	protected function updateRedirectKeywords() {
		if (!$this->updateKeywords) return;
		
		$saveKeywords = false;
		
		if(file_exists($this->cacheFilePath)){
			
			$fp = @fopen($this->cacheFilePath);
			
			if(!$fp){
				return;
			}
			
			$lastRedirectKeywordsUpdate = 0;
			$redirectKeywords = array();
			$firstLine = true;
			while($line = fgets($fp)){
				if($firstLine){
					$lastRedirectKeywordsUpdate = $line;
					$firstLine = false;
					if ((time() - ($lastRedirectKeywordsUpdate + $this->keywordCacheTime) > 0)) {
						try{
							$redirectKeywords = ShopgateMerchantApi::getInstance()->getMobileRedirectKeywords();
							
							// save keywords in file
							$saveKeywords = true;
							
							break;
						} catch(Exception $ex){
							continue;
						}
					}
					continue;
				}
				$redirectKeywords[] = $line;
			}
			@fclose($fp);
			
			$this->redirectKeywords = $redirectKeywords;
		} else {
			try{
				$redirectKeywords = ShopgateMerchantApi::getInstance()->getMobileRedirectKeywords();
					
				// save keywords in file
				$saveKeywords = true;
				
				$this->redirectKeywords = $redirectKeywords;
					
				break;
			} catch(Exception $ex){
			}
		}
		
		if($saveKeywords){
			// Save the keywords in cache
			$fp = @fopen($this->cacheFilePath, 'w');
			
			if(!$fp){
				return false;
			}
			
			fwrite($fp, time()."\n");
			foreach($this->redirectKeywords as $redirectKeyWord){
				fwrite($fp, $redirectKeyWord."\n");
			}
			fclose($fp);
		}
	}
	
	#############################
	### mobile url generation ###
	#############################
	
	/**
	 * Create a mobile-shop-url to the startmenu
	 */
	public function getShopUrl(){
		return $this->_getMobileUrl();
	}
	
	/**
	 * Create a mobile-product-url to a item
	 *
	 * @param String $itemNumber
	 */
	public function getItemUrl($itemNumber){
		return $this->_getMobileUrl().'/item/'.bin2hex($itemNumber);
	}
	
	/**
	 * Create a mobile-category-url to a category
	 *
	 * @param String $categoryNumber
	 */
	public function getCategoryUrl($categoryNumber){
		return $this->_getMobileUrl().'/category/'.bin2hex($categoryNumber);
	}
	
	/**
	 * Create a mobile-cms-url to a cms-page
	 *
	 * @param String $cmsKey
	 */
	public function getCmsUrl($cmsKey){
		return $this->_getMobileUrl().'/cms/'.$key;
	}
	
	/**
	 * Create a mobile-brand-url to a page with results for a specific manufacturer
	 *
	 * @param String $manufacturer
	 */
	public function getBrandUrl($manufacturerName){
		return $this->_getMobileUrl().'/brand/'.bin2hex($manufacturerName);
	}
	
	/**
	 * Create a mobile-search-url to a page with search results
	 *
	 * @param unknown_type $searchString
	 */
	public function getSearchUrl($searchString){
		return $this->_getMobileUrl().'/search/'.$searchString;
	}
	
}