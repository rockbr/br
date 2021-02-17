<?php namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes(true);

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Site');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('site', 'Site::index');

$routes->post('autocep', 'Auto::cep');
$routes->post('autocomplete', 'Auto::autocomplete');

$routes->post('download', 'Download::index');
$routes->get('download/(:any)', 'Download::download/$1');

$routes->post('home', 'Home::index');
$routes->post('vendapormes', 'Home::vendaPorMes');
$routes->post('vendapormesvendedora', 'Home::vendaPorMesVendedora');

$routes->get('admin', 'Usuario::index');
$routes->post('login', 'Usuario::login');
$routes->get('logout', 'Usuario::logout');
$routes->get('resetsenha', 'Usuario::resetSenha');
$routes->post('emailresetsenha', 'Usuario::emailResetSenha');
$routes->get('novasenhatoken/(:any)', 'Usuario::novaSenhaToken/$1');
$routes->post('novasenha', 'Usuario::novaSenha');

$routes->match(['get', 'post'], 'consultaempresas', 'Empresa::list');
$routes->get('novoempresas', 'Empresa::cadastro');
$routes->get('editaempresas/(:num)', 'Empresa::cadastro/$1');
$routes->post('deletaempresas', 'Empresa::delete');
$routes->post('salvaempresas', 'Empresa::salva');

$routes->match(['get', 'post'], 'consultacompras', 'Compra::list');
$routes->get('novocompras', 'Compra::cadastro');
$routes->get('editacompras/(:num)', 'Compra::cadastro/$1');
$routes->post('deletacompras', 'Compra::delete');
$routes->post('salvacompras', 'Compra::salva');
$routes->get('anexo/(:any)', 'Compra::anexo/$1');

$routes->match(['get', 'post'], 'consultatickets', 'Ticket::list');
$routes->get('novotickets', 'Ticket::cadastro');
$routes->get('editatickets/(:num)', 'Ticket::cadastro/$1');
$routes->post('deletatickets', 'Ticket::delete');
$routes->post('salvatickets', 'Ticket::salva');
$routes->get('anexo/(:any)', 'Ticket::anexo/$1');

$routes->match(['get', 'post'], 'consultausuarios', 'Usuario::list');
$routes->get('novousuarios', 'Usuario::cadastro');
$routes->get('editausuarios/(:num)', 'Usuario::cadastro/$1');
$routes->post('deletausuarios', 'Usuario::delete');
$routes->post('salvausuarios', 'Usuario::salva');

$routes->match(['get', 'post'], 'consultagruposusuarios', 'GrupoUsuario::list');
$routes->get('novogruposusuarios', 'GrupoUsuario::cadastro');
$routes->get('editagruposusuarios/(:num)', 'GrupoUsuario::cadastro/$1');
$routes->post('deletagruposusuarios', 'GrupoUsuario::delete');
$routes->post('salvagruposusuarios', 'GrupoUsuario::salva');

/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need to it be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
