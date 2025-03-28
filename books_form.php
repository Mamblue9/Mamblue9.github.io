<?php
require 'mysql/config.php';

$bkin = (isset($_GET['bkin'])) ? $_GET['bkin'] : date("Y-m-d");
$bkout = (isset($_GET['bkout'])) ? $_GET['bkout'] : date("Y-m-d");
$bkcust = (isset($_GET['bkcust'])) ? $_GET['bkcust'] : "";
$bktel = (isset($_GET['bktel'])) ? $_GET['bktel'] : "";
$q = (int)(isset($_GET['q'])) ? (int)$_GET['q'] : 0;

$days = (int)date_diff(date_create($bkin), date_create($bkout))->format('%R%a');

if ($days < 1) {
    echo "<script>
            alert('กรุณาเลือกวันเข้าพักให้ถูกต้อง');
            window.location.replace('books_range.php');
          </script>";
    exit();
}

if (isset($_GET['rmid'])) {
    $rmid = $_GET['rmid'];
    $bkstatus = 0;
    require 'books_status.php';
}

if($q > 0){
    $kw = " AND roomtype.rmtype='$q'";
}else{
    $kw = "";
}
?>

<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>ระบบจองห้องประชุม</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #0066cc;
            color: white;
            text-align: center;
            padding: 20px 0;
        }

        h1 {
            margin: 0;
        }

        .container {
            width: 60%;
            margin: 30px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 16px;
            margin-bottom: 5px;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-group button {
            padding: 10px 20px;
            background-color: #0066cc;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        .form-group button:hover {
            background-color: #004f99;
        }

        .form-group a {
            display: block;
            text-align: center;
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            font-size: 16px;
            text-decoration: none;
            border-radius: 4px;
        }

        .form-group a:hover {
            background-color: #218838;
        }

        select {
            height: 150px;
        }

        .form-container {
            margin-top: 20px;
        }

        .form-container a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ff7f50;
            color: white;
            font-size: 16px;
            text-decoration: none;
            border-radius: 4px;
            text-align: center;
        }

        .form-container a:hover {
            background-color: #ff5c00;
        }

    </style>
</head>

<body>
    <header>
        <h1>ระบบจองห้องประชุม</h1>
    </header>

    <div class="container">
        <!-- ฟอร์มค้นหา -->
        <form action="books_form.php" method="GET">
            <div class="form-group">
                <label for="bkin">เข้าพักวันที่</label>
                <input type="date" name="bkin" value="<?php echo $bkin; ?>" readonly />
            </div>

            <div class="form-group">
                <label for="bkout">ถึงวันที่</label>
                <input type="date" name="bkout" value="<?php echo $bkout; ?>" readonly />
            </div>

            <div class="form-group">
                <input type="hidden" name="bkcust" value="<?php echo $bkcust; ?>" />
                <input type="hidden" name="bktel" value="<?php echo $bktel; ?>" />
                <label for="q">ประเภทห้อง</label>
                <select name="q" id="q">
                    <option value="0">ทั้งหมด</option>
                    <?php
                    $sql = "SELECT * FROM roomtype ORDER BY rmtype ASC";
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                    ?>
                        <option value="<?php echo $row['rmtype']; ?>" <?php echo ($q == $row['rmtype']) ? 'selected' : ''; ?>>
                            <?php echo $row['tpname']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group">
                <button type="submit">ค้นหา</button>
            </div>
        </form>

        <!-- ฟอร์มจองห้อง -->
        <div class="form-container">
            <form action="books_insert.php" method="POST">
                <input type="hidden" name="bkin" value="<?php echo $bkin; ?>" />
                <input type="hidden" name="bkout" value="<?php echo $bkout; ?>" />

                <div class="form-group">
                    <label for="bkcust">ผู้จอง</label>
                    <input type="text" name="bkcust" value="<?php echo $bkcust; ?>" required />
                </div>

                <div class="form-group">
                    <label for="bktel">โทรศัพท์</label>
                    <input type="text" name="bktel" value="<?php echo $bktel; ?>" required />
                </div>

                <div class="form-group">
                    <label for="rmid">เลือกห้อง</label>
                    <select name="rmid" size="10" required>
                        <?php
                        $sql = "SELECT * FROM rooms 
                                LEFT JOIN roomtype ON rooms.rmtype = roomtype.rmtype 
                                WHERE rmid NOT IN (
                                    SELECT rmid FROM books WHERE bkstatus > 0
                                    AND ((bkin >= '$bkin' AND bkin < '$bkout') 
                                    OR (bkin < '$bkin' AND bkout > '$bkin'))
                                )" . $kw;
                        $result = $conn->query($sql);
                        while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                        ?>
                            <option value="<?php echo $row['rmid']; ?>">
                                <?php echo $row['rmid']; ?>&nbsp;
                                <?php echo $row['tpname']; ?>&nbsp;
                                <?php echo number_format($row['rmprice'], 0); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <button type="submit">บันทึก</button>
                </div>
            </form>
        </div>

        <!-- ลิงก์ย้อนกลับ -->
        <div class="form-container">
            <?php require 'books_room.php';?><br />
            <a href="books_list.php">ย้อนกลับ</a>
        </div>

    </div>

    <script>
        document.getElementById('q').value = "<?php echo $q; ?>";
    </script>

</body>

</html>
