<?php
require 'mysql/config.php';

$bkin = isset($_GET['bkin']) ? $_GET['bkin'] : date("Y-m-d");
$bkout = isset($_GET['bkout']) ? $_GET['bkout'] : date("Y-m-d");
$bkcust = isset($_GET['bkcust']) ? $_GET['bkcust'] : "";
$bktel = isset($_GET['bktel']) ? $_GET['bktel'] : "";

$sql = "SELECT * FROM books 
        LEFT JOIN rooms ON books.rmid = rooms.rmid 
        LEFT JOIN roomtype ON rooms.rmtype = roomtype.rmtype 
        WHERE books.bkin = '$bkin' 
        AND books.bktel = '$bktel' 
        AND books.bkstatus = '1'";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>รายละเอียดการจองห้อง</title>
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
            padding: 10px 0;
        }

        h1 {
            margin: 0;
        }

        .container {
            width: 80%;
            margin: 30px auto;
        }

        .search-form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .search-form label {
            font-size: 16px;
            margin-right: 10px;
        }

        .search-form input {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 150px;
        }

        .search-form button {
            padding: 10px 20px;
            background-color: #0066cc;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-form button:hover {
            background-color: #004f99;
        }

        .search-form a {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            font-size: 16px;
            text-decoration: none;
            border-radius: 4px;
            margin-left: 10px;
        }

        .search-form a:hover {
            background-color: #218838;
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

        .action-link {
            color: #dc3545;
            text-decoration: none;
            font-weight: bold;
        }

        .action-link:hover {
            text-decoration: underline;
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

<body>
    <header>
        <h1>รายละเอียดการจองห้อง</h1>
    </header>

    <div class="container">


        <!-- ตารางรายละเอียดการจอง -->
        <table>
            <thead>
                <tr>
                    <th>ยกเลิก</th>
                    <th>เลขที่ห้อง</th>
                    <th>ประเภท</th>
                    <th>เข้าพัก</th>
                    <th>ออก</th>
                    <th>ราคา</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_array(MYSQLI_ASSOC)) { ?>
                    <tr>
                        <td><a href="javascript:bookcancel('<?php echo $row['rmid'];?>');"></a>ยกเลิก</td>
                        </td>
                        <td><?php echo $row['rmid']; ?></td>
                        <td><?php echo $row['tpname']; ?></td>
                        <td><?php echo date_format(date_create($row['bkin']), "d/m/Y"); ?></td>
                        <td><?php echo date_format(date_create($row['bkout']), "d/m/Y"); ?></td>
                        <td align="right"><?php echo number_format($row['rmprice'], 0); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script>
        function bookcancel(v1) {
            window.location.href = "books_form.php?bkin=<?php echo $bkin; ?>&bkout=<?php echo $bkout; ?>&bkcust=<?php echo $bkcust; ?>&bktel=<?php echo $bktel; ?>&rmid=" + v1;
        }
    </script>
</body>

</html>
