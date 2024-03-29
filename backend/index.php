<?php
$articles = [
	["id" => 1, "title" => "První článek", "content" => "Obsah prvního článku"],
	["id" => 2, "title" => "Druhý článek", "content" => "Obsah druhého článku"],
	["id" => 3, "title" => "Třetí článek", "content" => "Obsah třetího článku"]
];

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
echo json_encode($articles);
