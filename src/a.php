<?php
require('connection.php');
$flag = 0;

if ($conn->connect_error) {
    die("connection Failed : " . $conn->connect_error);
} else {
    $pid = $_POST['patient_id'];
    $date_in = $_POST['date_in'];
    $patient_id = $_POST['patient_id']; // Retrieve the patient_id from the query string
}

$flag1 = 0;

// check if patient is already admitted
$already = $conn->prepare("SELECT admit_id FROM admit WHERE patient_id = ? AND status = 1");
$already->bind_param("i", $pid);
$already->execute();
$res = $already->get_result();
if ($res->num_rows > 0) {
    $flag1 = 1;
    echo "<div class=\"invalid\">";
    echo "<div>Patient already admitted <br> Please Checkout first.</div>";
}

if ($flag1 == 0) {
    try {
        // get available bed
        $stm = $conn->prepare("SELECT bed_id FROM beds WHERE status = 1 LIMIT 1");
        $stm->execute();
        $res = $stm->get_result()->fetch_assoc();

        if ($res) {
            $bed = $res['bed_id'];
        } else {
            echo "<div class=\"invalid\">No beds available at the moment.</div>";
            exit; // stop further execution
        }

        // insert admit record
        $stmt = $conn->prepare("INSERT INTO admit (patient_id, date_in, bed_id) VALUES (?,?,?)");
        $stmt->bind_param("ssi", $pid, $date_in, $bed);

        if ($stmt->execute()) {
            $aid = $conn->insert_id;
            echo "<div class=\"valid\">";
            echo "<div> Entry Successful </div>";
            echo "<div id=\"bed_store\">Bed Allocated - $bed</div>";
            echo "<div id=\"admit_store\" style=\"display:none;\">$aid</div></div>";

            $stm1 = $conn->prepare("UPDATE beds SET status = 0 WHERE bed_id = ?");
            $stm1->bind_param("i", $bed);
            $stm1->execute();

            $flag = 1;
        }
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == 1452) {
            echo "<div class=\"invalid\">No past Patient record found. Kindly register the new patient</div>";
            echo "<a href=\"patient_page.html\"><button >Register</button></a>";
        } else {
            echo "<div class=\"invalid\">Error in admitting ! $e</div>";
        }
    }

    // allocate 3 visiting badges
    if ($flag) {
        try {
            $stmt = $conn->prepare("SELECT admit_id FROM admit WHERE patient_id = ? AND status = 1");
            $stmt->bind_param("i", $pid);
            $stmt->execute();

            $result = $stmt->get_result()->fetch_assoc();
            $aid = $result['admit_id'];
            $stmt->close();

            $temp = 0;
            for ($i = 0; $i < 3; $i++) {
                $stmt1 = $conn->prepare("INSERT INTO badges (admit_id) VALUES (?)");
                $stmt1->bind_param("i", $aid);

                if ($stmt1->execute()) {
                    $temp = 1;
                } else {
                    $temp = 0;
                    break;
                }
            }
            $stmt1->close();
            $conn->close();
        } catch (mysqli_sql_exception $e) {
            echo "<div class=\"invalid\">Error in badge allocation! $e</div>";
        }
    }
}
