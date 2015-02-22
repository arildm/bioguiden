<?php
/**
 * @file
 * Contains \Klavaro\Bioguiden\Document.
 */

namespace Klavaro\Bioguiden\Document;

/**
 * An XML document used as request to the service.
 */
class Document {

  /**
   * The XML document.
   *
   * @var \DOMDocument
   */
  protected $document;

  /**
   * Constructs a new Document object from a base template.
   *
   * @param string $schema
   *   The document schema.
   */
  public function __construct($schema) {
    $document_base = file_get_contents(__DIR__ . '/document_template.xml');
    $this->document = new \DOMDocument();
    $this->document->loadXML($document_base);
    $this->xpathElement('/document')->setAttribute('schema', $schema);
    $this->setText('/document/information/created', date('Y-m-d\TH:i:s'));
  }

  /**
   * Returns the <data> node, by which the request is specified.
   *
   * @return \DOMElement
   *   The data node.
   */
  public function getDataNode() {
    return $this->xpathElement('/document/data');
  }

  /**
   * Performs an XPath query on the document.
   *
   * @param string $xpath
   *   XPath query to perform.
   *
   * @return \DOMNodeList
   *   All nodes matching the given XPath expression. Any expression which does
   *   not return nodes will return an empty DOMNodeList.
   */
  public function xpath($xpath) {
    $dom_xpath = new \DOMXpath($this->document);
    return $dom_xpath->query($xpath);
  }

  /**
   * Performs an XPath query on the document, for an element specifically.
   *
   * @param string $xpath
   *   XPath query to perform.
   *
   * @return \DOMElement
   *   The first node that matches the query. If no node matches, this returns
   *   NULL.
   *
   * @throws \UnexpectedValueException
   *   If the first matching node is not an element (but text).
   */
  public function xpathElement($xpath) {
    $node = $this->xpath($xpath)->item(0);
    if (!$node instanceof \DOMElement) {
      throw new \UnexpectedValueException('Identified node is not an element.');
    }
    return $node;
  }

  /**
   * Returns the document as a string.
   *
   * @return string
   *   The document.
   */
  public function serialize() {
    return $this->document->saveXML();
  }

  /**
   * Set the text content of a node.
   *
   * @param string $xpath
   *   XPath query to identify the node to modify. If the query matches multiple
   *   elements, only the first is used.
   * @param string $text
   *   The text to set on the node.
   */
  protected function setText($xpath, $text) {
    // Get requested node.
    $node = $this->xpathElement($xpath);
    if (empty($node)) {
      throw new \UnexpectedValueException('Requested node not found.');
    }

    // Remove any existing children.
    while ($node->hasChildNodes()) {
      $node->removeChild($node->firstChild);
    }
    // Add text node.
    $node->appendChild(new \DOMText($text));
  }

}
