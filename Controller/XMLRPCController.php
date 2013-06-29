<?php
namespace BD\Bundle\EzIFTTTBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use BD\Bundle\EzIFTTTBundle\XmlRpcResponse;

class XMLRPCController extends Controller
{
    public function endpointAction()
    {
        $request = $this->container->get( 'request' );

        $requestXml = simplexml_load_string( $request->getContent() );

        switch ( $requestXml->methodName )
        {
            case 'metaWeblog.getRecentPosts':
                return new XmlRpcResponse\Success( "<array><data></data></array>" );
                //return $this->createSuccessResponse( "<array><data></data></array>" );
            
            case 'mt.supportedMethods':
                return new XmlRpcResponse\Success( 'metaWeblog.getRecentPosts' );

            case 'metaWeblog.newPost':
                $repository = $this->container->get( 'ezpublish.api.repository' );

                //@see http://codex.wordpress.org/XML-RPC_WordPress_API/Posts#wp.newPost
                //get the parameters from xml
                $username = (string)$requestXml->params->param[1]->value->string;
                $password = (string)$requestXml->params->param[2]->value->string;

                $userService = $this->container->get( 'ezpublish.api.service.user' );

                try
                {
                    $user = $userService->loadUserByCredentials( $username, $password );
                    $repository->setCurrentUser( $user );
                }
                catch ( \Exception $e )
                {
                     return new XmlRpcResponse\Success( $e->getMessage(), 403 );
                }

                $contentService = $this->container->get( 'ezpublish.api.service.content' );
                $contentTypeService = $this->container->get( 'ezpublish.api.service.content_type' );
                $locationService = $this->container->get( 'ezpublish.api.service.location' );

                $contentCreateStruct = $contentService->newContentCreateStruct(
                    $contentTypeService->loadContentTypeByIdentifier( 'folder' ),
                    'eng-GB'
                );


                //@see content in the wordpress docs
                $content = $requestXml->params->param[3]->value->struct->member;

                foreach ( $content as $data )
                {
                    switch ( (string)$data->name )
                    {
                        //this is used for title/description
                        case 'title':
                            $contentCreateStruct->setField( 'name', (string)$data->value->string );
                            break;
                    }
                }

                $repository->sudo( function( $repository ) use ( $user, $contentCreateStruct, $contentService, $locationService ) {
                    $content = $contentService->createContent(
                        $contentCreateStruct,
                        array( $locationService->newLocationCreateStruct( 2 ) )
                    );
                    $contentService->publishVersion( $content->versionInfo );
                } );
                return new XmlRpcResponse\Success( '<string>200</string>' );
                break;
        }

        return new XmlRpcResponse\Error( "No such method {$requestXml->methodName}", 404 );
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
