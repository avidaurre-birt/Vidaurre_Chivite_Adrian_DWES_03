<?php


class Actividad
{
    protected $id;
    protected $fecha;
    protected $duracion;
    protected $distancia;

    public function __construct()
    {
    }
    //GET
    public function getAll()
    {
        $data = file_get_contents('../data/actividades.json');
        $actividades = json_decode($data, true);

        if ($actividades) {

            print_r($actividades) . '<br>';

            // Devuelve las actividades con el código HTTP 200 (OK)
            HttpCodes::sendResponse(HttpCodes::HTTP_OK, "Lista de actividades obtenida con exito.");
        } else {
            // Devuelve un mensaje de error con el código HTTP 500 (Internal Server Error)
            HttpCodes::sendResponse(HttpCodes::HTTP_INTERNAL_SERVER_ERROR, "Error al obtener la lista de actividades.");
        }
    }

    public function getById($id)
    {
        // Lee el contenido del archivo actividades.json
        $data = file_get_contents('../data/actividades.json');
        $actividades = json_decode($data, true);

        // Busca la actividad con el ID proporcionado
        $actividadEncontrada = null;
        foreach ($actividades as $actividad) {
            if ($actividad['Id'] == $id) {
                $actividadEncontrada = $actividad;
                break;
            }
        }

        if ($actividadEncontrada) {
            // Devuelve la actividad encontrada con el código HTTP 200 (OK)
            print_r($actividadEncontrada) . '<br>';

            HttpCodes::sendResponse(HttpCodes::HTTP_OK, "Actividad encontrada con exito.");
        } else {
            // Devuelve un mensaje de error con el código HTTP 404 (Not Found)
            HttpCodes::sendResponse(HttpCodes::HTTP_NOT_FOUND, "No se encontro la actividad con el ID proporcionado.");
        }
    }

    //POST
    public function createAct($data)
    {
        // Lee el contenido actual del archivo actividades.json
        $info = file_get_contents('../data/actividades.json');
        $actividades = json_decode($info, true);

        // Obtiene un nuevo ID para la nueva actividad
        $nuevoId = count($actividades) + 1;

        if (isset($data['Fecha']) && isset($data['Duracion_min']) && isset($data['Distancia_km']) && isset($data['Tipo'])) {
            // Agrega la nueva actividad al array de actividades
            $actividades[] = [
                'Id' => $nuevoId,
                'Fecha' => $data['Fecha'],
                'Duracion_min' => $data['Duracion_min'],
                'Distancia_km' => $data['Distancia_km'],
                'Tipo' => $data['Tipo']
            ];

            // Guarda el nuevo array de actividades en el archivo actividades.json
            file_put_contents('../data/actividades.json', json_encode($actividades, JSON_PRETTY_PRINT));

            print_r($actividades[$nuevoId - 1]) . '<br>';


            HttpCodes::sendResponse(HttpCodes::HTTP_CREATED, "Actividad creada con exito.");
        } else {
            //Devuelve un mensaje de error con el código HTTP 400 (Bad Request)
            HttpCodes::sendResponse(HttpCodes::HTTP_BAD_REQUEST, "Datos incompletos o incorrectos.");
        }
    }

    //PUT
    public function updateAct($id, $data)
    {
        // Lee el contenido actual del archivo actividades.json
        $info = file_get_contents('../data/actividades.json');
        $actividades = json_decode($info, true);
        $actividadEncontrada = false;

        if (isset($data['Fecha']) || isset($data['Duracion_min']) || isset($data['Distancia_km']) || isset($data['Tipo'])) {

            // Busca la actividad con el ID proporcionado
            foreach ($actividades as $actividad) {
                if ($actividad['Id'] == $id) {

                    // Actualiza solo si se proporciona un nuevo valor
                    if (empty($data['Fecha'])) {
                        $data['Fecha'] = $actividad[$id]['Fecha'];
                    }
                    if (empty($data['Duracion_min'])) {
                        $data['Duracion_min'] = $actividad['Duracion_min'];
                    }
                    if (empty($data['Distancia_km'])) {
                        $data['Distancia_km'] = $actividad['Distancia_km'];
                    }
                    if (empty($data['Tipo'])) {
                        $data['Tipo'] = $actividad['Tipo'];
                    }

                    $actividades[$id] = [
                        'Id' => $id,
                        'Fecha' => $data['Fecha'],
                        'Duracion' => $data['Duracion_min'],
                        'Distancia' => $data['Distancia_km'],
                        'Tipo' => $data['Tipo']
                    ];

                    $actividadEncontrada = true;

                    // Guarda el nuevo array de actividades en el archivo actividades.json
                    file_put_contents('../data/actividades.json', json_encode($actividades, JSON_PRETTY_PRINT));

                    print_r($actividades[$id]) . '<br>';

                    HttpCodes::sendResponse(HttpCodes::HTTP_OK, "Actividad actualizada con exito.");
                }
            }
            if (!$actividadEncontrada) {
                // Devuelve un mensaje de error con el código HTTP 404 (Not Found)
                HttpCodes::sendResponse(HttpCodes::HTTP_NOT_FOUND, "No se encontro la actividad con ID $id.");
            }
        } else {
            // Devuelve un mensaje de error con el código HTTP 400 (Bad Request)
            HttpCodes::sendResponse(HttpCodes::HTTP_BAD_REQUEST,  "Datos incompletos o incorrectos.");
        }
    }

    //DELETE
    public function deleteAct($id)
    {
        // Lee el contenido actual del archivo actividades.json
        $info = file_get_contents('../data/actividades.json');
        $actividades = json_decode($info, true);

        // Busca la actividad con el ID proporcionado
        foreach ($actividades as $key => $actividad) {
            if ($actividad['Id'] == $id) {

                // Elimina el registro 
                unset($actividades[$key]);

                // Reindexa el array para evitar índices discontinuos
                $actividades = array_values($actividades);

                // Guarda el nuevo array de actividades en el archivo actividades.json
                file_put_contents('../data/actividades.json', json_encode($actividades, JSON_PRETTY_PRINT));

                HttpCodes::sendResponse(HttpCodes::HTTP_NO_CONTENT, "Actividad eliminada con exito.");

                return;
            } else {
                // Devuelve un mensaje de error con el código HTTP 404 (Not Found)
                HttpCodes::sendResponse(HttpCodes::HTTP_NOT_FOUND, "No se encontro la actividad con el ID " . $id);
            }
        }
    }
}

class Carrera extends Actividad
{

    protected $desnivel = 0;

    public function __construct($id, $fecha, $duracion, $distancia, $desnivel)
    {
        parent::__construct($id, $fecha, $duracion, $distancia);
        $this->desnivel = $desnivel;
    }
}
class Andar extends Actividad
{

    protected $desnivel = 0;

    public function __construct($id, $fecha, $duracion, $distancia, $desnivel)
    {
        parent::__construct($id, $fecha, $duracion, $distancia);
        $this->desnivel = $desnivel;
    }


    public function getTipo()
    {
        return 'Andar';
    }
}
class Natacion extends Actividad
{

    protected $largos;

    public function __construct($id, $fecha, $duracion, $distancia, $largos)
    {
        parent::__construct($id, $fecha, $duracion, $distancia);
        $this->largos = $largos;
    }


    public function getTipo()
    {
        return 'Natacion';
    }
}
