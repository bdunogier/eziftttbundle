<?php
/**
 * File containing the Success class.
 *
 * @copyright Copyright (C) 2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */
namespace BD\Bundle\EzIFTTTBundle\Controller\XmlRpcResponse;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class Success extends Response
{
    public function __construct( $message = '' )
    {
        $xml = <<<XML
<?xml version="1.0"?>
<methodResponse>
  <params>
    <param>
      <value>$message</value>
    </param>
  </params>
</methodResponse>
XML;
        parent::__construct(
            $xml,
            200,
            array( 'Content-Type' => 'text/xml' )
        );
    }
}
