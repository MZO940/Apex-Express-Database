<?php

// ---------------- BRANCH ----------------
function addBranch($conn, $data)
{
    $stmt = $conn->prepare(
        "INSERT INTO branch (Branch_ID, Name, Email, Street_no, Area, City)
         VALUES (?, ?, ?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "ssssss",
        $data['branch_id'],
        $data['name'],
        $data['email'],
        $data['street'],
        $data['area'],
        $data['city']
    );

    $ok = $stmt->execute();

    if (!empty($data['phone'])) {
        $stmt2 = $conn->prepare(
            "INSERT INTO branch_phone (Branch_ID, Phone_no) VALUES (?, ?)"
        );
        $stmt2->bind_param("ss", $data['branch_id'], $data['phone']);
        $stmt2->execute();
    }

    return $ok;
}

// ---------------- RIDER ----------------
function addRider($conn, $data)
{
    $stmt = $conn->prepare(
        "INSERT INTO rider (Rider_ID, First_name, Last_name, Cnic_no, Branch_ID)
         VALUES (?, ?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "sssss",
        $data['rider_id'],
        $data['first_name'],
        $data['last_name'],
        $data['cnic'],
        $data['branch_id']
    );

    $ok = $stmt->execute();

    if (!empty($data['phone'])) {
        $stmt2 = $conn->prepare(
            "INSERT INTO rider_phone (Rider_ID, Phone_no) VALUES (?, ?)"
        );
        $stmt2->bind_param("ss", $data['rider_id'], $data['phone']);
        $stmt2->execute();
    }

    return $ok;
}

// ---------------- PARCEL (FIXED) ----------------
function createParcel($conn, $data)
{
    mysqli_begin_transaction($conn);

    try {

        // ---------------- Sender ----------------
        $stmt = $conn->prepare(
            "INSERT INTO sender
            (Sender_ID, First_name, Last_name, Email, Street_no, Area, City)
            VALUES (?, ?, ?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "sssssss",
            $data['sender_id'],
            $data['sender_fname'],
            $data['sender_lname'],
            $data['sender_email'],
            $data['sender_street'],
            $data['sender_area'],
            $data['sender_city']
        );
        $stmt->execute();

        $stmt = $conn->prepare(
            "INSERT INTO sender_phone (Sender_ID, Phone_no)
             VALUES (?, ?)"
        );
        $stmt->bind_param("ss", $data['sender_id'], $data['sender_phone']);
        $stmt->execute();

        // ---------------- Receiver ----------------
        $stmt = $conn->prepare(
            "INSERT INTO reciever
            (Reciever_ID, First_name, Last_name, Email, Postal_code, Street_no, Area, City)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "ssssssss",
            $data['reciever_id'],
            $data['receiver_fname'],
            $data['receiver_lname'],
            $data['receiver_email'],
            $data['receiver_zip'],
            $data['receiver_street'],
            $data['receiver_area'],
            $data['receiver_city']
        );
        $stmt->execute();

        $stmt = $conn->prepare(
            "INSERT INTO reciever_phone (Reciever_ID, Phone_no)
             VALUES (?, ?)"
        );
        $stmt->bind_param("ss", $data['reciever_id'], $data['receiver_phone']);
        $stmt->execute();

        // ---------------- Status ----------------
        $stmt = $conn->prepare(
            "INSERT INTO delivery_status (Status_id, Status_type, Remarks, Attempts)
             VALUES (?, ?, ?, 0)"
        );

        $stmt->bind_param(
            "sss",
            $data['status_id'],
            $data['status_type'],
            $data['remarks']
        );
        $stmt->execute();

        // ---------------- Parcel (FIXED bind types) ----------------
        $stmt = $conn->prepare(
            "INSERT INTO parcel
            (Tracking_id, Weight, Origin_city, Destination_city,
             Sender_ID, Reciever_ID, Branch_ID, Rider_ID,
             Status_id, price, payment_option)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $rider = !empty($data['rider_id']) ? $data['rider_id'] : null;

        $stmt->bind_param(
            "sdsssssssss",
            $data['tracking_id'],
            $data['weight'],
            $data['sender_city'],
            $data['receiver_city'],
            $data['sender_id'],
            $data['reciever_id'],
            $data['branch_id'],
            $rider,
            $data['status_id'],
            $data['price'],
            $data['payment_option']
        );

        $stmt->execute();

        mysqli_commit($conn);
        return true;
    } catch (Throwable $e) {

        mysqli_rollback($conn);
        error_log("Parcel insert failed: " . $e->getMessage());
        return false;
    }
}

#---------------- UTILITY ----------------
function renderTable($conn, $tableName)
{
    $res = $conn->query("SELECT * FROM `$tableName`");

    echo "<h3 class='table-section-divider-title'>" .
        strtoupper($tableName) .
        " DATA</h3>";

    if ($res && $res->num_rows > 0) {

        echo "<div class='table-alignment-box'>";
        echo "<table class='brand-data-table'>";
        echo "<thead><tr>";

        while ($field = $res->fetch_field()) {
            echo "<th>{$field->name}</th>";
        }

        echo "</tr></thead>";
        echo "<tbody>";

        while ($row = $res->fetch_assoc()) {

            echo "<tr>";

            foreach ($row as $value) {
                echo "<td>" .
                    htmlspecialchars($value ?? 'NULL') .
                    "</td>";
            }

            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
        echo "</div>";
    } else {

        echo "<p class='no-records-banner'>
                No active entries located in table: {$tableName}
              </p>";
    }
}

#---------------- SHOWS ALERTS ----------------
function showAlert($message)
{
    echo "<script>alert('" .
        addslashes($message) .
        "');</script>";
}
