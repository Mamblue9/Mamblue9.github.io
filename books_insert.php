<!DOCTYPE html>
<?php
require 'mysql/config.php';

$bkin = $_POST['bkin'];
$bkout = $_POST['bkout'];
$bkcust = $_POST['bkcust'];
$bktel = $_POST['bktel'];

if (isset($_POST['rmid'])) {
    $rmid = $_POST['rmid'];
    $sql = "SELECT COUNT(rmid) AS countid FROM books 
            WHERE rmid = '$rmid' AND bkstatus > 0
            AND (
                (bkin >= '$bkin' AND bkin < '$bkout') 
                OR (bkin < '$bkin' AND bkout > '$bkin')
            )";

    $result = $conn->query($sql);
    $row = $result->fetch_array(MYSQLI_ASSOC);
    $countid = (int) $row['countid'];

    if ($countid < 1) {
        $sql = "INSERT INTO books (bkdate, rmid, bkin, bkout, bkcust, bktel, bkstatus) 
                VALUES (NOW(), '$rmid', '$bkin', '$bkout', '$bkcust', '$bktel', '1')";

        $result = $conn->query($sql);
        $v1 = ($result == true) ? 1 : 0;
    } else {
        $v1 = 0; 
    }
} else {
    $v1 = 0; 
}
?>

<html>
<head>
    <meta charset="UTF-8">
    <title>ระบบจองห้องประชุม</title>
</head>
<body>
    <script>
        var v1 = <?php echo $v1; ?>;
        var vurl = "books_form.php?bkin=<?php echo $bkin; ?>";
        vurl += "&bkout=<?php echo $bkout; ?>";
        vurl += "&bkcust=<?php echo $bkcust; ?>";
        vurl += "&bktel=<?php echo $bktel; ?>";

        if (v1 == 1) {
            alert("ดำเนินการเสร็จสิ้น");
        } else {
            alert("ดำเนินการผิดพลาด หรือห้องไม่ว่าง");
        }
        window.location.replace(vurl);
    </script>
</body>
</html>
