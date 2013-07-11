<?php
/**
 * File containing the Handler class.
 *
 * @copyright Copyright (C) 2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */
namespace BD\Bundle\EzIFTTTBundle\IFTTT;

use BD\Bundle\EzIFTTTBundle\ContentProvider;
use BD\Bundle\IFTTTBundle\IFTTT\Handler as IFTTTHandler;
use BD\Bundle\IFTTTBundle\IFTTT\Request;
use eZ\Publish\API\Repository\Repository;
use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\API\Repository\UserService;

class Handler implements IFTTTHandler
{
    /**
     * @var \eZ\Publish\API\Repository\Repository
     */
    private $repository;

    /**
     * @var \BD\Bundle\EzIFTTTBundle\ContentProvider $provider
     */
    private $contentProvider;

    /**
     * @var \eZ\Publish\API\Repository\ContentService
     */
    private $contentService;

    /**
     * @var \eZ\Publish\API\Repository\UserService
     */
    private $userService;

    public function __construct( Repository $repository, ContentService $contentService, UserService $userService, ContentProvider $contentProvider )
    {
        $this->repository = $repository;
        $this->contentProvider = $contentProvider;
        $this->contentService = $contentService;
        $this->userService = $userService;
    }

    public function handleAction( Request $request )
    {
        $user = $this->userService->loadUserByCredentials(
            $request->username,
            $request->password
        );
        $this->repository->setCurrentUser( $user );

        $contentCreateStruct = $this->contentProvider->newContentCreateStructFromRequest( $request );
        $locationCreateStruct = $this->contentProvider->newLocationCreateStructFromRequest( $request );

        $content = $this->contentService->createContent(
            $contentCreateStruct,
            array( $locationCreateStruct )
        );
        $this->contentService->publishVersion( $content->versionInfo );
    }
}
