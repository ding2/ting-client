<?php

/**
 * Class for TingClient errors.
 */
class TingClientException extends Exception {
  /**
   * Type for errors not explicitly matched.
   */
  const TYPE_UNKNOWN_ERROR = 0;

  /**
   * Query syntax error.
   */
  const TYPE_SYNTAX_ERROR = 1;

  /**
   * Internal errors.
   */
  const TYPE_INTERNAL_ERROR = 2;

  /**
   * Type of error.
   *
   * @var int
   */
  protected $type = NULL;

  /**
   * Maps error messages to error types.
   *
   * @var array
   */
  protected $errorMapping = array(
    'Query syntax error' => self::TYPE_SYNTAX_ERROR,
    'Unsupported index' => self::TYPE_SYNTAX_ERROR,
    'Unsupported boolean modifier' => self::TYPE_SYNTAX_ERROR,
    'Invalid or unsupported use' => self::TYPE_SYNTAX_ERROR,
    'Internal problem' => self::TYPE_INTERNAL_ERROR,
  );

  /**
   * Get the type of error.
   *
   * Makes it possible to differentiate between syntax, internal and other
   * errors.
   *
   * @return int
   *   The type of error.
   */
  public function getType() {
    if (is_null($this->type)) {
      foreach ($this->errorMapping as $string => $type) {
        if (strpos($this->getMessage(), $string) !== FALSE) {
          $this->type = $type;

          break;
        }
      }

      return $this->type;
    }
  }

}
