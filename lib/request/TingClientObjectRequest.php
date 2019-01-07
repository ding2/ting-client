<?php

/**
 * Get a Ting object by ID.
 *
 * Objects requests are much like search request, so this is implemented
 * as a subclass, even though it is a different request type.
 */
class TingClientObjectRequest extends TingClientRequest {
  // When getObject is unable to find an object it will return an object with
  // this title. So to avoid returning "fake" objects with this title and no
  // data, we will have to look for this prefix in the titles of objects
  // returned from getObject.
  const MISSING_OBJECT_TITLE = 'Error: unknown/missing/inaccessible record:';

  protected $agency;
  protected $allRelations;
  protected $format;
  protected $id;
  protected $localIds;
  protected $relationData;
  protected $identifiers;
  protected $profile;
  protected $outputType;
  protected $objectFormat;
  protected $fausts;

  public function setObjectFormat($objectFormat) {
    $this->objectFormat = $objectFormat;
  }

  public function getObjectFormat() {
    return $this->objectFormat;
  }

  public function setOutputType($outputType) {
    $this->outputType = $outputType;
  }

  public function getOutputType() {
    return $this->outputType;
  }

  public function getProfile() {
    return $this->profile;
  }
  public function setProfile($profile) {
    $this->profile = $profile;
  }
  public function getAgency() {
    return $this->agency;
  }

  public function setAgency($agency) {
    $this->agency = $agency;
  }

  public function getAllRelations() {
    return $this->allRelations;
  }

  public function setAllRelations($allRelations) {
    $this->allRelations = $allRelations;
  }

  public function getFormat() {
    return $this->format;
  }

  public function setFormat($format) {
    $this->format = $format;
  }

  public function getLocalIds() {
    return $this->localIds;
  }

  public function setLocalId($localId) {
    $this->localIds = array($localId);
  }

  public function setLocalIds($localIds) {
    $this->localIds = $localIds;
  }

  public function getObjectIds() {
    return $this->identifiers;
  }

  public function setObjectId($id) {
    $this->identifiers = array($id);
  }

  public function setObjectIds(array $ids) {
    $this->identifiers = $ids;
  }

  public function getRelationData() {
    return $this->relationData;
  }

  public function setRelationData($relationData) {
    $this->relationData = $relationData;
  }

  public function getRequest() {
    $parameters = $this->getParameters();

    $this->useAuth();

    // These defaults are always needed.
    $this->setParameter('action', 'getObjectRequest');

    if (!isset($parameters['format']) || empty($parameters['format'])) {
      $this->setParameter('format', 'dkabm');
    }

    // Determine which id to use.
    if ($this->identifiers) {
      $this->setParameter('identifier', $this->identifiers);
    }
    elseif ($this->localIds) {
      $this->setParameter('localIdentifier', $this->localIds);
    }

    $methodParameterMap = array(
      'format' => 'format',
      'allRelations' => 'allRelations',
      'relationData' => 'relationData',
      'agency' => 'agency',
      'profile' => 'profile',
      'outputType' => 'outputType',
      'objectFormat' => 'objectFormat',
    );

    foreach ($methodParameterMap as $method => $parameter) {
      $getter = 'get' . ucfirst($method);
      if ($value = $this->$getter()) {
        $this->setParameter($parameter, $value);
      }
    }

    return $this;
  }

  public function processResponse(stdClass $response) {
    // Use TingClientSearchRequest::processResponse for processing the
    // response from Ting.
    $searchRequest = new TingClientSearchRequest(NULL);
    $response = $searchRequest->processResponse($response);

    // As the get object request can return more than one object we need to
    // extract them.
    $objects = array();
    foreach ($response->collections as $collection) {
      foreach ($collection->objects as $object) {
        $title = isset($object->record['dc:title'][''][0]) ? $object->record['dc:title'][''][0] : '';
        // Ensure that getObject was able to finde the object.
        if (strpos($title, self::MISSING_OBJECT_TITLE) !== 0) {
          $objects[$object->id] = $object;
        }
      }
    }

    return $objects;
  }
}
