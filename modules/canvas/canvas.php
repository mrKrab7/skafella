<?php


if (isset($_POST['getImg'])) // определение события добавления файлов
{

    $mas = array(
        'typeDoor2' => array(
            'backgroundFon' => array(
                '0' => array(
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/fon-tupe2-dark.png',
                    'type' => 'center',
                    'zakladnaia' => '',
                    'locationOnCanvas' => 'background'
                ),
                '1' => array(
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/fon-2d-left.png',
                    'type' => 'left',
                    'zakladnaia' => '',
                    'locationOnCanvas' => 'background'
                ),
                '2' => array(
                    'src' => 'http',
                    'type' => 'right'
                )


            ),
            'frameFacade' => array(
                '0' => array(
                    'type' => 'd1',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door1_r_d1(new).png',
                    'locationOnCanvas' => 'frameType4Door1',
                    'DoorHandle' => 'handle1',
                    'article' => 'metallic'
                ),
                '1' => array(
                    'type' => 'd1',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door2_r_d1(new).png',
                    'locationOnCanvas' => 'frameType4Door2',
                    'DoorHandle' => 'handle1',
                    'article' => 'metallic'
                ),

                '2' => array(
                    'type' => 'd2',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door1_r_d2.png',
                    'locationOnCanvas' => 'frameType4Door1',
                    'DoorHandle' => 'handle1',
                    'article' => 'metallic'
                ),
                '3' => array(
                    'type' => 'd2',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door2_r_d2.png',
                    'locationOnCanvas' => 'frameType4Door2',
                    'DoorHandle' => 'handle1',
                    'article' => 'metallic'
                ),
                '4' => array(
                    'type' => 'd2',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door3_r_d2.png',
                    'locationOnCanvas' => 'frameType4Door3',
                    'DoorHandle' => 'handle1',
                    'article' => 'metallic'
                ),
                '5' => array(
                    'type' => 'd2',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door4_r_d2.png',
                    'locationOnCanvas' => 'frameType4Door4',
                    'DoorHandle' => 'handle1',
                    'article' => 'metallic'
                ),
                '6' => array(
                    'type' => 'd3',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door1_r_d3.png',
                    'locationOnCanvas' => 'frameType4Door1',
                    'DoorHandle' => 'handle1',
                    'article' => 'metallic'
                ),
                '7' => array(
                    'type' => 'd3',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door2_r_d3.png',
                    'locationOnCanvas' => 'frameType4Door2',
                    'DoorHandle' => 'handle1',
                    'article' => 'metallic'
                ),


            ),
            'textureFacade' => array(
                '0' => array(
                    'src' => 'http',
                    'doorPosition' => 'door1',
                    'article' => '&we432ds'
                )
            ),
            'textureCenterFacade' => array(
                '0' => array(
                    'type' => 'd',
                    'src' => 'http',
                    'doorPosition' => 'door1',
                    'article' => '&we432ds'
                )
            ),
            'textureCarcase' => array(
                '0' => array(
                    'typeSection' => 'полки',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4Section1.png',
                    'locationOnCanvas' => 'section1',
                    'article' => 's1-type1',
                    'color' => 'gray'
                ),
                '1' => array(
                    'typeSection' => 'полки',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4Section1-variant2.png',
                    'locationOnCanvas' => 'section1',
                    'article' => 's1-type2',
                    'color' => 'gray'
                ),
                '2' => array(
                    'typeSection' => 'полки',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4Section2.png',
                    'locationOnCanvas' => 'section2',
                    'article' => 's2-type1',
                    'color' => 'gray'
                ),
                '3' => array(
                    'typeSection' => 'полки',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4Section2-variant2.png',
                    'locationOnCanvas' => 'section2',
                    'article' => 's2-type2',
                    'color' => 'gray'
                ),

            )


        ),
        'typeDoor3' => array(
            'backgroundFon' => array(
                '0' => array(
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/fon-tupe3-dark.png',
                    'type' => 'center',
                    'locationOnCanvas' => 'background'
                ),

            ),
            'frameFacade' => array(
                '0' => array(
                    'type' => 'd3',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door1_r_d3.png',
                    'locationOnCanvas' => 'frameType4Door1',
                    'DoorHandle' => 'handle1',
                    'article' => 'metallic'
                ),
                '1' => array(
                    'type' => 'd3',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door2_r_d3.png',
                    'locationOnCanvas' => 'frameType4Door2',
                    'DoorHandle' => 'handle1',
                    'article' => 'metallic'
                ),
                '2' => array(
                    'type' => 'd3',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door3_r_d3.png',
                    'locationOnCanvas' => 'frameType4Door3',
                    'DoorHandle' => 'handle1',
                    'article' => 'metallic'
                ),

            ),
            'textureFacade' => array(
                '0' => array(
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door1_dyb.png',
                    'locationOnCanvas' => 'textureType4Door1',
                    'article' => 'dyb'
                ),
                '1' => array(
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door2_dyb.png',
                    'locationOnCanvas' => 'textureType4Door2',
                    'article' => 'dyb'
                ),
                '2' => array(
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door3_dyb.png',
                    'locationOnCanvas' => 'textureType4Door3',
                    'article' => 'dyb'
                ),
            ),
            'textureCenterFacade' => array(
                '0' => array(
                    'type' => 'd',
                    'src' => 'http',
                    'doorPosition' => 'door1',
                    'article' => '&we432ds'
                )
            ),
            'textureCarcase' => array(
                '0' => array(
                    'typeSection' => 'полки',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4Section1.png',
                    'locationOnCanvas' => 'section1',
                    'article' => 's1-type1',
                    'color' => 'gray'
                ),
                '1' => array(
                    'typeSection' => 'полки',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4Section2.png',
                    'locationOnCanvas' => 'section2',
                    'article' => 's2-type1',
                    'color' => 'gray'
                ),
                '2' => array(
                    'typeSection' => 'полки',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4Section3.png',
                    'locationOnCanvas' => 'section3',
                    'article' => 's3-type1',
                    'color' => 'gray'
                ),
                '3' => array(
                    'typeSection' => 'полки',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4Section1-variant2.png',
                    'locationOnCanvas' => 'section1',
                    'article' => 's1-type2',
                    'color' => 'gray'
                ),

                '4' => array(
                    'typeSection' => 'полки',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4Section2-variant2.png',
                    'locationOnCanvas' => 'section2',
                    'article' => 's2-type2',
                    'color' => 'gray'
                ),
            )
        ),
        'typeDoor4' => array(
            'backgroundFon' => array(
                '0' => array(
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_fon_night.png',
                    'type' => 'center',
                    'zakladnaia' => '',
                    'locationOnCanvas' => 'background'                 // местоположение на канвасе
                )
            ),
            'frameFacade' => array(                                     // рамка двери
                '0' => array(
                    'type' => 'd1',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door1_r_d1(new).png',
                    'locationOnCanvas' => 'frameType4Door1',
                    'DoorHandle' => 'handle1',                             // тип дверной ручке
                    'article' => 'metallic'
                ),
                '1' => array(
                    'type' => 'd1',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door2_r_d1(new).png',
                    'locationOnCanvas' => 'frameType4Door2',
                    'DoorHandle' => 'handle1',
                    'article' => 'metallic'
                ),
                '2' => array(
                    'type' => 'd1',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door3_r_d1(new).png',
                    'locationOnCanvas' => 'frameType4Door3',
                    'DoorHandle' => 'handle1',
                    'article' => 'metallic'
                ),
                '3' => array(
                    'type' => 'd1',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door4_r_d1(new).png',
                    'locationOnCanvas' => 'frameType4Door4',
                    'DoorHandle' => 'handle1',
                    'article' => 'metallic'
                ),
                '4' => array(
                    'type' => 'd2',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door1_r_d2.png',
                    'locationOnCanvas' => 'frameType4Door1',
                    'DoorHandle' => 'handle1',
                    'article' => 'metallic'
                ),
                '5' => array(
                    'type' => 'd2',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door2_r_d2.png',
                    'locationOnCanvas' => 'frameType4Door2',
                    'DoorHandle' => 'handle1',
                    'article' => 'metallic'
                ),
                '6' => array(
                    'type' => 'd2',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door3_r_d2.png',
                    'locationOnCanvas' => 'frameType4Door3',
                    'DoorHandle' => 'handle1',
                    'article' => 'metallic'
                ),
                '7' => array(
                    'type' => 'd2',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door4_r_d2.png',
                    'locationOnCanvas' => 'frameType4Door4',
                    'DoorHandle' => 'handle1',
                    'article' => 'metallic'
                ),
                '8' => array(
                    'type' => 'd3',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door1_r_d3.png',
                    'locationOnCanvas' => 'frameType4Door1',
                    'DoorHandle' => 'handle1',
                    'article' => 'metallic'
                ),
                '9' => array(
                    'type' => 'd3',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door2_r_d3.png',
                    'locationOnCanvas' => 'frameType4Door2',
                    'DoorHandle' => 'handle1',
                    'article' => 'metallic'
                ),
                '10' => array(
                    'type' => 'd3',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door3_r_d3.png',
                    'locationOnCanvas' => 'frameType4Door3',
                    'DoorHandle' => 'handle1',
                    'article' => 'metallic'
                ),
                '11' => array(
                    'type' => 'd3',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door4_r_d3.png',
                    'locationOnCanvas' => 'frameType4Door4',
                    'DoorHandle' => 'handle1',
                    'article' => 'metallic'
                ),
                '12' => array(
                    'type' => 'd4',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door1_r_d4.png',
                    'locationOnCanvas' => 'frameType4Door1',
                    'DoorHandle' => 'handle1',
                    'article' => 'metallic'
                ),
                '13' => array(
                    'type' => 'd4',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door2_r_d4.png',
                    'locationOnCanvas' => 'frameType4Door2',
                    'DoorHandle' => 'handle1',
                    'article' => 'metallic'
                ),
                '14' => array(
                    'type' => 'd4',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door3_r_d4.png',
                    'locationOnCanvas' => 'frameType4Door3',
                    'DoorHandle' => 'handle1',
                    'article' => 'metallic'
                ),
                '15' => array(
                    'type' => 'd4',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door4_r_d4.png',
                    'locationOnCanvas' => 'frameType4Door4',
                    'DoorHandle' => 'handle1',
                    'article' => 'metallic'
                ),
                '16' => array(
                    'type' => 'd5',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door1_r_d5.png',
                    'locationOnCanvas' => 'frameType4Door1',
                    'DoorHandle' => 'handle1',
                    'article' => 'metallic'
                ),
                '17' => array(
                    'type' => 'd5',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door2_r_d5.png',
                    'locationOnCanvas' => 'frameType4Door2',
                    'DoorHandle' => 'handle1',
                    'article' => 'metallic'
                ),
                '18' => array(
                    'type' => 'd5',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door3_r_d5.png',
                    'locationOnCanvas' => 'frameType4Door3',
                    'DoorHandle' => 'handle1',
                    'article' => 'metallic'
                ),
                '19' => array(
                    'type' => 'd5',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door4_r_d5.png',
                    'locationOnCanvas' => 'frameType4Door4',
                    'DoorHandle' => 'handle1',
                    'article' => 'metallic'
                )
            ),
            'textureFacade' => array(                // фоновая текстура двери
                '0' => array(
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door1_fon_sosna.png',
                    'locationOnCanvas' => 'textureType4Door1',
                    'article' => 'sosna'

                ),
                '1' => array(
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door2_fon_sosna.png',
                    'locationOnCanvas' => 'textureType4Door2',
                    'article' => 'sosna'
                ),
                '2' => array(
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door3_fon_sosna.png',
                    'locationOnCanvas' => 'textureType4Door3',
                    'article' => 'sosna'
                ),
                '3' => array(
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door4_fon_sosna.png',
                    'locationOnCanvas' => 'textureType4Door4',
                    'article' => 'sosna'
                ),
                '4' => array(
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door4_fon_sosna.png',
                    'locationOnCanvas' => 'textureType4Door4',
                    'article' => 'sosna'
                ),
                '5' => array(
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door2_fon_z.png',
                    'locationOnCanvas' => 'textureType4Door2',
                    'article' => 'glass'
                ),
                '6' => array(
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door3_fon_z.png',
                    'locationOnCanvas' => 'textureType4Door3',
                    'article' => 'glass'
                ),
                '7' => array(
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door1_dyb.png',
                    'locationOnCanvas' => 'textureType4Door1',
                    'article' => 'dyb'
                ),
                '8' => array(
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door2_dyb.png',
                    'locationOnCanvas' => 'textureType4Door2',
                    'article' => 'dyb'
                ),
                '9' => array(
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door3_dyb.png',
                    'locationOnCanvas' => 'textureType4Door3',
                    'article' => 'dyb'
                ),
                '10' => array(
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door4_dyb.png',
                    'locationOnCanvas' => 'textureType4Door4',
                    'article' => 'dyb'
                ),
                '11' => array(
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door1_mirror.png',
                    'locationOnCanvas' => 'textureType4Door1',
                    'article' => 'mirror'
                ),
                '12' => array(
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door2_mirror.png',
                    'locationOnCanvas' => 'textureType4Door2',
                    'article' => 'mirror',
                    'swap' => 'mirror-center'
                ),
                '13' => array(
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door3_mirror.png',
                    'locationOnCanvas' => 'textureType4Door3',
                    'article' => 'mirror',
                    'swap' => 'mirror-center'
                ),
                '14' => array(
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door4_mirror.png',
                    'locationOnCanvas' => 'textureType4Door4',
                    'article' => 'mirror'
                ),
                '15' => array(
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door1_t_white.png',
                    'locationOnCanvas' => 'textureType4Door1',
                    'article' => 'white-texture'
                ),
                '16' => array(
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door2_t_white.png',
                    'locationOnCanvas' => 'textureType4Door2',
                    'article' => 'white-texture'
                ),
                '17' => array(
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door3_t_white.png',
                    'locationOnCanvas' => 'textureType4Door3',
                    'article' => 'white-texture'
                ),
                '18' => array(
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door4_t_white.png',
                    'locationOnCanvas' => 'textureType4Door4',
                    'article' => 'white-texture'
                ),


            ),
            'textureCenterFacade' => array( // фоновая центровая текстура
                '0' => array(
                    'type' => 'd2',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door1_d2_c_steklo1.png',
                    'locationOnCanvas' => 'textureCenter1Type4',
                    'article' => 'yellow'
                ),
                '1' => array(
                    'type' => 'd2',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door2_d2_c_steklo1.png',
                    'locationOnCanvas' => 'textureCenter2Type4',
                    'article' => 'yellow'
                ),
                '2' => array(
                    'type' => 'd2',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door3_d2_c_steklo1.png',
                    'locationOnCanvas' => 'textureCenter3Type4',
                    'article' => 'yellow'
                ), '3' => array(
                    'type' => 'd2',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door4_d2_c_steklo1.png',
                    'locationOnCanvas' => 'textureCenter4Type4',
                    'article' => 'yellow'
                ),
                '4' => array(
                    'type' => 'd3',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door1_center-white.png',
                    'locationOnCanvas' => 'textureCenter1Type4',
                    'article' => 'white'
                ),
                '5' => array(
                    'type' => 'd3',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door2_center-white.png',
                    'locationOnCanvas' => 'textureCenter2Type4',
                    'article' => 'white'
                ),
                '6' => array(
                    'type' => 'd3',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door3_center-white.png',
                    'locationOnCanvas' => 'textureCenter3Type4',
                    'article' => 'white'
                ),
                '7' => array(
                    'type' => 'd3',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4_door4_center-white.png',
                    'locationOnCanvas' => 'textureCenter4Type4',
                    'article' => 'white'
                )
            ),
            'textureCarcase' => array(
                '0' => array(        // текстура секций
                    'typeSection' => 'полки',      //  дополнительный артикул (под вопросом)
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4Section1.png',
                    'locationOnCanvas' => 'section1',
                    'article' => 's1-type1',
                    'color' => 'dyb-section'
                ),
                '1' => array(
                    'typeSection' => 'полки',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4Section2.png',
                    'locationOnCanvas' => 'section2',
                    'article' => 's2-type1',
                    'color' => 'dyb-section'
                ),
                '2' => array(
                    'typeSection' => 'полки',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4Section3.png',
                    'locationOnCanvas' => 'section3',
                    'article' => 's3-type1',
                    'color' => 'dyb-section'
                ),
                '3' => array(
                    'typeSection' => 'полки',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4Section4.png',
                    'locationOnCanvas' => 'section4',
                    'article' => 's4-type1',
                    'color' => 'dyb-section'
                ),
                '4' => array(
                    'typeSection' => 'полки',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4Section1-variant2.png',
                    'locationOnCanvas' => 'section1',
                    'article' => 's1-type2',
                    'color' => 'dyb-section'
                ),

                '5' => array(
                    'typeSection' => 'полки',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/type4Section2-variant2.png',
                    'locationOnCanvas' => 'section2',
                    'article' => 's2-type2',
                    'color' => 'dyb-section'
                ),
                '6' => array(
                    'typeSection' => 'полки',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/section1-white.png',
                    'locationOnCanvas' => 'section1',
                    'article' => 's1-type1',
                    'color' => 'white-section'
                ),
                '7' => array(
                    'typeSection' => 'полки',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/section2-white.png',
                    'locationOnCanvas' => 'section2',
                    'article' => 's1-type1',
                    'color' => 'white-section'
                ),
                '8' => array(
                    'typeSection' => 'полки',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/section3-white.png',
                    'locationOnCanvas' => 'section3',
                    'article' => 's1-type1',
                    'color' => 'white-section'
                ),
                '9' => array(
                    'typeSection' => 'полки',
                    'src' => 'http://shkafella.itvaksa.ru/zed/photo/section4-white.png',
                    'locationOnCanvas' => 'section4',
                    'article' => 's1-type1',
                    'color' => 'white-section'
                ),
            ))
    );


    //print_r($tec_param);
    echo json_encode($mas);
}
?>