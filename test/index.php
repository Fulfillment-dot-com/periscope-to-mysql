<?php

require_once '../src/periscopeToMySQL.php';

use Fulfillment\periscopeToMySQL\periscopeToMySQL;

$args = [
	'dateStart' => "2016-01-01",
	'dateEnd'   => "2016-03-11",
	'Warehouse' => "1,2,3",
	'State'     => "GA",
];

$sql = 'SELECT
            [yourFirstTable.aDate:est] AS aDate,
            yourSecondTable.part2
        FROM
            yourFirstTable
            JOIN yourSecondTable ON yourFirstTable.id = yourSecondTable.id
        WHERE
            [yourFirstTable.recordedOn=daterange:est]
            AND [yourFirstTable.warehouse=Warehouse]
            AND [yourSecondTable.state=State]';

$sql = periscopeToMySQL::fillTemplate($sql, $args);

echo $sql;