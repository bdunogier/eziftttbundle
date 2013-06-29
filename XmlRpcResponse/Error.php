<?php
/**
 * File containing the Error class.
 *
 * @copyright Copyright (C) 2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */ 
namespace BD\Bundle\EzIFTTTBundle\XmlRpcResponse;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class Error extends Response
{
    public function __construct( $message = '', $code = 500 )
    {
        $content = <<< XML
<?xml version="1.0"?>
<methodResponse>
  <fault>
    <value>
      <struct>
        <member>
          <name>faultCode</name>
          <value><int>{$code}</int></value>
        </member>
        <member>
          <name>faultString</name>
          <value><string>{$message}</string></value>
        </member>
      </struct>
    </value>
  </fault>
</methodResponse>
XML;

        parent::__construct(
            $content,
            200,
            array( 'Content-Type' => 'text/xml' )
        );
    }

}
