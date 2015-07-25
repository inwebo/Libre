<?php

namespace Libre\Mvc\Controller\AjaxController\PrivateAjaxController {

    use Libre\Models\User;
    use Libre\Mvc\Controller\AjaxController\PrivateAjaxController;

    /**
     * Class RestController
     *
     * Route l'action selon le verb de la requête HTTP.
     *  verbs : OPTIONS, GET, POST, UPDATE, DELETE, PUT, OPTIONS, PATCH
     * @package Libre\Mvc\Controller
     */
    class RestController extends PrivateAjaxController{

        public function init() {
            parent::init();
        }

        public function indexAction(){
            switch( $this->_request->getVerb() ) {
                case 'OPTIONS':
                    // https://developer.mozilla.org/fr/docs/HTTP/Access_control_CORS !
                    // X-domain
                    $this->options();
                    break;

                case 'GET':
                    $this->get();
                    break;

                case 'POST':
                    $this->post();
                    break;

                case 'UPDATE':
                    $this->update();
                    break;

                case 'DELETE':
                    $this->delete();
                    break;

                case 'PUT':
                    $this->put();
                    break;

                case 'OPTIONS':
                    $this->options();
                    break;

                case 'PATCH':
                    $this->patch();
                    break;
            }
        }

        public function options() {
            echo __METHOD__;
        }

        public function get() {
            echo __METHOD__;
        }

        public function post() {
            echo __METHOD__;
        }

        public function head() {
            echo __METHOD__;
        }

        public function patch() {
            echo __METHOD__;
        }

        public function update() {
            echo __METHOD__;
        }

        public function delete() {
            echo __METHOD__;
        }

        public function put() {
            echo __METHOD__;
        }

    }
}