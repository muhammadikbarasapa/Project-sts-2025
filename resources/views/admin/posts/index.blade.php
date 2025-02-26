<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Posts</title>

    <!-- Tambahkan Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Load DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
</head>
<body class="bg-gray-100">

    <!-- Navbar -->
    <nav class="bg-white text-white p-4 flex justify-between items-center">
        <h1 class="text-lg font-semibold">All Posts</h1>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="bg-red-500 px-4 py-2 rounded hover:bg-red-700">
                Logout
            </button>
        </form>        
    </nav>

    <!-- Content -->
    <div class="container mx-auto mt-6 p-6 bg-white shadow-md rounded-lg">
        <table id="postsTable" class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr>
                    <th class="border px-4 py-2">#</th>
                    <th class="border px-4 py-2">User</th>
                    <th class="border px-4 py-2">Post</th>
                    <th class="border px-4 py-2">Photo</th>
                    <th class="border px-4 py-2">Created At</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <!-- Load jQuery & DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#postsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.posts.index') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'user_name', name: 'user_name' },
                    { data: 'body', name: 'body' },
                    { 
                        data: 'photo', 
                        name: 'photo', 
                        orderable: false, 
                        searchable: false,
                        render: function(data) {
                            return data 
                                ? `<img src="${data}" alt="Post Photo" class="w-16 h-16 object-cover rounded">` 
                                : '-';
                        }
                    },
                    { data: 'created_at', name: 'created_at' },
                ]
            });
        });
    </script>
</body>
</html>
