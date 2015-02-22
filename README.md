Bioguiden
=========

PHP Client for the SOAP API provided by Swedish cinema database
[Bioguiden][bioguiden].

Basic usage
-----------

The library can be imported using [Composer][composer], which includes class
autoloading support.

    <?php
    $client = new Client('username', 'password', new RepertoireExport());
    $response = $client->request();
    var_dump($response);
    ?>

Planned milestones
------------------

### Version 1.0.0

* Support the RepertoireExport service with limited parameters.

[bioguiden]: http://admin.bioguiden.se/Browser/WebServiceDoc/StartPage.aspx
[composer]: https://getcomposer.org/doc/00-intro.md
