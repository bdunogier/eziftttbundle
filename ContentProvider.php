<?php
/**
 * File containing the ${NAME} class.
 *
 * @copyright Copyright (C) 2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace BD\Bundle\EzIFTTTBundle;

use BD\Bundle\IFTTTBundle\IFTTT\Request;
use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\API\Repository\ContentTypeService;
use eZ\Publish\API\Repository\LocationService;

abstract class ContentProvider
{
    /**
     * @var ContentService
     */
    protected $contentService;

    /**
     * @var ContentTypeService
     */
    protected $contentTypeService;

    /**
     * @var ContentService
     */
    protected $locationService;

    public function __construct( ContentService $contentService, ContentTypeService $contentTypeService, LocationService $locationService )
    {
        $this->contentService = $contentService;
        $this->contentTypeService = $contentTypeService;
        $this->locationService = $locationService;
    }

    /**
     * @eturn ContentCreateStruct
     */
    abstract public function newContentCreateStructFromRequest( Request $request );

    /**
     * @return LocationCreateStruct
     */
    abstract public function newLocationCreateStructFromRequest( Request $request );
}