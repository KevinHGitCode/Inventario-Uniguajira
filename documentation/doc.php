<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentación del Proyecto</title>
    <link rel="preload" href="assets/images/fondounigua.jpeg" as="image">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/doc.css">
</head>
<body>
    <header>
        <h1>Documentación del Proyecto</h1>
    </header>
    <main>
        <!-- Indice -->
        <nav>
            <h2>Índice</h2>
            <ul>
                <li><a href="#controladores">Controladores</a></li>
                <li><a href="#rutas">Rutas</a></li>
                <li><a href="#modelos">Modelos</a></li>
                <li><a href="#javascript">JavaScript</a></li>
                <li><a href="#flujo">Flujo de Rutas</a></li>
                <li><a href="#css-classes">Clases CSS</a></li>
            </ul>
        </nav>

        <div class="section-container">
            <section id="controladores">
                <h2>Controladores</h2>

                <!-- Router -->
                <details>
                    <summary>Router</summary>
                    <p>Clase para manejar las rutas de la aplicación.</p>
                    <details>
                        <summary>add($route, $controller, $method)</summary>
                        <p>Agrega una nueva ruta al enrutador.</p>
                        <p><strong>Argumentos:</strong></p>
                        <ul>
                            <li><code>$route</code> (string) - URI de la ruta.</li>
                            <li><code>$controller</code> (string) - Nombre del controlador.</li>
                            <li><code>$method</code> (string) - Método del controlador.</li>
                        </ul>
                        <p><strong>Retorna:</strong> void.</p>
                    </details>
                    <details>
                        <summary>dispatch($requestUri)</summary>
                        <p>Despacha la solicitud a la ruta correspondiente.</p>
                        <p><strong>Argumentos:</strong> <code>$requestUri</code> (string) - URI de la solicitud.</p>
                        <p><strong>Retorna:</strong> Ejecuta el método correspondiente o muestra la vista 404.</p>
                    </details>
                </details>

                <!-- ctlView -->
                <details>
                    <summary>ctlView</summary>
                    <p>Controlador para manejar las vistas de la aplicación.</p>
                    <details>
                        <summary>index()</summary>
                        <p>Muestra la vista principal (index).</p>
                        <p><strong>Retorna:</strong> HTML de la vista principal.</p>
                    </details>
                    <details>
                        <summary>login()</summary>
                        <p>Muestra la vista de inicio de sesión (login).</p>
                        <p><strong>Retorna:</strong> HTML de la vista de inicio de sesión.</p>
                    </details>
                    <details>
                        <summary>doc()</summary>
                        <p>Muestra la vista de documentación (doc).</p>
                        <p><strong>Retorna:</strong> HTML de la vista de documentación.</p>
                    </details>
                    <details>
                        <summary>notFound()</summary>
                        <p>Muestra la vista de error 404 (not found).</p>
                        <p><strong>Retorna:</strong> HTML de la vista de error 404.</p>
                    </details>
                    <details>
                        <summary>sendJsonResponse($response)</summary>
                        <p>Envía una respuesta en formato JSON.</p>
                        <p><strong>Argumentos:</strong> <code>$response</code> (array) - Datos a enviar en la respuesta JSON.</p>
                        <p><strong>Retorna:</strong> JSON.</p>
                    </details>
                </details>

                <!-- ctlSidebar -->
                <details>
                    <summary>ctlSidebar</summary>
                    <p>Controlador para manejar las vistas de la barra lateral.</p>
                    <details>
                        <summary>home()</summary>
                        <p>Muestra la vista principal (home).</p>
                        <p><strong>Retorna:</strong> HTML de la vista principal.</p>
                    </details>
                    <details>
                        <summary>goods()</summary>
                        <p>Muestra la vista de bienes.</p>
                        <p><strong>Retorna:</strong> HTML de la vista de bienes.</p>
                    </details>
                    <details>
                        <summary>inventary()</summary>
                        <p>Muestra la vista de inventario.</p>
                        <p><strong>Retorna:</strong> HTML de la vista de inventario.</p>
                    </details>
                    <details>
                        <summary>users()</summary>
                        <p>Muestra la vista de usuarios.</p>
                        <p><strong>Retorna:</strong> HTML de la vista de usuarios.</p>
                    </details>
                </details>

                <!-- ctlUser -->
                <details>
                    <summary>ctlUser</summary>
                    <p>Controlador para manejar las operaciones relacionadas con los usuarios.</p>
                    <details>
                        <summary>login()</summary>
                        <p>Inicia sesión validando las credenciales del usuario.</p>
                        <p><strong>Retorna:</strong> Redirección a la página principal o mensaje de error.</p>
                    </details>
                    <details>
                        <summary>register()</summary>
                        <p>Registra un nuevo usuario en el sistema.</p>
                        <p><strong>Retorna:</strong> Mensaje de éxito o error.</p>
                    </details>
                    <details>
                        <summary>profile()</summary>
                        <p>Obtiene el perfil de un usuario específico.</p>
                        <p><strong>Retorna:</strong> Datos del perfil del usuario.</p>
                    </details>
                    <details>
                        <summary>edit()</summary>
                        <p>Edita la información de un usuario existente.</p>
                        <p><strong>Retorna:</strong> Mensaje de éxito o error.</p>
                    </details>
                    <details>
                        <summary>logout()</summary>
                        <p>Cierra la sesión del usuario y redirige a la página de inicio de sesión.</p>
                        <p><strong>Retorna:</strong> Redirección a la página de inicio de sesión.</p>
                    </details>
                </details>
                
            </section>

            <section id="rutas">
                <h2>Rutas</h2>

                <!-- Vistas principales -->
                <details>
                    <summary>Vistas principales</summary>
                    <ul>
                        <li><strong>/</strong> - Muestra la vista principal (index).</li>
                        <li><strong>/login</strong> - Muestra la vista de inicio de sesión.</li>
                        <li><strong>/doc</strong> - Muestra la vista de documentación.</li>
                        <li><strong>/404</strong> - Muestra la vista de error 404.</li>
                    </ul>
                </details>

                <!-- Sidebar -->
                <details>
                    <summary>Sidebar</summary>
                    <ul>
                        <li><strong>/home</strong> - Muestra la vista del panel principal.</li>
                        <li><strong>/goods</strong> - Muestra la vista de bienes.</li>
                        <li><strong>/inventary</strong> - Muestra la vista de inventario.</li>
                        <li><strong>/users</strong> - Muestra la vista de usuarios.</li>
                    </ul>
                </details>

                <!-- API de usuarios -->
                <details>
                    <summary>API de usuarios</summary>
                    <ul>
                        <li><strong>/api/login</strong> - Endpoint para iniciar sesión.</li>
                        <li><strong>/api/logout</strong> - Endpoint para cerrar sesión.</li>
                    </ul>
                </details>

            </section>



            <section id="modelos">
                <h2>Modelos</h2>
                
                <!-- User -->
                <details>
                    <summary>User</summary>
                    <p>Modelo para manejar las operaciones relacionadas con los usuarios en la base de datos.</p>
                    
                    <details>
                        <summary>getById($id)</summary>
                        <p>Obtiene un usuario por su ID.</p>
                        <p><strong>Argumentos:</strong></p>
                        <ul>
                            <li><code>$id</code> (int) - ID del usuario.</li>
                        </ul>
                        <p><strong>Retorna:</strong> Datos del usuario o <code>null</code> si no se encuentra.</p>
                    </details>
                    
                    <details>
                        <summary>getAllUsers()</summary>
                        <p>Obtiene todos los usuarios registrados en el sistema.</p>
                        <p><strong>Retorna:</strong> Lista de usuarios.</p>
                    </details>
                    
                    <details>
                        <summary>createUser($nombre, $email, $contraseña, $rol)</summary>
                        <p>Crea un nuevo usuario en la base de datos.</p>
                        <p><strong>Argumentos:</strong></p>
                        <ul>
                            <li><code>$nombre</code> (string) - Nombre del usuario.</li>
                            <li><code>$email</code> (string) - Correo electrónico del usuario.</li>
                            <li><code>$contraseña</code> (string) - Contraseña del usuario.</li>
                            <li><code>$rol</code> (int) - Rol del usuario.</li>
                        </ul>
                        <p><strong>Retorna:</strong> Mensaje de éxito o error.</p>
                    </details>
                    
                    <details>
                        <summary>updateUserR($id, $nombre, $email, $contraseña, $rol)</summary>
                        <p>Actualiza los datos de un usuario, incluyendo su rol.</p>
                        <p><strong>Argumentos:</strong></p>
                        <ul>
                            <li><code>$id</code> (int) - ID del usuario.</li>
                            <li><code>$nombre</code> (string) - Nombre del usuario.</li>
                            <li><code>$email</code> (string) - Correo electrónico del usuario.</li>
                            <li><code>$contraseña</code> (string) - Contraseña del usuario.</li>
                            <li><code>$rol</code> (int) - Rol del usuario.</li>
                        </ul>
                        <p><strong>Retorna:</strong> Mensaje de éxito o de que no hubo cambios.</p>
                    </details>
                    
                    <details>
                        <summary>updateUser($id, $nombre, $email, $contraseña)</summary>
                        <p>Actualiza los datos de un usuario sin modificar su rol.</p>
                        <p><strong>Argumentos:</strong></p>
                        <ul>
                            <li><code>$id</code> (int) - ID del usuario.</li>
                            <li><code>$nombre</code> (string) - Nombre del usuario.</li>
                            <li><code>$email</code> (string) - Correo electrónico del usuario.</li>
                            <li><code>$contraseña</code> (string) - Contraseña del usuario.</li>
                        </ul>
                        <p><strong>Retorna:</strong> Mensaje de éxito o de que no hubo cambios.</p>
                    </details>
                    
                    <details>
                        <summary>updatepassword($id, $contraseña)</summary>
                        <p>Actualiza la contraseña de un usuario.</p>
                        <p><strong>Argumentos:</strong></p>
                        <ul>
                            <li><code>$id</code> (int) - ID del usuario.</li>
                            <li><code>$contraseña</code> (string) - Nueva contraseña del usuario.</li>
                        </ul>
                        <p><strong>Retorna:</strong> Mensaje de éxito o de que no hubo cambios.</p>
                    </details>
                    
                    <details>
                        <summary>deleteUser($id)</summary>
                        <p>Elimina un usuario por su ID.</p>
                        <p><strong>Argumentos:</strong></p>
                        <ul>
                            <li><code>$id</code> (int) - ID del usuario.</li>
                        </ul>
                        <p><strong>Retorna:</strong> Mensaje de éxito o de que no se encontró el usuario.</p>
                        <p><strong>Nota:</strong> El usuario con ID 1 no puede ser eliminado.</p>
                    </details>
                    
                    <details>
                        <summary>authentication($nombre, $contraseña)</summary>
                        <p>Autentica a un usuario por su nombre y contraseña.</p>
                        <p><strong>Argumentos:</strong></p>
                        <ul>
                            <li><code>$nombre</code> (string) - Nombre del usuario.</li>
                            <li><code>$contraseña</code> (string) - Contraseña del usuario.</li>
                        </ul>
                        <p><strong>Retorna:</strong> Datos del usuario si la autenticación es exitosa, <code>false</code> en caso contrario.</p>
                    </details>
                </details>

                <!-- Tasks -->
                <details>
                    <summary>Tasks</summary>
                    <p>Modelo para manejar las operaciones CRUD relacionadas con las tareas en la base de datos.</p>
                    
                    <details>
                        <summary>create($name, $description, $usuario_id, $estado)</summary>
                        <p>Crea una nueva tarea.</p>
                        <p><strong>Argumentos:</strong></p>
                        <ul>
                            <li><code>$name</code> (string) - Nombre de la tarea.</li>
                            <li><code>$description</code> (string) - Descripción de la tarea.</li>
                            <li><code>$usuario_id</code> (int) - ID del usuario asociado a la tarea.</li>
                            <li><code>$estado</code> (string) - Estado inicial de la tarea ('por hacer' o 'completado').</li>
                        </ul>
                        <p><strong>Retorna:</strong> <code>true</code> si la tarea se creó correctamente, <code>false</code> en caso contrario.</p>
                    </details>
                    
                    <details>
                        <summary>getAll()</summary>
                        <p>Obtiene la lista de todas las tareas.</p>
                        <p><strong>Retorna:</strong> Un arreglo asociativo con las tareas (id, nombre, descripción, estado).</p>
                    </details>
                    
                    <details>
                        <summary>updateName($id, $name, $description)</summary>
                        <p>Modifica una tarea existente.</p>
                        <p><strong>Argumentos:</strong></p>
                        <ul>
                            <li><code>$id</code> (int) - ID de la tarea a modificar.</li>
                            <li><code>$name</code> (string) - Nuevo nombre de la tarea.</li>
                            <li><code>$description</code> (string) - Nueva descripción de la tarea.</li>
                        </ul>
                        <p><strong>Retorna:</strong> <code>true</code> si la tarea se actualizó correctamente, <code>false</code> en caso contrario.</p>
                    </details>
                    
                    <details>
                        <summary>delete($id)</summary>
                        <p>Elimina una tarea por su ID.</p>
                        <p><strong>Argumentos:</strong></p>
                        <ul>
                            <li><code>$id</code> (int) - ID de la tarea a eliminar.</li>
                        </ul>
                        <p><strong>Retorna:</strong> <code>true</code> si la tarea se eliminó correctamente, <code>false</code> en caso contrario.</p>
                    </details>
                    
                    <details>
                        <summary>changeState($id)</summary>
                        <p>Cambia el estado de una tarea entre 'por hacer' y 'completado'.</p>
                        <p><strong>Argumentos:</strong></p>
                        <ul>
                            <li><code>$id</code> (int) - ID de la tarea cuyo estado se desea cambiar.</li>
                        </ul>
                        <p><strong>Retorna:</strong> <code>true</code> si el estado se cambió correctamente, <code>false</code> en caso contrario.</p>
                    </details>
                </details>

                <!-- Goods -->
                <details>
                    <summary>Goods</summary>
                    <p>Modelo para manejar las operaciones relacionadas con los bienes.</p>
                    
                    <details>
                        <summary>getAll()</summary>
                        <p>Obtiene la lista de todos los bienes.</p>
                        <p><strong>Retorna:</strong> Lista de bienes.</p>
                    </details>
                    
                    <details>
                        <summary>create($nombre, $tipo)</summary>
                        <p>Crea un nuevo bien.</p>
                        <p><strong>Argumentos:</strong></p>
                        <ul>
                            <li><code>$nombre</code> (string) - Nombre del bien.</li>
                            <li><code>$tipo</code> (int) - Tipo del bien.</li>
                        </ul>
                        <p><strong>Retorna:</strong> <code>true</code> si se creó correctamente, <code>false</code> en caso contrario.</p>
                    </details>
                </details>

                <!-- Groups -->
                <details>
                    <summary>Groups</summary>
                    <p>Modelo para manejar las operaciones relacionadas con los grupos.</p>
                    
                    <details>
                        <summary>getAllGroups()</summary>
                        <p>Obtiene todos los grupos.</p>
                        <p><strong>Retorna:</strong> Lista de grupos.</p>
                    </details>
                    
                    <details>
                        <summary>getInventoriesByGroup($groupId)</summary>
                        <p>Obtiene los inventarios asociados a un grupo específico.</p>
                        <p><strong>Argumentos:</strong></p>
                        <ul>
                            <li><code>$groupId</code> (int) - ID del grupo.</li>
                        </ul>
                        <p><strong>Retorna:</strong> Lista de inventarios asociados al grupo.</p>
                    </details>
                    
                    <details>
                        <summary>createGroup($name)</summary>
                        <p>Crea un nuevo grupo.</p>
                        <p><strong>Argumentos:</strong></p>
                        <ul>
                            <li><code>$name</code> (string) - Nombre del grupo.</li>
                        </ul>
                        <p><strong>Retorna:</strong> <code>true</code> si se creó correctamente, <code>false</code> si el nombre ya existe.</p>
                    </details>
                    
                    <details>
                        <summary>updateGroup($id, $newName)</summary>
                        <p>Edita un grupo cambiando su nombre.</p>
                        <p><strong>Argumentos:</strong></p>
                        <ul>
                            <li><code>$id</code> (int) - ID del grupo.</li>
                            <li><code>$newName</code> (string) - Nuevo nombre del grupo.</li>
                        </ul>
                        <p><strong>Retorna:</strong> <code>true</code> si se actualizó correctamente, <code>false</code> en caso contrario.</p>
                    </details>
                    
                    <details>
                        <summary>deleteGroup($id)</summary>
                        <p>Elimina un grupo si no tiene inventarios asociados.</p>
                        <p><strong>Argumentos:</strong></p>
                        <ul>
                            <li><code>$id</code> (int) - ID del grupo.</li>
                        </ul>
                        <p><strong>Retorna:</strong> <code>true</code> si se eliminó correctamente, <code>false</code> si no se pudo eliminar.</p>
                        <p><strong>Nota:</strong> No se puede eliminar un grupo que tenga inventarios asociados.</p>
                    </details>
                </details>

                <!-- Inventory -->
                <details>
                    <summary>Inventory</summary>
                    <p>Modelo para manejar las operaciones relacionadas con los inventarios.</p>
                    
                    <details>
                        <summary>getAll()</summary>
                        <p>Obtiene todos los inventarios.</p>
                        <p><strong>Retorna:</strong> Lista de inventarios.</p>
                    </details>
                    
                    <details>
                        <summary>create($name, $grupoId)</summary>
                        <p>Crea un nuevo inventario.</p>
                        <p><strong>Argumentos:</strong></p>
                        <ul>
                            <li><code>$name</code> (string) - Nombre del inventario.</li>
                            <li><code>$grupoId</code> (int) - ID del grupo al que pertenece el inventario.</li>
                        </ul>
                        <p><strong>Retorna:</strong> <code>true</code> si se creó correctamente, <code>false</code> en caso contrario.</p>
                    </details>
                    
                    <details>
                        <summary>updateConservation($id, $conservation)</summary>
                        <p>Actualiza el estado de conservación de un inventario.</p>
                        <p><strong>Argumentos:</strong></p>
                        <ul>
                            <li><code>$id</code> (int) - ID del inventario.</li>
                            <li><code>$conservation</code> (int) - Nuevo estado de conservación (1, 2 o 3).</li>
                        </ul>
                        <p><strong>Retorna:</strong> <code>true</code> si se actualizó correctamente, <code>false</code> si el estado es inválido o no se actualizó.</p>
                    </details>
                    
                    <details>
                        <summary>update($id, $name, $grupoId, $state_conservation)</summary>
                        <p>Edita un inventario existente.</p>
                        <p><strong>Argumentos:</strong></p>
                        <ul>
                            <li><code>$id</code> (int) - ID del inventario.</li>
                            <li><code>$name</code> (string) - Nuevo nombre del inventario.</li>
                            <li><code>$grupoId</code> (int) - Nuevo ID del grupo al que pertenece.</li>
                            <li><code>$state_conservation</code> (int) - Nuevo estado de conservación.</li>
                        </ul>
                        <p><strong>Retorna:</strong> <code>true</code> si se actualizó correctamente, <code>false</code> en caso contrario.</p>
                    </details>
                    
                    <details>
                        <summary>delete($id)</summary>
                        <p>Elimina un inventario.</p>
                        <p><strong>Nota:</strong> Solo se puede eliminar si no tiene bienes asociados.</p>
                        <p><strong>Argumentos:</strong></p>
                        <ul>
                            <li><code>$id</code> (int) - ID del inventario.</li>
                        </ul>
                        <p><strong>Retorna:</strong> <code>true</code> si se eliminó correctamente, <code>false</code> si tiene bienes asociados o no se eliminó.</p>
                    </details>
                </details>

            </section>
            
            <section id="javascript">
                <h2>JavaScript</h2>
            
                <!-- Sidebar.js -->
                <details>
                    <summary>sidebar.js</summary>
                    <p>Este script maneja la interacción con el sidebar y la carga dinámica de contenido.</p>
                    
                    <details>
                        <summary>menu.addEventListener('click', ...)</summary>
                        <p>Controla la visibilidad del sidebar al hacer clic en el menú.</p>
                        <p><strong>Función:</strong> Añade o quita la clase CSS <code>menu-toggle</code> para mostrar u ocultar el sidebar, el menú y el contenido principal.</p>
                    </details>
                    
                    <details>
                        <summary>loadContent(path)</summary>
                        <p>Carga contenido dinámico en el elemento <code>#main</code> desde una ruta específica utilizando la API <code>fetch</code>.</p>
                        <p><strong>Descripción:</strong> Esta función realiza una solicitud HTTP GET a la ruta especificada en el argumento <code>path</code>. Si la solicitud es exitosa, el contenido HTML obtenido se inserta en el elemento con ID <code>#main</code>. En caso de error, se muestra un mensaje en la consola.</p>
                        <p><strong>Argumentos:</strong></p>
                        <ul>
                            <li><code>path</code> (string) - Ruta del archivo HTML a cargar.</li>
                        </ul>
                        <p><strong>Funcionamiento:</strong></p>
                        <ul>
                            <li>Se utiliza <code>fetch(path)</code> para realizar la solicitud HTTP.</li>
                            <li>El método <code>.then()</code> procesa la respuesta y convierte el contenido a texto con <code>response.text()</code>.</li>
                            <li>El contenido obtenido se asigna al atributo <code>innerHTML</code> del elemento <code>#main</code>.</li>
                            <li>Si ocurre un error, el método <code>.catch()</code> captura la excepción y la registra en la consola.</li>
                        </ul>
                        <p><strong>Retorna:</strong> Actualiza el contenido del elemento <code>#main</code> o muestra un error en la consola si la solicitud falla.</p>
                    </details>
                    
                    <details>
                        <summary>links.forEach(link => ...)</summary>
                        <p>Asigna eventos <code>click</code> a los enlaces del sidebar para gestionar la clase <code>selected</code>.</p>
                        <p><strong>Función:</strong> Marca el enlace clickeado como seleccionado y desmarca los demás.</p>
                    </details>
                    
                    <details>
                        <summary>window.onload</summary>
                        <p>Simula un clic en el elemento con ID <code>home</code> al cargar la página.</p>
                        <p><strong>Función:</strong> Asegura que el contenido inicial se cargue automáticamente.</p>
                    </details>
                </details>
            
                <!-- Navbar.js -->
                <details>
                    <summary>navbar.js</summary>
                    <p>Este script maneja la animación del menú de usuario y el cierre de sesión.</p>
                    
                    <details>
                        <summary>toggleUserMenu()</summary>
                        <p>Alterna la visibilidad del menú de usuario.</p>
                        <p><strong>Función:</strong> Añade o quita las clases <code>hidden</code> y <code>visible</code> en el elemento con ID <code>userMenu</code>.</p>
                    </details>

                    <details>
                        <summary>logout()</summary>
                        <p>Cierra la sesión del usuario y redirige a la página de inicio de sesión.</p>
                        <p><strong>Función:</strong> Realiza una solicitud <code>fetch</code> al endpoint <code>/api/logout</code>. Si la solicitud es exitosa, redirige al usuario a <code>/login</code>. En caso de error, muestra un mensaje en la consola.</p>
                    </details>
                </details>

            </section>

            <section id="flujo">
                <h2>Flujo de Rutas en la Aplicación</h2>
                <p>Este documento describe el flujo que sigue cada ruta en la aplicación desde que se realiza una solicitud hasta que se genera la respuesta.</p>
                <!-- Pasos de las rutas -->
                <details>
                    <summary>Flujo de Rutas</summary>
                    <div class="step">
                        <h2>1. Solicitud inicial del cliente</h2>
                        <p>El cliente realiza una solicitud HTTP a una URL específica, como <code>/home</code> o <code>/api/login</code>.</p>
                    </div>
    
                    <div class="step">
                        <h2>2. Redirección por .htaccess</h2>
                        <p>El archivo <code>.htaccess</code> intercepta la solicitud. Si la solicitud no corresponde a un archivo o directorio existente, redirige la solicitud a <code>index.php</code> con la misma URI.</p>
                    </div>
    
                    <div class="step">
                        <h2>3. Ejecución de index.php</h2>
                        <p>El archivo <code>index.php</code> se ejecuta. Se incluye el archivo del enrutador <code>Router.php</code> y se crea una instancia de la clase <code>Router</code>.</p>
                    </div>
    
                    <div class="step">
                        <h2>4. Definición de rutas</h2>
                        <p>En <code>index.php</code>, se definen las rutas mediante el método <code>add</code> de la clase <code>Router</code>. Por ejemplo:</p>
                        <pre><code>$router->add('/home', 'ctlSidebar', 'home');</code></pre>
                        <p>Esto asocia la ruta <code>/home</code> con el controlador <code>ctlSidebar</code> y el método <code>home</code>.</p>
                    </div>
    
                    <div class="step">
                        <h2>5. Despacho de la solicitud</h2>
                        <p>La URI solicitada se obtiene mediante <code>$_SERVER['REQUEST_URI']</code>. Luego, se llama al método <code>dispatch</code> de la clase <code>Router</code> con la URI como argumento:</p>
                        <pre><code>$router->dispatch($requestUri);</code></pre>
                    </div>
    
                    <div class="step">
                        <h2>6. Lógica del enrutador (Router.php)</h2>
                        <p>En el método <code>dispatch</code> de <code>Router.php</code>:</p>
                        <ul>
                            <li>Se verifica si la URI solicitada coincide con alguna ruta registrada.</li>
                            <li>Si coincide:
                                <ul>
                                    <li>Se carga el archivo del controlador correspondiente.</li>
                                    <li>Se crea una instancia del controlador.</li>
                                    <li>Se llama al método asociado a la ruta.</li>
                                </ul>
                            </li>
                            <li>Si no coincide, se incluye la vista de error 404.</li>
                        </ul>
                    </div>
    
                    <div class="step">
                        <h2>7. Ejecución del controlador</h2>
                        <p>El controlador correspondiente se ejecuta. Por ejemplo:</p>
                        <ul>
                            <li>Para la ruta <code>/home</code>, se ejecuta el método <code>home</code> de la clase <code>ctlSidebar</code>.</li>
                            <li>En este método, las variables necesarias se definen desde el modelo o se obtienen desde el método HTTP correspondiente.</li>
                            <li>Este método puede incluir una vista, como <code>home.php</code>, para generar la respuesta.</li>
                        </ul>
                    </div>
    
                    <div class="step">
                        <h2>8. Generación de la respuesta</h2>
                        <p>El controlador incluye la vista correspondiente, que genera el contenido HTML o JSON que se enviará al cliente.</p>
                    </div>
    
                    <div class="step">
                        <h2>9. Respuesta al cliente</h2>
                        <p>El servidor envía la respuesta generada (HTML, JSON, etc.) al cliente. El cliente recibe y renderiza la respuesta en el navegador.</p>
                    </div>
    
                    <h2>Ejemplo: Ruta <code>/home</code></h2>
                    <ol>
                        <li>El cliente solicita <code>/home</code>.</li>
                        <li><code>.htaccess</code> redirige la solicitud a <code>index.php</code>.</li>
                        <li>En <code>index.php</code>, la ruta <code>/home</code> está asociada al controlador <code>ctlSidebar</code> y al método <code>home</code>.</li>
                        <li>El método <code>dispatch</code> de <code>Router.php</code> llama al método <code>home</code> de <code>ctlSidebar</code>.</li>
                        <li>El método <code>home</code> incluye la vista <code>home.php</code>, que genera el contenido HTML.</li>
                        <li>El servidor envía el HTML al cliente.</li>
                    </ol>
                </details>
                
            </section>

            <section id="css-classes">
                <h2>Clases CSS</h2>
                <ul>
                    <!-- Clases de sidebar.css -->
                    <details>
                        <summary>sidebar.css</summary>
                        <li><code>sidebar</code></li>
                        <li><code>sidebar.menu-toggle</code></li>
                        <li><code>sidebar a</code></li>
                        <li><code>sidebar a:hover</code></li>
                        <li><code>sidebar a.selected</code></li>
                        <li><code>sidebar a.search</code></li>
                        <li><code>sidebar img</code></li>
                    </details>
                    <hr>
                    
                    <!-- Clases de navbar.css -->
                    <details>
                        <summary>navbar.css</summary>
                        <li><code>header</code></li>
                        <li><code>left</code></li>
                        <li><code>menu-container</code></li>
                        <li><code>menu</code></li>
                        <li><code>menu div</code></li>
                        <li><code>menu.menu-toggle div:first-child</code></li>
                        <li><code>menu.menu-toggle div:last-child</code></li>
                        <li><code>brand</code></li>
                        <li><code>brand .logo</code></li>
                        <li><code>right</code></li>
                        <li><code>right a</code></li>
                        <li><code>right a:hover</code></li>
                        <li><code>right img</code></li>
                        <li><code>right .user</code></li>
                    </details>
                    <hr>

                    <!-- Clases de userMenu.css -->
                    <details>
                        <summary>userMenu.css</summary>
                        <li><code>user-menu</code></li>
                        <li><code>user-menu-content</code></li>
                        <li><code>user-menu-content.hidden</code></li>
                        <li><code>user-menu-content.visible</code></li>
                        <li><code>user-menu-content button</code></li>
                        <li><code>user-menu-content button:hover</code></li>
                        <li><code>user-menu-content button:first-child</code></li>
                        <li><code>user-menu-content button:last-child</code></li>
                        <li><code>user-menu-icon</code></li>
                        <li><code>user-menu-item</code></li>
                    </details>
                    <hr>

                    <!-- Clases home.css -->
                    <details>
                        <summary>home.css</summary>
                        <li><code>container-list-task</code></li>
                        <li><code>contenedor-hijo</code></li>
                        <li><code>tittle-list-task</code></li>
                        <li><code>task-card</code></li>
                        <li><code>button.check</code></li>
                        <li><code>task-content</code></li>
                        <li><code>task-title</code></li>
                        <li><code>task-duration</code></li>
                        <li><code>task-date</code></li>
                        <li><code>add-task-button</code></li>
                        <li><code>add-task-button .icon</code></li>
                        <li><code>add-task-button .text</code></li>
                    </details>
                    <hr>
                
                    <!-- Clases de login.css -->
                    <details>
                        <summary>login.css</summary>
                        <li><code>wrapper</code></li>
                        <li><code>wrapper h1</code></li>
                        <li><code>wrapper .input-box</code></li>
                        <li><code>input-box input</code></li>
                        <li><code>input-box input::placeholder</code></li>
                        <li><code>input-box i</code></li>
                        <li><code>wrapper .remember-forgot</code></li>
                        <li><code>remember-forgot label input</code></li>
                        <li><code>remember-forgot a</code></li>
                        <li><code>remember-forgot a:hover</code></li>
                        <li><code>wrapper .btn</code></li>
                        <li><code>wrapper .space-1</code></li>
                        <li><code>wrapper .space-2</code></li>
                        <li><code>celula</code></li>
                        <li><code>celula1</code></li>
                        <li><code>celula2</code></li>
                    </details>
                    <hr>

                    <!-- Clases de main.css -->
                    <details>
                        <summary>main.css</summary>
                        <li><code>main</code></li>
                        <li><code>main.menu-toggle</code></li>
                    </details>
                
                </ul>
            </section>
            
        </div>
    </main>
</body>
</html>
