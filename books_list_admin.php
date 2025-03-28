<!DOCTYPE html>
<?php
require 'mysql/config.php';
require 'books_config.php';
$stdate = (isset($_GET['stdate'])) ? $_GET['stdate'] : date("Y-m-d");
$endate = (isset($_GET['endate'])) ? $_GET['endate'] : date("Y-m-d");

if (isset($_GET['rmid'])) {
    $rmid = $_GET['rmid'];
    $bkin = $_GET['bkin'];
    $bkstatus = $_GET['bkstatus'];
    require 'books_status.php';
}
?>

<html>

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
            width: 80%;
            margin: 30px auto;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        label {
            font-size: 16px;
            margin-right: 10px;
        }

        input[type="date"] {
            padding: 8px;
            margin: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            padding: 10px 20px;
            background-color: #0066cc;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #004f99;
        }

        a {
            color: #0066cc;
            text-decoration: none;
            margin-left: 20px;
        }

        a:hover {
            text-decoration: underline;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #0066cc;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #e9e9e9;
        }

        .status {
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
        }

        .status-1 {
            background-color: #ffcc00;
            color: black;
        }

        .status-2 {
            background-color: #28a745;
            color: white;
        }

        .status-0 {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>
    <header>
        <h1>ระบบจองห้องประชุม</h1>
    </header>
<body>
    
    <form action="books_list.php" method="GET">
        <label>เลือกดูวันที่</label>
        <input type="date" name="stdate" value="<?php echo $stdate; ?>" required />
        <label>ถึง</label>
        <input type="date" name="endate" value="<?php echo $endate; ?>" required /><br />
        <button type="submit">ค้นหา</button>
        <a href="books_list_admin.php">วันนี้</a>
    </form><br/>

    <table border="1" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th>จัดการ</th>
                <th>เลขที่ห้อง</th>
                <th>ประเภท</th>
                <th>ผู้จอง</th>
                <th>โทรศัพท์</th>
                <th>วันเข้า</th>
                <th>วันออก</th>
                <th>จำนวนวัน</th>
                <th>ราคา</th>
                <th>สถานะ</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT * FROM books
                    LEFT JOIN rooms ON books.rmid = rooms.rmid 
                    LEFT JOIN roomtype ON rooms.rmtype = roomtype.rmtype 
                    WHERE books.bkin BETWEEN '$stdate' AND '$endate' 
                    AND books.bkstatus >=0
                    ORDER BY books.bkin ASC, books.bkcust ASC, books.rmid ASC ";

            $result = $conn->query($sql);

            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $days = (int) date_diff(date_create($row['bkin']), date_create($row['bkout']))->format('%a');
                $sumprice=$days * $row['rmprice'];

            ?>
                <tr>
                    <td>
                        <?php if($row['bkstatus']==1){?>
                        <a href="javascript:bookstatus('<?php echo $row['rmid']; ?>','<?php echo $row['bkin']; ?>','0')">ยกเลิก</a>
                        <a href="javascript:bookstatus('<?php echo $row['rmid']; ?>','<?php echo $row['bkin']; ?>','2')">กำลังประชุม</a>
                        <?php } ?>
                    </td>
                    <td><?php echo $row['rmid']; ?></td>
                    <td><?php echo $row['tpname']; ?></td>
                    <td><?php echo $row['bkcust']; ?></td>
                    <td><?php echo $row['bktel']; ?></td>
                    <td><?php echo date_format(date_create($row['bkin']), "d/m/Y"); ?></td>
                    <td><?php echo date_format(date_create($row['bkout']), "d/m/Y"); ?></td>
                    <td align="right"><?php echo $days; ?></td>
                    <td align="right"><?php echo number_format($sumprice, 0); ?></td>
                    <td><?php echo $bookstatus[$row['bkstatus']]; ?></td>

                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
    <a href="logout.php" class="btn btn-danger">Logout</a>
    <script>
        var vurl = "books_list_admin.php?stdate=<?php echo $stdate; ?>&endate=<?php echo $endate; ?>";
        function bookstatus(v1,v2,v3) {
            var v4 = vurl += "&rmid=" + v1 + "&bkin=" + v2 + "&bkstatus=" + v3;
            window.location.replace(v4);
        }
    </script>
</body>

</html>
