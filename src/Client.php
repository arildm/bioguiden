<?php
/**
 * @file
 * Contains \Klavaro\Bioguiden\Client.
 */

namespace Klavaro\Bioguiden;
use Klavaro\Bioguiden\Document\Document;
use Klavaro\Bioguiden\Service\ServiceInterface;

/**
 * SOAP client for Bioguiden services.
 */
class Client extends \SoapClient {

  /**
   * Base URL for the service location, ending in '/'.
   */
  const SERVICE_BASE_URL = 'https://service.bioguiden.se/';

  /**
   * Username for accessing the service.
   *
   * @var string
   */
  protected $username;

  /**
   * Password for accessing the service.
   *
   * @var string
   */
  protected $password;

  /**
   * The request XML document.
   *
   * @var \Klavaro\Bioguiden\Document\Document
   */
  protected $requestDocument;

  /**
   * The service definition to use with the request.
   *
   * @var \Klavaro\Bioguiden\Service\ServiceInterface
   */
  protected $service;

  /**
   * Constructs a Bioguiden SOAP client.
   *
   * @param string $username
   *   Username for accessing the service.
   * @param string $password
   *   Password for accessing the service.
   * @param \Klavaro\Bioguiden\Service\ServiceInterface $service
   *   The service definition.
   */
  public function __construct($username, $password, ServiceInterface $service) {
    parent::__construct(NULL, [
      'location' => static::SERVICE_BASE_URL . $service->getPath(),
      'uri' => static::SERVICE_BASE_URL,
      'soap_version' => SOAP_1_2,
      'trace' => 1,
    ]);
    $this->username = $username;
    $this->password = $password;
    $this->requestDocument = new Document($service->getSchema());
    $this->service = $service;
  }

  /**
   * Generic SOAP call implementation.
   *
   * This may be called by function-specific subclasses.
   *
   * @throws \DOMException
   *   If the SOAP call fails.
   */
  protected function sendRequest() {
    return $this->__soapCall('Export', [
      new \SoapParam($this->username, 'ns1:username'),
      new \SoapParam($this->password, 'ns1:password'),
      new \SoapParam($this->requestDocument->serialize(), 'ns1:xmlDocument'),
    ], [
      'soapoperation' => 'Export',
    ]);
  }

  /**
   * Performs the specific SOAP request.
   *
   * @return mixed
   *   The response from the request.
   *
   * @throws \DOMException
   *   If the SOAP call fails.
   */
  public function request() {
    $this->service->decorateDataNode($this->requestDocument->getDataNode());
    $response = $this->sendRequest();
    return $response;
  }

}
