<!DOCTYPE html>
<html>
<head>
    <title>OTT Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body{
            margin:0;
            font-family:Arial;
            background:#0f0f0f;
            color:#fff;
            display:flex;
        }
        .sidebar{
            width:220px;
            background:#141414;
            min-height:100vh;
            padding:20px;
        }
        .sidebar h2{
            color:#e50914;
        }
        .sidebar a{
            display:block;
            color:#fff;
            text-decoration:none;
            padding:10px 0;
        }
        .sidebar a:hover{
            color:#e50914;
        }
        .content{
            flex:1;
            padding:30px;
        }
        .card{
            background:#1c1c1c;
            padding:20px;
            border-radius:6px;
            margin-bottom:20px;
        }
        button{
            background:#e50914;
            border:none;
            color:#fff;
            padding:8px 14px;
            cursor:pointer;
        }
        input,select{
            padding:6px;
            margin:5px 0;
        }
    </style>
</head>

<body>

<div class="sidebar">
    <h2>OTT Admin</h2>

    <a href="/admin/dashboard">Dashboard</a>
    <a href="/admin/users">Users</a>
    <a href="/admin/roles">Roles</a>
    <a href="/admin/permissions">Permissions</a>
    <a href="/admin/contents">Contents</a>

    <form method="POST" action="/logout">
        @csrf
        <button style="margin-top:20px;width:100%">Logout</button>
    </form>
</div>

<div class="content">
    @yield('content')
</div>

</body>
</html>
