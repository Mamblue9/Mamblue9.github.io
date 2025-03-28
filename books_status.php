<?php
$sql = "UPDATE books SET bkstatus='$bkstatus'WHERE rmid='$rmid'AND bkin='$bkin' AND bkstatus='1'";
$result = $conn->query($sql);
if ($result == 1) {
    $msg = "การดำเนินการเสร็จสิ้น";
} else {
    $msg = "การดำเนินการผิดพลาด";
}
?>
<script>
    alert('<?php echo $msg; ?>');
</script>