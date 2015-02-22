<?php
/**
 * @file
 * Contains \Klavaro\Bioguiden\Functions\ServiceInterface.
 */

namespace Klavaro\Bioguiden\Service;

/**
 * Defines a service available on the server.
 */
interface ServiceInterface {

  /**
   * Returns the service path.
   *
   * @return string
   *   The service path, relative to the service base URL.
   *
   * @see \Klavaro\Bioguiden\Client::SERVICE_BASE_URL
   */
  public function getPath();

  /**
   * Adds elements to the <data> node in order to specify the request.
   *
   * @param \DOMElement $data_node
   *   The data node from the template document.
   */
  public function decorateDataNode(\DOMElement $data_node);

  /**
   * Returns the schema string.
   *
   * @return string
   *   The schema string, ending in ".xsd".
   *
   * @todo Understand XSD and provide better description.
   */
  public function getSchema();

}
