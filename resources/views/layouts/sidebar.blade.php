<div class="sidebar">
    <div class="sidebar-header">Library Management System</div>

    <a class="menu-item {{ request()->is('home') ? 'active' : '' }}" href="/home">🏠 Home Page</a>
    <a class="menu-item {{ request()->is('dashboard') ? 'active' : '' }}" href="/dashboard">📊 Dashboard</a>

    <div style="padding: 15px; font-size: 14px; color:#ccc;">Features</div>

    <a class="menu-item {{ request()->is('books') ? 'active' : '' }}" href="/books">📚 Manage Books</a>
    <a class="menu-item {{ request()->is('authors') ? 'active' : '' }}" href="/authors">📝 Manage Authors</a>
    <a class="menu-item {{ request()->is('students') ? 'active' : '' }}" href="/students">🎓 Manage Students</a>
    <a class="menu-item {{ request()->is('issues/create') ? 'active' : '' }}" href="/issues/create">📘 Issue Book</a>
    <a class="menu-item {{ request()->is('issues') ? 'active' : '' }}" href="/issues">📗 Return Book</a>
    <a class="menu-item {{ request()->is('issues/issued') ? 'active' : '' }}" href="/issues/issued">📄 Issued Books</a>
    <a class="menu-item {{ request()->is('issues/defaulters') ? 'active' : '' }}" href="/issues/defaulters">⚠ Defaulters</a>

    <a class="menu-item logout" href="/logout">🚪 Logout</a>
</div>
