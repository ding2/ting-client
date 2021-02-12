<?php

/**
 * Ting logger wrapper for the Drupal watchdog API.
 *
 * @see http://api.drupal.org/api/function/watchdog/
 */
class TingClientDrupalWatchDogLogger extends TingClientLogger {

  /**
   * Log a message to the Drupal watchdog.
   *
   * @param string $message
   *   Message to log.
   * @param string $severity
   *   Severity to log with.
   */
  public function doLog($message, $severity) {
    watchdog(
      'ting client',
      '<pre>@message</pre>',
      ['@message' => $message],
      constant('WATCHDOG_' . $severity),
      'http://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]
    );
  }

}
