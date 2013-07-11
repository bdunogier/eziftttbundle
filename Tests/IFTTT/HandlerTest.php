<?php
/**
 * File containing the HandlerTest class.
 *
 * @copyright Copyright (C) 2013 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */
namespace BD\Bundle\EzIFTTTBundle\Tests\IFTTT;

use PHPUnit_Framework_TestCase;
use BD\Bundle\EzIFTTTBundle\IFTTT\Handler as IFTTTHandler;

class HandlerTest extends PHPUnit_Framework_TestCase
{
    public function testHandleAction()
    {
        $IFTTTRequest = new \BD\Bundle\IFTTTBundle\IFTTT\Request(
            array(
                'username' => 'username',
                'password' => 'password',
                'title' => 'This is the title',
                'description' => "This is the description"
            )
        );

        $expectedContentCreateStruct = new \eZ\Publish\Core\Repository\Values\Content\ContentCreateStruct();
        $expectedLocationCreateStruct = new \eZ\Publish\API\Repository\Values\Content\LocationCreateStruct();

        $this->getUserServiceMock()
            ->expects( $this->once() )
            ->method( 'loadUserByCredentials' )
            ->with( $IFTTTRequest->username, $IFTTTRequest->password )
            ->will( $this->returnValue( new \eZ\Publish\Core\Repository\Values\User\User() ) );

        $this->getContentProviderMock()
            ->expects( $this->once() )
            ->method( 'newContentCreateStructFromRequest' )
            ->with( $IFTTTRequest )
            ->will( $this->returnValue( $expectedContentCreateStruct ) );

        $this->getContentProviderMock()
            ->expects( $this->once() )
            ->method( 'newLocationCreateStructFromRequest' )
            ->with( $IFTTTRequest )
            ->will( $this->returnValue( $expectedLocationCreateStruct ) );

        $expectedContent = new \eZ\Publish\Core\Repository\Values\Content\Content(
            array(
                'versionInfo' => new \eZ\Publish\Core\Repository\Values\Content\VersionInfo(),
                'internalFields' => array(),
            )
        );
        $this->getContentServiceMock()
            ->expects( $this->once() )
            ->method( 'createContent' )
            ->will( $this->returnValue( $expectedContent ) );

        $handler = $this->getHandler();
        $handler->handleAction( $IFTTTRequest );
    }

    /**
     * @return IFTTTHandler
     */
    private function getHandler()
    {
        if ( !isset( $this->handler ) )
        {
            $this->handler = new IFTTTHandler(
                $this->getRepositoryMock(),
                $this->getContentServiceMock(),
                $this->getUserServiceMock(),
                $this->getContentProviderMock()
            );
        }
        return $this->handler;
    }

    /**
     * @return \eZ\Publish\API\Repository\Repository|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getRepositoryMock()
    {
        if ( !isset( $this->repositoryMock ) )
        {
            $this->repositoryMock = $this->getMock( 'eZ\\Publish\\API\\Repository\\Repository' );
        }
        return $this->repositoryMock;
    }

    /**
     * @return \eZ\Publish\API\Repository\ContentService|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getContentServiceMock()
    {
        if ( !isset( $this->contentServiceMock ) )
        {
            $this->contentServiceMock = $this->getMock( 'eZ\\Publish\\API\\Repository\\ContentService' );
        }
        return $this->contentServiceMock;
    }

    /**
     * @return \eZ\Publish\API\Repository\UserService|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getUserServiceMock()
    {
        if ( !isset( $this->userServiceMock) )
        {
            $this->userServiceMock = $this->getMock( 'eZ\\Publish\\API\\Repository\\UserService' );
        }
        return $this->userServiceMock;
    }

    /**
     * @return \BD\Bundle\EzIFTTTBundle\ContentProvider|\PHPUnit_Framework_MockObject_MockObject
     */
    private function getContentProviderMock()
    {
        if ( !isset( $this->contentProviderMock ) )
        {
            $this->contentProviderMock = $this
                ->getMockBuilder( 'BD\\Bundle\\EzIFTTTBundle\\ContentProvider' )
                ->disableOriginalConstructor()
                ->getMock();
        }
        return $this->contentProviderMock;
    }

    /**
     * @var \BD\Bundle\EzIFTTTBundle\IFTTT\Handler
     **/
    private $handler;

    /**
     * @var \eZ\Publish\API\Repository\Repository|\PHPUnit_Framework_MockObject_MockObject
     */
    private $repositoryMock;

    /**
     * @var \eZ\Publish\API\Repository\ContentService|\PHPUnit_Framework_MockObject_MockObject
     */
    private $contentServiceMock;

    /**
     * @var \eZ\Publish\API\Repository\UserService|\PHPUnit_Framework_MockObject_MockObject
     */
    private $userServiceMock;

    /**
     * @var \BD\Bundle\EzIFTTTBundle\ContentProvider|\PHPUnit_Framework_MockObject_MockObject
     */
    private $contentProviderMock;
}
