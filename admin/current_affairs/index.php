<?php require_once "../components/session.php";?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Current Affairs Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../style/main.css">
    <link rel="stylesheet" href="../style/nav.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        .main-content {
            padding: var(--space-xxl);
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: var(--space-xl);
        }
        
        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-dark);
        }
        
        .action-buttons {
            display: flex;
            gap: var(--space-md);
        }
        
        .btn {
            padding: var(--space-sm) var(--space-md);
            border-radius: var(--btn-radius);
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: var(--space-xs);
        }
        
        .btn-primary {
            background-color: var(--accent-blue);
            color: white;
            border: none;
        }
        
        .btn-primary:hover {
            background-color: var(--accent-blue-dark);
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            background-color: var(--white);
            border-radius: var(--card-radius);
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        
        .data-table th, .data-table td {
            padding: var(--space-md);
            text-align: left;
            border-bottom: 1px solid var(--medium-gray);
        }
        
        .data-table th {
            background-color: var(--light-gray);
            font-weight: 600;
            color: var(--dark-gray);
            position: sticky;
            top: 0;
        }
        
        .data-table tr:last-child td {
            border-bottom: none;
        }
        
        .data-table tr:hover {
            background-color: var(--light-gray);
        }
        
        .action-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: var(--accent-blue);
            font-size: 1rem;
            padding: var(--space-xs);
            border-radius: 4px;
            transition: all 0.2s ease;
        }
        
        .action-btn:hover {
            background-color: var(--light-gray);
        }
        
        .action-btn.delete {
            color: var(--accent-red);
        }
        
        .swal2-input, .swal2-select, .swal2-textarea {
            margin: 0.5em 0;
            width: 100%;
        }
        
        .swal2-container {
            z-index: 2000;
        }
        
        .control-bar {
            background: var(--white, #fff);
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }
        .search-control:focus {
            border-color: var(--accent-blue, #007bff);
            outline: none;
            background: #f0f8ff;
        }
        @media (max-width: 600px) {
            .control-bar {
                flex-direction: column;
                align-items: stretch;
                padding: 1rem 0.5rem;
            }
            .search-control {
                width: 100% !important;
                min-width: 0 !important;
                max-width: 100% !important;
            }
        }
    </style>
</head>
<body>
    <?php require_once "../nav/index.php"?>
    
    <main class="main-content">
        <div class="header">
            <h1 class="page-title">Current Affairs Management</h1>
            <div class="action-buttons">
                <button class="btn btn-primary" id="addPositionBtn">
                    <i class="fas fa-plus"></i> Add Position
                </button>
            </div>
        </div>
        <!-- Search and Filter Controls -->
        <div class="control-bar" style="display: flex; gap: 1rem; margin-bottom: 2rem; align-items: center; flex-wrap: wrap;">
            <input type="text" id="searchInput" class="search-control" placeholder="Search by position or name..." style="min-width:220px;max-width:320px;flex:1; padding: 0.7em 1em; border: 1px solid var(--medium-gray); border-radius: 6px; font-size: 1rem; background: var(--light-gray); transition: border 0.2s;" />
            <select id="filterSelect" class="search-control" style="min-width:180px;max-width:260px; padding: 0.7em 1em; border: 1px solid var(--medium-gray); border-radius: 6px; font-size: 1rem; background: var(--light-gray);">
                <option value="">All Positions</option>
            </select>
        </div>
        <div class="table-responsive">
            <table class="data-table" id="positionsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Position</th>
                        <th>Name</th>
                        <th>From Date</th>
                        <th>To Date</th>
                        <th>Updated On</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Positions will be loaded here via JavaScript -->
                </tbody>
            </table>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Load positions when page loads
            loadPositions();
            
            // Add position button click handler
            document.getElementById('addPositionBtn').addEventListener('click', showAddPositionModal);

            const searchInput = document.getElementById('searchInput');
            const filterSelect = document.getElementById('filterSelect');
            let allPositions = []; // Store all positions for filtering
            
            // Function to load positions from server
            function loadPositions() {
                fetch('get_positions.php')
                    .then(response => response.json())
                    .then(data => {
                        allPositions = data; // Store for filtering
                        renderTable(data);
                        populateFilterOptions(data);
                    })
                    .catch(error => {
                        console.error('Error loading positions:', error);
                        Swal.fire({
                            title: 'Error',
                            text: 'Failed to load positions. Please try again.',
                            icon: 'error'
                        });
                    });
            }

            // Render table rows based on filtered data
            function renderTable(data) {
                const tableBody = document.querySelector('#positionsTable tbody');
                tableBody.innerHTML = '';
                data.forEach(position => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${position.id}</td>
                        <td>${position.position}</td>
                        <td>${position.name}</td>
                        <td>${position.from_date}</td>
                        <td>${position.to_date || 'NULL'}</td>
                        <td>${position.updated_on}</td>
                        <td>
                            <button class="action-btn edit" data-id="${position.id}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="action-btn delete" data-id="${position.id}">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
                
                // Add event listeners to edit buttons
                document.querySelectorAll('.action-btn.edit').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const positionId = this.getAttribute('data-id');
                        showEditPositionModal(positionId);
                    });
                });
                
                // Add event listeners to delete buttons
                document.querySelectorAll('.action-btn.delete').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const positionId = this.getAttribute('data-id');
                        confirmDeletePosition(positionId);
                    });
                });
            }

            // Populate filter dropdown with unique positions
            function populateFilterOptions(data) {
                const uniquePositions = [...new Set(data.map(pos => pos.position))];
                filterSelect.innerHTML = '<option value="">All Positions</option>';
                uniquePositions.forEach(position => {
                    const option = document.createElement('option');
                    option.value = position;
                    option.textContent = position;
                    filterSelect.appendChild(option);
                });

                // Add event listener for filter select
                filterSelect.addEventListener('change', () => {
                    const selectedPosition = filterSelect.value;
                    if (selectedPosition === '') {
                        renderTable(allPositions); // Show all positions
                    } else {
                        const filteredData = allPositions.filter(pos => pos.position === selectedPosition);
                        renderTable(filteredData);
                    }
                });
            }
            
            // Show modal for adding a new position
            function showAddPositionModal() {
                Swal.fire({
                    title: 'Add New Position',
                    html: `
                        <form id="positionForm">
                            <input type="text" id="position" class="swal2-input" placeholder="Position" required>
                            <input type="text" id="name" class="swal2-input" placeholder="Name" required>
                            <input type="date" id="from_date" class="swal2-input" placeholder="From Date" required>
                            <input type="date" id="to_date" class="swal2-input" placeholder="To Date (optional)">
                        </form>
                    `,
                    focusConfirm: false,
                    showCancelButton: true,
                    confirmButtonText: 'Add Position',
                    cancelButtonText: 'Cancel',
                    preConfirm: () => {
                        const position = document.getElementById('position').value;
                        const name = document.getElementById('name').value;
                        const fromDate = document.getElementById('from_date').value;
                        const toDate = document.getElementById('to_date').value;
                        
                        if (!position || !name || !fromDate) {
                            Swal.showValidationMessage('Please fill in all required fields');
                            return false;
                        }
                        
                        return { position, name, from_date: fromDate, to_date: toDate || null };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        addPosition(result.value);
                    }
                });
            }
            
            // Show modal for editing a position
            function showEditPositionModal(positionId) {
                // Fetch position details first
                fetch(`get_position.php?id=${positionId}`)
                    .then(response => response.json())
                    .then(position => {
                        Swal.fire({
                            title: 'Edit Position',
                            html: `
                                <form id="positionForm">
                                    <input type="text" id="position" class="swal2-input" placeholder="Position" value="${position.position}" required>
                                    <input type="text" id="name" class="swal2-input" placeholder="Name" value="${position.name}" required>
                                    <input type="date" id="from_date" class="swal2-input" placeholder="From Date" value="${position.from_date}" required>
                                    <input type="date" id="to_date" class="swal2-input" placeholder="To Date (optional)" value="${position.to_date || ''}">
                                </form>
                            `,
                            focusConfirm: false,
                            showCancelButton: true,
                            confirmButtonText: 'Update',
                            cancelButtonText: 'Cancel',
                            preConfirm: () => {
                                const position = document.getElementById('position').value;
                                const name = document.getElementById('name').value;
                                const fromDate = document.getElementById('from_date').value;
                                const toDate = document.getElementById('to_date').value;
                                
                                if (!position || !name || !fromDate) {
                                    Swal.showValidationMessage('Please fill in all required fields');
                                    return false;
                                }
                                
                                return { 
                                    id: positionId,
                                    position, 
                                    name, 
                                    from_date: fromDate, 
                                    to_date: toDate || null 
                                };
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                updatePosition(result.value);
                            }
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching position:', error);
                        Swal.fire({
                            title: 'Error',
                            text: 'Failed to load position details. Please try again.',
                            icon: 'error'
                        });
                    });
            }
            
            // Add a new position
            function addPosition(positionData) {
                fetch('add_position.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(positionData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Success',
                            text: 'Position added successfully',
                            icon: 'success'
                        });
                        loadPositions();
                    } else {
                        throw new Error(data.message || 'Failed to add position');
                    }
                })
                .catch(error => {
                    console.error('Error adding position:', error);
                    Swal.fire({
                        title: 'Error',
                        text: error.message,
                        icon: 'error'
                    });
                });
            }
            
            // Update an existing position
            function updatePosition(positionData) {
                fetch('update_position.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(positionData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Success',
                            text: 'Position updated successfully',
                            icon: 'success'
                        });
                        loadPositions();
                    } else {
                        throw new Error(data.message || 'Failed to update position');
                    }
                })
                .catch(error => {
                    console.error('Error updating position:', error);
                    Swal.fire({
                        title: 'Error',
                        text: error.message,
                        icon: 'error'
                    });
                });
            }
            
            // Confirm and delete a position
            function confirmDeletePosition(positionId) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        deletePosition(positionId);
                    }
                });
            }
            
            // Delete a position
            function deletePosition(positionId) {
                fetch(`delete_position.php?id=${positionId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Position has been deleted.',
                            icon: 'success'
                        });
                        loadPositions();
                    } else {
                        throw new Error(data.message || 'Failed to delete position');
                    }
                })
                .catch(error => {
                    console.error('Error deleting position:', error);
                    Swal.fire({
                        title: 'Error',
                        text: error.message,
                        icon: 'error'
                    });
                });
            }

            function filterTable() {
                const searchValue = searchInput.value.toLowerCase();
                const filterValue = filterSelect.value;
                const filtered = allPositions.filter(item => {
                    const matchesSearch = item.position.toLowerCase().includes(searchValue) || item.name.toLowerCase().includes(searchValue);
                    const matchesFilter = !filterValue || item.position === filterValue;
                    return matchesSearch && matchesFilter;
                });
                renderTable(filtered);
            }

            searchInput.addEventListener('input', filterTable);
            filterSelect.addEventListener('change', filterTable);
        });
    </script>
</body>
</html>