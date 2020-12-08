<?php
/**
 * @file
 * TingClientMarcXchnageRequest class.
 */

class TingClientMarcXchangeRequest extends TingClientRequest {
  protected $agency;
  protected $profile;
  protected $identifier;

  /**
   * Set agency.
   *
   * @param string $agency
   *   Well agency identifier.
   */
  public function setAgency($agency) {
    $this->agency = $agency;
  }

  /**
   * Set profile.
   *
   * @param string $profile
   *   Well profile name.
   */
  public function setProfile($profile) {
    $this->profile = $profile;
  }

  /**
   * Set indentifiers for this object request.
   *
   * @param mixed $id
   *   A string with id or an array of ids if multiple object is being fetched.
   */
  public function setIdentifier($id) {
    $this->identifier = $id;
  }

  /**
   * {@inheritdoc}
   */
  public function getRequest() {
    $this->useAuth();

    // Hardcoded defaults.
    $this->setParameter('action', 'getObjectRequest');
    $this->setParameter('outputType', 'json');
    $this->setParameter('objectFormat', 'marcxchange');

    // Library settings.
    $this->setParameter('agency', $this->agency);
    $this->setParameter('profile', $this->profile);

    // Object ID (agency:faust).
    $this->setParameter('identifier', $this->identifier);

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function processResponse(stdClass $response) {
    // Check for errors.
    if (!empty($response->searchResponse->error)) {
      throw new TingMarcException($response->searchResponse->error);
    }

    $objects = [];

    // Collect each marc object from the search result.
    foreach ($response->searchResponse->result->searchResult as $result) {
      if (is_array($result->collection->object)) {
        $object = $result->collection->object[0];
        $objects[$object->identifier->{'$'}] = new TingMarcResult($object);
      }
      else {
        $object = $result->collection->object;
        watchdog_exception('opensearch', new TingClientException('Unexpected error on object in getObject response: ' . var_export($object, TRUE)));
      }
    }

    return $objects;
  }

}
