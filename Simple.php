<?php

/**
 * @todo
 */
class Simple {

  protected $username, $password;

  public function __construct($username, $password) {
    $this->username = $username;
    $this->password = $password;
  }

  protected function makeRequest($schema, $location, array $data_child_nodes) {
    $document = new \DOMDocument();
    $document->loadXML('<?xml version="1.0" encoding="iso8859-1" ?>
      <document>
          <information>
              <name>Request</name>
              <description>Request from Bioguiden</description>
              <created></created>
              <server>Bioguiden</server>
              <ip>127.0.0.1</ip>
          </information>
          <data></data>
      </document>');
    $xpath = new \DOMXPath($document);
    $xpath->query('/document')->item(0)->setAttribute('schema', $schema);
    $xpath->query('/document/information/created')->item(0)->appendChild(new \DOMText(date('Y-m-d\TH:i:s')));
    foreach ($data_child_nodes as $data_child_node) {
      $xpath->query('/document/data')->item(0)->appendChild($data_child_node);
    }
    $client = new \SoapClient(NULL, [
      'location' => 'https://service.bioguiden.se/' . $location,
      'uri' => 'https://service.bioguiden.se/',
      'soap_version' => SOAP_1_2,
      'trace' => 1,
    ]);
    $response = $client->__soapCall('Export', [
      new \SoapParam($this->username, 'ns1:username'),
      new \SoapParam($this->password, 'ns1:password'),
      new \SoapParam($document->saveXML(), 'ns1:xmlDocument'),
    ], ['soapoperation' => 'Export']);
    return $response->data;
  }

  public function exportRepertoire() {
    $start_date = date('Y-m-d\T00:00:00');
    $end_date = date('Y-m-d\T00:00:00', time() + 7 * 24 * 60 * 60);
    $data_node_children = [
      new \DOMElement('start-date', $start_date),
      new \DOMElement('end-date', $end_date),
    ];
    $response_data = $this->makeRequest('RepertoireExportSchema1_3.xsd', 'repertoireexport.asmx', $data_node_children);
    $screenings = [];
    foreach ($response_data->theatres->theatre->salons->salon as $salon) {
      foreach ($salon->movies->movie as $movie) {
        $movie_id = $movie->{'full-movie-number'};
        $query = substr($movie->{'booking-url'}, strpos($movie->{'booking-url'}, '@') + 1);
        preg_match('/salongnr=(\d*)&tid=(\d\d.\d\d)&datum=([\d-]+)/', $query, $matches);
        $date = new \DateTime($matches[3] . ' ' . $matches[2]);
        $salon = intval($matches[1]);
        $screenings[] = [$movie_id, $date, $salon];
      }
    }
    return $screenings;
  }

  public function exportMovies() {
    $data_node_children = [
      new \DOMElement('movie-updated-date', date('Y-m-d\T00:00:00', time() - 30 * 86400)),
    ];
    $response_data = $this->makeRequest('MoviesExportSchema1_6.xsd', 'moviesexport.asmx', $data_node_children);
    foreach ($response_data->movies->movie as $movie) {
      var_dump($movie);
    }
  }

}
