<?php

namespace app\models;

class MatrizOperations
{
    
    private $matriz;
    private static $mensajeError = [
        '1' => [
            'Mensaje' => 'La primera no contiene un número entero (Número de pruebas).'
        ],
        '2' => [
            'Mensaje' => 'El número de pruebas no debe ser mayor a 50.'
        ],
        '3' => [
            'Mensaje' => 'Error en la función UPDATE, las coordenadas deben ser'
            . ' insertadas en el formato  ( x y z valor ). El error está en la linea '            
        ],
        '4' => [
            'Mensaje' => 'Error en la función QUERY, las coordenadas deben ser'
            . ' insertadas en el formato ( x1 x2 z1 x2 y2 z2). El error está en la linea '
        ],
        '5' => [
            'Mensaje' => ''
        ]
        
    ];

    public function __construct() {
        //$this->input = $input;
        //$this->controlDatos($input);
    }
    
    
    
    
    
    
    //Función para manejar como llegan los datos que llegan del formulario
    //matrizform, esto con el fin de separarlos tan y como lo solicita el problema,
    //primero por renglones y luego dependiendo del comando ingresado, ejecutar dicha función
    
    
    public function controlDatos($input)
    {
        //se separan los datos en arreglos
        $datos = explode("\n", $input);
        $resultados = array();
        //validación para que el número de tests sea un número
        if($this->validarCantidadTests((int)$datos[0]))
        {
            $resultados = $this->encontrarMensajeError('2');
        }
        else{
            $datosCompletos = array();
        //se separa cada linea en sub arreglos
        for($x = 1 ; $x < count($datos); $x++ )
        {
            $datosCompletos[] = explode(' ', $datos[$x]);
        }
        
        
        foreach ($datosCompletos as $index => $data)
        {
            
            
            
            //validación de datos a través de la función validarDatos() -- Se hace para cada linea de forma
            //individual
            
            
            
            //si no hay errores continua
            //condicionales para indicar cuando hay que realizar queries, updates o crear matrices nuevas
            if(!in_array('QUERY', $data) && !in_array('UPDATE', $data))
            {
                $this->crearMatriz($datosCompletos[$index][0]);
            }
            elseif (in_array('UPDATE', $data)) 
            {
                $resultados[] = $this->actualizarMatriz($datosCompletos[$index][1], $datosCompletos[$index][2], $datosCompletos[$index][3], $datosCompletos[$index][4]);
            }
            elseif (in_array('QUERY', $data))
            {   
                $resultados[] = $this->consultarSumaMatriz($datosCompletos[$index][1], $datosCompletos[$index][2], $datosCompletos[$index][3], 
                        $datosCompletos[$index][4], $datosCompletos[$index][5], $datosCompletos[$index][6]);                
            }
            
        }
        //se eliminar del arreglo de resultados los indices que tienen valores vacios a excepción de los ceros
        //a través de la función is_numeric
        $resultados = array_filter($resultados , 'is_numeric');
        }
        
        return $resultados;
    }
    
    
    //función para validar la cantidad de tests - validación de entero y de tamaño < 50
    private function validarCantidadTests($cantidad)
    {
        if(!is_int($cantidad))
        {
            return true;
        }
        if($cantidad > 50)
        {            
            return true;
        }
        return false;
        
    }
    
    //buscador de mensajes
    private function encontrarMensajeError($id)
    {
        return self::$mensajeError[$id];
    }




    //función para validar los datos de entrada del formulario principal - esta función
    //recibe los datos de una linea cada linea de manera individual
    private function validarDatos($input)
    {
        
    }







    //función para crear una matriz tridimensional a partir de una tamaño indicado
    private function crearMatriz($tamano)
    {
        for($x = 0; $x <= $tamano ; $x++)
        {
            for($y = 0; $y <= $tamano; $y++)
            {
                for($z = 0 ; $z <= $tamano ; $z++)
                {
                    $this->matriz[$x][$y][$z] = 0;
                }
            }
        }
    }
    
    
    //función para consultar la suma de la matriz a partir de unas posiciones específicas y hasta otra posición
    //específica indicadas cada letra en su número uno como punto de partida en el arreglo y cada letra en 
    //su número 2 como el punto final en el arreglo.
    private function consultarSumaMatriz($x1 , $y1, $z1 , $x2 , $y2, $z2)
    {
        $suma = 0;
        for($x = $x1; $x <= $x2 ; $x++)
        {
            for($y = $y1; $y <= $y2; $y++)
            {
                for($z = $z1 ; $z <= $z2 ; $z++)
                {
                    $suma = $suma + $this->matriz[$x][$y][$z];
                }
            }
        }
        return $suma;
    }
    //función encargada de actualizar los valores de la matriz dada unas coordenadas especificas en el arreglo
    private function actualizarMatriz($x , $y , $z , $valor)
    {
        $this->matriz[$x][$y][$z] = $valor;
    }
    
    
    
}

