<?php
    class AutoTable{
        private $contents = [];
        private $keyIndex;
        
        
        function __construct(mysqli $conn, $tableName, $keyIndex)
        {
            $sql = "SELECT * from " . $tableName;
            if($stmt = $conn->prepare($sql)){
                $stmt->execute();
                $result = $stmt->get_result();
                if($result){
                    while($row = $result->fetch_array(MYSQLI_NUM)){
                        $this->contents[] = $row;
                    }
                }
                $stmt->close();
                $this->keyIndex = $keyIndex;
            }
        }

        function display(){
            echo "<form action='helloWorld.php' method='post'><table>";
            echo "<table>";
            foreach($this->contents as $row){
                echo "<tr>";
                foreach($row as $index => $entry){
                    if($index != $this->keyIndex)
                        echo "<td>" . $entry . "</td>";
                }
                echo "<td><button type='submit' name='deleteID' value = '". $row[$this->keyIndex] ."'>delete</button></td>";
                $this->echo_random_image();
                echo "</tr>";
            }
            echo "</table>";
            echo "</form>";
        }

        private function echo_random_image(){
            $seed = rand();
            echo '<td><img src="https://picsum.photos/100/75?nocache=' . $seed . '"/></td>';
        }
    }
?>