<?php
session_start();
include '../Partials/db_connection.php';

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['username'];

// Verify if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$page = ($page <= 0) ? 1 : $page;
$offset = ($page - 1) * $limit;

// Search functionality
$search_query = '';
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $search_query = "AND (thread_title LIKE '%$search%' OR thread_user_name LIKE '%$search%')";
}

// Query to get threads with pagination and search
$query = "SELECT thread.*, category.category_name 
          FROM thread 
          INNER JOIN category ON thread.thread_cat_id = category.category_id
          WHERE thread_user_name = '$user_name' $search_query
          ORDER BY time DESC 
          LIMIT $offset, $limit";
          
$result = mysqli_query($conn, $query);

// Get total number of questions for pagination
$total_query = "SELECT COUNT(*) FROM thread WHERE thread_user_name = '$user_name' $search_query";
$total_result = $conn->query($total_query);
$total_threads = $total_result->fetch_row()[0];
$total_pages = ceil($total_threads / $limit);

// Handle thread deletion
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);

    // Delete associated comments first
    $delete_comments_query = "DELETE FROM comments WHERE thread_comment_id = ?";
    $stmt_comments = $conn->prepare($delete_comments_query);
    $stmt_comments->bind_param("i", $delete_id);
    $stmt_comments->execute();
    $stmt_comments->close();

    // Delete the thread
    $delete_query = "DELETE FROM thread WHERE thread_id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    header('Location: manage_posted_question.php' . (isset($_GET['search']) ? '?search=' . urlencode($_GET['search']) : ''));
    exit();
}

