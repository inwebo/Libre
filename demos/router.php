<?php
namespace Libre{
    include_once 'header.php';

    use Libre\Routing\Uri;
    use Libre\Routing\Route;
    use Libre\Routing\RoutesCollection;
    use Libre\Routing\Router;

    /**
     * Routes constraints
     */

    /**
     * Segments
     * Mandatory separator : /
     * !Mandatory separator : [/]
     * static : :static
     * instance : :instance
     * module : :module
     *
     * controller : :controller
     * action : :action
     * param : :id
     * namedParam : :id|paramName
     *
     * mandatory : /segment/
     * !mandatory : [segment]
     *
     * type : (int|regex)
     * regex:
     */
    try{

        /**
         * BaseUri : /
         *
         * default : /
         * error : errors/
         * blog : blog/
         * img : img/
         * rest : v1/
         */
        $baseUri = '/';
        $uri = new Uri($baseUri);

        // Error route
        $errors = new Route(
            $baseUri . "errors/[:id|codeError]",
            "ErrorsController",
            "byCodeError",
            null,
            null
        );
        RoutesCollection::get("default")->addRoute($errors);

        // Default route collection
        $default = new Route(
            $baseUri . "index/[:controller][/][:action][/][(int):id][/][:id|arf]",
            "DefaultController",
            // Default action
            "index",
            null,
            $baseUri."may-be-default/"
        );
        RoutesCollection::get("default")->addRoute($default);

        // Default Blog route collection
        $defaultBlog = new Route(
            $baseUri . "blog",
            "BlogController",
            "index",
            null,
            null
        );
        RoutesCollection::get("default")->addRoute($defaultBlog);

        // Billet
        $entry = new Route(
            $baseUri . "blog/[:id|Y][/][:id|M][/][:id|D]",
            "BlogController",
            "entry",
            null,
            null
        );
        RoutesCollection::get("default")->addRoute($entry);

        var_dump(RoutesCollection::get("default")->getDefaultRoute());
        echo RoutesCollection::get("default");
        $uri = new Uri($baseUri. 'index/controller/action/1/bar');
        var_dump($uri);
        $router = new Router($uri,RoutesCollection::get("default"));
        $routed = $router->dispatch();
        var_dump($routed);
        var_dump($routed===$default);

        $uri = new Uri($baseUri. 'blog');
        var_dump($uri);
        $router = new Router($uri,RoutesCollection::get("default"));
        $routed = $router->dispatch();
        var_dump($routed);
        var_dump($routed===$defaultBlog);

        $uri = new Uri($baseUri. 'blog/1997/12/01');
        var_dump($uri);
        $router = new Router($uri,RoutesCollection::get("default"));
        $routed = $router->dispatch();
        var_dump($routed);
        var_dump($routed===$entry);

        $uri = new Uri($baseUri. 'doesntexists/');
        var_dump($uri);
        $router = new Router($uri,RoutesCollection::get("default"),false);
        $routed = $router->dispatch();
        var_dump($routed);
        var_dump($routed===$errors);
    }
    catch(\Exception $e)
    {
        echo $e->getMessage();

        $entry = new Route(
            $baseUri . "error/404",
            "ErrorController",
            "index",
            array('code'=>$e->getCode()),
            null
        );
        RoutesCollection::get("exception")->addRoute($entry);
        $routed = $router->reRoute(RoutesCollection::get("exception"));
        var_dump($routed);
        var_dump($routed===$entry);
    }
}



