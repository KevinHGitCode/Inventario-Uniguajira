<?php 
require 'app/controllers/Router.php';

$router = new Router();

// Rutas para vistas principales (ok)
$router->add('/', 'ctlView', 'index');
$router->add('/login', 'ctlView', 'login');
$router->add('/profile', 'ctlView', 'Profile');
$router->add('/doc', 'ctlView', 'doc');
$router->add('/404', 'ctlView', 'notFound');
$router->add('/test', 'ctlView', 'test');


// Rutas para el sidebar (ok)
$router->add('/home', 'ctlSidebar', 'home');
$router->add('/goods', 'ctlSidebar', 'goods');
$router->add('/inventory', 'ctlSidebar', 'inventory');
$router->add('/reports', 'ctlSidebar', 'reports');
$router->add('/users', 'ctlSidebar', 'users');
$router->add('/record', 'ctlSidebar', 'record');


// Rutas para el navbar (ok)
$router->add('/api/users/editProfile', 'ctlUser', 'editProfile');
$router->add('/api/users/update', 'ctlUser', 'updatePassword');
$router->add('/api/logout', 'ctlUser', 'logout');


// Rutas para la API de usuarios (ok)
$router->add('/api/login', 'ctlUser', 'login');
$router->add('/api/users/create', 'ctlUser', 'create');
$router->add('/api/users/edit', 'ctlUser', 'edit');
$router->add('/api/users/delete/:id', 'ctlUser', 'deleteUser');
// ruta para getSessionData
$router->add('/api/session/get', 'ctlUser', 'getSessionData');


// Rutas para la API de tareas (ok)
$router->add('/api/tasks/create', 'ctlTasks', 'create');
$router->add('/api/tasks/delete/:id', 'ctlTasks', 'delete');
$router->add('/api/tasks/toggle', 'ctlTasks', 'toggle');
$router->add('/api/tasks/update', 'ctlTasks', 'update');

// Rutas para la API de bienes (ok)
$router->add('/api/goods/get/json', 'ctlGoods', 'getJson');
$router->add('/api/goods/create', 'ctlGoods', 'create');
$router->add('/api/goods/delete/:id', 'ctlGoods', 'delete');
$router->add('/api/goods/update', 'ctlGoods', 'update');


// Rutas para la API de navegacion de '/inventory' (ok)
$router->add('/api/get/inventories/:id_group', 'ctlInventory', 'getInventoriesOfGroup');  // abrir grupo
$router->add('/api/get/goodsInventory/:id_inventory', 'ctlInventory', 'getGoodsOfInventory');  // abrir inventario

// Rutas para la API de navegacion de '/foldesr' (ok)
$router->add('/api/reports/getAll/:id_folder', 'ctlReports', 'getReportsByFolder');  // abrir grupo
$router->add('/api/get/goodsInventory/:id_inventory', 'ctlInventory', 'getGoodsOfInventory');  // abrir inventario


// Rutas para la API de grupos (ok)
$router->add('/api/groups/create', 'ctlGroup', 'create');
$router->add('/api/groups/rename', 'ctlGroup', 'rename');
$router->add('/api/groups/delete/:id', 'ctlGroup', 'delete');


// Rutas para la API de inventario (ok)
$router->add('/api/inventories/create', 'ctlInventory', 'create');
$router->add('/api/inventories/rename', 'ctlInventory', 'rename');
$router->add('/api/inventories/delete/:id', 'ctlInventory', 'delete');


// Rutas para la API de bienes en inventario
$router->add('/api/goods-inventory/create', 'ctlGoodInventory', 'create');
$router->add('/api/goods-inventory/delete/:id', 'ctlGoodInventory', 'delete');
$router->add('/api/goods-inventory/update-quantity', 'ctlGoodInventory', 'updateQuantity');
$router->add('/api/goods-inventory/move', 'ctlGoodInventory', 'moveGood');

// $router->add('/api/goods-inventory/create', 'ctlInventory', 'addGood');
// $router->add('/api/goods-inventory/update-quantity', 'ctlInventory', 'updateQuantity');
// $router->add('/api/goods-inventory/move', 'ctlInventory', 'moveGood');
// $router->add('/api/goods-inventory/delete', 'ctlInventory', 'removeGood');

// $router->add('/api/goods-inventory/create', 'ctlGoodsInventory', 'create');
// $router->add('/api/goods-inventory/delete/:id', 'ctlGoodsInventory', 'delete');
// $router->add('/api/goods-inventory/update', 'ctlGoodsInventory', 'update');


// Rutas para la API de carpetas (ok)
$router->add('/api/folders/create', 'ctlReports', 'createFolder');
$router->add('/api/folders/rename', 'ctlReports', 'renameFolder');
$router->add('/api/folders/delete/:id', 'ctlReports', 'deleteFolder');


// Rutas para la API de reportes (ok)
$router->add('/api/reports/create', 'ctlReports', 'create');
$router->add('/api/reports/rename', 'ctlReports', 'rename');
$router->add('/api/reports/delete/:id', 'ctlReports', 'delete');

// Mas rutas...


// Despachar la solicitud según la URI
$requestUri = $_SERVER['REQUEST_URI'];
$router->dispatch($requestUri);