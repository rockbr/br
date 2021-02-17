<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class BasicAuthFilter implements FilterInterface
{
        /**
         * This is a demo implementation of using the Throttler class
         * to implement rate limiting for your application.
         *
         * @param RequestInterface|\CodeIgniter\HTTP\IncomingRequest $request
         *
         * @return mixed
         */
        public function before(RequestInterface $request)
        {
            if(empty($_SERVER['PHP_AUTH_USER'])){
                die('Você deve fazer login para usar este serviço');
            }else{
                $username = $_SERVER['PHP_AUTH_USER'];
                $password = $_SERVER['PHP_AUTH_PW'];

                if($username != 'admin' && $password != '202020'){
                    die('Usuário ou senha inválidos!');
                }
            }
        }

        //--------------------------------------------------------------------

        /**
         * We don't have anything to do here.
         *
         * @param RequestInterface|\CodeIgniter\HTTP\IncomingRequest $request
         * @param ResponseInterface|\CodeIgniter\HTTP\Response       $response
         *
         * @return mixed
         */
        public function after(RequestInterface $request, ResponseInterface $response)
        {
        }
}
