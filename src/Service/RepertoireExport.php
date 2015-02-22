<?php
/**
 * @file
 * Contains \Klavaro\Bioguiden\RepertoireExport.
 */

namespace Klavaro\Bioguiden\Service;

/**
 * Defines the service for exporting a repertoire.
 *
 * The salon criteria is not supported, and date criteria are hardcoded to the
 * week starting today.
 */
class RepertoireExport implements ServiceInterface {

  /**
   * {@inheritdoc}
   */
  public function getPath() {
    return 'repertoireexport.asmx';
  }

  /**
   * {@inheritdoc}
   */
  public function getSchema() {
    return 'RepertoireExportSchema1_3.xsd';
  }

  /**
   * {@inheritdoc}
   */
  public function decorateDataNode(\DOMElement $data_node) {
    // Start date is the start of today.
    $start_date = date('Y-m-d\T00:00:00');
    // End date is one week from now.
    $end_date = date('Y-m-d\T00:00:00', time() + 7 * 24 * 60 * 60);

    $data_node->appendChild(new \DOMElement('start-date', $start_date));
    $data_node->appendChild(new \DOMElement('end-date', $end_date));
  }

}
