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
            'Mensaje' => 'El número de pruebas no debe ser mayor a 50, ni menor a 0.'
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
            'Mensaje' => 'Error: El tamaño de la matriz no se puede ser superior a 100, ni inferior a 1 ó '
            . 'el número de operaciones no puede ser superior a 1000 ni menor a 1.'
            . ' El error está en la linea '
        ],
        '6' => [
            'Mensaje' => 'Error: Las coordenadas de la función UPDATE no deben ser superiores al '
            . 'tamaño de la matriz, ni menores a 0. Los valores de update no deben ser superiores'
            . ' a 10^9 ni menores a -10^9. El error está en la linea '
        ],
        '7' => [
            'Mensaje' => 'Error: las coordenadas iniciales no deben ser mayores a las coordenadas finales, ni '
            . 'mayores al tamaño de la matriz. El error está en la linea '
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
        
        $tamanoMatriz = 0;
        
        foreach ($datosCompletos as $index => $data)
        {   
             
            
            if(!in_array('QUERY', $data) && !in_array('UPDATE', $data))
            {
                //validación de datos a través de la función validarDatos() -- Se hace para cada linea de forma
                //individual 
                //si cumple los parámetros establecidos se crear la matriz, de lo contrario devuelve el error
                if($this->validarTamanoMatrizOperaciones($datosCompletos[$index][0], $datosCompletos[$index][1]))
                {
                    
                    
                    $mensaje = $this->encontrarMensajeError('5');
                    $posicion = $index + 2;
                    $resultados[] = $mensaje['Mensaje'] . $posicion ;
                    return $resultados;
                }
                else{
                    
                    $tamanoMatriz = $datosCompletos[$index][0];
                    $this->crearMatriz($datosCompletos[$index][0]);
                }
            } 
            
            
            if (in_array('UPDATE', $data)) 
            {
                //validación de datos para cuando el usuario ejecuta la función update
                
                
                if($this->validarUpdate((int)$datosCompletos[$index][1], (int)$datosCompletos[$index][2], (int)$datosCompletos[$index][3], 
                        (int)$tamanoMatriz, $datosCompletos[$index][4]))
                {  
                    
                    
                    $mensaje = $this->encontrarMensajeError('6');
                    $posicion = $index + 2;
                    $resultados[] = $mensaje['Mensaje'] . $posicion ;
                    return $resultados;
                }
                else{
                    
                    
                    $resultados[] = $this->actualizarMatriz($datosCompletos[$index][1], $datosCompletos[$index][2], $datosCompletos[$index][3], $datosCompletos[$index][4]);
                }
            }

            elseif (in_array('QUERY', $data))
            {  
                
                if($this->validarQuery((int)$datosCompletos[$index][1], (int)$datosCompletos[$index][2], (int)$datosCompletos[$index][3], 
                        (int)$datosCompletos[$index][4], (int)$datosCompletos[$index][5], (int)$datosCompletos[$index][6] , (int)$tamanoMatriz))
                { 
                    
                    
                    $mensaje = $this->encontrarMensajeError('7');
                    $posicion = $index + 2;
                    $resultados[] = $mensaje['Mensaje'] . $posicion ;
                    return $resultados;
                    
                }
                else{
                    $resultados[] = $this->consultarSumaMatriz($datosCompletos[$index][1], $datosCompletos[$index][2], $datosCompletos[$index][3], 
                        $datosCompletos[$index][4], $datosCompletos[$index][5], $datosCompletos[$index][6]);    
                }
                               
            }
            
            
        }
        //se eliminar del arreglo de resultados los indices que tienen valores vacios a excepción de los ceros
        //a través de la función is_numeric
        //$resultados = array_filter($resultados , 'is_numeric');
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
        if($cantidad > 50 || $cantidad < 1)
        {            
            return true;
        }
        return false;
        
    }
    
    //buscador de mensajes de error
    private function encontrarMensajeError($id)
    {
        return self::$mensajeError[$id];
    }



    //lista validaciones
    //funciones para validar los datos de entrada del formulario principal - estas funciones
    //reciben los datos de una linea cada linea de manera individual
    //se encargan de validar
    //1- Tamano de la matriz < 100
    //2- Número de operaciones < 1000
    //3- La segunda coordenada del query debe ser mayor o igual a la primera
    //4- Las coordenadas del query deben ser mayor a 1 y menores o igual que el tamaño del array
    //5- Las coordenadas del update deben ser mayor o igual a 1 y menos o igual que el tamaño del array
    
    //función que abarca el punto 1 y 2 de la "lista validaciones" 
    private function validarTamanoMatrizOperaciones($tamanoMatriz , $tamanoOperaciones)
    {
        if(($tamanoMatriz > 100 || $tamanoMatriz < 1) 
                || ($tamanoOperaciones > 1000 || $tamanoOperaciones < 1))
        {
            return true;
        }
        return false;
    }
    
    //función que abarca el punto 5 de la "lista validaciones"
    private function validarUpdate($x, $y, $z, $tamanoMatriz, $valor)
    {
        \var_dump($valor);
        if(($x > $tamanoMatriz || $x < 1) 
                || ($y > $tamanoMatriz || $y < 1) 
                || ($z > $tamanoMatriz || $z < 1) || ($valor > 10E+9 || $valor < -10E+9))
        {
            return true;
        }
        return false;
    }
    
    //función que abarca el punto 3 y 4
    private function validarQuery($x1 , $y1, $z1 , $x2 , $y2, $z2, $tamanoMatriz)
    {
        
        if((($x1 > $x2) || ($y1 > $y2) || ($z1 > $z2)) || (($x1 > $tamanoMatriz) || ($x2 > $tamanoMatriz))
                || (($y1 > $tamanoMatriz) || ($y2 > $tamanoMatriz)) || (($z1 > $tamanoMatriz) || ($z2 > $tamanoMatriz))
                || (($x1 < 1) || ($x2 < 1))
                || (($y1 < 1) || ($y2 < 1)) || (($z1 < 1) || ($z2 < 1)))
        {
            return true;
        }
        return false;
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

