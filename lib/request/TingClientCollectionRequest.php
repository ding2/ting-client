<?php

class TingClientCollectionRequest extends TingClientSearchRequest {
  protected $id;
  protected $agency;

  public function getObjectId() {
    return $this->id;
  }

  public function setObjectId($id) {
    $this->id = $id;
  }

  public function getAgency() {
    return $this->agency;
  }

  public function setAgency($agency) {
    $this->agency = $agency;
  }

  public function getRequest() {
    $id_array[] = $this->id;
    if(strpos($this->id, $this->agency.'-katalog') !== FALSE) {
      list($owner, $id) = explode(':', $this->id);
      $this->setQuery('rec.id=870970-basis:' . $id . ' OR rec.id=' . $this->id);
    }
    else{
      $this->setQuery('rec.id=' . $this->id);
    }

    $this->setAgency($this->agency);
    $this->setNumResults(1);

    return parent::getRequest();
  }

  public function processResponse(stdClass $response) {
    $response = parent::processResponse($response);

    if (isset($response->collections[0])) {
      return $response->collections[0];
    }
  }
}
