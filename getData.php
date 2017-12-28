<?php 
header('Content-Type: application/json');

$words = '[
            {
                "id": 0,
                "name": "Item 0",
                "price": "$0"
            },
            {
                "id": 1,
                "name": "Item 1",
                "price": "$1"
            },
            {
                "id": 2,
                "name": "Item 2",
                "price": "$2"
            },
            {
                "id": 3,
                "name": "Item 3",
                "price": "$3"
            },
            {
                "id": 4,
                "name": "Item 4",
                "price": "$4"
            },
            {
                "id": 5,
                "name": "Item 5",
                "price": "$5"
            }
          ]';
/*
$words = array(
            'id' => '0',
            'name' => 'Item 0',
            'price' => '$0'
         );
*/    
//echo json_encode($words);
echo($words);


//array_push($words,array(
//            'id' => '1',
//            'name' => 'Item 1',
//            'price' => '$1'
//         ));

/*
$json = '[
            {
                "id": 0,
                "name": "Item 0",
                "price": "$0"
            },
            {
                "id": 1,
                "name": "Item 1",
                "price": "$1"
            },
            {
                "id": 2,
                "name": "Item 2",
                "price": "$2"
            },
            {
                "id": 3,
                "name": "Item 3",
                "price": "$3"
            },
            {
                "id": 4,
                "name": "Item 4",
                "price": "$4"
            },
            {
                "id": 5,
                "name": "Item 5",
                "price": "$5"
            }
        ]';

*/



/*
echo '[
            {
                "id": 0,
                "name": "Item 0",
                "price": "$0"
            },
    ';
*/    

	/*
    case 'getProductosCampania':
		require_once (C_ROOT_DIR.'classes/csProductos.php');
		$csDatos = new csProductos();
		$rsDatos = $csDatos->getProductosPorCampania($_GET['idCampania']);
		$json = array();
		if ($rsDatos) {
		  while ($row = $rsDatos->fetch_assoc()) {
			$json[] = array('id' => $row["upc"].'~'.$row["codigo_producto"].'~'.$row["unidades_empaque"].'~'.utf8_encode($row["descripcion"]).'~'.$row["precio"], 'reg' => utf8_encode($row["descripcion"]).' - '.$row["upc"].' - '.$row["codigo_producto"]);
		  }    
		}
		echo json_encode($json);
		break;
    */

/*
echo "sdfsdf";
die();
    $json = '[
            {
                "id": 0,
                "name": "Item 0",
                "price": "$0"
            },
            {
                "id": 1,
                "name": "Item 1",
                "price": "$1"
            },
            {
                "id": 2,
                "name": "Item 2",
                "price": "$2"
            },
            {
                "id": 3,
                "name": "Item 3",
                "price": "$3"
            },
            {
                "id": 4,
                "name": "Item 4",
                "price": "$4"
            },
            {
                "id": 5,
                "name": "Item 5",
                "price": "$5"
            }
        ]';

    var_dump($json);
    echo json_encode($json);
*/
?>