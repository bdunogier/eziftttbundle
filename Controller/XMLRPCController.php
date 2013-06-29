<?php
namespace BD\Bundle\EzIFTTTBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use BD\Bundle\EzIFTTTBundle\Controller\XmlRpcResponse;
use BD\Bundle\EzIFTTTBundle\IFTTT\RequestParser;

class XMLRPCController extends Controller
{
    public function endpointAction()
    {
        file_put_contents(
            "/tmp/ifttt-request-" . time() . ".xml",
            $this->container->get( 'request' )->getContent()
        );
        try
        {
            $requestParser = new RequestParser( $this->container->get( 'request' )->getContent() );
            $IFTTTRequest = $requestParser->getRequest();
        }
        catch ( \Exception $e )
        {
            return new XmlRpcResponse\Error( $e->getMessage(), 401 );
        }

        switch ( $IFTTTRequest->method )
        {
            case 'metaWeblog.getRecentPosts':
                return new XmlRpcResponse\Success( "<array><data></data></array>" );
                //return $this->createSuccessResponse( "<array><data></data></array>" );
            
            case 'mt.supportedMethods':
                return new XmlRpcResponse\Success( 'metaWeblog.getRecentPosts' );

            case 'metaWeblog.newPost':
                $repository = $this->container->get( 'ezpublish.api.repository' );

                try
                {
                    $userService = $this->container->get( 'ezpublish.api.service.user' );
                    $user = $userService->loadUserByCredentials(
                        $IFTTTRequest->username,
                        $IFTTTRequest->password
                    );
                    $repository->setCurrentUser( $user );
                }
                catch ( \Exception $e )
                {
                     return new XmlRpcResponse\Error( $e->getMessage(), 403 );
                }

                $contentProvider = $this->container->get( 'ezifttt.content_provider' );
                $contentCreateStruct = $contentProvider->newContentCreateStructFromRequest( $IFTTTRequest );
                $locationCreateStruct =  $contentProvider->newLocationCreateStructFromRequest( $IFTTTRequest );

                /** @var $repository \eZ\Publish\API\Repository\Repository */
                $contentService = $this->container->get( 'ezpublish.api.service.content' );
                $content = $contentService->createContent(
                    $contentCreateStruct,
                    array( $locationCreateStruct )
                );
                $contentService->publishVersion( $content->versionInfo );

                return new XmlRpcResponse\Success( '<string>200</string>' );
                break;
        }

        return new XmlRpcResponse\Error( "No such method {$IFTTTRequest->method}", 404 );
    }

    private function createSuccessResponse( $contents )
    {
        $xml = <<<XML
<?xml version="1.0"?>
<methodResponse>
  <params>
    <param>
      <value>
      $contents
      </value>
    </param>
  </params>
</methodResponse>

XML;
        $response = new Response( $xml );
        $response->headers->set( 'Content-Type', 'text/xml' );
        return $response;
    }

    private function createErrorResponse( $message, $code = 500 )
    {
        $xml = <<< XML
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

        $response = new Response( $xml );
        $response->headers->set( 'Content-Type', 'text/xml' );
        return $response;
    }

}
