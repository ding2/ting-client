<?php
abstract class TingClientInfomediaRequest extends TingClientRequest {
  const ARTICLE = 'Article';
  const REVIEW  = 'Review';
  protected $method;
  protected $type;
  protected $pin;
  protected $user;
  protected $faust;

  public function makeGet() {
    $this->method = 'get';
  }

  public function makeCheck() {
    $this->method = 'check';
  }

  public function getMethod() {
    return $this->method;
  }

  public function setAgency($agency) {
    $this->agency = $agency;
  }

  public function getAgency() {
    return $this->agency;
  }

  public function setPin($pin) {
    $this->pin = $pin;
  }

  public function getPin() {
    return $this->pin;
  }

  public function setUser($user) {
    $this->user = $user;
  }

  public function getUser() {
    return $this->user;
  }

  public function setFaust($faust) {
    $this->faust = $faust;
  }

  public function getFaust() {
    return $this->faust;
  } 

  public function processResponse(stdClass $response) {
    return $response;
  } 

  public function parseResponse($responseString) {
    return $responseString;
  }
}
