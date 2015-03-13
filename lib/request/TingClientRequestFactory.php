<?php

class TingClientRequestFactory {
	public function __construct($urls) {
		$this->urls = $urls;
	}

  /**
   * Get certain webservice url.
   *
   * @param string $key
   *   WebService link identitifer as mapped in the ting.client.inc file,
   *   line 426.
   * @see ting.client.inc
   *
   * @return String
   *   Webservice url, if any. NULL if entry not found.
   */
  public function getRequestUrl($key){
    $url = isset($this->urls[$key]) ? $this->urls[$key] : NULL;

    return $url;
  }

	/**
	 * @return TingClientSearchRequest
	 */
	public function getSearchRequest() {
		if (empty($this->urls['search'])) {
			return FALSE;
		}
		return new TingClientSearchRequest($this->urls['search']);
	}

	/**
	 * @return TingClientScanRequest
	 */
	public function getScanRequest() {
		if (empty($this->urls['scan'])) {
			return FALSE;
		}
		return new TingClientScanRequest($this->urls['scan']);
	}

	/**
	 * @return TingClientCollectionRequest
	 */
	public function getCollectionRequest() {
		if (empty($this->urls['collection'])) {
			return FALSE;
		}
		return new TingClientCollectionRequest($this->urls['collection']);
	}

	/**
	 * @return TingClientObjectRequest
	 */
	public function getObjectRequest() {
		if (empty($this->urls['object'])) {
			return FALSE;
		}
		return new TingClientObjectRequest($this->urls['object']);
	}

	/**
	 * @return TingClientSpellRequest
	 */
	public function getSpellRequest() {
		if (empty($this->urls['spell'])) {
			return FALSE;
		}
		return new TingClientSpellRequest($this->urls['spell']);
	}

	/**
	 * @return TingClientObjectRecommendationRequest
	 */
	function getObjectRecommendationRequest() {
		if (empty($this->urls['recommendation'])) {
			return FALSE;
		}
		return new TingClientObjectRecommendationRequest($this->urls['recommendation']);
	}

	/**
	 * @ return TingClientInfomediaArticleRequest
	 */
	function getInfomediaArticleRequest(){
		if (empty($this->urls['infomedia'])) {
			return FALSE;
		}
		return new TingClientInfomediaArticleRequest($this->urls['infomedia']);
	}

	/**
	 * @return TingClientInfomediaReviewRequest
	 */
	function getInfomediaReviewRequest(){
		if (empty($this->urls['infomedia'])) {
			return FALSE;
		}
		return new TingClientInfomediaReviewRequest($this->urls['infomedia']);
	}

	/**
	 * @return TingFulltextRequest
	 */
	function getFulltextRequest() {
		if (empty($this->urls['object'])) {
			return FALSE;
		}
		return new TingFulltextRequest($this->urls['object']);
	}
}
