<?php
/**
 * File containing the Request class.
 *
 * @copyright Copyright (C) 2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */
namespace BD\Bundle\EzIFTTTBundle\IFTTT;

class Request
{
    public $method;

    public $username;

    public $password;

    public $title;

    public $description;

    public function __construct( array $properties = array() )
    {
        foreach ( $properties as $property => $value )
        {
            if ( !property_exists( $this, $property ) )
                throw new \Psr\Log\InvalidArgumentException( "No such property $property" );
            $this->$property = $value;
        }
    }
}
