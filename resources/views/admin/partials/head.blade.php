<head>

    <meta charset="UTF-8">
    <title>OTT Admin Panel</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- DataTables -->
    <link rel="stylesheet"
        href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    @vite(['resources/css/app.css','resources/js/app.js'])

    <style>
        * {
            box-sizing: border-box
        }

        body {
            margin: 0;
            font-family: Inter, Arial, sans-serif;
            background: #0f0f0f;
            color: #fff;
            display: flex;
        }

        .sidebar {
            width: 240px;
            background: #141414;
            min-height: 100vh;
            padding: 24px 20px;
        }

        .sidebar h2 {
            color: #e50914;
            margin-bottom: 30px;
            font-size: 20px;
        }

        .sidebar a {
            display: block;
            padding: 10px 0;
            color: #ccc;
            text-decoration: none;
            font-size: 14px;
        }

        .sidebar a:hover {
            color: #fff;
        }

        .content {
            flex: 1;
            padding: 30px;
        }

        .card {
            background: #1c1c1c;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            padding: 12px 10px;
            text-align: left;
            border-bottom: 1px solid #2a2a2a;
            font-size: 14px;
        }

        th {
            color: #aaa;
            font-weight: 600;
        }

        input,
        select {
            background: #222;
            border: 1px solid #333;
            color: #fff;
            padding: 8px;
            border-radius: 4px;
            font-size: 13px;
        }

        button {
            background: #e50914;
            border: none;
            color: #fff;
            padding: 8px 14px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
        }

        button.secondary {
            background: #333;
        }

        .badge {
            background: #333;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            margin-right: 5px;
        }

        .logout-btn {
            margin-top: 30px;
            width: 100%;
        }

        /* DataTables Dark */

        .dataTables_wrapper,
        .dataTables_wrapper label,
        .dataTables_wrapper input,
        .dataTables_wrapper select {
            color: #fff;
        }

        table.dataTable {
            background: #1c1c1c;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            color: white !important;
        }

        .dataTables_wrapper .dataTables_filter input {
            background: #222;
            border: 1px solid #333;
            color: #fff;
        }
    </style>

</head>