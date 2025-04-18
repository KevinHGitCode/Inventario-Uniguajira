<?php 
require 'app/controllers/Router.php';

$router = new Router();

// Rutas para vistas principales
$router->add('/', 'ctlView', 'index');
$router->add('/login', 'ctlView', 'login');
$router->add('/profile', 'ctlView', 'Profile');
$router->add('/doc', 'ctlView', 'doc');
$router->add('/404', 'ctlView', 'notFound');
$router->add('/test', 'ctlView', 'test');


// Rutas para el sidebar
$router->add('/home', 'ctlSidebar', 'home');
$router->add('/goods', 'ctlSidebar', 'goods');
$router->add('/inventory', 'ctlSidebar', 'inventory');
$router->add('/reports', 'ctlSidebar', 'reports');
$router->add('/users', 'ctlSidebar', 'users');

// Rutas para el navbar
$router->add('/api/users/editProfile', 'ctlUser', 'editProfile');
$router->add('/api/users/update', 'ctlUser', 'updatePassword');
$router->add('/api/logout', 'ctlUser', 'logout');

// Rutas para la API de usuarios
$router->add('/api/login', 'ctlUser', 'login');
$router->add('/api/users/create', 'ctlUser', 'create');
$router->add('/api/users/edit', 'ctlUser', 'edit');
$router->add('/api/users/delete/:id', 'ctlUser', 'deleteUser');


// Rutas para la API de tareas
$router->add('/api/tasks/create', 'ctlTasks', 'create');
$router->add('/api/tasks/delete/:id', 'ctlTasks', 'delete');
$router->add('/api/tasks/toggle', 'ctlTasks', 'toggle');
$router->add('/api/tasks/update', 'ctlTasks', 'update');

// Rutas para la API de bienes
$router->add('/api/goods/create', 'ctlGoods', 'create');
$router->add('/api/goods/delete/:id', 'ctlGoods', 'delete');
$router->add('/api/goods/update', 'ctlGoods', 'update');


// Rutas para la API de grupos
$router->add('/api/groups', 'ctlGroup', 'getAll');
$router->add('/api/groups/get/:id', 'ctlGroup', 'getById');
$router->add('/api/groups/create', 'ctlGroup', 'create');
$router->add('/api/groups/rename', 'ctlGroup', 'rename');
$router->add('/api/groups/delete/:id', 'ctlGroup', 'delete');


// Rutas para la API de inventarios
$router->add('/api/get/inventories/:id_group', 'ctlInventory', 'getInventoriesOfGroup');
$router->add('/api/get/goodsInventory/:id_inventory', 'ctlInventory', 'getGoodsOfInventory');
// $router->add('/api/grupos/create', 'ctlInventory', 'createGroup');


// Mas rutas...


// Despachar la solicitud según la URI
$requestUri = $_SERVER['REQUEST_URI'];
$router->dispatch($requestUri);