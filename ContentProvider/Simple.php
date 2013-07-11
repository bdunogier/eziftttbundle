<?php
/**
 * File containing the Simple class.
 *
 * @copyright Copyright (C) 2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */ 
namespace BD\Bundle\EzIFTTTBundle\ContentProvider;

use BD\Bundle\EzIFTTTBundle\ContentProvider;
use BD\Bundle\IFTTTBundle\IFTTT\Request;

/**
 * Creates a folder out of a Request.
 *
 * - request::title is mapped to the folder's name
 * - request::description is mapped to the folder's short_description
 *   HTML tags are stripped from the description, and each line is placed in a paragraph.
 *
 */
class Simple extends ContentProvider
{
    public function newContentCreateStructFromRequest( Request $request )
    {
        $contentCreateStruct = $this->contentService->newContentCreateStruct(
            $this->contentTypeService->loadContentTypeByIdentifier( 'folder' ),
            'eng-GB'
        );


        $contentCreateStruct->setField( 'name', $request->title );

        $descriptionInnerXml = '';
        foreach ( explode( "\n", strip_tags( $request->description ) ) as $descriptionLine )
        {
            $descriptionInnerXml .= "    <paragraph>{$descriptionLine}</paragraph>\n";
        }

        $descriptionXml = <<< XML
<section xmlns:image="http://ez.no/namespaces/ezpublish3/image/"
         xmlns:xhtml="http://ez.no/namespaces/ezpublish3/xhtml/"
         xmlns:custom="http://ez.no/namespaces/ezpublish3/custom/">
{$descriptionInnerXml}
</section>
XML;

        $contentCreateStruct->setField( 'short_description', $descriptionXml );

        return $contentCreateStruct;
    }

    /**
     * The content is located below the Root folder.
     */
    public function newLocationCreateStructFromRequest( Request $request )
    {
        return $this->locationService->newLocationCreateStruct( 2 );
    }
}
