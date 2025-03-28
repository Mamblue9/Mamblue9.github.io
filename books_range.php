<?php $nowdate = date("Y-m-d"); ?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>ระบบจองห้องพัก</title>
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
            width: 50%;
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

        .form-group input {
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

        .error-message {
            color: #dc3545;
            font-size: 14px;
            margin-top: 10px;
            text-align: center;
        }

    </style>
    <script>
        function validateForm() {
            var bkin = document.getElementById("bkin").value;
            var bkout = document.getElementById("bkout").value;
            if (bkout < bkin) {
                alert("วันออกต้องไม่น้อยกว่าวันเข้าพัก");
                return false;
            }
            return true;
        }
    </script>
</head>

<body>
    <header>
        <h1>ระบบจองห้องพัก</h1>
    </header>

    <div class="container">
        <form action="books_form.php" method="GET" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="bkin">เข้าพักวันที่</label>
                <input type="date" id="bkin" name="bkin" value="<?php echo $nowdate; ?>" required />
            </div>
            <div class="form-group">
                <label for="bkout">ถึงวันที่</label>
                <input type="date" id="bkout" name="bkout" value="<?php echo $nowdate; ?>" required />
            </div>
            <div class="form-group">
                <button type="submit">ตกลง</button>
            </div>
            <div class="form-group">
                <a href="books_list.php">ย้อนกลับ</a>
            </div>
        </form>
    </div>

</body>

</html>
