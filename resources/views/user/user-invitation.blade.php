@include('user.user-dashboard-base')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<div class="content-wrapper py-5">
    <style>
        .changewhite:hover {
            color: white;
            transition: color 0.3s ease;
        }
        .table-wrapper {
            padding: 25px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        .rounded-circle {
            object-fit: cover;
            border: 2px solid #e9ecef;
            transition: transform 0.3s ease;
        }
        .rounded-circle:hover {
            transform: scale(1.1);
        }
        .badge {
            font-size: 0.85rem;
            padding: 0.5em 0.8em;
            font-weight: 500;
        }
        .btn {
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .table thead th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9rem;
        }
        .table tbody tr {
            transition: background-color 0.3s ease;
        }
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        .search-container {
            position: relative;
        }
        .search-input {
            padding-right: 40px;
            border-radius: 20px;
        }
        .action-buttons .btn {
            margin-right: 8px;
        }
    </style>
</head>
<body>

<div class=" bg-white mx-3">
    <div class="d-flex justify-content-between align-items-center mb-4 px-3">
        <h2 class="fw-bold">My Contacts</h2>
        <div class="search-container">
            <input type="text" class="form-control search-input" placeholder="Search contacts...">
            <i class="fas fa-search" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%);"></i>
        </div>
    </div>

    <div class="action-buttons mb-4 px-3">
        <button class="btn btn-outline-primary">
            <i class="fas fa-list me-2"></i>All Contacts
        </button>
        <button class="btn btn-outline-warning">
            <i class="fas fa-star me-2"></i>Favorites
        </button>
        <button class="btn btn-outline-success">
            <i class="fas fa-users me-2"></i>Groups
        </button>
        <button class="btn btn-outline-danger">
            <i class="fas fa-trash me-2"></i>Trash
        </button>
        <a href="/user/create-invitation" class="btn btn-primary">
            <i class="fas fa-user-plus me-2"></i>Add New Contact
        </a>
    </div>

    <div class="table-wrapper">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-info text-dark">
                    <tr>
                        <th>Contact Name</th>
                        <th>Email</th>
                        <th>Alternative Email</th>
                        <th>Phone No</th>
                        <th>Country</th>
                        <th>Image</th>
                        <th>Badge</th>
                        <!-- <th>Actions</th> -->
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fw-bold">John Doe</td>
                        <td>john@example.com</td>
                        <td>johndoe@gmail.com</td>
                        <td>+1234567890</td>
                        <td><i class="fas fa-globe-americas me-2"></i>USA</td>
                        <td><i class="fas fa-user-circle fa-2x"></i></td>
                        <td><span class="badge bg-success">Active</span></td>
                        <!-- <td>
                            <button class="btn btn-sm btn-info me-1" title="Edit"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-danger" title="Delete"><i class="fas fa-trash"></i></button>
                        </td> -->
                    </tr>
                    <tr>
                        <td class="fw-bold">Afone Mazi</td>
                        <td>socialafonete@omail.ai</td>
                        <td>socialafonete@gmail.com</td>
                        <td>N/A</td>
                        <td><i class="fas fa-globe-africa me-2"></i>Rwanda</td>
                        <td><i class="fas fa-user-circle fa-2x"></i></td>
                        <td><span class="badge bg-primary">New</span></td>
                        <!-- <td>
                            <button class="btn btn-sm btn-info me-1" title="Edit"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-danger" title="Delete"><i class="fas fa-trash"></i></button>
                        </td> -->
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>