<?php

function getParcelTrackingData($conn, $trackingId)
{
    $stmt = $conn->prepare("
        SELECT
            p.*,
            s.First_name AS s_fname,
            r.First_name AS r_fname,
            ds.Status_type
        FROM parcel p
        JOIN sender s
            ON p.Sender_ID = s.Sender_ID
        JOIN reciever r
            ON p.Reciever_ID = r.Reciever_ID
        JOIN delivery_status ds
            ON p.Status_id = ds.Status_id
        WHERE p.Tracking_id = ?
    ");

    $stmt->bind_param("s", $trackingId);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }

    return null;
}
