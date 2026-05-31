<?php

function loginAdmin($conn, $user, $pass)
{
    $stmt = $conn->prepare("SELECT admin_pswd FROM admin WHERE admin_username = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        return $pass === $row['admin_pswd'];
    }
    return false;
}

function logoutAdmin()
{
    session_destroy();
}
