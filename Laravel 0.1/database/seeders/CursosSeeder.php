<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CursosSeeder extends Seeder
{
    public function run()
    {
        // Cursos Malla 2025 (id_malla = 2)
        $cursosMalla2025 = [
            // CICLO 1
            ['id_malla' => 2, 'codigo_curso' => 'C.3', 'nombre_curso' => 'Comprensión y Producción de Textos 1', 'ciclo' => 1, 'creditos' => 3.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.3', 'nombre_curso' => 'Laboratorio de Liderazgo e Innovación', 'ciclo' => 1, 'creditos' => 3.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.4', 'nombre_curso' => 'Estrategias y Herramientas Digitales para el Aprendizaje', 'ciclo' => 1, 'creditos' => 4.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.4', 'nombre_curso' => 'Matemática Básica', 'ciclo' => 1, 'creditos' => 4.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.4', 'nombre_curso' => 'Matemática Discreta 1', 'ciclo' => 1, 'creditos' => 4.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.2', 'nombre_curso' => 'Técnicas de Programación', 'ciclo' => 1, 'creditos' => 2.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.3', 'nombre_curso' => 'Introducción a la Ingeniería de Sistemas e Informática', 'ciclo' => 1, 'creditos' => 3.0],

            // CICLO 2
            ['id_malla' => 2, 'codigo_curso' => 'C.4', 'nombre_curso' => 'Comprensión y Producción de Textos 2', 'ciclo' => 2, 'creditos' => 4.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.3', 'nombre_curso' => 'Electivo General 1', 'ciclo' => 2, 'creditos' => 3.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.4', 'nombre_curso' => 'Álgebra Lineal y Geometría Analítica', 'ciclo' => 2, 'creditos' => 4.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.3', 'nombre_curso' => 'Modelado de Negocios', 'ciclo' => 2, 'creditos' => 3.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.4', 'nombre_curso' => 'Matemática Superior', 'ciclo' => 2, 'creditos' => 4.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.4', 'nombre_curso' => 'Matemática Discreta 2', 'ciclo' => 2, 'creditos' => 4.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.2', 'nombre_curso' => 'Programación Orientada a Objetos', 'ciclo' => 2, 'creditos' => 2.0],

            // CICLO 3
            ['id_malla' => 2, 'codigo_curso' => 'C.4', 'nombre_curso' => 'Estadística y Probabilidades', 'ciclo' => 3, 'creditos' => 4.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.3', 'nombre_curso' => 'Electivo General 2', 'ciclo' => 3, 'creditos' => 3.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.2', 'nombre_curso' => 'Laboratorio de Liderazgo e Innovación Intermedio', 'ciclo' => 3, 'creditos' => 2.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.4', 'nombre_curso' => 'Cálculo Diferencial', 'ciclo' => 3, 'creditos' => 4.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.4', 'nombre_curso' => 'Física 1', 'ciclo' => 3, 'creditos' => 4.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.3', 'nombre_curso' => 'Base de Datos 1', 'ciclo' => 3, 'creditos' => 3.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.2', 'nombre_curso' => 'Diseño Web', 'ciclo' => 3, 'creditos' => 2.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.2', 'nombre_curso' => 'Estructura de Datos', 'ciclo' => 3, 'creditos' => 2.0],

            // CICLO 4
            ['id_malla' => 2, 'codigo_curso' => 'C.3', 'nombre_curso' => 'Electivo General 3', 'ciclo' => 4, 'creditos' => 3.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.3', 'nombre_curso' => 'Electivo General 4', 'ciclo' => 4, 'creditos' => 3.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.3', 'nombre_curso' => 'English Course 1', 'ciclo' => 4, 'creditos' => 3.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.4', 'nombre_curso' => 'Cálculo Integral', 'ciclo' => 4, 'creditos' => 4.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.4', 'nombre_curso' => 'Física Electromagnética', 'ciclo' => 4, 'creditos' => 4.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.4', 'nombre_curso' => 'Análisis y Diseño de Software', 'ciclo' => 4, 'creditos' => 4.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.2', 'nombre_curso' => 'Programación Web', 'ciclo' => 4, 'creditos' => 2.0],

            // CICLO 5
            ['id_malla' => 2, 'codigo_curso' => 'C.3', 'nombre_curso' => 'English Course 2', 'ciclo' => 5, 'creditos' => 3.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.3', 'nombre_curso' => 'Laboratorio de Liderazgo e Innovación Avanzado', 'ciclo' => 5, 'creditos' => 3.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.4', 'nombre_curso' => 'Ecuaciones Diferenciales', 'ciclo' => 5, 'creditos' => 4.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.3', 'nombre_curso' => 'Estadística para Ingeniería', 'ciclo' => 5, 'creditos' => 3.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.2', 'nombre_curso' => 'Desarrollo de Aplicaciones Web', 'ciclo' => 5, 'creditos' => 2.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.3', 'nombre_curso' => 'Base de Datos 2', 'ciclo' => 5, 'creditos' => 3.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.3', 'nombre_curso' => 'Arquitectura del Computador', 'ciclo' => 5, 'creditos' => 3.0],

            // CICLO 6
            ['id_malla' => 2, 'codigo_curso' => 'C.3', 'nombre_curso' => 'Investigación Académica', 'ciclo' => 6, 'creditos' => 3.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.3', 'nombre_curso' => 'English Course 3', 'ciclo' => 6, 'creditos' => 3.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.4', 'nombre_curso' => 'Investigación Operativa 1', 'ciclo' => 6, 'creditos' => 4.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.4', 'nombre_curso' => 'Métodos Numéricos', 'ciclo' => 6, 'creditos' => 4.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.4', 'nombre_curso' => 'Desarrollo de Videojuegos', 'ciclo' => 6, 'creditos' => 4.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.4', 'nombre_curso' => 'Sistemas Operativos', 'ciclo' => 6, 'creditos' => 4.0],

            // CICLO 7
            ['id_malla' => 2, 'codigo_curso' => 'C.3', 'nombre_curso' => 'English Course 4', 'ciclo' => 7, 'creditos' => 3.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.1', 'nombre_curso' => 'Electivo General 5', 'ciclo' => 7, 'creditos' => 1.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.3', 'nombre_curso' => 'Ingeniería Económica', 'ciclo' => 7, 'creditos' => 3.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.4', 'nombre_curso' => 'Redes de Computadoras', 'ciclo' => 7, 'creditos' => 4.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.4', 'nombre_curso' => 'Ingeniería de Software', 'ciclo' => 7, 'creditos' => 4.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.2', 'nombre_curso' => 'Proyectos de Innovación', 'ciclo' => 7, 'creditos' => 2.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.3', 'nombre_curso' => 'Fundamentos de Sistemas Dinámicos y Modelado', 'ciclo' => 7, 'creditos' => 3.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.4', 'nombre_curso' => 'Construcción y Pruebas de Software', 'ciclo' => 7, 'creditos' => 4.0],

            // CICLO 8
            ['id_malla' => 2, 'codigo_curso' => 'C.3', 'nombre_curso' => 'Simulación de Procesos', 'ciclo' => 8, 'creditos' => 3.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.3', 'nombre_curso' => 'Gestión de Proyectos en Ingeniería', 'ciclo' => 8, 'creditos' => 3.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.4', 'nombre_curso' => 'Conmutación y Enrutamiento', 'ciclo' => 8, 'creditos' => 4.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.4', 'nombre_curso' => 'Desarrollo de Aplicaciones Móviles', 'ciclo' => 8, 'creditos' => 4.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.3', 'nombre_curso' => 'Arquitectura de Software', 'ciclo' => 8, 'creditos' => 3.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.3', 'nombre_curso' => 'Metodologías Ágiles para el Desarrollo de Software', 'ciclo' => 8, 'creditos' => 3.0],

            // CICLO 9
            ['id_malla' => 2, 'codigo_curso' => 'C.3', 'nombre_curso' => 'Electivo Transversal o de Especialidad 1', 'ciclo' => 9, 'creditos' => 3.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.4', 'nombre_curso' => 'Taller de Investigación 1 en Ingeniería de Sistemas e Informática', 'ciclo' => 9, 'creditos' => 4.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.4', 'nombre_curso' => 'Proyectos de Diseño en Ingeniería de Sistemas e Informática', 'ciclo' => 9, 'creditos' => 4.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.4', 'nombre_curso' => 'Inteligencia de Negocios y Ciencia de Datos', 'ciclo' => 9, 'creditos' => 4.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.3', 'nombre_curso' => 'Seguridad de la Información', 'ciclo' => 9, 'creditos' => 3.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.2', 'nombre_curso' => 'Aplicaciones Cloud', 'ciclo' => 9, 'creditos' => 2.0],

            // CICLO 10
            ['id_malla' => 2, 'codigo_curso' => 'C.4', 'nombre_curso' => 'Taller de Investigación 2 en Ingeniería de Sistemas e Informática', 'ciclo' => 10, 'creditos' => 4.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.4', 'nombre_curso' => 'Proyectos de Diseño y Desarrollo en Ingeniería de Sistemas e Informática', 'ciclo' => 10, 'creditos' => 4.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.3', 'nombre_curso' => 'Auditoria de Sistemas', 'ciclo' => 10, 'creditos' => 3.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.2', 'nombre_curso' => 'Robótica y Machine Learning', 'ciclo' => 10, 'creditos' => 2.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.3', 'nombre_curso' => 'Planificación y Gestión de Tecnologías de la Información', 'ciclo' => 10, 'creditos' => 3.0],
            ['id_malla' => 2, 'codigo_curso' => 'C.3', 'nombre_curso' => 'Electivo Transversal o de Especialidad 2', 'ciclo' => 10, 'creditos' => 3.0],
        ];

        // Cursos Malla 2018 (id_malla = 1) - Algunos ejemplos
        $cursosMalla2018 = [
            // CICLO 1
            ['id_malla' => 1, 'codigo_curso' => 'C.4', 'nombre_curso' => 'Habilidades Comunicativas', 'ciclo' => 1, 'creditos' => 4.0],
            ['id_malla' => 1, 'codigo_curso' => 'C.5', 'nombre_curso' => 'Matemática Superior', 'ciclo' => 1, 'creditos' => 5.0],
            ['id_malla' => 1, 'codigo_curso' => 'C.2', 'nombre_curso' => 'Laboratorio de Liderazgo', 'ciclo' => 1, 'creditos' => 2.0],
            ['id_malla' => 1, 'codigo_curso' => 'C.3', 'nombre_curso' => 'Gestión del Aprendizaje', 'ciclo' => 1, 'creditos' => 3.0],
            ['id_malla' => 1, 'codigo_curso' => 'C.3', 'nombre_curso' => 'Química 1', 'ciclo' => 1, 'creditos' => 3.0],
            ['id_malla' => 1, 'codigo_curso' => 'C.3', 'nombre_curso' => 'Introducción a la Ing. de Sistemas e Informática', 'ciclo' => 1, 'creditos' => 3.0],
            ['id_malla' => 1, 'codigo_curso' => 'C.1', 'nombre_curso' => 'Herramientas Virtuales para el Aprendizaje', 'ciclo' => 1, 'creditos' => 1.0],

            // CICLO 2
            ['id_malla' => 1, 'codigo_curso' => 'C.3', 'nombre_curso' => 'Comunicación Efectiva', 'ciclo' => 2, 'creditos' => 3.0],
            ['id_malla' => 1, 'codigo_curso' => 'C.4', 'nombre_curso' => 'Fundamentos del Cálculo', 'ciclo' => 2, 'creditos' => 4.0],
            ['id_malla' => 1, 'codigo_curso' => 'C.3', 'nombre_curso' => 'Ética, Ciudadanía y Globalización', 'ciclo' => 2, 'creditos' => 3.0],
            ['id_malla' => 1, 'codigo_curso' => 'C.4', 'nombre_curso' => 'Álgebra Matricial y Geometría Analítica', 'ciclo' => 2, 'creditos' => 4.0],
            ['id_malla' => 1, 'codigo_curso' => 'C.3', 'nombre_curso' => 'Gestión Basada en Procesos', 'ciclo' => 2, 'creditos' => 3.0],
            ['id_malla' => 1, 'codigo_curso' => 'C.4', 'nombre_curso' => 'Matemática Discreta', 'ciclo' => 2, 'creditos' => 4.0],

            // Agrega más ciclos según necesites...
        ];

        DB::table('cursos')->insert(array_merge($cursosMalla2025, $cursosMalla2018));
    }
}
