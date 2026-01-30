<?php
session_start();
require "backend/db.php";

if (!isset($_SESSION["user_id"])) {
  header("Location: login.html");
  exit;
}

$userId = $_SESSION["user_id"];

$stmt = $conn->prepare(
  "SELECT * FROM documents WHERE user_id = ? ORDER BY uploaded_at DESC"
);
$stmt->bind_param("i", $userId);
$stmt->execute();
$docs = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <title>Dashboard | DocVault</title>
  <link rel="stylesheet" href="style.css" />

 <style>
  /* Reset & base */
  * {
    box-sizing: border-box;
  }
  body {
    margin: 0;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen,
      Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    background: #f0f4f8;
    color: #344054;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
  }

  /* Topbar */
  .topbar {
    height: 64px;
    background: #fff;
    box-shadow: 0 2px 6px rgb(0 0 0 / 0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 32px;
    position: sticky;
    top: 0;
    z-index: 10;
  }

  .logo {
    font-weight: 700;
    font-size: 1.5rem;
    color: #0b69d7;
    user-select: none;
  }

  .user {
    display: flex;
    align-items: center;
    font-size: 0.9rem;
    color: #4b5563;
  }
  .user > a {
    margin-left: 16px;
    color: #ef4444;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s;
  }
  .user > a:hover {
    color: #b91c1c;
  }
  .user > span {
    margin-left: 8px;
  }

  /* Layout */
  .dashboard {
    display: flex;
    min-height: calc(100vh - 64px);
  }

  /* Sidebar */
  .sidebar {
    width: 220px;
    background: #fff;
    padding: 32px 20px;
    box-shadow: 2px 0 6px rgb(0 0 0 / 0.05);
    display: flex;
    flex-direction: column;
  }

  .sidebar a {
    display: flex;
    align-items: center;
    padding: 14px 16px;
    margin-bottom: 12px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.95rem;
    color: #475569;
    text-decoration: none;
    user-select: none;
    transition: background-color 0.3s, color 0.3s;
  }

  .sidebar a:hover,
  .sidebar a.active {
    background: #e0f2fe;
    color: #0284c7;
  }

  .sidebar a svg {
    margin-right: 10px;
    flex-shrink: 0;
  }

  /* Content */
  .content {
    flex-grow: 1;
    padding: 32px 40px;
    overflow-y: auto;
  }

  .content h2 {
    font-weight: 700;
    font-size: 1.75rem;
    margin-bottom: 28px;
    color: #1e293b;
  }

  /* Document Cards Container */
  .docs {
    display: grid;
    grid-template-columns: repeat(auto-fill,minmax(250px,1fr));
    gap: 24px;
  }

  /* Single Document Card */
  .doc {
    background: #fff;
    border-radius: 20px;
    padding: 20px 24px 28px;
    box-shadow: 0 8px 20px rgb(0 0 0 / 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: default;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }

  .doc:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgb(0 0 0 / 0.15);
  }

  .doc .icon {
    font-size: 40px;
    color: #0284c7;
    margin-bottom: 16px;
  }

  .doc h4 {
    margin: 0 0 8px;
    font-size: 1.1rem;
    font-weight: 700;
    color: #0f172a;
    word-break: break-word;
  }

  .doc small {
    color: #64748b;
    font-size: 0.85rem;
    margin-bottom: 16px;
    display: block;
  }

  .doc a {
    align-self: flex-start;
    padding: 8px 20px;
    border-radius: 9999px;
    background: #0b69d7;
    color: white;
    font-weight: 600;
    font-size: 0.9rem;
    text-decoration: none;
    box-shadow: 0 4px 14px rgb(11 105 215 / 0.6);
    transition: background 0.3s ease;
  }
  .doc a:hover {
    background: linear-gradient(90deg, #094ca1, #20a798);
  }

  /* Empty State */
  .empty {
    padding: 80px 0;
    background: #fff;
    border-radius: 20px;
    text-align: center;
    font-size: 1.1rem;
    font-weight: 500;
    color: #94a3b8;
    box-shadow: 0 8px 24px rgb(0 0 0 / 0.05);
  }

  /* Responsive adjustments */
  @media (max-width: 768px) {
    .dashboard {
      flex-direction: column;
    }

    .sidebar {
      width: 100%;
      padding: 16px 24px;
      box-shadow: none;
      display: flex;
      flex-wrap: wrap;
      justify-content: space-around;
    }

    .sidebar a {
      margin-bottom: 8px;
      flex-grow: 1;
      text-align: center;
    }

    .content {
      padding: 24px 20px;
    }

    .docs {
      grid-template-columns: repeat(auto-fill,minmax(180px,1fr));
      gap: 16px;
    }
  }

  .dashboard {
  display: flex;
  min-height: calc(100vh - 64px);
  overflow-x: hidden; /* prevent horizontal scroll */
}

.sidebar {
  width: 220px;       /* fixed width sidebar */
  background: #fff;
  padding: 32px 20px;
  box-shadow: 2px 0 6px rgb(0 0 0 / 0.05);
  flex-shrink: 0;     /* prevent shrinking */
  position: sticky;   /* so it stays visible when scrolling */
  top: 64px;          /* just below topbar */
  height: calc(100vh - 64px);
  overflow-y: auto;   /* scroll inside sidebar if needed */
  z-index: 10;        /* keep sidebar on top */
}

.content {
  flex-grow: 1;       /* fill remaining space */
  padding: 32px 40px;
  overflow-y: auto;   /* scroll content if tall */
  min-width: 0;       /* important to prevent overflow */
}
.doc-buttons {
  margin-top: 12px;
  display: flex;
  gap: 8px;
}

.doc-buttons .btn {
  display: inline-block;
  padding: 8px 16px;
  border-radius: 999px;
  font-size: 13px;
  color: white;
  text-decoration: none;
  transition: background-color 0.3s ease;
  cursor: pointer;
  user-select: none;
}

/* Existing View button gradient */
.doc-buttons .btn.view {
  background: linear-gradient(90deg, #0b69d7, #2dd4bf);
}

/* Edit button style
.doc-buttons .btn.edit {
  background-color: #3b82f6; /* blue */
/* } */

/* .doc-buttons .btn.edit:hover {
  background-color: #2563eb;
} */

/* Delete button style */
/* .doc-buttons .btn.delete {
  background-color: #ef4444; /* red */
/* } */

/* .doc-buttons .btn.delete:hover { */
  /* background-color: #b91c1c;
} */

</style>

</head>
<body>

<header class="topbar">
  <div class="logo">DocVault</div>
  <div class="user">
    👤 <?php echo htmlspecialchars($_SESSION["user_name"]); ?>
    <a href="logout.php">Logout</a>
  </div>
</header>

<div class="dashboard">
  <aside class="sidebar">
    <a class="active">📄 My Documents</a>
    <a href="upload.html">⬆ Upload</a>
    <a href="#">⚙ Settings</a>
  </aside>

  <main class="content">
    <h2>My Uploaded Documents</h2>

    <?php if ($docs->num_rows === 0): ?>
      <div class="empty">No documents uploaded yet.</div>
    <?php else: ?>
      <div class="docs">
        <?php while ($d = $docs->fetch_assoc()): ?>
       <div class="doc">
  <div class="icon">📄</div>
  <h4><?php echo htmlspecialchars($d["file_name"]); ?></h4>
  <small><?php echo date("d M Y", strtotime($d["uploaded_at"])); ?></small>
  <div class="doc-buttons">
    <a href="<?php echo htmlspecialchars($d["file_path"]); ?>" target="_blank" rel="noopener noreferrer" class="btn view">View</a>
    <!-- <a href="edit.php?id=<?php echo $d['id']; ?>" class="btn edit">Edit</a> -->
    <a href="delete.php?id=<?php echo $d['id']; ?>" class="btn delete" onclick="return confirm('Are you sure you want to delete this document?');">Delete</a>
  </div>
</div>

        <?php endwhile; ?>
      </div>
    <?php endif; ?>
  </main>
</div>

</body>
</html>
