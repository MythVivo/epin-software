<?php

namespace App\Helpers;


class Datatable
{
    function header()
    {

        echo '
        <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
        ';
    }
    function html()
    {
        echo '
        <table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Position</th>
                <th>Office</th>
                <th>Extn.</th>
                <th>Start date</th>
                <th>Salary</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Name</th>
                <th>Position</th>
                <th>Office</th>
                <th>Extn.</th>
                <th>Start date</th>
                <th>Salary</th>
            </tr>
        </tfoot>
    </table>
    ';
    }
    function js()
    {
        echo '
        <script>
        $(document).ready(function () {
            $("#example").DataTable({
                processing: true,
                serverSide: true,
                ajax: "datatable/ajax",
                "columns": [
                    { "name": "engine" },
                    { "name": "browser" },
                    { "name": "platform" },
                    { "name": "version" },
                    { "name": "grade" },
                    { "name": "xxx" }
                  ]
            });
        });
        </script>
        ';
    }
    function get()
    {
        echo self::header();
        echo self::html();
        echo self::js();
    }
    function ouput($data, $totalCount = 0, $draw = 1)
    {
        $totalCount = $totalCount == 0 ? sizeof($data) : $totalCount;
        $output = [
            "draw" => ($draw),
            "recordsTotal" => $totalCount,
            "recordsFiltered" => $totalCount,
            "data" => $data
        ];
        return json_encode($output, JSON_UNESCAPED_UNICODE);
    }
    function ajax()
    {
        echo json_encode(@$_GET);
        echo "<br><br><br><br>";
        exit();
        $xxx = [
            [
                "Airi",
                "Satou",
                "Accountant",
                "Tokyo",
                "28th Nov 08",
                "$162,700"
            ],
            [
                "Angelica",
                "Ramos",
                "Chief Executive Officer (CEO)",
                "London",
                "9th Oct 09",
                "$1,200,000"
            ],
            [
                "Ashton",
                "Cox",
                "Junior Technical Author",
                "San Francisco",
                "12th Jan 09",
                "$86,000"
            ],
            [
                "Bradley",
                "Greer",
                "Software Engineer",
                "London",
                "13th Oct 12",
                "$132,000"
            ],
            [
                "Brenden",
                "Wagner",
                "Software Engineer",
                "San Francisco",
                "7th Jun 11",
                "$206,850"
            ],
            [
                "Brielle",
                "Williamson",
                "Integration Specialist",
                "New York",
                "2nd Dec 12",
                "$372,000"
            ],
            [
                "Bruno",
                "Nash",
                "Software Engineer",
                "London",
                "3rd May 11",
                "$163,500"
            ],
            [
                "Caesar",
                "Vance",
                "Pre-Sales Support",
                "New York",
                "12th Dec 11",
                "$106,450"
            ],
            [
                "Cara",
                "Stevens",
                "Sales Assistant",
                "New York",
                "6th Dec 11",
                "$145,600"
            ],
            [
                "Cedric",
                "Kelly",
                "Senior Javascript Developer",
                "Edinburgh",
                "29th Mar 12",
                "$433,060"
            ]
        ];
        echo self::ouput($xxx, 0, @$_GET['draw'] ? $_GET['draw'] : 1);
    }
}
