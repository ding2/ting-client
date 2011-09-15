<?php
class TingClientInfomediaReviewRequest extends TingClientInfomediaRequest {
  protected $isbn;

  public function setISBN($isbn) {
    $this->isbn = $isbn;
  }

  public function getISBN() {
    return $this->isbn;
  }

  public function getRequest() {
    $options = array('workIdentifier' => array('faust', 'isbn',),
                     'libraryCode' => 'agency',
                     'userId' => 'user',
                     'userPinCode' => 'pin',);

    $action = $this->method . self::REVIEW. 'Request';

    $this->setParameter('action', $action);

    foreach ($options as $param => $value_name) {
      if (is_array($value_name)) {
        foreach ($value_name as $item)
          if (isset($this->$item)) {
            $this->setParameter($param, array($item => $this->$item));
            break;
          }
      }
      else
        if (isset($this->$value_name))
          $this->setParameter($param, $this->$value_name);
    }

    $this->setParameter('outputType', 'xml'); 
    return $this; 
  }

  public function parse($responseString) {
    $result = new TingClientInfomediaResult();
    $result->type = self::REVIEW;
    $dom = new DOMDocument();
    $dom->loadXML($responseString);
    $xpath = new DOMXPath($dom);
    $responseNode = '/uaim:' . $this->method . 'ReviewResponse';
    $detailsNode = '/uaim:' . $this->method . 'ReviewResponseDetails';
    $errorNode = '/uaim:error';
    $articleNode = '/uaim:imArticle'; 
    $nodelist = $xpath->query($responseNode);

    if ($nodelist->length == 0)
      return $result;
      #throw new TingClientException('TingClientInfomediaRequest got no Infomedia response: ', $responseString);

    $errorlist = $xpath->query($responseNode . $errorNode);

    if ($errorlist->length > 0) {
      $result->error = $errorlist->item(0)->nodeValue;
      return $result;
    }
      
    $detailslist = $xpath->query($responseNode . $detailsNode);
    $result->length = $detailslist->length;
    $identifierlist = $xpath->query($responseNode . $detailsNode . '/uaim:workIdentifier');
    $countlist = $xpath->query($responseNode . $detailsNode . '/uaim:reviewsCount');

    if ($this->method == 'check') { 
      for ($i = 0; $i < $detailslist->length; $i++) {
        $identifier = $identifierlist->item($i)->nodeValue;
        $count = $countlist->item($i)->nodeValue;
        $result->parts[] = array('identifier' => $identifier, 'count' => (int) $count);
      } 
    }
    else { 
      for ($i = 0; $i < $detailslist->length; $i++) {
        $identifier = $identifierlist->item($i)->nodeValue;
        $count = $countlist->item($i)->nodeValue;
        $identifiers = $xpath->query('uaim:articleIdentifier', $detailslist->item($i));
        $articleidentifiers = array();

        for ($j = 0; $j < $identifiers->length; $j++) {
          $articleidentifiers[] = $identifiers->item($j)->nodeValue; 
        }

        $articlelist = $xpath->query('uaim:imArticle', $detailslist->item($i));
        $articles = array();

        for ($j = 0; $j < $articlelist->length; $j++) {
          $articles[] = $articlelist->item($j)->nodeValue; 
        }

        $result->parts[] = array('identifier' => $identifier, 'count' => (int) $count, 'identifier_list' => $articleidentifiers, 'article_list' => $articles);
      } 
    } 
    
    return $result;
  }
} 
