<?php
/**
 * File containing the RequestParser class.
 *
 * @copyright Copyright (C) 2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */ 
namespace BD\Bundle\EzIFTTTBundle\IFTTT;

use BD\Bundle\EzIFTTTBundle\IFTTT\Request;

/**
 * Parses an IFTTT request
 */
class RequestParser
{
    /** @var \SimpleXMLElement */
    private $simpleXml;

    /**
     * @param $xmlString
     *
     * @throws \UnexpectedValueException If the XML payload could not be parsed
     */
    public function __construct( $xmlString )
    {
        if ( ( $this->simpleXml = simplexml_load_string( $xmlString ) ) === false )
        {
            $errors = array();
            foreach( libxml_get_errors() as $error )
                $errors[] = $error->message;
            throw new \UnexpectedValueException( "Invalid xmlString argument:" . implode( "\n", $errors ) );
        }
    }

    /**
     * @return \BD\Bundle\EzIFTTTBundle\IFTTT\Request
     */
    public function getRequest()
    {
        $requestProperties = array(
            'method' => (string)$this->simpleXml->methodName,
            'username' => (string)$this->simpleXml->params->param[1]->value->string,
            'password' => (string)$this->simpleXml->params->param[2]->value->string,
        );

        //@see content in the wordpress docs
        if ( isset( $this->simpleXml->params->param[3]->value->struct->member ) )
        {
            foreach ( $this->simpleXml->params->param[3]->value->struct->member as $data )
            {
                switch ( (string)$data->name )
                {
                    case 'title':
                        $requestProperties['title'] = (string)$data->value->string;
                        break;

                    case 'description':
                        $requestProperties['description'] = (string)$data->value->string;
                        break;
                }
            }
        }


        return new Request( $requestProperties );
    }
}