// Handle thread editing
if (isset($_POST['edit_thread'])) {
    $thread_id = $_POST['thread_id'];
    $new_title = mysqli_real_escape_string($conn, $_POST['thread_title']);
    $update_query = "UPDATE thread SET thread_title = ? WHERE thread_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("si", $new_title, $thread_id);
    $stmt->execute();
    $stmt->close();
    header('Location: manage_posted_question.php' . (isset($_GET['search']) ? '?search=' . urlencode($_GET['search']) : ''));
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Posted Questions</title>
    <link rel="icon" type="image/jpg" href="/PHP/Forum_website1/images/favicon1.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
             background-color: #f4f4f9;
             display: flex;
             flex-direction: column;
             min-height: 100vh;
                
        }

        .container1 {
            margin-top: 80px;
        }

        .btn {
            padding: 8px 15px;
        }

        .search-bar {
            margin-bottom: 20px;
        }

        .table-responsive {
            margin-bottom: 20px;
        }

        /* To ensure the page works well on small screens */
        @media (max-width: 768px) {
            .table th,
            .table td {
                padding: 10px;
            }

            .btn {
                padding: 6px 12px;
            }

            .search-bar input {
                width: 100%;
            }
        }

        /* Style for the scrollable td */
        .table-responsive tbody td {
            max-height: 150px;
            /* Adjust max-height as needed */
            overflow-y: auto;
            display: block;
            /* Make td a block-level element */
            padding: 10px;
            word-break: break-word;
            /* Allow long words to break */
            white-space: normal;
            /* Allow text to wrap normally */
        }

        .table th {
            text-align: center;
        }

        .table td {
            white-space: normal;
            word-break: break-word;
        }

        .comment-box {
            background-color: #f0f0f0;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
        }

        .comment-username {
            font-weight: bold;
            color: #007bff;
        }
        .footer{
            margin-top: auto;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="user_profile.php">User Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="user_profile.php">Back to Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../Partials/_handle_logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container  container1">
        <button class=" button rounded py-2 px-3 mt-2 text-white bg-dark border-0"> <a class="text-white text-decoration-none"
                href="user_profile.php">Back to Dashboard</a></button>
    </div>
    <!-- Main Content -->
    <div class="container mt-3">
        <h2>Manage Posted Questions</h2>

        <!-- Search Form -->
        <form class="search-bar" method="GET" action="manage_posted_question.php">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search by question"
                    value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>

        <?php if (isset($_GET['search']) && !empty($_GET['search'])): ?>
            <a href="manage_posted_question.php" class="btn btn-secondary mb-3">Back to All Questions</a>
        <?php endif; ?>

        <!-- Table of Questions -->
        <div class="table-responsive ">
            <table class="table table-bordered table-hover ">
                <tbody>
                    <?php
                    $serial = $offset + 1;
                    while ($thread = $result->fetch_assoc()): ?>
                        <tr class="">
                        <td class="bg-secondary fw-bold d-flex align-items-center justify-content-between px-3 mt-4" style="min-width: 100px;">
                            <span ><?= $serial++; ?></span>
                            <span class="text-wrap text-center">
                                Category - <?= htmlspecialchars($thread['category_name']); ?>
                            </span>
                        </td>

                            <td class="bg-warning-subtle px-3 text-start text-wrap" style="min-width: 200px;">
                                <span class="fw-bold text-danger"> Question: </span>
                                <?= htmlspecialchars($thread['thread_title']); ?>
                            </td>
                            <td class="bg-light text-muted text-center" style="min-width: 120px;">
                                <?= htmlspecialchars($thread['time']); ?>
                            </td>
                            <td class="w-auto text-center" style="min-width: 150px;">
                                <!-- Edit Button -->
                                <button class="btn btn-warning btn-sm mt-1 px-3" data-bs-toggle="modal"
                                    data-bs-target="#editThreadModal<?= $thread['thread_id']; ?>">
                                    ✏️ Edit
                                </button>
                                <!-- Delete Button -->
                                <a href="manage_posted_question.php?delete_id=<?= $thread['thread_id']; ?><?= (isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''); ?>"
                                    class="btn btn-danger btn-sm mt-1 px-3"
                                    onclick="return confirm('Are you sure you want to delete this question and its comments?');">
                                    ❌ Delete
                                </a>
                            </td>
                        </tr>

                        <!-- Edit Thread Modal -->
                        <div class="modal fade" id="editThreadModal<?= $thread['thread_id']; ?>" tabindex="-1"
                            aria-labelledby="editThreadModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editThreadModalLabel">Edit Question</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form
                                            action="manage_posted_question.php<?= (isset($_GET['search']) ? '?search=' . urlencode($_GET['search']) : '') ?>"
                                            method="POST">
                                            <div class="mb-3">
                                                <label for="thread_title" class="form-label">Question</label>
                                                <textarea class="form-control" name="thread_title" id="thread_title"
                                                    rows="4"><?= htmlspecialchars($thread['thread_title']); ?></textarea>
                                            </div>
                                            <input type="hidden" name="thread_id" value="<?= $thread['thread_id']; ?>">
                                            <button type="submit" name="edit_thread" class="btn btn-primary">Save
                                                Changes</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                <!-- Comment Section -->
                        <tr>
                            <td colspan="5">
                                <?php
                                $comment_query = "SELECT comments.*, users.user_name
                                                    FROM comments
                                                    INNER JOIN users ON comments.user_name = users.user_name
                                                    WHERE comments.thread_comment_id = " . $thread['thread_id'];
                                $comment_result = mysqli_query($conn, $comment_query);


                                if ($comment_result && mysqli_num_rows($comment_result) > 0) {
                                    while ($comment = mysqli_fetch_assoc($comment_result)) {

                                        ?>
                                        <div class="comment-box">
                                            <p><span
                                                    class="comment-username"><?= htmlspecialchars($comment['user_name']); ?>:</span>
                                                <?= htmlspecialchars($comment['comment']); ?></p>
                                        </div>
                                        <?php

                                    }
                                } else {
                                    echo '<p class="text-center fst-italic">No answers yet.</p>';
                                }
                                ?>
                            </td>
                        </tr>

                    <?php endwhile; ?>
                </tbody>
            </table>
         </div>

        <!-- Pagination -->
        <nav>
            <ul class="pagination justify-content-center">
                <li class="page-item <?= $page <= 1 ? 'disabled' : ''; ?>">
                    <a class="page-link"
                        href="manage_posted_question.php?page=<?= max($page - 1, 1); ?><?= (isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''); ?>">Previous</a>
                </li>
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= $i == $page ? 'active' : ''; ?>">
                        <a class="page-link"
                            href="manage_posted_question.php?page=<?= $i; ?><?= (isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''); ?>"><?= $i; ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= $page >= $total_pages ? 'disabled' : ''; ?>">
                    <a class="page-link"
                        href="manage_posted_question.php?page=<?= min($page + 1, $total_pages); ?><?= (isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''); ?>">Next</a>
                </li>
            </ul>
        </nav>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <div class="footer">
        <?php include '../Partials/_footer.php'; ?>
    </div>
</body>

</html>