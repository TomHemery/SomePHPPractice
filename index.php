<html style="background-color:black;">
    <head>
        <link rel="stylesheet" type="text/css" href="mainStyle.css">
        <title>PHP Fun</title>
        <h1>PHP Fun</h1>
    </head>
    <body>
        <form action ="helloWorld.php" method="post">
            FruitName<input type="text" name="fruitName"><br>
            QuantityAvailable<input type="number" name="quantity" value="0"><br>
            <input type="submit" value = "Add to DB">
        </form>

        <?php
            include "./table.php";

            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbName = "main";

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbName);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            if(isset($_POST["fruitName"]) && $_POST["fruitName"] != ""){
                $fruitName = $conn->real_escape_string(sanitize_string($_POST["fruitName"]));
                $quantity = $conn->real_escape_string(sanitize_string(isset($_POST["quantity"]) ? $_POST["quantity"] : 0));
                $sql = "INSERT INTO fruit (fruitID, fruitName, quantity) VALUES (NULL, ?, ?)";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param('si', $fruitName, $quantity);
                    $stmt->execute();
                }   

                unset($_POST["fruitName"]);
                unset($_POST["quantity"]);
            }

            else if(isset($_POST["deleteID"])){
                $delID = $_POST["deleteID"];
                $sql = "DELETE FROM fruit WHERE fruitID = ?";
                if($stmt = $conn->prepare($sql)){
                    $stmt->bind_param('i', $delID);
                    $stmt->execute();
                }
                unset($_POST["deleteID"]);
            }

            $table = new AutoTable($conn, "fruit", 0);
            $table->display();

            function sanitize_string($data){
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }

            function println($output) {
                $js_code = '<script>console.log(' . json_encode($output, JSON_HEX_TAG) . ');</script>';
                echo $js_code;
            }

        ?>
    </body>
</html>
