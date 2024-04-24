<?php 

require_once "database/Database.php";

$db = new Database();


// ===============================================================
/*
INSERT INTO murids (name)
VALUES ('Subatsa')
*/
// $db->table('murids')->create(
//     ['name'], 
//     ['Subatsa']
// );
// ===============================================================


// ===============================================================
/*
INSERT INTO murids (name, gender)
VALUES ('Jidan', 'Laki-Laki'),
       ('Rafena', 'Perempuan'),
       ('Budi', 'Laki-Laki'),
       ('Salsa', 'Perempuan'),
       ('Dimas', 'Laki-Laki');
*/
// $db->table('murids')->insert(
//     [
//         'name',
//         'gender'
//     ],
//     [
//         [
//             'Jidan',
//             'Laki-Laki',
//         ],
//         [
//             'Rafena',
//             'Perempuan',
//         ],
//         [
//             'Budi',
//             'Laki-Laki',
//         ],
//         [
//             'Salsa',
//             'Perempuan',
//         ],
//         [
//             'Dimas',
//             'laki-Laki',
//         ]
//     ]
// );
// ===============================================================



// ===============================================================
/*
SELECT * FROM murids
WHERE name = 'Fena' AND
      gender = 'Perempuan'
*/
// $results = $db->table('murids')->getWhere([
//     'name' => 'Fena',
//     "gender" => "Perempuan"
// ]);
// ===============================================================


// ===============================================================
/*
SELECT * FROM murids
*/
// $results = $db->table('murids')->getAll();
// var_dump($results);





// ===============================================================
/*
UPDATE murids
SET name = 'Muhammad Jidan',
    gender = 'Laki-Laki'
WHERE id = 1
*/
// $db->table('murids')->update(
//     [
//         'name' => 'Muhammad Jidan',
//         'gender' => 'Laki-Laki'
//     ],
//     [
//         'id' => 1
//     ]
// );
// ===============================================================



// ===============================================================
/*
DELETE FROM murids 
WHERE name = 'Salsa' AND gender = 'Perempuan'
*/
// $db->table('murids')->deleteWhere([
//     'name' => 'Salsa',
//     'gender' => 'Perempuan'
// ]);
// ===============================================================