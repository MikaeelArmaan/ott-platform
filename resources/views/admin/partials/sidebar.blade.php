<div class="sidebar">

    <h2>OTT Admin</h2>

    <a href="/admin/dashboard">Dashboard</a>
    <a href="/admin/users">Users</a>
    <a href="/admin/roles">Roles</a>
    <a href="/admin/permissions">Permissions</a>
    <a href="/admin/contents">Contents</a>

    <form method="POST" action="/logout">
        @csrf
        <button class="logout-btn">Logout</button>
    </form>

</div>