<?php
namespace Libre{

    use Libre\Http\Header;
    use Libre\Http\Request;
    use Libre\Http\Url;

    include_once 'header.php';

    try{

        $validatorPostCallback = function($data)
        {
            $filterArgs =array(
                "id"=>FILTER_VALIDATE_INT
            );
            $myinputs = filter_var_array((array)json_decode($data), $filterArgs);
            var_dump((array)$data,$myinputs);
        };

        //$rest = new Http\Rest\Client('http://www.inwebo.dev');
        //$rest = new Http\Rest\Client('http://www.inwebo.dev/test/');
        //$rest = new Http\Rest\Client('http://www.inwebo.dev/');
        // 404 est une exception
        $rest = new Http\Rest\Client( 'http://jsonplaceholder.typicode.com');
        //$result = $rest->get('/users');
        //echo($result->getContent());
        $result = $rest->post('/posts',array('title'=>'This is title'));
        $content = $result->getContent();
        $validatorPostCallback($content);
        echo($content);
        //$result = $rest->post('echo-rest-header.php',array('test'=>'arf'));
        //var_dump($result->getMetaData());
        //var_dump($rest);
        //var_dump($rest->getProtocol());
        //var_dump($rest->getServiceUrl());
        //var_dump($rest->parseUrl());
        //var_dump($rest->isValid());
        //var_dump($rest->get());
        //var_dump($rest->get('tests/'));
        //$response = $rest->get();
        //var_dump($response->getMetaData());
        //echo($response->getContent());
        //var_dump(Request::get(Url::get()));

        $params = array('test'=>4,'foo'=>4);

        $restParams = function($params)
        {
            $buffer = array();
            foreach($params as $k=>$v)
            {
                if(is_string($k))
                {
                    $buffer[] = $k;
                    $buffer[] = $v;
                }
                elseif(is_int($k))
                {
                    $buffer[] = $v;
                }
            }
            return implode('/', $buffer);
        };

//var_dump($restParams($params));

        $handler = fopen('http://localhost/Libre/demos/echo-rest-header.php','r',false,stream_context_create(
                array(
                'http'=>array(
                    'header'    => 'Content-type: application/x-www-form-urlencoded',
                    'user_agent'=> 'Inwebot',
                    'proxy'=>'',
                    'request_fulluri'=>'',
                    'follow_location'=>'',
                    'max_redirects'=>'',
                    'protocol_version'=>'',
                    'timeout'=>30,
                    'ignore_errors'=>'',
                    'method'    => strtoupper('POST'),
                    'content'   => http_build_query(array(
                        'test'=>'arf'
                    ))
                )
            )
        ));
        if(is_resource($handler))
        {
            //var_dump(stream_get_meta_data($handler));
            //echo(stream_get_contents($handler, -1, null));
            //var_dump(stream_get_meta_data($handler)[ 'unread_bytes' ]);
        }
        else
        {
            echo 'bad request';
        }

    }
    catch(\Exception $e)
    {
        echo $e->getMessage();
    }
}